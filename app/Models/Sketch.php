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
}
