<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'level',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_of_department_names', 'role_id', 'user_id')
                    ->withPivot('department_id', 'role')
                    ->withTimestamps();
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'role_of_department_names', 'role_id', 'department_id')
                    ->withPivot('user_id', 'role')
                    ->withTimestamps();
    }
}
