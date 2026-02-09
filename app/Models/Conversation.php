<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    protected $fillable = [
        'user_one',
        'user_two',
    ];

        protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    /**
     * Return the "other" participant for the authenticated user
     */
    public function otherUser(int $authId): User
    {
        return $this->user_one === $authId
            ? $this->userTwo
            : $this->userOne;
    }



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}

