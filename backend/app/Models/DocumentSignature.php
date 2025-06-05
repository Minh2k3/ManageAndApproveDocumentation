<?php
// app/Models/DocumentSignature.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\DocumentFlowStep;
use App\Models\DocumentVersion;
use App\Models\DigitalCertificate;
use App\Models\SignatureTimestamp;

class DocumentSignature extends Model
{
    protected $fillable = [
        'document_flow_step_id', 'document_version_id', 'certificate_id',
        'pkcs7_signature', 'document_hash', 'signature_attributes', 
        'signed_at', 'is_valid', 'verification_details'
    ];
    
    protected $casts = [
        'signature_attributes' => 'array',
        'signed_at' => 'datetime',
        'is_valid' => 'boolean'
    ];
    
    // Relationships
    public function documentFlowStep(): BelongsTo
    {
        return $this->belongsTo(DocumentFlowStep::class);
    }
    
    public function documentVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentVersion::class);
    }
    
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(DigitalCertificate::class);
    }
    
    public function timestamps(): HasMany
    {
        return $this->hasMany(SignatureTimestamp::class, 'signature_id');
    }
}