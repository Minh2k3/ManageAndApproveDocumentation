<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentVersion;
use App\Models\Approver;

class DocumentSignature extends Model
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
        'document_version_id',
        'approver_id',
        'signed_at',
        'signature_text',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * Get the document version that owns the signature.
     */
    public function documentVersion()
    {
        return $this->belongsTo(DocumentVersion::class);
    }

    /**
     * Get the approver that signed the document.
     */
    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }
}
