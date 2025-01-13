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
        'description'
    ];

    protected $nestedSetLeftColumn = '_lft';
    protected $nestedSetRightColumn = '_rht';
    protected $nestedSetParentColumn = 'parent_id';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class)
            ->withTimestamps();
    }
}
