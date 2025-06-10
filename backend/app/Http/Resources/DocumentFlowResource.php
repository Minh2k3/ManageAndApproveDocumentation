<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentFlowResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => $this->id, // For compatibility with your original structure
            'name' => $this->name,
            'label' => $this->name, // For compatibility with your original structure
            'description' => $this->description,
            'is_active' => $this->is_active,
            'is_template' => $this->is_template,
            'process' => $this->process,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->when($this->createdBy, [
                'id' => $this->createdBy?->id,
                'name' => $this->createdBy?->name,
                'avatar' => $this->createdBy?->avatar,
                'role_id' => $this->createdBy?->role_id,
                'position_title' => $this->getCreatedByPositionTitle(),
                'position_details' => $this->getCreatedByPositionDetails(),
            ]),
            'document_flow_steps' => $this->when($this->documentFlowSteps, 
                $this->documentFlowSteps->map(function ($step) {
                    return [
                        'id' => $step->id,
                        'document_flow_id' => $step->document_flow_id,
                        'step' => $step->step,
                        'department_id' => $step->department_id,
                        'approver_id' => $step->approver_id,
                        'multichoice' => $step->multichoice,
                    ];
                })
            )
        ];
    }

    private function getCreatedByPositionTitle()
    {
        if (!$this->createdBy) {
            return null;
        }

        switch ($this->createdBy->role_id) {
            case 1: // Admin
                return 'Admin';
                
            case 2: // Creator
                $position = $this->createdBy->creator?->rollAtDepartment?->name ?? '';
                $department = $this->createdBy->creator?->department?->name ?? '';
                
                if ($position && $department) {
                    return trim($position . ' - ' . $department);
                }
                return $position ?: $department ?: 'Creator';
                
            case 3: // Approver
                $position = $this->createdBy->approver?->rollAtDepartment?->name ?? '';
                $department = $this->createdBy->approver?->department?->name ?? '';
                
                if ($position && $department) {
                    return trim($position . ' - ' . $department);
                }
                return $position ?: $department ?: 'Approver';
                
            default:
                return 'Unknown Role';
        }
    }

    private function getCreatedByPositionDetails()
    {
        if (!$this->createdBy) {
            return null;
        }

        switch ($this->createdBy->role_id) {
            case 1: // Admin
                return [
                    'role_type' => 'admin',
                    'position' => 'Admin'
                ];
                
            case 2: // Creator
                return [
                    'role_type' => 'creator',
                    'roll_at_department' => $this->createdBy->creator?->rollAtDepartment ? [
                        'id' => $this->createdBy->creator->rollAtDepartment->id,
                        'name' => $this->createdBy->creator->rollAtDepartment->name,
                    ] : null,
                    'department' => $this->createdBy->creator?->department ? [
                        'id' => $this->createdBy->creator->department->id,
                        'name' => $this->createdBy->creator->department->name,
                    ] : null
                ];
                
            case 3: // Approver
                return [
                    'role_type' => 'approver',
                    'roll_at_department' => $this->createdBy->approver?->rollAtDepartment ? [
                        'id' => $this->createdBy->approver->rollAtDepartment->id,
                        'name' => $this->createdBy->approver->rollAtDepartment->name,
                    ] : null,
                    'department' => $this->createdBy->approver?->department ? [
                        'id' => $this->createdBy->approver->department->id,
                        'name' => $this->createdBy->approver->department->name,
                    ] : null
                ];
                
            default:
                return ['role_type' => 'unknown'];
        }
    }
}
