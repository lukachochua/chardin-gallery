<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id'
    ];

    // Generate slug from name
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // Relationships
    public function artworks()
    {
        return $this->belongsToMany(Artwork::class)
            ->withTimestamps();
    }

    // Get all children categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Get parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
