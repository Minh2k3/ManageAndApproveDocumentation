<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonAdmin extends Model
{
    protected $table = 'non_admins';

    protected $fillable = [
        'user_id',
        'role_id',
        'department_id',
        'signature_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function signature()
    {
        return $this->belongsTo(DigitalSignature::class, 'signature_id');
    }
}
