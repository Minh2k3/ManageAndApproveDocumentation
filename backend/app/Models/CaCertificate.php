<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaCertificate extends Model
{
    protected $fillable = ['name', 'private_key', 'certificate', 'issued_at', 'expires_at'];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    
}
