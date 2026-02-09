<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PostAction extends Model
{
    use HasFactory;

    protected $table = 'posts_actions';

    protected $fillable = [
        'user_id',
        'post_id',
        'action_type',
        'content'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
        }
}
