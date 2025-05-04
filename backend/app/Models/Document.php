<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

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
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function flow()
    {
        return $this->belongsTo(DocumentFlow::class, 'document_flow_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class, 'document_version_id');
    }

    public function signatures()
    {
        return $this->hasMany(DocumentSignature::class, 'document_version_id');
    }
}
