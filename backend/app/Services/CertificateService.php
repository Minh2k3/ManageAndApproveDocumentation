<?php
// app/Services/CertificateService.php
namespace App\Services;

use phpseclib3\Crypt\RSA;
use phpseclib3\File\X509;
use App\Models\DigitalCertificate;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    private $caCert = null;
    private $caPrivateKey = null;
    
    // Constructor to initialize CA certificate and private key
    public function __construct()
    {
        $this->initializeCA();
    }
    
    /**
     * Initialize the CA certificate and private key: Chứng chỉ của cơ quan cấp phát.
     * If they do not exist, generate them.
     */
    private function initializeCA(): void
    {
        $caPath = storage_path('certificates/ca.pem');
        $caKeyPath = storage_path('certificates/ca-key.pem');
        
        if (!file_exists($caPath) || !file_exists($caKeyPath)) {
            $this->generateCACertificate();
        }
        
        $this->caCert = file_get_contents($caPath);
        $this->caPrivateKey = file_get_contents($caKeyPath);
    }
    
    /**
     * Generate a digital certificate for a user.
     *
     * @param User $user
     * @param array|null $departmentInfo
     * @return DigitalCertificate
     * @throws \Exception
     */
    public function generateUserCertificate(User $user, array $departmentInfo = null): DigitalCertificate
    {
        try {
            // 1. Generate RSA key pair
            $private = RSA::createKey(config('certificate.key_size', 2048));
            $public = $private->getPublicKey();
            
            // 2. Create X.509 certificate
            $x509 = new X509();
            
            // 3. Set subject information
            $subject = [
                'CN' => $user->name,
                'emailAddress' => $user->email,
                'O' => config('certificate.organization', 'Dai hoc Thuy loi'),
                'OU' => $departmentInfo['name'] ?? 'Student Organization',
                'C' => 'VN',
                'ST' => 'Hanoi',
                'L' => 'Hanoi'
            ];
            
            $x509->setDN($subject);
            $x509->setPublicKey($public);
            
            // 4. Set certificate extensions
            $x509->setExtension('id-ce-basicConstraints', [
                'cA' => false,
                'pathLenConstraint' => null
            ]);
            
            $x509->setExtension('id-ce-keyUsage', [
                'digitalSignature', 'nonRepudiation'
            ]);
            
            $x509->setExtension('id-ce-extKeyUsage', [
                'id-kp-emailProtection'
            ]);
            
            // 5. Set validity period
            $validityYears = config('certificate.validity_years', 2);
            $x509->setStartDate('-1 day');
            $x509->setEndDate("+{$validityYears} years");
            
            // 6. Generate serial number
            $serialNumber = strtoupper(bin2hex(random_bytes(16)));
            $x509->setSerialNumber($serialNumber, 16);
            
            // 7. Set issuer and sign with CA
            $x509->setIssuerDN([
                'CN' => config('certificate.ca_name', 'TLU Document Management CA'),
                'O' => config('certificate.organization', 'Dai hoc Thuy loi'),
                'C' => 'VN'
            ]);
            
            $caPrivateKey = RSA::loadPrivateKey($this->caPrivateKey);
            $cert = $x509->sign($x509, $caPrivateKey, 'sha256WithRSAEncryption');
            
            // 8. Calculate thumbprint
            $certPem = $x509->saveX509($cert);
            $thumbprint = hash('sha256', base64_decode(
                preg_replace('/-----[^-]+-----|\s/', '', $certPem)
            ));
            
            // 9. Save to database
            $digitalCert = DigitalCertificate::create([
                'user_id' => $user->id,
                'certificate_pem' => $certPem,
                'private_key_encrypted' => Crypt::encrypt($private->toString('PKCS8')),
                'serial_number' => $serialNumber,
                'subject' => $this->buildSubjectString($subject),
                'valid_from' => now()->subDay(),
                'valid_to' => now()->addYears($validityYears),
                'thumbprint' => $thumbprint,
                'renewal_status' => 'active'
            ]);
            
            return $digitalCert;
            
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate certificate: ' . $e->getMessage());
        }
    }
    
    public function getCertificateInfo(int $certificateId): array
    {
        $cert = DigitalCertificate::with('user')->findOrFail($certificateId);
        
        $x509 = new X509();
        $certData = $x509->loadX509($cert->certificate_pem);
        
        return [
            'id' => $cert->id,
            'serial_number' => $cert->serial_number,
            'subject' => $x509->getDN(true),
            'issuer' => $x509->getIssuerDN(true),
            'valid_from' => $cert->valid_from->format('Y-m-d H:i:s'),
            'valid_to' => $cert->valid_to->format('Y-m-d H:i:s'),
            'thumbprint' => $cert->thumbprint,
            'is_valid' => $cert->isValid(),
            'renewal_status' => $cert->renewal_status,
            'days_until_expiry' => $cert->getDaysUntilExpiry(),
            'owner' => [
                'name' => $cert->user->name,
                'email' => $cert->user->email
            ],
            'public_key_info' => [
                'algorithm' => 'RSA',
                'key_size' => '2048 bits'
            ]
        ];
    }
    
    public function revokeCertificate(int $certificateId, string $reason = 'User request'): bool
    {
        $cert = DigitalCertificate::findOrFail($certificateId);
        
        return $cert->update([
            'is_revoked' => true,
            'renewal_status' => 'expired'
        ]);
    }
    
    public function getValidCertificate(int $userId): ?DigitalCertificate
    {
        return DigitalCertificate::where('user_id', $userId)
                                ->where('is_revoked', false)
                                ->where('valid_to', '>', now())
                                ->orderBy('created_at', 'desc')
                                ->first();
    }
    
    private function generateCACertificate(): void
    {
        try {
            echo "Step 1: Creating RSA key...\n";
            $caPrivate = RSA::createKey(4096);
            echo "RSA key created successfully\n";

            // Tạo đối tượng X509 để xác định thông tin subject/issuer
            $issuer = new X509();
            $issuer->setPrivateKey($caPrivate);

            $subject = new X509();
            $subject->setPublicKey($caPrivate->getPublicKey());

            // Thiết lập Distinguished Name
            $dn = [
                'CN' => config('certificate.ca_name', 'TLU Document Management CA'),
                'O' => config('certificate.organization', 'Dai hoc Thuy loi'),
                'C' => 'VN'
            ];
            $issuer->setDN($dn);
            $subject->setDN($dn);

            // Thời hạn hiệu lực
            $issuer->setStartDate('-1 day');
            $issuer->setEndDate('+10 years');

            // Serial number
            $issuer->setSerialNumber('1', 10);

            // Extensions cho CA
            $issuer->setExtension('id-ce-basicConstraints', [
                'cA' => true,
                'pathLenConstraint' => 2
            ]);
            $issuer->setExtension('id-ce-keyUsage', [
                'keyCertSign', 'cRLSign'
            ]);

            // Ký self-signed (issuer = subject)
            echo "Step 2: Signing CA certificate (self-signed)...\n";
            $caCert = $issuer->sign($issuer, $subject, ['digest_alg' => 'sha256']);
            if (!$caCert) {
                throw new \Exception('Failed to sign CA certificate');
            }

            // Chuyển sang PEM
            $caCertPem = $issuer->saveX509($caCert);

            // Đảm bảo thư mục tồn tại
            $certDir = storage_path('certificates');
            if (!file_exists($certDir)) {
                mkdir($certDir, 0755, true);
            }

            // Lưu file
            $caFile = $certDir . '/ca.pem';
            $keyFile = $certDir . '/ca-key.pem';

            $certWritten = file_put_contents($caFile, $caCertPem);
            $keyWritten = file_put_contents($keyFile, $caPrivate->toString('PKCS8'));

            chmod($keyFile, 0600);
            chmod($caFile, 0644);

            echo "CA Certificate file: $caFile, size: " . filesize($caFile) . " bytes\n";
            echo "CA Key file: $keyFile, size: " . filesize($keyFile) . " bytes\n";
            echo "✅ CA Certificate generation completed!\n";
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    private function buildSubjectString(array $subject): string
    {
        $parts = [];
        foreach ($subject as $key => $value) {
            $parts[] = "{$key}={$value}";
        }
        return implode(', ', $parts);
    }
}