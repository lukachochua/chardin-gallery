<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the number of artists, artworks, categories, and tags for the dashboard
        $artistsCount = Artist::count();
        $artworksCount = Artwork::count();
        $categoriesCount = Category::count();
        $tagsCount = Tag::count();

        // Pass the data to the view
        return view('admin.dashboard', compact('artistsCount', 'artworksCount', 'categoriesCount', 'tagsCount'));
    }

    /**
     * Show all artists.
     *
     * @return \Illuminate\View\View
     */
    public function artistsIndex()
    {
        $artists = Artist::all();

        return view('admin.artists.index', compact('artists'));
    }

    /**
     * Show the form to create a new artist.
     *
     * @return \Illuminate\View\View
     */
    public function artistsCreate()
    {
        return view('admin.artists.create');
    }

    /**
     * Store a newly created artist in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function artistsStore(Request $request)
    {
        // Validate and store the artist
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
        ]);

        Artist::create($request->all());

        return redirect()->route('admin.artists.index')->with('success', 'Artist created successfully.');
    }

    /**
     * Show the form for editing an existing artist.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function artistsEdit($id)
    {
        $artist = Artist::findOrFail($id);

        return view('admin.artists.edit', compact('artist'));
    }

    /**
     * Update an existing artist in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function artistsUpdate(Request $request, $id)
    {
        // Validate and update the artist
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
        ]);

        $artist = Artist::findOrFail($id);
        $artist->update($request->all());

        return redirect()->route('admin.artists.index')->with('success', 'Artist updated successfully.');
    }

    /**
     * Remove the specified artist from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function artistsDestroy($id)
    {
        $artist = Artist::findOrFail($id);
        $artist->delete();

        return redirect()->route('admin.artists.index')->with('success', 'Artist deleted successfully.');
    }

    // --- Artworks Management Methods ---
    
    /**
     * Show all artworks.
     *
     * @return \Illuminate\View\View
     */
    public function artworksIndex()
    {
        $artworks = Artwork::all();

        return view('admin.artworks.index', compact('artworks'));
    }

    /**
     * Show the form to create a new artwork.
     *
     * @return \Illuminate\View\View
     */
    public function artworksCreate()
    {
        $artists = Artist::all();
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.artworks.create', compact('artists', 'categories', 'tags'));
    }

    /**
     * Store a newly created artwork in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function artworksStore(Request $request)
    {
        // Validate and store the artwork
        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
        ]);

        $artwork = Artwork::create($request->all());

        // Attach categories and tags
        $artwork->categories()->attach($request->input('categories'));
        $artwork->tags()->attach($request->input('tags'));

        return redirect()->route('admin.artworks.index')->with('success', 'Artwork created successfully.');
    }

    /**
     * Show the form for editing an existing artwork.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function artworksEdit($id)
    {
        $artwork = Artwork::findOrFail($id);
        $artists = Artist::all();
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.artworks.edit', compact('artwork', 'artists', 'categories', 'tags'));
    }

    /**
     * Update an existing artwork in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function artworksUpdate(Request $request, $id)
    {
        // Validate and update the artwork
        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
        ]);

        $artwork = Artwork::findOrFail($id);
        $artwork->update($request->all());

        // Sync categories and tags
        $artwork->categories()->sync($request->input('categories'));
        $artwork->tags()->sync($request->input('tags'));

        return redirect()->route('admin.artworks.index')->with('success', 'Artwork updated successfully.');
    }

    /**
     * Remove the specified artwork from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function artworksDestroy($id)
    {
        $artwork = Artwork::findOrFail($id);
        $artwork->delete();

        return redirect()->route('admin.artworks.index')->with('success', 'Artwork deleted successfully.');
    }

    // --- Categories Management Methods ---
    
    /**
     * Show all categories.
     *
     * @return \Illuminate\View\View
     */
    public function categoriesIndex()
    {
        $categories = Category::all();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form to create a new category.
     *
     * @return \Illuminate\View\View
     */
    public function categoriesCreate()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing an existing category.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function categoriesEdit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update an existing category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function categoriesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function categoriesDestroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    // --- Tags Management Methods ---

    /**
     * Show all tags.
     *
     * @return \Illuminate\View\View
     */
    public function tagsIndex()
    {
        $tags = Tag::all();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form to create a new tag.
     *
     * @return \Illuminate\View\View
     */
    public function tagsCreate()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tagsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Tag::create($request->all());

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    /**
     * Show the form for editing an existing tag.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function tagsEdit($id)
    {
        $tag = Tag::findOrFail($id);

        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update an existing tag in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tagsUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update($request->all());

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tagsDestroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
