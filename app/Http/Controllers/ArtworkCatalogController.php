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
        if ($request->has('artist')) {
            $query->where('artist_id', $request->artist);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Filter by availability
        if ($request->has('available')) {
            $query->where('is_available', true);
        }

        // Filter by featured
        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        // Search by keyword
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('medium', 'like', '%' . $request->search . '%');
            });
        }

        $artworks = $query->paginate(12);

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
