<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CartItem;
use App\Models\CartParticipant;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        // UBAH: Tampilkan semua event published yang upcoming, tidak peduli kuota
        $events = Event::published()->upcoming()->latest()->paginate(9);
        return view('front.events.index', compact('events'));
    }

    public function show($slug)
    {
        // UBAH: Tampilkan event meskipun sudah penuh
        $event = Event::where('slug', $slug)->published()->firstOrFail();
        return view('events.show', compact('event'));
    }

    // NEW: Show event booking form
    public function book($slug)
    {
        // UBAH: Tampilkan form booking meskipun event penuh
        $event = Event::where('slug', $slug)->published()->firstOrFail();
        return view('front.events.book', compact('event'));
    }

    // In EventController.php - Replace the processBooking method with this CORRECTED version:
    public function processBooking(Request $request, Event $event)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login untuk mendaftar event.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'participant_count' => 'required|integer|min:1',
            'contact_preference' => 'required|in:chat_only,chat_and_call',
            'referral_code' => 'nullable|string', // HAPUS: exists:referral_codes,code
            'participants' => 'required|array|min:1',
            'participants.*.full_name' => 'required|string|max:255',
            'participants.*.phone_number' => 'required|string|max:20',
            'participants.*.email' => 'nullable|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $existingCartItem = CartItem::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingCartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki event ini di keranjang.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // ✅ CORRECT: Get the original event price (NOT discounted)
            $originalPricePerPerson = $event->price; // This is the ACTUAL original price from events table
            $participantCount = $request->participant_count;
            $subtotal = $originalPricePerPerson * $participantCount;

            // GANTI bagian apply referral code discount (sekitar line 115-145) dengan:

            $discountAmount = 0;
            $referralCodeToSave = null;
            $referralCodeId = null;

            // Apply referral code discount to SUBTOTAL
            if ($request->referral_code) {
                $referralCode = ReferralCode::where('code', $request->referral_code)->first();

                if ($referralCode && $referralCode->isValid()) {
                    // Calculate discount based on type
                    if ($referralCode->discount_type === 'fixed') {
                        // Fixed discount: amount per participant
                        $discountAmount = $referralCode->discount_amount * $participantCount;
                    } else {
                        // Percentage discount
                        $discountAmount = $subtotal * ($referralCode->discount_percentage / 100);
                    }

                    // Ensure discount doesn't exceed subtotal
                    $discountAmount = min($discountAmount, $subtotal);

                    $referralCodeToSave = $request->referral_code;
                    $referralCodeId = $referralCode->id;

                    Log::info('Referral code applied successfully', [
                        'code' => $request->referral_code,
                        'discount_type' => $referralCode->discount_type,
                        'discount_amount' => $discountAmount,
                        'participant_count' => $participantCount,
                        'subtotal' => $subtotal
                    ]);
                } else {
                    Log::warning('Invalid referral code for event booking', [
                        'code' => $request->referral_code,
                        'is_valid' => $referralCode ? $referralCode->isValid() : false
                    ]);
                }
            }

            // UPDATE cart item creation untuk menyimpan referral_code_id juga
            // FUNGSI INI SUDAH BENAR DI KODE ANDA
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'participant_count' => $participantCount,
                'original_price' => $originalPricePerPerson,
                'price' => $originalPricePerPerson,
                'quantity' => 1,
                'hours' => 0,
                'hourly_price' => 0.00,
                'booked_date' => $event->event_date,
                'booked_time' => $event->event_time,
                'session_type' => $event->session_type,
                'offline_address' => $event->session_type === 'offline' ? $event->place : null,
                'contact_preference' => $request->contact_preference,
                'payment_type' => 'full_payment',
                'referral_code' => $referralCodeToSave, // string code
                'referral_code_id' => $referralCodeId, // ADD THIS LINE - ID reference
                'discount_amount' => $discountAmount,
                'item_type' => 'event' // <-- INI SUDAH BENAR
            ]);

            Log::info('Cart item created for event', [
                'cart_item_id' => $cartItem->id,
                'event_id' => $event->id,
                'original_price_per_person' => $originalPricePerPerson,
                'participant_count' => $participantCount,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'final_total' => $subtotal - $discountAmount,
                'referral_code' => $referralCodeToSave
            ]);

            // Save participant data
            foreach ($request->participants as $participantData) {
                CartParticipant::create([
                    'cart_item_id' => $cartItem->id,
                    'full_name' => $participantData['full_name'],
                    'phone_number' => $participantData['phone_number'],
                    'email' => $participantData['email'] ?? null
                ]);
            }

            DB::commit();

            $cartCount = CartItem::where('user_id', Auth::id())->count();

            // Build success message
            $successMessage = 'Event berhasil ditambahkan ke keranjang!';

            if ($referralCodeToSave && $referralCode) {
                if ($referralCode->discount_type === 'fixed') {
                    $successMessage .= " Diskon Rp " . number_format($referralCode->discount_amount, 0, ',', '.') . " dari kode referral telah diterapkan.";
                } else {
                    $successMessage .= " Diskon {$referralCode->discount_percentage}% dari kode referral telah diterapkan.";
                }
            }

            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'cart_count' => $cartCount,
                'redirect_url' => route('front.cart.view')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Event booking failed: ' . $e->getMessage(), [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar event: ' . $e->getMessage()
            ], 500);
        }
    }

    // NEW: Edit event booking in cart
    public function editBooking(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id() || !$cartItem->isEvent()) {
            abort(404);
        }

        $event = $cartItem->event;
        $participants = $cartItem->participantData;

        return view('front.events.edit-booking', compact('event', 'cartItem', 'participants'));
    }

    // NEW: Update event booking
    public function updateBooking(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id() || !$cartItem->isEvent()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $event = $cartItem->event;

        $validator = Validator::make($request->all(), [
            'participant_count' => 'required|integer|min:1|max:',
            'contact_preference' => 'required|in:chat_only,chat_and_call',
            'participants' => 'required|array|min:1|max:',
            'participants.*.full_name' => 'required|string|max:255',
            'participants.*.phone_number' => 'required|string|max:20',
            'participants.*.email' => 'nullable|email|max:255'
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
            // Update cart item
            $cartItem->update([
                'participant_count' => $request->participant_count,
                'contact_preference' => $request->contact_preference,
            ]);

            // Delete existing participants
            $cartItem->participantData()->delete();

            // Create new participants
            foreach ($request->participants as $participantData) {
                CartParticipant::create([
                    'cart_item_id' => $cartItem->id,
                    'full_name' => $participantData['full_name'],
                    'phone_number' => $participantData['phone_number'],
                    'email' => $participantData['email'] ?? null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diperbarui!',
                'redirect_url' => route('front.cart.view')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addToCart(Request $request, Event $event)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login untuk mendaftar event.'
            ], 401);
        }

        // Check if event is available
        if (!$event->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Event sudah penuh atau pendaftaran ditutup.'
            ], 422);
        }

        // Check if user already registered for this event
        $existingRegistration = CartItem::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar untuk event ini.'
            ], 422);
        }

        try {
            // Create cart item for event
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'price' => $event->price,
                'quantity' => 1,
                'hours' => 0,
                'booked_date' => $event->event_date,
                'booked_time' => $event->event_time,
                'session_type' => $event->session_type,
                'offline_address' => $event->session_type === 'offline' ? $event->place : null,
                'contact_preference' => 'chat_and_call',
                'payment_type' => 'full_payment',
                
                // ⬇️ TAMBAHKAN BARIS INI (INI YANG HILANG) ⬇️
                'item_type' => 'event'
            ]);

            $cartCount = CartItem::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Event berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftar event: ' . $e->getMessage()
            ], 500);
        }
    }
}
