<?php

namespace App\Services;

use App\Models\Exhibition;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

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
        try {
            $path = $image->store('exhibitions', 'public');
            $fullPath = Storage::disk('public')->path($path);

            $manager = new ImageManager(
                driver: \Intervention\Image\Drivers\Gd\Driver::class
            );

            $img = $manager->read($fullPath);

            $img->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($fullPath);

            return $path;
        } catch (\Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function deleteImage($imagePath)
    {
        if ($imagePath && Storage::exists('public/' . $imagePath)) {
            Storage::delete('public/' . $imagePath);
        }
    }
}
