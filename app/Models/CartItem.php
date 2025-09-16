<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'free_consultation_type_id',
        'free_consultation_schedule_id',
        'quantity',
        'price',
        'hourly_price',
        'hours',
        'booked_date',
        'booked_time',
        'session_type',
        'offline_address',
        'contact_preference',
        'payment_type',
        'referral_code',
    ];

    protected $appends = [
        'item_subtotal',
        'discount_amount',
        'final_item_price',
        'total_to_pay',
        'service_title',
        'service_description',
        'service_thumbnail',
    ];

    // Existing relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(ConsultationService::class, 'service_id');
    }

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code', 'code');
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

    // Updated accessors
    public function getItemSubtotalAttribute(): float
    {
        if ($this->isFreeConsultation()) {
            return 0.0;
        }

        return (float)$this->price + ((float)$this->hourly_price * (int)$this->hours);
    }

    public function getDiscountAmountAttribute(): float
    {
        if ($this->isFreeConsultation()) {
            return 0.0;
        }

        $itemDiscount = 0.0;

        if ($this->referral_code && $this->referralCode) {
            $referral = $this->referralCode;
            $isExpired = $referral->valid_until && Carbon::now()->gt($referral->valid_until);
            $isUsedUp = $referral->max_uses && $referral->current_uses >= $referral->max_uses;

            if (!$isExpired && !$isUsedUp) {
                $itemSubtotal = $this->item_subtotal;
                $itemDiscount = ($itemSubtotal * $referral->discount_percentage) / 100;
            }
        }

        return (float)$itemDiscount;
    }

    public function getFinalItemPriceAttribute(): float
    {
        return $this->item_subtotal - $this->discount_amount;
    }

    public function getTotalToPayAttribute(): float
    {
        return $this->final_item_price;
    }

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

        return $this->service ? $this->service->title : 'Layanan Tidak Ditemukan';
    }

    public function getServiceDescriptionAttribute()
    {
        if ($this->isNewFreeConsultation()) {
            if ($this->freeConsultationType && $this->freeConsultationSchedule) {
                return $this->freeConsultationType->description . ' - ' . 
                       $this->freeConsultationSchedule->formatted_date_time;
            }
            return $this->freeConsultationType ? 
                $this->freeConsultationType->description : 
                'Sesi konsultasi gratis';
        }

        if ($this->isLegacyFreeConsultation()) {
            return 'Sesi konsultasi gratis 1 jam untuk pengguna baru';
        }

        return $this->service ? $this->service->short_description : '';
    }

    public function getServiceThumbnailAttribute()
    {
        if ($this->isFreeConsultation()) {
            return 'https://placehold.co/100x100/D4AF37/ffffff?text=Gratis';
        }

        return $this->service && $this->service->thumbnail 
            ? asset('storage/' . $this->service->thumbnail)
            : 'https://placehold.co/100x100/cccccc/ffffff?text=No+Image';
    }

    // Get formatted schedule info for new free consultation
    public function getFormattedScheduleAttribute()
    {
        if ($this->isNewFreeConsultation() && $this->freeConsultationSchedule) {
            return $this->freeConsultationSchedule->formatted_date_time;
        }

        if ($this->booked_date && $this->booked_time) {
            return Carbon::parse($this->booked_date)->format('d M Y') . ' ' . 
                   Carbon::parse($this->booked_time)->format('H:i');
        }

        return '';
    }
}