<?php

namespace App\Traits;

use App\Models\CartItem;
use App\Models\ConsultationService;
use App\Models\FreeConsultationSchedule;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait HandlesCartTransfer
{
    /**
     * Transfer temp cart data to a user after login/registration
     */
    protected function transferTempCartData($tempCartData, User $user)
    {
        if (!$tempCartData) {
            return;
        }

        try {
            $tempCartItems = is_array($tempCartData) ? $tempCartData : json_decode($tempCartData, true);
            
            if (!is_array($tempCartItems) || empty($tempCartItems)) {
                return;
            }

            foreach ($tempCartItems as $serviceId => $item) {
                try {
                    // Handle new free consultation system
                    if (isset($item['consultation_type']) && $item['consultation_type'] === 'free-consultation-new') {
                        $typeId = $item['free_consultation_type_id'];
                        $scheduleId = $item['free_consultation_schedule_id'];

                        $existingCartItem = CartItem::where('user_id', $user->id)
                            ->where('free_consultation_type_id', $typeId)
                            ->first();

                        if (!$existingCartItem) {
                            $schedule = FreeConsultationSchedule::where('id', $scheduleId)
                                ->where('type_id', $typeId)
                                ->first();

                            if ($schedule && $schedule->isAvailable()) {
                                CartItem::create([
                                    'user_id' => $user->id,
                                    'service_id' => null,
                                    'free_consultation_type_id' => $typeId,
                                    'free_consultation_schedule_id' => $scheduleId,
                                    'price' => 0,
                                    'hourly_price' => 0,
                                    'quantity' => 1,
                                    'hours' => 1,
                                    'booked_date' => $schedule->scheduled_date,
                                    'booked_time' => $schedule->scheduled_time,
                                    'session_type' => $item['session_type'] ?? 'Online',
                                    'offline_address' => $item['offline_address'] ?? null,
                                    'contact_preference' => $item['contact_preference'] ?? 'chat_and_call',
                                    'referral_code' => null,
                                    'payment_type' => 'full_payment'
                                ]);

                                $schedule->incrementBooking();
                                Log::info('Transfer: New free consultation added for user: ' . $user->id);
                            }
                        }
                    }
                    // Handle legacy free consultation
                    elseif ($serviceId === 'free-consultation') {
                        $existing = CartItem::where('user_id', $user->id)
                            ->where('service_id', 'free-consultation')
                            ->first();
                        
                        if (!$existing) {
                            CartItem::create([
                                'user_id' => $user->id,
                                'service_id' => 'free-consultation',
                                'price' => 0,
                                'hourly_price' => 0,
                                'quantity' => 1,
                                'hours' => 1,
                                'booked_date' => $item['booked_date'] ?? null,
                                'booked_time' => $item['booked_time'] ?? null,
                                'session_type' => $item['session_type'] ?? 'Online',
                                'offline_address' => $item['offline_address'] ?? null,
                                'contact_preference' => $item['contact_preference'] ?? 'chat_and_call',
                                'referral_code' => null,
                                'payment_type' => 'full_payment'
                            ]);
                            Log::info('Transfer: Legacy free consultation added for user: ' . $user->id);
                        }
                    } 
                    // Handle regular services
                    else {
                        $service = ConsultationService::find($serviceId);
                        if ($service) {
                            CartItem::updateOrCreate(
                                [
                                    'user_id' => $user->id, 
                                    'service_id' => $service->id,
                                    'booked_date' => $item['booked_date'] ?? null,
                                    'booked_time' => $item['booked_time'] ?? null,
                                ],
                                [
                                    'price' => $service->price,
                                    'hourly_price' => $service->hourly_price ?? 0,
                                    'quantity' => 1,
                                    'hours' => (int)($item['hours'] ?? 1),
                                    'session_type' => $item['session_type'] ?? 'Online',
                                    'offline_address' => $item['offline_address'] ?? null,
                                    'contact_preference' => $item['contact_preference'] ?? 'chat_and_call',
                                    'referral_code' => $item['referral_code'] ?? null,
                                    'payment_type' => 'full_payment'
                                ]
                            );
                            Log::info('Transfer: Regular service added: ' . $service->title);
                        }
                    }
                } catch (\Exception $itemException) {
                    Log::error('Transfer Item Error: ' . $itemException->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Cart Transfer Error: ' . $e->getMessage());
        }
    }
}
