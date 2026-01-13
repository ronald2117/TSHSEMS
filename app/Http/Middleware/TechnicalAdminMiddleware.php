<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TechnicalAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'technical_admin') {
            return redirect('dashboard')->with('error', 'Unauthorized access - Technical Admin only');
        }
        return $next($request);
    }
}
