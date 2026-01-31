<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherManagementController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')
            ->with('teacherProfile')
            ->latest()
            ->paginate(20);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function show(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->load('teacherProfile', 'classSchedules.section.schoolYear', 'classSchedules.subject', 'classSchedules.academicPeriod', 'advisedSections.schoolYear', 'advisedSections.strand');

        return view('admin.teachers.show', compact('teacher'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'employee_id' => 'required|string|unique:users,login_id',
            'department' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        // Create user account
        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
            'login_id' => $validated['employee_id'],
        ]);

        // Create teacher profile
        TeacherProfile::create([
            'user_id' => $user->id,
            'department' => $validated['department'],
            'specialization' => $validated['specialization'],
        ]);

        ActivityLog::log(
            'create',
            "Created teacher: {$user->first_name} {$user->last_name} (ID: {$validated['employee_id']})"
        );

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->load('teacherProfile');

        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        // Check authorization
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->isSuperAdmin();
        $isAcademicAdmin = $currentUser->role === 'academic_admin';
        $isTechnicalAdmin = $currentUser->role === 'technical_admin';

        // Technical Admin can only reset passwords
        if ($isTechnicalAdmin) {
            if (!$request->filled('password')) {
                return back()->withErrors(['authorization' => 'Technical Admins can only reset passwords.']);
            }
            
            $validated = $request->validate([
                'password' => 'required|min:8|confirmed',
            ]);

            $teacher->update([
                'password' => Hash::make($validated['password'])
            ]);

            ActivityLog::log(
                'password_reset',
                "Technical Admin reset password for teacher: {$teacher->first_name} {$teacher->last_name}",
                $currentUser
            );

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher password reset successfully.');
        }

        // Academic Admin and Super Admin can edit full profile
        if (!$isAcademicAdmin && !$isSuperAdmin) {
            abort(403, 'Unauthorized to edit teacher information.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => ['required', 'email', Rule::unique('users')->ignore($teacher->id)],
            'password' => 'nullable|min:8|confirmed',
            'employee_id' => ['required', 'string', Rule::unique('users', 'login_id')->ignore($teacher->id)],
            'department' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($teacher->avatar_path && Storage::disk('public')->exists($teacher->avatar_path)) {
                Storage::disk('public')->delete($teacher->avatar_path);
            }
            $teacher->avatar_path = null;
            $teacher->save();
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($teacher->avatar_path && Storage::disk('public')->exists($teacher->avatar_path)) {
                Storage::disk('public')->delete($teacher->avatar_path);
            }
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $teacher->avatar_path = $avatarPath;
            $teacher->save();
        }

        // Update user account
        $userData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'login_id' => $validated['employee_id'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $teacher->update($userData);

        // Update teacher profile
        $teacher->teacherProfile->update([
            'department' => $validated['department'],
            'specialization' => $validated['specialization'],
        ]);

        // Log with Super Admin override if applicable
        if ($isSuperAdmin) {
            ActivityLog::log(
                'super_admin_override',
                "SUPER ADMIN OVERRIDE: Updated teacher: {$teacher->first_name} {$teacher->last_name} (ID: {$validated['employee_id']})",
                $currentUser
            );
        } else {
            ActivityLog::log(
                'update',
                "Academic Admin updated teacher: {$teacher->first_name} {$teacher->last_name}",
                $currentUser
            );
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacherName = $teacher->full_name;
        
        $teacher->delete();

        ActivityLog::log(
            'delete',
            "Deleted teacher: {$teacherName}"
        );

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    public function toggleStatus(User $teacher)
    {
        // Check if the user is a teacher
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        // Check authorization: only super_admin and academic_admin can toggle teacher status
        if (!auth()->user()->isSuperAdmin() && auth()->user()->role !== 'academic_admin') {
            abort(403, 'Unauthorized action.');
        }

        // Toggle the is_active status
        $teacher->is_active = !$teacher->is_active;
        $teacher->save();

        $status = $teacher->is_active ? 'enabled' : 'disabled';
        $teacherName = $teacher->full_name;
        $employeeId = $teacher->login_id ?? 'N/A';

        ActivityLog::log(
            'update',
            "Teacher account {$status}: {$teacherName} (ID: {$employeeId})"
        );

        return redirect()->back()
            ->with('success', "Teacher account has been {$status} successfully.");
    }
}
