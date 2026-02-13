<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'body',
        'conversation_id',
        'expires_at',
        'attachment_path',
        'attachment_name',
        'attachment_mime',
        'attachment_size',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'attachment_size' => 'integer',
    ];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isImageAttachment(): bool
    {
        if (! $this->attachment_path) {
            return false;
        }

        $mime = strtolower((string) $this->attachment_mime);
        if (str_starts_with($mime, 'image/')) {
            return true;
        }

        $ext = strtolower(pathinfo($this->attachment_name ?: $this->attachment_path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }

    public function isVideoAttachment(): bool
    {
        if (! $this->attachment_path) {
            return false;
        }

        $mime = strtolower((string) $this->attachment_mime);
        if (str_starts_with($mime, 'video/')) {
            return true;
        }

        $ext = strtolower(pathinfo($this->attachment_name ?: $this->attachment_path, PATHINFO_EXTENSION));
        return in_array($ext, ['mp4', 'mov', 'webm', 'm4v', 'avi', 'mkv'], true);
    }
}
