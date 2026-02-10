<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;

class TracksController extends Controller
{
    public function index()
    {
        $tracks = Track::withCount('strands')->latest()->paginate(15);
        return view('admin.tracks.index', compact('tracks'));
    }

    public function create()
    {
        return view('admin.tracks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:tracks,code',
            'description' => 'required|string|max:255',
        ]);

        Track::create($validated);

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track created successfully.');
    }

    public function show(Track $track)
    {
        $track->load(['strands' => function ($query) {
            $query->withCount('sections');
        }]);
        
        return view('admin.tracks.show', compact('track'));
    }

    public function edit(Track $track)
    {
        return view('admin.tracks.edit', compact('track'));
    }

    public function update(Request $request, Track $track)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:tracks,code,' . $track->id,
            'description' => 'required|string|max:255',
        ]);

        $track->update($validated);

        return redirect()->route('admin.tracks.show', $track)
            ->with('success', 'Track updated successfully.');
    }

    public function destroy(Track $track)
    {
        if ($track->strands()->count() > 0) {
            return redirect()->route('admin.tracks.index')
                ->with('error', 'Cannot delete track with associated strands.');
        }

        $track->delete();

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track deleted successfully.');
    }
}
