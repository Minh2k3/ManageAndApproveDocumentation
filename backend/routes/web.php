<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Route::get('/email/verify/{id}/{hash}', function (Request $request) {
//     // Kiểm tra chữ ký hợp lệ
//     if (!URL::hasValidSignature($request)) {
//         return redirect(env('FRONTEND_URL') . '/verified_email?status=invalid-link');
//     }

//     // Lấy user theo ID
//     $user = User::findOrFail($request->route('id'));

//     // Kiểm tra xem email đã xác thực chưa
//     if (!$user->hasVerifiedEmail()) {
//         $user->markEmailAsVerified();
//         event(new Verified($user));
//         return redirect(env('FRONTEND_URL') . '/verified_email?status=verified');
//     }

//     // Nếu đã xác thực từ trước
//     return redirect(env('FRONTEND_URL') . '/verified_email?status=already_verified');
// })->middleware(['signed'])->name('verification.verify');


Route::get('/verified_email', function () {
    return view('verified_email');
})->name('verified_email');

Route::get('/verify', [RegisterController::class, 'notice'])->name('verification.notice');

Route::post('/resend-verification-email', [RegisterController::class, 'resendVerificationEmail'])
    ->name('verification.resend');

Route::get('/api/verify-email/{id}/{token}', [RegisterController::class, 'verifyEmail'])
    ->name('verification.verify');

// require __DIR__.'/auth.php';
