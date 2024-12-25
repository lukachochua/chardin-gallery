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
    Route::get('artists', [ArtistController::class, 'index'])->name('artists.index');
    Route::get('artists/create', [ArtistController::class, 'create'])->name('artists.create');
    Route::post('artists', [ArtistController::class, 'store'])->name('artists.store');
    Route::get('artists/{artist}/edit', [ArtistController::class, 'edit'])->name('artists.edit');
    Route::put('artists/{artist}', [ArtistController::class, 'update'])->name('artists.update');
    Route::delete('artists/{artist}', [ArtistController::class, 'destroy'])->name('artists.destroy');

    // Artworks Management
    Route::get('artworks', [ArtworkController::class, 'index'])->name('artworks.index');
    Route::get('artworks/create', [ArtworkController::class, 'create'])->name('artworks.create');
    Route::post('artworks', [ArtworkController::class, 'store'])->name('artworks.store');
    Route::get('artworks/{artwork}/edit', [ArtworkController::class, 'edit'])->name('artworks.edit');
    Route::put('artworks/{artwork}', [ArtworkController::class, 'update'])->name('artworks.update');
    Route::delete('artworks/{artwork}', [ArtworkController::class, 'destroy'])->name('artworks.destroy');
    Route::post('artworks/upload-image', [ArtworkController::class, 'uploadImage'])->name('artworks.upload-image');
    Route::delete('artworks/remove-image/{image}', [ArtworkController::class, 'removeImage'])->name('artworks.remove-image');

    // Categories Management
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // Tags Management
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('tags/create', [TagController::class, 'create'])->name('tags.create');
    Route::post('tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::put('tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
});

require __DIR__ . '/auth.php';
