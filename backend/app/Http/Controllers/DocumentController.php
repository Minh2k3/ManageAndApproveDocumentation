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

    public function getDocumentsByCreator($id)
    {
        $documents = Document::where('created_by', $id)
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->select(
                'documents.id as id',
                'documents.title as title',
                'documents.description as description',
                'documents.file_path as file_path',
                'documents.status as status',
                'documents.created_at as created_at',
                'documents.updated_at as updated_at',
                'document_types.name as type'
            )
            ->orderBy('documents.created_at', 'desc')
            ->get();

        return response()->json([
            'documents' => $documents,
        ]);
    }

    public function getDocumentsByApprover($id)
    {
        $documents_of_me = Document::where('created_by', $id)
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->select(
                'documents.id as id',
                'documents.title as title',
                'documents.description as description',
                'documents.file_path as file_path',
                'documents.status as status',
                'documents.created_at as created_at',
                'documents.updated_at as updated_at',
                'document_types.name as type'
            )
            ->orderBy('documents.created_at', 'desc')
            ->get();
        
        $documents_need_me = Document::whereHas('documentFlow.documentFlowSteps', function ($query) use ($id) {
                $query->where('approver_id', $id);
            })
            ->where('status', '!=', 'draft')
            ->get();

        $documents = $documents_of_me->merge($documents_need_me)->unique('id')->values();

        return response()->json([
            'documents' => $documents,
        ]);
    }

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
                'file_path' => 'Minh',
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
            'message' => 'Bản nháp đã được lưu thành công.',
            'id' => $new_document['id'],
        ]);
    }

    public function uploadFile(Request $request)
    {
        $file = $request->file('upload_file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'public');

        $document = Document::find($request['document_id']);
        $document->file_path = $filename; 
        if ($document['status'] == 'pending') {
            $document_version = DocumentVersion::where('document_id', $request['document_id'])
                ->orderBy('version', 'desc')
                ->first();
            
            $document_version->file_path = $filename;
            $document_version->save();
        }
        $document->save();

        return response()->json([
            'message' => 'Upload file thành công',
            'file_url' => url(`/documents/` . $filename),
        ]);
    }
}
