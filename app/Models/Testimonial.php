<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'occupation',
        'location', // <-- DITAMBAHKAN
        'quote',
        'image',
        'is_active',
        'sort_order'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'age' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Interact with the user's age.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: function ($originalAge) {
                if (!$this->created_at) {
                    return $originalAge;
                }
                $yearsPassed = now()->year - $this->created_at->year;

                return $originalAge + $yearsPassed;
            }
        );
    }
    
    /**
     * Scope a query to only include active testimonials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order testimonials by sort_order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get the full URL for the testimonial's image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // If it's already a full URL, return as is
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            
            // If it's already prefixed with 'storage/', use directly
            if (str_starts_with($this->image, 'storage/')) {
                return asset($this->image);
            }
            
            // If it's a storage file path without 'storage/' prefix (e.g., 'testimonials/...')
            // Add storage/ prefix to make it accessible through the public symlink
            if (str_starts_with($this->image, 'testimonials/') || 
                str_starts_with($this->image, 'articles/') ||
                str_starts_with($this->image, 'sketches/')) {
                return asset('storage/' . $this->image);
            }
            
            // For legacy paths like 'assets/testimoni/Wira.jpeg', use directly
            return asset($this->image);
        }
        return asset('assets/testimoni/default-avatar.jpg');
    }

    /**
     * Get the shortened version of the quote.
     *
     * @param  int  $length
     * @return string
     */
    public function getShortQuoteAttribute($length = 150)
    {
        return \Str::limit($this->quote, $length);
    }
}