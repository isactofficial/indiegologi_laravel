<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeConsultationParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_booking_id',
        'full_name',
        'phone_number',
        'email',
        'attendance_status'
    ];

    // Relationships
    public function consultationBooking()
    {
        return $this->belongsTo(FreeConsultationBooking::class, 'consultation_booking_id');
    }

    // Scopes
    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'hadir');
    }

    public function scopePending($query)
    {
        return $query->where('attendance_status', 'pending');
    }
}