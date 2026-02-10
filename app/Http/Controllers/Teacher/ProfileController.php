<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    /**
     * Display teacher profile
     */
    public function index()
    {
        $teacher = auth()->user()->load('teacherProfile');
        
        return view('teacher.profile.index', compact('teacher'));
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        $teacher = auth()->user()->load('teacherProfile');
        
        return view('teacher.profile.edit', compact('teacher'));
    }

    /**
     * Update teacher profile (personal and security details only)
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'specialization' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $user = auth()->user();
        $teacher = $user->teacherProfile;

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $user->avatar_path = null;
            $user->save();
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $avatarPath;
            $user->save();
        }

        // Update user email
        $user->update(['email' => $validated['email']]);

        // Update teacher profile (specialization only - department is managed by admin)
        if ($teacher) {
            $teacher->update([
                'specialization' => $validated['specialization'],
            ]);
        }

        ActivityLog::log(
            'update',
            "Teacher updated own profile: {$user->full_name}"
        );

        return redirect()->route('teacher.profile.index')->with('success', 'Profile updated successfully');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        ActivityLog::log(
            'update',
            "Teacher changed password: " . auth()->user()->full_name
        );

        return back()->with('success', 'Password updated successfully');
    }
}
