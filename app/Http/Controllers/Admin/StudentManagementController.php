<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Strand;
use App\Models\Section;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->with('studentProfile.strand', 'studentProfile.currentSection');

        // Smart search across multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('login_id', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function($q) use ($search) {
                      $q->where('lrn', 'like', "%{$search}%")
                        ->orWhere('guardian_name', 'like', "%{$search}%")
                        ->orWhere('guardian_contact', 'like', "%{$search}%")
                        ->orWhereHas('strand', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('currentSection', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('grade_level', 'like', "%{$search}%");
                        });
                  });
            });
        }

        $students = $query->latest()->paginate(20)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        // Only registrar and super admin can create students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $strands = Strand::all();
        $sections = Section::with('schoolYear', 'strand')
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        return view('admin.students.create', compact('strands', 'sections'));
    }

    public function show(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->load('studentProfile.strand', 'studentProfile.currentSection.schoolYear');

        return view('admin.students.show', compact('student'));
    }

    public function store(Request $request)
    {
        // Only registrar and super admin can create students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'lrn' => 'required|string|unique:student_profiles,lrn',
            'strand_id' => 'required|exists:strands,id',
            'current_section_id' => 'nullable|exists:sections,id',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string',
        ]);

        // Create user account
        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'login_id' => $validated['lrn'],
        ]);

        // Create student profile
        StudentProfile::create([
            'user_id' => $user->id,
            'lrn' => $validated['lrn'],
            'strand_id' => $validated['strand_id'],
            'current_section_id' => $validated['current_section_id'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_contact' => $validated['guardian_contact'],
            'birthdate' => $validated['birthdate'],
            'address' => $validated['address'],
        ]);

        ActivityLog::log(
            'create',
            "Created student: {$user->first_name} {$user->last_name} (LRN: {$validated['lrn']})"
        );

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        // Only registrar and super admin can edit students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $student->load('studentProfile');
        $strands = Strand::all();
        $sections = Section::with('schoolYear', 'strand')
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        return view('admin.students.edit', compact('student', 'strands', 'sections'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        // Only registrar and super admin can update students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->id)],
            'password' => 'nullable|min:8|confirmed',
            'lrn' => ['required', 'string', Rule::unique('student_profiles')->ignore($student->studentProfile->id)],
            'strand_id' => 'required|exists:strands,id',
            'current_section_id' => 'nullable|exists:sections,id',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($student->avatar_path && \Storage::disk('public')->exists($student->avatar_path)) {
                \Storage::disk('public')->delete($student->avatar_path);
            }
            $student->avatar_path = null;
            $student->save();
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($student->avatar_path && \Storage::disk('public')->exists($student->avatar_path)) {
                \Storage::disk('public')->delete($student->avatar_path);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $student->avatar_path = $avatarPath;
            $student->save();
        }

        // Update user account
        $userData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'login_id' => $validated['lrn'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $student->update($userData);

        // Update student profile
        $student->studentProfile->update([
            'lrn' => $validated['lrn'],
            'strand_id' => $validated['strand_id'],
            'current_section_id' => $validated['current_section_id'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_contact' => $validated['guardian_contact'],
            'birthdate' => $validated['birthdate'],
            'address' => $validated['address'],
        ]);

        ActivityLog::log(
            'update',
            "Updated student: {$student->first_name} {$student->last_name} (LRN: {$validated['lrn']})"
        );

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        // Only registrar and super admin can delete students
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        $studentName = $student->full_name;
        $lrn = $student->studentProfile->lrn;
        
        $student->delete();

        ActivityLog::log(
            'delete',
            "Deleted student: {$studentName} (LRN: {$lrn})"
        );

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function toggleStatus(User $student)
    {
        // Check if the user is a student
        if ($student->role !== 'student') {
            abort(404);
        }

        // Check authorization: only super_admin and registrar_admin can toggle student status
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'registrar_admin') {
            abort(403, 'Unauthorized action.');
        }

        // Toggle the is_active status
        $student->is_active = !$student->is_active;
        $student->save();

        $status = $student->is_active ? 'enabled' : 'disabled';
        $studentName = $student->full_name;
        $lrn = $student->studentProfile->lrn ?? 'N/A';

        ActivityLog::log(
            'update',
            "Student account {$status}: {$studentName} (LRN: {$lrn})"
        );

        return redirect()->back()
            ->with('success', "Student account has been {$status} successfully.");
    }
}
