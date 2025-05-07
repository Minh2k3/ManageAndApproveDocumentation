<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    if (! URL::hasValidSignature($request)) {
        return response()->json(['message' => 'Invalid or expired verification link.'], 403);
    }

    $user = \App\Models\User::findOrFail($request->route('id'));

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    // Redirect về trang frontend nếu muốn
    return redirect(env('FRONTEND_URL') . '/login');
})->middleware(['signed'])->name('verification.verify');

// require __DIR__.'/auth.php';
