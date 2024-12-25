<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    // Generate slug from name
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($tag) {
            $tag->slug = Str::slug($tag->name);
        });
    }

    // Relationships
    public function artworks()
    {
        return $this->belongsToMany(Artwork::class)
            ->withTimestamps();
    }

    // Scope for getting popular tags
    public function scopePopular($query, $limit = 10)
    {
        return $query->withCount('artworks')
            ->orderBy('artworks_count', 'desc')
            ->limit($limit);
    }
}
