<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationService;
use App\Models\ReferralCode;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;
use App\Models\ConsultationBooking;

class ConsultationBookingController extends Controller
{
    /**
     * Display a listing of the consultation bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = ConsultationBooking::with(['user', 'services', 'invoice'])
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(10);
        return view('consultation-bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new consultation booking.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::all();
        $services = ConsultationService::whereIn('status', ['published', 'special'])->get();
        $referralCodes = ReferralCode::all();

        return view('consultation-bookings.create', compact('users', 'services', 'referralCodes'));
    }

    /**
     * Store a newly created consultation booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'services' => 'required|array',
            'services.*.id' => 'required|exists:consultation_services,id',
            'services.*.hours' => 'required|integer|min:0',
            'services.*.booked_date' => 'required|date',
            'services.*.booked_time' => 'required|date_format:H:i',
            'services.*.session_type' => 'required|string|in:Online,Offline',
            'services.*.offline_address' => 'nullable|string|required_if:services.*.session_type,Offline',
            'services.*.referral_code' => 'nullable|string|exists:referral_codes,code',
            'contact_preference' => 'required|string|in:chat_only,chat_and_call',
            'payment_type' => 'required|string|in:dp,full_payment',
            'receiver_name' => 'nullable|string|max:255',
        ]);

        $totalFinalPrice = 0;
        $totalDiscountAmount = 0;
        $attachedServices = [];

        // 2. Iterasi dan Hitung Harga per Layanan
        foreach ($request->services as $serviceData) {
            $service = ConsultationService::find($serviceData['id']);
            $hours = (int) $serviceData['hours'];
            $referralCode = null;
            $discountAmountPerService = 0;

            // Perhitungan Harga Awal Per Layanan: Harga Dasar + (Harga per Jam * Jumlah Jam)
            $totalPriceAtBooking = $service->price + ($service->hourly_price * $hours);

            // Terapkan diskon per layanan jika ada kode referral yang valid
            if (!empty($serviceData['referral_code'])) {
                $referralCode = ReferralCode::where('code', Str::upper($serviceData['referral_code']))
                                            ->where(function ($query) {
                                                $query->whereNull('valid_until')
                                                      ->orWhere('valid_until', '>=', Carbon::now());
                                            })
                                            ->first();
                if ($referralCode && ($referralCode->max_uses === null || $referralCode->current_uses < $referralCode->max_uses)) {
                    $discountPercentage = $referralCode->discount_percentage;
                    $discountAmountPerService = ($totalPriceAtBooking * $discountPercentage) / 100;
                    $referralCode->increment('current_uses'); // Naikkan counter saat kode digunakan
                } else {
                    $referralCode = null;
                }
            }

            $finalPricePerService = max(0, $totalPriceAtBooking - $discountAmountPerService);

            $totalFinalPrice += $finalPricePerService;
            $totalDiscountAmount += $discountAmountPerService;

            // Siapkan data untuk tabel pivot
            $attachedServices[] = [
                'service_id' => $service->id,
                'hours_booked' => $hours,
                'booked_date' => $serviceData['booked_date'],
                'booked_time' => $serviceData['booked_time'],
                'session_type' => $serviceData['session_type'],
                'offline_address' => $serviceData['offline_address'] ?? null,
                'total_price_at_booking' => $totalPriceAtBooking, // Harga sebelum diskon
                'discount_amount_at_booking' => $discountAmountPerService,
                'final_price_at_booking' => $finalPricePerService,
                'referral_code_id' => $referralCode ? $referralCode->id : null,
            ];
        }

        // 3. Hitung jumlah yang harus dibayar berdasarkan payment_type
        $paidAmount = ($request->payment_type === 'dp') ? ($totalFinalPrice * 0.5) : $totalFinalPrice;

        // 4. Buat Entri Invoice Baru
        $invoice = Invoice::create([
            'user_id' => $request->user_id,
            'invoice_no' => 'INV-' . time(),
            'invoice_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(7),
            'total_amount' => $totalFinalPrice,
            'discount_amount' => $totalDiscountAmount,
            'paid_amount' => $paidAmount,
            'payment_type' => $request->payment_type,
            'payment_status' => 'Pending',
        ]);

        // 5. Buat Entri ConsultationBooking Baru
        $booking = ConsultationBooking::create([
            'user_id' => $request->user_id,
            'invoice_id' => $invoice->id,
            'receiver_name' => $request->receiver_name,
            'contact_preference' => $request->contact_preference,
            'payment_type' => $request->payment_type,
            'session_status' => 'menunggu pembayaran',
            'discount_amount' => $totalDiscountAmount,
            'final_price' => $totalFinalPrice,
        ]);

        // Perbaikan: Menggunakan `attach()` dalam loop untuk mendukung multiple services yang sama
        foreach($attachedServices as $service) {
             $booking->services()->attach($service['service_id'], [
                'hours_booked' => $service['hours_booked'],
                'booked_date' => $service['booked_date'],
                'booked_time' => $service['booked_time'],
                'session_type' => $service['session_type'],
                'offline_address' => $service['offline_address'],
                'total_price_at_booking' => $service['total_price_at_booking'],
                'discount_amount_at_booking' => $service['discount_amount_at_booking'],
                'final_price_at_booking' => $service['final_price_at_booking'],
                'referral_code_id' => $service['referral_code_id'],
            ]);
        }

        return redirect()->route('admin.consultation-bookings.index')->with('success', 'Booking berhasil dibuat!');
    }

    /**
     * Display the specified consultation booking.
     *
     * @param  \App\Models\ConsultationBooking  $consultationBooking
     * @return \Illuminate\View\View
     */
    public function show(ConsultationBooking $consultationBooking)
    {
        // PERBAIKAN: Eager load referralCode dari tabel pivot
        $consultationBooking->load(['user', 'invoice', 'services' => function ($query) {
            $query->withPivot('referral_code_id');
        }]);

        return view('consultation-bookings.show', compact('consultationBooking'));
    }

