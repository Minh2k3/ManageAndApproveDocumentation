<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentFlow;
use App\Models\Department;

class DocumentFlowStep extends Model
{
    protected $table = 'document_flow_steps';

    protected $fillable = [
        'document_flow_id',
        'step',
        'department_id',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    // Relationships
    public function documentFlow()
    {
        return $this->belongsTo(DocumentFlow::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
