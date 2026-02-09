@extends('layouts.app')

@section('content')
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Edit Your Profile</h2>
                <p class="text-gray-600 mt-1">Update your personal information and preferences</p>
            </div>

            @if(session('success'))
                <div class="m-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-green-700">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <p class="font-medium text-red-800">Please fix the following errors:</p>
                            <ul class="mt-2 text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-8" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Picture</h3>
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <div class="w-32 h-32 bg-indigo-100 rounded-full flex items-center justify-center overflow-hidden">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-indigo-600 text-5xl"></i>
                                @endif
                            </div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-indigo-700 transition-colors">
                                <label for="profile_picture" class="cursor-pointer">
                                    <i class="fas fa-camera text-white"></i>
                                </label>
                                <input type="file" 
                                       id="profile_picture" 
                                       name="profile_picture" 
                                       class="hidden"
                                       accept="image/*">
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">
                                Upload a clear photo of yourself. JPG, PNG, or GIF formats are allowed.
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Maximum file size: 5MB
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name
                            </label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name', auth()->user()->first_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   required>
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name
                            </label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name', auth()->user()->last_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   required>
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username', auth()->user()->username) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   required>
                            <p class="mt-2 text-xs text-gray-500">This will be your unique identifier on Linkup</p>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   required>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">About You</h3>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                            Bio
                        </label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                  placeholder="Tell us about yourself...">{{ old('bio', auth()->user()->bio) }}</textarea>
                        <p class="mt-2 text-xs text-gray-500">
                            A short bio helps others get to know you better. Max 250 characters.
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location
                            </label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', auth()->user()->location) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="City, Country">
                        </div>

                        <div>
                            <label for="industry" class="block text-sm font-medium text-gray-700 mb-2">
                                Industry
                            </label>
                            <select id="industry" 
                                    name="industry"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">Select an industry</option>
                                <option value="Technology" {{ old('industry', auth()->user()->industry) == 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Healthcare" {{ old('industry', auth()->user()->industry) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                <option value="Finance" {{ old('industry', auth()->user()->industry) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="Education" {{ old('industry', auth()->user()->industry) == 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Marketing" {{ old('industry', auth()->user()->industry) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Design" {{ old('industry', auth()->user()->industry) == 'Design' ? 'selected' : '' }}>Design</option>
                                <option value="Other" {{ old('industry', auth()->user()->industry) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Interests</h3>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">
                            Select interests that match your professional background and hobbies
                        </p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $allInterests = ['Programming', 'Design', 'Startups', 'Tech', 'Marketing', 'AI', 'Blockchain', 'Cybersecurity', 'Cloud Computing', 'Data Science', 'Mobile Development', 'Web Development', 'UI/UX', 'Photography', 'Music', 'Sports', 'Travel', 'Cooking', 'Reading', 'Gaming'];
                                $userInterests = json_decode(auth()->user()->interests ?? '[]', true);
                            @endphp
                            
                            @foreach($allInterests as $interest)
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="interests[]" 
                                           value="{{ $interest }}"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                           {{ in_array($interest, $userInterests) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm text-gray-700">{{ $interest }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 flex justify-end space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
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
    </script>
</body>
</html>