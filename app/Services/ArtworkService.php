<?php

namespace App\Services;

use App\Models\Artwork;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ArtworkService
{
    public function create(array $data)
    {
        // Process the image and return the data with the image path
        $data = $this->handleImage($data);

        // Create the artwork and save it to the database
        $artwork = Artwork::create($data);

        // Sync categories and tags if provided
        if (isset($data['categories'])) {
            $artwork->categories()->sync($data['categories']);
        }

        if (isset($data['tags'])) {
            $artwork->tags()->sync($data['tags']);
        }

        return $artwork;
    }

    /**
     * Update an artwork with the provided data.
     *
     * @param  \App\Models\Artwork  $artwork
     * @param  array  $data
     * @return \App\Models\Artwork
     */
    public function update(Artwork $artwork, array $data)
    {
        Log::debug($data);

        // Handle image upload if provided
        if (isset($data['image'])) {
            // Delete the old image if it exists
            if ($artwork->image) {
                Storage::disk('public')->delete($artwork->image);
            }

            // Process and store the new image
            $imagePath = $data['image']->store('artworks', 'public');
            $data['image'] = $imagePath; // Save the new path to the data array
        }

        // Update the artwork attributes directly
        $artwork->fill($data); // Uses model's $fillable to mass assign

        // Save the changes to the database
        $artwork->save();

        // Sync categories and tags if provided
        if (isset($data['categories'])) {
            $artwork->categories()->sync($data['categories']);
        }

        if (isset($data['tags'])) {
            $artwork->tags()->sync($data['tags']);
        }

        return $artwork;
    }

    private function handleImage(array $data)
    {
        try {
            if (!isset($data['image'])) {
                return $data;
            }

            $image = $data['image'];
            $path = $image->store('artworks', 'public');
            $fullPath = Storage::disk('public')->path($path);

            $manager = new ImageManager(
                driver: \Intervention\Image\Drivers\Gd\Driver::class
            );

            $img = $manager->read($fullPath);

            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio(); // Maintain aspect ratio
            });

            $img->save($fullPath);

            $data['image'] = $path;

            return $data;
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            Log::error('Image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
