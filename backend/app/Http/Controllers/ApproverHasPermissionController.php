<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproverHasPermission;
use App\Models\Approver;
use App\Models\DocumentType;
use App\Models\RollAtDepartment;

class ApproverHasPermissionController extends Controller
{
    public function index() 
    {
        $approverHasPermissions = ApproverHasPermission::with([
            'approver.user:id,name,email,phone,avatar,status',
            'documentType:id,name,description',
        ])->get();

        return response()->json([
            'approver_has_permissions' => $approverHasPermissions,
        ])->setStatusCode(200, 'Approver permissions retrieved successfully.');
    }

    public function update($approver_id, Request $request)
    {
        $permissions = $request->input('permissions', []);
        try {
            // Truncate existing permissions
            ApproverHasPermission::where('approver_id', $approver_id)->delete();
            \Log::info("Deleted existing permissions for approver ID: $approver_id");
            // Create new permissions
            foreach ($permissions as $permission) {
                ApproverHasPermission::create([
                    'approver_id' => $approver_id,
                    'document_type_id' => $permission,
                    'created_at' => now()
                ]);
                \Log::info("Created permission for approver ID: $approver_id, Document Type ID: {$permission}");
            }
        } catch (\Throwable $th) {
            \Log::error("Error updating permissions for approver ID: $approver_id. Error: " . $th->getMessage());
            return response()->json(['message' => 'Error updating permissions: ' . $th->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permissions updated successfully',
        ])->setStatusCode(200, 'Permissions updated successfully.');
    }

    public function create($id, $level) 
    {
        $documentTypes = DB::table('document_types')->select('id')->get();

        foreach ($documentTypes as $documentType) {
            $documentTypeId = $documentType->id;
            if ($approver->level == 1 || ($documentTypeId != 1 && $documentTypeId != 2)) {
                DB::table('approver_has_permissions')->insert([
                    'approver_id' => $id,
                    'document_type_id' => $documentTypeId,
                    'created_at' => now()
                ]);
            }
        }

        return response()->json([
            'message' => 'Permissions created successfully',
        ])->setStatusCode(201, 'Permissions created successfully.');
    }
}
