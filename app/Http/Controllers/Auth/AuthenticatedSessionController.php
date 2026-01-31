<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Support both email and login_id for authentication
        $user = \App\Models\User::where('email', $credentials['login_id'])
            ->orWhere('login_id', $credentials['login_id'])
            ->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            // Check if account is active
            if (!$user->is_active) {
                ActivityLog::log(
                    'login_failed',
                    "Login attempt blocked - account disabled: {$user->full_name} (Role: {$user->role})",
                    $user
                );

                return back()->withErrors([
                    'login_id' => 'Your account has been disabled. Please contact the administrator.',
                ])->onlyInput('login_id');
            }

            Auth::login($user);
            $request->session()->regenerate();
            
            // Update last login
            $user->update(['last_login_at' => now()]);

            ActivityLog::log(
                'login',
                "User logged in: {$user->full_name} (Role: {$user->role})",
                $user
            );

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'login_id' => 'The provided credentials do not match our records.',
        ])->onlyInput('login_id');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            ActivityLog::log(
                'logout',
                "User logged out: {$user->full_name}",
                $user
            );
        }

        return redirect('/');
    }
}
