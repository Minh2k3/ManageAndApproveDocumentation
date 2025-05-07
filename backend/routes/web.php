<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    // Kiểm tra chữ ký hợp lệ
    if (!URL::hasValidSignature($request)) {
        return redirect(env('FRONTEND_URL') . '/login?status=invalid-link');
    }

    // Lấy user theo ID
    $user = User::findOrFail($request->route('id'));

    // Kiểm tra xem email đã xác thực chưa
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
        return redirect(env('FRONTEND_URL') . '/login?status=verified');
    }

    // Nếu đã xác thực từ trước
    return redirect(env('FRONTEND_URL') . '/login?status=already-verified');
})->middleware(['signed'])->name('verification.verify');

// require __DIR__.'/auth.php';
