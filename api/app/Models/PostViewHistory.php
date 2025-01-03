<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostViewHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    public function post()
    {

        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}
