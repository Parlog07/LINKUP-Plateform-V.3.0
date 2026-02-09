<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Global Feed - Linkup</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-800">Linkup</span>
                    </a>

                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent' }} px-3 py-2 text-sm font-medium transition-colors">
                            Discover
                        </a>
                        <a href="{{ route('connections') }}"
                            class="{{ request()->routeIs('connections') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent' }} px-3 py-2 text-sm font-medium transition-colors">
                            Connections
                        </a>

                        <a href="{{ route('posts.store') }}"
                            class="{{ request()->routeIs('posts.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent' }} px-3 py-2 text-sm font-medium transition-colors">
                            Posts
                        </a>

                        <a href="{{ route('posts.feeds') }}"
                            class="{{ request()->routeIs('posts.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent' }} px-3 py-2 text-sm font-medium transition-colors">
                            Feed
                        </a>
                        <a href="#" class="text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent px-3 py-2 text-sm font-medium transition-colors">
                            Messages
                        </a>
                        <a href="#" class="text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent px-3 py-2 text-sm font-medium transition-colors">
                            Notifications
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="relative hidden md:block">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64">
                        <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <div class="relative" id="userDropdown">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 focus:outline-none hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center overflow-hidden border border-indigo-50">
                                @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-full h-full object-cover">
                                @else
                                <i class="fas fa-user text-indigo-600"></i>
                                @endif
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->first_name }}</span>
                            <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
                        </button>

                        <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 hidden border border-gray-200 ring-1 ring-black ring-opacity-5">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-user-edit text-indigo-500 mr-3 w-4"></i> Edit Profile
                            </a>
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3 w-4"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-gray-100">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center overflow-hidden">
                        @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                        <i class="fas fa-user text-indigo-600"></i>
                        @endif
                    </div>
                </div>
                <div class="flex-1 w-full" x-data="{ fileName: '' }">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea name="content" rows="2"
                            class="w-full border-none focus:ring-0 text-gray-700 text-lg resize-none p-0 placeholder-gray-400 bg-transparent"
                            placeholder="What's happening, {{ Auth::user()->first_name }}?"></textarea>

                        <div x-show="fileName" class="text-xs text-indigo-600 mt-2 flex items-center bg-indigo-50 w-fit px-2 py-1 rounded">
                            <i class="fas fa-file-upload mr-2"></i>
                            <span x-text="fileName"></span>
                        </div>

                        <div class="mt-4 flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex space-x-2">
                                <label class="cursor-pointer flex items-center px-3 py-2 rounded-full text-gray-500 hover:bg-gray-100 transition-colors group">
                                    <i class="fas fa-image text-xl mr-2 text-green-500 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-sm font-medium">Media</span>
                                    <input type="file" name="media" class="hidden" @change="fileName = $event.target.files[0].name">
                                </label>
                            </div>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-full hover:bg-indigo-700 transition-colors shadow-sm">
                                Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @if(session('message'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('message') }}</p>
                </div>
            </div>
        </div>
        @endif


        @error('content')
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0"><i class="fas fa-check-circle text-red-400"></i></div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ $message }}</p>
                </div>
            </div>
        </div>
        @enderror


        @foreach($posts as $post)
        @if ($post->status === 'pending' && auth()->id() === $post->user_id)
        <span class="text-yellow-600 text-sm">
            ⏳ Under review
        </span>
        @endif
@endforeach

        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="space-y-6">
            @foreach($posts as $post)
            @if($post->status === 'approved')
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
            @endif
            @endforeach

            @if($posts->count() == 0)
            <div class="text-center py-12 bg-white rounded-xl border border-gray-200 border-dashed">
                <i class="fas fa-stream text-gray-300 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No posts yet</h3>
                <p class="text-gray-500">Be the first to share something!</p>
            </div>
            @endif

            <div class="mt-6">
                {{ $posts->links() }}
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
</body>

</html>