<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostActionController extends Controller
{
public function toggleLike($postId) {
    $post = Post::findOrFail($postId);
    $userId = Auth::id();

    $existingLike = PostAction::where('user_id', $userId)
        ->where('post_id', $postId)
        ->where('action_type', 'like')
        ->first(); // Added ->first() fix here

    if ($existingLike) {
        $existingLike->delete();
        $status = 'unliked';
    } else {
        PostAction::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'action_type' => 'like'
        ]);
        $status = 'liked';
    }

    // Return JSON instead of back()
    return response()->json([
        'status' => $status,
        'likes_count' => $post->likes()->count()
    ]);
}

    public function storeComment(Request $request, $postId){
        $request->validate([
            'content' => 'required|string|max:2500'
        ]);

        Post::findOrFail($postId);

        PostAction::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'action_type' => 'comment',
            'content' => $request->content
        ]);

        return back()->with('success', 'Comment posted');
    }

    public function destroyComment($id){
        $comment = PostAction::findOrFail($id);

        if($comment->user_id !== Auth::id()) {
            abort(403, 'unauthorized action');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted');
    }
}
