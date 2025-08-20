<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot; // <-- Pastikan ini Pivot!

class BookingService extends Pivot // <-- Harus extends Pivot untuk custom pivot model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_service'; // Nama tabel singular, sudah benar

    /**
     * The attributes that are mass assignable.
     * Pastikan semua kolom pivot yang akan diisi ada di sini.
     * booking_id dan service_id tidak perlu di fillable karena Laravel menanganinya.
     */
    protected $fillable = [
        'total_price_at_booking',
        'discount_amount_at_booking',
        'final_price_at_booking',
        'booked_date',
        'booked_time',
        'hours_booked',
        'session_type',
        'offline_address',
        'referral_code_id',
        'invoice_id', // Ini harus ada di fillable jika kolomnya ada di tabel
        // 'user_id' TIDAK PERLU DI SINI, karena user_id ada di ConsultationBooking, bukan di pivot booking_service
    ];

    // Relasi ke referral code
    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code_id');
    }

    // Relasi ke Invoice (ini akan berfungsi setelah invoice_id ada di tabel)
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Catatan: relasi ke ConsultationService atau User tidak perlu ada di model pivot ini
    // karena ini adalah model perantara, bukan model utama untuk entitas tersebut.
}
