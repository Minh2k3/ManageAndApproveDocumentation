<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            // Xoá token của người dùng
            $user->remember_token = null; // Xoá remember token nếu có
            $user->save();
            // Xoá tất cả các token cá nhân
            $user->tokens()->delete();
            Auth::guard('web')->logout();
            $cookie = cookie('XSRF-TOKEN', '', -1);
            return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
    }
}
