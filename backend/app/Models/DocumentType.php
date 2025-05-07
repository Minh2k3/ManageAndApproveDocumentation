<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\ApprovalPermission;

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
}
