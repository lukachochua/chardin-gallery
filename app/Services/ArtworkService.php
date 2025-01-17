<?php

namespace App\Services;

use App\Models\Artwork;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ArtworkService
{
    protected $imageService;
    protected $manager;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->manager = new ImageManager(
            driver: \Intervention\Image\Drivers\Gd\Driver::class
        );
    }

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

    public function update(Artwork $artwork, array $data)
    {
        Log::info('Service data before processing:', $data);

        if (isset($data['image'])) {
            if ($artwork->image) {
                if ($artwork->image_thumbnails) {
                    $this->imageService->deleteImages($artwork->image_thumbnails);
                } else {
                    Storage::disk('public')->delete($artwork->image);
                }
            }

            $data = $this->handleImage($data);
        }

        $data['is_available'] = isset($data['is_available']) && $data['is_available'] == 1;
        $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] == 1;

        $artwork->update($data);

        if (isset($data['category_id'])) {
            $artwork->categories()->sync([$data['category_id']]);
        } elseif (isset($data['parent_id'])) {
            $artwork->categories()->sync([$data['parent_id']]);
        }

        if (isset($data['tags'])) {
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

            $tempImage = $this->manager->read($data['image']->path());
            $orientation = $tempImage->width() > $tempImage->height() ? 'horizontal' : 'vertical';

            $imagePaths = $this->imageService->handleImageUpload(
                $data['image'],
                'artworks',
                'artwork',
                $orientation
            );

            $data['image'] = $imagePaths['original'];
            $data['image_thumbnails'] = $imagePaths;
            $data['orientation'] = $orientation;

            return $data;
        } catch (\Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
