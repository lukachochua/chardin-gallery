<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\ArtworkController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;

// // Public Controllers
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\GalleryController;
// use App\Http\Controllers\ArtistPageController;
// use App\Http\Controllers\CartController;
// use App\Http\Controllers\CheckoutController;

// // Public Routes
// Route::get('/', [HomeController::class, 'index'])->name('home');

// // Gallery Routes
// Route::group(['prefix' => 'gallery'], function () {
//     Route::get('/', [GalleryController::class, 'index'])->name('gallery.index');
//     Route::get('/artwork/{artwork:slug}', [GalleryController::class, 'show'])->name('gallery.show');
//     Route::get('/category/{category:slug}', [GalleryController::class, 'category'])->name('gallery.category');
//     Route::get('/tag/{tag:slug}', [GalleryController::class, 'tag'])->name('gallery.tag');
// });

// // Artist Public Pages
// Route::get('/artists', [ArtistPageController::class, 'index'])->name('artists.index');
// Route::get('/artist/{artist:slug}', [ArtistPageController::class, 'show'])->name('artists.show');

// // Shopping Cart Routes
// Route::group(['prefix' => 'cart'], function () {
//     Route::get('/', [CartController::class, 'index'])->name('cart.index');
//     Route::post('/add/{artwork}', [CartController::class, 'add'])->name('cart.add');
//     Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
//     Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
// });

// // Checkout Routes
// Route::group(['prefix' => 'checkout', 'middleware' => ['auth']], function () {
//     Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
//     Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
//     Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
//     Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
// });


// Admin Routes
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'], 
    'as' => 'admin.'
], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Artists Management
    Route::resource('artists', ArtistController::class);

    // Artworks Management
    Route::resource('artworks', ArtworkController::class);
    Route::post('artworks/upload-image', [ArtworkController::class, 'uploadImage'])
        ->name('artworks.upload-image');
    Route::delete('artworks/remove-image/{image}', [ArtworkController::class, 'removeImage'])
        ->name('artworks.remove-image');

    // Categories Management
    Route::resource('categories', CategoryController::class);
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])
        ->name('categories.reorder');

    // Tags Management
    Route::resource('tags', TagController::class);
});

require __DIR__.'/auth.php';