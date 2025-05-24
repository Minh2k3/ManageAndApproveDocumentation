<?php

namespace App\Http\Controllers;

use App\Models\DocumentFlowStep;
use Illuminate\Http\Request;

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
    public function approveStep($id) 
    {
        $documentFlowStep = DocumentFlowStep::findOrFail($id);
        $documentFlowStep->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Document flow step approved successfully.',
            'document_flow_step' => $documentFlowStep,
        ])->setStatusCode(200, 'Document flow step approved successfully.');
    }
}
