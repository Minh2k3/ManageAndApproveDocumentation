<?php

namespace App\Services;

use phpseclib3\Crypt\RSA;
use phpseclib3\File\X509;
use Carbon\Carbon;

class CertificateService
{
    public function generateCACertificate()
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
            $caKeyPem = $caPrivate->toString('PKCS8');

            // Đảm bảo thư mục tồn tại
            $certDir = storage_path('certificates');
            if (!file_exists($certDir)) {
                mkdir($certDir, 0755, true);
            }

            // Lưu file
            $caFile = $certDir . '/ca.pem';
            $keyFile = $certDir . '/ca-key.pem';

            $certWritten = file_put_contents($caFile, $caCertPem);
            $keyWritten = file_put_contents($keyFile, $caKeyPem);

            chmod($keyFile, 0600);
            chmod($caFile, 0644);

            echo "CA Certificate file: $caFile, size: " . filesize($caFile) . " bytes\n";
            echo "CA Key file: $keyFile, size: " . filesize($keyFile) . " bytes\n";
            echo "✅ CA Certificate generation completed!\n";

            return ['certificate' => $caCertPem, 'private_key' => $caKeyPem];
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}