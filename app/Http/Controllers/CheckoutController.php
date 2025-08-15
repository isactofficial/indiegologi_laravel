<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ConsultationBooking;
use App\Models\BookingService;
use App\Models\Invoice;
use App\Models\ReferralCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // 1. Validasi & Ambil Data
        $user = Auth::user();
        $selectedItemIds = $request->input('selected_items', []);

        if (empty($selectedItemIds)) {
            return redirect()->route('front.cart.view')->with('error', 'Pilih setidaknya satu layanan untuk di-checkout.');
        }

        $cartItems = CartItem::with(['service', 'referralCode'])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedItemIds)
            ->get();

        if ($cartItems->isEmpty()) {
             return redirect()->route('front.cart.view')->with('error', 'Item yang dipilih tidak valid atau tidak ditemukan di keranjang.');
        }

        DB::beginTransaction();
        try {
            // 2. Kalkulasi Harga
            $subtotal = 0;
            $totalDiscount = 0;
            $firstItem = $cartItems->first();

            foreach ($cartItems as $item) {
                // Harga Dasar + (Harga per Jam * Jumlah Jam)
                // Asumsi `price` adalah harga dasar dan `hourly_price` adalah harga per jam.
                $itemPrice = $item->price + ($item->hourly_price * $item->hours);
                $subtotal += $itemPrice;

                $itemDiscount = 0;
                if ($item->referralCode) {
                    // Diskon dihitung dari total harga item
                    $itemDiscount = $itemPrice * ($item->referralCode->discount_percentage / 100);
                    $totalDiscount += $itemDiscount;
                }
            }
            $grandTotal = $subtotal - $totalDiscount;
            $paymentType = $cartItems->contains('payment_type', 'dp') ? 'dp' : 'full_payment';

            // 3. Buat data di tabel `invoices`
            $invoice = Invoice::create([
                'user_id'         => $user->id,
                'invoice_no'      => 'INV-' . strtoupper(Str::random(8)),
                'due_date'        => now()->addDay(),
                'total_amount'    => $grandTotal,
                'paid_amount'     => 0.00,
                'discount_amount' => $totalDiscount,
                'payment_type'    => $paymentType,
                'payment_status'  => 'pending',
                'session_type'    => $firstItem->session_type,
            ]);

            // 4. Buat data di tabel `consultation_bookings`
            $booking = ConsultationBooking::create([
                'user_id'         => $user->id,
                'invoice_id'      => $invoice->id,
                'receiver_name'   => $user->name,
                'final_price'     => $grandTotal,
                'discount_amount' => $totalDiscount,
                'session_status'  => 'menunggu pembayaran',
                'contact_preference' => $firstItem->contact_preference,
                'referral_code_id' => $firstItem->referral_code_id,
                'session_type'    => $firstItem->session_type,
                'offline_address' => $firstItem->session_type == 'online' ? null : $firstItem->offline_address,
                'payment_type'    => $paymentType,
                'service_id'      => $firstItem->service_id,
            ]);

            // 5. Pindahkan setiap detail item ke tabel `booking_service`
            foreach ($cartItems as $item) {

                $itemPrice = $item->price + ($item->hourly_price * $item->hours);
                $itemDiscount = 0;
                if ($item->referralCode) {
                    $itemDiscount = $itemPrice * ($item->referralCode->discount_percentage / 100);
                }

                BookingService::create([
                    'booking_id'                 => $booking->id,
                    'service_id'                 => $item->service_id,
                    'total_price_at_booking'     => $itemPrice,
                    'discount_amount_at_booking' => $itemDiscount,
                    'final_price_at_booking'     => $itemPrice - $itemDiscount,
                    'booked_date'                => $item->booked_date,
                    'booked_time'                => $item->booked_time,
                    'hours_booked'               => $item->hours,
                    'session_type'               => $item->session_type,
                    'offline_address'            => $item->session_type == 'online' ? null : $item->offline_address,
                    'referral_code_id'           => $item->referral_code_id,
                ]);
            }

            // 6. Hapus item yang sudah di-checkout
            CartItem::where('user_id', $user->id)->whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            return redirect()->route('invoice.show', ['consultationBooking' => $booking->id])
                             ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('front.cart.view')->with('error', 'Terjadi kesalahan saat memproses booking: ' . $e->getMessage());
        }
    }
}
