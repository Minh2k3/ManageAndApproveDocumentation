<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Creator;
use App\Models\Approver;
use App\Models\DocumentType;
use Carbon\Carbon;

class RollAtDepartment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'level',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'level' => 'integer',
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
     * Get the creators for the roll at department.
     */
    public function creators()
    {
        return $this->hasMany(Creator::class);
    }

    /**
     * Relationship với DocumentType thông qua ApprovalPermission
     */
    public function documentTypes()
    {
        return $this->belongsToMany(
            DocumentType::class,
            'approval_permissions',
            'roll_at_department_id',
            'document_type_id'
        );
    }

    /**
     * Get the number of creators for the roll at department.
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
     * Get the approvers for the roll at department.
     */
    public function approvers()
    {
        return $this->hasMany(Approver::class);
    }

    /**
     * Get the number of approvers for the roll at department.
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
     * Get the number of users in the roll at department.
     */
    public function getNumberOfUsersAttribute()
    {
        return $this->number_of_creators + $this->number_of_approvers;
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
     * Scope a query to exclude first 3 rolls.
     */
    public function scopeExcludeFirst($query, $count = 3)
    {
        return $query->where('id', '>', $count);
    }

    /**
     * Scope a query to order by level.
     */
    public function scopeOrderByLevel($query, $direction = 'asc')
    {
        return $query->orderBy('level', $direction);
    }

    /**
     * Scope a query to filter by level.
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}
