<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ConsultationBooking;
use App\Models\Invoice;
use App\Models\ReferralCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // Validasi sudah benar, 'global_payment_type' sudah ada di sini
        $validatedData = $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:cart_items,id',
            'global_payment_type' => ['required', Rule::in(['full_payment', 'dp'])],
        ]);

        // AMBIL TIPE PEMBAYARAN DARI REQUEST YANG SUDAH DIVALIDASI
        $selectedPaymentType = $validatedData['global_payment_type'];

        $user = Auth::user();
        $selectedItemIds = $request->input('selected_items', []);
        if (empty($selectedItemIds)) {
            return back()->with('error', 'Pilih setidaknya satu layanan.');
        }

        $cartItems = CartItem::with(['service', 'referralCode'])
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

            foreach ($cartItems as $item) {
                if (!$item->service) continue;

                $itemPrice = $item->price + ($item->hourly_price * $item->hours);
                $subtotal += $itemPrice;
                $itemDiscount = 0;
                $referralIdToSave = null;

                if ($item->referralCode) {
                    $itemDiscount = $itemPrice * ($item->referralCode->discount_percentage / 100);
                    $totalDiscount += $itemDiscount;
                    $referralIdToSave = $item->referralCode->id;
                    if (!in_array($referralIdToSave, $usedReferralCodeIds)) {
                        $usedReferralCodeIds[] = $referralIdToSave;
                    }
                }

                $servicesToAttach[$item->service_id] = [
                    'total_price_at_booking'     => $itemPrice,
                    'discount_amount_at_booking' => $itemDiscount,
                    'final_price_at_booking'     => $itemPrice - $itemDiscount,
                    'booked_date'                => $item->booked_date,
                    'booked_time'                => $item->booked_time,
                    'hours_booked'               => $item->hours,
                    'session_type'               => $item->session_type,
                    'offline_address'            => $item->session_type == 'online' ? null : $item->offline_address,
                    'referral_code_id'           => $referralIdToSave,
                ];
            }

            $grandTotal = $subtotal - $totalDiscount;

            // Buat Invoice
            $invoice = Invoice::create([
                'user_id'         => $user->id,
                'invoice_no'      => 'INV-' . strtoupper(Str::random(8)),
                'invoice_date'    => now(),
                'due_date'        => now()->addDay(),
                'total_amount'    => $grandTotal,
                'discount_amount' => $totalDiscount,
                'paid_amount'     => 0.00,
                'payment_type'    => $selectedPaymentType, // Perbarui juga di sini untuk konsistensi
                'payment_status'  => 'pending',
                'session_type'    => $firstItem->session_type,
            ]);

            // Buat Booking
            $booking = ConsultationBooking::create([
                'user_id'         => $user->id,
                'invoice_id'      => $invoice->id,
                'receiver_name'   => $user->name,
                'final_price'     => $grandTotal,
                'discount_amount' => $totalDiscount,
                'session_status'  => 'menunggu pembayaran',
                'contact_preference' => $firstItem->contact_preference,
                // PERUBAHAN UTAMA DI SINI
                'payment_type'    => $selectedPaymentType,
            ]);

            $booking->services()->attach($servicesToAttach);

            if (!empty($usedReferralCodeIds)) {
                ReferralCode::whereIn('id', $usedReferralCodeIds)->increment('current_uses');
            }

            CartItem::whereIn('id', $selectedItemIds)->delete();
            DB::commit();

            return redirect()->route('invoice.show', $booking)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
