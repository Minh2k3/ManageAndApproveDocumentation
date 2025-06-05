<?php
// app/Services/DigitalSignatureService.php
namespace App\Services;

use App\Models\DocumentSignature;
use App\Models\DocumentVersion;
use App\Models\DigitalCertificate;
use App\Models\DocumentFlowStep;
use App\Models\SignatureTimestamp;
use phpseclib3\File\X509;
use phpseclib3\Crypt\RSA;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class DigitalSignatureService
{
    protected $certificateService;
    
    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }
    
    /**
     * Sign a document version at a specific flow step.
     *
     * @param int $documentVersionId
     * @param int $documentFlowStepId
     * @param int $userId
     * @return DocumentSignature
     * @throws \Exception
     */
    public function signDocument(int $documentVersionId, int $documentFlowStepId, int $userId): DocumentSignature
    {
        return DB::transaction(function () use ($documentVersionId, $documentFlowStepId, $userId) {
            try {
                // 1. Validate inputs
                $documentVersion = DocumentVersion::findOrFail($documentVersionId);
                $documentFlowStep = DocumentFlowStep::findOrFail($documentFlowStepId);
                $certificate = $this->certificateService->getValidCertificate($userId);
                
                if (!$certificate) {
                    throw new \Exception('No valid certificate found for user. Please contact administrator.');
                }
                
                // 2. Check if already signed
                $existingSignature = DocumentSignature::where('document_flow_step_id', $documentFlowStepId)
                                                    ->where('document_version_id', $documentVersionId)
                                                    ->where('certificate_id', $certificate->id)
                                                    ->first();
                
                if ($existingSignature) {
                    throw new \Exception('Document already signed by this user for this step.');
                }
                
                // 3. Prepare document content
                $documentContent = $this->prepareDocumentContent($documentVersion);
                $documentHash = hash('sha256', $documentContent);
                
                // 4. Create PKCS#7 signature
                $pkcs7Signature = $this->createPKCS7Signature($documentContent, $certificate);
                
                // 5. Save signature
                $signature = DocumentSignature::create([
                    'document_flow_step_id' => $documentFlowStepId,
                    'document_version_id' => $documentVersionId,
                    'certificate_id' => $certificate->id,
                    'pkcs7_signature' => base64_encode(json_encode($pkcs7Signature)),
                    'document_hash' => $documentHash,
                    'signature_attributes' => $pkcs7Signature['contentInfo']['content']['signerInfos'][0]['signedAttributes'],
                    'signed_at' => now()
                ]);
                
                // 6. Add timestamp
                $this->addTimestamp($signature);
                
                // 7. Update document flow step status
                $documentFlowStep->update([
                    'status' => 'approved',
                    'decision' => 'approved',
                    'signed_at' => now()
                ]);
                
                return $signature;
                
            } catch (\Exception $e) {
                throw new \Exception('Signing failed: ' . $e->getMessage());
            }
        });
    }
    
    private function prepareDocumentContent(DocumentVersion $documentVersion): string
    {
        // Normalize document content for consistent hashing
        $content = [
            'id' => $documentVersion->id,
            'document_id' => $documentVersion->document_id,
            'version' => $documentVersion->version,
            'document_data' => $documentVersion->document_data,
            'created_at' => $documentVersion->created_at->toISOString()
        ];
        
        // Use consistent JSON encoding
        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_SORT_KEYS);
    }
    
    private function createPKCS7Signature(string $content, DigitalCertificate $certificate): array
    {
        // 1. Load certificate and private key
        $x509 = new X509();
        $certData = $x509->loadX509($certificate->certificate_pem);
        
        $privateKeyPem = Crypt::decrypt($certificate->private_key_encrypted);
        $privateKey = RSA::loadPrivateKey($privateKeyPem);
        
        // 2. Calculate content hash
        $contentHash = hash('sha256', $content, true);
        
        // 3. Prepare signed attributes (according to PKCS#7 standard)
        $signedAttributes = [
            'contentType' => '1.2.840.113549.1.7.1', // Data OID
            'messageDigest' => base64_encode($contentHash),
            'signingTime' => now()->toISOString(),
            'signingCertificate' => [
                'certHash' => $certificate->thumbprint,
                'issuerSerial' => [
                    'issuer' => $certificate->issuer,
                    'serialNumber' => $certificate->serial_number
                ]
            ]
        ];
        
        // 4. Create signature over signed attributes
        $signedAttributesData = $this->encodeSignedAttributes($signedAttributes);
        $signedAttributesHash = hash('sha256', $signedAttributesData, true);
        
        // 5. Sign with private key
        $signature = '';
        if (!openssl_sign($signedAttributesHash, $signature, $privateKey->toString('PKCS1'), OPENSSL_ALGO_SHA256)) {
            throw new \Exception('Failed to create cryptographic signature');
        }
        
        // 6. Build PKCS#7 structure
        $pkcs7 = [
            'contentInfo' => [
                'contentType' => '1.2.840.113549.1.7.2', // Signed Data OID
                'content' => [
                    'version' => 1,
                    'digestAlgorithms' => [
                        ['algorithm' => '2.16.840.1.101.3.4.2.1'] // SHA256 OID
                    ],
                    'contentInfo' => [
                        'contentType' => '1.2.840.113549.1.7.1',
                        'content' => base64_encode($content)
                    ],
                    'certificates' => [$certificate->certificate_pem],
                    'signerInfos' => [[
                        'version' => 1,
                        'sid' => [
                            'issuerAndSerialNumber' => [
                                'issuer' => $certificate->issuer,
                                'serialNumber' => $certificate->serial_number
                            ]
                        ],
                        'digestAlgorithm' => [
                            'algorithm' => '2.16.840.1.101.3.4.2.1' // SHA256
                        ],
                        'signedAttributes' => $signedAttributes,
                        'signatureAlgorithm' => [
                            'algorithm' => '1.2.840.113549.1.1.11' // SHA256withRSA
                        ],
                        'signature' => base64_encode($signature)
                    ]]
                ]
            ]
        ];
        
        return $pkcs7;
    }
    
    private function encodeSignedAttributes(array $attributes): string
    {
        // Simplified DER encoding for signed attributes
        // In production, should use proper ASN.1 DER encoding
        return json_encode($attributes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_SORT_KEYS);
    }
    
    private function addTimestamp(DocumentSignature $signature): SignatureTimestamp
    {
        // Create mock RFC 3161 timestamp token
        $timestampData = [
            'version' => 1,
            'policy' => '1.2.3.4.5', // Mock policy OID
            'messageImprint' => [
                'hashAlgorithm' => 'SHA256',
                'hashedMessage' => hash('sha256', $signature->pkcs7_signature)
            ],
            'serialNumber' => bin2hex(random_bytes(8)),
            'genTime' => now()->toISOString(),
            'accuracy' => ['seconds' => 1],
            'tsa' => [
                'directoryName' => 'CN=TLU TimeStamp Authority,O=Dai hoc Thuy loi,C=VN'
            ]
        ];
        
        $timestampToken = base64_encode(json_encode($timestampData));
        
        return SignatureTimestamp::create([
            'signature_id' => $signature->id,
            'timestamp_token' => $timestampToken,
            'hash_algorithm' => 'SHA256',
            'timestamp_time' => now()
        ]);
    }
    
    public function verifySignature(int $signatureId): array
    {
        $signature = DocumentSignature::with([
            'certificate.user', 
            'documentVersion', 
            'timestamps'
        ])->findOrFail($signatureId);
        
        try {
            // 1. Parse PKCS#7 signature
            $pkcs7Data = json_decode(base64_decode($signature->pkcs7_signature), true);
            
            if (!$pkcs7Data) {
                throw new \Exception('Invalid PKCS#7 signature data');
            }
            
            // 2. Verify certificate validity
            $certValid = $this->verifyCertificate($signature->certificate);
            
            // 3. Verify document integrity
            $documentContent = $this->prepareDocumentContent($signature->documentVersion);
            $currentHash = hash('sha256', $documentContent);
            $documentIntact = ($currentHash === $signature->document_hash);
            
            // 4. Verify signature
            $signatureValid = $this->verifyPKCS7Signature($pkcs7Data, $signature->certificate);
            
            // 5. Check timestamp
            $timestampValid = $this->verifyTimestamp($signature);
            
            $result = [
                'signature_id' => $signatureId,
                'overall_valid' => $certValid && $documentIntact && $signatureValid && $timestampValid,
                'details' => [
                    'certificate_valid' => $certValid,
                    'document_intact' => $documentIntact,
                    'signature_valid' => $signatureValid,
                    'timestamp_valid' => $timestampValid
                ],
                'signer_info' => [
                    'name' => $signature->certificate->user->name,
                    'email' => $signature->certificate->user->email,
                    'certificate_serial' => $signature->certificate->serial_number,
                    'signed_at' => $signature->signed_at->format('Y-m-d H:i:s'),
                    'certificate_thumbprint' => $signature->certificate->thumbprint
                ],
                'verification_performed_at' => now()->toISOString()
            ];
            
            // Update verification result
            $signature->update([
                'is_valid' => $result['overall_valid'],
                'verification_details' => json_encode($result)
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            $errorResult = [
                'signature_id' => $signatureId,
                'overall_valid' => false,
                'error' => $e->getMessage(),
                'verification_performed_at' => now()->toISOString()
            ];
            
            $signature->update([
                'is_valid' => false,
                'verification_details' => json_encode($errorResult)
            ]);
            
            return $errorResult;
        }
    }
    
    private function verifyCertificate(DigitalCertificate $certificate): bool
    {
        // Check if certificate is not revoked and within validity period
        return !$certificate->is_revoked && 
               $certificate->valid_from <= now() && 
               $certificate->valid_to >= now();
    }
    
    private function verifyPKCS7Signature(array $pkcs7Data, DigitalCertificate $certificate): bool
    {
        try {
            // 1. Extract signature components
            $signerInfo = $pkcs7Data['contentInfo']['content']['signerInfos'][0];
            $signedAttributes = $signerInfo['signedAttributes'];
            $signature = base64_decode($signerInfo['signature']);
            
            // 2. Recreate signed attributes data
            $signedAttributesData = $this->encodeSignedAttributes($signedAttributes);
            $signedAttributesHash = hash('sha256', $signedAttributesData, true);
            
            // 3. Load public key from certificate
            $x509 = new X509();
            $certData = $x509->loadX509($certificate->certificate_pem);
            $publicKey = $x509->getPublicKey();
            
            // 4. Verify signature
            $isValid = openssl_verify(
                $signedAttributesHash, 
                $signature, 
                $publicKey->toString('PKCS8'), 
                OPENSSL_ALGO_SHA256
            );
            
            return $isValid === 1;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function verifyTimestamp(DocumentSignature $signature): bool
    {
        $timestamp = $signature->timestamps()->first();
        
        if (!$timestamp) {
            return false;
        }
        
        // Verify timestamp token (simplified for demo)
        try {
            $tokenData = json_decode(base64_decode($timestamp->timestamp_token), true);
            
            // Check if timestamp is within reasonable bounds
            $timestampTime = \Carbon\Carbon::parse($tokenData['genTime']);
            $signedTime = $signature->signed_at;
            
            // Timestamp should be within 5 minutes of signing time
            return abs($timestampTime->diffInMinutes($signedTime)) <= 5;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getDocumentSignatures(int $documentVersionId): array
    {
        $signatures = DocumentSignature::with([
            'certificate.user',
            'documentFlowStep',
            'timestamps'
        ])
        ->where('document_version_id', $documentVersionId)
        ->orderBy('signed_at')
        ->get();
        
        return $signatures->map(function ($signature) {
            return [
                'id' => $signature->id,
                'signer' => [
                    'name' => $signature->certificate->user->name,
                    'email' => $signature->certificate->user->email
                ],
                'step' => $signature->documentFlowStep->step,
                'signed_at' => $signature->signed_at->format('Y-m-d H:i:s'),
                'is_valid' => $signature->is_valid,
                'certificate_serial' => $signature->certificate->serial_number
            ];
        })->toArray();
    }
}
