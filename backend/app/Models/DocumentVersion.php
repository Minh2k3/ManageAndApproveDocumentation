<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\DocumentComment;
use App\Models\DocumentSignature;

class DocumentVersion extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_id',
        'version',
        'file_path',
        'file_name',
        'status',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
    ];

    /**
     * Get the document that owns the version.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the comments for the document version.
     */
    public function comments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    /**
     * Get the signatures for the document version.
     */
    public function signatures()
    {
        return $this->hasMany(DocumentSignature::class);
    }
}
