<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomAuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return response()->json([
                'message' => 'Bạn chưa xác minh địa chỉ email. Vui lòng kiểm tra hộp thư.',
            ], 401);
        }

        if ($user->status === 'pending') {
            Auth::logout();
            return response()->json([
                'message' => 'Tài khoản của bạn chưa được duyệt!'
            ], 402);
        }

        if ($user->status === 'banned') {
            Auth::logout();
            return response()->json([
                'message' => 'Tài khoản của bạn đang bị cấm!'
            ], 402);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => $user,
        ]);
    }
}
