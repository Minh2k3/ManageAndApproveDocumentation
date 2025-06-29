<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentCertificate;

class DocumentCertificateController extends Controller
{
    public function index($code)
    {
        // Kiểm tra xem mã chứng nhận có hợp lệ không
        if (empty($code)) {
            return response()->json(['error' => 'Invalid certificate code'], 400);
        }

        // Tìm kiếm chứng nhận theo mã
        $document_certificate = DocumentCertificate::where('code', $code)->first();

        if (!$document_certificate) {
            return response()->json(['error' => 'Certificate not found'], 404);
        }

        // Trả về thông tin chứng nhận
        return response()->json([
            'message' => 'success',
            'certificate' => $document_certificate,
        ]);
    }

    public function findByDocumentId($code)
    {
        // Kiểm tra xem mã chứng nhận có hợp lệ không
        if (empty($code)) {
            return response()->json(['error' => 'Invalid certificate code'], 400);
        }

        // Tìm kiếm chứng nhận theo mã
        $document_certificate = DocumentCertificate::where('document_id', $code)->first();

        if (!$document_certificate) {
            return response()->json(['error' => 'Certificate not found'], 404);
        }

        // Trả về thông tin chứng nhận
        return response()->json([
            'message' => 'success',
            'certificate' => $document_certificate,
        ]);
    }    
}
