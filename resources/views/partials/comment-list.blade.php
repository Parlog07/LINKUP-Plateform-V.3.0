<!-- Comment Input -->
<form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mb-4">
    @csrf
    <div class="flex items-center space-x-2">
        <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex-shrink-0 overflow-hidden border-2 border-indigo-50">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-full h-full object-cover">
            @else
                <i class="fas fa-user text-indigo-600 text-xs flex items-center justify-center h-full"></i>
            @endif
        </div>
        <div class="flex-1 relative">
            <input type="text" 
                   name="content" 
                   placeholder="Write a comment..." 
                   class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white transition-all text-[13px] hover:border-gray-400"
                   required>
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-full hover:from-indigo-700 hover:to-purple-700 transition-all flex items-center justify-center hover:scale-105">
                <i class="fas fa-paper-plane text-[10px]"></i>
            </button>
        </div>
    </div>
</form>

<!-- Comments List -->
<div class="space-y-3">
    @forelse($post->comments as $comment)
        <div class="flex space-x-2 group">
            <a href="{{ route('profile.show', $comment->user->id) }}" class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full overflow-hidden flex-shrink-0 border-2 border-gray-100 hover:border-indigo-300 transition-all hover:scale-105">
                @if($comment->user->profile_picture)
                    <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user text-indigo-600 text-xs flex items-center justify-center h-full"></i>
                @endif
            </a>
            <div class="flex-1 min-w-0">
                <div class="bg-gray-100 p-2.5 rounded-2xl group-hover:bg-gray-200 transition-colors">
                    <div class="flex items-center justify-between mb-0.5">
                        <a href="{{ route('profile.show', $comment->user->id) }}" class="text-xs font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                            {{ $comment->user->first_name }} {{ $comment->user->last_name }}
                        </a>
                        <span class="text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-[13px] text-gray-700 leading-relaxed">{{ $comment->content }}</p>
                </div>
                
                <!-- Comment Actions -->
                <div class="flex items-center space-x-3 mt-1 px-2.5">
                    <button class="text-[11px] text-gray-500 hover:text-red-500 font-medium transition-colors">
                        <i class="far fa-heart text-[10px] mr-0.5"></i>Like
                    </button>
                    <button class="text-[11px] text-gray-500 hover:text-indigo-600 font-medium transition-colors">
                        Reply
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-6">
            <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-comments text-gray-400 text-base"></i>
            </div>
            <p class="text-gray-500 text-xs font-medium">No comments yet</p>
            <p class="text-gray-400 text-[11px] mt-0.5">Be the first to comment!</p>
        </div>
    @endforelse
</div>

@if($post->comments->count() > 3)
    <div class="mt-3 text-center">
        <button class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold transition-colors hover:underline">
            <i class="fas fa-chevron-down mr-1 text-[10px]"></i>
            View more comments
        </button>
    </div>
@endif