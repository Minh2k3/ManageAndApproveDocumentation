<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentFlow;
use App\Models\Department;
use App\Models\Approver;

class DocumentFlowStep extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_flow_id',
        'step',
        'department_id',
        'approver_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the document flow that owns the step.
     */
    public function documentFlow()
    {
        return $this->belongsTo(DocumentFlow::class);
    }

    /**
     * Get the department that is responsible for this step.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the approver for this step.
     */
    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }
}
