<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;

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
        try {
            $path = $image->store('artists', 'public');
            $fullPath = Storage::disk('public')->path($path);

            $manager = new ImageManager(
                driver: \Intervention\Image\Drivers\Gd\Driver::class
            );

            $img = $manager->read($fullPath);

            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio(); 
                $constraint->upsize();    
            });

            $img->save($fullPath);

            return $path;
        } catch (\Exception $e) {
            Log::error('Profile image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
