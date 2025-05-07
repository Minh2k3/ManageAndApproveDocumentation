<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;

class NotificationCategory extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the notifications for the category.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
