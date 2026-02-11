<?php

namespace App\Http\Controllers;

use App\Models\InviteToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\FriendRequest;

class FriendInviteController extends Controller
{
    public function generate(Request $request)
    {
        InviteToken::where('inviter_id', auth()->id())
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->delete();
        $token = \Illuminate\Support\Str::uuid()->toString();

        InviteToken::create([
            'inviter_id' => auth()->id(),
            'token' => $token,
            'expires_at' => now()->addHour(),
        ]);

        $link = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'friends.invite.accept',
            now()->addHour(),
            ['token' => $token]
        );

        return back()->with('invite_link', $link);
    }


public function accept(Request $request)
{
    $tokenValue = $request->query('token');

    $invite = InviteToken::where('token', $tokenValue)->firstOrFail();

    if ($invite->used_at) {
        abort(410, 'Invite already used');
    }

    if (now()->gt($invite->expires_at)) {
        abort(410, 'Invite expired');
    }

    $inviterId = $invite->inviter_id;
    $receiverId = auth()->id();        

    if ($inviterId === $receiverId) {
        abort(400, 'You cannot add yourself');
    }

    $existing = FriendRequest::where(function ($q) use ($inviterId, $receiverId) {
            $q->where('user_id', $receiverId)
            ->where('request_sender_id', $inviterId);
        })
        ->orWhere(function ($q) use ($inviterId, $receiverId) {
            $q->where('user_id', $inviterId)
            ->where('request_sender_id', $receiverId);
        })
        ->first();

    if ($existing) {
        $existing->update(['status' => 'accepted']);
    } else {
        FriendRequest::create([
            'user_id' => $receiverId,
            'request_sender_id' => $inviterId,
            'status' => 'accepted',
        ]);
    }

    $invite->update(['used_at' => now()]);

    return redirect()->route('connections') 
        ->with('success', 'Friend added successfully!');
}

}

