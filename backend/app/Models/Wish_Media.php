<?php
// app/Models/WishMedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class WishMedia extends Model
{
    protected $fillable = [
        'wish_id',
        'type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'original_name'
    ];

    public function wish(): BelongsTo
    {
        return $this->belongsTo(Wish::class);
    }

    // Get full URL using wishes disk
    public function getUrlAttribute()
    {
        return Storage::disk('wishes')->url($this->file_path);
    }

    // Check if file exists
    public function getExistsAttribute()
    {
        return Storage::disk('wishes')->exists($this->file_path);
    }

    // Get file size in human readable format
    public function getHumanSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Delete file when model deleted
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($media) {
            Storage::disk('wishes')->delete($media->file_path);
        });
    }
}