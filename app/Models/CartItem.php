<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'free_consultation_type_id',
        'free_consultation_schedule_id',
        'event_id',
        'quantity',
        'participant_count',
        'hours',
        'booked_date',
        'booked_time',
        'session_type',
        'offline_address',
        'contact_preference',
        'payment_type',
        'referral_code',
        'price',
        'original_price',  // ✅ HARGA ASLI SEBELUM DISKON
        'hourly_price',
        'discount_amount',
        'item_type'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'booked_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(ConsultationService::class, 'service_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function freeConsultationType()
    {
        return $this->belongsTo(FreeConsultationType::class, 'free_consultation_type_id');
    }

    public function freeConsultationSchedule()
    {
        return $this->belongsTo(FreeConsultationSchedule::class, 'free_consultation_schedule_id');
    }

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code', 'code');
    }

    public function participantData()
    {
        return $this->hasMany(CartParticipant::class);
    }

    // Helper methods
    public function isEvent()
    {
        return $this->item_type === 'event' && !is_null($this->event_id);
    }

    public function isNewFreeConsultation()
    {
        return !is_null($this->free_consultation_type_id) &&
            !is_null($this->free_consultation_schedule_id);
    }

    public function isLegacyFreeConsultation()
    {
        return $this->service_id === 'free_consultation' ||
            ($this->price == 0 && is_null($this->free_consultation_type_id));
    }

    public function isFreeConsultation()
    {
        return $this->isNewFreeConsultation() || $this->isLegacyFreeConsultation();
    }

    // ✅ METHODS PERHITUNGAN YANG BENAR
    // In CartItem.php model
    public function calculateOriginalSubtotal()
    {
        if ($this->isEvent()) {
            // Harga dasar × jumlah peserta
            return (float) $this->original_price * (int) $this->participant_count;
        }

        if ($this->isFreeConsultation()) {
            return 0;
        }

        return (float) $this->original_price + ((float) $this->hourly_price * (int) $this->hours);
    }

    public function calculateFinalPrice()
    {
        $subtotal = $this->calculateOriginalSubtotal();
        return $subtotal - $this->getDiscountAmount();
    }

    public function getDiscountAmount()
    {
        return (float) $this->discount_amount;
    }

    public function calculateFinalSubtotal()
    {
        if ($this->isEvent()) {
            return (float) $this->price * (int) $this->participant_count;
        }

        if ($this->isFreeConsultation()) {
            return 0;
        }

        return (float) $this->price + ((float) $this->hourly_price * (int) $this->hours);
    }

    public function getServiceThumbnail()
    {
        if ($this->isEvent() && $this->event) {
            return $this->event->thumbnail ? asset('storage/' . $this->event->thumbnail) : 'https://placehold.co/400x300/4CAF50/ffffff?text=Event';
        }

        if ($this->service && $this->service->thumbnail) {
            return asset('storage/' . $this->service->thumbnail);
        }

        return 'https://placehold.co/400x300/4CAF50/ffffff?text=Service';
    }

    public function getServiceTitle()
    {
        if ($this->isEvent() && $this->event) {
            return $this->event->title;
        }

        if ($this->isNewFreeConsultation() && $this->freeConsultationType) {
            return 'Konsultasi Gratis - ' . $this->freeConsultationType->name;
        }

        if ($this->isLegacyFreeConsultation()) {
            return 'Konsultasi Gratis';
        }

        if ($this->service) {
            return $this->service->title;
        }

        return 'Unknown Service';
    }

    // Add these accessor methods to your CartItem.php model
    // Place them after the existing methods

    /**
     * Accessor for item_subtotal attribute
     * Allows using $item->item_subtotal in code
     */
    public function getItemSubtotalAttribute()
    {
        return $this->calculateOriginalSubtotal();
    }

    /**
     * Accessor for service_thumbnail attribute
     */
    public function getServiceThumbnailAttribute()
    {
        return $this->getServiceThumbnail();
    }

    /**
     * Accessor for service_title attribute
     */
    public function getServiceTitleAttribute()
    {
        return $this->getServiceTitle();
    }

    /**
     * Accessor for final_price attribute
     */
    public function getFinalPriceAttribute()
    {
        return $this->calculateFinalPrice();
    }
}
