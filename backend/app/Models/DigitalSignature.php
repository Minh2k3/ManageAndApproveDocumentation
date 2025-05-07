<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DigitalSignature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'public_key',
        'private_key',
    ];

    /**
     * Get the user that owns the digital signature.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'signature_id');
    }
}
