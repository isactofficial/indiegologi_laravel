<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FreeConsultationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'scheduled_date',
        'scheduled_time',
        'is_available',
        'max_participants',
        'current_bookings',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'is_available' => 'boolean',
        'max_participants' => 'integer',
        'current_bookings' => 'integer',
    ];

    public function type()
    {
        return $this->belongsTo(FreeConsultationType::class, 'type_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'free_consultation_schedule_id');
    }

    public function bookingServices()
    {
        return $this->hasMany(BookingService::class, 'free_consultation_schedule_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->whereRaw('current_bookings < max_participants');
    }

    public function scopeFuture($query)
    {
        return $query->where('scheduled_date', '>=', now()->toDateString());
    }

    public function scopeForType($query, $typeId)
    {
        return $query->where('type_id', $typeId);
    }

    // Accessors & Mutators
    public function getFormattedDateAttribute()
    {
        return $this->scheduled_date ? Carbon::parse($this->scheduled_date)->format('d M Y') : '';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->scheduled_time ? Carbon::parse($this->scheduled_time)->format('H:i') : '';
    }

    public function getFormattedDateTimeAttribute()
    {
        return $this->formatted_date . ' ' . $this->formatted_time;
    }

    // Methods
    public function isAvailable()
    {
        return $this->is_available && 
               $this->current_bookings < $this->max_participants &&
               $this->scheduled_date >= now()->toDateString();
    }

    public function incrementBooking()
    {
        $this->increment('current_bookings');
        
        // Mark as unavailable if fully booked
        if ($this->current_bookings >= $this->max_participants) {
            $this->update(['is_available' => false]);
        }
    }

    public function decrementBooking()
    {
        if ($this->current_bookings > 0) {
            $this->decrement('current_bookings');
            
            // Mark as available if there's space again
            if ($this->current_bookings < $this->max_participants) {
                $this->update(['is_available' => true]);
            }
        }
    }
}