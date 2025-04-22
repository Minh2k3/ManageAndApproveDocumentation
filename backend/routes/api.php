<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::middleware('api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/user/{id}', [UserController::class, 'show']);
// Route::get('/users', [UserController::class, 'index']);
Route::get('/users/create', [UserController::class, 'create']);
// Route::post('/users', [UserController::class, 'store']);