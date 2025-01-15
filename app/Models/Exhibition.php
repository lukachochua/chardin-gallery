<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exhibition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'image',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class)
            ->withTimestamps();
    }

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class)
            ->withTimestamps();
    }

    public function getStatusAttribute()
    {
        $today = now();

        if ($this->start_date > $today) {
            return 'upcoming';
        } elseif ($this->end_date > $today) {
            return 'running';
        } else {
            return 'done';
        }
    }
}
