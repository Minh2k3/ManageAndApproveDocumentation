<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Certificate;
use App\Models\CaCertificate;
use App\Models\Document;
use App\Models\DocumentSignature;
use App\Models\DocumentPermission;
use phpseclib3\Crypt\RSA;
use phpseclib3\File\X509;
use Carbon\Carbon;
use App\Http\Resources\CertificateResource;

class CertificateController extends Controller
{
    // Issue the certificate to user by user_id
    public function issueCertificate(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            // Find user and CA certificate
            $user = User::findOrFail($request->user_id);
            $ca = CaCertificate::firstOrFail();

            // Generate private and public keys
            $privateKey = RSA::createKey(2048);
            $publicKey = $privateKey->getPublicKey();

            // Create and configure X509 certificate
            $x509 = new X509();
            $x509->setPrivateKey($privateKey);
            $x509->setPublicKey($publicKey);
            $x509->setDN([
                'id-at-commonName' => $user->name,
                'id-at-emailAddress' => $user->email,
                'id-at-organizationName' => config('certificate.organization', 'Dai hoc Thuy loi'),
                'id-at-countryName' => 'VN',
            ]);
            $x509->setStartDate('-1 day');
            $x509->setEndDate('+1 year');
            $x509->setSerialNumber($user->id, 10);

            // Load CA certificate and private key
            $caX509 = new X509();
            $caPrivate = RSA::loadPrivateKey(decrypt($ca->private_key));
            $caCert = $caX509->loadX509($ca->certificate);
            
            // Set CA as issuer and sign the certificate
            $caX509->setPrivateKey($caPrivate);
            $userCert = $x509->sign($caX509, $x509, 'sha256');
            $certPem = $x509->saveX509($userCert);            

            $old_certificate = Certificate::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            // if ($old_certificate && Carbon::parse($old_certificate->expires_at)->isFuture()) {
            //     // If user already has an active certificate that is not expired, return error
            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Người dùng đã có chứng chỉ hợp lệ',
            //         'certificate_id' => $old_certificate->id,
            //     ], 400);
            // } 

            if ($old_certificate) {
                $expiresAt = Carbon::createFromFormat('H:i:s d/m/Y', $old_certificate->expires_at);

                if ($expiresAt->isFuture() && $expiresAt->greaterThan(Carbon::now()->addMonth())) {
                    // Nếu chứng chỉ còn hạn và thời hạn > 1 tháng thì không cho tạo mới
                    return response()->json([
                        'success' => true,
                        'message' => 'Người dùng đã có chứng chỉ hợp lệ còn hạn trên 1 tháng',
                        'certificate_id' => $old_certificate->id,
                    ], 200);
                } else {
                    $old_certificate->update(['status' => 'expired']);
                }
            }

            // Save certificate to database
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'public_key' => $certPem,
                'private_key' => encrypt($privateKey->toString('PKCS8')),
                'certificate' => $certPem,
                'issued_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addYear(),
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chứng chỉ đã được cấp thành công',
                'certificate_id' => $certificate->id,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng hoặc CA certificate',
                'error' => $e->getMessage(),
            ], 404);

        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể giải mã private key của CA',
                'error' => 'CA certificate may be corrupted',
            ], 500);

        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Certificate issuance failed', [
                'user_id' => $request->user_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cấp chứng chỉ',
                'error' => 'Internal server error',
            ], 500);
        }
    }

    // 
    public function renewCertificate(Request $request, $certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        $user = User::findOrFail($certificate->user_id);
        $ca = CaCertificate::firstOrFail();

        // Tạo khóa mới
        $privateKey = RSA::createKey(2048);
        $publicKey = $privateKey->getPublicKey();

        // Tạo CSR
        $x509 = new X509();
        $x509->setPrivateKey($privateKey);
        $x509->setPublicKey($publicKey);
        $x509->setDN([
            'id-at-commonName' => $user->name,
            'id-at-emailAddress' => $user->email,
            'id-at-organizationName' => config('certificate.organization', 'Dai hoc Thuy loi'),
            'id-at-countryName' => 'VN',
        ]);
        $x509->setStartDate('-1 day');
        $x509->setEndDate('+1 year');
        $x509->setSerialNumber($user->id, 10);

        // Ký chứng chỉ
        $caX509 = new X509();
        $caPrivate = RSA::loadPrivateKey(decrypt($ca->private_key));
        $caCert = $caX509->loadX509($ca->certificate);
        $userCert = $x509->sign($caX509, $caPrivate, ['digest_alg' => 'sha256']);
        $certPem = $x509->saveX509($userCert);

        // Cập nhật chứng chỉ
        $certificate->update([
            'public_key' => $certPem,
            'private_key' => encrypt($privateKey->toString('PKCS8')),
            'certificate' => $certPem,
            'issued_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(),
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Chứng chỉ đã được gia hạn',
            'certificate_id' => $certificate->id,
        ]);
    }

    public function revokeCertificate($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        $certificate->update(['status' => 'revoked']);
        return response()->json(['message' => 'Chứng chỉ đã được thu hồi']);
    }

    public function signDocument(Request $request)
    {
        try {
            $documentFlowStepId = $request->input('step_id');
            $documentVersionId = $request->input('version_id');
            $document = Document::findOrFail($request->document_id);
            \Log::info('Signing document', [
                'document_flow_step_id' => $documentFlowStepId,
                'document_version_id' => $documentVersionId,
                'document_id' => $document->id,
            ]);

            $user = auth()->user();

            $certificate = Certificate::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('expires_at', '>', Carbon::now())
                ->firstOrFail();
            \Log::info('Found certificate', [
                'certificate_id' => $certificate->id,
            ]);
            // Ký theo nội dung tài liệu
            // $filePath = storage_path('app/' . $document->file_path); // Điều chỉnh đường dẫn nếu cần
            //     if (!file_exists($filePath)) {
            //         return response()->json([
            //             'message' => 'File tài liệu không tồn tại',
            //         ], 400);
            //     }

            // $fileContent = file_get_contents($filePath);
            // if ($fileContent === false) {
            //     return response()->json([
            //         'message' => 'Không thể đọc nội dung file tài liệu',
            //     ], 400);
            // }

            $privateKey = RSA::loadPrivateKey(decrypt($certificate->private_key));
            $signature = $privateKey->withHash('sha256')->sign($document->file_path);

            $signatureRecord = DocumentSignature::create([
                'document_flow_step_id' => $documentFlowStepId,
                'document_version_id' => $documentVersionId,
                'certificate_id' => $certificate->id,
                'signature' => base64_encode($signature),
                'signed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            \Log::info('Document signed', [
                'signature_id' => $signatureRecord->id,
            ]);

            return response()->json([
                'message' => 'Tài liệu đã được ký',
                'signature_id' => $signatureRecord->id,
                'document_id' => $document->id,
                'signed_at' => $signatureRecord->signed_at,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error signing document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Có lỗi xảy ra khi ký tài liệu',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyDocumentSignatures(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
        ]);

        $user = auth()->user();
        $documentId = $request->input('document_id');
        $document = Document::findOrFail($documentId);
        $documentVersionId = $document->latest_version_id;

        // Kiểm tra quyền
        // $isSigner = DocumentSignature::where('document_version_id', $documentVersionId)
        //     ->where('user_id', $user->id)
        //     ->exists();
        // $hasPermission = DocumentPermission::where('document_id', $documentId)
        //     ->where('user_id', $user->id)
        //     ->where('permission', 'verify')
        //     ->exists();

        // if ($user->role !== 'admin' && !$isSigner && !$hasPermission) {
        //     return response()->json([
        //         'message' => 'Bạn không có quyền xác minh chữ ký trên tài liệu này',
        //     ], 403);
        // }

        $signatures = DocumentSignature::where('document_version_id', $documentVersionId)->get();

        if ($signatures->isEmpty()) {
            return response()->json([
                'message' => 'Tài liệu chưa có chữ ký nào',
                'is_valid' => false,
            ], 400);
        }

        $results = [];
        $allValid = true;
        $ca = CaCertificate::firstOrFail();

        foreach ($signatures as $signature) {
            $certificate = Certificate::findOrFail($signature->certificate_id);

            if ($certificate->status !== 'active' || Carbon::parse($certificate->expires_at)->isPast()) {
                $results[] = [
                    'user_id' => $signature->user_id,
                    'is_valid' => false,
                    'message' => 'Chứng chỉ không hợp lệ hoặc đã hết hạn',
                ];
                $allValid = false;
                continue;
            }

            $x509 = new X509();
            $userCert = $x509->loadX509($certificate->certificate);
            $publicKey = $x509->getPublicKey();

            $signatureData = base64_decode($signature->signature);
            $isValid = $publicKey->withHash('sha256')->verify($document->content, $signatureData);

            $caX509 = new X509();
            $caCert = $caX509->loadX509($ca->certificate);
            $isCertValid = $x509->validateSignature($caCert);

            $results[] = [
                'user_id' => $signature->user_id,
                'is_valid' => $isValid && $isCertValid,
                'message' => $isValid ? ($isCertValid ? 'Chữ ký hợp lệ' : 'Chứng chỉ không được ký bởi CA') : 'Chữ ký không hợp lệ',
            ];

            if (!$isValid || !$isCertValid) {
                $allValid = false;
            }
        }

        if (Carbon::parse($ca->expires_at)->isPast()) {
            return response()->json([
                'message' => 'Chứng chỉ CA đã hết hạn',
                'is_valid' => false,
                'signatures' => $results,
            ], 400);
        }

        return response()->json([
            'message' => $allValid ? 'Tất cả chữ ký hợp lệ' : 'Một hoặc nhiều chữ ký không hợp lệ',
            'is_valid' => $allValid,
            'document_id' => $documentId,
            'signatures' => $results,
            'verified_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function getAllCertificates(Request $request)
    {
        $certificates = Certificate::with([
            'user.role', // Load role để biết user thuộc loại nào
            'user.creator.rollAtDepartment', // Load rollAtDepartment cho creator
            'user.creator.department', // Load department cho creator
            'user.approver.rollAtDepartment', // Load rollAtDepartment cho approver
            'user.approver.department', // Load department cho approver
        ])
        ->select(
            'id',
            'user_id',
            'public_key',
            'private_key',
            'certificate',
            'issued_at',
            'expires_at',
            'status',
            'required_renewal',
            'used_count',
            'created_at',
            'updated_at'
        )
        ->orderByRaw("CASE 
            WHEN status = 'active' THEN 1 
            WHEN status = 'revoked' THEN 2 
            WHEN status = 'expired' THEN 3 
            ELSE 4 
        END")
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'certificates' => CertificateResource::collection($certificates),
        ])->setStatusCode(200, 'Certificates retrieved successfully.');
    }

    public function getCertificateById($certificateId)
    {
        $certificate = Certificate::with([
            'user.role',
            'user.creator.rollAtDepartment',
            'user.creator.department',
            'user.approver.rollAtDepartment',
            'user.approver.department',
        ])
        ->findOrFail($certificateId);

        return response()->json([
            'certificate' => new CertificateResource($certificate),
        ])->setStatusCode(200, 'Certificate retrieved successfully.');
    }

    public function getCertificate($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        return response()->json([
            'certificate' => $certificate,
        ]);
    }
}