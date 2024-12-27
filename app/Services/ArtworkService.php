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
        $data = $this->handleImage($data);

        $artwork = Artwork::create($data);

        if (isset($data['category_id'])) {
            $artwork->categories()->attach($data['category_id']);
        } elseif (isset($data['parent_id'])) {
            $artwork->categories()->attach($data['parent_id']);
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
        Log::info('Service data before processing:', $data);

        if (isset($data['image'])) {
            if ($artwork->image) {
                Storage::disk('public')->delete($artwork->image);
            }
            $imagePath = $data['image']->store('artworks', 'public');
            $data['image'] = $imagePath;
        }

        // Handle boolean fields
        $data['is_available'] = isset($data['is_available']) && $data['is_available'] == 1;
        $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] == 1;

        $artwork->update($data);

        // Handle categories
        if (isset($data['category_id'])) {
            $artwork->categories()->sync([$data['category_id']]);
        } elseif (isset($data['parent_id'])) {
            $artwork->categories()->sync([$data['parent_id']]);
        }

        // Handle tags
        if (isset($data['tags'])) {
            // Ensure we have an array of valid tag IDs
            $tagIds = collect($data['tags'])
                ->filter()
                ->map(fn($id) => (int)$id)
                ->filter(fn($id) => $id > 0)
                ->values()
                ->toArray();

            Log::info('Tag IDs to sync:', $tagIds);
            $artwork->tags()->sync($tagIds);
        } else {
            $artwork->tags()->sync([]);
        }

        return $artwork->fresh()->load('tags');
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
