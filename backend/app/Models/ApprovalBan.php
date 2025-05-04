<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalBan extends Model
{
    protected $table = 'approval_bans';

    protected $fillable = [
        'user_id',
        'banned_by',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}
