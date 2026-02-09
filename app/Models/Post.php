<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'media_path', 'media_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actions(){
        return $this->hasMany(PostAction::class);
    }
    
    public function likes(){
        return $this->hasMany(PostAction::class)->where('action_type', 'like');
    }

    public function comments(){
        return $this->hasMany(PostAction::class)->where('action_type', 'comment')->latest();
    }

    public function isLikedBy($user){
        if (!$user) return false;

        return $this->likes()->where('user_id', $user->id)->exists();
    }



protected static function booted()
{
    static::addGlobalScope('mcp_visibility', function ($query) {
        if (!auth()->check()) {
            $query->where('status', 'approved');
            return;
        }

        if (!auth()->user()->is_admin) {
            $query->where(function ($q) {
                $q->where('status', 'approved')
                  ->orWhere('user_id', auth()->id());
            });
        }
    });
}


}