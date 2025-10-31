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

    /**
     * Get all booking services (including events and free consultations)
     */
    public function bookingServices()
    {
        return $this->hasMany(BookingService::class, 'booking_id');
    }

    /**
     * Get events through booking services
     */
    public function events()
    {
        return $this->hasManyThrough(
            Event::class,
            BookingService::class,
            'booking_id', // Foreign key on booking_service table
            'id', // Foreign key on events table  
            'id', // Local key on consultation_bookings table
            'event_id' // Local key on booking_service table
        );
    }

    /**
     * Get all bookable items (services + events)
     */
    public function getBookableItemsAttribute()
    {
        $items = collect();

        // Get services
        foreach ($this->services as $service) {
            $items->push([
                'type' => 'service',
                'item' => $service,
                'pivot' => $service->pivot
            ]);
        }

        // Get events
        foreach ($this->bookingServices()->whereNotNull('event_id')->with('event')->get() as $bookingService) {
            if ($bookingService->event) {
                $items->push([
                    'type' => 'event',
                    'item' => $bookingService->event,
                    'pivot' => $bookingService
                ]);
            }
        }

        // Get free consultations
        foreach ($this->bookingServices()->whereNotNull('free_consultation_type_id')->with('freeConsultationType')->get() as $bookingService) {
            $items->push([
                'type' => 'free_consultation',
                'item' => $bookingService->freeConsultationType,
                'pivot' => $bookingService
            ]);
        }

        return $items;
    }

    // Di ConsultationBooking.php - tambahkan method untuk mendapatkan semua items
    public function getAllItemsAttribute()
    {
        $items = collect();

        // Get services
        foreach ($this->services as $service) {
            $items->push([
                'type' => 'service',
                'item' => $service,
                'pivot' => $service->pivot,
                'title' => $service->title,
                'price' => $service->pivot->final_price_at_booking,
                'schedule' => $service->pivot->booked_date . ' ' . $service->pivot->booked_time
            ]);
        }

        // Get events
        foreach ($this->bookingServices()->whereNotNull('event_id')->with('event')->get() as $bookingService) {
            if ($bookingService->event) {
                $items->push([
                    'type' => 'event',
                    'item' => $bookingService->event,
                    'pivot' => $bookingService,
                    'title' => $bookingService->event->title,
                    'price' => $bookingService->final_price_at_booking,
                    'schedule' => $bookingService->event->event_date . ' ' . $bookingService->event->event_time
                ]);
            }
        }

        // Get free consultations
        foreach ($this->bookingServices()->whereNotNull('free_consultation_type_id')->with('freeConsultationType')->get() as $bookingService) {
            $items->push([
                'type' => 'free_consultation',
                'item' => $bookingService->freeConsultationType,
                'pivot' => $bookingService,
                'title' => 'Konsultasi Gratis - ' . $bookingService->freeConsultationType->name,
                'price' => 0,
                'schedule' => $bookingService->booked_date . ' ' . $bookingService->booked_time
            ]);
        }

        return $items;
    }

    // Tambahkan method ini ke ConsultationBooking.php
    public function getAllItemsWithDetailsAttribute()
    {
        $items = collect();

        // Services with details
        foreach ($this->services as $service) {
            $items->push([
                'type' => 'service',
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->short_description,
                'price' => $service->pivot->final_price_at_booking,
                'schedule' => $service->pivot->booked_date . ' ' . $service->pivot->booked_time,
                'duration' => $service->pivot->hours_booked . ' jam',
                'session_type' => $service->pivot->session_type,
                'pivot' => $service->pivot
            ]);
        }

        // Events with details  
        foreach ($this->bookingServices()->whereNotNull('event_id')->with('event')->get() as $bookingService) {
            if ($bookingService->event) {
                $items->push([
                    'type' => 'event',
                    'id' => $bookingService->event->id,
                    'title' => $bookingService->event->title,
                    'description' => $bookingService->event->description,
                    'price' => $bookingService->final_price_at_booking,
                    'schedule' => $bookingService->event->event_date . ' ' . $bookingService->event->event_time,
                    'duration' => 'Event',
                    'session_type' => $bookingService->event->session_type,
                    'location' => $bookingService->event->place,
                    'pivot' => $bookingService
                ]);
            }
        }

        return $items;
    }
}
