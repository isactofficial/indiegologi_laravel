<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug; // Import HasSlug
use Spatie\Sluggable\SlugOptions; // Import SlugOptions
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika belum ada
use App\Models\Subheading; // Pastikan model Subheading di-import
use App\Models\Comment; // Pastikan model Comment di-import
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany untuk relasi

class Article extends Model
{
    use HasFactory, HasSlug; // Tambahkan HasFactory jika digunakan, dan HasSlug

    protected $fillable = [
        'title', 'description', 'thumbnail', 'status', 'views', 'user_id', 'author',
        'slug', // Tambahkan 'slug' di sini
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title') // Buat slug dari kolom 'title'
            ->saveSlugsTo('slug')       // Simpan slug di kolom 'slug'
            ->doNotGenerateSlugsOnUpdate(); // Opsional: Tidak membuat ulang slug saat update
    }

    /**
     * Get the route key for the model.
     * Ini memberitahu Laravel untuk menggunakan 'slug' alih-alih 'id' di Route Model Binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function subheadings()
    {
        return $this->hasMany(Subheading::class, 'article_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the full URL for the article's thumbnail image.
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
            
            // If it's a storage file path without 'storage/' prefix (e.g., 'thumbnails/...')
            // Add storage/ prefix to make it accessible through the public symlink
            if (str_starts_with($this->thumbnail, 'thumbnails/') || 
                str_starts_with($this->thumbnail, 'articles/')) {
                return asset('storage/' . $this->thumbnail);
            }
            
            // For legacy paths, use directly
            return asset($this->thumbnail);
        }
        return asset('assets/default-thumbnail.jpg');
    }
}
