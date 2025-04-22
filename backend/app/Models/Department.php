<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'avatar',
        'phone_number',
        'position',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_department_roles')
            ->withPivot('role_id', 'created_at', 'ended_at');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_department_roles')
            ->withPivot('user_id', 'created_at', 'ended_at');
    } 
}
