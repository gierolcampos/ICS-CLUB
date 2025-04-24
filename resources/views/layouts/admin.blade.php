<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel') - Club Management System</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CountUp.js for animated counters -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.6.0/countUp.min.js"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease;
            @apply bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen;
        }
        
        /* Dark mode styles */
        .dark {
            @apply bg-gray-900 text-gray-100;
        }
        
        /* Admin card styling */
        .admin-card {
            @apply bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300;
        }
        
        /* Status badges */
        .status-badge {
            @apply text-xs font-medium px-2 py-0.5 rounded-full;
            @apply bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400;
        }
        
        /* Sidebar styling */
        .sidebar-link {
            @apply flex items-center text-gray-500 dark:text-gray-400 py-3 px-4 rounded-lg transition-all duration-300;
        }
        
        .sidebar-link:hover {
            @apply bg-gray-100 dark:bg-gray-700/50 text-gray-900 dark:text-white transform;
        }
        
        .sidebar-link.active {
            @apply bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-medium;
        }
        
        .sidebar-link .icon {
            @apply flex-shrink-0 w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 transition-colors;
        }
        
        .sidebar-link:hover .icon {
            @apply text-indigo-600 dark:text-indigo-400;
        }
        
        .sidebar-link.active .icon {
            @apply text-indigo-600 dark:text-indigo-400;
        }
        
        /* Enhanced tooltip styles */
        .tooltip {
            @apply relative;
        }
        
        .tooltip .tooltip-text {
            @apply absolute invisible z-50 bg-gray-900 text-xs text-white rounded-md opacity-0;
            width: max-content;
            max-width: 200px;
            padding: 6px 10px;
            left: 50%;
            top: -40px;
            transform: translateX(-50%);
            pointer-events: none;
            transition: opacity 0.2s;
        }
        
        .tooltip .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
        }
        
        .tooltip:hover .tooltip-text {
            @apply visible opacity-100;
        }
        
        /* Member profile hover card */
        .member-hover-card {
            @apply absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden z-20 invisible opacity-0 translate-y-1;
            transition: all 0.3s ease;
        }
        
        .member-hover-trigger:hover .member-hover-card {
            @apply visible opacity-100 translate-y-0;
        }
        
        /* Add mobile menu styles */
        .mobile-menu-overlay {
            @apply fixed inset-0 bg-gray-900 bg-opacity-50 dark:bg-opacity-80 z-40 transition-opacity duration-300 ease-in-out;
        }
        
        /* Enhanced dark mode toggle */
        .theme-toggle-wrapper {
            @apply relative w-12 h-6 rounded-full bg-gray-200 dark:bg-gray-700 transition-colors duration-300 ease-in-out cursor-pointer;
        }
        
        .theme-toggle-circle {
            @apply absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-all duration-300 ease-in-out;
        }
        
        .theme-toggle-icon {
            @apply absolute transition-all duration-300 ease-in-out;
        }
        
        /* Notification dot with animation */
        .notification-dot {
            @apply absolute h-2.5 w-2.5 bg-red-500 rounded-full top-0 right-0 transform -translate-y-1/4 translate-x-1/4;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1) translate(-1/4, 1/4);
            }
            50% {
                opacity: .8;
                transform: scale(1.1) translate(-1/4, 1/4);
            }
        }
        
        /* Counter animation */
        .counter-value {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        
        .counter-value.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .counter-anime {
            animation: counter-pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        @keyframes counter-pop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        /* Stats card hover effect */
        .stat-card {
            @apply relative overflow-hidden transition-all duration-300;
        }
        
        .stat-card:hover {
            @apply transform -translate-y-1 shadow-lg;
        }
        
        .stat-card .icon-container {
            @apply transition-all duration-300;
        }
        
        .stat-card:hover .icon-container {
            @apply transform scale-110 rotate-3;
        }
        
        /* Dark mode toggle */
        .dark-mode-toggle {
            @apply relative flex items-center justify-center w-9 h-9 rounded-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none;
            transition: all 0.2s ease;
        }
        
        .dark-mode-toggle .sun {
            @apply absolute transform -translate-x-1/2 -translate-y-1/2;
            opacity: 1;
            transition: opacity 0.2s ease;
        }
        
        .dark-mode-toggle .moon {
            @apply absolute transform -translate-x-1/2 -translate-y-1/2;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .dark .dark-mode-toggle .sun {
            opacity: 0;
        }
        
        .dark .dark-mode-toggle .moon {
            opacity: 1;
        }
        
        /* Profile dropdown */
        .profile-trigger {
            @apply relative;
        }
        
        .profile-hover-card {
            @apply absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden z-20 invisible opacity-0 translate-y-1;
            transition: all 0.3s ease;
        }
        
        .profile-trigger:hover .profile-hover-card {
            @apply visible opacity-100 translate-y-0;
        }
        
        /* Section dividers */
        .section-divider {
            @apply my-6 border-t border-gray-200 dark:border-gray-700;
        }
        
        /* Dropdown menu item hover */
        .dropdown-item {
            @apply flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md transition-colors duration-200;
        }
        
        .dropdown-item:hover {
            @apply bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white;
        }
        
        .dropdown-item.danger:hover {
            @apply bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400;
        }
        
        /* Sidebar styling with improved alignment */
        .sidebar-link {
            @apply flex items-center text-gray-500 dark:text-gray-400 py-3 px-4 rounded-lg transition-all duration-300;
        }
        
        .sidebar-link .icon {
            @apply flex-shrink-0 w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 transition-colors;
        }
        
        .sidebar a, .sidebar button {
            @apply relative justify-between;
        }
        
        .sidebar a span:not(.tooltip-text), .sidebar button span:not(.tooltip-text) {
            @apply flex-grow text-left;
        }
    </style>
    
    <!-- Custom Styles -->
    @yield('styles')
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    transitionTimingFunction: {
                        'spring': 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                    },
                    spacing: {
                        '18': '4.5rem',
                    }
                }
            }
        }
        
        // Check if dark mode preference exists
        if (localStorage.getItem('darkMode') === 'true' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="antialiased" x-data="{ sidebarOpen: false, activePage: 'dashboard' }">
    
    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="mobile-menu-overlay hidden opacity-0"></div>
    
    <div class="min-h-screen flex">
        <!-- Sidebar (desktop) -->
        <aside class="sidebar hidden md:flex md:flex-col md:w-64 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="bg-indigo-500 text-white rounded-lg w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="text-lg font-semibold text-gray-800 dark:text-white">Club Admin</span>
                </a>
            </div>
            
            <nav class="flex-1 py-4 px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('admin/dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('admin/dashboard') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('members.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('members*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('members*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Manage Members</span>
                </a>
                
                <a href="{{ route('events.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('events*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('events*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Manage Events</span>
                </a>
                
                <a href="{{ route('announcements.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('announcements*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-bullhorn w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('announcements*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Manage Announcements</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <div class="border-t border-gray-100 dark:border-gray-700"></div>
                </div>
                
                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('admin/profile') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-user-circle w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('admin/profile') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Manage Your Profile</span>
                </a>
                
                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('admin/settings') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-cog w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('admin/settings') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>System Settings</span>
                </a>
                
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 hover:text-red-600 dark:hover:bg-gray-700 dark:hover:text-red-400 group transition-colors">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden"></div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 shadow-lg transform menu-transition md:hidden">
            
            <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="bg-indigo-500 text-white rounded-lg w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="text-lg font-semibold text-gray-800 dark:text-white">Club Admin</span>
                </a>
                <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="py-4 px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('admin/dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('admin/dashboard') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('members.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('members*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('members*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Members</span>
                </a>
                
                <a href="{{ route('events.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('events*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('events*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Events</span>
                </a>
                
                <a href="{{ route('announcements.index') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('announcements*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-bullhorn w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('announcements*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Announcements</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <div class="border-t border-gray-100 dark:border-gray-700"></div>
                </div>
                
                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('admin/profile') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-user-circle w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('admin/profile') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Profile</span>
                </a>
                
                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors {{ Request::is('admin/settings') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400' : '' }}">
                    <i class="fas fa-cog w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ Request::is('admin/settings') ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                    <span>Settings</span>
                </a>
                
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 hover:text-red-600 dark:hover:bg-gray-700 dark:hover:text-red-400 group transition-colors">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navbar -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 z-10">
                <div class="px-4 sm:px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none mr-3">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            
                            <!-- Page title -->
                            <div class="md:ml-0">
                                <h1 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">@yield('page-title', 'Dashboard')</h1>
                                <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">@yield('page-subtitle', 'Overview and statistics')</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <!-- Notifications -->
                            <div class="relative flex items-center justify-center">
                                <button class="p-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <span class="notification-dot"></span>
                                    <i class="fas fa-bell text-lg"></i>
                                </button>
                            </div>
                            
                            <!-- Dark Mode Toggle -->
                            <div class="relative flex items-center justify-center">
                                <button @click="darkMode = !darkMode" class="p-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <span class="sun absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"><i class="fas fa-sun text-lg"></i></span>
                                    <span class="moon absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"><i class="fas fa-moon text-lg"></i></span>
                                </button>
                            </div>
                            
                            <!-- Profile dropdown -->
                            <div class="relative profile-trigger">
                                <button class="flex items-center space-x-3 focus:outline-none py-1 px-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="Admin" class="h-8 w-8 rounded-full border-2 border-gray-200 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:inline-block">Admin User</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                                </button>
                                
                                <div class="profile-hover-card">
                                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center space-x-3">
                                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="Admin" class="h-12 w-12 rounded-full border-2 border-gray-200 dark:border-gray-700">
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-white">Admin User</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">admin@example.com</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <a href="{{ route('admin.profile') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-user-circle w-5 h-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                                            <span>Profile</span>
                                        </a>
                                        <a href="{{ route('admin.settings') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-cog w-5 h-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                                            <span>Settings</span>
                                        </a>
                                    </div>
                                    <div class="p-2 border-t border-gray-100 dark:border-gray-700">
                                        <form action="{{ route('admin.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center px-3 py-2 text-sm text-red-700 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20">
                                                <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-red-500 dark:text-red-400"></i>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    @yield('scripts')
    
    <script>
        // Function to initialize counter animations
        function initCounters() {
            const counterObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counterElement = entry.target;
                        const countTo = parseInt(counterElement.textContent, 10);
                        
                        counterElement.classList.add('visible');
                        
                        if (counterElement.dataset.counted !== 'true') {
                            const counter = new CountUp(counterElement, 0, countTo, 0, 2.5, {
                                useEasing: true,
                                useGrouping: true,
                                separator: ',',
                                decimal: '.',
                            });
                            
                            counter.start();
                            counterElement.dataset.counted = 'true';
                        }
                        
                        observer.unobserve(counterElement);
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.counter-value').forEach(counter => {
                counterObserver.observe(counter);
            });
        }
        
        // Initialize components when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize counters
            initCounters();
            
            // Handle mobile navigation
            document.querySelectorAll('.mobile-nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    document.querySelector('.mobile-menu').classList.add('hidden');
                });
            });
        });
    </script>
</body>
</html> 