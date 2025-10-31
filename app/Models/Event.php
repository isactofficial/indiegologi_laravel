<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug', 
        'description',
        'event_date',
        'event_time',
        'place',
        'max_participants',
        'current_participants',
        'price',
        'session_type',
        'status',
        'thumbnail'
    ];

    protected $casts = [
        'event_date' => 'date',
        'price' => 'decimal:2'
    ];

    // FIXED: Use EventBooking instead of BookingService
    public function eventBookings()
    {
        return $this->hasMany(EventBooking::class);
    }

    public function confirmedBookings()
    {
        return $this->hasMany(EventBooking::class)
                    ->where('booking_status', 'terdaftar');
    }

    // Keep this for backward compatibility but point to EventBooking
    public function bookings()
    {
        return $this->hasMany(EventBooking::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function scopeAvailable($query)
    {
        return $query->published()
                    ->upcoming()
                    ->whereColumn('current_participants', '<', 'max_participants');
    }

    // Methods
    public function isAvailable()
    {
        return $this->status === 'published' 
            && $this->event_date >= now()->toDateString()
            // HAPUS: && $this->registration_deadline >= now()->toDateString()
            && $this->current_participants < $this->max_participants;
    }

    public function getSpotsLeftAttribute()
    {
        return $this->max_participants - $this->current_participants;
    }

    public function incrementParticipants($count = 1)
    {
        return $this->increment('current_participants', $count);
    }

    public function decrementParticipants($count = 1)
    {
        return $this->decrement('current_participants', $count);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedEventDateTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->event_date->format('Y-m-d') . ' ' . $this->event_time)
                            ->format('l, j F Y \\a\\t H:i');
    }
}