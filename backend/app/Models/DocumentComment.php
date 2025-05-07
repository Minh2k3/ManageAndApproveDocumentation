<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DocumentVersion;

class DocumentComment extends Model
{
    protected $table = 'document_comment';

    protected $fillable = [
        'user_id',
        'document_version_id',
        'comment',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentVersion()
    {
        return $this->belongsTo(DocumentVersion::class);
    }
}
