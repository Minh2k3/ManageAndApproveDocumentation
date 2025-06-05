<?php
// app/Http/Controllers/CertificateController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CertificateService;
use App\Services\CertificateRenewalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    protected $certificateService;
    protected $renewalService;
    
    public function __construct(
        CertificateService $certificateService,
        CertificateRenewalService $renewalService
    ) {
        $this->certificateService = $certificateService;
        $this->renewalService = $renewalService;
    }
    
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $certificates = DigitalCertificate::where('user_id', $user->id)
                                         ->with('renewals')
                                         ->orderBy('created_at', 'desc')
                                         ->get();
        
        return response()->json([
            'success' => true,
            'data' => $certificates->map(function ($cert) {
                return [
                    'id' => $cert->id,
                    'serial_number' => $cert->serial_number,
                    'subject' => $cert->subject,
                    'valid_from' => $cert->valid_from->format('Y-m-d'),
                    'valid_to' => $cert->valid_to->format('Y-m-d'),
                    'renewal_status' => $cert->renewal_status,
                    'is_valid' => $cert->isValid(),
                    'days_until_expiry' => $cert->getDaysUntilExpiry(),
                    'can_renew' => $cert->isExpiringSoon(180) // 6 months
                ];
            })
        ]);
    }
    
    public function show(int $id): JsonResponse
    {
        try {
            $certificateInfo = $this->certificateService->getCertificateInfo($id);
            
            // Check if user has permission to view this certificate
            if ($certificateInfo['owner']['email'] !== auth()->user()->email && 
                !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this certificate'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'data' => $certificateInfo
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
    
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'department_name' => 'sometimes|string|max:255'
        ]);
        
        try {
            $user = auth()->user();
            
            // Check if user already has a valid certificate
            $existingCert = $this->certificateService->getValidCertificate($user->id);
            if ($existingCert) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already has a valid certificate'
                ], 400);
            }
            
            $departmentInfo = null;
            if ($request->has('department_name')) {
                $departmentInfo = ['name' => $request->department_name];
            }
            
            $certificate = $this->certificateService->generateUserCertificate($user, $departmentInfo);
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate generated successfully',
                'data' => [
                    'certificate_id' => $certificate->id,
                    'serial_number' => $certificate->serial_number,
                    'valid_to' => $certificate->valid_to->format('Y-m-d')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function requestRenewal(Request $request): JsonResponse
    {
        $request->validate([
            'certificate_id' => 'required|exists:digital_certificates,id',
            'renewal_type' => 'required|in:renewal,reissue',
            'reason' => 'sometimes|string|max:500'
        ]);
        
        try {
            $renewal = $this->renewalService->requestRenewal(
                $request->certificate_id,
                auth()->id(),
                $request->renewal_type,
                $request->reason
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu gia hạn đã được gửi. Vui lòng chờ admin phê duyệt.',
                'data' => [
                    'renewal_id' => $renewal->id,
                    'status' => $renewal->status,
                    'renewal_type' => $renewal->renewal_type
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function renewalHistory(): JsonResponse
    {
        $renewals = $this->renewalService->getUserRenewals(auth()->id());
        
        return response()->json([
            'success' => true,
            'data' => $renewals->map(function ($renewal) {
                return [
                    'id' => $renewal->id,
                    'renewal_type' => $renewal->renewal_type,
                    'status' => $renewal->status,
                    'reason' => $renewal->reason,
                    'old_certificate_serial' => $renewal->oldCertificate->serial_number,
                    'new_certificate_serial' => $renewal->newCertificate?->serial_number,
                    'approved_by' => $renewal->approver?->name,
                    'created_at' => $renewal->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    }
    
    public function revoke(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        try {
            // Only admin or certificate owner can revoke
            $certificate = DigitalCertificate::findOrFail($id);
            
            if ($certificate->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to revoke this certificate'
                ], 403);
            }
            
            $this->certificateService->revokeCertificate($id, $request->reason);
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate has been revoked successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}