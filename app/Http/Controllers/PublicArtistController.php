<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;

class PublicArtistController extends Controller
{
    public function index(Request $request)
    {
        $artists = Artist::query()
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($request->has('status') && in_array($request->status, ['1', '0']), function ($query) use ($request) {
                return $query->where('is_active', $request->status);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('artists.index', [
            'artists' => $artists
        ]);
    }

    public function show(Artist $artist)
    {
        $artist->load(['artworks' => function ($query) {
            $query->latest()
                ->with('artist');
        }]);

        return view('artists.show', [
            'artist' => $artist,
        ]);
    }
}
