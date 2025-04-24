<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NPC - Integrated Computer Society') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    <style>
        /* Navigation and general styles */
        .nav-link {
            @apply inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium text-gray-600 hover:text-indigo-600 hover:border-indigo-300 transition duration-150 ease-in-out;
        }
        
        .nav-link i {
            @apply mr-2;
        }
        
        .nav-link.active {
            @apply border-indigo-500 text-indigo-600;
        }
    </style>
    @yield('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="flex items-center">
                                <div class="flex items-center bg-indigo-600 text-white rounded-md px-3 py-1.5">
                                    <span class="text-lg font-bold">NPC-ICS</span>
                                </div>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-2 sm:-my-px sm:ml-6 sm:flex">
                            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->is('/') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-home mr-2"></i> Dashboard
                            </a>
                            <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->routeIs('members.*') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-users mr-2"></i> Members
                            </a>
                            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->routeIs('events.*') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-calendar-alt mr-2"></i> Events
                            </a>
                            <a href="{{ route('announcements.index') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->routeIs('announcements.*') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-bullhorn mr-2"></i> Announcements
                            </a>
                            <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->routeIs('payments.*') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-credit-card mr-2"></i> Payments
                            </a>
                            <a href="{{ route('letters.index') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->routeIs('letters.*') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-envelope mr-2"></i> Letters
                            </a>
                            <a href="{{ route('about-us') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->routeIs('about-us') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-info-circle mr-2"></i> About Us
                            </a>
                        </div>
                    </div>

                    <!-- Right Side Navigation -->
                    <div class="hidden sm:flex sm:items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border-b-2 {{ request()->routeIs('admin.*') ? 'border-indigo-500 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:border-indigo-300' }} text-sm transition duration-150 ease-in-out">
                            <i class="fas fa-user-shield mr-2"></i> Admin
                        </a>
                    </div>

                    <!-- Hamburger Menu -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button id="toggleMobileMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-indigo-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-indigo-600 transition duration-150 ease-in-out">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, toggle classes based on menu state -->
            <div id="mobileMenu" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ url('/') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('/') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('members.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('members.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                        <i class="fas fa-users mr-2"></i> Members
                    </a>
                    <a href="{{ route('events.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('events.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                        <i class="fas fa-calendar-alt mr-2"></i> Events
                    </a>
                    <a href="{{ route('announcements.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('announcements.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                        <i class="fas fa-bullhorn mr-2"></i> Announcements
                    </a>
                    <a href="{{ route('payments.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('payments.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                        <i class="fas fa-credit-card mr-2"></i> Payments
                    </a>
                    <a href="{{ route('letters.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('letters.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                        <i class="fas fa-envelope mr-2"></i> Letters
                    </a>
                    <a href="{{ route('about-us') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('about-us') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                        <i class="fas fa-info-circle mr-2"></i> About Us
                    </a>
                </div>
                
                <!-- Admin Access (Mobile) -->
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-gray-900">Administration</div>
                            <div class="text-sm font-medium leading-none text-gray-500 mt-1">Manage your club</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50 hover:border-indigo-300' }} text-base focus:outline-none transition duration-150 ease-in-out">
                            <i class="fas fa-user-shield mr-2"></i> Admin Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @yield('scripts')

    <script>
        // Toggle mobile menu
        const mobileMenuBtn = document.getElementById('toggleMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html> 