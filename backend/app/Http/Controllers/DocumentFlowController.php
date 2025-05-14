<?php

namespace App\Http\Controllers;

use App\Models\DocumentFlow;
use Illuminate\Http\Request;

class DocumentFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentFlows = \DB::table('document_flows')
            ->select(
                'id as value',
                'name as label',
            )
            ->where('is_active', true)
            ->where('is_template', true)
            ->get();
        return response()->json([
            'document_flow_templates' => $documentFlows,
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
}
