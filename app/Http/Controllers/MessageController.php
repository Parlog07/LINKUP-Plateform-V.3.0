<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        
        // CRITICAL: Prevent messaging yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('dashboard')
                ->with('error', 'You cannot message yourself.');
        }
        
        return view('messages.show', compact('user'));
    }

    public function downloadAttachment(Message $message)
    {
        $this->authorizeAttachmentAccess($message);

        if (! $message->attachment_path || ! Storage::disk('public')->exists($message->attachment_path)) {
            abort(404, 'Attachment not found.');
        }

        return Storage::disk('public')->download(
            $message->attachment_path,
            $message->attachment_name ?? basename($message->attachment_path)
        );
    }

    public function previewAttachment(Message $message)
    {
        $userId = auth()->id();
        $conversation = $message->conversation;

        if (! $conversation) {
            abort(404);
        }

        $isParticipant = (int) $conversation->user_one === (int) $userId
            || (int) $conversation->user_two === (int) $userId;

        if (! $isParticipant) {
            abort(403);
        }

        if (! $message->attachment_path || ! Storage::disk('public')->exists($message->attachment_path)) {
            abort(404, 'Attachment not found.');
        }

        return response()->file(Storage::disk('public')->path($message->attachment_path), [
            'Content-Type' => $message->attachment_mime ?: 'application/octet-stream',
        ]);
    }

    private function authorizeAttachmentAccess(Message $message): void
    {
        $userId = auth()->id();
        $conversation = $message->conversation;

        if (! $conversation) {
            abort(404);
        }

        $isParticipant = (int) $conversation->user_one === (int) $userId
            || (int) $conversation->user_two === (int) $userId;

        if (! $isParticipant) {
            abort(403);
        }
    }
}
