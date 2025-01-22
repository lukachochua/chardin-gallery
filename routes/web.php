<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\ArtworkController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExhibitionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\ArtworkCatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PublicArtistController;
use App\Http\Controllers\PublicExhibitionController;

// // Public Controllers
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\GalleryController;
// use App\Http\Controllers\ArtistPageController;
// use App\Http\Controllers\CartController;
// use App\Http\Controllers\CheckoutController;

Route::get('/', [ArtworkCatalogController::class, 'index'])->name('home');
Route::get('/artworks', [ArtworkCatalogController::class, 'index'])->name('artworks.index');
Route::get('/artworks/{artwork:slug}', [ArtworkCatalogController::class, 'show'])->name('artworks.show');

Route::get('/artists', [PublicArtistController::class, 'index'])->name('artists.index');
Route::get('/artists/{artist:slug}', [PublicArtistController::class, 'show'])->name('artists.show');

Route::get('/exhibitions', [PublicExhibitionController::class, 'index'])->name('exhibitions.index');
Route::get('/exhibitions/{exhibition:slug}', [PublicExhibitionController::class, 'show'])->name('exhibitions.show');

// Shopping Cart Routes
Route::group(['prefix' => 'cart', 'as' => 'cart.', 'middleware' => ['auth']], function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{artwork}', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
});

// Checkout Routes
Route::group(['prefix' => 'checkout', 'middleware' => ['auth'], 'as' => 'checkout.'], function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
});

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
    Route::get('/api/categories/{parent_id}/children', [CategoryController::class, 'getChildren']);
    Route::post('categories/rebuild-tree', [CategoryController::class, 'rebuildTree'])
        ->name('categories.rebuild-tree');

    // Tags Management
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('tags/create', [TagController::class, 'create'])->name('tags.create');
    Route::post('tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::put('tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

    // Exhibition Management
    Route::get('exhibitions', [ExhibitionController::class, 'index'])->name('exhibitions.index');
    Route::get('exhibitions/create', [ExhibitionController::class, 'create'])->name('exhibitions.create');
    Route::post('exhibitions', [ExhibitionController::class, 'store'])->name('exhibitions.store');
    Route::get('exhibitions/{exhibition}/edit', [ExhibitionController::class, 'edit'])->name('exhibitions.edit');
    Route::put('exhibitions/{exhibition}', [ExhibitionController::class, 'update'])->name('exhibitions.update');
    Route::delete('exhibitions/{exhibition}', [ExhibitionController::class, 'destroy'])->name('exhibitions.destroy');

    // Order Management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});

require __DIR__ . '/auth.php';
