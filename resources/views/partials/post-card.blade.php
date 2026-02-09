<div class="space-y-6">

            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100" x-data="{ showComments: false }">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center overflow-hidden">
                                    @if($post->user->profile_picture)
                                    <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="w-full h-full object-cover">
                                    @else
                                    <i class="fas fa-user text-indigo-600"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900">{{ $post->user->first_name }} {{ $post->user->last_name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $post->user->industry ?? 'Member' }} • {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        @if(Auth::id() === $post->user_id)
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50" onclick="return confirm('Delete this post?')">
                                        <i class="fas fa-trash-alt mr-2"></i> Delete
                                    </button>
                                </form>
                                <form action="{{ route('posts.edit', $post->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50" onclick="return confirm('Delete this post?')">
                                        <i class="fas fa-trash-alt mr-2"></i> Edit
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($post->content)
                    <p class="text-gray-800 mb-4 whitespace-pre-wrap leading-relaxed">{{ $post->content }}</p>
                    @endif

                    @if($post->media_path)
                    <div class="rounded-xl overflow-hidden border border-gray-100 mt-3">
                        @if($post->media_type == 'video')
                        <video controls class="w-full max-h-[500px] object-cover bg-black">
                            <source src="{{ asset('storage/' . $post->media_path) }}">
                            Your browser does not support the video tag.
                        </video>
                        @else
                        <img src="{{ asset('storage/' . $post->media_path) }}" alt="Post image" class="w-full max-h-[500px] object-cover">
                        @endif
                    </div>
                    @endif
                </div>

                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <button type="button" onclick="toggleLike( {{ $post->id }}, this)" class="flex items-center space-x-2 transition-colors group {{ $post->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">

                        <div class="p-2 rounded-full group-hover:bg-red-50">
                            <i class="{{ $post->isLikedBy(Auth::user()) ? 'fas' : 'far' }} fa-heart text-lg"></i>
                        </div>

                        <span class="text-sm font-medium likes-count">
                            {{ $post->likes_count > 0 ? $post->likes_count : 'Like' }}
                        </span>
                    </button>

                    <button @click="showComments = !showComments" class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors group">
                        <div class="p-2 rounded-full group-hover:bg-indigo-50">
                            <i class="far fa-comment-alt text-lg"></i>
                        </div>
                        <span class="text-sm font-medium">
                            {{ $post->comments_count > 0 ? $post->comments_count : 'Comment' }}
                        </span>
                    </button>

                    <button class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors group">
                        <div class="p-2 rounded-full group-hover:bg-indigo-50">
                            <i class="fas fa-share text-lg"></i>
                        </div>
                        <span class="text-sm font-medium">Share</span>
                    </button>
                </div>

                <div x-show="showComments" class="bg-gray-50 border-t border-gray-100 p-4" style="display: none;">

                    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex items-start space-x-3 mb-6">
                        @csrf
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex-shrink-0 overflow-hidden">
                            @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                            <i class="fas fa-user text-indigo-600 p-2"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="text" name="content" placeholder="Write a comment..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                    </form>

                    @if($post->comments->count() > 0)
                    <div class="space-y-4">
                        @foreach($post->comments as $comment)
                        <div class="flex space-x-3">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex-shrink-0 overflow-hidden">
                                @if($comment->user->profile_picture)
                                <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" class="w-full h-full object-cover">
                                @else
                                <i class="fas fa-user text-gray-500 p-2"></i>
                                @endif
                            </div>
                            <div class="flex-1 bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                                <div class="flex justify-between items-start">
                                    <p class="text-xs font-bold text-gray-900">{{ $comment->user->first_name }} {{ $comment->user->last_name }}</p>
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">{{ $comment->content }}</p>

                                @if(Auth::id() === $comment->user_id)
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600">Delete</button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-center text-sm text-gray-500 italic py-2">No comments yet. Be the first!</p>
                    @endif
                </div>

            </div>

            


        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = document.querySelector('#userDropdown button');

            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });


        async function toggleLike(postId, buttonElement) {
            try {
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Update the count text
                    const countSpan = buttonElement.querySelector('.likes-count');
                    countSpan.innerText = data.likes_count > 0 ? data.likes_count : 'Like';

                    // Toggle colors/icons
                    const icon = buttonElement.querySelector('i');
                    if (data.status === 'liked') {
                        buttonElement.classList.add('text-red-500');
                        buttonElement.classList.remove('text-gray-500');
                        icon.classList.replace('far', 'fas');
                    } else {
                        buttonElement.classList.remove('text-red-500');
                        buttonElement.classList.add('text-gray-500');
                        icon.classList.replace('fas', 'far');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>