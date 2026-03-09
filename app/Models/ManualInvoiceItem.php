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
        'is_addon',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
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
     * Calculate line total
     */
    public function getLineTotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}

