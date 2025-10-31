<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\CartParticipant;
use App\Models\CartItem;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required',
            'place' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            // HAPUS: 'registration_deadline' => 'required|date|before_or_equal:event_date',
            'session_type' => 'required|in:online,offline',
            'status' => 'required|in:draft,published,cancelled',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(6);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('event-thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'place' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            // HAPUS: 'registration_deadline' => 'required|date|before_or_equal:event_date',
            'session_type' => 'required|in:online,offline',
            'status' => 'required|in:draft,published,cancelled',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($event->thumbnail) {
                Storage::disk('public')->delete($event->thumbnail);
            }

            $path = $request->file('thumbnail')->store('event-thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Delete thumbnail
        if ($event->thumbnail) {
            Storage::disk('public')->delete($event->thumbnail);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    // NEW: Process event booking with multiple participants
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

            // ✅ CRITICAL FIX: Store the ORIGINAL price, not the discounted price
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'participant_count' => $participantCount,
                'original_price' => $originalPricePerPerson,  // ✅ ORIGINAL event price per person
                'price' => $originalPricePerPerson,           // ✅ Same as original for events
                'quantity' => 1,
                'hours' => 0,
                'hourly_price' => 0.00,                       // ✅ Events don't have hourly pricing
                'booked_date' => $event->event_date,
                'booked_time' => $event->event_time,
                'session_type' => $event->session_type,
                'offline_address' => $event->session_type === 'offline' ? $event->place : null,
                'contact_preference' => $request->contact_preference,
                'payment_type' => 'full_payment',
                'referral_code' => $referralCodeToSave,
                'discount_amount' => $discountAmount,         // ✅ Total discount amount
                'item_type' => 'event'
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

            return response()->json([
                'success' => true,
                'message' => 'Event berhasil ditambahkan ke keranjang!',
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
}
