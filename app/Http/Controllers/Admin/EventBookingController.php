<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventBooking;
use App\Models\Event;
use App\Models\User;
use App\Models\Invoice;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventBookingController extends Controller
{
    /**
     * Display a listing of event bookings.
     */
    public function index()
    {
        $bookings = EventBooking::with(['user', 'event', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('event-bookings.index', compact('bookings'));
    }

    /**
     * Display the specified event booking.
     */
    public function show(EventBooking $eventBooking)
    {
        $eventBooking->load(['user', 'event', 'invoice', 'participants']);

        return view('event-bookings.show', compact('eventBooking'));
    }

    /**
     * Show the form for editing event booking status.
     */
    public function edit(EventBooking $eventBooking)
    {
        $eventBooking->load(['user', 'event', 'invoice', 'participants']);

        return view('event-bookings.edit', compact('eventBooking'));
    }

    /**
     * Update the event booking status and payment.
     */
    public function update(Request $request, EventBooking $eventBooking)
    {
        $request->validate([
            'booking_status' => 'required|in:menunggu pembayaran,terdaftar,hadir,tidak_hadir,dibatalkan',
            'payment_status' => 'required|in:draft,unpaid,pending,paid,partial,overdue,cancelled,dibatalkan'
        ]);

        DB::transaction(function () use ($request, $eventBooking) {
            $oldStatus = $eventBooking->booking_status;
            $newStatus = $request->booking_status;

            // Update booking status AND payment status
            $updateData = [
                'booking_status' => $newStatus,
                'contact_preference' => $request->contact_preference
            ];

            // TAMBAH: Update payment_status untuk guest booking
            if ($eventBooking->is_guest) {
                $updateData['payment_status'] = $request->payment_status;
            }

            $eventBooking->update($updateData);

            // Update invoice payment status if exists (for registered users)
            if ($eventBooking->invoice) {
                $eventBooking->invoice->update([
                    'payment_status' => $request->payment_status
                ]);
            }

            // Handle participant count changes based on status transitions
            $event = $eventBooking->event;
            $participantCount = $eventBooking->participant_count;

            // Status changed to "terdaftar" - ADD participants
            if ($newStatus === 'terdaftar' && $oldStatus !== 'terdaftar') {
                // Check if there are enough spots available
                $availableSpots = $event->max_participants - $event->current_participants;

                if ($availableSpots >= $participantCount) {
                    $event->increment('current_participants', $participantCount);
                    Log::info('Participants added to event (status changed to terdaftar)', [
                        'event_id' => $event->id,
                        'added_count' => $participantCount,
                        'new_count' => $event->current_participants,
                        'booking_id' => $eventBooking->id,
                        'is_guest' => $eventBooking->is_guest
                    ]);

                    // TAMBAH: Increment referral code usage ketika status berubah ke terdaftar
                    if ($eventBooking->referral_code_id) {
                        $referralCode = ReferralCode::find($eventBooking->referral_code_id);
                        if ($referralCode) {
                            $referralCode->increment('current_uses');
                            Log::info('Referral code usage incremented for confirmed booking', [
                                'booking_id' => $eventBooking->id,
                                'referral_code_id' => $eventBooking->referral_code_id,
                                'code' => $referralCode->code,
                                'is_guest' => $eventBooking->is_guest
                            ]);
                        }
                    }
                } else {
                    // Rollback the status change if not enough spots
                    $eventBooking->update(['booking_status' => $oldStatus]);
                    throw new \Exception("Tidak cukup slot tersedia. Sisa slot: {$availableSpots}");
                }
            }

            // Status changed from "terdaftar" to something else - REMOVE participants
            if ($oldStatus === 'terdaftar' && $newStatus !== 'terdaftar') {
                $event->decrement('current_participants', $participantCount);
                Log::info('Participants removed from event (status changed from terdaftar)', [
                    'event_id' => $event->id,
                    'removed_count' => $participantCount,
                    'new_count' => $event->current_participants,
                    'booking_id' => $eventBooking->id,
                    'is_guest' => $eventBooking->is_guest
                ]);
            }

            // Update participant attendance if provided
            if ($request->has('participants')) {
                foreach ($request->participants as $participantId => $participantData) {
                    if (isset($participantData['attendance_status'])) {
                        $eventBooking->participants()
                            ->where('id', $participantId)
                            ->update(['attendance_status' => $participantData['attendance_status']]);
                    }
                }
            }
        });

        return redirect()->route('admin.event-bookings.index')
            ->with('success', 'Status booking dan pembayaran berhasil diperbarui!');
    }

    /**
     * Show the form for creating a new event booking.
     */
    // Di method create(), ubah query events:
    public function create()
    {
        $users = User::all();
        $events = Event::where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->get();
        $referralCodes = ReferralCode::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->get();

        return view('event-bookings.create', compact('users', 'events', 'referralCodes'));
    }

    /**
     * Store a newly created event booking in storage.
     */
    public function store(Request $request)
    {
        Log::info('=== EVENT BOOKING STORE START ===');
        Log::info('Request data:', $request->all());

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'participant_count' => 'required|integer|min:1',
            'contact_preference' => 'required|in:chat_only,chat_and_call',
            'payment_type' => 'required|in:dp,full_payment',
            'referral_code_id' => 'nullable|exists:referral_codes,id',
            'participants' => 'required|array|min:1',
            'participants.*.full_name' => 'required|string|max:255',
            'participants.*.phone_number' => 'required|string|max:20',
            'participants.*.email' => 'nullable|email|max:255'
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->user_id);
            $event = Event::findOrFail($request->event_id);

            Log::info('User found:', ['user_id' => $user->id, 'name' => $user->name]);
            Log::info('Event found:', ['event_id' => $event->id, 'title' => $event->title]);

            // Check if event has available spots
            $availableSpots = $event->max_participants - $event->current_participants;
            Log::info('Available spots:', ['available' => $availableSpots, 'requested' => $request->participant_count]);

            if ($availableSpots < $request->participant_count) {
                Log::warning('Not enough spots', ['available' => $availableSpots, 'requested' => $request->participant_count]);
                return back()->with('error', 'Event tidak memiliki cukup slot. Sisa slot: ' . $availableSpots);
            }

            // Calculate pricing
            $totalPrice = $event->price * $request->participant_count;
            $discountAmount = 0;
            $referralCodeId = null;

            Log::info('Pricing calculation:', [
                'event_price' => $event->price,
                'participant_count' => $request->participant_count,
                'total_price' => $totalPrice
            ]);

            // Apply referral discount if provided
            if ($request->referral_code_id) {
                $referralCode = ReferralCode::find($request->referral_code_id);
                if ($referralCode && $referralCode->isValid()) {
                    // Check minimum purchase amount if exists
                    if ($referralCode->min_purchase_amount && $totalPrice < $referralCode->min_purchase_amount) {
                        Log::warning('Minimum purchase amount not met for referral code', [
                            'min_purchase_amount' => $referralCode->min_purchase_amount,
                            'total_price' => $totalPrice,
                            'code' => $referralCode->code
                        ]);

                        // Skip discount if minimum purchase not met
                        $discountAmount = 0;
                        $referralCodeId = null;
                    } else {
                        // Calculate discount based on type - CONSISTENT with frontend
                        if ($referralCode->discount_type === 'percentage') {
                            $discountAmount = $totalPrice * ($referralCode->discount_percentage / 100);
                        } else {
                            // FIXED: Fixed discount applied PER PARTICIPANT
                            $discountPerParticipant = $referralCode->discount_amount;
                            $discountAmount = $discountPerParticipant * $request->participant_count;

                            // Ensure discount doesn't exceed total price
                            $discountAmount = min($discountAmount, $totalPrice);
                        }

                        $referralCodeId = $referralCode->id;

                        // Only increment if discount is actually applied
                        if ($discountAmount > 0) {
                            $referralCode->increment('current_uses');
                            Log::info('Referral applied successfully:', [
                                'code' => $referralCode->code,
                                'discount_type' => $referralCode->discount_type,
                                'discount_percentage' => $referralCode->discount_percentage,
                                'discount_amount' => $referralCode->discount_amount,
                                'participant_count' => $request->participant_count,
                                'discount_per_participant' => $referralCode->discount_type === 'fixed' ? $referralCode->discount_amount : 'N/A',
                                'calculated_discount' => $discountAmount,
                                'min_purchase_amount' => $referralCode->min_purchase_amount
                            ]);
                        }
                    }
                } else {
                    Log::warning('Referral code invalid or not found:', [
                        'referral_code_id' => $request->referral_code_id,
                        'is_valid' => $referralCode ? $referralCode->isValid() : 'not found'
                    ]);
                }
            }

            $finalPrice = $totalPrice - $discountAmount;

            Log::info('Final pricing:', [
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice
            ]);

            // Create invoice
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'invoice_no' => 'INV-EV-' . time() . Str::random(3),
                'invoice_type' => 'manual',
                'source_type' => 'event',
                'invoice_date' => now(),
                'due_date' => now()->addDays(3),
                'total_amount' => $finalPrice,
                'paid_amount' => 0,
                'auto_discount_amount' => $discountAmount,
                'final_amount' => $finalPrice,
                'remaining_amount' => $finalPrice,
                'payment_type' => $request->payment_type,
                'payment_status' => 'unpaid',
                'session_type' => $event->session_type,
                'is_active' => true,
            ]);

            Log::info('Invoice created:', ['invoice_id' => $invoice->id, 'invoice_no' => $invoice->invoice_no]);

            // Create event booking with "menunggu pembayaran" status
            $eventBooking = EventBooking::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'booker_name' => $user->name,
                'booker_phone' => $user->userProfile->phone_number ?? '',
                'booker_email' => $user->email,
                'participant_count' => $request->participant_count,
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'payment_type' => $request->payment_type,
                'contact_preference' => $request->contact_preference,
                'booking_status' => 'menunggu pembayaran', // Default status
                'referral_code_id' => $referralCodeId,
                'invoice_id' => $invoice->id,
            ]);

            Log::info('Event booking created:', ['booking_id' => $eventBooking->id]);

            // Create participants
            foreach ($request->participants as $index => $participantData) {
                $participant = \App\Models\EventParticipant::create([
                    'event_booking_id' => $eventBooking->id,
                    'full_name' => $participantData['full_name'],
                    'phone_number' => $participantData['phone_number'],
                    'email' => $participantData['email'] ?? null,
                    'attendance_status' => 'pending'
                ]);
                Log::info("Participant {$index} created:", [
                    'name' => $participantData['full_name'],
                    'participant_id' => $participant->id
                ]);
            }

            // REMOVED: Don't update event participant count here
            // $event->increment('current_participants', $request->participant_count);

            Log::info('Event participant count NOT updated - waiting for "terdaftar" status');

            DB::commit();

            Log::info('=== EVENT BOOKING STORE SUCCESS ===');

            return redirect()->route('admin.event-bookings.index')
                ->with('success', 'Booking event berhasil dibuat! ID Booking: #' . $eventBooking->id);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Event booking store failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal membuat booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Confirm payment for event booking.
     */
    public function confirmPayment(EventBooking $eventBooking)
    {
        DB::transaction(function () use ($eventBooking) {
            $oldStatus = $eventBooking->booking_status;

            $eventBooking->update([
                'booking_status' => 'terdaftar'
            ]);

            if ($eventBooking->invoice) {
                $eventBooking->invoice->update([
                    'payment_status' => 'paid'
                ]);
            }

            // Only add participants if status changed to "terdaftar"
            if ($oldStatus !== 'terdaftar') {
                $event = $eventBooking->event;
                $participantCount = $eventBooking->participant_count;

                // Check available spots
                $availableSpots = $event->max_participants - $event->current_participants;

                if ($availableSpots >= $participantCount) {
                    $event->increment('current_participants', $participantCount);
                } else {
                    // Rollback if not enough spots
                    $eventBooking->update(['booking_status' => $oldStatus]);
                    if ($eventBooking->invoice) {
                        $eventBooking->invoice->update(['payment_status' => 'unpaid']);
                    }
                    throw new \Exception("Tidak cukup slot tersedia. Sisa slot: {$availableSpots}");
                }
            }
        });

        return redirect()->route('admin.event-bookings.show', $eventBooking)
            ->with('success', 'Pembayaran dikonfirmasi dan peserta terdaftar!');
    }

    /**
     * Update participant attendance.
     */
    public function updateAttendance(Request $request, EventBooking $eventBooking)
    {
        $request->validate([
            'participants' => 'required|array',
            'participants.*.id' => 'required|exists:event_participants,id',
            'participants.*.attendance_status' => 'required|in:hadir,tidak_hadir,pending'
        ]);

        foreach ($request->participants as $participantData) {
            $eventBooking->participants()
                ->where('id', $participantData['id'])
                ->update(['attendance_status' => $participantData['attendance_status']]);
        }

        return redirect()->route('event-bookings.show', $eventBooking)
            ->with('success', 'Status kehadiran peserta berhasil diperbarui!');
    }

    /**
     * Cancel event booking.
     */
    public function cancel(EventBooking $eventBooking)
    {
        DB::transaction(function () use ($eventBooking) {
            $previousStatus = $eventBooking->booking_status;

            $eventBooking->update([
                'booking_status' => 'dibatalkan'
            ]);

            if ($eventBooking->invoice) {
                $eventBooking->invoice->update([
                    'payment_status' => 'cancelled'
                ]);
            }

            // If booking was confirmed, decrement event participants
            if ($previousStatus === 'terdaftar') {
                $eventBooking->event->decrement('current_participants', $eventBooking->participant_count);
            }
        });

        return redirect()->route('admin.event-bookings.show', $eventBooking)
            ->with('success', 'Booking berhasil dibatalkan!');
    }

    /**
     * Remove the specified event booking from storage.
     */
    public function destroy(EventBooking $eventBooking)
    {
        Log::info('Deleting event booking:', ['booking_id' => $eventBooking->id]);

        DB::beginTransaction();
        try {
            // Store info for success message - FIXED: Handle guest users
            $bookingId = $eventBooking->id;
            $userName = $eventBooking->user ? $eventBooking->user->name : ($eventBooking->guest_name ?? 'Guest User');
            $eventName = $eventBooking->event ? $eventBooking->event->title : 'Event Tidak Ditemukan';
            $participantCount = $eventBooking->participant_count;

            // If booking was confirmed, decrement event participants
            if ($eventBooking->booking_status === 'terdaftar' && $eventBooking->event) {
                $eventBooking->event->decrement('current_participants', $participantCount);
                Log::info('Decremented event participants:', [
                    'event_id' => $eventBooking->event->id,
                    'decrement_by' => $participantCount
                ]);
            }

            // Decrement referral code usage if used
            if ($eventBooking->referral_code_id) {
                ReferralCode::where('id', $eventBooking->referral_code_id)->decrement('current_uses');
                Log::info('Decremented referral code usage:', [
                    'referral_code_id' => $eventBooking->referral_code_id
                ]);
            }

            // Delete participants
            $eventBooking->participants()->delete();
            Log::info('Deleted participants for booking:', ['booking_id' => $eventBooking->id]);

            // Delete invoice if exists
            if ($eventBooking->invoice) {
                $eventBooking->invoice->delete();
                Log::info('Deleted invoice for booking:', ['invoice_id' => $eventBooking->invoice->id]);
            }

            // Delete the booking
            $eventBooking->delete();
            Log::info('Successfully deleted event booking:', ['booking_id' => $bookingId]);

            DB::commit();

            return redirect()->route('admin.event-bookings.index')
                ->with('success', "Booking event #{$bookingId} untuk {$userName} ({$eventName}) berhasil dihapus!");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete event booking: ' . $e->getMessage(), [
                'booking_id' => $eventBooking->id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('admin.event-bookings.index')
                ->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
        }
    }
}
