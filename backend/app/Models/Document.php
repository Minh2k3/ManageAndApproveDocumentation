<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentType;
use App\Models\DocumentFlow;
use App\Models\NonAdmin;
use App\Models\DocumentVersion;
/**
 * Document Model
 *
 * This model represents a document in the system.
 * It contains information about the document's title, description, type, creator,
 * and whether it is public or not.
 */

class Document extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type_id',
        'creator_id',
        'document_flow_id',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Relationships
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'type_id');
    }

    public function creator()
    {
        return $this->belongsTo(NonAdmin::class, 'creator_id');
    }

    public function flow()
    {
        return $this->belongsTo(DocumentFlow::class, 'document_flow_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }
}
