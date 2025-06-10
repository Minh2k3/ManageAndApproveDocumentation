<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTemplateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file_path' => $this->file_path,
            'description' => $this->description,
            'status' => $this->status,
            'document_type_id' => $this->document_type_id,
            'downloaded' => $this->downloaded,
            'liked' => $this->liked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => $this->when($this->creator, [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
                'avatar' => $this->creator?->avatar,
                'role_id' => $this->creator?->role_id,
                'position_title' => $this->getCreatorPositionTitle(),
                'position_details' => $this->getCreatorPositionDetails(),
            ]),
            'document_type' => $this->when($this->documentType, [
                'id' => $this->documentType?->id,
                'name' => $this->documentType?->name,
                'description' => $this->documentType?->description,
            ])
        ];
    }

    private function getCreatorPositionTitle()
    {
        if (!$this->creator) {
            return null;
        }

        switch ($this->creator->role_id) {
            case 1: // Admin
                return 'Admin';
                
            case 2: // Creator
                $position = $this->creator->creator?->rollAtDepartment?->name ?? '';
                $department = $this->creator->creator?->department?->name ?? '';
                
                if ($position && $department) {
                    return trim($position . ' - ' . $department);
                }
                return $position ?: $department ?: 'Creator';
                
            case 3: // Approver
                $position = $this->creator->approver?->rollAtDepartment?->name ?? '';
                $department = $this->creator->approver?->department?->name ?? '';
                
                if ($position && $department) {
                    return trim($position . ' - ' . $department);
                }
                return $position ?: $department ?: 'Approver';
                
            default:
                return 'Unknown Role';
        }
    }

    private function getCreatorPositionDetails()
    {
        if (!$this->creator) {
            return null;
        }

        switch ($this->creator->role_id) {
            case 1: // Admin
                return [
                    'role_type' => 'admin',
                    'position' => 'Admin'
                ];
                
            case 2: // Creator
                return [
                    'role_type' => 'creator',
                    'roll_at_department' => $this->creator->creator?->rollAtDepartment ? [
                        'id' => $this->creator->creator->rollAtDepartment->id,
                        'name' => $this->creator->creator->rollAtDepartment->name,
                    ] : null,
                    'department' => $this->creator->creator?->department ? [
                        'id' => $this->creator->creator->department->id,
                        'name' => $this->creator->creator->department->name,
                    ] : null
                ];
                
            case 3: // Approver
                return [
                    'role_type' => 'approver',
                    'roll_at_department' => $this->creator->approver?->rollAtDepartment ? [
                        'id' => $this->creator->approver->rollAtDepartment->id,
                        'name' => $this->creator->approver->rollAtDepartment->name,
                    ] : null,
                    'department' => $this->creator->approver?->department ? [
                        'id' => $this->creator->approver->department->id,
                        'name' => $this->creator->approver->department->name,
                    ] : null
                ];
                
            default:
                return ['role_type' => 'unknown'];
        }
    }
}
