<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\DocumentTemplate;
use App\Models\DocumentVersion;
use App\Models\DocumentFlow;
use App\Models\DocumentFlowStep;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::all();
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
            ->get();

        return response()->json([
            'documents' => $documents,
        ]);
    }

    public function storeDraftDocument(Request $request)
    {
        $pdfPath = $request->file('file_path')->storeAs(
            '', // Thư mục con (rỗng vì đã set trong filesystems.php)
            Str::uuid() . '.' . $request->file('pdf_file')->getClientOriginalExtension(),
            'public'
        );

        try {
            $document = Document::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'file_path' => $pdfPath,
                'document_type_id' => $request->input('document_type_id'),
                'created_by' => auth()->user()->id,
                'document_flow_id' => null, 
                'status' => 0,
                'is_public' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể lưu nháp vì ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Bản nháp đã được lưu thành công.',
            'document' => $document,
        ]);
    }
}
