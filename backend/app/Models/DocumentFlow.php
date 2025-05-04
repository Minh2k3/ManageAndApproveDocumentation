<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
