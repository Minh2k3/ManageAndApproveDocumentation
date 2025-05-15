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
            ->orderBy('documents.created_at', 'desc')
            ->get();

        return response()->json([
            'documents' => $documents,
        ]);
    }

    public function storeDraftDocument(Request $request)
    {
        // $pdfPath = $request->file('upload_files')->storeAs(
        //     '', // Thư mục con (rỗng vì đã set trong filesystems.php)
        //     Str::uuid() . '.' . $request->file('pdf_file')->getClientOriginalExtension(),
        //     'public'
        // );

        $pdfPath = "Hello.txt";

        $document = $request['document'];
        $document_flow = $request['document_flow'];
        $document_flow_step = $document_flow['current_flow_step'];

        \DB::beginTransaction();

        try {
            $new_document_flow = DocumentFlow::create([
                'name' => $document_flow['document_flow_name'],
                'created_by' => $document_flow['created_by'],
                'is_active' => false,
                'is_template' => true,
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

            Document::create([
                'title' => $document['title'],
                'description' => $document['description'],
                'file_path' => $pdfPath,
                'document_type_id' => $document['document_type_id'],
                'created_by' => $document['created_by'],
                'document_flow_id' => $new_document_flow['id'],
                'status' => 'draft',
                'is_public' => $document['is_public'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Lỗi khi lưu bản nháp: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Bản nháp đã được lưu thành công.',
        ]);
    }

    public function storeRequestDocument(Request $request)
    {
        $pdfPath = "test.pdf";

        $document = $request['document'];
        $document_flow = $request['document_flow'];
        $document_flow_step = $document_flow['current_flow_step'];

        \DB::beginTransaction();

        try {
            $new_document_flow = DocumentFlow::create([
                'name' => $document_flow['document_flow_name'],
                'created_by' => $document_flow['created_by'],
                'is_active' => false,
                'is_template' => true,
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
                'file_path' => $pdfPath,
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
                'file_path' => $pdfPath,
                'created_at' => now(),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Lỗi khi lưu bản nháp: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Bản nháp đã được lưu thành công.',
        ]);
    }
}
