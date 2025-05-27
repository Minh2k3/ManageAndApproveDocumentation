<?php

namespace App\Http\Controllers;

use App\Models\DocumentFlowStep;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;
use App\Models\Notification;
use Pusher\Pusher;


class DocumentFlowStepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentFlowSteps = DocumentFlowStep::all();
        return response()->json($documentFlowSteps);
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
        $documentFlowStep = DocumentFlowStep::create($request->all());

        return response()->json([
            'message' => 'Document flow step created successfully.',
            'document_flow_step' => $documentFlowStep,
        ])->setStatusCode(201, 'Document flow step created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentFlowStep $documentFlowStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentFlowStep $documentFlowStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentFlowStep $documentFlowStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentFlowStep $documentFlowStep)
    {
        //
    }

    /**
     * Get all step of a document flow
     */
    public function getStepsByDocumentFlowId($documentFlowId)
    {
        $steps = DocumentFlowStep::where('document_flow_id', $documentFlowId)
        ->select(
            'document_flow_id',
            'step',
            'multichoice',
            'department_id',
        )
        ->get();
        return response()->json([
            'document_flow_steps' => $steps,
        ])->setStatusCode(200, 'Document flow steps retrieved successfully.');
    }

    /**
     * Approve a document flow step
     */
    public function approveStep(Request $request, $id) 
    {
        $document = Document::findOrFail($request['document_id']);
        $documentFlowStep = DocumentFlowStep::findOrFail($id);
        $documentFlowStep->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Document flow step approved successfully.',
            'document_flow_step' => $documentFlowStep,
        ])->setStatusCode(200, 'Document flow step approved successfully.');
    }

    /**
     * Reject a document flow step
     */
    public function rejectStep(Request $request, $id)
    {
        \DB::beginTransaction();

        $document_version_id = $request['document_version_id'];
        $document_version = DocumentVersion::where('id', $document_version_id)
            ->lockForUpdate()
            ->first();
            
        if ($document_version->status !== 'in_review') {
            return response()->json([
                'message' => 'Document version is not in review status.',
            ])->setStatusCode(400, 'Bad Request');
        }

        $document = Document::where('id', $document_version['document_id'])
            ->lockForUpdate()
            ->first();

        $creator_id = $document->created_by;
        $reason = $request['reason'];
        $document_flow_step = DocumentFlowStep::findOrFail($id);   

        try {
            $document_version->update(['status' => 'rejected']);
            $document_flow_step->update(['status' => 'rejected']);
            $document_flow_step->update(['decision' => 'rejected']);
            $document_flow_step->update(['reason' => $reason]);
            $document->update(['status' => 'rejected']);

            $user = auth()->user();
            $admins = User::where('role_id', '1')->get();
            foreach ($admins as $admin) {
                $notification = Notification::create([
                    'notification_category_id' => 2,
                    'from_user_id' => $user['id'],
                    'receiver_id' => $admin['id'],
                    'title' => "Từ chối văn bản",
                    'content' => "Từ chối phê duyệt cho văn bản '" . $document['title'] . "'",
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
                'receiver_id' => $creator_id,
                'title' => "Từ chối văn bản",
                'content' => "Từ chối phê duyệt cho văn bản '" . $document['title'] . "' của bạn.",
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
            $pusher->trigger('user.' . $creator_id, 'new-notification', $data);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Failed to reject document.',
                'error' => $e->getMessage(),
            ])->setStatusCode(500, 'Internal Server Error');
        }
        return response()->json([
            'message' => 'Document flow step rejected successfully.',
            'document_flow_step' => $document_flow_step,
        ])->setStatusCode(200, 'Document flow step rejected successfully.');
    }
}
