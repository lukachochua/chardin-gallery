<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArtistRequest;
use App\Models\Artist;
use App\Services\ArtistService;

class ArtistController extends Controller
{
    protected $artistService;

    public function __construct(ArtistService $artistService)
    {
        $this->artistService = $artistService;
    }

    public function index()
    {
        $artists = Artist::withCount('artworks')
            ->latest()
            ->paginate(10);

        return view('admin.artists.index', compact('artists'));
    }

    public function create()
    {
        return view('admin.artists.create');
    }

    public function store(ArtistRequest $request)
    {
        $this->artistService->create($request->validated());

        return redirect()
            ->route('admin.artists.index')
            ->with('success', 'Artist created successfully.');
    }

    public function edit(Artist $artist)
    {
        return view('admin.artists.edit', compact('artist'));
    }

    public function update(ArtistRequest $request, Artist $artist)
    {
        $this->artistService->update($artist, $request->validated());

        return redirect()
            ->route('admin.artists.index')
            ->with('success', 'Artist updated successfully.');
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect()
            ->route('admin.artists.index')
            ->with('success', 'Artist deleted successfully.');
    }
}
