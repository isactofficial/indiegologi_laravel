<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationService extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'hourly_price',
        'status',
        'short_description',
        'product_description',
        'thumbnail',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'hourly_price' => 'decimal:2',
    ];


    public function consultationBookings()
    {
        return $this->belongsToMany(ConsultationBooking::class, 'booking_service', 'service_id', 'booking_id')
                    ->using(BookingService::class) // <-- WAJIB: Tentukan model pivotnya di sini
                    ->withPivot(
                        'total_price_at_booking',
                        'discount_amount_at_booking',
                        'final_price_at_booking',
                        'referral_code_id',
                        'hours_booked',
                        'booked_date',
                        'booked_time',
                        'session_type',
                        'offline_address'
                    )
                    ->withTimestamps();
    }
}
