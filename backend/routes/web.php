<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\HandlePasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CreatePDFController;
use Illuminate\Support\Facades\Response;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });

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

// Route::get('/api/verify-email/{id}/{token}', [RegisterController::class, 'verifyEmail'])
//     ->name('verification.verify');

// Route::post('/forgot-password', [HandlePasswordController::class, 'forgotPassword'])
//     ->middleware('throttle:3,60')
//     ->name('forgot-password');

Route::post('/reset-password', [HandlePasswordController::class, 'resetPassword'])
    ->name('reset-password');
Route::post('/verify-reset-token', [HandlePasswordController::class, 'verifyResetToken'])
    ->name('verify-reset-token');

Route::get('/direct-pusher', function () {
    $options = [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true
    ];
    $pusher = new \Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );

    $data['message'] = 'Thử nghiệm trực tiếp từ Pusher API';
    $pusher->trigger('user.16', 'new-notification', $data);

    return "Đã gửi sự kiện trực tiếp qua Pusher API. Kiểm tra Debug Console.";
});  



// Route để serve documents với CORS headers
// Route đơn giản để serve PDF
// routes/web.php
Route::prefix('documents')->group(function () {
    // Route::get('{filename}/view', [DocumentController::class, 'viewPdf'])->name('documents.view');
    // Route::get('{filename}/download', [DocumentController::class, 'downloadPdf'])->name('documents.download');
    Route::options('{filename}/view', [DocumentController::class, 'handlePreflight']);
    Route::options('{filename}/download', [DocumentController::class, 'handlePreflight']);
});



Route::get('download-pdf', [CreatePDFController::class, 'createPDF'])
    ->name('pdf.downloadPDF');

Route::get('/create-certificate/{id}', [CreatePDFController::class, 'createPDF'])
    ->name('pdf.createPDF');

Route::get('/drive/{file_name}', function ($file_name) {
        try {
            // Lấy file từ Google Drive để kiểm tra tồn tại
            $data = Gdrive::get($file_name);
            if (!$data || !$data->file) {
                return response()->json(['error' => 'File not found'], 404);
            }

            // Khởi tạo Google Client
            $client = new Google_Client();
            $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
            $client->setAccessToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
            $client->addScope(Google_Service_Drive::DRIVE);
            $service = new Google_Service_Drive($client);

            // Tìm file trên Google Drive
            $files = $service->files->listFiles([
                'q' => "name='" . basename($file_name) . "' and '" . env('GOOGLE_DRIVE_FOLDER_ID') . "' in parents",
                'fields' => 'files(id, webViewLink)',
            ]);

            if (empty($files->getFiles())) {
                return response()->json(['error' => 'File not found in specified folder'], 404);
            }

            $file = $files->getFiles()[0];
            $fileId = $file->getId();


            // Cập nhật quyền để file công khai
            $permission = new \Google_Service_Drive_Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $service->permissions->create($fileId, $permission);
            
            // Lấy webViewLink
            $webViewLink = $file->getWebViewLink();

            return response()->json(['url' => $webViewLink], 200);
        } catch (\Exception $e) {
            Log::error("Error retrieving file URL: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve file URL'], 500);
        }
})->name('drive.getFile');

Route::fallback(function () {
    return response()->file(public_path('spa/index.html'));
});
// require __DIR__.'/auth.php';
