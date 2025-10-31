<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventBooking;
use App\Models\EventParticipant;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GuestEventBookingController extends Controller
{
    public function book($slug)
    {
        $event = Event::where('slug', $slug)->available()->firstOrFail();
        return view('front.events.guest-book', compact('event'));
    }

    public function processBooking(Request $request, Event $event)
    {
        Log::info('Processing guest event booking', [
            'event_id' => $event->id,
            'event_title' => $event->title,
            'request_data' => $request->all()
        ]);

        if (!$event->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Event sudah penuh atau pendaftaran ditutup. Sisa slot: ' . $event->spots_left
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'participant_count' => 'required|integer|min:1|max:' . $event->spots_left,
            'contact_preference' => 'required|in:chat_only,chat_and_call',
            'referral_code' => 'nullable|string',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'required|email|max:255',
            'participants' => 'required|array|min:1|max:' . $event->spots_left,
            'participants.*.full_name' => 'required|string|max:255',
            'participants.*.phone_number' => 'required|string|max:20',
            'participants.*.email' => 'nullable|email|max:255'
        ], [
            'participant_count.max' => 'Jumlah peserta melebihi kuota yang tersedia. Sisa slot: ' . $event->spots_left,
            'participants.*.full_name.required' => 'Nama lengkap peserta wajib diisi',
            'participants.*.phone_number.required' => 'Nomor telepon peserta wajib diisi',
            'guest_name.required' => 'Nama pemesan wajib diisi',
            'guest_phone.required' => 'Nomor telepon pemesan wajib diisi',
            'guest_email.required' => 'Email pemesan wajib diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Calculate pricing
            $totalPrice = $event->price * $request->participant_count;

            $discountAmount = 0;
            $referralCodeId = null;
            $referralCodeUsed = null;

            // ✅ FIXED: Apply referral code discount for guest users
            if ($request->referral_code) {
                $referralCode = ReferralCode::where('code', $request->referral_code)->first();

                if ($referralCode && $referralCode->isValid()) {
                    // Calculate discount based on type
                    if ($referralCode->discount_type === 'fixed') {
                        // Fixed discount: amount per participant
                        $discountAmount = $referralCode->discount_amount * $request->participant_count;
                    } else {
                        // Percentage discount
                        $discountAmount = $totalPrice * ($referralCode->discount_percentage / 100);
                    }

                    // Ensure discount doesn't exceed total price
                    $discountAmount = min($discountAmount, $totalPrice);

                    $referralCodeId = $referralCode->id;
                    $referralCodeUsed = $request->referral_code;

                    Log::info('Referral code applied successfully for guest booking', [
                        'code' => $request->referral_code,
                        'discount_type' => $referralCode->discount_type,
                        'discount_amount' => $discountAmount,
                        'total_price' => $totalPrice,
                        'participant_count' => $request->participant_count
                    ]);
                } else {
                    Log::warning('Invalid referral code for guest booking', [
                        'code' => $request->referral_code,
                        'is_valid' => $referralCode ? $referralCode->isValid() : false
                    ]);
                }
            }

            $finalPrice = $totalPrice - $discountAmount;

            // Create event booking for guest (no user_id) with "menunggu pembayaran" status
            $eventBooking = EventBooking::create([
                'user_id' => null, // Guest user
                'event_id' => $event->id,
                'booker_name' => $request->guest_name,
                'booker_phone' => $request->guest_phone,
                'booker_email' => $request->guest_email,
                'guest_name' => $request->guest_name,
                'guest_phone' => $request->guest_phone,
                'guest_email' => $request->guest_email,
                'participant_count' => $request->participant_count,
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'payment_type' => 'full_payment',
                'contact_preference' => $request->contact_preference,
                'booking_status' => 'menunggu pembayaran', // Default status
                'payment_status' => 'unpaid',
                'referral_code_id' => $referralCodeId, // Store referral code ID even for guest users
                'invoice_id' => null // No invoice for guest bookings
            ]);

            // Save participant data
            foreach ($request->participants as $participantData) {
                EventParticipant::create([
                    'event_booking_id' => $eventBooking->id,
                    'full_name' => $participantData['full_name'],
                    'phone_number' => $participantData['phone_number'],
                    'email' => $participantData['email'] ?? null,
                    'attendance_status' => 'pending'
                ]);
            }

            Log::info('Guest event booking created successfully', [
                'booking_id' => $eventBooking->id,
                'event_id' => $event->id,
                'participant_count' => $request->participant_count,
                'guest_name' => $request->guest_name,
                'status' => 'menunggu pembayaran',
                'referral_code_used' => $referralCodeUsed,
                'discount_applied' => $discountAmount,
                'final_price' => $finalPrice
            ]);

            DB::commit();

            // Build success message based on discount type
            $successMessage = 'Pendaftaran event berhasil! ';

            if ($referralCodeUsed && $referralCode) {
                if ($referralCode->discount_type === 'fixed') {
                    $discountPerPerson = number_format($referralCode->discount_amount, 0, ',', '.');
                    $totalDiscount = number_format($discountAmount, 0, ',', '.');
                    $successMessage .= "Diskon Rp {$discountPerPerson} per peserta (total Rp {$totalDiscount}) dari kode referral telah diterapkan. ";
                } else {
                    $successMessage .= "Diskon {$referralCode->discount_percentage}% dari kode referral telah diterapkan. ";
                }
            }

            $successMessage .= 'Admin akan menghubungi Anda untuk konfirmasi pembayaran.';

            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'booking_id' => $eventBooking->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Guest event booking failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar event: ' . $e->getMessage()
            ], 500);
        }
    }
}
