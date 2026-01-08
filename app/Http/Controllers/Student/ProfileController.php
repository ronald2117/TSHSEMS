<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display student profile
     */
    public function index()
    {
        $student = auth()->user()->studentProfile()->with(['section.strand', 'section.schoolYear'])->first();
        
        return view('student.profile.index', compact('student'));
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        $student = auth()->user()->studentProfile;
        
        return view('student.profile.edit', compact('student'));
    }

    /**
     * Update student profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
        ]);

        $user = auth()->user();
        $student = $user->studentProfile;

        // Update user email
        $user->update(['email' => $validated['email']]);

        // Update student profile
        $student->update([
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'],
        ]);

        return redirect()->route('student.profile.index')->with('success', 'Profile updated successfully');
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

        return back()->with('success', 'Password updated successfully');
    }
}
