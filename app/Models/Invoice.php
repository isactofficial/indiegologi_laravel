<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_no',
        'invoice_type',
        'source_type',
        'source_id',
        'parent_invoice_id',
        'revision_number',
        'invoice_date',
        'due_date',
        'subtotal_amount',
        'total_amount',
        'paid_amount',
        'auto_discount_amount',
        'manual_discount_amount',
        'total_discount_amount',
        'final_amount',
        'remaining_amount',
        'payment_type',
        'payment_status',
        'session_type',
        'is_active',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultationBooking()
    {
        return $this->hasOne(ConsultationBooking::class);
    }

    // NEW: Event bookings relationship
    public function eventBookings()
    {
        return $this->hasMany(EventBooking::class);
    }

    public function bookingServices()
    {
        return $this->hasMany(BookingService::class);
    }

    // Helper method to get all invoice items (both services and events)
    public function getAllItems()
    {
        $items = collect();

        // Add consultation services
        if ($this->consultationBooking) {
            foreach ($this->consultationBooking->services as $service) {
                $items->push([
                    'type' => 'service',
                    'item' => $service,
                    'pivot' => $service->pivot,
                    'session_type' => $service->pivot->session_type ?? 'online',
                    'offline_address' => $service->pivot->offline_address ?? null
                ]);
            }
        }

        // Add event bookings
        foreach ($this->eventBookings as $eventBooking) {
            $items->push([
                'type' => 'event',
                'item' => $eventBooking->event,
                'booking' => $eventBooking,
                'participant_count' => $eventBooking->participant_count,
                'session_type' => $eventBooking->event->session_type ?? 'online',
                'offline_address' => $eventBooking->event->session_type === 'offline' ? $eventBooking->event->place : null
            ]);
        }

        return $items;
    }

    // Helper to get the main booking (for backward compatibility)
    public function getMainBooking()
    {
        if ($this->consultationBooking) {
            return $this->consultationBooking;
        }

        if ($this->eventBookings->isNotEmpty()) {
            return $this->eventBookings->first();
        }

        return null;
    }
}
