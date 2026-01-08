<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display student announcements
     */
    public function index()
    {
        $announcements = Announcement::query()
            ->where('status', 'published')
            ->where(function ($query) {
                $query->where('target_role', 'student')
                      ->orWhere('target_role', 'all');
            })
            ->latest('published_at')
            ->paginate(10);

        return view('student.announcements.index', compact('announcements'));
    }
}
