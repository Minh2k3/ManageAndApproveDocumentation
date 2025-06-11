<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
    {
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'public_key' => $this->public_key,
                'private_key' => $this->private_key,
                'certificate' => $this->certificate,
                'issued_at' => $this->issued_at,
                'expires_at' => $this->expires_at,
                'status' => $this->status,
                'required_renewal' => $this->required_renewal,
                'used_count' => $this->used_count,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'user' => $this->when($this->user, [
                    'id' => $this->user?->id,
                    'name' => $this->user?->name,
                    'email' => $this->user?->email,
                    'avatar' => $this->user?->avatar,
                    'role_id' => $this->user?->role_id,
                    'position_title' => $this->getUserPositionTitle(),
                    'position_details' => $this->getUserPositionDetails(),
                ])
            ];
        }

        private function getUserPositionTitle()
        {
            if (!$this->user) {
                return null;
            }

            switch ($this->user->role_id) {
                case 1: // Admin
                    return 'Admin';
                    
                case 2: // Creator
                    $position = $this->user->creator?->rollAtDepartment?->name ?? '';
                    $department = $this->user->creator?->department?->name ?? '';
                    
                    if ($position && $department) {
                        return trim($position . ' - ' . $department);
                    }
                    return $position ?: $department ?: 'Creator';
                    
                case 3: // Approver
                    $position = $this->user->approver?->rollAtDepartment?->name ?? '';
                    $department = $this->user->approver?->department?->name ?? '';
                    
                    if ($position && $department) {
                        return trim($position . ' - ' . $department);
                    }
                    return $position ?: $department ?: 'Approver';
                    
                default:
                    return 'Unknown Role';
            }
        }

        private function getUserPositionDetails()
        {
            if (!$this->user) {
                return null;
            }

            switch ($this->user->role_id) {
                case 1: // Admin
                    return [
                        'role_type' => 'admin',
                        'position' => 'Admin'
                    ];
                    
                case 2: // Creator
                    return [
                        'role_type' => 'creator',
                        'roll_at_department' => $this->user->creator?->rollAtDepartment ? [
                            'id' => $this->user->creator->rollAtDepartment->id,
                            'name' => $this->user->creator->rollAtDepartment->name,
                        ] : null,
                        'department' => $this->user->creator?->department ? [
                            'id' => $this->user->creator->department->id,
                            'name' => $this->user->creator->department->name,
                        ] : null
                    ];
                    
                case 3: // Approver
                    return [
                        'role_type' => 'approver',
                        'roll_at_department' => $this->user->approver?->rollAtDepartment ? [
                            'id' => $this->user->approver->rollAtDepartment->id,
                            'name' => $this->user->approver->rollAtDepartment->name,
                        ] : null,
                        'department' => $this->user->approver?->department ? [
                            'id' => $this->user->approver->department->id,
                            'name' => $this->user->approver->department->name,
                        ] : null
                    ];
                    
                default:
                    return ['role_type' => 'unknown'];
            }
        }
    }
