<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingService extends Pivot
{
    use HasFactory;

    protected $table = 'booking_service';

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
        'invoice_id',
        'user_id',
        'contact_preference',
    ];

    protected $casts = [
        'booked_date' => 'date',
        'booked_time' => 'datetime:H:i',
        'total_price_at_booking' => 'decimal:2',
        'discount_amount_at_booking' => 'decimal:2',
        'final_price_at_booking' => 'decimal:2',
        'hours_booked' => 'integer',
    ];

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeFreeConsultation($query)
    {
        return $query->where('service_id', 'free-consultation');
    }

    public function scopeRegularServices($query)
    {
        return $query->where('service_id', '!=', 'free-consultation');
    }

    public function scopePaidBookings($query)
    {
        return $query->whereHas('invoice', function ($query) {
            $query->where('payment_status', 'paid');
        });
    }

    public function scopePendingOrUnpaidBookings($query)
    {
        return $query->whereHas('invoice', function ($query) {
            $query->whereIn('payment_status', ['unpaid', 'pending'])
                  ->where('due_date', '>=', now());
        });
    }

    public function isFreeConsultation()
    {
        return $this->service_id === 'free-consultation';
    }

    public function isValidBooking()
    {
        if (!$this->invoice) {
            return false;
        }

        return $this->invoice->payment_status === 'paid' || 
               (in_array($this->invoice->payment_status, ['unpaid', 'pending']) && 
                $this->invoice->due_date >= now());
    }
}