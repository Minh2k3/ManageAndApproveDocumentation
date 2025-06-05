<?php
// app/Models/DigitalCertificate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;
use App\Models\DocumentSignature;
use App\Models\CertificateRenewal;

class DigitalCertificate extends Model
{
    protected $fillable = [
        'user_id', 'certificate_pem', 'private_key_encrypted', 
        'serial_number', 'subject', 'issuer', 'valid_from', 
        'valid_to', 'thumbprint', 'parent_certificate_id',
        'renewal_status', 'renewal_requested_at', 'expiry_notification_sent_at'
    ];
    
    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'renewal_requested_at' => 'datetime',
        'expiry_notification_sent_at' => 'datetime',
        'is_revoked' => 'boolean'
    ];
    
    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function signatures(): HasMany
    {
        return $this->hasMany(DocumentSignature::class, 'certificate_id');
    }
    
    public function renewals(): HasMany
    {
        return $this->hasMany(CertificateRenewal::class, 'old_certificate_id');
    }
    
    public function parentCertificate(): BelongsTo
    {
        return $this->belongsTo(DigitalCertificate::class, 'parent_certificate_id');
    }
    
    // Helper methods
    public function isValid(): bool
    {
        return !$this->is_revoked && 
               $this->valid_from <= now() && 
               $this->valid_to >= now();
    }
    
    public function isExpiringSoon($days = 30): bool
    {
        return $this->valid_to <= now()->addDays($days) && 
               $this->valid_to > now();
    }
    
    public function getDaysUntilExpiry(): int
    {
        return now()->diffInDays($this->valid_to, false);
    }
}