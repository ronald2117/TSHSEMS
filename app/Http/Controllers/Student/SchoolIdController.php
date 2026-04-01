<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SchoolIdController extends Controller
{
    /**
     * Display the upload form for ID photo and signature
     */
    public function upload()
    {
        $student = Auth::user();
        $profile = $student->studentProfile;

        return view('student.school-id.upload', compact('profile'));
    }

    /**
     * Store the uploaded ID photo
     */
    public function storePhoto(Request $request)
    {
        try {
            $request->validate([
                'id_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048|dimensions:min_width=300,min_height=400',
            ], [
                'id_photo.required' => 'Please upload your ID photo.',
                'id_photo.image' => 'The file must be an image.',
                'id_photo.mimes' => 'Only JPEG, JPG, and PNG formats are allowed.',
                'id_photo.max' => 'Image size must not exceed 2MB.',
                'id_photo.dimensions' => 'Image must be at least 300x400 pixels.',
            ]);

            $student = Auth::user();
            $profile = $student->studentProfile;

            // Delete old photo if exists
            if ($profile->id_photo_path && Storage::disk('public')->exists($profile->id_photo_path)) {
                Storage::disk('public')->delete($profile->id_photo_path);
            }

            // Store new photo
            $path = $request->file('id_photo')->store('id_photos', 'public');
            
            $profile->update(['id_photo_path' => $path]);

            return back()->with('success', 'ID photo uploaded successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload photo: ' . $e->getMessage());
        }
    }

    /**
     * Store the uploaded signature
     */
    public function storeSignature(Request $request)
    {
        try {
            $request->validate([
                'signature' => 'required|image|mimes:jpeg,jpg,png|max:1024',
            ], [
                'signature.required' => 'Please upload your signature.',
                'signature.image' => 'The file must be an image.',
                'signature.mimes' => 'Only JPEG, JPG, and PNG formats are allowed.',
                'signature.max' => 'Signature size must not exceed 1MB.',
            ]);

            $student = Auth::user();
            $profile = $student->studentProfile;

            // Delete old signature if exists
            if ($profile->signature_path && Storage::disk('public')->exists($profile->signature_path)) {
                Storage::disk('public')->delete($profile->signature_path);
            }

            // Store new signature
            $path = $request->file('signature')->store('signatures', 'public');
            
            $profile->update(['signature_path' => $path]);

            return back()->with('success', 'Signature uploaded successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload signature: ' . $e->getMessage());
        }
    }

    /**
     * Display the school ID card (preview/print)
     */
    public function show()
    {
        $student = Auth::user();
        $profile = $student->studentProfile;

        // Check if photo and signature are uploaded
        if (!$profile->id_photo_path || !$profile->signature_path) {
            return redirect()->route('student.school-id.upload')
                ->with('error', 'Please upload your ID photo and signature first.');
        }

        // Get current school year for validity
        $currentSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        $nextYear = $currentSchoolYear ? (int)substr($currentSchoolYear->name, 0, 4) + 1 : now()->year + 1;
        $validityYears = [
            $currentSchoolYear?->name ?? now()->year . '-' . (now()->year + 1),
            $nextYear . '-' . ($nextYear + 1)
        ];

        // Get emergency contact (first one if multiple)
        $emergencyContact = is_array($profile->emergency_contacts) && count($profile->emergency_contacts) > 0 
            ? $profile->emergency_contacts[0] 
            : null;

        return view('student.school-id.card', compact('profile', 'validityYears', 'emergencyContact'));
    }

    /**
     * Delete uploaded photo
     */
    public function deletePhoto()
    {
        $student = Auth::user();
        $profile = $student->studentProfile;

        if ($profile->id_photo_path && Storage::disk('public')->exists($profile->id_photo_path)) {
            Storage::disk('public')->delete($profile->id_photo_path);
        }

        $profile->update(['id_photo_path' => null]);

        return back()->with('success', 'ID photo deleted successfully!');
    }

    /**
     * Delete uploaded signature
     */
    public function deleteSignature()
    {
        $student = Auth::user();
        $profile = $student->studentProfile;

        if ($profile->signature_path && Storage::disk('public')->exists($profile->signature_path)) {
            Storage::disk('public')->delete($profile->signature_path);
        }

        $profile->update(['signature_path' => null]);

        return back()->with('success', 'Signature deleted successfully!');
    }
}
