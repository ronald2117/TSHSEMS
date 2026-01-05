<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Models\Track;
use Illuminate\Http\Request;

class StrandsController extends Controller
{
    public function index()
    {
        $strands = Strand::with('track')
            ->withCount('sections')
            ->latest()
            ->paginate(15);
            
        return view('admin.strands.index', compact('strands'));
    }

    public function create()
    {
        $tracks = Track::orderBy('code')->get();
        return view('admin.strands.create', compact('tracks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'track_id' => 'required|exists:tracks,id',
            'code' => 'required|string|max:20|unique:strands,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        Strand::create($validated);

        return redirect()->route('admin.strands.index')
            ->with('success', 'Strand created successfully.');
    }

    public function show(Strand $strand)
    {
        $strand->load(['track', 'sections' => function ($query) {
            $query->withCount('studentProfiles');
        }]);
        
        return view('admin.strands.show', compact('strand'));
    }

    public function edit(Strand $strand)
    {
        $tracks = Track::orderBy('code')->get();
        return view('admin.strands.edit', compact('strand', 'tracks'));
    }

    public function update(Request $request, Strand $strand)
    {
        $validated = $request->validate([
            'track_id' => 'required|exists:tracks,id',
            'code' => 'required|string|max:20|unique:strands,code,' . $strand->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $strand->update($validated);

        return redirect()->route('admin.strands.show', $strand)
            ->with('success', 'Strand updated successfully.');
    }

    public function destroy(Strand $strand)
    {
        if ($strand->sections()->count() > 0) {
            return redirect()->route('admin.strands.index')
                ->with('error', 'Cannot delete strand with associated sections.');
        }

        $strand->delete();

        return redirect()->route('admin.strands.index')
            ->with('success', 'Strand deleted successfully.');
    }
}
