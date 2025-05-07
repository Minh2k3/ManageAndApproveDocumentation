<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\NonAdmin;
use App\Models\Admin;
use App\Models\DocumentTemplate;
use App\Models\Notification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function nonAdmin()
    {
        return $this->hasMany(NonAdmin::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }

    public function documentTemplates()
    {
        return $this->hasMany(DocumentTemplate::class);
    }


}
