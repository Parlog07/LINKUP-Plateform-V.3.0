<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Linkup</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
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
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <a href="{{ route('welcome') }}" class="flex items-center justify-center mb-8">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="ml-2 text-2xl font-bold text-gray-800">Linkup</span>
                </a>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Create your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Or
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        sign in to existing account
                    </a>
                </p>
            </div>
            
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 font-medium text-sm text-red-600">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name Fields -->
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                First Name
                            </label>
                            <input id="first_name" name="first_name" type="text" required
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                   placeholder="John"
                                   value="{{ old('first_name') }}">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name
                            </label>
                            <input id="last_name" name="last_name" type="text" required
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                   placeholder="Doe"
                                   value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Username
                        </label>
                        <input id="username" name="username" type="text" required autofocus
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                               placeholder="john_doe"
                               value="{{ old('username') }}">
                        <p class="mt-1 text-xs text-gray-500">Only letters, numbers, and underscores</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input id="email" name="email" type="email" required
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                               placeholder="john@example.com"
                               value="{{ old('email') }}">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm pr-10"
                                   placeholder="Create a password"
                                   oninput="checkPasswordMatch()">
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePasswordVisibility('password')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters with letters and numbers</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm pr-10"
                                   placeholder="Confirm your password"
                                   oninput="checkPasswordMatch()">
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePasswordVisibility('password_confirmation')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <div id="passwordMatch" class="mt-1 text-sm hidden">
                            <span class="text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Passwords do not match
                            </span>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                               {{ old('terms') ? 'checked' : '' }}>
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            I agree to the 
                            <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms of Service</a>
                            and 
                            <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" id="submitButton"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility(fieldId) {
            const input = document.getElementById(fieldId);
            const button = input.parentElement.querySelector('button');
            const icon = button.querySelector('svg');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
        
        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('password')?.value;
            const confirmPassword = document.getElementById('password_confirmation')?.value;
            const matchIndicator = document.getElementById('passwordMatch');
            const submitButton = document.getElementById('submitButton');
            
            if (!password || !confirmPassword) return;
            
            if (confirmPassword.length === 0) {
                matchIndicator.classList.add('hidden');
                submitButton.disabled = false;
                return;
            }
            
            if (password === confirmPassword) {
                matchIndicator.classList.add('hidden');
                submitButton.disabled = false;
            } else {
                matchIndicator.classList.remove('hidden');
                submitButton.disabled = true;
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const confirmInput = document.getElementById('password_confirmation');
            
            if (confirmInput && confirmInput.value) {
                checkPasswordMatch();
            }
            
            // Form submission handler
            const form = document.getElementById('registerForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const terms = document.getElementById('terms');
                    if (!terms.checked) {
                        e.preventDefault();
                        alert('Please agree to the Terms of Service and Privacy Policy.');
                        return;
                    }
                    
                    const submitButton = document.getElementById('submitButton');
                    submitButton.innerHTML = 'Creating Account...';
                    submitButton.disabled = true;
                });
            }
        });
    </script>
</body>
</html>