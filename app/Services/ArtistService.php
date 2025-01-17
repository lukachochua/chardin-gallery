<?php

namespace App\Services;

use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArtistService
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function create(array $data)
    {
        if (isset($data['profile_image'])) {
            $data['profile_image'] = $this->imageService->handleImageUpload(
                $data['profile_image'],
                'artists',
                800,
                800
            );
        }

        return Artist::create($data);
    }

    public function update(Artist $artist, array $data)
    {
        if (isset($data['profile_image'])) {
            if ($artist->profile_image) {
                Storage::disk('public')->delete($artist->profile_image);
            }
            $data['profile_image'] = $this->imageService->handleImageUpload(
                $data['profile_image'],
                'artists',
                800,
                800
            );
        }

        $artist->update($data);
        return $artist;
    }
}
