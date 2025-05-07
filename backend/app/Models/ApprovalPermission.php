<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\NonAdmin;
use App\Models\DocumentType;
use App\Models\User;

/**
 * ApprovalPermission Model
 *
 * This model represents a permission granted to a user to approve documents.
 * It contains information about the user, the document type, and the person who granted the permission.
 */

class ApprovalPermission extends Model
{
    use HasFactory;

    protected $table = 'approval_permissions';

    protected $fillable = [
        'user_id',
        'document_type_id',
        'granted_by',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(NonAdmin::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }
}
