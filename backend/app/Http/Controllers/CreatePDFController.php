<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Document;
use App\Models\DocumentCertificate;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;


class CreatePDFController extends Controller
{
    public function createPDF(Request $request, $documentId)
    {
        \Log::info("Creating PDF for document ID: $documentId");
        $document = Document::with('documentType')
            ->with([
                'documentFlow.steps.approver.user.activeCertificates',
                'creator',
            ])
            ->find($documentId);
        \Log::info("Document retrieved: " . ($document ? 'Found' : 'Not Found'));
        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        $documentCertificate = DocumentCertificate::where('document_id', $document->id)->first();
        if (!$documentCertificate) {
            $file_path = public_path('documents\\' . $document->file_path);
            $code_random = now()->timestamp;
            $document_certificate = DocumentCertificate::create([
                'code' => $document->created_by . '-' . $code_random,
                'document_id' => $document->id,
                'file_path' => 'Null',
                'created_at' => now(),
            ]);
            
            \Log::info("Document certificate created with code: " . $document_certificate->code);
            $new_file_path = $document_certificate->code . '.pdf';
            $output_file_path = public_path('documents/certificates/' . $new_file_path);
            \Log::info("Output file path: " . $new_file_path);
            $this->insertCertificate($file_path, $output_file_path, $document_certificate->code);
            \Log::info("Certificate inserted into PDF: " . $output_file_path);
            $document_certificate->file_path = $document_certificate->code . '.pdf';
            $document_certificate->save();
            \Log::info("Document certificate file path updated: " . $document_certificate->id);
        }
        // Log the document details for debugging
        // \Log::info($document->toArray());

        if ($document->certificate_path) {
            // Nếu đã có chứng nhận, trả về thông báo
            return response()->json([
                'message' => 'success',
                'certificate_path' => $document->certificate_path,
            ]);
        }
        $current_creator = $document->creator;
        // \Log::info("Current creator: " . ($current_creator ? $current_creator : 'Not Found'));
        // \Log::info("Current creator: " . $current_creator->creator->full_role);
        $creator_role = $current_creator->creator?->full_role ?? $current_creator->approver?->full_role;
        // return $creator_role;
        $current_steps = $document->documentFlow->steps;
        $approvers = $current_steps->map(function ($step) {
            \Log::info($step->approver->full_role);
            $certificate = $step->approver->user->activeCertificates->first();
            $path = $certificate ? $certificate->signature_image_path : null;
            $signatureImage = $path ? public_path('images/signatures/' . $path) : "";
            return [
                'name' => $step->approver->user->name,
                'department' => $step->approver->full_role,
                'signature' => $signatureImage,
                'signed_at' => $step->signed_at ? $step->signed_at->format('H:i d/m/Y') : 'Chưa ký',
            ];
        })->toArray();
        // return $approvers;

        // Dữ liệu mẫu cho văn bản được ký
        $data = [
            'logo_tlu' => public_path('images/departments/logo_tlu.png'),
            'logo_dtn' => public_path('images/departments/logo_dtn.png'),
            'logo_hsv' => public_path('images/departments/logo_hsv.png'),
            'document_title' => $document->title ?? 'Chưa xác định',
            'description' => $document->documentType->name ?? 'Chưa xác định',
            'creation_date' => $document->created_at ?? now()->format('H:i d/m/Y'),
            'proposer_name' => $current_creator->name ?? 'Chưa xác định',
            'proposer_department' => $creator_role,
            'approvers' => $approvers, // Danh sách người phê duyệt
            'generated_at' => now()->format('H:i d/m/Y'), // Thời gian tạo file
        ];

        // return view('pdf.signed_document', $data);

        // Tải view và truyền dữ liệu
        $pdfSave = Pdf::loadView('pdf.signed_document', $data);
        $pdfSave->setPaper('A4', 'portrait');

        $fileName = 'document_' . $documentId . '_' . now()->timestamp . '.pdf';
        
        try {
            Storage::disk('certificates')->put($fileName, $pdfSave->output());
            
            $document->certificate_path = $fileName;
            $document->save();

            $fileContent = Storage::disk('certificates')->get($fileName);
            \Log::info("PDF content size: " . strlen($fileContent));
            // Lưu vào Google Drive
            Storage::disk('google')->put($fileName, $fileContent);
            \Log::info("PDF saved successfully: " . $fileName);
        } catch (\Exception $e) {
            \Log::error("Error saving PDF: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'success',
            'certificate_path' => $fileName,
        ]);
    }

    public function insertCertificate($file, $outputFilePath, $code) 
    {
        \Log::info("Inserting certificate into PDF: " . $file);
        $fpdi = new Fpdi();

        $pageCount = $fpdi->setSourceFile($file);
        \Log::info("Page count: " . $pageCount);

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($templateId);
            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($templateId);
            \Log::info("Processing page: " . $i);

            $fpdi->SetFont('Arial', '', 12);
            $fpdi->SetTextColor(67, 195, 250);

            // Thêm mã chứng nhận vào góc dưới bên phải
            $left = $size['width'] - 80;
            $top = $size['height'] - 20;
            $fpdi->SetTextColor(67, 195, 250); // Đặt màu chữ
            $fpdi->SetFont('Times', 'B', 12);
            $fpdi->Text($left, $top, 'Certificate Code: ' . $code);

            // Thêm dòng tra cứu tại vào dưới dòng mã
            $fpdi->SetTextColor(67, 195, 250);
            $fpdi->SetFont('Times', '', 10);
            $fpdi->Text($left, $top + 5, 'Retrieve at: https://tminh.id.vn/retrieve/');
            \Log::info("Added certificate code to page: " . $i);

            // Thêm dòng tra cứu tại vào dưới dòng mã
            $fpdi->SetTextColor(67, 195, 250);
            $fpdi->SetFont('Times', '', 10);
            $fpdi->Text($left - 100, $top + 5, 'Authenticated by TLU');
            \Log::info("Added certificate code to page: " . $i);
        }

        return $fpdi->Output($outputFilePath, 'F');
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
