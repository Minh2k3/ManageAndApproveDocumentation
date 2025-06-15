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
        'document_flow_step_id',
        'certificate_id',
        'signature',
        'signed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'signed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the document version that owns the signature.
     */
    public function documentVersion()
    {
        return $this->belongsTo(DocumentVersion::class);
    }

    /**
     * Get the approver associated with the signature.
     */
    public function approver()
    {
        return $this->belongsTo(Approver::class, 'document_flow_step_id', 'approver_id');
    }

    /**
     * Get the certificate associated with the signature.
     */
    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id', 'id');
    }
    /**
     * Get the document flow step associated with the signature.
     */
    public function documentFlowStep()
    {
        return $this->belongsTo(DocumentFlowStep::class, 'document_flow_step_id', 'id');
    }
}
