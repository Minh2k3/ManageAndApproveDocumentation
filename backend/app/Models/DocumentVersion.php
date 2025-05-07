<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\DocumentComment;
use App\Models\DocumentSignature;

class DocumentVersion extends Model
{
    protected $table = 'document_versions';

    protected $fillable = [
        'document_id',
        'version',
        'file_path',
        'file_name',
        'status',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    public function signatures()
    {
        return $this->hasMany(DocumentSignature::class);
    }
}
