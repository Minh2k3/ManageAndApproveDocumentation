<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Creator;
use App\Models\Approver;

class RollAtDepartment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id',
        'roll_at_department_id',
        'name',
        'level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Get the creators for the roll at department.
     */
    public function creators()
    {
        return $this->hasMany(Creator::class);
    }

    /**
     * Get the approvers for the roll at department.
     */
    public function approvers()
    {
        return $this->hasMany(Approver::class);
    }
}
