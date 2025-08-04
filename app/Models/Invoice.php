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
        'invoice_date',
        'due_date',
        'total_amount',
        'discount_amount',
        'paid_amount', // Ditambahkan
        'payment_type', // Ditambahkan
        'payment_status',
        'session_type',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2', // Ditambahkan
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultationBooking()
    {
        return $this->hasOne(ConsultationBooking::class);
    }
}
