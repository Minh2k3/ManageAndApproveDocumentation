<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Models\PasswordResetToken;

class HandlePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:2|confirmed',
        ], [
            'new_password.confirmed' => 'Mật khẩu mới không khớp.',
            'current_password.string' => 'Mật khẩu hiện tại phải là chuỗi.',
            'new_password.string' => 'Mật khẩu mới phải là chuỗi.',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = \Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function forgotPassword(Request $request)    
    {
        \Log::info('Forgot password request received', ['email' => $request->input('email')]);
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        
        // Rate limiting key
        $key = 'forgot-password:' . $email;
        
        // Kiểm tra rate limit (3 lần trong 60 phút)
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            
            return response()->json([
                'message' => "Quá nhiều yêu cầu. Vui lòng thử lại sau {$minutes} phút."
            ], 429);
        }

        $user = User::where('email', $email)->first();
        \Log::info('User found', ['user' => $user]);
        
        if (!$user) {
            // Vẫn increment để tránh email enumeration
            RateLimiter::hit($key, 3600); // 60 minutes
            return response()->json([
                'message' => 'Email không tồn tại trong hệ thống'
            ], 403);
        }

        // Kiểm tra xem đã gửi email gần đây chưa
        $recentToken = PasswordResetToken::where('email', $email)
            ->where('created_at', '>', now()->subMinutes(5)) // 5 phút
            ->first();
            
        if ($recentToken) {
            return response()->json([
                'message' => 'Email đặt lại mật khẩu đã được gửi. Vui lòng kiểm tra hộp thư và thử lại sau 5 phút.'
            ], 429);
        }

        // Increment rate limiter
        RateLimiter::hit($key, 3600); // 60 minutes

        // Xóa token cũ
        PasswordResetToken::where('email', $email)->delete();

        // Tạo token mới
        $token = Str::random(64);
        
        PasswordResetToken::create([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Gửi email
        try {
            Mail::to($email)->send(new PasswordResetMail($token, $user));
            
            return response()->json([
                'message' => 'Link đặt lại mật khẩu đã được gửi đến email của bạn'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi gửi email'
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        $newPassword = $request->input('password');

        // Tìm token reset
        $resetToken = PasswordResetToken::where('email', $email)->first();

        if (!$resetToken) {
            return response()->json([
                'message' => 'Token không hợp lệ'
            ], 400);
        }

        // Kiểm tra token có đúng không
        if (!Hash::check($token, $resetToken->token)) {
            return response()->json([
                'message' => 'Token không hợp lệ'
            ], 400);
        }

        // Kiểm tra token có hết hạn không (60 phút)
        if ($resetToken->isExpired()) {
            return response()->json([
                'message' => 'Token đã hết hạn'
            ], 400);
        }

        // Cập nhật mật khẩu
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($newPassword);
        $user->save();

        // Xóa token đã sử dụng
        $resetToken->delete();

        return response()->json([
            'message' => 'Mật khẩu đã được đặt lại thành công'
        ]);
    }

    public function verifyResetToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');

        $resetToken = PasswordResetToken::where('email', $email)->first();

        if (!$resetToken || !Hash::check($token, $resetToken->token) || $resetToken->isExpired()) {
            return response()->json([
                'valid' => false,
                'message' => 'Token không hợp lệ hoặc đã hết hạn'
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Token hợp lệ'
        ]);
    }
}
