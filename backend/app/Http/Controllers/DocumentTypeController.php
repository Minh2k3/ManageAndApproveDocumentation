<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\ApprovalPermission;
use App\Models\RollAtDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentTypes = DocumentType::select([
            'document_types.id',
            'document_types.id as value',
            'document_types.name',
            'document_types.name as label',
            'document_types.description',
            'document_types.is_active'
        ])
        ->withCount('documents')
        ->with(['rollAtDepartments' => function ($query) {
            $query->select('roll_at_departments.id', 'roll_at_departments.name', 'roll_at_departments.description');
        }])
        ->get();
    
        return response()->json([
            'document_types' => $documentTypes,
        ])->setStatusCode(200, 'Document types retrieved successfully.');
    }

    public function getActiveDocumentTypes()
    {
        $documentTypes = DocumentType::where('is_active', true)
            ->select([
                'id as value',
                'name as label',
            ])
            ->get();

        return response()->json([
            'document_types' => $documentTypes,
        ])->setStatusCode(200, 'Active document types retrieved successfully.');
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
        $data = $request['formData'] ?? $request->all();
        \Log::info('Store Document Type Request Data:', $data);

        $documentType = DocumentType::create($data->only(['name', 'description', 'is_active']));

        return response()->json([
            'message' => 'Document type created successfully.',
            'document_type' => $documentType,
        ])->setStatusCode(201, 'Document type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // \Log::info('Update Document Type Request:', $request->all());
        $data = $request['formData'] ?? $request->all();
        // \Log::info('Update Document Type Request Data:', $data);
        // return response()->json([
        //     'message' => 'Update Document Type Request Data',
        //     'data' => $data,
        // ])->setStatusCode(200, 'Update Document Type Request Data');

        try {
            $documentType = DocumentType::findOrFail($data['id']);
            if (!$documentType) {
                return response()->json([
                    'error' => 'Document type not found.',
                ], 404);
            }
            $updateData = Arr::only($data, ['name', 'description', 'is_active']);
            $documentType->update($updateData);
            \Log::info('Document Type Updated:', $documentType->toArray());

            // Lấy dữ liệu từ request
            $rollAtDepartments = $data['roll_at_departments'] ?? [];
            \Log::info('Roll At Departments:', $rollAtDepartments);
            $now = Carbon::now();

            // Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
            DB::transaction(function () use ($documentType, $rollAtDepartments, $now) {
                // Bước 1: Xóa tất cả permissions cũ
                DB::table('approval_permissions')
                    ->where('document_type_id', $documentType->id)
                    ->delete();
                
                // Bước 2: Chỉ insert nếu có dữ liệu
                if (is_array($rollAtDepartments) && count($rollAtDepartments) > 0) {
                    $permissionsData = collect($rollAtDepartments)->map(function ($rollId) use ($documentType, $now) {
                        return [
                            'roll_at_department_id' => $rollId,
                            'document_type_id' => $documentType->id,
                            'created_at' => $now,
                            'ended_at' => null,
                        ];
                    })->toArray();
                    \Log::info('Inserting Approval Permissions:', $permissionsData);
                    
                    DB::table('approval_permissions')->insert($permissionsData);
                    \Log::info('Approval Permissions inserted successfully.');
                }
            });

            return response()->json([
                'message' => 'Document type updated successfully.',
                'document_type' => $documentType,
            ])->setStatusCode(200, 'Document type updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid data provided.',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        try {
            $documentType->delete();

            return response()->json([
                'message' => 'Document type deleted successfully.',
            ])->setStatusCode(200, 'Document type deleted successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to delete document type.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
