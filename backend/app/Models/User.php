<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'sex',
        'status',
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

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_of_department_names', 'user_id', 'role_id')
                    ->withPivot('department_id', 'role')
                    ->withTimestamps();
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'role_of_department_names', 'user_id', 'department_id')
                    ->withPivot('role_id', 'role')
                    ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'creator_id');
    }

    public function documentComments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    public function documentSignatures()
    {
        return $this->hasMany(DocumentSignature::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    public function adminRole()
    {
        return $this->hasOne(Admin::class);
    }

    public function templates()
    {
        return $this->hasMany(TemplateUser::class);
    }

}
