<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NonAdmin;
use App\Models\Admin;
/**
 * ApprovalBan Model
 *
 * This model represents a ban on a user from making approval requests.
 * It contains information about the user who is banned, the admin who banned them,
 * and the duration of the ban.
 */

class ApprovalBan extends Model
{
    protected $table = 'approval_bans';

    protected $fillable = [
        'user_id',
        'banned_by',
        'reason',
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
        return $this->belongsTo(NonAdmin::class);
    }

    public function bannedBy()
    {
        return $this->belongsTo(Admin::class, 'banned_by');
    }
}
