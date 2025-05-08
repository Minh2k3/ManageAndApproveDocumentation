<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DocumentType;

class DocumentTemplate extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'file_path',
        'created_by',
        'document_type_id',
    ];

    /**
     * Get the user that created the template.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the document type for the template.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the users that have used or liked this template.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'templates_users', 'template_id', 'user_id')
            ->withPivot('count', 'is_liked')
            ->withTimestamps();
    }
}
