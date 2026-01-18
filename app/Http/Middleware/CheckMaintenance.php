<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;

class CheckMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (!SystemSetting::isMaintenanceMode()) {
            return $next($request);
        }

        // Allow super admins if setting is enabled
        if (auth()->check() && 
            auth()->user()->isSuperAdmin() && 
            SystemSetting::allowSuperAdminDuringMaintenance()) {
            return $next($request);
        }

        // Redirect to maintenance page
        if (!$request->is('maintenance')) {
            return redirect()->route('maintenance.show');
        }

        return $next($request);
    }
}
