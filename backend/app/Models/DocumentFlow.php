<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\DocumentFlowStep;

class DocumentFlow extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'is_template',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_template' => 'boolean',
    ];

    // Relationships
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function steps()
    {
        return $this->hasMany(DocumentFlowStep::class);
    }
}
