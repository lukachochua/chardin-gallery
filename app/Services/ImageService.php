<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImage;

class ImageService
{
    protected $manager;

    protected const IMAGE_CONFIGS = [
        'profile' => [
            'width' => 800,
            'height' => 800,
            'thumbnails' => [
                'small' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 400, 'height' => 400]
            ]
        ],
        'exhibition' => [
            'width' => 1200,
            'height' => 800,
            'thumbnails' => [
                'small' => ['width' => 400, 'height' => 267],
                'medium' => ['width' => 800, 'height' => 533]
            ]
        ],
        'artwork' => [
            'horizontal' => [
                'width' => 1600,
                'height' => 1200,
                'thumbnails' => [
                    'small' => ['width' => 400, 'height' => 300],
                    'medium' => ['width' => 800, 'height' => 600]
                ]
            ],
            'vertical' => [
                'width' => 1200,
                'height' => 1600,
                'thumbnails' => [
                    'small' => ['width' => 300, 'height' => 400],
                    'medium' => ['width' => 600, 'height' => 800]
                ]
            ]
        ]
    ];

    public function __construct()
    {
        $this->manager = new ImageManager(
            driver: \Intervention\Image\Drivers\Gd\Driver::class
        );
    }

    /**
     * Handle image upload with specific configurations
     *
     * @param UploadedFile $image
     * @param string $directory
     * @param string $type
     * @param string|null $orientation
     * @return array
     */
    public function handleImageUpload(
        UploadedFile $image,
        string $directory,
        string $type,
        ?string $orientation = null
    ): array {
        try {
            $config = $this->getImageConfig($type, $orientation);
            $filename = $image->hashName();
            $paths = [];

            // Store original image
            $originalPath = $image->store($directory, 'public');
            $fullPath = Storage::disk('public')->path($originalPath);

            // Process main image
            $img = $this->manager->read($fullPath);
            $this->resizeImage($img, $config['width'], $config['height']);
            $img->save($fullPath);
            $paths['original'] = $originalPath;

            // Generate thumbnails
            if (isset($config['thumbnails'])) {
                foreach ($config['thumbnails'] as $size => $dimensions) {
                    $thumbPath = $directory . '/thumb_' . $size . '_' . $filename;
                    $fullThumbPath = Storage::disk('public')->path($thumbPath);

                    $thumb = $this->manager->read($fullPath);
                    $this->resizeImage($thumb, $dimensions['width'], $dimensions['height']);
                    $thumb->save($fullThumbPath);

                    $paths[$size] = $thumbPath;
                }
            }

            return $paths;
        } catch (\Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get image configuration based on type and orientation
     */
    protected function getImageConfig(string $type, ?string $orientation = null): array
    {
        if ($type === 'artwork' && $orientation) {
            return self::IMAGE_CONFIGS[$type][$orientation];
        }
        return self::IMAGE_CONFIGS[$type];
    }

    /**
     * Resize image maintaining aspect ratio
     */
    protected function resizeImage($img, int $width, int $height): void
    {
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }

    /**
     * Delete all image versions
     */
    public function deleteImages(array $paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}
