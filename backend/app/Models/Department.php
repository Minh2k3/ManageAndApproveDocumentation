<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'avatar',
        'phone_number',
        'position',
        'can_approve',
    ];

    protected $casts = [
        'can_approve' => 'boolean',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_of_department_names', 'department_id', 'user_id')
                    ->withPivot('role_id', 'role')
                    ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_of_department_names', 'department_id', 'role_id')
                    ->withPivot('user_id', 'role')
                    ->withTimestamps();
    }

    public function documentFlowSteps()
    {
        return $this->hasMany(DocumentFlowStep::class);
    }

    public function nonAdmins()
    {
        return $this->hasMany(NonAdmin::class);
    }
}
