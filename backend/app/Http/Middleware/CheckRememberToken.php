<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckRememberToken
{
    public function handle(Request $request, Closure $next)
    {
        $rememberToken = $request->cookie('remember_token');
        
        if ($rememberToken && !auth()->check()) {
            $user = User::where('remember_token', $rememberToken)->first();
            
            if ($user) {
                auth()->login($user);
            }
        }
        
        return $next($request);
    }
}