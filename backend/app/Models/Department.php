<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Approver;
use App\Models\Creator;
use App\Models\DocumentFlowStep;
use Carbon\Carbon;

class Department extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'avatar',
        'phone_number',
        'position',
        'can_approve',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'can_approve' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the approvers for the department.
     */
    public function approvers()
    {
        return $this->hasMany(Approver::class);
    }

    /**
     * Get the creators for the department.
     */
    public function creators()
    {
        return $this->hasMany(Creator::class);
    }

    /**
     * Get the document flow steps for the department.
     */
    public function documentFlowSteps()
    {
        return $this->hasMany(DocumentFlowStep::class);
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
