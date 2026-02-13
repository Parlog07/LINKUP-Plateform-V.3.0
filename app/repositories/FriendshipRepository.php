<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FriendRequestRepository
{
    public function send(int $receiverId, int $senderId)
    {
        return DB::table('friend_requests')->insert([
            'user_id' => $receiverId,
            'request_sender_id' => $senderId,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function accept(int $requestId)
    {
        return DB::table('friend_requests')
            ->where('id', $requestId)
            ->update(['status' => 'accepted']);
    }

    public function getFriends(int $userId)
    {
        return DB::table('friend_requests')
            ->join('users', function ($join) use ($userId) {
                $join->on('users.id', '=', DB::raw("
                    CASE
                        WHEN friend_requests.user_id = $userId
                        THEN friend_requests.request_sender_id
                        ELSE friend_requests.user_id
                    END
                "));
            })
            ->where('status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('friend_requests.user_id', $userId)
                      ->orWhere('friend_requests.request_sender_id', $userId);
            })
            ->select('users.*')
            ->get();
    }
}
