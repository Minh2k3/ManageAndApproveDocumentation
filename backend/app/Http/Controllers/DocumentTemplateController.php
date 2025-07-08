<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Models\TemplateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Http\Resources\DocumentTemplateResource;

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
            'creator.role', // Load role để biết user thuộc loại nào
            'creator.creator.rollAtDepartment', // Load rollAtDepartment cho creator
            'creator.creator.department', // Load department cho creator
            'creator.approver.rollAtDepartment', // Load rollAtDepartment cho approver
            'creator.approver.department', // Load department cho approver
            'documentType:id,name,description',
        ])
        ->select(
            'id', 'name', 'file_path', 'description', 'status',
            'created_by', 'document_type_id', 'downloaded', 'liked',
            'created_at', 'updated_at'
        )
        ->orderByRaw("CASE 
            WHEN status = 'pending' THEN 1 
            WHEN status = 'active' THEN 2 
            WHEN status = 'inactive' THEN 3 
            ELSE 4 
        END")
        ->orderBy('updated_at', 'desc')
        ->get();

        return response()->json([
            'document_templates' => DocumentTemplateResource::collection($documentTemplates),
        ])->setStatusCode(200, 'Document templates retrieved successfully.');
        // return response() DocumentTemplateResource::collection($documentTemplates);
    }

    public function getAllTemplatesUser()
    {
        $user = auth()->user();
        $documentTemplates = DocumentTemplate::with([
            'creator.role',
            'creator.creator.rollAtDepartment',
            'creator.creator.department',
            'creator.approver.rollAtDepartment',
            'creator.approver.department',
            'documentType:id,name,description',
        ])
        ->select(
            'document_templates.id',
            'document_templates.name',
            'document_templates.file_path',
            'document_templates.description',
            'document_templates.status',
            'document_templates.created_by',
            'document_templates.document_type_id',
            'document_templates.downloaded',
            'document_templates.liked',
            'document_templates.created_at',
            'document_templates.updated_at',
            \DB::raw('CASE WHEN template_users.id IS NOT NULL THEN 1 ELSE 0 END as is_liked')
        )
        ->leftJoin('template_users', function ($join) {
            $join->on('document_templates.id', '=', 'template_users.template_id')
            ->where('template_users.user_id', '=', auth()->id());
        })
        ->addSelect(\DB::raw('template_users.id IS NOT NULL as is_liked'))
        ->where(function ($query) use ($user) {
            $query->where('document_templates.created_by', $user->id)
                  ->orWhere('document_templates.status', 'active');
        })
        ->orderByRaw('template_users.id IS NOT NULL DESC')
        ->orderBy('document_templates.updated_at', 'desc')
        ->get();

        $templateUser = TemplateUser::where('user_id', $user->id)
            ->pluck('template_id')
            ->toArray();

        $documentTemplates = $documentTemplates->map(function ($template) use ($templateUser) {
            // Thêm thuộc tính userHasLike vào mỗi template
            $template->userHasLike = in_array($template->id, $templateUser);
            return $template;
        });

        return response()->json([
            'document_templates' => DocumentTemplateResource::collection($documentTemplates),
        ])->setStatusCode(200, 'Document templates retrieved successfully.');
        // return response() DocumentTemplateResource::collection($documentTemplates);
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

    public function getLikedTemplatesByUserId(Request $request, $user_id)
    {
        if (!$userId) {
            return response()->json([
                'message' => 'User ID is required.',
            ])->setStatusCode(400);
        }

        $likedTemplates = DocumentTemplate::where('liked', true)
            ->where('user_id', $user_id)
            ->get();

        return response()->json([
            'liked_templates' => $likedTemplates,
        ])->setStatusCode(200, 'Liked templates retrieved successfully.');
    }

    public function likeTemplate(Request $request, $id)
    {
        $user = auth()->user();
        $template = DocumentTemplate::find($id);
        if (!$template) {
            return response()->json([
                'message' => 'Document template not found.',
            ])->setStatusCode(404);
        }

        \DB::beginTransaction();

        try {
            $template_user = TemplateUser::create([
                'user_id' => $user->id,
                'template_id' => $template->id,
            ]);

            // Cập nhật số lượng người thích
            $template->increment('liked');

            \DB::commit();

            return response()->json([
                'message' => 'Document template liked successfully.',
                'template_user' => $template_user,
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Failed to like document template: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    public function unlikeTemplate(Request $request, $id)
    {
        $user = auth()->user();
        $template = DocumentTemplate::find($id);
        if (!$template) {
            return response()->json([
                'message' => 'Document template not found.',
            ])->setStatusCode(404);
        }

        \DB::beginTransaction();

        try {
            // Xóa bản ghi trong bảng trung gian
            TemplateUser::where('user_id', $user->id)
                ->where('template_id', $template->id)
                ->delete();

            // Giảm số lượng người thích
            $template->decrement('liked');

            \DB::commit();

            return response()->json([
                'message' => 'Document template unliked successfully.',
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Failed to unlike document template: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
