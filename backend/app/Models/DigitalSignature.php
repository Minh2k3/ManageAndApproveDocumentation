<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSignature extends Model
{
    protected $table = 'digital_signatures';

    protected $fillable = [
        'public_key',
        'private_key',
    ];

    // Relationships
    public function nonAdmin()
    {
        return $this->hasOne(NonAdmin::class, 'signature_id');
    }
}
