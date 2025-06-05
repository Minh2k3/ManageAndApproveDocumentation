<?php
// app/Http/Controllers/Admin/CertificateController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CertificateRenewalService;
use App\Models\DigitalCertificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    protected $renewalService;
    
    public function __construct(CertificateRenewalService $renewalService)
    {
        $this->renewalService = $renewalService;
        $this->middleware('role:admin');
    }
    
    public function pendingRenewals(): JsonResponse
    {
        $renewals = $this->renewalService->getPendingRenewals();
        
        return response()->json([
            'success' => true,
            'data' => $renewals->items(),
            'pagination' => [
                'current_page' => $renewals->currentPage(),
                'last_page' => $renewals->lastPage(),
                'per_page' => $renewals->perPage(),
                'total' => $renewals->total()
            ]
        ]);
    }
    
    public function approveRenewal(Request $request, int $renewalId): JsonResponse
    {
        $request->validate([
            'approved' => 'required|boolean',
            'note' => 'sometimes|string|max:500'
        ]);
        
        try {
            $newCert = $this->renewalService->processRenewal(
                $renewalId,
                auth()->id(),
                $request->approved,
                $request->note
            );
            
            return response()->json([
                'success' => true,
                'message' => $request->approved 
                    ? 'Certificate renewal approved successfully' 
                    : 'Certificate renewal rejected',
                'data' => $newCert ? [
                    'new_certificate_id' => $newCert->id,
                    'new_serial_number' => $newCert->serial_number
                ] : null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function allCertificates(Request $request): JsonResponse
    {
        $query = DigitalCertificate::with('user');
        
        // Filters
        if ($request->has('status')) {
            $query->where('renewal_status', $request->status);
        }
        
        if ($request->has('is_revoked')) {
            $query->where('is_revoked', $request->boolean('is_revoked'));
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $certificates->items(),
            'pagination' => [
                'current_page' => $certificates->currentPage(),
                'last_page' => $certificates->lastPage(),
                'per_page' => $certificates->perPage(),
                'total' => $certificates->total()
            ]
        ]);
    }
    
    public function revokeCertificate(Request $request, int $certificateId): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        try {
            $certificate = DigitalCertificate::findOrFail($certificateId);
            
            $certificate->update([
                'is_revoked' => true,
                'renewal_status' => 'expired'
            ]);
            
            // Log the revocation
            \Log::info("Certificate revoked by admin", [
                'certificate_id' => $certificateId,
                'serial_number' => $certificate->serial_number,
                'admin_id' => auth()->id(),
                'reason' => $request->reason
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate revoked successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function certificateStatistics(): JsonResponse
    {
        $stats = [
            'total_certificates' => DigitalCertificate::count(),
            'active_certificates' => DigitalCertificate::where('is_revoked', false)
                                                    ->where('valid_to', '>', now())
                                                    ->count(),
            'expiring_soon' => DigitalCertificate::where('renewal_status', 'expiring_soon')->count(),
            'expired_certificates' => DigitalCertificate::where('renewal_status', 'expired')->count(),
            'revoked_certificates' => DigitalCertificate::where('is_revoked', true)->count(),
            'pending_renewals' => CertificateRenewal::where('status', 'pending')->count()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}