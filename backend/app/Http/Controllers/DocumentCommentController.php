<?php

namespace App\Http\Controllers;

use App\Models\DocumentComment;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use App\Models\Creator;
use App\Models\Approver;
use App\Models\DocumentFlowStep;
use App\Models\DocumentFlow;
use App\Models\Notification;
use App\Models\DocumentVersion;
use Pusher\Pusher;


class DocumentCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentComment $documentComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentComment $documentComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentComment $documentComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentComment $documentComment)
    {
        //
    }

    /**
     * Get all the comments of a document by document_id.
     */
    public function getCommentsByDocument($id)
    {
        // Tìm version mới nhất của document
        $latestVersion = DocumentVersion::with(['comments.user:id,name,avatar'])
            ->where('document_id', $id)
            ->latest('created_at')
            ->first();

        if (!$latestVersion) {
            return response()->json([
                'document_id' => $id,
                'total_comments' => 0,
                'current_comments' => [],
                'other_comments' => [],
                'message' => 'No versions found for this document'
            ]);
        }

        // Lấy comments của version mới nhất
        $current_comments = $latestVersion->comments->map(function($comment) {
            return [
                'comment_id' => $comment->id,
                'content' => $comment->comment,
                'document_version_id' => $comment->document_version_id,
                'version' => $comment->documentVersion->version ?? null,
                'user' => $comment->user ? $comment->user->only(['id', 'name', 'avatar']) : null,
                'commented_at' => $comment->created_at,
            ];
        });

        // Lấy comments của các versions khác (không phải version mới nhất)
        $otherVersions = DocumentVersion::with(['comments.user:id,name,avatar'])
            ->where('document_id', $id)
            ->where('id', '!=', $latestVersion->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $other_comments = collect();
        foreach ($otherVersions as $version) {
            $versionComments = $version->comments->map(function($comment) {
                return [
                    'comment_id' => $comment->id,
                    'content' => $comment->comment,
                    'document_version_id' => $comment->document_version_id,
                    'version' => $comment->documentVersion->version ?? null,
                    'user' => $comment->user ? $comment->user->only(['id', 'name', 'avatar']) : null,
                    'commented_at' => $comment->created_at,
                ];
            });
            $other_comments = $other_comments->merge($versionComments);
        }

        // Sắp xếp other_comments theo thời gian comment mới nhất
        $other_comments = $other_comments->sortByDesc('commented_at')->values();

        return response()->json([
            'document_id' => $id,
            'latest_version_id' => $latestVersion->id,
            'total_current_comments' => $current_comments->count(),
            'total_other_comments' => $other_comments->count(),
            'total_comments' => $current_comments->count() + $other_comments->count(),
            'current_comments' => $current_comments,
            'other_comments' => $other_comments,
        ]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function storeComment(Request $request, $document_id)
    {
        $user = auth()->user();
        $document = Document::find($document_id);
        
        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        // Tìm version mới nhất của document
        $latestVersion = DocumentVersion::where('document_id', $document_id)
            ->latest('created_at')
            ->first();

        if (!$latestVersion) {
            return response()->json(['error' => 'No version found for this document'], 404);
        }

        // Validate request
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        \DB::beginTransaction();

        try {
            // Tạo comment mới cho version mới nhất
            $comment = DocumentComment::create([
                'document_version_id' => $latestVersion->id,
                'user_id' => $user->id,
                'comment' => $request['comment'],
                'created_at' => now(),
            ]);

            // Tìm thông tin người tạo document từ user_id
            $creatorInfo = $this->getCreatorInfoByUserId($document->created_by);
            $creator = User::find($document->created_by);

            // Gửi notification cho admins
            $admins = User::where('role_id', '1')->get();
            foreach ($admins as $admin) {
                // Không gửi cho chính người comment
                if ($admin->id != $user->id) {
                    $notification = Notification::create([
                        'notification_category_id' => 2,
                        'from_user_id' => $user->id,
                        'receiver_id' => $admin->id,
                        'title' => "Nhận xét văn bản",
                        'content' => "Nhận xét về văn bản '" . $document->title . "' của " . ($creator ? $creator->name : 'N/A'),
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Send real-time notification
                    $this->sendPusherNotification($admin->id, $notification, $document);
                }
            }

            // Lấy tất cả người trong luồng phê duyệt của document
            $approversInFlow = collect();
            if ($document->documentFlow) {
                $flowSteps = $document->documentFlow->documentFlowSteps()
                    ->with('approver.user:id,name')
                    ->get();

                foreach ($flowSteps as $step) {
                    if ($step->approver && $step->approver->user) {
                        $approversInFlow->push($step->approver->user);
                    }
                }
            }

            // Gửi notification cho tất cả người trong luồng phê duyệt
            foreach ($approversInFlow->unique('id') as $approver) {
                if ($approver->id != $user->id && !$admins->contains('id', $approver->id)) {
                    $notification = Notification::create([
                        'notification_category_id' => 2,
                        'from_user_id' => $user->id,
                        'receiver_id' => $approver->id,
                        'title' => "Nhận xét văn bản",
                        'content' => "Có nhận xét mới về văn bản '" . $document->title . "' trong luồng phê duyệt",
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Send real-time notification
                    $this->sendPusherNotification($approver->id, $notification, $document);
                }
            }

            // Gửi notification cho người tạo document (nếu không phải chính người comment và không phải admin)
            if ($document->created_by != $user->id && !$admins->contains('id', $document->created_by)) {
                $notification = Notification::create([
                    'notification_category_id' => 2,
                    'receiver_id' => $document->created_by,
                    'title' => "Nhận xét văn bản",
                    'content' => "Nhận xét về văn bản '" . $document->title . "' của bạn",
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Send real-time notification
                $this->sendPusherNotification($document->created_by, $notification, $document);
            }

            \DB::commit();

            // Load user info for response
            $comment->load('user:id,name,avatar');

            return response()->json([
                'message' => 'Comment saved successfully',
                'comment' => [
                    'comment_id' => $comment->id,
                    'content' => $comment->comment,
                    'document_version_id' => $comment->document_version_id,
                    'version' => $latestVersion->version,
                    'user' => $comment->user->only(['id', 'name', 'avatar']),
                    'commented_at' => $comment->created_at,
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to save comment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save comment'], 500);
        }
    }

    /**
     * Helper method để gửi Pusher notification
     */
    private function sendPusherNotification($userId, $notification, $document)
    {
        try {
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

            $data = [
                'notification' => $notification,
                'document' => $document
            ];
            
            $pusher->trigger('user.' . $userId, 'new-notification', $data);
        } catch (\Exception $e) {
            \Log::error('Failed to send pusher notification: ' . $e->getMessage());
            // Không throw exception để không ảnh hưởng đến việc lưu comment
        }
    }

    /**
     * Lấy thông tin người tạo từ user_id - tìm trong creators hoặc approvers
     */
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

}
