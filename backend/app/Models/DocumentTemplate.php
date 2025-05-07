<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\TemplateUser;

class DocumentTemplate extends Model
{
    protected $table = 'document_templates';

    protected $fillable = [
        'name',
        'description',
        'file_path',
        'created_by',
        'document_type_id',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function templateUsers()
    {
        return $this->hasMany(TemplateUser::class, 'template_id');
    }
}
