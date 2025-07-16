<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Approver;
use App\Models\DocumentType;
use Carbon\Carbon;

class ApproverHasPermission extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'approver_id',
        'document_type_id',
        'created_at',
        'ended_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the approver associated with the permission.
     */
    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }

    /**
     * Get the document type associated with the permission.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    protected $dates = ['created_at', 'ended_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getEndedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }    
}
