<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'payment_type',
        'referral_code',
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
}