<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingService extends Pivot
{
    use HasFactory;

    protected $table = 'booking_service';

    protected $fillable = [
        'total_price_at_booking',
        'discount_amount_at_booking',
        'final_price_at_booking',
        'booked_date',
        'booked_time',
        'hours_booked',
        'session_type',
        'offline_address',
        'referral_code_id',
        'invoice_id',
        'user_id',
        'contact_preference',
        'free_consultation_type_id',
        'free_consultation_schedule_id',
    ];

    protected $casts = [
        'booked_date' => 'date',
        'booked_time' => 'datetime:H:i',
        'total_price_at_booking' => 'decimal:2',
        'discount_amount_at_booking' => 'decimal:2',
        'final_price_at_booking' => 'decimal:2',
        'hours_booked' => 'integer',
    ];

    // Existing relationships
    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // New relationships for free consultation
    public function freeConsultationType()
    {
        return $this->belongsTo(FreeConsultationType::class, 'free_consultation_type_id');
    }

    public function freeConsultationSchedule()
    {
        return $this->belongsTo(FreeConsultationSchedule::class, 'free_consultation_schedule_id');
    }

    // Updated scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeFreeConsultation($query)
    {
        return $query->where('service_id', 'free-consultation')
                     ->orWhereNotNull('free_consultation_type_id');
    }

    public function scopeNewFreeConsultation($query)
    {
        return $query->whereNotNull('free_consultation_type_id');
    }

    public function scopeLegacyFreeConsultation($query)
    {
        return $query->where('service_id', 'free-consultation')
                     ->whereNull('free_consultation_type_id');
    }

    public function scopeRegularServices($query)
    {
        return $query->where('service_id', '!=', 'free-consultation')
                     ->whereNull('free_consultation_type_id');
    }

    public function scopePaidBookings($query)
    {
        return $query->whereHas('invoice', function ($query) {
            $query->where('payment_status', 'paid');
        });
    }

    public function scopePendingOrUnpaidBookings($query)
    {
        return $query->whereHas('invoice', function ($query) {
            $query->whereIn('payment_status', ['unpaid', 'pending'])
                  ->where('due_date', '>=', now());
        });
    }

    // Updated helper methods
    public function isFreeConsultation()
    {
        return $this->service_id === 'free-consultation' || $this->free_consultation_type_id !== null;
    }

    public function isNewFreeConsultation()
    {
        return $this->free_consultation_type_id !== null;
    }

    public function isLegacyFreeConsultation()
    {
        return $this->service_id === 'free-consultation' && $this->free_consultation_type_id === null;
    }

    public function isValidBooking()
    {
        if (!$this->invoice) {
            return false;
        }

        return $this->invoice->payment_status === 'paid' || 
               (in_array($this->invoice->payment_status, ['unpaid', 'pending']) && 
                $this->invoice->due_date >= now());
    }

    // Get service title for display
    public function getServiceTitleAttribute()
    {
        if ($this->isNewFreeConsultation()) {
            return $this->freeConsultationType ? 
                'Konsultasi Gratis - ' . $this->freeConsultationType->name : 
                'Konsultasi Gratis';
        }

        if ($this->isLegacyFreeConsultation()) {
            return 'Konsultasi Gratis';
        }

        // For regular services, you would access through the relationship
        // This assumes you have a service relationship in ConsultationBooking
        return 'Layanan Konsultasi';
    }

    // Get formatted schedule info
    public function getFormattedScheduleAttribute()
    {
        if ($this->isNewFreeConsultation() && $this->freeConsultationSchedule) {
            return $this->freeConsultationSchedule->formatted_date_time;
        }

        if ($this->booked_date && $this->booked_time) {
            return $this->booked_date->format('d M Y') . ' ' . 
                   $this->booked_time->format('H:i');
        }

        return '';
    }
}