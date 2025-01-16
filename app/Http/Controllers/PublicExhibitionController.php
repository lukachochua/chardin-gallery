<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use Illuminate\Http\Request;

class PublicExhibitionController extends Controller
{
    public function index(Request $request)
    {
        $exhibitions = Exhibition::query()
            ->with(['artists', 'artworks'])
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            })
            ->when($request->status, function ($query, $status) {
                return match ($status) {
                    'upcoming' => $query->where('start_date', '>', now()),
                    'running' => $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now()),
                    'done' => $query->where('end_date', '<', now()),
                    default => $query,
                };
            })
            ->latest('start_date')
            ->paginate(12)
            ->withQueryString();

        return view('exhibitions.index', [
            'exhibitions' => $exhibitions
        ]);
    }

    public function show(Exhibition $exhibition)
    {
        $exhibition->load(['artists', 'artworks']);

        $relatedExhibitions = Exhibition::query()
            ->with(['artists', 'artworks'])
            ->where('id', '!=', $exhibition->id)
            ->whereHas('artists', function ($query) use ($exhibition) {
                $query->whereIn('artists.id', $exhibition->artists->pluck('id'));
            })
            ->latest('start_date')
            ->take(3)
            ->get();

        $exhibitions = Exhibition::with(['artists', 'artworks'])
            ->latest('start_date')
            ->get();

        return view('exhibitions.show', [
            'exhibition' => $exhibition,
            'relatedExhibitions' => $relatedExhibitions,
            'exhibitions' => $exhibitions, 
        ]);
    }
}
