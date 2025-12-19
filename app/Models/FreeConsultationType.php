<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeConsultationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function schedules()
    {
        return $this->hasMany(FreeConsultationSchedule::class, 'type_id');
    }

    public function availableSchedules()
    {
        return $this->hasMany(FreeConsultationSchedule::class, 'type_id')
                    ->where('is_available', true)
                    ->where('current_bookings', '<', \Illuminate\Support\Facades\DB::raw('max_participants'))
                    ->where('scheduled_date', '>=', now()->toDateString())
                    ->orderBy('scheduled_date')
                    ->orderBy('scheduled_time');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'free_consultation_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}