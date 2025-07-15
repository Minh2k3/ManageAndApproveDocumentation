<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class CustomAuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        $user = Auth::user();

        if (!$user->email_verified) {
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

        $token = $user->createToken('auth_token')->plainTextToken;
        $remember = $request->boolean('remember');
        // Nếu remember = true, tạo remember token
        $rememberToken = null;
        if ($remember) {
            $rememberToken = Str::random(60);
            $user->remember_token = $rememberToken;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'remember_token' => $rememberToken,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    public function loginWithRememberToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remember_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Remember token is required',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('remember_token', $request->remember_token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid remember token'
            ], 401);
        }

        // Tạo token mới
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful with remember token',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }    

    public function user(Request $request)
    {
        \Log::info('User details requested', ['user' => $request->user()]);
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }    
}
