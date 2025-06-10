<?php

namespace App\Http\Controllers;

use App\Models\DocumentFlow;
use Illuminate\Http\Request;

use App\Http\Resources\DocumentFlowResource;

use App\Models\DocumentFlowStep;

class DocumentFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentFlows = DocumentFlow::with([
            'documentFlowSteps' => function($query) {
                $query->select('id', 'document_flow_id', 'step', 'department_id', 'approver_id', 'multichoice')
                    ->orderBy('step', 'asc');
            },
            'createdBy.role', // Load role để biết user thuộc loại nào
            'createdBy.creator.rollAtDepartment', // Load rollAtDepartment cho creator
            'createdBy.creator.department', // Load department cho creator
            'createdBy.approver.rollAtDepartment', // Load rollAtDepartment cho approver
            'createdBy.approver.department', // Load department cho approver
        ])
        ->select(
            'id',
            'name',
            'description',
            'created_by',
            'is_active',
            'is_template',
            'process',
            'created_at',
            'updated_at'
        )
        ->where('is_template', true)
        ->orderBy('updated_at', 'desc')
        ->get();

        return response()->json([
            'document_flow_templates' => DocumentFlowResource::collection($documentFlows),
        ])->setStatusCode(200, 'Document flows retrieved successfully.');
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
        $documentFlow = DocumentFlow::create($request->all());

        return response()->json([
            'message' => 'Document flow created successfully.',
            'document_flow' => $documentFlow,
        ])->setStatusCode(201, 'Document flow created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $documentFlow = DocumentFlow::find($id);
        if ($documentFlow) {
            return response()->json($documentFlow);
        } else {
            return response()->json(['message' => 'Document Flow not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentFlow $documentFlow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentFlow $documentFlow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentFlow $documentFlow)
    {
        //
    }

    public function getStepsByDocumentFlow($id)
    {
        $documentFlow = DocumentFlow::with([
            'documentFlowSteps' => function($query) {
                $query->orderBy('step', 'asc');
            },
            'documentFlowSteps.department:id,name',
            'documentFlowSteps.approver.user:id,name',
            'documentFlowSteps.approver.department:id,name',
            'documentFlowSteps.approver.rollAtDepartment:id,name'
        ])->find($id);

        if (!$documentFlow) {
            return response()->json(['message' => 'Document Flow not found'], 404);
        }

        // Đếm số bước đã được chấp thuận
        $approvedStepsCount = $documentFlow->documentFlowSteps
            ->where('decision', 'approved')
            ->count();

        $steps = $documentFlow->documentFlowSteps->map(function($step) {
            $approverInfo = null;
            
            if ($step->approver) {
                $approverInfo = [
                    'id' => $step->approver->id,
                    'name' => $step->approver->user->name ?? null,
                    'department' => [
                        'id' => $step->approver->department->id ?? null,
                        'name' => $step->approver->department->name ?? null,
                    ],
                    'roll' => $step->approver->rollAtDepartment ? [
                        'id' => $step->approver->rollAtDepartment->id,
                        'name' => $step->approver->rollAtDepartment->name,
                    ] : null,
                    'roll_display' => $step->approver->rollAtDepartment && $step->approver->department 
                        ? $step->approver->rollAtDepartment->name . ' - ' . $step->approver->department->name 
                        : null,
                ];
            }

            return [
                'id' => $step->id,
                'step' => $step->step,
                'department' => [
                    'id' => $step->department->id ?? null,
                    'name' => $step->department->name ?? null,
                ],
                'approver' => $approverInfo,
                'multichoice' => (bool) $step->multichoice,
                'status' => $step->status,
                'decision' => $step->decision,
                'signed_at' => $step->signed_at,
                'created_at' => $step->created_at,
                'updated_at' => $step->updated_at,
            ];
        });

        return response()->json([
            'document_flow_id' => $documentFlow->id,
            'document_flow_name' => $documentFlow->name,
            'total_steps' => $steps->count(),
            'process' => $approvedStepsCount, // Số bước đã chấp thuận
            'approved_steps' => $approvedStepsCount, // Alias rõ ràng hơn
            'progress_percentage' => $steps->count() > 0 ? round(($approvedStepsCount / $steps->count()) * 100, 2) : 0,
            'is_completed' => $approvedStepsCount == $steps->count() && $steps->count() > 0,
            'steps' => $steps
        ]);
    }

    public function createFlowTemplate(Request $request)
    {
        \DB::beginTransaction();

        try {
            $documentFlow = DocumentFlow::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $request->created_by,
                'is_template' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'process' => $request->process ?? 0,
            ]);

            foreach ($request->steps as $step) {
                $documentFlow->documentFlowSteps()->create([
                    'step' => $step['step'],
                    'department_id' => $step['department_id'],
                    'approver_id' => $step['approver_id'] ?? null,
                    'multichoice' => $step['multichoice'] ?? false,
                ]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Failed to create document flow template.',
                'error' => $e->getMessage(),
            ])->setStatusCode(500, 'Failed to create document flow template.');
        }

        return response()->json([
            'message' => 'Document flow template created successfully.',
            'document_flow_template_id' => $documentFlow->id,
        ])->setStatusCode(201, 'Document flow template created successfully.');
    }
}
