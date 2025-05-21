<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentType;
use App\Models\DocumentFlow;
use App\Models\DocumentVersion;
use App\Models\User;
use Carbon\Carbon;
/**
 * Document Model
 *
 * This model represents a document in the system.
 * It contains information about the document's title, description, type, creator,
 * and whether it is public or not.
 */

class Document extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'document_type_id',
        'created_by',
        'document_flow_id',
        'status',
        'is_public',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'document_data' => 'array',
    ];

    /**
     * Get the document type for the document.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the creator for the document.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the document flow for the document.
     */
    public function documentFlow()
    {
        return $this->belongsTo(DocumentFlow::class);
    }

    /**
     * Get the versions for the document.
     */
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    /**
     * Get the latest version of the document.
     */
    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)->latest('version');
    }

    protected $dates = ['created_at', 'updated_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }
}
