<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class CreatePDFController extends Controller
{
    public function createPDF(Request $request, $documentId)
    {
        \Log::info("Creating PDF for document ID: $documentId");
        $document = Document::with('documentType')
            ->with([
                'documentFlow.steps.approver',
            ])
            ->find($documentId);
        \Log::info("Document retrieved: " . ($document ? 'Found' : 'Not Found'));
        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }
        // Log the document details for debugging
        \Log::info($document->toArray());

        if ($document->certificate_path) {
            // Nếu đã có chứng nhận, trả về thông báo
            return response()->json(['message' => 'Document already has a certificate'], 400);
        }
        return $document;

        // Dữ liệu mẫu cho văn bản được ký
        $data = [
            'logo' => public_path('images/departments/root.png'),
            'document_title' => $document->title,
            'description' => $document->document_type->name,
            'creation_date' => $document->created_at,
            'proposer_name' => 'Nguyễn Văn A',
            'proposer_department' => 'Phòng Kế hoạch',
            'approvers' => [
                [
                    'name' => 'Trần Thị B',
                    'department' => 'Phòng Nhân sự',
                    'signature' => public_path('images/signatures/signature-b.png'),
                ],
                [
                    'name' => 'Lê Văn C',
                    'department' => 'Phòng Tài chính',
                    'signature' => '', // Không có chữ ký
                ],
                // Thêm tối đa 8-9 người phê duyệt nếu cần
            ],
            'generated_at' => now()->format('H:i d/m/Y'), // Thời gian tạo file
        ];

        // Tải view và truyền dữ liệu
        $pdf = PDF::loadView('pdf.signed_document', $data);
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'document_' . $documentId . '_' . now()->timestamp . '.pdf';

        Storage::disk('certificates')->put($filePath, $pdf->output());

        $document->certificate_path = $filePath;
        $document->save();

        // Tùy chỉnh kích thước và hướng trang
        return $pdf->stream();

        // Tải xuống file PDF
        return $pdf->download('ChungNhanVanBanKy.pdf');
    }

    public function downloadPDF($documentId)
    {
        $document = Document::find($documentId);
        if (!$document || !$document->certificate_path) {
            return response()->json(['error' => 'Document not found or certificate not generated'], 404);
        }

        $filePath = $document->certificate_path;
        if (!Storage::disk('certificates')->exists($filePath)) {
            return response()->json(['error' => 'Certificate file not found'], 404);
        }

        return Storage::disk('certificates')->download($filePath);
    }
    
}
