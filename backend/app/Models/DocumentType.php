<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentType extends Model
{
    use HasFactory;

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
