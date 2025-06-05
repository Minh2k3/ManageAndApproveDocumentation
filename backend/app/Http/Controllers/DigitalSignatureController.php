<?php
// app/Http/Controllers/DigitalSignatureController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DigitalSignatureService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DigitalSignatureController extends Controller
{
    protected $signatureService;
    
    public function __construct(DigitalSignatureService $signatureService)
    {
        $this->signatureService = $signatureService;
    }
    
    public function sign(Request $request): JsonResponse
    {
        $request->validate([
            'document_version_id' => 'required|exists:document_versions,id',
            'document_flow_step_id' => 'required|exists:document_flow_steps,id'
        ]);
        
        try {
            $signature = $this->signatureService->signDocument(
                $request->document_version_id,
                $request->document_flow_step_id,
                auth()->id()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Document signed successfully',
                'data' => [
                    'signature_id' => $signature->id,
                    'signed_at' => $signature->signed_at->format('Y-m-d H:i:s'),
                    'certificate_serial' => $signature->certificate->serial_number
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function verify(int $signatureId): JsonResponse
    {
        try {
            $verificationResult = $this->signatureService->verifySignature($signatureId);
            
            return response()->json([
                'success' => true,
                'data' => $verificationResult
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
    
    public function getDocumentSignatures(int $documentVersionId): JsonResponse
    {
        try {
            $signatures = $this->signatureService->getDocumentSignatures($documentVersionId);
            
            return response()->json([
                'success' => true,
                'data' => $signatures
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
    
    public function getSignatureDetails(int $signatureId): JsonResponse
    {
        try {
            $signature = DocumentSignature::with([
                'certificate.user',
                'documentVersion.document',
                'documentFlowStep',
                'timestamps'
            ])->findOrFail($signatureId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $signature->id,
                    'document' => [
                        'id' => $signature->documentVersion->document->id,
                        'title' => $signature->documentVersion->document->title,
                        'version' => $signature->documentVersion->version
                    ],
                    'signer' => [
                        'name' => $signature->certificate->user->name,
                        'email' => $signature->certificate->user->email
                    ],
                    'certificate' => [
                        'serial_number' => $signature->certificate->serial_number,
                        'subject' => $signature->certificate->subject,
                        'valid_from' => $signature->certificate->valid_from->format('Y-m-d'),
                        'valid_to' => $signature->certificate->valid_to->format('Y-m-d'),
                        'thumbprint' => $signature->certificate->thumbprint
                    ],
                    'signature_info' => [
                        'signed_at' => $signature->signed_at->format('Y-m-d H:i:s'),
                        'document_hash' => $signature->document_hash,
                        'is_valid' => $signature->is_valid,
                        'signature_attributes' => $signature->signature_attributes
                    ],
                    'timestamp' => $signature->timestamps->first() ? [
                        'timestamp_time' => $signature->timestamps->first()->timestamp_time->format('Y-m-d H:i:s'),
                        'hash_algorithm' => $signature->timestamps->first()->hash_algorithm
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}