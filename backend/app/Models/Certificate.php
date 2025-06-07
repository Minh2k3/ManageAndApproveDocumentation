<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Certificate extends Model
{
    protected $fillable = ['user_id', 'public_key', 'private_key', 'certificate', 'issued_at', 'expires_at', 'status'];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'private_key',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
