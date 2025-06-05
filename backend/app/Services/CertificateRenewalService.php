<?php
// app/Services/CertificateRenewalService.php
namespace App\Services;

use App\Models\DigitalCertificate;
use App\Models\CertificateRenewal;
use App\Models\Notification;
use App\Models\User;
use phpseclib3\File\X509;
use phpseclib3\Crypt\RSA;
use Illuminate\Support\Facades\DB;

class CertificateRenewalService
{
    protected $certificateService;
    
    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }
    
    public function checkExpiringCertificates(): array
    {
        $results = [
            'notifications_sent' => 0,
            'marked_expiring' => 0,
            'marked_expired' => 0
        ];
        
        // Find certificates expiring in 30 days
        $expiringCerts = DigitalCertificate::where('valid_to', '<=', now()->addDays(30))
                                          ->where('valid_to', '>', now())
                                          ->where('renewal_status', 'active')
                                          ->where('is_revoked', false)
                                          ->get();
        
        foreach ($expiringCerts as $cert) {
            $this->sendExpiryNotification($cert);
            $cert->update([
                'renewal_status' => 'expiring_soon',
                'expiry_notification_sent_at' => now()
            ]);
            $results['notifications_sent']++;
            $results['marked_expiring']++;
        }
        
        // Mark expired certificates
        $expiredCount = DigitalCertificate::where('valid_to', '<=', now())
                                         ->where('renewal_status', '!=', 'expired')
                                         ->update(['renewal_status' => 'expired']);
        
        $results['marked_expired'] = $expiredCount;
        
        return $results;
    }
    
    public function requestRenewal(int $certificateId, int $userId, string $renewalType = 'renewal', string $reason = null): CertificateRenewal
    {
        return DB::transaction(function () use ($certificateId, $userId, $renewalType, $reason) {
            $oldCert = DigitalCertificate::where('id', $certificateId)
                                       ->where('user_id', $userId)
                                       ->firstOrFail();
            
            // Validation checks
            if ($oldCert->valid_to > now()->addMonths(6)) {
                throw new \Exception('Certificate chưa đến thời hạn gia hạn (còn hơn 6 tháng)');
            }
            
            if ($oldCert->is_revoked) {
                throw new \Exception('Certificate đã bị thu hồi, không thể gia hạn');
            }
            
            // Check for pending renewal
            $pendingRenewal = CertificateRenewal::where('old_certificate_id', $oldCert->id)
                                              ->whereIn('status', ['pending', 'approved'])
                                              ->first();
            
            if ($pendingRenewal) {
                throw new \Exception('Đã có yêu cầu gia hạn đang chờ xử lý');
            }
            
            // Create renewal request
            $renewal = CertificateRenewal::create([
                'old_certificate_id' => $oldCert->id,
                'renewal_type' => $renewalType,
                'status' => 'pending',
                'reason' => $reason ?: "Certificate expiring on {$oldCert->valid_to->format('Y-m-d')}"
            ]);
            
            // Update certificate status
            $oldCert->update(['renewal_requested_at' => now()]);
            
            // Notify admins
            $this->notifyAdminForApproval($renewal);
            
            return $renewal;
        });
    }
    
    public function processRenewal(int $renewalId, int $adminId, bool $approved = true, string $adminNote = null): ?DigitalCertificate
    {
        return DB::transaction(function () use ($renewalId, $adminId, $approved, $adminNote) {
            $renewal = CertificateRenewal::with('oldCertificate.user')->findOrFail($renewalId);
            $oldCert = $renewal->oldCertificate;
            
            if ($renewal->status !== 'pending') {
                throw new \Exception('Renewal request already processed');
            }
            
            if (!$approved) {
                $renewal->update([
                    'status' => 'rejected',
                    'approved_by' => $adminId,
                    'reason' => $adminNote ?: $renewal->reason
                ]);
                
                $this->notifyUserRenewalRejected($renewal);
                return null;
            }
            
            try {
                // Generate new certificate
                if ($renewal->renewal_type === 'renewal') {
                    $newCert = $this->renewCertificate($oldCert);
                } else {
                    $newCert = $this->reissueCertificate($oldCert);
                }
                
                // Update renewal record
                $renewal->update([
                    'new_certificate_id' => $newCert->id,
                    'status' => 'completed',
                    'approved_by' => $adminId
                ]);
                
                // Update old certificate
                $oldCert->update([
                    'renewal_status' => 'renewed',
                    'is_revoked' => true
                ]);
                
                // Notify user
                $this->notifyUserRenewalCompleted($newCert);
                
                return $newCert;
                
            } catch (\Exception $e) {
                $renewal->update([
                    'status' => 'rejected',
                    'approved_by' => $adminId,
                    'reason' => 'System error: ' . $e->getMessage()
                ]);
                throw $e;
            }
        });
    }
    
    private function renewCertificate(DigitalCertificate $oldCert): DigitalCertificate
    {
        // Keep same key pair, extend validity
        $validityYears = config('certificate.validity_years', 2);
        $newSerialNumber = strtoupper(bin2hex(random_bytes(16)));
        
        // Generate new certificate with extended validity
        $newCertPem = $this->extendCertificateValidity($oldCert->certificate_pem);
        
        $newCert = DigitalCertificate::create([
            'user_id' => $oldCert->user_id,
            'certificate_pem' => $newCertPem,
            'private_key_encrypted' => $oldCert->private_key_encrypted, // Same key
            'serial_number' => $newSerialNumber,
            'subject' => $oldCert->subject,
            'issuer' => $oldCert->issuer,
            'valid_from' => now(),
            'valid_to' => now()->addYears($validityYears),
            'parent_certificate_id' => $oldCert->id,
            'renewal_status' => 'active',
            'thumbprint' => hash('sha256', base64_decode(
                preg_replace('/-----[^-]+-----|\s/', '', $newCertPem)
            ))
        ]);
        
        return $newCert;
    }
    
    private function reissueCertificate(DigitalCertificate $oldCert): DigitalCertificate
    {
        // Generate completely new certificate with new key pair
        $user = $oldCert->user;
        
        // Get department info if available
        $departmentInfo = null;
        if ($user->creators()->exists()) {
            $creator = $user->creators()->with('department')->first();
            $departmentInfo = ['name' => $creator->department->name ?? 'Student Organization'];
        }
        
        $newCert = $this->certificateService->generateUserCertificate($user, $departmentInfo);
        $newCert->update(['parent_certificate_id' => $oldCert->id]);
        
        return $newCert;
    }
    
    private function extendCertificateValidity(string $certPem): string
    {
        $x509 = new X509();
        $certData = $x509->loadX509($certPem);
        
        // Set new validity period
        $validityYears = config('certificate.validity_years', 2);
        $x509->setStartDate('-1 day');
        $x509->setEndDate("+{$validityYears} years");
        
        // Generate new serial number
        $x509->setSerialNumber(strtoupper(bin2hex(random_bytes(16))), 16);
        
        // Re-sign with CA private key
        $caPrivateKey = RSA::loadPrivateKey(file_get_contents(storage_path('certificates/ca-key.pem')));
        $newCert = $x509->sign($x509, $caPrivateKey, 'sha256WithRSAEncryption');
        
        return $x509->saveX509($newCert);
    }
    
    private function sendExpiryNotification(DigitalCertificate $certificate): void
    {
        $user = $certificate->user;
        $daysLeft = now()->diffInDays($certificate->valid_to);
        
        Notification::create([
            'user_id' => $user->id,
            'notification_category_id' => 1, // Certificate category
            'title' => 'Certificate sắp hết hạn',
            'content' => "Certificate của bạn (Serial: {$certificate->serial_number}) sẽ hết hạn sau {$daysLeft} ngày. Vui lòng gia hạn để tiếp tục sử dụng hệ thống.",
            'is_read' => false
        ]);
    }
    
    private function notifyAdminForApproval(CertificateRenewal $renewal): void
    {
        // Get all admin users
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'notification_category_id' => 1,
                'title' => 'Yêu cầu gia hạn certificate mới',
                'content' => "User {$renewal->oldCertificate->user->name} đã yêu cầu {$renewal->renewal_type} certificate. Vui lòng xem xét và phê duyệt.",
                'is_read' => false
            ]);
        }
    }
    
    private function notifyUserRenewalCompleted(DigitalCertificate $newCert): void
    {
        Notification::create([
            'user_id' => $newCert->user_id,
            'notification_category_id' => 1,
            'title' => 'Certificate đã được gia hạn thành công',
            'content' => "Certificate mới của bạn đã được cấp (Serial: {$newCert->serial_number}). Có hiệu lực đến {$newCert->valid_to->format('d/m/Y')}.",
            'is_read' => false
        ]);
    }
    
    private function notifyUserRenewalRejected(CertificateRenewal $renewal): void
    {
        Notification::create([
            'user_id' => $renewal->oldCertificate->user_id,
            'notification_category_id' => 1,
            'title' => 'Yêu cầu gia hạn certificate bị từ chối',
            'content' => "Yêu cầu gia hạn certificate của bạn đã bị từ chối. Lý do: {$renewal->reason}",
            'is_read' => false
        ]);
    }
    
    public function getPendingRenewals(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return CertificateRenewal::with(['oldCertificate.user'])
                                ->where('status', 'pending')
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);
    }
    
    public function getUserRenewals(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return CertificateRenewal::with(['oldCertificate', 'newCertificate', 'approver'])
                                ->whereHas('oldCertificate', function ($query) use ($userId) {
                                    $query->where('user_id', $userId);
                                })
                                ->orderBy('created_at', 'desc')
                                ->get();
    }
}