<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

class ImageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ImageManager::class, function ($app) {
            return new ImageManager(
                driver: \Intervention\Image\Drivers\Gd\Driver::class
            );
        });
    }

    public function boot()
    {
        // Additional bootstrapping if needed
    }
}
