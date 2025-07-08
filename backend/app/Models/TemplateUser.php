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
    protected $table = 'template_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'template_id',
    ];
}
