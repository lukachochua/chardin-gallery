<?php

namespace App\Services;

use App\Models\Exhibition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ExhibitionService
{
    public function getAllExhibitions()
    {
        return Exhibition::with(['artists', 'artworks'])->get();
    }


    public function createExhibition($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleImageUpload($data['image']);
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
            $this->deleteImage($exhibition->image);
            $data['image'] = $this->handleImageUpload($data['image']);
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
        $this->deleteImage($exhibition->image);
        $exhibition->delete();
    }

    private function handleImageUpload($image)
    {
        return $image->store('exhibitions', 'public');
    }

    private function deleteImage($imagePath)
    {
        if ($imagePath && Storage::exists('public/' . $imagePath)) {
            Storage::delete('public/' . $imagePath);
        }
    }
}
