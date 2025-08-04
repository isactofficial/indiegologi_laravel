<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'valid_from',
        'valid_until',
        'max_uses',
        'current_uses',
        'created_by',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function consultationBookings()
    {
        return $this->hasMany(ConsultationBooking::class, 'referral_code_id');
    }
}
