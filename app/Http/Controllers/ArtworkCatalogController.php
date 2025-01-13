<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Artist;
use App\Models\Category;
use Illuminate\Http\Request;

class ArtworkCatalogController extends Controller
{
    /**
     * Display a listing of the artworks.
     */
    public function index(Request $request)
    {
        $query = Artwork::query()->with(['artist', 'categories', 'tags']);

        // Filter by artist
        if ($request->has('artist') && $request->artist != '') {
            $query->where('artist_id', $request->artist);
        }

        // Filter by category including subcategories
        if ($request->has('category') && $request->category != '') {
            $selectedCategory = Category::find($request->category);
            if ($selectedCategory) {
                // Get all descendant categories (including the selected category)
                $categoryIds = $selectedCategory->descendants()->pluck('id')->push($selectedCategory->id);

                // Filter artworks by category IDs
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        // Filter by min price
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        // Filter by max price
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by keyword
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('medium', 'like', '%' . $request->search . '%');
            });
        }

        $artworks = $query->paginate(12)->withQueryString();

        $artists = Artist::all();
        $categories = Category::all();

        return view('artworks.index', compact('artworks', 'artists', 'categories'));
    }

    /**
     * Display the specified artwork.
     */
    public function show(Artwork $artwork)
    {
        $relatedArtworks = Artwork::whereHas('categories', function ($query) use ($artwork) {
            $query->whereIn('categories.id', $artwork->categories->pluck('id'));
        })
            ->where('id', '!=', $artwork->id)
            ->limit(4)
            ->get();

        return view('artworks.show', compact('artwork', 'relatedArtworks'));
    }
}
