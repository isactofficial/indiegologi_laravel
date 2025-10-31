<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'booker_name',
        'booker_phone',
        'booker_email',
        'participant_count',
        'total_price',
        'discount_amount',
        'final_price',
        'payment_type',
        'contact_preference',
        'booking_status',
        'payment_status', // TAMBAH INI
        'referral_code_id',
        'invoice_id',
        'guest_name',
        'guest_phone',
        'guest_email'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('booking_status', 'menunggu pembayaran');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('booking_status', 'terdaftar');
    }

    // Methods
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedFinalPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
    }

    // Add these methods to your existing EventBooking model
    public function getBookerDisplayNameAttribute()
    {
        return $this->user ? $this->user->name : ($this->guest_name ?? 'Guest User');
    }

    public function getBookerDisplayEmailAttribute()
    {
        return $this->user ? $this->user->email : ($this->guest_email ?? 'No email');
    }

    public function getBookerDisplayPhoneAttribute()
    {
        return $this->user ? ($this->user->userProfile->phone_number ?? $this->booker_phone) : ($this->guest_phone ?? 'No phone');
    }

    public function getIsGuestAttribute()
    {
        return is_null($this->user_id);
    }

    // TAMBAH METHOD BARU: Untuk mendapatkan status pembayaran yang benar
    public function getEffectivePaymentStatusAttribute()
    {
        // Jika ada invoice, gunakan status dari invoice
        if ($this->invoice) {
            return $this->invoice->payment_status;
        }
        // Jika guest booking, gunakan payment_status dari event_booking
        return $this->payment_status;
    }
}