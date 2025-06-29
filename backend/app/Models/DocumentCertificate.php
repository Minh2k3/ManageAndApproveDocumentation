<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;

class DocumentCertificate extends Model
{
    protected $table = 'document_certificates';

    protected $fillable = [
        'document_id',
        'code',
        'file_path',
        'created_at',
    ];

    public $timestamps = false;

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
