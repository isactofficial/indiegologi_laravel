<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age', 
        'occupation',
        'quote',
        'image',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'age' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Scope untuk mendapatkan testimoni yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Accessor untuk mendapatkan URL gambar lengkap
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }
        return asset('assets/testimoni/default-avatar.jpg');
    }

    /**
     * Accessor untuk membatasi quote
     */
    public function getShortQuoteAttribute($length = 150)
    {
        return \Str::limit($this->quote, $length);
    }
}