    /**
     * Generate and download PDF invoice for the specified booking.
     *
     * @param  \App\Models\ConsultationBooking  $consultationBooking
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(ConsultationBooking $consultationBooking)
    {
        // Eager load relationships yang diperlukan, sama seperti di metode show
        $consultationBooking->load(['user', 'invoice', 'services' => function ($query) {
            $query->withPivot('referral_code_id')->with('referralCode');
        }]);

        // Muat view show.blade.php dengan data booking
        $pdf = PDF::loadView('admin.consultation-bookings.show', compact('consultationBooking'));

        // Kembalikan PDF sebagai unduhan
        $invoiceNo = optional($consultationBooking->invoice)->invoice_no ?? 'Invoice';
        return $pdf->download($invoiceNo . '.pdf');
    }

    /**
     * Show the form for editing the specified consultation booking.
     *
     * @param  \App\Models\ConsultationBooking  $consultationBooking
     * @return \Illuminate\View\View
     */
    public function edit(ConsultationBooking $consultationBooking)
    {
        $users = User::all();
        $services = ConsultationService::whereIn('status', ['published', 'special'])->get();
        $referralCodes = ReferralCode::all();

        // Eager load pivot data
        $consultationBooking->load('services');

        return view('consultation-bookings.edit', compact('consultationBooking', 'users', 'services', 'referralCodes'));
    }

