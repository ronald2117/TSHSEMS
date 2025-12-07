<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'login_id' => 'nullable|string|unique:'.User::class,
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'login_id' => $validated['login_id'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        event(new Registered($user));

        // Create profile based on role
        if ($user->role === 'student') {
            $user->studentProfile()->create([
                'strand_id' => 1, // Default strand, should be selected during registration
            ]);
        } elseif ($user->role === 'teacher') {
            $user->teacherProfile()->create();
        }

        return redirect(route('login'))->with('success', 'Registration successful. Please log in.');
    }
}
