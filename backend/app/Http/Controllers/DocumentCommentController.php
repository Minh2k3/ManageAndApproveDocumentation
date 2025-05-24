<?php

namespace App\Http\Controllers;

use App\Models\DocumentComment;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use App\Models\DocumentFlowStep;

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
        $document = Document::with([
            'documentFlow.documentFlowSteps.comments',
            'documentFlow.documentFlowSteps.approver.user'
        ])->findOrFail($id);

        $comments = [];

        foreach ($document->documentFlow->documentFlowSteps as $step) {
            $user = optional($step->approver->user)->only(['id', 'name', 'avatar']);

            foreach ($step->comments as $comment) {
                $comments[] = [
                    'comment_id' => $comment->id,
                    'content' => $comment->comment,
                    'step_id' => $step->id,
                    'user' => $user,
                    'commented_at' => $comment->created_at,
                ];
            }
        }

        return response()->json([
            'document_id' => $document->id,
            'total_comments' => count($comments),
            'comments' => $comments,
        ]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function storeComment(Request $request, $document_flow_step_id)
    {
        $step = DocumentFlowStep::find($document_flow_step_id);
        $creator_of_document_id = $request['creator_id'];
        $creator = User::find($creator_of_document_id);
        $user = auth()->user();
        $document = Document::find($request['document_id']);

        \DB::beginTransaction();

        try {
            $step->comments()->create([
                'document_flow_step_id' => $document_flow_step_id,
                'content' => $request->comment,
            ]);

            $admins = User::where('role_id', '1')->get();
            foreach ($admins as $admin) {
                $notification = Notification::create([
                    'notification_category_id' => 2,
                    'from_user_id' => $user['id'],
                    'receiver_id' => $admin['id'],
                    'title' => "Nhận xét văn bản",
                    'content' => "Nhận xét về văn bản '" . $document['title'] . "' của " . $creator['name'],
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
                $data['document'] = $document;
                $pusher->trigger('user.' . $admin['id'], 'new-notification', $data);
            }

            $notification = Notification::create([
                    'notification_category_id' => 2,
                    'from_user_id' => $user['id'],
                    'receiver_id' => $creator['id'],
                    'title' => "Nhận xét văn bản",
                    'content' => "Nhận xét về văn bản '" . $document['title'] . "' của bạn",
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
            $data['document'] = $document;
            $pusher->trigger('user.' . $creator['id'], 'new-notification', $data);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to save comment'], 500);
        }

        return response()->json([
            'message' => 'Comment saved successfully',
            'comment' => $request->comment,
        ]);
    }

}
