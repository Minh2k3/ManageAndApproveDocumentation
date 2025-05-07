<?php

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection; 

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get(uri: '/users', action: function (Request $request): Collection {
    return User::all();
});

Route::get('register-options', [RegisterController::class, 'getFormOptions'])
    ->middleware('guest')
    ->name('register.form-options');

// Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');


// Route::get('user/{identifier}', [UserController::class, 'show'])
//     ->middleware('auth:sanctum')
//     ->name('user.show');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::post('/login', [CustomAuthenticatedSessionController::class, 'store']);

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});