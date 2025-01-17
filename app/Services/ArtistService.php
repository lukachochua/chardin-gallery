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
            $imagePaths = $this->imageService->handleImageUpload(
                $data['profile_image'],
                'artists',
                'profile'
            );
            $data['profile_image'] = $imagePaths['original'];
            $data['profile_image_thumbnails'] = $imagePaths;
        }

        return Artist::create($data);
    }

    public function update(Artist $artist, array $data)
    {
        if (isset($data['profile_image'])) {
            if ($artist->profile_image_thumbnails) {
                $this->imageService->deleteImages($artist->profile_image_thumbnails);
            }

            $imagePaths = $this->imageService->handleImageUpload(
                $data['profile_image'],
                'artists',
                'profile'
            );
            $data['profile_image'] = $imagePaths['original'];
            $data['profile_image_thumbnails'] = $imagePaths;
        }

        $artist->update($data);
        return $artist;
    }
}
