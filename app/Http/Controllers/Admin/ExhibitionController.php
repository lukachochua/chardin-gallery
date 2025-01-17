<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExhibitionRequest;
use App\Models\Exhibition;
use App\Models\Artist;
use App\Models\Artwork;
use App\Services\ExhibitionService;

class ExhibitionController extends Controller
{
    protected ExhibitionService $exhibitionService;

    public function __construct(ExhibitionService $exhibitionService)
    {
        $this->exhibitionService = $exhibitionService;
    }

    public function index()
    {
        $exhibitions = $this->exhibitionService->getAllExhibitions();
        return view('admin.exhibitions.index', compact('exhibitions'));
    }

    public function create()
    {
        $artists = Artist::all();
        $artworks = Artwork::all();
        return view('admin.exhibitions.create', compact('artists', 'artworks'));
    }

    public function store(ExhibitionRequest $request)
    {
        $data = $request->validated();
        $this->exhibitionService->createExhibition($data);

        return redirect()->route('admin.exhibitions.index')->with('success', 'Exhibition created successfully.');
    }

    public function edit(Exhibition $exhibition)
    {
        $artists = Artist::all();
        $artworks = Artwork::all();
        return view('admin.exhibitions.edit', compact('exhibition', 'artists', 'artworks'));
    }

    public function update(ExhibitionRequest $request, Exhibition $exhibition)
    {
        $data = $request->validated();
        $this->exhibitionService->updateExhibition($exhibition, $data);

        return redirect()->route('admin.exhibitions.index')->with('success', 'Exhibition updated successfully.');
    }

    public function destroy(Exhibition $exhibition)
    {
        $this->exhibitionService->deleteExhibition($exhibition);
        return redirect()->route('admin.exhibitions.index')->with('success', 'Exhibition deleted successfully.');
    }
}
