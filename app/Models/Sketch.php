<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk slug jika diperlukan

class Sketch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'author',
        'thumbnail',
        'status',
        'views',
        'content',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Auto generate slug from title when creating
        static::creating(function ($sketch) {
            if (empty($sketch->slug)) {
                $sketch->slug = Str::slug($sketch->title);
            }
        });
    }

    /**
     * Get the user that owns the sketch.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL for the sketch's thumbnail image.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            // If it's already a full URL, return as is
            if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
                return $this->thumbnail;
            }
            
            // If it's already prefixed with 'storage/', use directly
            if (str_starts_with($this->thumbnail, 'storage/')) {
                return asset($this->thumbnail);
            }
            
            // If it's a storage file path without 'storage/' prefix (e.g., 'sketches/...')
            // Add storage/ prefix to make it accessible through the public symlink
            if (str_starts_with($this->thumbnail, 'sketches/')) {
                return asset('storage/' . $this->thumbnail);
            }
            
            // For legacy paths, use directly
            return asset($this->thumbnail);
        }
        return asset('assets/default-thumbnail.jpg');
    }
}
