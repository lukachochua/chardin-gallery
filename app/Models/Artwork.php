<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Artwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'title',
        'slug',
        'description',
        'price',
        'dimensions',
        'medium',
        'year_created',
        'is_available',
        'is_featured',
        'stock'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'year_created' => 'integer',
        'stock' => 'integer'
    ];

    // Generate slug from title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artwork) {
            $artwork->slug = Str::slug($artwork->title);
        });

        static::updating(function ($artwork) {
            if ($artwork->isDirty('title')) {
                $artwork->slug = Str::slug($artwork->title);
            }
        });
    }

    // Relationships
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps();
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->where('stock', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
