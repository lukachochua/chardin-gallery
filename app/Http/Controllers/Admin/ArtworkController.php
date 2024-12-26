<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArtworkRequest;
use App\Models\Artwork;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ArtworkService;
use Illuminate\Support\Facades\Storage;

class ArtworkController extends Controller
{
    protected $artworkService;

    public function __construct(ArtworkService $artworkService)
    {
        $this->artworkService = $artworkService;
    }

    /**
     * Display a listing of the artworks.
     */
    public function index()
    {
        $artworks = Artwork::with('artist', 'categories', 'tags')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.artworks.index', compact('artworks'));
    }

    /**
     * Show the form for creating a new artwork.
     */
    public function create()
    {
        $artists = Artist::active()->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.artworks.create', compact('artists', 'categories', 'tags'));
    }

    /**
     * Store a newly created artwork in storage.
     */
    public function store(ArtworkRequest $request)
    {
        $this->artworkService->create($request->validated());

        return redirect()->route('admin.artworks.index')->with('success', 'Artwork created successfully.');
    }

    /**
     * Show the form for editing the specified artwork.
     */
    public function edit(Artwork $artwork)
    {
        $artists = Artist::active()->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.artworks.edit', compact('artwork', 'artists', 'categories', 'tags'));
    }

    /**
     * Update the specified artwork in storage.
     */
    public function update(ArtworkRequest $request, $id)
    {
        // Find the artwork by ID
        $artwork = Artwork::findOrFail($id);

        // Call the service to handle the update
        $updatedArtwork = $this->artworkService->update($artwork, $request->validated());

        // Redirect with success message
        return redirect()->route('admin.artworks.index')->with('success', 'Artwork updated successfully!');
    }

    /**
     * Remove the specified artwork from storage.
     */
    public function destroy(Artwork $artwork)
    {
        if ($artwork->image) {
            Storage::disk('public')->delete($artwork->image);
        }

        $artwork->delete();

        return redirect()->route('admin.artworks.index')->with('success', 'Artwork deleted successfully.');
    }
}
