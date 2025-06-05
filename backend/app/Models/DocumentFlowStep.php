<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentFlow;
use App\Models\Department;
use App\Models\Approver;
use App\Models\DocumentComment;
use App\Models\DocumentSignature;
use Carbon\Carbon;

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
        'multichoice',
        'status',
        'decision',
        'signed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'decision' => 'string',
        'multichoice' => 'boolean',
        'signed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the document flow that owns the step.
     */
    public function documentFlow()
    {
        return $this->belongsTo(DocumentFlow::class, 'document_flow_id');
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

    /**
     * Get the comments for this step.
     */
    public function comments()
    {
        return $this->hasMany(DocumentComment::class, 'document_flow_step_id');
    }

    /**
     * Get the signatures for this step.
     */
    public function signatures()
    {
        return $this->hasMany(DocumentSignature::class, 'document_flow_step_id');
    }
    
    /**
     * Get the created_at attribute formatted.
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    /**
     * Get the updated_at attribute formatted.
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

}
