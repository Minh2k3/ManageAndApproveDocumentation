<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use Carbon\Carbon;

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
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $dates = ['created_at', 'updated_at', 'issued_at', 'expires_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getIssuedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getExpiresAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }
}
