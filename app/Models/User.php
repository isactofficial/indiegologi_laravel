<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'onboarding_completed_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
    ];

    // Relationships
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function eventBookings()
    {
        return $this->hasMany(EventBooking::class);
    }

    public function consultationBookings()
    {
        return $this->hasMany(ConsultationBooking::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeAuthors($query)
    {
        return $query->where('role', 'author');
    }

    public function scopeReaders($query)
    {
        return $query->where('role', 'reader');
    }

    // Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAuthor()
    {
        return $this->role === 'author';
    }

    public function isReader()
    {
        return $this->role === 'reader';
    }

    public function hasCompletedOnboarding()
    {
        return !is_null($this->onboarding_completed_at);
    }

    public function getAllAppointments()
    {
        $appointments = [];

        // Event bookings from event_bookings table
        foreach ($this->eventBookings as $booking) {
            $appointments[] = [
                'type' => 'event',
                'title' => $booking->event->title ?? 'Event',
                'participant_count' => $booking->participant_count,
                'booking' => $booking,
                'invoice' => $booking->invoice,
                'created_at' => $booking->created_at
            ];
        }

        // Consultation bookings that contain events or services
        foreach ($this->consultationBookings as $consultation) {
            foreach ($consultation->bookingServices as $bookingService) {
                if ($bookingService->event_id) {
                    $appointments[] = [
                        'type' => 'event',
                        'title' => $bookingService->event->title ?? 'Event',
                        'participant_count' => $bookingService->participant_count ?? 1,
                        'booking' => $consultation,
                        'invoice' => $consultation->invoice,
                        'created_at' => $consultation->created_at
                    ];
                } elseif ($bookingService->service_id) {
                    $appointments[] = [
                        'type' => 'service',
                        'title' => $bookingService->service->title ?? 'Service',
                        'participant_count' => 1,
                        'booking' => $consultation,
                        'invoice' => $consultation->invoice,
                        'created_at' => $consultation->created_at
                    ];
                }
            }
        }

        // Sort by creation date (newest first)
        usort($appointments, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return $appointments;
    }
}
