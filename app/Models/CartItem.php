<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Pastikan Carbon diimpor untuk validasi tanggal

class CartItem extends Model
{
    use HasFactory;

    // Izinkan kolom ini untuk diisi secara massal
    protected $fillable = [
        'user_id',
        'service_id',
        'quantity',
        'price',
        'hourly_price',
        'hours',
        'booked_date',
        'booked_time',
        'session_type',
        'offline_address',
        'contact_preference',
        'payment_type', // Tetap ada di fillable untuk kompatibilitas database/data lama
        'referral_code',
    ];

    /**
     * Tambahkan properti virtual (accessors) untuk perhitungan harga.
     * Ini akan membuat properti seperti $item->item_subtotal bisa diakses.
     */
    protected $appends = [
        'item_subtotal',
        'discount_amount',
        'final_item_price',
        'total_to_pay', // Tetap ada, tapi logikanya akan sederhana (harga penuh)
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model ConsultationService
     */
    public function service()
    {
        return $this->belongsTo(ConsultationService::class, 'service_id');
    }

    /**
     * Relasi ke model ReferralCode
     * Ini tetap diperlukan untuk memuat data ReferralCode yang terkait.
     */
    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code', 'code');
    }

    /**
     * Accessor untuk menghitung subtotal per item (harga dasar + harga per jam).
     *
     * @return float
     */
    public function getItemSubtotalAttribute(): float
    {
        return (float)$this->price + ((float)$this->hourly_price * (int)$this->hours);
    }

    /**
     * Accessor untuk menghitung jumlah diskon per item.
     * Logika validasi referral code ditempatkan di sini.
     *
     * @return float
     */
    public function getDiscountAmountAttribute(): float
    {
        $itemDiscount = 0.0;

        // Pastikan ada kode referral dan relasi referralCode sudah dimuat
        if ($this->referral_code && $this->referralCode) {
            $referral = $this->referralCode;

            // Validasi apakah kode referral sudah kedaluwarsa
            $isExpired = $referral->valid_until && Carbon::now()->gt($referral->valid_until);

            // Validasi apakah batas penggunaan sudah tercapai
            $isUsedUp = $referral->max_uses && $referral->current_uses >= $referral->max_uses;

            // Jika kode referral tidak kedaluwarsa dan belum habis batas pakainya
            if (!$isExpired && !$isUsedUp) {
                $itemSubtotal = $this->item_subtotal;
                $itemDiscount = ($itemSubtotal * $referral->discount_percentage) / 100;
            }
        }
        return (float)$itemDiscount;
    }

    /**
     * Accessor untuk menghitung harga akhir per item setelah diskon.
     *
     * @return float
     */
    public function getFinalItemPriceAttribute(): float
    {
        return $this->item_subtotal - $this->discount_amount;
    }

    /**
     * Accessor untuk menghitung total yang harus dibayar sekarang untuk item ini.
     * Karena payment_type global, accessor ini akan selalu mengembalikan harga penuh item.
     * Logika DP/Full Payment akan ditangani di calculateSummary() di controller.
     *
     * @return float
     */
    public function getTotalToPayAttribute(): float
    {
        return $this->final_item_price;
    }
}
