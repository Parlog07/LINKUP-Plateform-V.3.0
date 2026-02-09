<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FriendRequest; // Still needed for the index page checks
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\FriendRequestSent;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        
        // 1. Base Query
        $query = User::where('id', '!=', $currentUser->id);

        // 2. Search Logic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Sort Logic
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'alphabetical':
                    $query->orderBy('first_name')->orderBy('last_name');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest(); 
        }

        // 4. Paginate
        $users = $query->paginate(9)->withQueryString();

        // 5. Check Status Logic (This usually stays in Controller or a Service)
        foreach ($users as $user) {
            $user->friend_request_pending = FriendRequest::where('request_sender_id', $currentUser->id)
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists();

            $user->is_friend = FriendRequest::where('status', 'accepted')
                ->where(function($q) use ($currentUser, $user) {
                    $q->where(function($sub) use ($currentUser, $user) {
                        $sub->where('request_sender_id', $currentUser->id)->where('user_id', $user->id);
                    })->orWhere(function($sub) use ($currentUser, $user) {
                        $sub->where('request_sender_id', $user->id)->where('user_id', $currentUser->id);
                    });
                })
                ->exists();
        }

        return view('dashboard', [
            'users' => $users,
            'currentUser' => $currentUser
        ]);
    }

    public function connections()
    {
        $user = Auth::user();

        // Using the Model methods we made earlier!
        $receivedRequests = $user->getReceivedRequests();
        $sentRequests     = $user->getSentRequests();
        $friends          = $user->getFriends();

        return view('connections', compact('receivedRequests', 'sentRequests', 'friends'));
    }
    

public function sendFriendRequest($id)
{
    $user = Auth::user();

    $success = $user->sendFriendRequestTo($id);

    if (!$success) {
        return redirect()->back()->with('warning', 'Request already sent.');
    }

    $recipient = User::findOrFail($id);
    $senderName = $user->username;

    event(new FriendRequestSent($senderName, $recipient->id));

    return redirect()->back()->with('success', 'Friend request sent!');
}

    public function acceptRequest($id)
    {
        Auth::user()->acceptFriendRequest($id);
        return redirect()->back()->with('success', 'You are now connected!');
    }

    public function rejectRequest($id)
    {
        Auth::user()->declineFriendRequest($id);
        return redirect()->back()->with('success', 'Request declined.');
    }

    public function cancelRequest($id)
    {
        Auth::user()->cancelFriendRequest($id);
        return redirect()->back()->with('success', 'Request cancelled.');
    }
    
    public function removeConnection($id)
    {
        Auth::user()->removeFriend($id);
        return redirect()->back()->with('success', 'Connection removed.');
    }
}