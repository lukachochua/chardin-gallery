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
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'location' => 'required|string',
            'image' => 'required|string',
            'artists' => 'array',
            'artworks' => 'array',
        ]);

        $exhibition = Exhibition::create($request->only([
            'title',
            'start_date',
            'end_date',
            'location',
            'image'
        ]));

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
        // Base validation rules
        $validationRules = [
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'location' => 'required|string',
            'artists' => 'array',
            'artworks' => 'array',
        ];

        // Add image validation only if a new image is being uploaded
        if ($request->hasFile('image')) {
            $validationRules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $request->validate($validationRules);

        // Prepare data for update
        $data = $request->only([
            'title',
            'start_date',
            'end_date',
            'location',
        ]);

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($exhibition->image && Storage::exists('public/' . $exhibition->image)) {
                Storage::delete('public/' . $exhibition->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('exhibitions', 'public');
            $data['image'] = $imagePath;
        }

        // Update exhibition
        $exhibition->update($data);

        // Sync relationships
        if ($request->has('artists')) {
            $exhibition->artists()->sync($request->artists);
        }

        if ($request->has('artworks')) {
            $exhibition->artworks()->sync($request->artworks);
        }

        return redirect()
            ->route('admin.exhibitions.index')
            ->with('success', 'Exhibition updated successfully.');
    }

    // Remove the specified exhibition
    public function destroy(Exhibition $exhibition)
    {
        $exhibition->delete();
        return redirect()->route('admin.exhibitions.index')->with('success', 'Exhibition deleted successfully.');
    }
}
