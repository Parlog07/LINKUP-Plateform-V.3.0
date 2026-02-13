<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class MessageRepository
{
    public function send(
        int $conversationId,
        int $userId,
        string $body,
        ?string $attachmentPath = null,
        ?string $attachmentName = null,
        ?string $attachmentMime = null,
        ?int $attachmentSize = null
    )
    {
        return DB::table('messages')->insertGetId([
            'conversation_id' => $conversationId,
            'user_id' => $userId,
            'body' => $body,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_mime' => $attachmentMime,
            'attachment_size' => $attachmentSize,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function markAsRead(int $messageId, int $userId)
    {
        return DB::table('message_reads')->insertOrIgnore([
            'message_id' => $messageId,
            'user_id' => $userId,
            'read_at' => now(),
        ]);
    }

    public function unreadCountByConversation(int $userId)
    {
        return DB::table('messages')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->leftJoin('message_reads', function ($join) use ($userId) {
                $join->on('messages.id', '=', 'message_reads.message_id')
                     ->where('message_reads.user_id', '=', $userId);
            })
            ->where(function ($query) use ($userId) {
                $query->where('conversations.user_one', $userId)
                      ->orWhere('conversations.user_two', $userId);
            })
            ->where('messages.user_id', '!=', $userId)
            ->whereNull('message_reads.id')
            ->select('messages.conversation_id', DB::raw('COUNT(*) as unread_count'))
            ->groupBy('messages.conversation_id')
            ->get();
    }

    public function messagesPerDay($start, $end)
    {
        return DB::table('messages')
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get();
    }

    public function topConversation(int $userId)
    {
        return DB::table('messages')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->where(function ($query) use ($userId) {
                $query->where('conversations.user_one', $userId)
                      ->orWhere('conversations.user_two', $userId);
            })
            ->select('messages.conversation_id', DB::raw('COUNT(*) as total'))
            ->groupBy('messages.conversation_id')
            ->orderByDesc('total')
            ->first();
    }

    public function lastMessage(int $conversationId)
    {
        return DB::table('messages')
            ->where('conversation_id', $conversationId)
            ->orderByDesc('created_at')
            ->first();
    }
}
