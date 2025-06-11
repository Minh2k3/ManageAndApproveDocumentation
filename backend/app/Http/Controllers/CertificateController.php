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
    // public function createDocument(Request $request)
    // {
    //     $request->validate([
    //         'content' => 'required|string',
    //         'title' => 'required|string',
    //     ]);

    //     $document = Document::create([
    //         'content' => $request->input('content'),
    //         'title' => $request->input('title'),
    //     ]);

    //     return response()->json([
    //         'message' => 'Tài liệu đã được tạo',
    //         'document_id' => $document->id,
    //     ]);
    // }

    // Issue the certificate to user by user_id
    public function issueCertificate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $ca = CaCertificate::firstOrFail();

        // Tạo khóa riêng và công khai
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

        // Lưu vào database
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
            'message' => 'Chứng chỉ đã được cấp',
            'certificate_id' => $certificate->id,
        ]);
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
        $documentFlowStepId = $request->input('document_flow_step_id');
        $documentVersionId = $request->input('document_version_id');
        $document = Document::findOrFail($documentId);

        $certificate = Certificate::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        $privateKey = RSA::loadPrivateKey(decrypt($certificate->private_key));
        $signature = $privateKey->withHash('sha256')->sign($document->content);

        $signatureRecord = DocumentSignature::create([
            'document_flow_step_id' => $documentFlowStepId,
            'document_version_id' => $documentVersionId,
            'certificate_id' => $certificate->id,
            'signature' => base64_encode($signature),
            'signed_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Tài liệu đã được ký',
            'signature_id' => $signatureRecord->id,
            'document_id' => $documentId,
            'signed_at' => $signatureRecord->signed_at,
        ]);
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
        ->orderBy('updated_at', 'desc')
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