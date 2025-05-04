<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleOfDepartmentName extends Model
{
    protected $fillable = [
        'user_id',
        'role_id',
        'department_id',
        'role',
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
}
