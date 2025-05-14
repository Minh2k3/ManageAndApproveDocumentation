<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerificationMail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use App\Models\Approver;
use App\Models\Creator;

class RegisterController extends Controller
{

    public function getFormOptions(): JsonResponse
    {
        $departments = \DB::table('departments')
            ->select(
                'id as value',
                'name as label'
            )
            ->where('id', '>', 3)
            ->get();

        $rolls = \DB::table('roll_at_departments')
            ->select(
                'id as value',
                'name as label'
            )
            ->get();

        return response()
                    ->json([
                        'departments' => $departments,
                        'rolls' => $rolls,
                    ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'department_id' => 'required|integer',
            'roll_at_department_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $existingUser = User::where('email', $request['email'])->first();
        if ($existingUser && !$existingUser->email_verified) {
            // Tạo token xác thực mới và thời gian hết hạn
            $verificationToken = Str::random(64);
            $tokenExpiry = Carbon::now()->addDays(3);

            // Cập nhật thông tin người dùng
            $existingUser->name = $request['name'];
            $existingUser->password = Hash::make($request['password']);
            $existingUser->verification_token = $verificationToken;
            $existingUser->verification_token_expiry = $tokenExpiry;
            $existingUser->save();

            // Gửi lại email xác thực
            Mail::to($existingUser->email)->send(new VerificationEmail($existingUser));

            return response()->json([
                'status' => 'success',
                'message' => 'Email xác thực đã được gửi lại',
            ], 200);
        }

        if ($existingUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email đã đăng ký và xác thực thành công',
            ], 422);
        }

        // Lấy thông tin department
        $department = \DB::table('departments')
            ->where('id', $request['department_id'])
            ->first();
            
        $roll = \DB::table('roll_at_departments')
            ->where('id', $request['roll_at_department_id'])
            ->first();
        // Xác định role_id dựa trên thuộc tính can_approve
        $role_id = ($department->can_approve && $roll->level <= 5) ? 3 : 2; // 3: Approver, 2: Creator

        $roll_id = $request['roll_at_department_id'];

        $verificationToken = Str::random(64);
        $tokenExpiry = Carbon::now()->addDays(3);

        \DB::beginTransaction();

        try {
            // Tạo user mới
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'department_id' => $request['department_id'],
                'role_id' => $role_id,
                'email_verified' => false,
                'email_verified_at' => null,
                'verification_token' => $verificationToken,
                'verification_token_expiry' => $tokenExpiry,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Thêm vào bảng approvers hoặc creators
            if ($role_id === 3) {
                Approver::create([
                    'user_id' => $user->id,
                    'department_id' => $request['department_id'],
                    'roll_at_department_id' => $roll_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                Creator::create([
                    'user_id' => $user->id,
                    'department_id' => $request['department_id'],
                    'roll_at_department_id' => $roll_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Gửi email xác thực
            Mail::to($user->email)->send(new VerificationMail($user));

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tài khoản đã được tạo thành công. Vui lòng kiểm tra email để xác thực tài khoản.',
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi tạo tài khoản: ' . $e->getMessage(),
            ], 500);
        }
        
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'User created successfully',
        //     'user' => $user,
        // ], 201);
    }

    public function notice()
    {
        return view('auth.verify-email')->with('success', 'Đăng ký thành công vui lòng kiểm tra email để xác thực!');
    }

    public function verifyEmail($id, $token)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->to('/verified_email?status=not_found');
        }

        if ($user->email_verified) {
            return redirect()->to('/verified_email?status=already_verified');
        }

        if ($user->verification_token !== $token) {
            return redirect()->to('/verified_email?status=invalid');
        }

        if (now()->greaterThan($user->verification_token_expiry)) {
            return redirect()->to('/verified_email?status=expired');
        }
        
        \DB::beginTransaction();
        try {
            $user->email_verified = true;
            $user->email_verified_at = now(); // Thêm timestamp xác thực
            $user->status = "pending";
            $user->verification_token = null;
            $user->verification_token_expiry = null;
            $user->save();
            
            \DB::commit();
            
            return redirect(env('APP_URL') . '/verified_email?status=verified');
        } catch (\Exception $e) {
            \DB::rollBack();
            print_r($e->getMessage());
            return redirect(env('APP_URL') . '/verified_email?status=error')
                ->withErrors(['message' => 'Có lỗi xảy ra khi xác thực email.']);
        }
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/[a-z0-9._%+-]+@(tlu\.edu\.vn|e\.tlu\.edu\.vn)$/',
            ],
        ], [
            'email.regex' => 'Email phải có định dạng @tlu.edu.vn hoặc @e.tlu.edu.vn',
        ]);

        $user = User::where('email', $request['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Không tìm thấy email này trong hệ thống.']);
        }

        if ($user->email_verified) {
            return ridẻect()->route('login')
                ->with('success', 'Email đã được xác thực trước đó. Vui lòng đăng nhập.');
        }

        $resendLimit = 5;
        $cooldownPeriod = 5; // minutes

        if ($user->verification_resent_count >= $resendLimit) {
            $lastResent = $user->last_verification_resent_at;

            if ($lastResent && Carbon::now()->diffInMinutes($lastResent) < $cooldownPeriod) {
                $minutesLeft = $cooldownPeriod - Carbon::now()->diffInMinutes($lastResent);
                return back()->withErrors(['email' => "Vui lòng đợi {$minutesLeft} phút trước khi gửi lại email xác thực."]);
            }

            $user->verification_resent_count = 0;
        }

        // Tạo token mới
        $user->verification_token = Str::random(64);
        $user->verification_token_expiry = Carbon::now()->addDays(3);
        $user->verification_resent_count += 1;
        $user->last_verification_resent_at = Carbon::now();
        $user->save();

        try {
            Mail::to($user->email)->send(new VerificationEmail($user));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Có lỗi khi gửi email xác thực. Vui lòng thử lại sau.']);
        }

        return back()->with('error', 'Email xác thực đã được gửi lại thành công!');
    }
}
