<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_item_id',
        'full_name',
        'phone_number',
        'email'
    ];

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }
}