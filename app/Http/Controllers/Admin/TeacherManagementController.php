<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        ]);

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

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
