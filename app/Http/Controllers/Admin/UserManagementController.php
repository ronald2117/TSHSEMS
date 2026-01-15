<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Filter only admin roles (exclude teachers and students)
        $query->whereIn('role', ['super_admin', 'academic_admin', 'registrar_admin', 'technical_admin']);

        // Smart search across multiple fields including role and status
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('login_id', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
                  
                // Handle status keywords
                if (str_contains($search, 'active')) {
                    $q->orWhere('is_active', true);
                }
                if (str_contains($search, 'inactive') || str_contains($search, 'disabled')) {
                    $q->orWhere('is_active', false);
                }
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.users.index', ['users' => $users]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', ['user' => $user]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => 'required|string|email|unique:users',
            'login_id' => 'nullable|string|unique:users',
            'role' => 'required|in:super_admin,academic_admin,registrar_admin,technical_admin,teacher,student',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'avatar_path' => $avatarPath,
            'is_active' => true,
        ]);

        if (in_array($validated['role'], ['teacher'])) {
            $user->teacherProfile()->create();
        }

        ActivityLog::log(
            'create',
            "Created user: {$user->first_name} {$user->last_name} (Role: {$validated['role']})"
        );

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'login_id' => 'nullable|string|unique:users,login_id,' . $user->id,
            'role' => 'required|in:super_admin,academic_admin,registrar_admin,technical_admin,teacher,student',
            'password' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($user->avatar_path && file_exists(public_path('storage/' . $user->avatar_path))) {
                unlink(public_path('storage/' . $user->avatar_path));
            }
            $validated['avatar_path'] = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_path && file_exists(public_path('storage/' . $user->avatar_path))) {
                unlink(public_path('storage/' . $user->avatar_path));
            }
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle password update
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLog::log(
            'update',
            "Updated user: {$user->first_name} {$user->last_name} (Role: {$user->role})"
        );

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        ActivityLog::log(
            'update',
            "Changed user status to " . ($newStatus ? 'active' : 'inactive') . ": {$user->first_name} {$user->last_name}"
        );

        return back()->with('success', 'User status updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $userName = $user->full_name;
        $userRole = $user->role;
        
        $user->delete();

        ActivityLog::log(
            'delete',
            "Deleted user: {$userName} (Role: {$userRole})"
        );

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
