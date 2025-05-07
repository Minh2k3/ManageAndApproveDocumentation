<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NonAdmin;
use App\Models\Role;
use App\Models\DocumentFlowStep;
/**
 * Departments Model
 *
 * This model represents a department within the organization.
 * It contains information about the department's name, description, avatar,
 * phone number, position, and whether it can approve documents.
 */

class Departments extends Model
{

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
    public function documentFlowSteps()
    {
        return $this->hasMany(DocumentFlowStep::class);
    }

    public function nonAdmins()
    {
        return $this->hasMany(NonAdmin::class);
    }
}
