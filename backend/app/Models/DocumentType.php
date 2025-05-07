<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\DocumentTemplate;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships
    public function documents()
    {
        return $this->hasMany(Document::class, 'type_id');
    }

    public function documentTemplates()
    {
        return $this->hasMany(DocumentTemplate::class, 'document_type_id');
    }
}
