<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NonAdmin;
use App\Models\DocumentVersion;

class DocumentSignature extends Model
{
    protected $table = 'document_signatures';

    protected $fillable = [
        'document_version_id',
        'user_id',
        'signed_at',
        'signature_text',
        'status',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    // Relationships
    public function documentVersion()
    {
        return $this->belongsTo(DocumentVersion::class);
    }

    public function user()
    {
        return $this->belongsTo(NonAdmin::class);
    }
}
