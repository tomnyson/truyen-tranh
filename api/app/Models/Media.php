<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'file_name',
        'original_name',
        'mime_type',
        'size',
        'disk',
        'path',
        'url',
        'user_id',
    ];

    /**
     * If media belongs to a user, define the relationship:
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A helper to get full URL if not stored in DB.
     * For example, if using Storage facade:
     */
    public function getFullUrlAttribute()
    {
        return $this->url ?? \Storage::disk($this->disk)->url($this->path);
    }
}