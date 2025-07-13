<?php

namespace App\Http\Controllers;

use App\Models\DocumentFlowStep;
use App\Models\DocumentFlow;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;
use App\Models\Notification;
use App\Models\DocumentComment;
use App\Models\ApprovalPermission;
use App\Models\Department;
use App\Models\RollAtDepartment;
use App\Models\Approver;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class DocumentFlowStepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentFlowSteps = DocumentFlowStep::with(['approver.user', 'department'])
            ->paginate(20);
            
        return response()->json($documentFlowSteps);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_flow_id' => 'required|exists:document_flows,id',
            'step' => 'required|integer|min:1',
            'department_id' => 'required|exists:departments,id',
            'approver_id' => 'nullable|exists:approvers,id',
            'multichoice' => 'boolean',
            'status' => 'in:pending,in_review,approved,rejected'
        ]);

        $documentFlowStep = DocumentFlowStep::create($validated);

        return response()->json([
            'message' => 'Document flow step created successfully.',
            'document_flow_step' => $documentFlowStep->load(['approver.user', 'department'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $documentFlowStep = DocumentFlowStep::with([
            'approver.user',
            'department',
            'documentFlow',
            'comments'
        ])->findOrFail($id);

        return response()->json($documentFlowStep);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $documentFlowStep = DocumentFlowStep::findOrFail($id);
        
        $validated = $request->validate([
            'approver_id' => 'nullable|exists:approvers,id',
            'multichoice' => 'boolean'
        ]);

        $documentFlowStep->update($validated);

        return response()->json([
            'message' => 'Document flow step updated successfully.',
            'document_flow_step' => $documentFlowStep->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $documentFlowStep = DocumentFlowStep::findOrFail($id);
        
        // Check if can delete
        if ($documentFlowStep->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot delete a step that has been processed.'
            ], 400);
        }
        
        $documentFlowStep->delete();

        return response()->json([
            'message' => 'Document flow step deleted successfully.'
        ]);
    }

    /**
     * Get all steps of a document flow
     */
    public function getStepsByDocumentFlowId($documentFlowId)
    {
        $steps = DocumentFlowStep::where('document_flow_id', $documentFlowId)
            ->with(['approver.user', 'department'])
            ->orderBy('step')
            ->get();
            
        return response()->json([
            'document_flow_steps' => $steps
        ]);
    }

    /**
     * Approve a document flow step
     */
    public function approveStep($id)
    {
        // $validated = $request->validate([
        //     'comment' => 'nullable|string|max:1000'
        // ]);

        DB::beginTransaction();
        
        try {
            // 1. Lock và kiểm tra document flow step hiện tại
            $currentStep = DocumentFlowStep::where('id', $id)
                ->lockForUpdate()
                ->first();
                
            if (!$currentStep) {
                throw new \Exception('Document flow step not found.', 404);
            }
            
            // Kiểm tra quyền
            $checkPermission = $this->authorizeApproval($currentStep);
            if (!$checkPermission) {
                throw new \Exception('You do not have permission to approve this step.', 403);
            }
            \Log::info('Current step status: ' . $currentStep->status);
            
            // Kiểm tra trạng thái hiện tại
            if ($currentStep->status !== 'in_review') {
                throw new \Exception('This step has already been processed.', 400);
            }
            
            // Lock các resources liên quan
            $resources = $this->lockRelatedResources($currentStep);
            \Log::info('Resources locked successfully.');
            
            // 2. Cập nhật trạng thái và decision của step hiện tại
            $currentStep->update([
                'status' => 'approved',
                'decision' => 'approved',
                'signed_at' => now()
            ]);
            \Log::info('Current step updated to approved.');
            if ($currentStep['approver_id'] == null) {
                $user = auth()->user();
                $currentStep->approver_id = $user->approver->id;
            }
            $currentStep->save();
            // Ký văn bản
            

            
            // if (!empty($validated['comment'])) {
            //     $this->saveComment($currentStep->id, $validated['comment']);
            // }
            
            // 3. Tăng process count trong document_flows
            $resources['documentFlow']->increment('process');
            
            // 4. Xử lý logic multichoice và chuyển step
            // Trả về true khi bước hiện tại đã phê duyệt xong
            $shouldProceedToNext = $this->handleMultichoiceLogic($currentStep);
            \Log::info('Multichoice logic handled. Should proceed to next step: ' . ($shouldProceedToNext ? 'Yes' : 'No'));

            // Kiểm tra xem đã ở bước cuối hay chưa
            $flagIfDone = false;
            if ($shouldProceedToNext) {
                $flagIfDone = $this->processNextStep($currentStep, $resources);
            }

            \Log::info('Next step processed. Should proceed to next step: ' . ($flagIfDone ? 'Yes' : 'No'));
            
            // 5. Gửi thông báo (chỉ gửi cho người tạo và admins)
            // Nếu trả về đúng, tức là chưa phải bước cuối => gửi thông báo phê duyệt
            if ($flagIfDone) {
                $this->sendApprovalNotifications($resources['document'], $currentStep);
            }
            \Log::info('Approval notifications sent.');

            DB::commit();
            
            return response()->json([
                'message' => 'Document flow step approved successfully.',
                'document_flow_step' => $currentStep->fresh(['approver.user', 'department']),
                'document' => $resources['document']->fresh(),
                'document_version' => $resources['documentVersion']->fresh(),
                'should_proceed' => $shouldProceedToNext
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            $statusCode = $e->getCode() ?: 500;
            return response()->json([
                'message' => 'Failed to approve document.',
                'error' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * Reject a document flow step
     */
    public function rejectStep(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        DB::beginTransaction();
        
        try {
            // 1. Lock và kiểm tra document flow step hiện tại
            $currentStep = DocumentFlowStep::where('id', $id)
                ->first();
                
            if (!$currentStep) {
                throw new \Exception('Document flow step not found.', 404);
            }
            
            // Kiểm tra quyền
            // $checkPermission = $this->authorizeApproval($currentStep);
            // if (!$checkPermission) {
            //     throw new \Exception('You do not have permission to reject this step.', 403);
            // }

            \Log::info('Current step status: ' . $currentStep->status);
            
            // Kiểm tra trạng thái
            if ($currentStep->status !== 'in_review') {
                throw new \Exception('This step has already been processed.', 400);
            }
            
            // Lock các resources liên quan
            // $resources = $this->lockRelatedResources($currentStep);
            // \Log::info('Resources locked successfully.');
            
            // 2. Cập nhật trạng thái rejection
            $currentStep->update([
                'status' => 'rejected',
                'decision' => 'rejected',
                'reason' => $validated['reason'],
                'signed_at' => now()
            ]);
            \Log::info('Current step updated to rejected.');

            if ($currentStep['approver_id'] == null) {
                $user = auth()->user();
                $currentStep->approver_id = $user->approver->id;
            }
            $currentStep->save();
            
            // Lưu lý do từ chối
            // $this->saveComment($resources['documentVersion']->id, $validated['reason']);
            
            // 3. Cập nhật tất cả các step sau và document
            // $this->rejectSubsequentSteps($currentStep);
            
            // Update document và version status
            $document_version = DocumentVersion::where('id', $request['document_version_id'])
                ->first();
            if (!$document_version) {
                throw new \Exception('Document version not found.', 404);
            }
            
            $document = Document::where('id', $document_version->document_id)
                ->first();
            if (!$document) {
                throw new \Exception('Document not found.', 404);
            }
            $document->status = 'rejected';
            $document->save();
            $document->refresh();
            $document_version->document_data = $document;
            $document_version->status = 'rejected';
            $document_version->save();
            \Log::info('Document and version updated to rejected.');
            
            // 4. Gửi thông báo
            $this->sendRejectionNotifications($document, $currentStep, $validated['reason']);
            \Log::info('Rejection notifications sent.');
            
            DB::commit();
            
            return response()->json([
                'message' => 'Document flow step rejected successfully.',
                'document_flow_step' => $currentStep->fresh(),
                'document' => $document->fresh()
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            $statusCode = 410;
            return response()->json([
                'message' => 'Failed to reject document.',
                'error' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * Check authorization for approval/rejection
     */
    private function authorizeApproval($step)
    {
        $user = auth()->user();

        $department = Department::find($step->department_id);
        if (!$department || !$department->can_approve) {
            return false;
        }
        
        // Lấy thông tin document để biết document_type_id
        $document = Document::where('document_flow_id', $step->document_flow_id)->first();
        if (!$document) {
            return false;
        }
        
        // Admin không có quyền phê duyệt
        if ($user->role_id == 1) {
            return false;
        }
        
        // Xác định approver cần kiểm tra
        $approver = null;
        
        if ($step->approver_id) {
            // Step có chỉ định approver cụ thể
            $approver = $user->approver;
            if (!$approver || $approver->id !== $step->approver_id) {
                return false;
            }
        } else {
            // Step theo department
            $approver = $user->approver()
                ->where('department_id', $step->department_id)
                ->first();
                
            if (!$approver) {
                return false;
            }
        }
        
        // Kiểm tra quyền ký trong approval_permissions
        $hasPermission = ApprovalPermission::where('roll_at_department_id', $approver->roll_at_department_id)
            ->where('document_type_id', $document->document_type_id)
            ->where(function($query) {
                $query->whereNull('ended_at')
                    ->orWhere('ended_at', '>', now());
            })
            ->exists();
            
        if (!$hasPermission) {
            return false;
        }

        return true;
    }

    /**
     * Lock all related resources
     */
    private function lockRelatedResources($currentStep)
    {
        // Lock document flow
        $documentFlow = DocumentFlow::where('id', $currentStep->document_flow_id)
            ->lockForUpdate()
            ->first();
            
        if (!$documentFlow) {
            throw new \Exception('Document flow not found.', 404);
        }
        
        // Lock document
        $document = Document::where('document_flow_id', $documentFlow->id)
            ->lockForUpdate()
            ->first();
            
        if (!$document) {
            throw new \Exception('Document not found.', 404);
        }
        
        // Lock document version
        $documentVersion = DocumentVersion::where('document_id', $document->id)
            ->where('status', 'in_review')
            ->orderBy('version', 'desc')
            ->lockForUpdate()
            ->first();
            
        if (!$documentVersion) {
            throw new \Exception('No document version in review status.', 400);
        }
        
        return [
            'documentFlow' => $documentFlow,
            'document' => $document,
            'documentVersion' => $documentVersion
        ];
    }

    /**
     * Handle multichoice logic
     */
    private function handleMultichoiceLogic($currentStep)
    {
        if (!$currentStep->multichoice) {
            // Single choice - proceed immediately
            // Set other steps at same level to skipped
            // DocumentFlowStep::where('document_flow_id', $currentStep->document_flow_id)
            //     ->where('step', $currentStep->step)
            //     ->where('id', '!=', $currentStep->id)
            //     ->where('status', 'in_review')
            //     ->update(['status' => 'skipped']);
  
            return true;
        }
        
        // Multichoice - check if all approved
        $sameStepQuery = DocumentFlowStep::where('document_flow_id', $currentStep->document_flow_id)
            ->where('step', $currentStep->step);
            
        $totalCount = $sameStepQuery->count();  // Total steps at this level
        $approvedCount = $sameStepQuery->where('status', 'approved')->count(); // Count of approved steps
        
        return $totalCount === $approvedCount;
    }

    /**
     * Process next step or complete approval
     */
    private function processNextStep($currentStep, $resources)
    {
        // Get next step number
        $nextStepNumber = DocumentFlowStep::where('document_flow_id', $currentStep->document_flow_id)
            ->where('step', '>', $currentStep->step)
            ->min('step');
        \Log::info('Next step number: ' . $nextStepNumber);
            
        if ($nextStepNumber) {
            // Activate next step
            DocumentFlowStep::where('document_flow_id', $currentStep->document_flow_id)
                ->where('step', $nextStepNumber)
                ->update(['status' => 'in_review']);
                
            // Notify next approvers
            $this->notifyNextStepApprovers($currentStep->document_flow_id, $nextStepNumber, $resources['document']);
            \Log::info('Next step activated and notified approvers.');

            return true;
        } else {
            // No more steps - complete approval
            $resources['documentVersion']->update(['status' => 'approved']);
            $resources['document']->update(['status' => 'approved']);
            \Log::info('Document and version updated to approved.');
            
            // Notify completion
            $this->notifyDocumentApproved($resources['document']);
            \Log::info('Document fully approved and notified.');

            return false;
        }
    }

    /**
     * Reject all subsequent steps
     */
    private function rejectSubsequentSteps($currentStep)
    {
        DocumentFlowStep::where('document_flow_id', $currentStep->document_flow_id)
            ->where('step', '>', $currentStep->step)
            ->update(['status' => 'rejected']);
    }

    /**
     * Save comment for a step
     */
    private function saveComment($documentVersionId, $comment)
    {
        DocumentComment::create([
            'document_version_id' => $documentVersionId,
            'user_id' => auth()->id(),
            'comment' => 'Lý do từ chối: ' . $comment,
            'created_at' => now()
        ]);
    }

    /**
     * Send approval notifications
     */
    private function sendApprovalNotifications($document, $approvedStep)
    {
        $pusher = $this->getPusherInstance();
        $currentUser = auth()->user();
        
        // Notify creator
        $this->createAndSendNotification(
            $currentUser->id,
            $document->created_by,
            'Văn bản được phê duyệt',
            "Văn bản '{$document->title}' đã được phê duyệt bởi {$currentUser->name}.",
            $pusher,
            $document,
            'step_approved'
        );
        
        // Notify admins
        $this->notifyAdmins($document, 'Đồng ý phê duyệt', "Đồng ý phê duyệt cho văn bản '{$document->title}'.");
    }

    /**
     * Send rejection notifications
     */
    private function sendRejectionNotifications($document, $rejectedStep, $reason)
    {
        $pusher = $this->getPusherInstance();
        $currentUser = auth()->user();
        
        // Notify creator
        $this->createAndSendNotification(
            $currentUser->id,
            $document->created_by,
            'Văn bản bị từ chối',
            "Văn bản '{$document->title}' đã bị từ chối. Lý do: {$reason}",
            $pusher,
            $document,
            'document_rejected'
        );
        
        // Notify previous approvers
        $this->notifyPreviousApprovers($document, $rejectedStep, $reason);
        
        // Notify admins
        $this->notifyAdmins($document, 'Từ chối phê duyệt', "Từ chối phê duyệt cho văn bản '{$document->title}'.");
    }

    /**
     * Notify approvers at next step
     */
    private function notifyNextStepApprovers($documentFlowId, $nextStepNumber, $document)
    {
        $nextStepApprovers = DocumentFlowStep::where('document_flow_id', $documentFlowId)
            ->where('step', $nextStepNumber)
            ->with('approver.user')
            ->get();
            
        $pusher = $this->getPusherInstance();
        $currentUser = auth()->user();
        
        foreach ($nextStepApprovers as $stepApprover) {
            if ($stepApprover->approver && $stepApprover->approver->user) {
                $this->createAndSendNotification(
                    $currentUser->id,
                    $stepApprover->approver->user_id,
                    'Văn bản cần phê duyệt',
                    "Bạn có văn bản '{$document->title}' cần phê duyệt.",
                    $pusher,
                    $document,
                    'new_approval_request'
                );
            }
        }
    }

    /**
     * Notify when document is fully approved
     */
    private function notifyDocumentApproved($document)
    {
        $pusher = $this->getPusherInstance();
        $currentUser = auth()->user();
        \Log::info('Notifying document fully approved for document ID: ' . $document->id);
        \Log::info('Current user ID: ' . $currentUser->id);
        // Notify creator
        $this->createAndSendNotification(
            $currentUser->id,
            $document->created_by,
            'Văn bản đã được phê duyệt',
            "Văn bản '{$document->title}' của bạn đã được phê duyệt hoàn tất.",
            $pusher,
            $document,
            'document_fully_approved'
        );
        \Log::info('Notification sent to creator for document ID: ' . $document->id);
        
        // Notify admins
        $this->notifyAdmins($document, 'Hoàn tất phê duyệt', "Văn bản '{$document->title}' đã được phê duyệt hoàn tất");
    }

    /**
     * Notify previous approvers when document is rejected
     */
    private function notifyPreviousApprovers($document, $rejectedStep, $reason)
    {
        $previousApprovers = DocumentFlowStep::where('document_flow_id', $rejectedStep->document_flow_id)
            ->where('step', '<=', $rejectedStep->step)
            ->where('status', 'approved')
            ->with('approver.user')
            ->get();
            
        $pusher = $this->getPusherInstance();
        $currentUser = auth()->user();
        
        foreach ($previousApprovers as $approver) {
            if ($approver->approver && $approver->approver->user) {
                $this->createAndSendNotification(
                    $currentUser->id,
                    $approver->approver->user_id,
                    'Văn bản đã duyệt bị từ chối',
                    "Văn bản '{$document->title}' mà bạn đã phê duyệt đã bị từ chối ở bước sau. Lý do: {$reason}",
                    $pusher,
                    $document,
                    'approved_document_rejected'
                );
            }
        }
    }

    /**
     * Notify all admins
     */
    private function notifyAdmins($document, $title, $content)
    {
        $pusher = $this->getPusherInstance();
        $currentUser = auth()->user();
        
        $admins = User::where('role_id', 1)->get();
        
        foreach ($admins as $admin) {
            $this->createAndSendNotification(
                $currentUser->id,
                $admin->id,
                $title,
                $content,
                $pusher,
                $document,
                'system_notification'
            );
        }
    }

    /**
     * Create and send notification helper
     */
    private function createAndSendNotification($fromUserId, $toUserId, $title, $content, $pusher, $document, $type)
    {
        \Log::info("Creating notification from user ID: {$fromUserId} to user ID: {$toUserId}");
        $notification = Notification::create([
            'notification_category_id' => 2,
            'from_user_id' => $fromUserId,
            'receiver_id' => $toUserId,
            'title' => $title,
            'content' => $content,
            'is_read' => false,
            'created_at' => now(),
        ]);
        \Log::info('Notification created with ID: ' . $notification->id);
        
        $data = [
            'notification' => $notification,
            'document' => $document,
            'type' => $type
        ];
        \Log::info('Sending notification to user ID: ' . $toUserId . ' with type: ' . $type);
        
        $pusher->trigger("user.{$toUserId}", 'new-notification', $data);
    }

    /**
     * Get Pusher instance
     */
    private function getPusherInstance()
    {
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ];
        
        return new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
    }
}