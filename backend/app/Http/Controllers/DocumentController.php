<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Approver;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\DocumentTemplate;
use App\Models\DocumentVersion;
use App\Models\DocumentFlow;
use App\Models\DocumentFlowStep;
use App\Models\Notification;
use App\Models\Creator;
use App\Models\RollAtDepartment;
use Pusher\Pusher;

use App\Notifications\SaveDraft;
use App\Events\SaveDraft as SaveDraftEvent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::withCount('versions')
            ->join('users', 'documents.created_by', '=', 'users.id')
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->leftJoin(\DB::raw('(
                SELECT user_id, department_id, roll_at_department_id FROM approvers
                UNION ALL
                SELECT user_id, department_id, roll_at_department_id FROM creators
            ) as user_roles'), 'users.id', '=', 'user_roles.user_id')
            ->leftJoin('departments', 'user_roles.department_id', '=', 'departments.id')
            ->leftJoin('roll_at_departments', 'user_roles.roll_at_department_id', '=', 'roll_at_departments.id')
            ->select(
                'documents.id as id',
                'documents.title as title',
                'documents.description as description',
                'documents.file_path as file_path',
                'documents.status as status',
                'documents.created_at as created_at',
                'documents.updated_at as updated_at',
                'users.name as creator_name',
                'users.id as creator_id',
                'document_types.name as type',
                'document_types.id as type_id',
                'departments.id as department_id',
                'roll_at_departments.id as roll_id',
                \DB::raw('CONCAT(roll_at_departments.name, " - ", departments.name) as roll')
            )
            ->orderBy('documents.updated_at', 'desc')
            ->get();
        return response()->json([
            'documents' => $documents,
        ]);
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $document = Document::where('id', $id)
            ->get();
        
        return response()->json([
            'message' => 'Document retrieved successfully.',
            'document' => $document,
        ])->setStatusCode(200, 'Document retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }

    private function getCreatorInfoByUserId($user_id)
    {
        // Thử tìm trong bảng creators trước
        $creator = Creator::with(['user:id,name', 'department:id,name', 'rollAtDepartment:id,name'])
            ->where('user_id', $user_id)
            ->first();
        
        if ($creator) {
            return [
                'name' => $creator->user->name ?? null,
                'department_id' => $creator->department_id,
                'roll_at_department_id' => $creator->roll_at_department_id,
                'roll' => $creator->rollAtDepartment && $creator->department 
                    ? $creator->rollAtDepartment->name . ' - ' . $creator->department->name 
                    : null,
            ];
        }
        
        // Nếu không tìm thấy trong creators, thử tìm trong approvers
        $approver = Approver::with(['user:id,name', 'department:id,name', 'rollAtDepartment:id,name'])
            ->where('user_id', $user_id)
            ->first();
        
        if ($approver) {
            return [
                'name' => $approver->user->name ?? null,
                'department_id' => $approver->department_id,
                'roll_at_department_id' => $approver->roll_at_department_id,
                'roll' => $approver->rollAtDepartment && $approver->department 
                    ? $approver->rollAtDepartment->name . ' - ' . $approver->department->name 
                    : null,
            ];
        }
        
        // Nếu không tìm thấy ở cả hai bảng, chỉ lấy thông tin user
        $user = User::select('id', 'name')->find($user_id);
        
        return [
            'name' => $user ? $user->name : null,
            'department_id' => null,
            'roll_at_department_id' => null,
            'roll' => null,
        ];
    }

    // Hàm lấy các văn bản của một người gửi yêu cầu
    public function getDocumentsByCreator($id)
    {
        $documents = Document::with([
            'documentType:id,name',
            'documentFlow:id,name,process',
            'documentFlow.documentFlowSteps:id,document_flow_id',
            'versions' => function($query) {
                $query->select('id', 'document_id', 'version', 'document_data', 'status')
                    ->orderBy('version', 'desc')
                    ->limit(1);
            }
        ])
        ->where('created_by', $id) // $id là user_id
        ->orderBy('updated_at', 'desc')
        ->get()
        ->map(function($document) {
            $latestVersion = $document->versions->first();
            
            // Tìm thông tin người tạo từ user_id
            $creatorInfo = $this->getCreatorInfoByUserId($document->created_by);
            
            return [
                'id' => $document->id,
                'title' => $document->title,
                'description' => $document->description,
                'file_path' => $document->file_path,
                'status' => $latestVersion ? $latestVersion->status : null,
                'created_at' => $document->created_at,
                'updated_at' => $document->updated_at,
                'document_flow_id' => $document->document_flow_id,
                'type' => $document->documentType->name ?? null,
                'creator_name' => $creatorInfo['name'],
                'process' => $document->documentFlow->process ?? 0,
                'from_me' => true,
                'version_id' => $latestVersion->id ?? null,
                'version_count' => $latestVersion->version ?? 0,
                'version_data' => $latestVersion ? json_decode($latestVersion->document_data) ?? null : null,
                'roll' => $creatorInfo['roll'],
                'step_count' => $document->documentFlow->documentFlowSteps->count() ?? 0,
            ];
        });

        return response()->json([
            'documents' => $documents,
        ]);
    }

    // Hàm lấy các văn bản liên quan đến một người phê duyệt theo id
    public function getDocumentsByApprover($id)
    {
        // Lấy các văn bản do tôi tạo
        $documents_of_me = Document::with([
            'documentType:id,name',
            'documentFlow:id,name,process',
            'documentFlow.documentFlowSteps:id,document_flow_id',
            'versions' => function($query) {
                $query->select('id', 'document_id', 'version', 'document_data', 'status')
                    ->orderBy('version', 'desc')
                    ->limit(1);
            }
        ])
        ->where('created_by', $id) // $id là user_id
        ->orderBy('updated_at', 'desc')
        ->get()
        ->map(function($document) {
            $latestVersion = $document->versions->first();
            
            // Tìm thông tin người tạo từ user_id
            $creatorInfo = $this->getCreatorInfoByUserId($document->created_by);
            
            return [
                'id' => $document->id,
                'title' => $document->title,
                'description' => $document->description,
                'file_path' => $document->file_path,
                'status' => $latestVersion ? $latestVersion->status : null,
                'created_at' => $document->created_at,
                'updated_at' => $document->updated_at,
                'document_flow_id' => $document->document_flow_id,
                'type' => $document->documentType->name ?? null,
                'creator_name' => $creatorInfo['name'],
                'process' => $document->documentFlow->process ?? 0,
                'from_me' => true,
                'version_id' => $latestVersion->id ?? null,
                'version_count' => $latestVersion->version ?? 0,
                'version_data' => $latestVersion ? json_decode($latestVersion->document_data) ?? null : null,
                'roll' => $creatorInfo['roll'],
                'step_count' => $document->documentFlow->documentFlowSteps->count() ?? 0,
            ];
        });

        // Tìm approver record của user hiện tại
        $approver = Approver::where('user_id', $id)->first();
        $approver_id = $approver ? $approver->id : null;

        // Lấy các văn bản cần tôi duyệt
        $documents_need_me = collect();
        
        if ($approver_id) {
            $documents_need_me = Document::with([
                'documentType:id,name',
                'documentFlow:id,name,process',
                'documentFlow.documentFlowSteps' => function($query) use ($approver_id) {
                    $query->where('approver_id', $approver_id)
                        ->select('id', 'document_flow_id', 'approver_id', 'step', 'multichoice', 'status');
                },
                'versions' => function($query) {
                    $query->select('id', 'document_id', 'version', 'document_data', 'status')
                        ->orderBy('version', 'desc')
                        ->limit(1);
                }
            ])
            ->whereHas('documentFlow.documentFlowSteps', function ($query) use ($approver_id) {
                $query->where('approver_id', $approver_id);
            })
            ->whereHas('versions', function($query) {
                $query->where('status', '!=', 'draft');
            })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($document) use ($approver_id) {
                $latestVersion = $document->versions->first();
                $myStep = $document->documentFlow->documentFlowSteps->first();
                
                // Tìm thông tin người tạo từ user_id
                $creatorInfo = $this->getCreatorInfoByUserId($document->created_by);
                
                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'description' => $document->description,
                    'file_path' => $document->file_path,
                    'created_at' => $document->created_at,
                    'updated_at' => $document->updated_at,
                    'document_flow_id' => $document->document_flow_id,
                    'type' => $document->documentType->name ?? null,
                    'version_id' => $latestVersion->id ?? null,
                    'version_count' => $latestVersion->version ?? 0,
                    'version_data' => $latestVersion ? json_decode($latestVersion->document_data) ?? null : null,
                    'from_me' => false,
                    'creator_id' => $document->created_by,
                    'department_id' => $creatorInfo['department_id'],
                    'roll_id' => $creatorInfo['roll_at_department_id'],
                    'roll' => $creatorInfo['roll'],
                    'creator_name' => $creatorInfo['name'],
                    'process' => $document->documentFlow->process ?? 0,
                    'step_count' => DocumentFlowStep::where('document_flow_id', $document->document_flow_id)->count(),
                    'document_flow_step_id' => $myStep->id ?? 0,
                    'approver_id' => $myStep->approver_id ?? 0,
                    'multichoice' => $myStep->multichoice ?? false,
                    'step' => $myStep->step ?? 0,
                    'document_status' => $document->status ?? 'pending',
                    'step_status' => $myStep->status ?? 'pending',
                ];
            });
        }

        $documents = [
            'documents_need_me' => $documents_need_me,
            'documents_of_me' => $documents_of_me,
        ];

        return response()->json([
            'documents' => $documents,
        ]);
    }

    /**
     * Lấy thông tin văn bản do người dùng hiện tại tạo theo document_id
     * Dùng cho cả creators và approvers
     */
    public function getDocumentOfMeById($documentId)
    {
        $userId = auth()->user()->id;
        
        $document = Document::with([
            'documentType:id,name',
            'documentFlow:id,name,process',
            'documentFlow.documentFlowSteps:id,document_flow_id',
            'versions' => function($query) {
                $query->select('id', 'document_id', 'version', 'document_data', 'status')
                    ->orderBy('version', 'desc')
                    ->limit(1);
            }
        ])
        ->where('id', $documentId)
        ->where('created_by', $userId)
        ->first();
        
        if (!$document) {
            return response()->json([
                'message' => 'Document not found or you are not the creator',
                'document' => null
            ], 404);
        }
        
        $latestVersion = $document->versions->first();
        
        // Tìm thông tin người tạo từ user_id
        $creatorInfo = $this->getCreatorInfoByUserId($document->created_by);
        
        $documentData = [
            'id' => $document->id,
            'title' => $document->title,
            'description' => $document->description,
            'file_path' => $document->file_path,
            'status' => $latestVersion ? $latestVersion->status : null,
            'created_at' => $document->created_at,
            'updated_at' => $document->updated_at,
            'document_flow_id' => $document->document_flow_id,
            'type' => $document->documentType->name ?? null,
            'creator_name' => $creatorInfo['name'],
            'process' => $document->documentFlow->process ?? 0,
            'from_me' => true,
            'version_id' => $latestVersion->id ?? null,
            'version_count' => $latestVersion->version ?? 0,
            'version_data' => $latestVersion ? json_decode($latestVersion->document_data) ?? null : null,
            'roll' => $creatorInfo['roll'],
            'step_count' => $document->documentFlow->documentFlowSteps->count() ?? 0,
        ];
        
        return response()->json([
            'document' => $documentData
        ]);
    }

    /**
     * Lấy thông tin văn bản cần duyệt của approver theo document_id
     * Chỉ dùng cho approvers
     */
    public function getDocumentNeedMeById($documentId)
    {
        $userId = auth()->user()->id;
        
        // Tìm approver record của user hiện tại
        $approver = Approver::where('user_id', $userId)->first();
        
        if (!$approver) {
            return response()->json([
                'message' => 'You are not an approver',
                'document' => null
            ], 403);
        }
        
        $approverId = $approver->id;
        
        $document = Document::with([
            'documentType:id,name',
            'documentFlow:id,name,process',
            'documentFlow.documentFlowSteps' => function($query) use ($approverId) {
                $query->where('approver_id', $approverId)
                    ->select('id', 'document_flow_id', 'approver_id', 'step', 'multichoice', 'status');
            },
            'versions' => function($query) {
                $query->select('id', 'document_id', 'version', 'document_data', 'status')
                    ->orderBy('version', 'desc')
                    ->limit(1);
            }
        ])
        ->where('id', $documentId)
        ->whereHas('documentFlow.documentFlowSteps', function ($query) use ($approverId) {
            $query->where('approver_id', $approverId);
        })
        ->whereHas('versions', function($query) {
            $query->where('status', '!=', 'draft');
        })
        ->first();
        
        if (!$document) {
            return response()->json([
                'message' => 'Document not found or you are not assigned to approve this document',
                'document' => null
            ], 404);
        }
        
        $latestVersion = $document->versions->first();
        $myStep = $document->documentFlow->documentFlowSteps->first();
        
        // Tìm thông tin người tạo từ user_id
        $creatorInfo = $this->getCreatorInfoByUserId($document->created_by);
        
        $documentData = [
            'id' => $document->id,
            'title' => $document->title,
            'description' => $document->description,
            'file_path' => $document->file_path,
            'created_at' => $document->created_at,
            'updated_at' => $document->updated_at,
            'document_flow_id' => $document->document_flow_id,
            'type' => $document->documentType->name ?? null,
            'version_id' => $latestVersion->id ?? null,
            'version_count' => $latestVersion->version ?? 0,
            'version_data' => $latestVersion ? json_decode($latestVersion->document_data) ?? null : null,
            'from_me' => false,
            'creator_id' => $document->created_by,
            'department_id' => $creatorInfo['department_id'],
            'roll_id' => $creatorInfo['roll_at_department_id'],
            'roll' => $creatorInfo['roll'],
            'creator_name' => $creatorInfo['name'],
            'process' => $document->documentFlow->process ?? 0,
            'step_count' => DocumentFlowStep::where('document_flow_id', $document->document_flow_id)->count(),
            'document_flow_step_id' => $myStep->id ?? 0,
            'approver_id' => $myStep->approver_id ?? 0,
            'multichoice' => $myStep->multichoice ?? false,
            'step' => $myStep->step ?? 0,
            'document_status' => $document->status ?? 'pending',
            'step_status' => $myStep->status ?? 'pending',
        ];
        
        return response()->json([
            'document' => $documentData
        ]);
    }

    // Hàm lưu nháp
    public function storeDraftDocument(Request $request)
    {
        $document = $request['document'];
        $document_flow = $request['document_flow'];
        $document_flow_step = $document_flow['current_flow_step'];

        \DB::beginTransaction();

        try {
            $new_document_flow = DocumentFlow::create([
                'name' => $document_flow['document_flow_name'],
                'created_by' => $document_flow['created_by'],
                'is_active' => false,
                'is_template' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (count($document_flow_step) == 1 && $document_flow_step[0]['department_id'] == null) {

            } else {
                $document_flow_id = $new_document_flow['id'];
                foreach ($document_flow_step as $step) {
                    DocumentFlowStep::create([
                        'document_flow_id' => $document_flow_id,
                        'step' => $step['step'],
                        'department_id' => $step['department_id'],
                        'approver_id' => $step['approver_id'],
                        'multichoice' => $step['multichoice'],
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $new_document = Document::create([
                'title' => $document['title'],
                'description' => $document['description'],
                'file_path' => 'Minh',
                'document_type_id' => $document['document_type_id'],
                'created_by' => $document['created_by'],
                'document_flow_id' => $new_document_flow['id'],
                'status' => 'draft',
                'is_public' => $document['is_public'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $new_document_version = DocumentVersion::create([
                'document_id' => $new_document['id'],
                'version' => 1,
                'document_data' => $new_document,
                'created_at' => now(),
            ]);

            $user = auth()->user();
            $admins = User::where('role_id', '1')->get();
            foreach ($admins as $admin) {
                $notification = Notification::create([
                    'notification_category_id' => 1,
                    'from_user_id' => $user['id'],
                    'receiver_id' => $admin['id'],
                    'title' => "Lưu nháp văn bản",
                    'content' => "Lưu bản nháp văn bản '" . $new_document['title'] . "'",
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                // broadcast(new SaveDraftEvent($admin, $notification, $new_document['id']));
                $options = [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                ];
                $pusher = new \Pusher\Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );

                // $user_id = $admin['user_id'];
                $data['notification'] = $notification;
                $data['document'] = $new_document;
                // Log::info('Pusher admin: ', $admin);
                $pusher->trigger('user.' . $admin['id'], 'new-notification', $data);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Lỗi khi lưu bản nháp: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Bản nháp đã được lưu thành công.',
            'id' => $new_document['id'],
            'admins' => $admins,
            'user' => $user,
        ]);
    }

    // Hàm gửi yêu cầu phê duyệt
    public function storeRequestDocument(Request $request)
    {
        $document = $request['document'];
        $document_flow = $request['document_flow'];
        $document_flow_step = $document_flow['current_flow_step'];

        \DB::beginTransaction();

        try {
            $new_document_flow = DocumentFlow::create([
                'name' => $document_flow['document_flow_name'],
                'created_by' => $document_flow['created_by'],
                'is_active' => false,
                'is_template' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $document_flow_id = $new_document_flow['id'];
            foreach ($document_flow_step as $step) {
                $status = ($step['step'] == 1) ? 'in_review' : 'pending';

                DocumentFlowStep::create([
                    'document_flow_id' => $document_flow_id,
                    'step' => $step['step'],
                    'department_id' => $step['department_id'],
                    'approver_id' => $step['approver_id'],
                    'multichoice' => $step['multichoice'],
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $new_document = Document::create([
                'title' => $document['title'],
                'description' => $document['description'],
                'file_path' => 'Minh',
                'document_type_id' => $document['document_type_id'],
                'created_by' => $document['created_by'],
                'document_flow_id' => $new_document_flow['id'],
                'status' => 'in_review',
                'is_public' => $document['is_public'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DocumentVersion::create([
                'document_id' => $new_document['id'],
                'version' => 1,
                'document_data' => $new_document,
                'status' => 'in_review',
                'created_at' => now(),
            ]);

            $user = auth()->user();
            $admins = User::where('role_id', '1')->get();
            foreach ($admins as $admin) {
                $notification = Notification::create([
                    'notification_category_id' => 2,
                    'from_user_id' => $user['id'],
                    'receiver_id' => $admin['id'],
                    'title' => "Tạo văn bản phê duyệt",
                    'content' => "Tạo một luồng phê duyệt cho văn bản '" . $new_document['title'] . "'",
                    'is_read' => false,
                    'created_at' => now(),
                ]); 

                $options = [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                ];
                $pusher = new \Pusher\Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );

                $data['notification'] = $notification;
                $data['document'] = $new_document;
                $pusher->trigger('user.' . $admin['id'], 'new-notification', $data);
            }

            foreach ($document_flow_step as $step) {
                $approver = Approver::find($step['approver_id']);
                $content = "Thêm bạn vào luồng phê duyệt cho văn bản '" . $new_document['title'] . "'";
                if ($step['step'] == 1) {
                    $content = "Bạn là người phê duyệt đầu tiên cho văn bản '" . $new_document['title'] . "' do " . $user['name'] . ' tạo';
                }
                $notification = Notification::create([
                    'notification_category_id' => 3,
                    'from_user_id' => $user['id'],
                    'receiver_id' => $approver['user_id'],
                    'title' => "Thêm vào luồng phê duyệt văn bản",
                    'content' => $content,
                    'is_read' => false,
                    'created_at' => now(),
                ]);

                $options = [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                ];
                $pusher = new \Pusher\Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );

                $data['notification'] = $notification;
                $data['document'] = $new_document;
                $pusher->trigger('user.' . $approver['user_id'], 'new-notification', $data);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \Log::error('Error in storeRequestDocument: ' . $e->getMessage());
            \DB::rollBack();
            return response()->json([
                'message' => 'Lỗi khi gửi phê duyệt: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Luồng phê duyệt cho tài liệu đã được lưu thành công.',
            'id' => $new_document['id'],
        ]);
    }

    // Hàm xử lý upload file văn bản
    public function uploadFile(Request $request)
    {
        $file = $request->file('upload_file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'documents');

        // Update file_path cho Document
        $document = Document::find($request['document_id']);
        $document->file_path = $filename; 
        $document->save();

        // Update file_path cho DocumentVersion
        $document_version = DocumentVersion::where('document_id', $request['document_id'])
            ->orderBy('version', 'desc')
            ->first();

        $document_version->document_data = $document;
        $document_version->save();

        return response()->json([
            'message' => 'Upload file thành công',
            'file_url' => url(`/documents/` . $filename),
        ]);
    }


    // Mấy hàm dưới đây để hiển thị file PDF nhưng không rõ có dùng nữa hay không
    public function viewPdf($filename)
    {
        // Validate filename
        if (!preg_match('/^[a-zA-Z0-9\-_.]+\.pdf$/', $filename)) {
            return response()->json(['error' => 'Invalid filename'], 400);
        }
        
        $path = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        // Validate PDF file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);
        
        if ($mimeType !== 'application/pdf') {
            return response()->json(['error' => 'Invalid file type'], 400);
        }
        
        // Return file with CORS headers
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
            'Cache-Control' => 'public, max-age=3600',
            // CORS Headers
            'Access-Control-Allow-Origin' => $this->getAllowedOrigin(),
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization',
            'Access-Control-Expose-Headers' => 'Content-Length, Content-Type, Content-Disposition',
            'Access-Control-Max-Age' => '86400', // 24 hours
        ]);
    }
    
    public function downloadPdf($filename)
    {
        $path = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
            // CORS Headers for download
            'Access-Control-Allow-Origin' => $this->getAllowedOrigin(),
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }
    
    public function handlePreflight($filename)
    {
        return response('', 200, [
            'Access-Control-Allow-Origin' => $this->getAllowedOrigin(),
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization',
            'Access-Control-Max-Age' => '86400',
        ]);
    }
    
    private function getAllowedOrigin()
    {
        $allowedOrigins = [
            'http://localhost:8000',
            'http://localhost:5173',
            'http://127.0.0.1:8000',
            'http://127.0.0.1:5173',
            env('FRONTEND_URL', 'http://localhost:5173')
        ];
        
        $origin = request()->header('Origin');
        
        if (in_array($origin, $allowedOrigins)) {
            return $origin;
        }
        
        // For development, allow all
        if (app()->environment('local')) {
            return '*';
        }
        
        // Production: return specific domain
        return env('FRONTEND_URL', 'http://localhost:5173');
    }
}
