<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
{
    protected $table = 'notification_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'notification_category_id');
    }
}
