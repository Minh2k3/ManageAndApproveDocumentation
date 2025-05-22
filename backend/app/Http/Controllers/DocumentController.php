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
    public function show(Document $document)
    {
        //
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

    // Hàm lấy các văn bản của một người gửi yêu cầu
    public function getDocumentsByCreator($id)
    {
        $documents = Document::where('created_by', $id)
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->leftJoin(\DB::raw('(SELECT document_id, COUNT(*) as version_count FROM document_versions GROUP BY document_id) as version_counts'), 
                  'documents.id', '=', 'version_counts.document_id')
            ->select(
                'documents.id as id',
                'documents.title as title',
                'documents.description as description',
                'documents.file_path as file_path',
                'documents.status as status',
                'documents.created_at as created_at',
                'documents.updated_at as updated_at',
                'documents.document_flow_id as document_flow_id',
                'document_types.name as type',
                \DB::raw('COALESCE(version_counts.version_count, 0) as version_count')
            )
            ->orderBy('documents.updated_at', 'desc')
            ->get();

        return response()->json([
            'documents' => $documents,
        ]);
    }

    // Hàm lấy các văn bản liên quan đến một người phê duyệt theo id
    public function getDocumentsByApprover($id)
    {
        $documents_of_me = Document::where('created_by', $id)
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->leftJoin(\DB::raw('(SELECT document_id, COUNT(*) as version_count FROM document_versions GROUP BY document_id) as version_counts'), 
                  'documents.id', '=', 'version_counts.document_id')
            ->select(
                'documents.id as id',
                'documents.title as title',
                'documents.description as description',
                'documents.file_path as file_path',
                'documents.status as status',
                'documents.created_at as created_at',
                'documents.updated_at as updated_at',
                'documents.document_flow_id as document_flow_id',
                'document_types.name as type',
                \DB::raw('COALESCE(version_counts.version_count, 0) as version_count')
            )
            ->orderBy('documents.updated_at', 'desc')
            ->get();

        $approver = Approver::where('user_id', $id)->first(); 
        $approver_id = $approver ? $approver['id'] : null;
        
        $documents_need_me = Document::whereHas('documentFlow.documentFlowSteps', function ($query) use ($approver_id) {
                $query->where('approver_id', $approver_id);
            })
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->leftJoin(DB::raw('(
                SELECT document_id, MAX(version) as max_version
                FROM document_versions 
                GROUP BY document_id
            ) as latest_versions'), 'documents.id', '=', 'latest_versions.document_id')
            ->leftJoin(\DB::raw('(
                SELECT user_id, department_id, roll_at_department_id FROM approvers
                UNION ALL
                SELECT user_id, department_id, roll_at_department_id FROM creators
            ) as user_roles'), 'documents.created_by', '=', 'user_roles.user_id')
            ->leftJoin('departments', 'user_roles.department_id', '=', 'departments.id')
            ->leftJoin('roll_at_departments', 'user_roles.roll_at_department_id', '=', 'roll_at_departments.id')
            ->join('users', 'documents.created_by', '=', 'users.id')
            ->where('documents.status', '!=', 'draft')
            ->select(
                'documents.id as id',
                'documents.title as title',
                'documents.description as description',
                'documents.status as status',
                'documents.created_at as created_at',
                'documents.updated_at as updated_at',
                'documents.document_flow_id as document_flow_id',
                'document_types.name as type',
                'documents.file_path as file_path',
                'latest_versions.max_version as version_count',
                \DB::raw('false as from_me'),
                'documents.created_by as creator_id',
                'departments.id as department_id',
                'roll_at_departments.id as roll_id',
                \DB::raw('CONCAT(roll_at_departments.name, " - ", departments.name) as roll'),
                'users.name as creator_name'
            )
            ->orderBy('documents.updated_at', 'desc')
            ->get();

        $documents = $documents_of_me->merge($documents_need_me)->unique('id')->values();
        $documents = $documents->sortByDesc('updated_at')->values();

        return response()->json([
            'documents' => $documents,
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
                    'receiver_id' => $admin['id'],
                    'title' => "Lưu nháp văn bản",
                    'content' => $user['name'] . " đã lưu bản nháp tài liệu '" . $new_document['title'] . "'",
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
                'status' => 'pending',
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
                    'receiver_id' => $admin['id'],
                    'title' => "Phê duyệt văn bản",
                    'content' => $user['name'] . " đã tạo một luồng phê duyệt cho '" . $new_document['title'] . "'",
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
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
                $content = $user['name'] . " đã thêm bạn vào luồng phê duyệt cho '" . $new_document['title'];
                if ($step['step'] == 1) {
                    $content = "Bạn là người phê duyệt đầu tiên cho '" . $new_document['title'] . "' do " . $user['name'] . ' tạo';
                }
                $notification = Notification::create([
                    'notification_category_id' => 3,
                    'receiver_id' => $approver['user_id'],
                    'title' => "Thêm bạn vào luồng phê duyệt văn bản",
                    'content' => $content,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
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

    // Hàm xử lý upload file
    public function uploadFile(Request $request)
    {
        $file = $request->file('upload_file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'public');

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
}
