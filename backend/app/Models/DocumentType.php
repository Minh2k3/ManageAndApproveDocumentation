<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\ApprovalPermission;
use App\Models\ApproverHasPermission;
use App\Models\Approver;
use App\Models\RollAtDepartment;

class DocumentType extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the approval permissions for the document type.
     */
    public function approvalPermissions()
    {
        return $this->hasMany(ApprovalPermission::class);
    }

    /**
     * Get the documents for the document type.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the document templates for the document type.
     */
    public function documentTemplates()
    {
        return $this->hasMany(DocumentTemplate::class);
    }

    /**
     * Relationship với RollAtDepartment thông qua ApprovalPermission
     */
    public function rollAtDepartments()
    {
        return $this->belongsToMany(
            RollAtDepartment::class,
            'approval_permissions',
            'document_type_id',
            'roll_at_department_id'
        );
    }

    /**
     * Get the approvers associated with the document type through permissions.
     */
    public function approvers()
    {
        return $this->belongsToMany(
            Approver::class,
            'approver_has_permissions',
            'document_type_id',
            'approver_id'
        );  
    }

    /**
     * Get the permissions associated with the document type.
     */
    public function permissions()
    {
        return $this->hasMany(ApproverHasPermission::class, 'document_type_id');
    }
}
