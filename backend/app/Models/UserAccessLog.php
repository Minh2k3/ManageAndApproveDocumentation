<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class UserAccessLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'is_authenticated',
        'access_time',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_authenticated' => 'boolean',

    ];

    /**
     * Get the user that owns the access log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $dates = ['created_at', 'updated_at', 'access_time'];

    public function getCreadtedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getAccessTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }
}
