<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\DigitalSignature;
use App\Models\TemplateUser;
use App\Models\Notification;
use App\Models\ApprovalBan;
use App\Models\ApprovalPermission;
use App\Models\Document;
use App\Models\DocumentComment;

class NonAdmin extends Model
{
    protected $table = 'non_admins';

    protected $fillable = [
        'user_id',
        'role_id',
        'department_id',
        'signature_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function digitalSignature()
    {
        return $this->hasOne(DigitalSignature::class);
    }

    public function templatesUser()
    {
        return $this->hasMany(TemplateUser::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    public function approvalBan()
    {
        return $this->hasMany(ApprovalBan::class);
    }

    public function approvalPermission()
    {
        return $this->hasMany(ApprovalPermission::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function documentComments()
    {
        return $this->hasMany(DocumentComment::class);
    }
}
