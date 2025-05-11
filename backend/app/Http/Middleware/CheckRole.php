<?php

// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Cho phép tiếp tục nếu chưa đăng nhập
        if (!$user) {
            return $next($request);
        }

        // Kiểm tra role nếu đã đăng nhập
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