    /**
     * Update the specified consultation booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConsultationBooking  $consultationBooking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ConsultationBooking $consultationBooking)
    {
        // 1. Validasi Input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'services' => 'required|array',
            'services.*.id' => 'required|exists:consultation_services,id',
            'services.*.hours' => 'required|integer|min:0',
            'services.*.booked_date' => 'required|date',
            'services.*.booked_time' => 'required|date_format:H:i',
            'services.*.session_type' => 'required|string|in:Online,Offline',
            'services.*.offline_address' => 'nullable|string|required_if:services.*.session_type,Offline',
            'services.*.referral_code' => 'nullable|string|exists:referral_codes,code',
            'contact_preference' => 'required|string|in:chat_only,chat_and_call',
            'payment_type' => 'required|string|in:dp,full_payment',
            'receiver_name' => 'nullable|string|max:255',
            'session_status' => 'required|string|in:menunggu pembayaran,terdaftar,ongoing,selesai,dibatalkan',
            'payment_status' => 'required|string|in:Pending,Paid,Failed',
        ]);

        $totalFinalPrice = 0;
        $totalDiscountAmount = 0;
        $attachedServices = [];
        $appliedReferralCodes = [];

        // Ambil ID referral codes yang terkait dengan booking saat ini
        $oldReferralCodeIds = $consultationBooking->services()->pluck('referral_code_id')->filter()->toArray();
        $newReferralCodeIds = [];

        // 2. Iterasi dan Hitung Ulang Harga per Layanan
        foreach ($request->services as $serviceData) {
            $service = ConsultationService::find($serviceData['id']);
            $hours = (int) $serviceData['hours'];
            $referralCode = null;
            $discountAmountPerService = 0;

            $totalPriceAtBooking = $service->price + ($service->hourly_price * $hours);

            if (!empty($serviceData['referral_code'])) {
                $referralCode = ReferralCode::where('code', Str::upper($serviceData['referral_code']))->first();
                if ($referralCode) {
                    $discountPercentage = $referralCode->discount_percentage;
                    $discountAmountPerService = ($totalPriceAtBooking * $discountPercentage) / 100;
                    $newReferralCodeIds[] = $referralCode->id;
                }
            }

            $finalPricePerService = max(0, $totalPriceAtBooking - $discountAmountPerService);

            $totalFinalPrice += $finalPricePerService;
            $totalDiscountAmount += $discountAmountPerService;

            $attachedServices[$service->id] = [
                'hours_booked' => $hours,
                'booked_date' => $serviceData['booked_date'],
                'booked_time' => $serviceData['booked_time'],
                'session_type' => $serviceData['session_type'],
                'offline_address' => $serviceData['offline_address'] ?? null,
                'total_price_at_booking' => $totalPriceAtBooking,
                'discount_amount_at_booking' => $discountAmountPerService,
                'final_price_at_booking' => $finalPricePerService,
                'referral_code_id' => $referralCode ? $referralCode->id : null,
            ];
        }

        // 3. Update penggunaan kode referral
        $removedCodes = array_diff($oldReferralCodeIds, $newReferralCodeIds);
        $addedCodes = array_diff($newReferralCodeIds, $oldReferralCodeIds);

        if (!empty($removedCodes)) {
            ReferralCode::whereIn('id', $removedCodes)->decrement('current_uses');
        }
        if (!empty($addedCodes)) {
            ReferralCode::whereIn('id', $addedCodes)->increment('current_uses');
        }

        // 4. Hitung jumlah yang harus dibayar berdasarkan payment_type
        $paidAmount = ($request->payment_type === 'dp') ? ($totalFinalPrice * 0.5) : $totalFinalPrice;

        // 5. Update Entri Invoice
        $consultationBooking->invoice()->update([
            'user_id' => $request->user_id,
            'total_amount' => $totalFinalPrice,
            'discount_amount' => $totalDiscountAmount,
            'paid_amount' => $paidAmount,
            'payment_type' => $request->payment_type,
            'payment_status' => $request->payment_status,
        ]);

        // 6. Update Entri ConsultationBooking
        $consultationBooking->update([
            'user_id' => $request->user_id,
            'receiver_name' => $request->receiver_name,
            'contact_preference' => $request->contact_preference,
            'payment_type' => $request->payment_type,
            'session_status' => $request->session_status,
            'discount_amount' => $totalDiscountAmount,
            'final_price' => $totalFinalPrice,
        ]);

        $consultationBooking->services()->sync($attachedServices);

        return redirect()->route('admin.consultation-bookings.index')->with('success', 'Booking berhasil diperbarui!');
    }

    /**
     * Remove the specified consultation booking from storage.
     *
     * @param  \App\Models\ConsultationBooking  $consultationBooking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ConsultationBooking $consultationBooking)
    {
        $referralCodeIds = $consultationBooking->services()->pluck('referral_code_id')->filter();
        if ($referralCodeIds->isNotEmpty()) {
            ReferralCode::whereIn('id', $referralCodeIds)->decrement('current_uses');
        }

        if ($consultationBooking->invoice) {
            $consultationBooking->invoice->delete();
        }

        $consultationBooking->services()->detach();

        $consultationBooking->delete();

        return redirect()->route('admin.consultation-bookings.index')->with('success', 'Booking berhasil dihapus!');
    }

    public function showUserProfile(User $user)
    {
        // Load relasi 'profile' untuk mendapatkan data tambahan
        $user->load('profile');

        return view('admin.users.show', compact('user'));
    }
}
