@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview and statistics')

@section('styles')
<style>
    /* Card animations */
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Progress bar animation */
    .progress-bar {
        transition: width 1.5s ease;
    }
    
    /* Activity timeline */
    .timeline-container {
        position: relative;
    }
    
    .timeline-container::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 24px;
        width: 2px;
        background-color: #e5e7eb;
        @apply dark:bg-gray-700;
    }
    
    .timeline-item {
        position: relative;
    }
    
    .timeline-dot {
        position: absolute;
        left: 19px;
        top: 24px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #6366f1;
        @apply dark:bg-indigo-400;
        z-index: 10;
    }
    
    .timeline-content {
        margin-left: 48px;
    }
    
    /* Activity Icons */
    .activity-icon {
        @apply flex items-center justify-center w-10 h-10 rounded-full mr-3 flex-shrink-0;
    }
    
    .activity-icon.member {
        @apply bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400;
    }
    
    .activity-icon.event {
        @apply bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400;
    }
    
    .activity-icon.announcement {
        @apply bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400;
    }
    
    .activity-icon.system {
        @apply bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400;
    }
    
    /* Section titles */
    .section-title {
        @apply text-xl font-bold text-gray-800 dark:text-white mb-6 text-center sm:text-left relative pb-4;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        @apply bg-indigo-500 dark:bg-indigo-400 rounded-full;
    }
    
    @media (min-width: 640px) {
        .section-title::after {
            left: 0;
            transform: none;
        }
    }
