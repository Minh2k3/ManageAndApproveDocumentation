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
use App\Http\Controllers\DocumentCertificateController;

// Other Controllers
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserAccessLogController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CaCertificateController;
use App\Http\Controllers\Api\PDFProxyController;
use App\Http\Controllers\RollAtDepartmentController;

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


Route::middleware('auth:sanctum')->get('/user', [CustomAuthenticatedSessionController::class, 'user'])
    ->name('user.user');

Route::post('/login', [CustomAuthenticatedSessionController::class, 'store'])
    ->name('login');

Route::post('/login-remember', [CustomAuthenticatedSessionController::class, 'loginWithRememberToken'])
    ->name('login.loginWithRememberToken');

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(100)->by($request->ip());
});

// User api
// User
Route::get('/users', [UserController::class, 'getUsers'])
    // ->middleware('auth:sanctum')
    ->name('users.getUsers');

Route::post('forgot-password', [HandlePasswordController::class, 'forgotPassword'])
    ->name('forgot-password');
    
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
    ->middleware('auth:sanctum')
    ->name('users.getApproverByUserId');

Route::get('/users/{user_id}/creator', [UserController::class, 'getCreatorByUserId'])
    ->middleware('auth:sanctum')
    ->name('users.getCreatorByUserId');

Route::post('/users/{user_id}/delete', [UserController::class, 'deleteUser'])
    ->middleware('auth:sanctum')
    ->name('users.delete');

Route::post('/users/{user_id}/change-password', [HandlePasswordController::class, 'changePassword'])
    ->middleware('auth:sanctum')
    ->name('users.changePassword');



// Document api
// Document
Route::get('/documents', [DocumentController::class, 'index'])
    ->middleware('auth:sanctum')
    ->middleware('role:1')
    ->name('documents.index');

Route::get('/documents/public', [DocumentController::class, 'getPublicApprovedDocuments'])
    ->middleware('auth:sanctum')
    ->name('documents.getPublicApprovedDocuments');

