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
        'status',
        'group',
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['number_of_users', 'number_of_approvers', 'number_of_creators'];

    /**
     * Get the approvers for the department.
     */
    public function approvers()
    {
        return $this->hasMany(Approver::class);
    }

    /**
     * Get the number of approvers for the department.
     */
    public function getNumberOfApproversAttribute()
    {
        // Sử dụng cache để tránh query lặp lại
        if (!isset($this->attributes['approvers_count'])) {
            return $this->approvers()->count();
        }
        return $this->attributes['approvers_count'] ?? 0;
    }

    /**
     * Get the creators for the department.
     */
    public function creators()
    {
        return $this->hasMany(Creator::class);
    }

    /**
     * Get the number of creators for the department.
     */
    public function getNumberOfCreatorsAttribute()
    {
        // Sử dụng cache để tránh query lặp lại
        if (!isset($this->attributes['creators_count'])) {
            return $this->creators()->count();
        }
        return $this->attributes['creators_count'] ?? 0;
    }

    /**
     * Get the number of users for the department.
     */
    public function getNumberOfUsersAttribute()
    {
        return $this->number_of_creators + $this->number_of_approvers;
    }

    /**
     * Get the document flow steps for the department.
     */
    public function documentFlowSteps()
    {
        return $this->hasMany(DocumentFlowStep::class);
    }

    /**
     * Format created_at for display
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('H:i:s d/m/Y') : null;
    }

    /**
     * Format updated_at for display
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('H:i:s d/m/Y') : null;
    }

    /**
     * Scope a query to only include active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to group not type 'Khác'
     */
    public function scopeGroupNotOther($query)
    {
        return $query->where('group', '!=', 'Khác');
    }

    /**
     * Scope a query to exclude first 3 departments.
     */
    public function scopeExcludeFirst($query, $count = 3)
    {
        return $query->where('id', '>', $count);
    }
}
