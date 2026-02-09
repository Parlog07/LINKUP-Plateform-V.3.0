<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'first_name',
    'last_name',
    'username',
    'email',
    'bio',
    'location',
    'industry',
    'image',
    'interests',
    'password'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }





    public function getReceivedRequests()
    {
        return FriendRequest::where('user_id', $this->id)
            ->where('status', 'pending')
            ->join('users', 'friend_requests.request_sender_id', '=', 'users.id')
            ->select(
                'friend_requests.*',
                'users.first_name', 
                'users.last_name', 
                'users.image', // Fixed from 'image'
                'users.username'
            )->get();
    }

    /**
     * Get requests sent BY this user (People I want to add).
     */
    public function getSentRequests()
    {
        // FIX: We must check 'request_sender_id', not 'user_id'
        return FriendRequest::where('request_sender_id', $this->id)
            ->where('status', 'pending')
            ->join('users', 'friend_requests.user_id', '=', 'users.id')
            ->select(
                'friend_requests.*',
                'users.first_name', 
                'users.last_name', 
                'users.image', 
                'users.username'
            )->get();
    }

    /**
     * Get all confirmed friends (merged list).
     */
    public function getFriends()
    {
        // 1. Friends where I accepted THEIR request
        $friendsAsReceiver = FriendRequest::where('user_id', $this->id)
            ->where('status', 'accepted')
            ->join('users', 'friend_requests.request_sender_id', '=', 'users.id')
            ->select(
                'friend_requests.*',
                'users.first_name',
                'users.last_name',
                'users.image', // Fixed from 'image'
                'users.username'
            )->get();

        // 2. Friends where they accepted MY request
        $friendsAsSender = FriendRequest::where('request_sender_id', $this->id)
            ->where('status', 'accepted')
            ->join('users', 'friend_requests.user_id', '=', 'users.id')
            ->select(
                'friend_requests.*', 
                'users.first_name', 
                'users.last_name', 
                'users.image', // Fixed from 'image'
                'users.username'
            )->get();

        return $friendsAsReceiver->merge($friendsAsSender);
    }

    // ... inside App\Models\User.php ...

    /**
     * Send a friend request to another user.
     * Returns true if successful, false if already exists.
     */
    public function sendFriendRequestTo($recipientId)
    {
        // 1. Prevent duplicates
        $exists = FriendRequest::where('request_sender_id', $this->id)
            ->where('user_id', $recipientId)
            ->exists();

        if ($exists) {
            return false;
        }

        // 2. Create Request
        FriendRequest::create([
            'user_id' => $recipientId,
            'request_sender_id' => $this->id,
            'status' => 'pending'
        ]);
        

        return true;
    }

    /**
     * Accept a pending request (where I am the receiver).
     */
    public function acceptFriendRequest($requestId)
    {
        $request = FriendRequest::where('id', $requestId)
            ->where('user_id', $this->id) // Security: Ensure I am the one receiving it
            ->firstOrFail();

        $request->update(['status' => 'accepted']);
    }

    /**
     * Decline a pending request (where I am the receiver).
     */
    public function declineFriendRequest($requestId)
    {
        $request = FriendRequest::where('id', $requestId)
            ->where('user_id', $this->id)
            ->firstOrFail();

        $request->delete();
    }

    /**
     * Cancel a request I sent (where I am the sender).
     */
    public function cancelFriendRequest($requestId)
    {
        $request = FriendRequest::where('id', $requestId)
            ->where('request_sender_id', $this->id)
            ->firstOrFail();

        $request->delete();
    }

    /**
     * Remove a friend (handles both directions).
     */
    public function removeFriend($requestId)
    {
        $request = FriendRequest::where('id', $requestId)
            ->where(function($q) {
                $q->where('user_id', $this->id)
                  ->orWhere('request_sender_id', $this->id);
            })
            ->firstOrFail();

        $request->delete();
    }

    public function posts(){
        return $this->hasMany(Post::class);
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

    public function getRouteKeyName()
{
    return 'username';
}

}