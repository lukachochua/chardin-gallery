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
        } elseif (isset($data['parent_category_id'])) {
            $artwork->categories()->attach($data['parent_category_id']);
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
        if (isset($data['image'])) {
            if ($artwork->image) {
                Storage::disk('public')->delete($artwork->image);
            }

            $imagePath = $data['image']->store('artworks', 'public');
            $data['image'] = $imagePath;
        }

        $artwork->fill($data);
        $artwork->update($data);
        if (isset($data['category_id'])) {
            $artwork->categories()->sync([$data['category_id']]);
        } elseif (isset($data['parent_id'])) {
            $artwork->categories()->sync([$data['parent_id']]);
        }

        if (isset($data['tags'])) {
            $tags = is_array($data['tags']) ? array_map('intval', $data['tags']) : [];
            $artwork->tags()->sync($tags);
        } else {
            $artwork->tags()->sync([]);
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
