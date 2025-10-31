<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_percentage',
        'discount_amount',
        'min_purchase_amount',
        'valid_from',
        'valid_until',
        'max_uses',
        'current_uses',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function consultationBookings()
    {
        return $this->hasMany(ConsultationBooking::class, 'referral_code_id');
    }

    /**
     * Check if referral code is valid
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // Check start date
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        // Check expiration date
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        // Check max uses
        if ($this->max_uses && $this->current_uses >= $this->max_uses) {
            return false;
        }

        // Check if discount value is set based on type
        if ($this->discount_type === 'fixed') {
            if (!$this->discount_amount || $this->discount_amount <= 0) {
                return false;
            }
        } else {
            if (!$this->discount_percentage || $this->discount_percentage <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($totalAmount)
    {
        if (!$this->isValid()) {
            return 0;
        }

        // Check minimum purchase amount
        if ($this->min_purchase_amount && $totalAmount < $this->min_purchase_amount) {
            return 0;
        }

        if ($this->discount_type === 'fixed') {
            return $this->discount_amount ? min($this->discount_amount, $totalAmount) : 0;
        } else {
            return $this->discount_percentage ? $totalAmount * ($this->discount_percentage / 100) : 0;
        }
    }

    /**
     * Get discount description
     */
    public function getDiscountDescriptionAttribute()
    {
        if ($this->discount_type === 'fixed') {
            return $this->discount_amount ? 'Rp ' . number_format($this->discount_amount, 0, ',', '.') : 'Rp 0';
        } else {
            return $this->discount_percentage ? $this->discount_percentage . '%' : '0%';
        }
    }

    /**
     * Check if code can still be used
     */
    public function canBeUsed()
    {
        return $this->isValid();
    }

    /**
     * Get remaining uses
     */
    public function getRemainingUsesAttribute()
    {
        if (!$this->max_uses) {
            return null; // Unlimited
        }

        return max(0, $this->max_uses - $this->current_uses);
    }

    /**
     * Scope for active codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid codes
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_uses')
                    ->orWhereRaw('current_uses < max_uses');
            });
    }
}
