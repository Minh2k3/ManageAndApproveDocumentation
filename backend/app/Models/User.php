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
use App\Models\DigitalSignature;
use App\Models\DocumentComment;
use App\Models\Role;
use App\Models\UserBan;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Approver;
use App\Models\Creator;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'signature_id',
        'avatar',
        'sex',
        'status',
        'email_verified_at',
        'email_verified',
        'verification_token',
        'verification_token_expiry',
        'verification_resend_count',
        'last_verification_resent_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'string',
        'sex' => 'string',
        'email_verified' => 'boolean',
        'verification_token_expiry' => 'datetime',
        'last_verification_resent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the digital signature associated with the user.
     */
    public function digitalSignature()
    {
        return $this->belongsTo(DigitalSignature::class, 'signature_id');
    }

    /**
     * Get the admin profile associated with the user.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the approver profile associated with the user.
     */
    public function approver()
    {
        return $this->hasOne(Approver::class);
    }

    /**
     * Get the creator profile associated with the user.
     */
    public function creator()
    {
        return $this->hasOne(Creator::class);
    }

    /**
     * Get the user's ban records.
     */
    public function bans()
    {
        return $this->hasMany(UserBan::class);
    }

    /**
     * Get the user's document templates.
     */
    public function documentTemplates()
    {
        return $this->hasMany(DocumentTemplate::class, 'created_by');
    }

    /**
     * Get the templates that the user has used or liked.
     */
    public function usedTemplates()
    {
        return $this->belongsToMany(DocumentTemplate::class, 'templates_users', 'user_id', 'template_id')
            ->withPivot('count', 'is_liked')
            ->withTimestamps();
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    /**
     * Get the comments made by the user.
     */
    public function comments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    /**
     * Check if the user is an approver.
     */
    public function isApprover()
    {
        return $this->role->name === 'approver';
    }

    /**
     * Check if the user is a creator.
     */
    public function isCreator()
    {
        return $this->role->name === 'creator';
    }

    /**
     * Check if the user is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is banned.
     */
    public function isBanned()
    {
        return $this->status === 'banned';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    protected $dates = ['created_at', 'updated_at', 'email_verified_at', 'verification_token_expiry', 'last_verification_resent_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s d/m/Y');
    }

    // public function getEmailVerifiedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('H:i:s d/m/Y');
    // }

    // public function getVerificationTokenExpiryAttribute($value)
    // {
    //     return Carbon::parse($value)->format('H:i:s d/m/Y');
    // }

    // public function getLastVerificationResentAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('H:i:s d/m/Y');
    // }
}