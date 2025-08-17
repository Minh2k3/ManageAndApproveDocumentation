<?php
// app/Http/Middleware/VerifyCsrfToken.php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // API routes
        'api/*',
        
        // Wish routes
        'wishes',
        'wishes/*',
        
        // Sanctum routes
        'sanctum/*',
        
        // Development routes (remove in production)
        '*', // ✅ This disables CSRF for ALL routes (DEVELOPMENT ONLY)
    ];
}