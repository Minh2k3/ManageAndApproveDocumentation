<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'level',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_department_roles')
            ->withPivot('department_id', 'created_at', 'ended_at');
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_department_roles')
            ->withPivot('user_id', 'created_at', 'ended_at');
    }
}
