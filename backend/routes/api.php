<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Collection; 
use App\Http\Controllers\RegisterController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get(uri: '/users', action: function (Request $request): Collection {
    return User::all();
});

Route::get('register-options', [RegisterController::class, 'getFormOptions'])
    ->middleware('guest')
    ->name('register.form-options');

// Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');