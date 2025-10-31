<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_booking_id',
        'full_name',
        'phone_number',
        'email',
        'attendance_status'
    ];

    // Relationships
    public function eventBooking()
    {
        return $this->belongsTo(EventBooking::class);
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