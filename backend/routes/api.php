<?php

use Illuminate\Http\Request;
use App\Models\User;

// Auth Controllers
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\VerifyEmailController;

// User Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\ApproverController;

// Document Controllers
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentFlowController;
use App\Http\Controllers\DocumentFlowStepController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\DocumentVersionController;

// Other Controllers
use App\Http\Controllers\DepartmentController;

// 
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

// Document api
// Document
Route::get('/documents', [DocumentController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('documents.index');

Route::get('/documents/{document}', [DocumentController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('documents.show');


// Document Flow 
Route::get('/document-flows', [DocumentFlowController::class, 'index'])
    ->name('document-flows.index');

Route::get('/document-flows/{documentFlow}', [DocumentFlowController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('document-flows.show');


// Document Flow Step
Route::get('/document-flow-steps', [DocumentFlowStepController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('document-flow-steps.index');

Route::get('document-flow-steps/{documentFlow}', [DocumentFlowStepController::class, 'getStepsByDocumentFlowId'])
    ->name('document-flow-steps.getStepsByDocumentFlowId');

// Document Type
Route::get('/document-types', [DocumentTypeController::class, 'index'])
    ->name('document-types.index');

// Document Template
Route::get('/document-templates', [DocumentTemplateController::class, 'index'])
    ->name('document-templates.index');


// Approver 
Route::get('/approvers', [ApproverController::class, 'index'])
    ->name('approvers.index');

Route::get('/approvers/with_roll', [ApproverController::class, 'getApproverWithRoll'])
    ->name('approvers.getApproverWithRoll');


// Department
Route::get('/departments', [DepartmentController::class, 'index'])
    ->name('departments.index');

Route::get('/departments/can_approve', [DepartmentController::class, 'getDepartmentsCanApprove'])
    ->name('departments.getDepartmentsCanApprove');

