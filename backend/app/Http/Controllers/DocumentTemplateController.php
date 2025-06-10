<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DocumentTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentTemplates = \DB::table('document_templates')
            ->select(
                'id as value',
                'name as label',
            )
            ->get();
        return response()->json([
            'document_template_templates' => $documentTemplates,
        ])->setStatusCode(200, 'Document templates retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();
        \Log::info('Creating new document template with request data: ', $request->all());

        try {
            $new_document_template = DocumentTemplate::create([
                'name' => $request->name,
                'description' => $request->description,
                'file_path' => 'Minh',
                'created_by' => $request->created_by,
                'status' => $request->status,
                'document_type_id' => $request->document_type_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Log::info('New document template created: ' . $new_document_template->id);

            \DB::commit();

            return response()->json([
                'message' => 'Document template created successfully.',
                'document_template' => $new_document_template,
            ])->setStatusCode(201);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Failed to create document template: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $documentTemplate = DocumentTemplate::with([
            'creator' => function($query) {
                $query->select('id', 'name', 'avatar', 'role_id');
            },
            'documentType:id,name,description',
        ])
        ->select(
            'id', 'name', 'file_path', 'description', 'status',
            'created_by', 'document_type_id', 'downloaded', 'liked',
            'created_at', 'updated_at'
        )
        ->find($id);

        if (!$documentTemplate) {
            return response()->json([
                'message' => 'Document template not found.',
            ])->setStatusCode(404);
        }

        return response()->json([
            'document_template' => $documentTemplate,
        ])->setStatusCode(200, 'Document template retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentTemplate $documentTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentTemplate $documentTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentTemplate $documentTemplate)
    {
        //
    }

    /**
     * Get all active templates.
     */
    public function getAllTemplates()
    {
        $documentTemplates = DocumentTemplate::with([
            'creator' => function($query) {
                $query->select('id', 'name', 'avatar', 'role_id');
            },
            'documentType:id,name,description',
        ])
        ->select(
            'id', 'name', 'file_path', 'description', 'status',
            'created_by', 'document_type_id', 'downloaded', 'liked',
            'created_at', 'updated_at'
        )
        ->orderBy('updated_at', 'desc')
        ->get();

        return response()->json([
            'document_templates' => $documentTemplates,
        ])->setStatusCode(200, 'Templates retrieved successfully.');
    }

    // Hàm xử lý upload file văn bản
    public function uploadFile(Request $request)
    {
        \Log::info('Upload file request received with data: ', $request->all());
        $file = $request->file('upload_file');
        \Log::info('File upload request received.');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        \Log::info('Generated filename: ' . $filename);
        $path = $file->storeAs('', $filename, 'documents');
        \Log::info('File uploaded to: ' . $path);

        // Update file_path cho Document
        $document = DocumentTemplate::find($request['document_template_id']);
        \Log::info('Updating document with ID: ' . $document->id);
        $document->file_path = $filename; 
        $document->save();
        \Log::info('Document updated with file_path: ' . $filename);

        return response()->json([
            'message' => 'Upload file thành công',
            'file_url' => url(`/documents/` . $filename),
        ])->setStatusCode(200, 'File uploaded successfully.');
    }

    public function changeStatus(Request $request)
    {
        $documentTemplate = DocumentTemplate::find($request->id);
        if (!$documentTemplate) {
            return response()->json([
                'message' => 'Document template not found.',
            ])->setStatusCode(404);
        }

        \DB::beginTransaction();

        try {
            $documentTemplate->status = $request->status;
            $documentTemplate->updated_at = now();
            $documentTemplate->save();

            \DB::commit();

            return response()->json([
                'message' => 'Document template activated successfully.',
                'document_template' => $documentTemplate,
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Failed to activate document template: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    public function downloadTemplate($id, Request $request)
    {
        try {
            // Tìm template
            $template = DocumentTemplate::find($id);
            
            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template không tồn tại'
                ], 404);
            }

            // // Kiểm tra file có tồn tại không
            // if (!$template->file_path || !Storage::disk('documents')->exists($template->file_path)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'File không tồn tại hoặc đã bị xóa'
            //     ], 404);
            // }

            // // Lấy đường dẫn file thực
            // $filePath = Storage::disk('documents')->path($template->file_path);
            // \Log::info('Downloading template file: ' . $filePath);
            // $fileName = $template->file_name ?? basename($template->file_path);
            // \Log::info('File name for download: ' . $fileName);
            // $fileSize = File::size($filePath);
            // \Log::info('File size: ' . $fileSize . ' bytes');

            // Log download activity
            // $this->logDownload($template, $request);

            // Tăng counter download
            $template->increment('downloaded');

            // Return file download response
            return response()->json([
                'success' => true,
                'download_url' => asset('documents/' . $template->file_path)
            ]);

        } catch (\Exception $e) {
            Log::error('Download template error:', [
                'template_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải file'
            ], 500);
        }
    }    
}
