<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    /**
     * Display admin profile
     */
    public function index()
    {
        $admin = auth()->user();
        
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        $admin = auth()->user();
        
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $user = auth()->user();

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

        // Update user information
        $user->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
        ]);

        ActivityLog::log(
            'update',
            "Admin updated own profile: {$user->full_name}"
        );

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully');
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
            "Admin changed password: " . auth()->user()->full_name
        );

        return back()->with('success', 'Password updated successfully');
    }
}
