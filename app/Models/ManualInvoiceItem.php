<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'manual_invoice_id',
        'description',
        'subtitle',
        'quantity',
        'unit_price',
        'discount_amount',
        'is_addon',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_addon' => 'boolean',
    ];

    /**
     * Get the parent invoice
     */
    public function invoice()
    {
        return $this->belongsTo(ManualInvoice::class, 'manual_invoice_id');
    }

    /**
     * Calculate line total (before discount)
     */
    public function getLineTotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Calculate line total after discount
     */
    public function getLineTotalAfterDiscountAttribute()
    {
        $lineTotal = $this->quantity * $this->unit_price;
        $discount = $this->discount_amount ?? 0;
        return max(0, $lineTotal - $discount);
    }
}

