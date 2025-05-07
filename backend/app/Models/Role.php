<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NonAdmin;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'level',
    ];

    // Relationships
    public function nonAdmins()
    {
        return $this->hasMany(NonAdmin::class);
    }


}
