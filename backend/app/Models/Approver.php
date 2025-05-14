<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;
use App\Models\ApprovalPermission;
use App\Models\DocumentSignature;
use App\Models\RollAtDepartment;
use Carbon\Carbon;

class Approver extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'department_id',
        'roll_at_department_id',
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime', 
    ];

    /**
     * Get the user that owns the approver.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that the approver belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the approval permissions for the approver.
     */
    public function approvalPermissions()
    {
        return $this->hasMany(ApprovalPermission::class, 'user_id');
    }

    /**
     * Get the document signatures for the approver.
     */
    public function documentSignatures()
    {
        return $this->hasMany(DocumentSignature::class);
    }

    /**
     * Get the roll at department that the approver belongs to.
     */
    public function rollAtDepartment()
    {
        return $this->belongsTo(RollAtDepartment::class, 'roll_at_department_id');
    }

    protected $dates = ['created_at', 'updated_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }
}
