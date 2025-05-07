<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NonAdmin;

class TemplateUser extends Model
{
    protected $table = 'templates_user';

    protected $fillable = [
        'user_id',
        'template_id',
        'count',
        'is_liked',
    ];

    protected $casts = [
        'count' => 'integer',
        'is_liked' => 'boolean',
    ];

    // Relationships
    public function nonAdmin()
    {
        return $this->belongsTo(NonAdmin::class);
    }

    public function template()
    {
        return $this->belongsTo(DocumentTemplate::class, 'template_id');
    }
}
