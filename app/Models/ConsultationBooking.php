<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'invoice_id',
        'contact_preference',
        'payment_type',
        'discount_amount',
        'final_price',
        'session_status',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relasi many-to-many ke consultation services.
     * Relasi ini menggunakan model pivot BookingService.
     */
    public function services()
    {
        return $this->belongsToMany(ConsultationService::class, 'booking_service', 'booking_id', 'service_id')
                    ->using(BookingService::class)
                    ->withPivot([
                        'total_price_at_booking',
                        'discount_amount_at_booking',
                        'final_price_at_booking',
                        'referral_code_id',
                        'hours_booked',
                        'booked_date',
                        'booked_time',
                        'session_type',
                        'offline_address',
                 
                        'invoice_id'
                    ])
                    ->withTimestamps();
    }
}
