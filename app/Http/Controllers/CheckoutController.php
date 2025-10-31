<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\CartParticipant; // ADD THIS
use App\Models\ConsultationBooking;
use App\Models\ConsultationService;
use App\Models\Invoice;
use App\Models\ReferralCode;
use App\Models\FreeConsultationSchedule;
use App\Models\Event;
use App\Models\EventBooking; // ADD THIS
use App\Models\EventParticipant; // ADD THIS
use App\Models\BookingService;
use App\Models\User; // Ditambahkan
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
            'freeConsultationSchedule',
            'event',
            'participantData' // Relasi ke participants
        ])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedItemIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Item tidak valid.');
        }

        // --- VALIDASI FILTER BARU DITAMBAHKAN DI SINI ---
        // Ambil tipe item pertama (event atau bukan)
        $firstItemIsEvent = $cartItems->first()->isEvent(); 

        // Periksa apakah semua item lain memiliki tipe yang sama
        $allSameType = $cartItems->every(function ($item) use ($firstItemIsEvent) {
            return $item->isEvent() === $firstItemIsEvent;
        });

        // Jika tipe campur (mixed), kembalikan error
        if (!$allSameType) {
            return back()->with('error', 'Anda hanya dapat checkout layanan dan event secara terpisah. Harap pilih item dari satu kategori saja (layanan atau event).');
        }

        if ($cartItems->isNotEmpty()) {
            // Periksa tipe item pertama
            $firstItemIsEvent = $cartItems->first()->isEvent(); 
            
            // Periksa apakah ada item yang tipenya berbeda dari item pertama
            $isMixed = $cartItems->some(function ($item) use ($firstItemIsEvent) {
                return $item->isEvent() !== $firstItemIsEvent;
            });

            if ($isMixed) {
                // Jika tercampur, kembalikan error
                return back()->with('error', 'Anda hanya dapat checkout Layanan atau Event dalam satu transaksi, tidak keduanya.');
            }
        }
        // --- AKHIR BLOK VALIDASI FILTER ---


        DB::beginTransaction();
        try {
            $subtotal = 0;
            $totalDiscount = 0;
            $firstItem = $cartItems->first();
            $usedReferralCodeIds = [];
            $servicesToAttach = [];
            $freeConsultationSchedulesToUpdate = [];

            // NEW: Proper structure for event data
            $eventsToUpdate = [];
            $eventParticipantsData = [];

            foreach ($cartItems as $item) {
                $itemPrice = 0;
                $itemDiscount = 0;
                $referralIdToSave = null;

                // In CheckoutController.php - Replace the event handling section in the process() method:

                // Inside the foreach ($cartItems as $item) loop, replace the event handling with:

                if ($item->isEvent()) {
                    if (!$item->event) {
                        Log::warning("Event not found for cart item: {$item->id}");
                        continue;
                    }

                    // ✅ Use stored values from cart_item (already calculated correctly)
                    $originalPricePerPerson = $item->original_price; // Original price per person
                    $participantCount = $item->participant_count;
                    $itemSubtotal = $originalPricePerPerson * $participantCount; // Total before discount
                    $itemDiscount = $item->discount_amount; // Already calculated and stored
                    $itemFinalPrice = $itemSubtotal - $itemDiscount;

                    // Add to totals
                    $subtotal += $itemSubtotal;
                    $totalDiscount += $itemDiscount;

                    // Track referral code if used
                    $referralIdToSave = null;
                    if ($item->referralCode) {
                        $code = $item->referralCode;
                        $isValid = !$code->valid_until || (new \DateTime($code->valid_until)) > (new \DateTime());
                        $hasUses = !$code->max_uses || $code->current_uses < $code->max_uses;

                        if ($isValid && $hasUses) {
                            $referralIdToSave = $code->id;
                            if (!in_array($referralIdToSave, $usedReferralCodeIds)) {
                                $usedReferralCodeIds[] = $referralIdToSave;
                            }
                        }
                    }

                    $serviceKey = 'event-' . $item->event_id;

                    $servicesToAttach[$serviceKey] = [
                        'booking_id' => null,
                        'event_id' => $item->event_id,
                        'total_price_at_booking'     => $itemSubtotal,      // ✅ Subtotal before discount
                        'discount_amount_at_booking' => $itemDiscount,      // ✅ Discount amount
                        'final_price_at_booking'     => $itemFinalPrice,    // ✅ After discount
                        'booked_date'                => $item->event->event_date,
                        'booked_time'                => $item->event->event_time,
                        'hours_booked'               => 0,
                        'participant_count'          => $participantCount,
                        'session_type'               => $item->event->session_type,
                        'offline_address'            => $item->event->session_type == 'offline' ? $item->event->place : null,
                        'referral_code_id'           => $referralIdToSave,
                        'invoice_id'                 => null,
                        'free_consultation_type_id'  => null,
                        'free_consultation_schedule_id' => null,
                        'contact_preference'         => $item->contact_preference,
                        'user_id'                    => $user->id,
                    ];

                    // Track event for participant count update and booking creation
                    $eventsToUpdate[$item->event_id] = [
                        'event' => $item->event,
                        'participant_count' => $participantCount,
                        'cart_item_id' => $item->id,
                        'subtotal' => $itemSubtotal,
                        'discount' => $itemDiscount,
                        'final_price' => $itemFinalPrice,
                        'referral_id' => $referralIdToSave
                    ];

                    // Save participant data for later
                    $eventParticipantsData[$item->event_id] = $item->participantData;

                    Log::info('Event processed in checkout', [
                        'cart_item_id' => $item->id,
                        'event_id' => $item->event_id,
                        'original_price_per_person' => $originalPricePerPerson,
                        'participant_count' => $participantCount,
                        'subtotal' => $itemSubtotal,
                        'discount' => $itemDiscount,
                        'final_price' => $itemFinalPrice
                    ]);

                    continue; // Skip to next item
                }

                // Handle new free consultation system
                if ($item->isNewFreeConsultation()) {
                    $itemPrice = 0; // Free consultation has no cost
                    $itemDiscount = 0;

                    $serviceKey = 'new-free-consultation-' . $item->id;

                    $servicesToAttach[$serviceKey] = [
                        'booking_id' => null,
                        'service_id' => null,
                        'total_price_at_booking'     => 0,
                        'discount_amount_at_booking' => 0,
                        'final_price_at_booking'     => 0,
                        'booked_date'                => $item->booked_date,
                        'booked_time'                => $item->booked_time,
                        'hours_booked'               => 1,
                        'session_type'               => $item->session_type,
                        'offline_address'            => $item->session_type == 'Offline' ? $item->offline_address : null,
                        'referral_code_id'           => null,
                        'invoice_id'                 => null,
                        'free_consultation_type_id'  => $item->free_consultation_type_id,
                        'free_consultation_schedule_id' => $item->free_consultation_schedule_id,
                        'contact_preference'         => $item->contact_preference,
                        'user_id'                    => $user->id,
                    ];

                    // Track schedule to confirm booking
                    if ($item->freeConsultationSchedule) {
                        $freeConsultationSchedulesToUpdate[] = $item->freeConsultationSchedule;
                    }

                    continue;
                }

                // Handle legacy free consultation
                if ($item->isLegacyFreeConsultation()) {
                    $itemPrice = 0;
                    $itemDiscount = 0;

                    $serviceKey = 'legacy-free-consultation-' . $item->id;

                    $servicesToAttach[$serviceKey] = [
                        'booking_id' => null,
                        'service_id' => null,
                        'total_price_at_booking'     => 0,
                        'discount_amount_at_booking' => 0,
                        'final_price_at_booking'     => 0,
                        'booked_date'                => $item->booked_date,
                        'booked_time'                => $item->booked_time,
                        'hours_booked'               => 1,
                        'session_type'               => $item->session_type,
                        'offline_address'            => $item->session_type == 'Offline' ? $item->offline_address : null,
                        'referral_code_id'           => null,
                        'invoice_id'                 => null,
                        'free_consultation_type_id'  => null,
                        'free_consultation_schedule_id' => null,
                        'contact_preference'         => $item->contact_preference,
                        'user_id'                    => $user->id,
                    ];

                    continue;
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
                    'booking_id' => null,
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
                    'invoice_id'                 => null,
                    'free_consultation_type_id'  => null,
                    'free_consultation_schedule_id' => null,
                    'contact_preference'         => $item->contact_preference,
                    'user_id'                    => $user->id,
                ];
            }

            $grandTotal = $subtotal - $totalDiscount;

            // Create invoice
            // Create invoice
            $invoice = Invoice::create([
                'user_id'               => $user->id,
                'invoice_no'            => 'INV-' . strtoupper(Str::random(8)), // Sebaiknya gunakan method generate
                'invoice_type'          => 'auto',
                'source_type'           => $firstItemIsEvent ? 'event' : 'service', // Filter type
                'invoice_date'          => now(),
                'due_date'              => now()->addDay(),
                'subtotal_amount'       => $subtotal,
                'total_amount'          => $grandTotal, // Ini seharusnya subtotal
                'paid_amount'           => 0.00,
                'auto_discount_amount'  => $totalDiscount, // ✅ CORRECT COLUMN NAME
                'manual_discount_amount' => 0.00,
                'total_discount_amount' => $totalDiscount,
                'final_amount'          => $grandTotal,
                'remaining_amount'      => $grandTotal,
                'payment_type'          => $selectedPaymentType,
                'payment_status'        => 'unpaid', // Changed from 'pending' to 'unpaid'
                'session_type'          => $firstItem->session_type,
                'is_active'             => true,
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

            // Handle service attachments to booking_service table
            foreach ($servicesToAttach as $serviceKey => $pivotData) {
                // Set the booking_id and invoice_id
                $pivotData['booking_id'] = $booking->id;
                $pivotData['invoice_id'] = $invoice->id;

                // Remove unnecessary fields
                if (isset($pivotData['consultation_type'])) {
                    unset($pivotData['consultation_type']);
                }

                // Insert to booking_service table
                try {
                    DB::table('booking_service')->insert(array_merge($pivotData, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));

                    Log::info('Booking service inserted', [
                        'service_key' => $serviceKey,
                        'booking_id' => $booking->id,
                        'type' => isset($pivotData['event_id']) ? 'event' : (isset($pivotData['free_consultation_type_id']) ? 'free_consultation' : 'service')
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to insert booking service: ' . $e->getMessage(), [
                        'service_key' => $serviceKey,
                        'pivot_data' => $pivotData
                    ]);
                    throw $e;
                }
            }

            // NEW: Create event bookings and participants (AFTER invoice and booking are created)
            if (!empty($eventsToUpdate)) {
                foreach ($eventsToUpdate as $eventId => $data) {
                    try {
                        $event = $data['event'];
                        $participantCount = $data['participant_count'];
                        $cartItemId = $data['cart_item_id'];

                        $itemSubtotal = $data['subtotal'];    // Original subtotal
                        $itemDiscount = $data['discount'];    // Discount amount
                        $itemFinalPrice = $data['final_price']; // Final price after discount
                        $referralId = $data['referral_id'];

                        // Create event booking record
                        $eventBooking = EventBooking::create([
                            'user_id' => $user->id,
                            'event_id' => $eventId,
                            'booker_name' => $user->name,
                            'booker_phone' => $user->userProfile->phone_number ?? '',
                            'booker_email' => $user->email,
                            'participant_count' => $participantCount,
                            'total_price' => $itemSubtotal,        // ✅ Subtotal before discount
                            'discount_amount' => $itemDiscount,    // ✅ Discount amount
                            'final_price' => $itemFinalPrice,      // ✅ After discount
                            'payment_type' => $selectedPaymentType,
                            'booking_status' => 'menunggu pembayaran',
                            'referral_code_id' => $referralId,
                            'invoice_id' => $invoice->id
                        ]);

                        // Save individual participants to event_participants table
                        if (isset($eventParticipantsData[$eventId])) {
                            foreach ($eventParticipantsData[$eventId] as $participant) {
                                EventParticipant::create([
                                    'event_booking_id' => $eventBooking->id,
                                    'full_name' => $participant->full_name,
                                    'phone_number' => $participant->phone_number,
                                    'email' => $participant->email,
                                    'attendance_status' => 'pending'
                                ]);
                            }
                        }

                        Log::info('Event booking created successfully', [
                            'event_id' => $eventId,
                            'booking_id' => $eventBooking->id,
                            'participant_count' => $participantCount,
                            'total_price' => $itemSubtotal,
                            'discount' => $itemDiscount,
                            'final_price' => $itemFinalPrice
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create event booking: ' . $e->getMessage(), [
                            'event_id' => $eventId,
                            'user_id' => $user->id,
                            'cart_item_id' => $cartItemId,
                            'trace' => $e->getTraceAsString()
                        ]);
                        // Don't throw - allow other items to process
                    }
                }
            }

            // Update referral code usage
            if (!empty($usedReferralCodeIds)) {
                ReferralCode::whereIn('id', $usedReferralCodeIds)->increment('current_uses');
            }

            // Confirm free consultation schedule bookings
            foreach ($freeConsultationSchedulesToUpdate as $schedule) {
                Log::info('Free consultation schedule confirmed for booking', [
                    'schedule_id' => $schedule->id,
                    'booking_id' => $booking->id,
                    'user_id' => $user->id
                ]);
            }

            // Remove cart items
            CartParticipant::whereIn('cart_item_id', $selectedItemIds)->delete(); // Hapus partisipan dulu
            CartItem::whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            Log::info('Checkout completed successfully', [
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'total_amount' => $grandTotal,
                'item_count' => count($servicesToAttach),
                'event_count' => count($eventsToUpdate)
            ]);

            // Redirect ke invoice page, bukan consultation booking
            return redirect()->route('invoice.show', $invoice->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine(), [
                'user_id' => $user->id,
                'selected_items' => $selectedItemIds,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }
    }

    /**
     * Get service pricing for guest cart
     */
    public function getServicePricing(Request $request)
    {
        $serviceIds = $request->input('service_ids', []);

        $pricing = ConsultationService::whereIn('id', $serviceIds)
            ->get()
            ->keyBy('id')
            ->map(function ($service) {
                return [
                    'title' => $service->title,
                    'price' => $service->price,
                    'hourly_price' => $service->hourly_price,
                    'thumbnail' => $service->thumbnail ? asset('storage/' . $service->thumbnail) : null
                ];
            });

        return response()->json([
            'success' => true,
            'pricing' => $pricing
        ]);
    }

    // In CheckoutController.php - GANTI NAMA METHOD dan PERBAIKI PARAMETER
    /**
     * Update cart summary via AJAX
     */
    public function calculateSummary(Request $request) // NAMA DIGANTI
    {
        // PARAMETER DISESUAIKAN DENGAN BLADE
        $validated = $request->validate([
            'selected_items' => 'nullable|array',
            'selected_items.*' => 'exists:cart_items,id',
            'global_payment_type' => ['required', Rule::in(['full_payment', 'dp'])],
        ]);

        $selectedIds = $validated['selected_items'] ?? [];
        $paymentType = $validated['global_payment_type']; // Menggunakan 'global_payment_type'
        $user = Auth::user();

        if (empty($selectedIds) || !$user) {
            return response()->json([
                'subtotal' => '0',
                'totalDiscount' => '0',
                'grandTotal' => '0',
                'totalToPayNow' => '0',
            ]);
        }
        
        $cartItems = CartItem::with(['service', 'referralCode', 'event'])
            ->where('user_id', $user->id) // Scope ke user
            ->whereIn('id', $selectedIds)
            ->get();

        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($cartItems as $item) {
            // ✅ Calculate subtotal BEFORE discount
            $itemSubtotal = $item->calculateOriginalSubtotal();
            $itemDiscount = $item->getDiscountAmount();

            $subtotal += $itemSubtotal;
            $totalDiscount += $itemDiscount;

            Log::info('Cart summary calculation', [
                'item_id' => $item->id,
                'item_type' => $item->item_type,
                'is_event' => $item->isEvent(),
                'original_price' => $item->original_price,
                'participant_count' => $item->participant_count,
                'item_subtotal' => $itemSubtotal,
                'item_discount' => $itemDiscount
            ]);
        }

        $grandTotal = $subtotal - $totalDiscount;
        $totalToPayNow = ($paymentType === 'dp') ? $grandTotal * 0.5 : $grandTotal;

        Log::info('Cart summary totals', [
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal,
            'payment_type' => $paymentType,
            'total_to_pay_now' => $totalToPayNow
        ]);

        return response()->json([
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'totalDiscount' => number_format($totalDiscount, 0, ',', '.'),
            'grandTotal' => number_format($grandTotal, 0, ',', '.'),
            'totalToPayNow' => number_format($totalToPayNow, 0, ',', '.'),
        ]);
    }
}
