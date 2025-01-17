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
            $data['image'] = $this->imageService->handleImageUpload(
                $data['image'],
                'exhibitions',
                800,
                600
            );
        }

        $data['slug'] = Str::slug($data['title']);

        $exhibition = Exhibition::create($data);

        if (isset($data['artists'])) {
            $exhibition->artists()->sync($data['artists']);
        }

        if (isset($data['artworks'])) {
            $exhibition->artworks()->sync($data['artworks']);
        }

        return $exhibition;
    }

    public function updateExhibition(Exhibition $exhibition, $data)
    {
        if (isset($data['image'])) {
            if ($exhibition->image) {
                Storage::disk('public')->delete($exhibition->image);
            }
            $data['image'] = $this->imageService->handleImageUpload(
                $data['image'],
                'exhibitions',
                800,
                600
            );
        }

        $exhibition->update($data);

        if (isset($data['artists'])) {
            $exhibition->artists()->sync($data['artists']);
        }

        if (isset($data['artworks'])) {
            $exhibition->artworks()->sync($data['artworks']);
        }

        return $exhibition;
    }

    public function deleteExhibition(Exhibition $exhibition)
    {
        if ($exhibition->image) {
            Storage::disk('public')->delete($exhibition->image);
        }
        $exhibition->delete();
    }
}
