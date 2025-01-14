<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Artist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'biography',
        'email',
        'phone',
        'website',
        'profile_image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($artist) {
            $artist->slug = Str::slug($artist->name);
        });
    }

    // Relationships
    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public function exhibitions()
    {
        return $this->belongsToMany(Exhibition::class);
    }


    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
