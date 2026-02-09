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