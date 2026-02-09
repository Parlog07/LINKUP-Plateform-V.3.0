@auth
    <script>
        window.currentUserId = {{ auth()->id() }};
    </script>
@endauth


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - Linkup</title>

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

    @vite(['resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

                        <a href="{{ route('messages.show', Auth::id()) }}" class="text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent px-3 py-2 text-sm font-medium transition-colors">
                            Messages
                        </a>
                        <a href="{{ route('notifications') }}"
                            class="{{ request()->routeIs('notifications') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700 border-b-2 border-transparent' }} px-3 py-2 text-sm font-medium transition-colors">
                            Notifications
                        </a>

                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="relative hidden md:block">
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search users..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64 transition-shadow">
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
                                <i class="fas fa-user-edit text-indigo-500 mr-3 w-4"></i>
                                Edit Profile
                            </a>

                            <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-cog text-gray-400 mr-3 w-4"></i>
                                Settings
                            </a>

                            @auth
                            @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-cog text-gray-400 mr-3 w-4"></i>
                                Admin
                            </a>
                            @endif
                            @endauth


                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ Auth::user()->first_name ?? Auth::user()->username ?? 'User' }}!</h1>
            <p class="text-gray-600">Discover and connect with professionals on Linkup</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Connections</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">42</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">5</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">New This Week</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">12</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bolt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Discover People</h2>
                    <div class="flex space-x-2">
                        <button id="refreshBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i> Refresh
                        </button>
                        <form method="GET" action="{{ route('dashboard') }}" class="flex">
                            @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by: Recently Added</option>
                                <option value="alphabetical" {{ request('sort') == 'alphabetical' ? 'selected' : '' }}>Sort by: Alphabetical</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if($users->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($users as $user)
                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center overflow-hidden">
                                        @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                            alt="{{ $user->first_name }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <i class="fas fa-user text-indigo-600 text-2xl"></i>
                                        @endif
                                    </div>
                                    @if($user->is_online)
                                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $user->username }}</p>
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-map-marker-alt text-gray-400 text-xs mr-1"></i>
                                        <span class="text-xs text-gray-600">{{ $user->location ?? 'Unknown location' }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($user->friend_request_pending)
                            <div class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg">
                                <i class="fas fa-clock mr-2"></i>Pending
                            </div>
                            @elseif($user->is_friend)
                            <div class="px-4 py-2 bg-green-100 text-green-700 rounded-lg">
                                <i class="fas fa-check mr-2"></i>Connected
                            </div>
                            @else
                            <form action="{{ route('connections.store', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    <i class="fas fa-user-plus mr-2"></i>Add Friend
                                </button>
                            </form>
                            @endif
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-700 text-sm line-clamp-2">
                                {{ $user->bio ?? 'No bio available.' }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($user->interests && count(json_decode($user->interests)) > 0)
                            @foreach(json_decode($user->interests) as $interest)
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs">
                                {{ $interest }}
                            </span>
                            @endforeach
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                No interests listed
                            </span>
                            @endif
                        </div>

                        <div class="flex justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <p class="font-bold text-gray-900">{{ $user->connections_count ?? 0 }}</p>
                                <p>Connections</p>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-gray-900">{{ $user->mutual_friends ?? 0 }}</p>
                                <p>Mutual</p>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-gray-900">{{ $user->industry ?? 'N/A' }}</p>
                                <p>Industry</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">
                    {{ $users->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No users found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find anyone matching your search.</p>
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Clear Search
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">
                &copy; {{ date('Y') }} Linkup. All rights reserved.
                <a href="#" class="text-indigo-600 hover:text-indigo-500 ml-2">Privacy Policy</a>
                •
                <a href="#" class="text-indigo-600 hover:text-indigo-500 ml-2">Terms of Service</a>
            </p>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Refresh button functionality
            const refreshBtn = document.getElementById('refreshBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Refreshing...';
                    this.disabled = true;
                    window.location.href = "{{ route('dashboard') }}";
                });
            }
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>


</body>

</html>