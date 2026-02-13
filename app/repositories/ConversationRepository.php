<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ConversationRepository
{
    public function findBetweenUsers(int $userOne, int $userTwo)
    {
        return DB::table('conversations')
            ->where(function ($query) use ($userOne, $userTwo) {
                $query->where('user_one', $userOne)
                      ->where('user_two', $userTwo);
            })
            ->orWhere(function ($query) use ($userOne, $userTwo) {
                $query->where('user_one', $userTwo)
                      ->where('user_two', $userOne);
            })
            ->first();
    }

    public function create(int $userOne, int $userTwo)
    {
        return DB::table('conversations')->insertGetId([
            'user_one' => $userOne,
            'user_two' => $userTwo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function findOrCreate(int $userOne, int $userTwo)
    {
        $conversation = $this->findBetweenUsers($userOne, $userTwo);

        return $conversation
            ? $conversation->id
            : $this->create($userOne, $userTwo);
    }

    public function getUserConversations(int $userId)
    {
        return DB::table('conversations')
            ->where('user_one', $userId)
            ->orWhere('user_two', $userId)
            ->get();
    }

    public function activeLast24Hours(int $userId)
    {
        return DB::table('messages')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->where(function ($query) use ($userId) {
                $query->where('conversations.user_one', $userId)
                      ->orWhere('conversations.user_two', $userId);
            })
            ->select('messages.conversation_id', DB::raw('MAX(messages.created_at) as last_message'))
            ->groupBy('messages.conversation_id')
            ->havingRaw("MAX(messages.created_at) >= NOW() - INTERVAL '24 HOURS'")
            ->get();
    }
}
