<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCertificate extends Model
{
    protected $table = 'document_certificates';

    protected $fillable = [
        'code',
        'created_at',
    ];

    public $timestamps = false;
}