</style>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Stats Cards -->
    <div>
        <h2 class="section-title">Quick Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Members Stat Card -->
            <div class="admin-card group hover:shadow-md transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Members</h3>
                        <div class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 p-3 rounded-full">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="counter-value text-3xl font-bold text-gray-800 dark:text-white mb-1">{{ $membersCount }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($newMembersCount > 0)
                                    <span class="text-green-500 dark:text-green-400 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i> {{ $newMembersCount }} new this month
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">No new members this month</span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('members.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                            <span class="mr-1">View all</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700/50">
                    <a href="{{ route('members.create') }}" class="flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Add New Member</span>
                    </a>
                </div>
            </div>

            <!-- Events Stat Card -->
            <div class="admin-card group hover:shadow-md transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Upcoming Events</h3>
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 p-3 rounded-full">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="counter-value text-3xl font-bold text-gray-800 dark:text-white mb-1">{{ $eventsCount }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($nextEventDays !== null)
                                    <span class="text-amber-500 dark:text-amber-400 flex items-center">
                                        <i class="fas fa-clock mr-1"></i> Next event in {{ $nextEventDays }} days
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">No upcoming events</span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('events.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                            <span class="mr-1">View all</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700/50">
                    <a href="{{ route('events.create') }}" class="flex items-center text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Create New Event</span>
                    </a>
                </div>
            </div>

            <!-- Announcements Stat Card -->
            <div class="admin-card group hover:shadow-md transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Announcements</h3>
                        <div class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 p-3 rounded-full">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="counter-value text-3xl font-bold text-gray-800 dark:text-white mb-1">{{ $announcementsCount }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($recentAnnouncements > 0)
                                    <span class="text-blue-500 dark:text-blue-400 flex items-center">
                                        <i class="fas fa-paper-plane mr-1"></i> {{ $recentAnnouncements }} posted this week
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">No recent announcements</span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('announcements.index') }}" class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">
                            <span class="mr-1">View all</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700/50">
                    <a href="{{ route('announcements.create') }}" class="flex items-center text-sm text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Post New Announcement</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="section-divider"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Quick Actions -->
        <div>
            <h2 class="section-title">Quick Actions</h2>
            <div class="admin-card">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <a href="{{ route('members.create') }}" class="flex flex-col items-center justify-center p-4 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors group">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-plus text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <span class="text-sm font-medium">Add Member</span>
                        </a>
                        <a href="{{ route('events.create') }}" class="flex flex-col items-center justify-center p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors group">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-plus text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <span class="text-sm font-medium">Create Event</span>
                        </a>
                        <a href="{{ route('announcements.create') }}" class="flex flex-col items-center justify-center p-4 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors group">
                            <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-bullhorn text-amber-600 dark:text-amber-400"></i>
                            </div>
                            <span class="text-sm font-medium">Post Announcement</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div>
            <h2 class="section-title">Recent Activity</h2>
            <div class="admin-card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Recent Activity</h3>
                        <button id="refresh-activity" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    
                    <!-- Activity Timeline -->
                    <div class="relative">
                        <!-- Timeline line -->
                        <div class="absolute top-0 left-6 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                        
                        <div class="space-y-8">
                            <!-- New Member -->
                            <div class="relative pl-14">
                                <div class="absolute top-0 left-0 w-12 h-12 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 ring-8 ring-white dark:ring-gray-800 z-10">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="admin-card border border-gray-100 dark:border-gray-700">
                                    <div class="p-4">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">5 minutes ago</span>
                                        <h4 class="text-sm font-medium text-gray-800 dark:text-white mt-1">New Member Registration</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            <span class="font-medium text-indigo-600 dark:text-indigo-400">Jane Smith</span> joined the club as a <span class="status-badge status-badge-active">Regular Member</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Event Update -->
                            <div class="relative pl-14">
                                <div class="absolute top-0 left-0 w-12 h-12 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 ring-8 ring-white dark:ring-gray-800 z-10">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="admin-card border border-gray-100 dark:border-gray-700">
                                    <div class="p-4">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">2 hours ago</span>
                                        <h4 class="text-sm font-medium text-gray-800 dark:text-white mt-1">Event Updated</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            <span class="font-medium text-emerald-600 dark:text-emerald-400">Tech Showcase</span> has been rescheduled to <span class="font-medium">May 15, 2023</span>
                                        </p>
                                        <div class="mt-2">
                                            <span class="category-label category-label-tech">
                                                <i class="fas fa-laptop-code mr-1"></i> Technical
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- New Announcement -->
                            <div class="relative pl-14">
                                <div class="absolute top-0 left-0 w-12 h-12 flex items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 ring-8 ring-white dark:ring-gray-800 z-10">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="admin-card border border-gray-100 dark:border-gray-700">
                                    <div class="p-4">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">1 day ago</span>
                                        <h4 class="text-sm font-medium text-gray-800 dark:text-white mt-1">New Announcement Posted</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            <span class="font-medium text-amber-600 dark:text-amber-400">Summer Retreat</span> announcement has been posted by <span class="font-medium">Admin</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- System Update -->
                            <div class="relative pl-14">
                                <div class="absolute top-0 left-0 w-12 h-12 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 ring-8 ring-white dark:ring-gray-800 z-10">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="admin-card border border-gray-100 dark:border-gray-700">
                                    <div class="p-4">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">3 days ago</span>
                                        <h4 class="text-sm font-medium text-gray-800 dark:text-white mt-1">System Update</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Club management system has been updated to <span class="font-medium text-blue-600 dark:text-blue-400">version 2.5</span> with new features
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="section-divider"></div>
    
    <!-- Performance Metrics -->
    <div>
        <h2 class="section-title">Performance Metrics</h2>
        <div class="admin-card">
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Member Retention -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Member Retention</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">92%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full animate-progress" style="width: 92%"></div>
                        </div>
                    </div>
                    
                    <!-- Event Attendance -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Event Attendance</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">78%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full animate-progress" style="width: 78%"></div>
                        </div>
                    </div>
                    
                    <!-- Announcement Engagement -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Announcement Engagement</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">65%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full animate-progress" style="width: 65%"></div>
                        </div>
                    </div>
                    
                    <!-- New Member Growth -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">New Member Growth</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">45%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full animate-progress" style="width: 45%"></div>
                        </div>
                    </div>
                    
                    <!-- Budget Utilization -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Budget Utilization</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">83%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full animate-progress" style="width: 83%"></div>
                        </div>
                    </div>
                    
                    <!-- Volunteer Participation -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Volunteer Participation</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">60%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-pink-500 rounded-full animate-progress" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="section-divider"></div>
    
    <!-- Upcoming Events -->
    <div>
        <h2 class="section-title">Upcoming Events</h2>
        <div class="admin-card">
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Event 1 -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center p-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 flex flex-col items-center justify-center w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                    <span class="text-xs font-bold">MAY</span>
                                    <span class="text-lg font-bold leading-none">15</span>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-800 dark:text-white">Tech Showcase</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">10:00 AM - 2:00 PM</p>
                                </div>
                            </div>
                            <span class="category-label category-label-tech">
                                <i class="fas fa-laptop-code mr-1"></i> Technical
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Members will showcase their tech projects and discuss innovations.</p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Main Hall
                                </span>
                                <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 py-1 px-2 rounded-full">
                                    <i class="fas fa-users mr-1"></i> 25 attendees
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event 2 -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center p-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 flex flex-col items-center justify-center w-12 h-12 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400">
                                    <span class="text-xs font-bold">MAY</span>
                                    <span class="text-lg font-bold leading-none">20</span>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-800 dark:text-white">Networking Mixer</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">6:00 PM - 9:00 PM</p>
                                </div>
                            </div>
                            <span class="category-label category-label-social">
                                <i class="fas fa-users mr-1"></i> Social
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">An evening of networking and socializing with club members and guests.</p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Lounge Area
                                </span>
                                <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 py-1 px-2 rounded-full">
                                    <i class="fas fa-users mr-1"></i> 18 attendees
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event 3 -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center p-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 flex flex-col items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                    <span class="text-xs font-bold">MAY</span>
                                    <span class="text-lg font-bold leading-none">25</span>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-800 dark:text-white">Monthly Meeting</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">2:00 PM - 4:00 PM</p>
                                </div>
                            </div>
                            <span class="category-label category-label-meeting">
                                <i class="fas fa-handshake mr-1"></i> Meeting
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Regular monthly meeting to discuss club activities and future plans.</p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Conference Room
                                </span>
                                <span class="text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 py-1 px-2 rounded-full">
                                    <i class="fas fa-clock mr-1"></i> Upcoming
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize data refresh on button click
        const refreshButton = document.getElementById('refresh-activity');
        if (refreshButton) {
            refreshButton.addEventListener('click', function() {
                const icon = this.querySelector('i');
                icon.classList.add('animate-spin');
                
                // Simulate refresh with timeout
                setTimeout(() => {
                    icon.classList.remove('animate-spin');
                    // Here you would typically fetch new data via AJAX
                    // For demo purposes, we'll just show a notification
                    alert('Activity timeline refreshed');
                }, 1000);
            });
        }
    });
</script>
@endsection 