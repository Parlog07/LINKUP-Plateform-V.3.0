<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostAdminController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.index', compact('posts'));
    }

    public function updateStatus(Request $request, Post $post)
{
    $request->validate([
        'status' => 'required|in:approved,pending,blocked',
    ]);

    if ($request->status === 'blocked') {
        $post->delete(); // permanent delete
        return back()->with('success', 'Post blocked and deleted successfully!');
    }

    $post->update([
        'status' => $request->status,
        'moderation_reason' => $request->moderation_reason,
    ]);

    return back()->with('success', 'Post status updated successfully!');
}

}
