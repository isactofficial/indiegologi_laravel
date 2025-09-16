<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ConsultationBooking;
use App\Models\Invoice;
use App\Models\ReferralCode;
use App\Models\FreeConsultationSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $validatedData = $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:cart_items,id',
            'global_payment_type' => ['required', Rule::in(['full_payment', 'dp'])],
        ]);

        $selectedPaymentType = $validatedData['global_payment_type'];

        $user = Auth::user();
        $selectedItemIds = $request->input('selected_items', []);

        if (empty($selectedItemIds)) {
            return back()->with('error', 'Pilih setidaknya satu layanan.');
        }

        $cartItems = CartItem::with([
            'service', 
            'referralCode', 
            'freeConsultationType', 
            'freeConsultationSchedule'
        ])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedItemIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Item tidak valid.');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $totalDiscount = 0;
            $firstItem = $cartItems->first();
            $usedReferralCodeIds = [];
            $servicesToAttach = [];
            $freeConsultationSchedulesToUpdate = [];

            foreach ($cartItems as $item) {
                $itemPrice = 0;
                $itemDiscount = 0;
                $referralIdToSave = null;

                // Handle new free consultation system
                if ($item->isNewFreeConsultation()) {
                    $itemPrice = 0; // Free consultation has no cost
                    $itemDiscount = 0;
                    
                    // Use a consistent key format
                    $serviceKey = 'new-free-consultation-' . $item->id;
                    
                    // Prepare data for booking_service pivot table
                    $servicesToAttach[$serviceKey] = [
                        'booking_id' => null, // Will be set later
                        'service_id' => null, // For free consultation
                        'total_price_at_booking'     => 0,
                        'discount_amount_at_booking' => 0,
                        'final_price_at_booking'     => 0,
                        'booked_date'                => $item->booked_date,
                        'booked_time'                => $item->booked_time,
                        'hours_booked'               => 1,
                        'session_type'               => $item->session_type,
                        'offline_address'            => $item->session_type == 'Offline' ? $item->offline_address : null,
                        'referral_code_id'           => null,
                        'invoice_id'                 => null, // Will be set later
                        'free_consultation_type_id'  => $item->free_consultation_type_id,
                        'free_consultation_schedule_id' => $item->free_consultation_schedule_id,
                        'contact_preference'         => $item->contact_preference,
                        'consultation_type'          => 'new', // Add marker
                    ];

                    // Track schedule to confirm booking
                    if ($item->freeConsultationSchedule) {
                        $freeConsultationSchedulesToUpdate[] = $item->freeConsultationSchedule;
                    }

                    continue; // Skip to next item
                }

                // Handle legacy free consultation
                if ($item->isLegacyFreeConsultation()) {
                    $itemPrice = 0;
                    $itemDiscount = 0;
                    
                    $serviceKey = 'legacy-free-consultation-' . $item->id;
                    
                    $servicesToAttach[$serviceKey] = [
                        'booking_id' => null, // Will be set later
                        'service_id' => null, // For free consultation
                        'total_price_at_booking'     => 0,
                        'discount_amount_at_booking' => 0,
                        'final_price_at_booking'     => 0,
                        'booked_date'                => $item->booked_date,
                        'booked_time'                => $item->booked_time,
                        'hours_booked'               => 1,
                        'session_type'               => $item->session_type,
                        'offline_address'            => $item->session_type == 'Offline' ? $item->offline_address : null,
                        'referral_code_id'           => null,
                        'invoice_id'                 => null, // Will be set later
                        'free_consultation_type_id'  => null,
                        'free_consultation_schedule_id' => null,
                        'contact_preference'         => $item->contact_preference,
                        'consultation_type'          => 'legacy', // Add marker
                    ];

                    continue; // Skip to next item
                }

                // Handle regular services
                if (!$item->service) continue;

                $itemPrice = $item->price + ($item->hourly_price * $item->hours);
                $subtotal += $itemPrice;

                if ($item->referralCode) {
                    $code = $item->referralCode;
                    $isValid = !$code->valid_until || (new \DateTime($code->valid_until)) > (new \DateTime());
                    $hasUses = !$code->max_uses || $code->current_uses < $code->max_uses;

                    if ($isValid && $hasUses) {
                        $itemDiscount = $itemPrice * ($code->discount_percentage / 100);
                        $totalDiscount += $itemDiscount;
                        $referralIdToSave = $code->id;
                        if (!in_array($referralIdToSave, $usedReferralCodeIds)) {
                            $usedReferralCodeIds[] = $referralIdToSave;
                        }
                    } else {
                        Log::warning("Referral code {$code->code} for cart item {$item->id} is invalid at checkout.");
                    }
                }

                $servicesToAttach[$item->service_id] = [
                    'booking_id' => null, // Will be set later
                    'service_id' => $item->service_id,
                    'total_price_at_booking'     => $itemPrice,
                    'discount_amount_at_booking' => $itemDiscount,
                    'final_price_at_booking'     => $itemPrice - $itemDiscount,
                    'booked_date'                => $item->booked_date,
                    'booked_time'                => $item->booked_time,
                    'hours_booked'               => $item->hours,
                    'session_type'               => $item->session_type,
                    'offline_address'            => $item->session_type == 'Offline' ? $item->offline_address : null,
                    'referral_code_id'           => $referralIdToSave,
                    'invoice_id'                 => null, // Will be set later
                    'free_consultation_type_id'  => null,
                    'free_consultation_schedule_id' => null,
                    'contact_preference'         => $item->contact_preference,
                    'consultation_type'          => 'regular', // Add marker
                ];
            }

            $grandTotal = $subtotal - $totalDiscount;

            // Create invoice
            $invoice = Invoice::create([
                'user_id'         => $user->id,
                'invoice_no'      => 'INV-' . strtoupper(Str::random(8)),
                'invoice_date'    => now(),
                'due_date'        => now()->addDay(),
                'total_amount'    => $grandTotal,
                'discount_amount' => $totalDiscount,
                'paid_amount'     => 0.00,
                'payment_type'    => $selectedPaymentType,
                'payment_status'  => 'pending',
                'session_type'    => $firstItem->session_type,
            ]);

            // Create booking
            $booking = ConsultationBooking::create([
                'user_id'         => $user->id,
                'invoice_id'      => $invoice->id,
                'receiver_name'   => $user->name,
                'final_price'     => $grandTotal,
                'discount_amount' => $totalDiscount,
                'session_status'  => 'menunggu pembayaran',
                'contact_preference' => $firstItem->contact_preference,
                'payment_type'    => $selectedPaymentType,
            ]);

            // Handle service attachments - IMPROVED VERSION
            foreach ($servicesToAttach as $serviceKey => $pivotData) {
                // Set the booking_id and invoice_id
                $pivotData['booking_id'] = $booking->id;
                $pivotData['invoice_id'] = $invoice->id;
                
                // Remove consultation_type before inserting to database
                $consultationType = $pivotData['consultation_type'] ?? 'regular';
                unset($pivotData['consultation_type']);

                if (strpos($serviceKey, 'free-consultation-') === 0 || $consultationType !== 'regular') {
                    // Handle free consultation - direct insert to booking_service table
                    try {
                        DB::table('booking_service')->insert(array_merge($pivotData, [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]));
                        
                        Log::info('Free consultation booking service inserted', [
                            'service_key' => $serviceKey,
                            'consultation_type' => $consultationType,
                            'booking_id' => $booking->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to insert free consultation booking service: ' . $e->getMessage(), [
                            'service_key' => $serviceKey,
                            'pivot_data' => $pivotData
                        ]);
                        throw $e;
                    }
                } else {
                    // Handle regular services using the relationship
                    try {
                        $booking->services()->attach($pivotData['service_id'], $pivotData);
                        
                        Log::info('Regular service attached', [
                            'service_id' => $pivotData['service_id'],
                            'booking_id' => $booking->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to attach regular service: ' . $e->getMessage(), [
                            'service_id' => $pivotData['service_id'],
                            'pivot_data' => $pivotData
                        ]);
                        throw $e;
                    }
                }
            }

            // Update referral code usage
            if (!empty($usedReferralCodeIds)) {
                ReferralCode::whereIn('id', $usedReferralCodeIds)->increment('current_uses');
            }

            // Confirm free consultation schedule bookings
            foreach ($freeConsultationSchedulesToUpdate as $schedule) {
                // Schedule slot was already incremented when added to cart
                Log::info('Free consultation schedule confirmed for booking', [
                    'schedule_id' => $schedule->id,
                    'booking_id' => $booking->id,
                    'user_id' => $user->id
                ]);
            }

            // Remove cart items
            CartItem::whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            Log::info('Checkout completed successfully', [
                'booking_id' => $booking->id,
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'total_amount' => $grandTotal
            ]);

            return redirect()->route('invoice.show', $booking)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine(), [
                'user_id' => $user->id,
                'selected_items' => $selectedItemIds,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Detail: ' . $e->getMessage());
        }
    }
}