<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ContentModerationService;
use App\Models\FriendRequest;

class PostController extends Controller
{
    public function index(){
        $posts = Post::with(['user', 'likes', 'comments.user'])
                    ->withCount('likes', 'comments')
                    ->latest()
                    ->paginate(10);
                    
        return view('posts.index', compact('posts'));
    }


public function feeds()
{
    $userId = Auth::id();


    $friendIds = FriendRequest::where('status', 'accepted')
        ->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('request_sender_id', $userId);
        })
        ->get()
        ->map(function ($friendship) use ($userId) {
            return $friendship->user_id === $userId
                ? $friendship->request_sender_id
                : $friendship->user_id;
        })
        ->unique()
        ->values();


    $posts = Post::with(['user', 'likes', 'comments.user'])
        ->withCount('likes', 'comments')
        ->whereIn('user_id', $friendIds)
        ->latest()
        ->paginate(10);

    return view('posts.feeds', compact('posts'));
}


    public function store(Request $request, ContentModerationService $moderate){
        $request->validate([
            'content' => 'required_without:media|nullable|string|max:2500',
            'media' => 'nullable|mimes:jpg,png,jpeg,gif,mp4,mov,avi,webm,mkv|max:51200' // Increased to 50MB for videos
        ]);

        $result = $moderate->analyze($request->content);




        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content;
        $post->status = $result['status'];
        $post->moderation_reason = $result['reason'];

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('posts', 'public');
            $post->media_path = $path;

            // Better MIME type detection
            $mime = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Check if it's a video
            if (str_contains($mime, 'video') || in_array($extension, ['mp4', 'mov', 'avi', 'webm', 'mkv'])) {
                $post->media_type = 'video';
            } else {
                $post->media_type = 'image';
            }
        }

        $post->save();

                if ($result['status'] === 'blocked') {
    return back()->withErrors([
        'content' => 'Your post contains content that violates our guidelines.',
    ]);
}

        $message = match ($result['status']) {
    'approved' => 'Your post has been published successfully.',
    'pending'  => 'Your post has been submitted and is under review.',
    default    => 'Your post has been processed.',
};

return redirect()
    ->route('posts.index')
    ->with('status', $result['status'])
    ->with('message', $message);

    }

    public function edit(Request $request, ContentModerationService $moderate){
    $post = new Post();

        if (Auth::id() !== $post->user_id){
            abort(403);
        }

        $result = $moderate->analyze($request->content);

        $updateContent = [
            'content' => $request->content,
            'status' => $result['status'],
            'moderation_reason' => $result['reason']
        ];

        $post->update($updateContent);

        return redirect()->route('posts.index')
            ->with('success', $post->status === 'aproved' ? 'Post updated successfully!' : 'Post updated and sent for moderation');
    }

    public function destroy(Post $post){
        if(Auth::id() !== $post->user_id){
            abort(403);
        }

        // Delete media file if exists
        if ($post->media_path && Storage::disk('public')->exists($post->media_path)) {
            Storage::disk('public')->delete($post->media_path);
        }

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted');
    }

    public function notifications()
{
    $posts = Post::where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('notifications.index', compact('posts'));
}

}