Route::get('/documents/{document_id}', [DocumentController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('documents.show');

Route::get('/creators/{id}/documents', [DocumentController::class, 'getDocumentsByCreator'])
    ->middleware('auth:sanctum')
    // ->middleware('role:1,2')
    ->name('documents.getDocumentsByCreator');

Route::get('/approvers/{id}/documents', [DocumentController::class, 'getDocumentsByApprover'])
    ->middleware('auth:sanctum')
    // ->middleware('role:1,2,3')
    ->name('documents.getDocumentsByApprover');

Route::get('/document/{id}/fm', [DocumentController::class, 'getDocumentOfMeById'])
    ->middleware('auth:sanctum')
    // ->middleware('role:2,3')
    ->name('documents.getDocumentOfMeById');

Route::get('/document/{id}/nm', [DocumentController::class, 'getDocumentNeedMeById'])
    ->middleware('auth:sanctum')
    // ->middleware('role:3')
    ->name('documents.getDocumentNeedMeById');
    
Route::post('/documents/draft', [DocumentController::class, 'storeDraftDocument'])
    ->middleware('auth:sanctum')
    // ->middleware('role:2,3')
    ->name('documents.storeDraftDocument');

Route::post('/documents/request', [DocumentController::class, 'storeRequestDocument'])
    ->middleware('auth:sanctum')
    // ->middleware('role:2,3')
    ->name('documents.storeRequestDocument');

Route::post('/documents/new-version/{id}', [DocumentController::class, 'storeNewVersionDocument'])
    ->middleware('auth:sanctum')
    // ->middleware('role:2,3')
    ->name('documents.storeNewVersionDocument');

Route::post('/documents/upload-file', [DocumentController::class, 'uploadFile'])
    ->middleware('auth:sanctum')
    // ->middleware('role:2,3')
    ->name('documents.uploadFile');

Route::prefix('documents')->group(function () {
    Route::get('{filename}/view', [DocumentController::class, 'viewPdf'])->name('documents.view');
    Route::get('{filename}/download', [DocumentController::class, 'downloadPdf'])->name('documents.download');
});

Route::get('/documents/{id}/comments', [DocumentCommentController::class, 'getCommentsByDocument']);
Route::post('/documents/{document_id}/comments', [DocumentCommentController::class, 'storeComment'])
    ->middleware('auth:sanctum')
    ->name('documents.storeComment');

Route::get('documents/{id}/versions', [DocumentController::class, 'getVersionsByDocumentId'])
    ->middleware('auth:sanctum')
    ->name('documents.getVersionsByDocumentId');

Route::post('documents/sign-document', [CertificateController::class, 'signDocument'])
    ->middleware('auth:sanctum')
    ->name('documents.signDocument');

// Document Flow 
Route::get('/document-flows', [DocumentFlowController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('document-flows.index');

Route::get('/document-flows/{documentFlow}', [DocumentFlowController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('document-flows.show');

Route::get('/document-flows/{id}/steps', [DocumentFlowController::class, 'getStepsByDocumentFlow'])
    ->middleware('auth:sanctum')
    ->name('document-flows.getStepsByDocumentFlow');

Route::post('/document-flows-template', [DocumentFlowController::class, 'createFlowTemplate'])
    ->middleware('auth:sanctum')
    ->name('document-flows.createFlowTemplate');


// Document Flow Step
Route::get('/document-flow-steps', [DocumentFlowStepController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('document-flow-steps.index');

Route::get('document-flow-steps/{documentFlow}', [DocumentFlowStepController::class, 'getStepsByDocumentFlowId'])
    ->name('document-flow-steps.getStepsByDocumentFlowId');

Route::post('/document-steps/{document_flow_step_id}/approve', [DocumentFlowStepController::class, 'approveStep'])
    ->middleware('auth:sanctum')
    ->name('document-flow-steps.approveStep');

Route::post('/document-steps/{document_flow_step_id}/reject', [DocumentFlowStepController::class, 'rejectStep'])
    ->middleware('auth:sanctum')
    ->name('document-flow-steps.rejectStep');

// Document Type
Route::get('/document-types', [DocumentTypeController::class, 'getActiveDocumentTypes'])
    ->middleware('auth:sanctum')
    ->name('document-types.index');

Route::get('/document-types/all', [DocumentTypeController::class, 'index'])    
    ->middleware('auth:sanctum')
    ->name('document-types.getAllDocumentTypes');

Route::post('/document-types', [DocumentTypeController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('document-types.store');

Route::post('/document-types/update/{id}', [DocumentTypeController::class, 'update'])
    ->middleware('auth:sanctum')
    ->name('document-types.update');    

Route::post('/document-types/delete/{id}', [DocumentTypeController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('document-types.destroy');

// Document Template
Route::get('/admin/document-templates', [DocumentTemplateController::class, 'getAllTemplates'])
    ->name('document-templates.getAllTemplates');

Route::get('/user/document-templates', [DocumentTemplateController::class, 'getAllTemplatesUser'])
    ->name('document-templates.getAllTemplatesUser');

Route::get('/document-templates/{user_id}/liked', [DocumentTemplateController::class, 'getLikedTemplatesByUserId'])
    ->middleware('auth:sanctum')
    ->name('document-templates.getLikedTemplatesByUserId');

Route::post('/document-templates/{id}/like', [DocumentTemplateController::class, 'likeTemplate'])
    ->middleware('auth:sanctum')
    ->name('document-templates.likeTemplate');

Route::post('/document-templates/{id}/unlike', [DocumentTemplateController::class, 'unlikeTemplate'])
    ->middleware('auth:sanctum')
    ->name('document-templates.unlikeTemplate');

Route::post('/document-templates', [DocumentTemplateController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('document-templates.store');

Route::post('/document-templates/upload-file', [DocumentTemplateController::class, 'uploadFile'])
    ->middleware('auth:sanctum')
    ->name('document-templates.uploadFile');

Route::get('/document-templates/{id}', [DocumentTemplateController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('document-templates.show');

Route::get('/document-templates/{id}/download', [DocumentTemplateController::class, 'downloadTemplate'])
    ->middleware('auth:sanctum')
    ->name('document-templates.downloadTemplate');    

Route::post('/document-templates/{id}/change-status', [DocumentTemplateController::class, 'changeStatus'])
    ->middleware('auth:sanctum')
    ->name('document-templates.changeStatus');

// Document Version
Route::get('/document-versions/{id}', [DocumentVersionController::class, 'show'])
    ->middleware('auth:sanctum')
    ->name('document-versions.show');

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

Route::post('/departments', [DepartmentController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('departments.store');

Route::post('departments/upload-image', [DepartmentController::class, 'uploadFile'])
    ->middleware('auth:sanctum')
    ->name('departments.uploadFile');

Route::get('/departments/{id}', [DepartmentController::class, 'getDepartmentUsers'])
    ->middleware('auth:sanctum')
    ->name('departments.getDepartmentUsers');

// Notification
Route::get('/notifications/{user_id}', [NotificationController::class, 'getAllNotificationsByUserId'])
    ->middleware('auth:sanctum')
    ->name('notifications.getAllNotificationsByUserId');

Route::post('/notifications/read/{notification_id}', [NotificationController::class, 'markAsRead'])
    ->middleware('auth:sanctum')
    ->name('notifications.markAsRead');

Route::post('/notifications/read-all/{user_id}', [NotificationController::class, 'markAllAsRead'])
    ->middleware('auth:sanctum')
    ->name('notifications.markAllAsRead');

Route::post('/notifications', [NotificationController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('notifications.store');

Route::post('/notifications/all-admins', [NotificationController::class, 'sendNotificationToAllAdmins'])
    ->middleware('auth:sanctum')
    ->name('notifications.sendNotificationToAllAdmins');

Route::post('/notifications/all-users', [NotificationController::class, 'sendNotificationToAllUsers'])
    ->middleware('auth:sanctum')
    ->name('notifications.sendNotificationToAllUsers');

// Statistics
Route::get('/access-logs', [UserAccessLogController::class, 'getAccessStats'])
    // ->middleware('auth:sanctum')
    ->name('access-logs.getAccessStats');

// Route::get('/pdf-proxy', [PDFProxyController::class, 'proxy']);
// Route::get('/pdf-list', [PDFProxyController::class, 'list']);
Route::get('/pdf-proxy', [PDFProxyController::class, 'proxy']);
Route::options('/pdf-proxy', [PDFProxyController::class, 'options']);
Route::get('/pdf-list', [PDFProxyController::class, 'list']);
Route::get('/pdf-info', [PDFProxyController::class, 'info']);

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

// Certificate
Route::get('/certificates', [CertificateController::class, 'getAllCertificates'])
    // ->middleware('auth:sanctum')
    ->name('certificates.getAllCertificates');

Route::get('/certificates/{id}', [CertificateController::class, 'getCertificateById'])
    // ->middleware('auth:sanctum')
    ->name('certificates.getCertificateById');

Route::post('/certificates/issue-certificate', [CertificateController::class, 'issueCertificate'])
    ->middleware('auth:sanctum')
    // ->middleware('role:1')
    ->name('certificates.issueCertificate');

Route::post('/certificates/renew-certificate', [CertificateController::class, 'renewCertificate'])
    ->middleware('auth:sanctum')
    ->name('certificates.renewCertificate');

Route::post('/certificates/revoke-certificate', [CertificateController::class, 'revokeCertificate'])
    ->middleware('auth:sanctum')
    ->name('certificates.revokeCertificate');

Route::post('/certificates/extend-certificate', [CertificateController::class, 'extendCertificate'])
    ->middleware('auth:sanctum')
    ->name('certificates.extendCertificate');

Route::get('/users/{user_id}/certificates', [CertificateController::class, 'getUserCertificates'])
    ->middleware('auth:sanctum')
    ->name('certificates.getUserCertificates');

// Roll at Department
Route::get('/roll-at-departments', [RollAtDepartmentController::class, 'index'])
    // ->middleware('auth:sanctum')
    ->name('roll-at-departments.index');

// Document Certificate
Route::get('/document-certificates/{code}', [DocumentCertificateController::class, 'index'])
    // ->middleware('auth:sanctum')
    ->name('document-certificates.index');

Route::get('/document-certificate-id/{code}', [DocumentCertificateController::class, 'findByDocumentId'])
    // ->middleware('auth:sanctum')
    ->name('document-certificates.findByDocumentId');

// routes/api.php
// Route::get('/pdf-proxy', function (Request $request) {
//     $file = $request->query('file');
    
//     // Xử lý đường dẫn file
//     if (filter_var($file, FILTER_VALIDATE_URL)) {
//         // Nếu là URL đầy đủ thì proxy
//         $content = file_get_contents($file);
//     } else {
//         // Nếu là đường dẫn local
//         $filePath = public_path($file);
        
//         if (!file_exists($filePath)) {
//             return response()->json(['error' => 'File not found'], 404);
//         }
        
//         $content = file_get_contents($filePath);
//     }
    
//     return response($content)
//         ->header('Content-Type', 'application/pdf')
//         ->header('Access-Control-Allow-Origin', '*')
//         ->header('Access-Control-Allow-Methods', 'GET')
//         ->header('Access-Control-Allow-Headers', 'Content-Type');
// });