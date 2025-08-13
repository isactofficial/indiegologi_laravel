<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ConsultationBooking;
use App\Models\BookingService;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $user = Auth::user();
        // [DIUBAH] Ambil ID item yang dipilih dari form
        $selectedItemIds = $request->input('selected_items', []);

        if (empty($selectedItemIds)) {
            return redirect()->route('front.cart.view')->with('error', 'Pilih setidaknya satu layanan untuk di-checkout.');
        }

        $cartItems = CartItem::with('service')
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedItemIds) // Hanya proses item yang dipilih
            ->get();

        DB::beginTransaction();
        try {
            // ... (Logika kalkulasi sama seperti di FrontController)
            $subtotal = 0;
            $totalDiscount = 0;
            $totalToPayNow = 0;

            foreach ($cartItems as $item) {
                // ... (kalkulasi sama)
            }
            $grandTotal = $subtotal - $totalDiscount;

            // 2. Buat Invoice baru
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'invoice_no' => 'INV-' . strtoupper(Str::random(8)) . '-' . date('Ymd'),
                'total_amount' => $grandTotal,
                'paid_amount' => $totalToPayNow,
                'discount_amount' => $totalDiscount,
                'payment_type' => $cartItems->contains('payment_type', 'dp') ? 'dp' : 'full_payment',
                'payment_status' => 'pending',
                'session_type' => 'online',
                'due_date' => now()->addDay(),
            ]);

            // 3. Buat Consultation Booking
            $booking = ConsultationBooking::create([
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'receiver_name' => $user->name,
                'final_price' => $grandTotal,
                'discount_amount' => $totalDiscount,
                'session_status' => 'menunggu pembayaran',
            ]);

            // 4. Pindahkan item dari keranjang ke booking_service
            foreach ($cartItems as $item) {
                BookingService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $item->service_id,
                    // ... (field lainnya)
                ]);
            }

            // 5. [DIUBAH] Kosongkan HANYA item yang di-checkout
            CartItem::where('user_id', $user->id)->whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            return redirect()->route('profile.index')->with('success', 'Booking Anda berhasil dibuat! Silakan selesaikan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('front.cart.view')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
