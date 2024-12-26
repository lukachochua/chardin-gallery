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

        if (isset($data['categories'])) {
            $artwork->categories()->sync($data['categories']);
        }

        if (isset($data['tags'])) {
            $artwork->tags()->sync($data['tags']);
        }

        return $artwork;
    }

    public function update(Artwork $artwork, array $data)
    {
        Log::debug($data);
        if (isset($data['image'])) {
            if ($artwork->image) {
                Storage::disk('public')->delete($artwork->image);
            }

            $data = $this->handleImage($data);
        }

        $artwork->update($data);

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
        if (!isset($data['image'])) {
            return $data;
        }

        $image = $data['image'];

        $path = $image->store('artworks', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $img = new ImageManager('imagick');
        $image = $img->read($fullPath);

        $image->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->save($fullPath);

        $data['image'] = $path;

        return $data;
    }
}
