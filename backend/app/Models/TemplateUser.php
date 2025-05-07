<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateUser extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'templates_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'template_id',
        'count',
        'is_liked',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'count' => 'integer',
        'is_liked' => 'boolean',
    ];
}
