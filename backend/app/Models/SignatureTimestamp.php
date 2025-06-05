<?php
// app/Models/SignatureTimestamp.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\DocumentSignature;

class SignatureTimestamp extends Model
{
    protected $fillable = [
        'signature_id', 'timestamp_token', 'hash_algorithm', 'timestamp_time'
    ];
    
    protected $casts = [
        'timestamp_time' => 'datetime'
    ];
    
    public function signature(): BelongsTo
    {
        return $this->belongsTo(DocumentSignature::class);
    }
}