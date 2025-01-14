<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exhibition;
use App\Models\Artist;
use App\Models\Artwork;
use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
    // Show all exhibitions
    public function index()
    {
        $exhibitions = Exhibition::with(['artists', 'artworks'])->get();

        return view('admin.exhibitions.index', compact('exhibitions'));
    }

    // Show the form for creating a new exhibition
    public function create()
    {
        $artists = Artist::all();
        $artworks = Artwork::all();
        return view('admin.exhibitions.create', compact('artists', 'artworks'));
    }

    // Store a newly created exhibition
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'duration_days' => 'required|integer',
            'status' => 'required|in:upcoming,running,done',
            'artists' => 'array',
            'artworks' => 'array',
        ]);

        $exhibition = Exhibition::create($request->only(['name', 'start_date', 'duration_days', 'status']));

        // Sync artists and artworks
        if ($request->has('artists')) {
            $exhibition->artists()->sync($request->artists);
        }

        if ($request->has('artworks')) {
            $exhibition->artworks()->sync($request->artworks);
        }

        return redirect()->route('admin.exhibitions.index')->with('success', 'Exhibition created successfully.');
    }

    // Show the form for editing the specified exhibition
    public function edit(Exhibition $exhibition)
    {
        $artists = Artist::all();
        $artworks = Artwork::all();
        return view('admin.exhibitions.edit', compact('exhibition', 'artists', 'artworks'));
    }

    // Update the specified exhibition
    public function update(Request $request, Exhibition $exhibition)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'duration_days' => 'required|integer',
            'status' => 'required|in:upcoming,running,done',
            'artists' => 'array',
            'artworks' => 'array',
        ]);

        $exhibition->update($request->only(['name', 'start_date', 'duration_days', 'status']));

        // Sync artists and artworks
        if ($request->has('artists')) {
            $exhibition->artists()->sync($request->artists);
        }

        if ($request->has('artworks')) {
            $exhibition->artworks()->sync($request->artworks);
        }

        return redirect()->route('admin.exhibitions.index')->with('success', 'Exhibition updated successfully.');
    }

    // Remove the specified exhibition
    public function destroy(Exhibition $exhibition)
    {
        $exhibition->delete();
        return redirect()->route('admin.exhibitions.index')->with('success', 'Exhibition deleted successfully.');
    }
}
