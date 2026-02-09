<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Welcome - {{ config('app.name', 'MyApp') }}</title>

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
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        .dropdown-enter {
            opacity: 0;
            transform: translateY(-10px);
        }
        
        .dropdown-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 200ms ease, transform 200ms ease;
        }
        
        .dropdown-exit-active {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 200ms ease, transform 200ms ease;
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50">
    <!-- Header -->
    <header class="absolute top-0 left-0 right-0 z-10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-800">{{ config('app.name', 'MyApp') }}</span>
                </div>

                <!-- User Icon (Always visible) -->
                <div class="relative">
                    <button 
                        id="user-menu-btn"
                        class="p-2 rounded-full hover:bg-white/50 transition-all duration-200 group"
                        aria-label="User menu"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        id="user-dropdown"
                        class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100 dropdown-enter"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="user-menu-btn"
                    >
                        <a 
                            href="{{ route('login') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center"
                            role="menuitem"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3 3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Sign In
                        </a>
                        <a 
                            href="{{ route('register') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center"
                            role="menuitem"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Create Account
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-4xl w-full mt-16">
            <!-- Decorative Background Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-10 text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Welcome to 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                        {{ config('app.name', 'MyApp') }}
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-12 max-w-2xl mx-auto">
                    Your journey to better productivity starts here. Join thousands of users who have transformed their workflow.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <!-- Get Started Button (Signup) -->
                    <a 
                        href="{{ route('register') }}"
                        class="group relative px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto"
                        id="get-started-btn"
                    >
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity duration-300"></div>
                        <span class="relative flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Get Started
                        </span>
                        <span class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-3/4 h-1 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-full opacity-75 group-hover:opacity-100 transition-opacity duration-300"></span>
                    </a>

                    <!-- Continue Journey Button (Login) -->
                    <a 
                        href="{{ route('login') }}"
                        class="group relative px-8 py-4 bg-white text-gray-800 font-semibold rounded-xl shadow-lg hover:shadow-xl border border-gray-200 hover:border-indigo-300 transform hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto"
                        id="continue-btn"
                    >
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 rounded-xl transition-opacity duration-300"></div>
                        <span class="relative flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Continue Your Journey
                        </span>
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto">
                    <div class="p-6 bg-white/50 backdrop-blur-sm rounded-xl border border-white/20">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Secure & Private</h3>
                        <p class="text-gray-600 text-sm">Your data is encrypted and protected with enterprise-grade security</p>
                    </div>
                    <div class="p-6 bg-white/50 backdrop-blur-sm rounded-xl border border-white/20">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Lightning Fast</h3>
                        <p class="text-gray-600 text-sm">Optimized for speed and performance across all devices</p>
                    </div>
                    <div class="p-6 bg-white/50 backdrop-blur-sm rounded-xl border border-white/20">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Collaborative</h3>
                        <p class="text-gray-600 text-sm">Work together with your team in real-time</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="absolute bottom-0 left-0 right-0 py-6 text-center">
        <p class="text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'MyApp') }}. All rights reserved.
        </p>
    </footer>

    <!-- Vanilla JavaScript -->
    <script>
        class AuthWelcomePage {
            constructor() {
                this.isDropdownOpen = false;
                this.dropdownAnimationTimeout = null;
                this.init();
            }

            init() {
                this.cacheElements();
                this.bindEvents();
                this.setupAnalytics();
                this.initializeTooltips();
            }

            cacheElements() {
                this.userMenuBtn = document.getElementById('user-menu-btn');
                this.userDropdown = document.getElementById('user-dropdown');
                this.getStartedBtn = document.getElementById('get-started-btn');
                this.continueBtn = document.getElementById('continue-btn');
            }

            bindEvents() {
                this.bindUserMenuEvents();
                this.bindButtonHoverEffects();
                this.bindKeyboardEvents();
                this.bindDocumentClick();
            }

            bindUserMenuEvents() {
                if (!this.userMenuBtn || !this.userDropdown) return;

                this.userMenuBtn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    this.toggleDropdown();
                });

                this.userMenuBtn.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        this.toggleDropdown();
                    } else if (event.key === 'ArrowDown' && !this.isDropdownOpen) {
                        event.preventDefault();
                        this.toggleDropdown();
                    }
                });

                this.userDropdown.addEventListener('click', (event) => {
                    event.stopPropagation();
                });
            }

            bindButtonHoverEffects() {
                const buttons = [this.getStartedBtn, this.continueBtn];
                
                buttons.forEach(button => {
                    if (!button) return;
                    
                    const addHover = (btn) => {
                        btn.addEventListener('mouseenter', () => {
                            btn.style.transform = 'translateY(-4px)';
                        });
                        
                        btn.addEventListener('mouseleave', () => {
                            btn.style.transform = 'translateY(0)';
                        });
                        
                        btn.addEventListener('focus', () => {
                            btn.style.transform = 'translateY(-4px)';
                        });
                        
                        btn.addEventListener('blur', () => {
                            btn.style.transform = 'translateY(0)';
                        });
                    };
                    
                    addHover(button);
                });
            }

            bindKeyboardEvents() {
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && this.isDropdownOpen) {
                        this.toggleDropdown();
                        this.userMenuBtn?.focus();
                    }
                });
            }

            bindDocumentClick() {
                document.addEventListener('click', (event) => {
                    if (this.isDropdownOpen && 
                        !this.userMenuBtn.contains(event.target) && 
                        !this.userDropdown.contains(event.target)) {
                        this.toggleDropdown();
                    }
                });
            }

            toggleDropdown() {
                this.isDropdownOpen = !this.isDropdownOpen;
                
                if (this.dropdownAnimationTimeout) {
                    clearTimeout(this.dropdownAnimationTimeout);
                }
                
                if (this.isDropdownOpen) {
                    this.showDropdown();
                } else {
                    this.hideDropdown();
                }
            }

            showDropdown() {
                this.userDropdown.classList.remove('hidden');
                this.userDropdown.classList.remove('dropdown-exit-active');
                this.userDropdown.classList.add('dropdown-enter-active');
                this.userMenuBtn.setAttribute('aria-expanded', 'true');
                
                // Focus first dropdown item
                setTimeout(() => {
                    const firstItem = this.userDropdown.querySelector('a');
                    firstItem?.focus();
                }, 10);
            }

            hideDropdown() {
                this.userDropdown.classList.remove('dropdown-enter-active');
                this.userDropdown.classList.add('dropdown-exit-active');
                this.userMenuBtn.setAttribute('aria-expanded', 'false');
                
                this.dropdownAnimationTimeout = setTimeout(() => {
                    this.userDropdown.classList.add('hidden');
                    this.userDropdown.classList.remove('dropdown-exit-active');
                }, 200);
            }

            setupAnalytics() {
                if (this.getStartedBtn) {
                    this.getStartedBtn.addEventListener('click', () => {
                        console.log('Get Started button clicked');
                        // Your analytics code here
                    });
                }
                
                if (this.continueBtn) {
                    this.continueBtn.addEventListener('click', () => {
                        console.log('Continue Journey button clicked');
                        // Your analytics code here
                    });
                }
            }

            initializeTooltips() {
                const buttons = document.querySelectorAll('button, a');
                buttons.forEach(button => {
                    if (!button.hasAttribute('aria-label') && !button.textContent.trim()) {
                        const title = button.getAttribute('title');
                        if (title) {
                            button.setAttribute('aria-label', title);
                        }
                    }
                });
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new AuthWelcomePage();
        });
    </script>
</body>
</html>