<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentType;
use App\Models\Approver;
use Carbon\Carbon;

/**
 * ApprovalPermission Model
 *
 * This model represents a permission granted to a user to approve documents.
 * It contains information about the user, the document type, and the person who granted the permission.
 */

class ApprovalPermission extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'roll_at_department_id',
        'document_type_id',
        'created_at',
        'ended_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the approver that owns the permission.
     */
    public function approver()
    {
        return $this->belongsTo(Approver::class, 'user_id');
    }

    /**
     * Get the document type that the permission is for.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    protected $dates = ['created_at', 'ended_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getEndedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }
}
