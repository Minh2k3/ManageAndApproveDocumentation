<?php
// app/Models/CertificateRenewal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\DigitalCertificate;
use App\Models\Admin;

class CertificateRenewal extends Model
{
    protected $fillable = [
        'old_certificate_id', 'new_certificate_id', 'renewal_type',
        'reason', 'status', 'approved_by'
    ];
    
    public function oldCertificate(): BelongsTo
    {
        return $this->belongsTo(DigitalCertificate::class, 'old_certificate_id');
    }
    
    public function newCertificate(): BelongsTo
    {
        return $this->belongsTo(DigitalCertificate::class, 'new_certificate_id');
    }
    
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}