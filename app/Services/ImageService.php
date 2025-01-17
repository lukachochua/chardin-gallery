<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(
            driver: \Intervention\Image\Drivers\Gd\Driver::class
        );
    }

    /**
     * Handle the image upload and processing.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $directory
     * @param int $width
     * @param int|null $height
     * @return string The path to the stored image.
     */
    public function handleImageUpload($image, $directory, $width, $height = null)
    {
        try {
            $path = $image->store($directory, 'public');
            $fullPath = Storage::disk('public')->path($path);

            $img = $this->manager->read($fullPath);

            if ($height) {
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $img->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $img->save($fullPath);

            return $path;
        } catch (\Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
