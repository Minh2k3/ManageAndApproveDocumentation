<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;

class DocumentTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentTemplates = \DB::table('document_templates')
            ->select(
                'id as value',
                'name as label',
            )
            ->get();
        return response()->json([
            'document_template_templates' => $documentTemplates,
        ])->setStatusCode(200, 'Document templates retrieved successfully.');
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
    public function show(DocumentTemplate $documentTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentTemplate $documentTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentTemplate $documentTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentTemplate $documentTemplate)
    {
        //
    }

    /**
     * Get all active templates.
     */
    public function getAllTemplates()
    {
        $documentTemplates = DocumentTemplate::with('creator:id,name,avatar')
            ->with('documentType:id,name')
            ->select(
                'id',
                'name',
                'file_path',
                'description',
                'is_active',
                'created_at',
                'updated_at'
            )
            ->get();

        return response()->json([
            'document_templates' => $documentTemplates,
        ])->setStatusCode(200, 'Active templates retrieved successfully.');
    }
}
