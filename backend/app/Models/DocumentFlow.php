<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\DocumentFlowStep;
use App\Models\User;

class DocumentFlow extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'is_active',
        'is_template',
        'process',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_template' => 'boolean',
    ];

    /**
     * Get the steps for the document flow.
     */
    public function steps()
    {
        return $this->hasMany(DocumentFlowStep::class);
    }

    /**
     * Get the documents for the document flow.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    /**
     * Get the user that created the document flow.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documentFlowSteps()
    {
        return $this->hasMany(DocumentFlowStep::class, 'document_flow_id');
    }
}
