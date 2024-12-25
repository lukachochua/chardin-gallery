<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ArtistService
{
    public function create(array $data)
    {
        if (isset($data['profile_image'])) {
            $data['profile_image'] = $this->handleProfileImage($data['profile_image']);
        }

        return Artist::create($data);
    }

    public function update(Artist $artist, array $data)
    {
        if (isset($data['profile_image'])) {
            // Delete old image if exists
            if ($artist->profile_image) {
                Storage::disk('public')->delete($artist->profile_image);
            }
            $data['profile_image'] = $this->handleProfileImage($data['profile_image']);
        }

        $artist->update($data);
        return $artist;
    }

    private function handleProfileImage(UploadedFile $image)
    {
        $path = $image->store('artists', 'public');
        
        // Create optimized version
        $optimizedImage = Image::make(Storage::disk('public')->path($path))
            ->fit(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 80);
        
        Storage::disk('public')->put($path, $optimizedImage);
        
        return $path;
    }
}