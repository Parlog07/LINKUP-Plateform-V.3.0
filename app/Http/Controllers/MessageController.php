<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}