<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;
use App\Models\ApprovalPermission;
use App\Models\DocumentSignature;
use App\Models\RollAtDepartment;

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
}
