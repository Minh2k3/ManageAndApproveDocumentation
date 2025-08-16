<?php
// app/Models/Wish.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Wish extends Model
{
    protected $fillable = [
        'code',
        'user_id', 
        'sender_name',
        'content',
        'position_x',
        'position_y',
        'rotation',
        'is_active'
    ];

    protected $casts = [
        'position_x' => 'decimal:2',
        'position_y' => 'decimal:2', 
        'rotation' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(WishMedia::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(WishMedia::class)->where('type', 'image');
    }

    public function audio(): HasMany
    {
        return $this->hasMany(WishMedia::class)->where('type', 'audio');
    }

    // Auto generate code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($wish) {
            if (empty($wish->code)) {
                $year = date('Y');
                $lastWish = static::whereYear('created_at', $year)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $nextNumber = $lastWish ? (int)substr($lastWish->code, -3) + 1 : 1;
                $wish->code = 'WS' . $year . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // Accessors
    public function getPositionAttribute()
    {
        return [
            'x' => $this->position_x,
            'y' => $this->position_y,
            'rotation' => $this->rotation
        ];
    }

    public function getMediaByTypeAttribute()
    {
        return [
            'images' => $this->images->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => asset('storage/' . $media->file_path),
                    'name' => $media->original_name
                ];
            }),
            'audio' => $this->audio->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => asset('storage/' . $media->file_path),
                    'name' => $media->original_name
                ];
            })
        ];
    }
}