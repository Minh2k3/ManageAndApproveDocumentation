<?php

use Illuminate\Http\Request;
use App\Models\User;

// Auth Controllers
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\HandlePasswordController;

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
use App\Http\Controllers\DocumentCommentController;

// Other Controllers
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserAccessLogController;
use App\Http\Controllers\Api\PDFProxyController;

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

Route::get('/register-options', [RegisterController::class, 'getFormOptions'])
    ->name('register.form-options');

Route::post('/register', [RegisterController::class, 'register']);

Route::get('/verify-email/{id}/{token}', [RegisterController::class, 'verifyEmail'])
    ->name('verification.verify');

// Route::get('user/{identifier}', [UserController::class, 'show'])
//     ->middleware('auth:sanctum')
//     ->name('user.show');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])
    ->name('login');

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});

// User api
// User
Route::get('/users', [UserController::class, 'getUsers'])
    // ->middleware('auth:sanctum')
    ->name('users.index');

Route::post('/users/active', [UserController::class, 'activeUser'])
    ->middleware('auth:sanctum')
    ->name('users.active');

Route::post('/users/banned', [UserController::class, 'bannedUser'])
    ->middleware('auth:sanctum')
    ->name('users.banned');

Route::post('/users/unban', [UserController::class, 'unbanUser'])
    ->middleware('auth:sanctum')
    ->name('users.unban');

Route::get('/users/{user_id}/approver', [UserController::class, 'getApproverByUserId'])
    // ->middleware('auth:sanctum')
    ->name('users.getApproverByUserId');

Route::get('/users/{user_id}/creator', [UserController::class, 'getCreatorByUserId'])
    // ->middleware('auth:sanctum')
    ->name('users.getCreatorByUserId');

Route::post('/users/{user_id}/change-password', [HandlePasswordController::class, 'changePassword'])
    // ->middleware('auth:sanctum')
    ->name('users.changePassword');

// Document api
// Document
Route::get('/documents', [DocumentController::class, 'index'])
    // ->middleware('auth:sanctum')
    ->name('documents.index');

Route::get('/documents/public', [DocumentController::class, 'getPublicApprovedDocuments'])
    // ->middleware('auth:sanctum')
    ->name('documents.getPublicApprovedDocuments');

Route::get('/documents/{document_id}', [DocumentController::class, 'show'])
    // ->middleware('auth:sanctum')
    ->name('documents.show');

Route::get('/creators/{id}/documents', [DocumentController::class, 'getDocumentsByCreator'])
    // ->middleware('auth:sanctum')
    ->name('documents.getDocumentsByCreator');

Route::get('/approvers/{id}/documents', [DocumentController::class, 'getDocumentsByApprover'])
    // ->middleware('auth:sanctum')
    ->name('documents.getDocumentsByApprover');

Route::get('/document/{id}/fm', [DocumentController::class, 'getDocumentOfMeById'])
    // ->middleware('auth:sanctum')
    ->name('documents.getDocumentOfMeById');

Route::get('/document/{id}/nm', [DocumentController::class, 'getDocumentNeedMeById'])
    // ->middleware('auth:sanctum')
    ->name('documents.getDocumentNeedMeById');
    

Route::post('/documents/draft', [DocumentController::class, 'storeDraftDocument'])
    // ->middleware('auth:sanctum')
    ->name('documents.storeDraftDocument');

Route::post('/documents/request', [DocumentController::class, 'storeRequestDocument'])
    // ->middleware('auth:sanctum')
    ->name('documents.storeRequestDocument');

Route::post('/documents/upload-file', [DocumentController::class, 'uploadFile'])
    // ->middleware('auth:sanctum')
    ->name('documents.uploadFile');

Route::prefix('documents')->group(function () {
    Route::get('{filename}/view', [DocumentController::class, 'viewPdf'])->name('documents.view');
    Route::get('{filename}/download', [DocumentController::class, 'downloadPdf'])->name('documents.download');
});

Route::get('/documents/{id}/comments', [DocumentCommentController::class, 'getCommentsByDocument']);
Route::post('/documents/{document_id}/comments', [DocumentCommentController::class, 'storeComment'])
    // ->middleware('auth:sanctum')
    ->name('documents.storeComment');

// Document Flow 
Route::get('/document-flows', [DocumentFlowController::class, 'index'])
    ->name('document-flows.index');

Route::get('/document-flows/{documentFlow}', [DocumentFlowController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('document-flows.show');

Route::get('/document-flows/{id}/steps', [DocumentFlowController::class, 'getStepsByDocumentFlow'])
    // ->middleware('auth:sanctum')
    ->name('document-flows.getStepsByDocumentFlow');


// Document Flow Step
Route::get('/document-flow-steps', [DocumentFlowStepController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('document-flow-steps.index');

Route::get('document-flow-steps/{documentFlow}', [DocumentFlowStepController::class, 'getStepsByDocumentFlowId'])
    ->name('document-flow-steps.getStepsByDocumentFlowId');

Route::post('/document-steps/{document_flow_step_id}/approve', [DocumentFlowStepController::class, 'approveStep'])
    // ->middleware('auth:sanctum')
    ->name('document-flow-steps.approveStep');

Route::post('/document-steps/{document_flow_step_id}/reject', [DocumentFlowStepController::class, 'rejectStep'])
    // ->middleware('auth:sanctum')
    ->name('document-flow-steps.rejectStep');

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

// Notification
Route::get('/notifications/{user_id}', [NotificationController::class, 'getAllNotificationsByUserId'])
    // ->middleware('auth:sanctum')
    ->name('notifications.getAllNotificationsByUserId');

Route::post('/notifications/read/{notification_id}', [NotificationController::class, 'markAsRead'])
    // ->middleware('auth:sanctum')
    ->name('notifications.markAsRead');

Route::post('/notifications/read-all/{user_id}', [NotificationController::class, 'markAllAsRead'])
    // ->middleware('auth:sanctum')
    ->name('notifications.markAllAsRead');

// Statistics
Route::get('/access-logs', [UserAccessLogController::class, 'getAccessStats'])
    // ->middleware('auth:sanctum')
    ->name('access-logs.getAccessStats');

Route::get('/pdf-proxy', [PDFProxyController::class, 'proxy']);
Route::get('/pdf-list', [PDFProxyController::class, 'list']);

Route::get('/access-statistics', [UserAccessLogController::class, 'dailyAccess']);

// Download Document
Route::get('/download-document', function (Request $request) {
    $filePath = $request->query('file_path');
    $fullPath = storage_path('public/documents/' . $filePath);
    
    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'File not found'], 404);
    }
    
    return response()->download($fullPath);
});