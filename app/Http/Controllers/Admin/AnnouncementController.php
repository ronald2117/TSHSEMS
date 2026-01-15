<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')
            ->latest('created_at')
            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_public' => 'boolean',
            'target_role' => 'nullable|in:student,teacher,admin',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['is_public'] = $request->has('is_public');
        $validated['is_pinned'] = $request->has('is_pinned');
        
        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Announcement::create($validated);

        ActivityLog::log(
            'create',
            "Created announcement: {$validated['title']}"
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_public' => 'boolean',
            'target_role' => 'nullable|in:student,teacher,admin',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        $validated['is_public'] = $request->has('is_public');
        $validated['is_pinned'] = $request->has('is_pinned');

        $announcement->update($validated);

        ActivityLog::log(
            'update',
            "Updated announcement: {$validated['title']}"
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $title = $announcement->title;
        
        $announcement->delete();

        ActivityLog::log(
            'delete',
            "Deleted announcement: {$title}"
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}
