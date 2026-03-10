<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'client_name',
        'client_email',
        'client_phone',
        'counseling_date',
        'package',
        'session_type',
        'invoice_date',
        'due_date',
        'status',
        'payment_type',
        'company_name',
        'company_email',
        'company_phone',
        'company_website',
        'bank_name',
        'bank_account',
        'account_name',
        'confirm_number',
        'signed_by',
        'signed_title',
        'subtotal_amount',
        'discount_amount',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the items for this invoice
     */
    public function items()
    {
        return $this->hasMany(ManualInvoiceItem::class);
    }

    /**
     * Calculate totals from items
     */
    public function calculateTotals()
    {
        $subtotal = 0;
        $totalDiscount = 0;
        
        foreach ($this->items as $item) {
            $lineTotal = $item->quantity * $item->unit_price;
            $subtotal += $lineTotal;
            $totalDiscount += ($item->discount_amount ?? 0);
        }

        $this->subtotal_amount = $subtotal;
        $this->discount_amount = $totalDiscount;
        $this->total_amount = $subtotal - $totalDiscount;
        
        return $this;
    }

    /**
     * Get total discount from items
     */
    public function getTotalItemDiscountAttribute()
    {
        return $this->items->sum('discount_amount') ?? 0;
    }

    /**
     * Generate next invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = date('ym');
        $lastInvoice = self::where('invoice_no', 'like', "{$prefix}/invoice/%")
            ->orderBy('invoice_no', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) explode('/', $lastInvoice->invoice_no)[2];
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}/invoice/{$newNumber}";
    }

    /**
     * Format currency to Rupiah
     */
    public static function formatRp($amount)
    {
        return 'Rp ' . number_format(round($amount), 0, ',', '.');
    }
}

