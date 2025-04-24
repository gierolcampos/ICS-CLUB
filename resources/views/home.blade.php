@extends('layouts.app')

@section('styles')
<style>
    /* Advanced Card Styling */
    .stat-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        clip-path: circle(60px at 80% 20%);
        transition: 0.5s ease-in-out;
        z-index: -1;
    }
    .stat-card:hover::before {
        clip-path: circle(300px at 80% -20%);
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        clip-path: circle(40px at 15% 85%);
        transition: 0.5s ease-in-out;
        z-index: -1;
    }
    .stat-card:hover::after {
        clip-path: circle(300px at 15% 85%);
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.1;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        background: linear-gradient(to right, #000, #333);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 1px 1px 2px rgba(255,255,255,0.1);
    }
    .stat-label {
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
        font-size: 0.875rem;
    }
    .activity-item {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        position: relative;
        overflow: hidden;
    }
    .activity-item:hover {
        background-color: #f9fafb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateX(4px);
    }
    .activity-icon {
        transition: all 0.4s ease;
    }
    .activity-item:hover .activity-icon {
        transform: scale(1.2) rotate(10deg);
    }
    .dashboard-title {
        position: relative;
        display: inline-block;
    }
    .dashboard-title::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 60%;
        height: 3px;
        background: linear-gradient(to right, #4F46E5, transparent);
        border-radius: 3px;
    }
    .stat-trend-up {
        color: #10B981;
    }
    .stat-trend-down {
        color: #EF4444;
    }
    .progress-bar {
        height: 4px;
        border-radius: 2px;
        overflow: hidden;
        background: #EEE;
        margin-top: 8px;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(to right, #4F46E5, #818CF8);
        border-radius: 2px;
    }
    .badge {
        position: relative;
        overflow: hidden;
    }
    .badge::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }
    @keyframes shine {
        0% { left: -100%; }
        20% { left: 100%; }
        100% { left: 100%; }
    }
    .action-button {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .action-button:hover {
        transform: translateY(-2px);
    }
    .action-button::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: -100%;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
        transition: all 0.6s ease;
    }
    .action-button:hover::after {
        left: 100%;
    }
</style>
@endsection

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dashboard-title">Welcome to NPC</h1>
                <p class="mt-2 text-gray-600">Integrated Computer Society Dashboard</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <div class="bg-white rounded-lg shadow-sm px-4 py-2 flex items-center">
                    <span class="text-gray-500 mr-2"><i class="far fa-calendar-alt"></i></span>
                    <span class="text-sm font-medium">{{ now()->format('l, F d, Y') }}</span>
                </div>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm flex items-center action-button">
                    <i class="fas fa-sync-alt mr-2"></i> Refresh
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Stats Cards Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Members Card -->
                    <div class="bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl shadow-lg p-6 stat-card border border-indigo-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="stat-label text-indigo-700">Total Members</p>
                                <p class="stat-value">{{ $membersCount }}</p>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center text-sm stat-trend-up">
                                        <i class="fas fa-arrow-up mr-1"></i> 12%
                                    </span>
                                    <span class="text-gray-500 text-xs ml-2">vs last month</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 65%"></div>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-full shadow-md">
                                <i class="fas fa-users text-2xl text-indigo-600"></i>
                            </div>
                        </div>
                        <a href="{{ route('members.index') }}" class="mt-6 text-sm text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center">
                            View all members <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <!-- Events Card -->
                    <div class="bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl shadow-lg p-6 stat-card border border-emerald-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="stat-label text-emerald-700">Upcoming Events</p>
                                <p class="stat-value">{{ $upcomingEventsCount }}</p>
                                <div class="flex items-center">
                                    <span class="text-sm text-emerald-700 font-medium">
                                        <i class="fas fa-calendar-day mr-1"></i> 
                                        {{ \App\Models\Event::upcoming()->min('start_date') ? now()->diffInDays(\App\Models\Event::upcoming()->min('start_date')) : 0 }} days
                                    </span>
                                    <span class="text-gray-500 text-xs ml-2">until next event</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 45%; background: linear-gradient(to right, #10B981, #34D399);"></div>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-full shadow-md">
                                <i class="fas fa-calendar-alt text-2xl text-emerald-600"></i>
                            </div>
                        </div>
                        <a href="{{ route('events.index') }}" class="mt-6 text-sm text-emerald-600 hover:text-emerald-800 hover:underline inline-flex items-center">
                            View calendar <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <!-- Announcements Card -->
                    <div class="bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl shadow-lg p-6 stat-card border border-amber-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="stat-label text-amber-700">Announcements</p>
                                <p class="stat-value">{{ $announcementsCount }}</p>
                                <div class="flex items-center">
                                    <span class="text-sm font-medium">
                                        <i class="fas fa-clock mr-1 text-amber-700"></i> 
                                        {{ \App\Models\Announcement::current()->count() > 0 ? \App\Models\Announcement::current()->latest('publish_date')->first()->publish_date->diffForHumans() : 'No recent announcements' }}
                                    </span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%; background: linear-gradient(to right, #F59E0B, #FBBF24);"></div>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-full shadow-md">
                                <i class="fas fa-bullhorn text-2xl text-amber-600"></i>
                            </div>
                        </div>
                        <a href="{{ route('announcements.index') }}" class="mt-6 text-sm text-amber-600 hover:text-amber-800 hover:underline inline-flex items-center">
                            View announcements <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Recent Activities with Timeline -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800 dashboard-title">Recent Activities</h2>
                        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            <span>View all</span>
                            <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-4 activity-item bg-gray-50 border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2 mr-4 border border-indigo-200">
                                    <i class="fas fa-trophy text-indigo-600 activity-icon"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                            Debugging Competition
                                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-700 badge">
                                                Upcoming
                                            </span>
                                        </h3>
                                        <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full shadow-sm">5 days from now</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">April 15, 2025 • Computer Lab</p>
                                    <p class="text-sm text-gray-500 mt-1">Join our annual debugging contest and showcase your problem-solving skills!</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-user-check mr-1"></i> 24 participants
                                        </span>
                                        <div class="flex space-x-2">
                                            <button class="px-2 py-1 text-xs rounded bg-indigo-600 text-white action-button">Register</button>
                                            <button class="px-2 py-1 text-xs rounded bg-white border border-gray-300 text-gray-700">Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 activity-item bg-gray-50 border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-purple-100 rounded-full p-2 mr-4 border border-purple-200">
                                    <i class="fas fa-chalkboard-teacher text-purple-600 activity-icon"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                            Introduction to AI Workshop
                                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700 badge">
                                                Workshop
                                            </span>
                                        </h3>
                                        <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full shadow-sm">10 days from now</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">April 10, 2025 • Conference Room</p>
                                    <p class="text-sm text-gray-500 mt-1">Learn the fundamentals of artificial intelligence and machine learning.</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-user-check mr-1"></i> 18 participants
                                        </span>
                                        <div class="flex space-x-2">
                                            <button class="px-2 py-1 text-xs rounded bg-indigo-600 text-white action-button">Register</button>
                                            <button class="px-2 py-1 text-xs rounded bg-white border border-gray-300 text-gray-700">Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 activity-item bg-gray-50 border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-blue-100 rounded-full p-2 mr-4 border border-blue-200">
                                    <i class="fas fa-users text-blue-600 activity-icon"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                            General Assembly Meeting
                                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 badge">
                                                Mandatory
                                            </span>
                                        </h3>
                                        <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full shadow-sm">2 weeks from now</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">April 5, 2025 • Auditorium</p>
                                    <p class="text-sm text-gray-500 mt-1">Bi-annual meeting for all club members to discuss progress and future plans.</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex -space-x-2">
                                                <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs">JD</div>
                                                <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-white text-xs">MS</div>
                                                <div class="w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center text-white text-xs">AL</div>
                                                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-xs">+45</div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button class="px-2 py-1 text-xs rounded bg-indigo-600 text-white action-button">RSVP</button>
                                            <button class="px-2 py-1 text-xs rounded bg-white border border-gray-300 text-gray-700">Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('members.create') }}" class="flex items-center p-3 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition-all">
                            <i class="fas fa-user-plus mr-3"></i>
                            <span>Add New Member</span>
                        </a>
                        <a href="{{ route('events.create') }}" class="flex items-center p-3 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition-all">
                            <i class="fas fa-calendar-plus mr-3"></i>
                            <span>Create Event</span>
                        </a>
                        <a href="{{ route('announcements.create') }}" class="flex items-center p-3 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 transition-all">
                            <i class="fas fa-bullhorn mr-3"></i>
                            <span>New Announcement</span>
                        </a>
                        <a href="#" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 transition-all">
                            <i class="fas fa-cog mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
                
                <!-- Recent Members -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Members</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold mr-3">JD</div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">John Doe</p>
                                <p class="text-xs text-gray-500">Computer Science</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold mr-3">MS</div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Maria Smith</p>
                                <p class="text-xs text-gray-500">Information Technology</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-semibold mr-3">AL</div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Alex Lee</p>
                                <p class="text-xs text-gray-500">Data Science</p>
                            </div>
                        </div>
                        <a href="{{ route('members.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center justify-center mt-2">
                            <span>View all members</span>
                            <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Club Stats -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Club Stats</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">Active Members</span>
                                <span class="text-indigo-600">85%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">Event Participation</span>
                                <span class="text-emerald-600">72%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: 72%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">Budget Utilization</span>
                                <span class="text-amber-600">43%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-amber-600 h-2 rounded-full" style="width: 43%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 