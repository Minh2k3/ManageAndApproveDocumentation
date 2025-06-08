<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    // Quan trọng: Cast created_at thành datetime
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Token expires after 60 minutes
    public function isExpired()
    {
        // Đảm bảo created_at là Carbon instance
        $createdAt = $this->created_at instanceof Carbon 
            ? $this->created_at 
            : Carbon::parse($this->created_at);
            
        return $createdAt->addMinutes(60)->isPast();
    }
}