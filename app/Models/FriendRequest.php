<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $fillable = [
        'user_id',
        'request_sender_id',
        'status'
    ];
}
