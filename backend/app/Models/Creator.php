<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;
use App\Models\Document;
use App\Models\RollAtDepartment;

class Creator extends Model
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
     * Get the user that owns the creator.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that the creator belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the documents created by this creator.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the roll at department that the creator belongs to.
     */
    public function rollAtDepartment()
    {
        return $this->belongsTo(RollAtDepartment::class);
    }
}
