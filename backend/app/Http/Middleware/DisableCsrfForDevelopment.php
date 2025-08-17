<?php
// app/Http/Middleware/DisableCsrfForDevelopment.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableCsrfForDevelopment
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only disable CSRF in local environment
        if (config('app.env') === 'local') {
            // Skip CSRF verification
            return $next($request);
        }
        
        // In production, let normal CSRF middleware handle it
        return $next($request);
    }
}