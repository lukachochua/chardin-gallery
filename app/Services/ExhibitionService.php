<?php

namespace App\Services;

use App\Models\Exhibition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExhibitionService
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function getAllExhibitions()
    {
        return Exhibition::with(['artists', 'artworks'])->get();
    }

    public function createExhibition($data)
    {
        if (isset($data['image'])) {
            $imagePaths = $this->imageService->handleImageUpload(
                $data['image'],
                'exhibitions',
                'exhibition'
            );
            $data['image'] = $imagePaths['original'];
            $data['image_thumbnails'] = $imagePaths;
        }

        $data['slug'] = Str::slug($data['title']);
        return Exhibition::create($data);
    }

    public function updateExhibition(Exhibition $exhibition, $data)
    {
        if (isset($data['image'])) {
            if ($exhibition->image_thumbnails) {
                $this->imageService->deleteImages($exhibition->image_thumbnails);
            }

            $imagePaths = $this->imageService->handleImageUpload(
                $data['image'],
                'exhibitions',
                'exhibition'
            );
            $data['image'] = $imagePaths['original'];
            $data['image_thumbnails'] = $imagePaths;
        }

        return $exhibition->update($data);
    }

    public function deleteExhibition(Exhibition $exhibition)
    {
        if ($exhibition->image) {
            Storage::disk('public')->delete($exhibition->image);
        }
        $exhibition->delete();
    }
}
