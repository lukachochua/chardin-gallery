<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exhibition extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'image',
    ];

    // Ensure that 'start_date' and 'end_date' are cast to Carbon instances
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class);  // Many-to-many with artists
    }

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class);  // Many-to-many with artworks
    }

    public function getStatusAttribute()
    {
        $today = now();

        // Check if the exhibition is upcoming
        if ($this->start_date > $today) {
            return 'upcoming';
        }

        // Check if the exhibition is currently running
        elseif ($this->end_date > $today) {
            return 'running';
        }

        // If the exhibition has ended
        else {
            return 'done';
        }
    }
}
