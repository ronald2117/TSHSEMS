<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrarAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || 
            (auth()->user()->role !== 'registrar_admin' && !auth()->user()->isSuperAdmin())) {
            return redirect('dashboard')->with('error', 'Unauthorized access');
        }
        return $next($request);
    }
}
