@extends('layouts.admin')

@section('title', 'Activity Log')

@section('styles')
<style>
    .activity-timeline {
        position: relative;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 30px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
        z-index: 1;
    }
    
    .activity-item {
        position: relative;
        padding-left: 60px;
        margin-bottom: 1.5rem;
    }
    
    .activity-icon {
        position: absolute;
        left: 13px;
        top: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    
    .activity-content {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .activity-type-member { background-color: #818cf8; }
    .activity-type-event { background-color: #34d399; }
    .activity-type-announcement { background-color: #fbbf24; }
    .activity-type-system { background-color: #94a3b8; }
    .activity-type-auth { background-color: #f87171; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Activity Log</h1>
        <div class="flex items-center space-x-2">
            <button id="refresh-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex items-center">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Type</label>
                <select id="type-filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    <option value="">All Types</option>
                    <option value="member">Member</option>
                    <option value="event">Event</option>
                    <option value="announcement">Announcement</option>
                    <option value="system">System</option>
                    <option value="auth">Authentication</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by User</label>
                <select id="user-filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    <option value="">All Users</option>
                    <option value="system">System</option>
                    <!-- Will be populated dynamically -->
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select id="date-filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 3 Months</option>
                    <option value="all">All Time</option>
                </select>
            </div>
            <div class="flex items-end">
                <button id="apply-filters" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md">
                    Apply Filters
                </button>
            </div>
        </div>
        
        <div id="activity-timeline" class="activity-timeline">
            <!-- Activities will be loaded here -->
            <div class="animate-pulse">
                <div class="activity-item">
                    <div class="activity-icon bg-gray-300"></div>
                    <div class="activity-content">
                        <div class="h-4 bg-gray-300 rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon bg-gray-300"></div>
                    <div class="activity-content">
                        <div class="h-4 bg-gray-300 rounded w-2/3 mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon bg-gray-300"></div>
                    <div class="activity-content">
                        <div class="h-4 bg-gray-300 rounded w-3/5 mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-2/5"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="load-more" class="text-center mt-4 hidden">
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md">
                Load More
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial load
        loadActivityData();
        
        // Refresh button
        document.getElementById('refresh-btn').addEventListener('click', function() {
            loadActivityData();
        });
        
        // Apply filters
        document.getElementById('apply-filters').addEventListener('click', function() {
            loadActivityData();
        });
        
        function loadActivityData() {
            const typeFilter = document.getElementById('type-filter').value;
            const userFilter = document.getElementById('user-filter').value;
            const dateFilter = document.getElementById('date-filter').value;
            
            // Show loading state
            document.getElementById('activity-timeline').innerHTML = `
                <div class="animate-pulse">
                    <div class="activity-item">
                        <div class="activity-icon bg-gray-300"></div>
                        <div class="activity-content">
                            <div class="h-4 bg-gray-300 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-gray-300"></div>
                        <div class="activity-content">
                            <div class="h-4 bg-gray-300 rounded w-2/3 mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-gray-300"></div>
                        <div class="activity-content">
                            <div class="h-4 bg-gray-300 rounded w-3/5 mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-2/5"></div>
                        </div>
                    </div>
                </div>
            `;
            
            // Fetch activity data
            fetch(`/admin/activity/refresh?type=${typeFilter}&user=${userFilter}&days=${dateFilter}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderActivityItems(data.activities || getSampleActivityData());
                } else {
                    console.error('Failed to load activity data');
                }
            })
            .catch(error => {
                console.error('Error fetching activity data:', error);
                // Show sample data for demonstration
                renderActivityItems(getSampleActivityData());
            });
        }
        
        function renderActivityItems(activities) {
            const timelineEl = document.getElementById('activity-timeline');
            
            if (activities.length === 0) {
                timelineEl.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>No activity records found.</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            
            activities.forEach(activity => {
                const iconClass = getIconClass(activity.type);
                const activityTypeClass = `activity-type-${activity.type.split('.')[0]}`;
                
                html += `
                    <div class="activity-item">
                        <div class="activity-icon ${activityTypeClass}">
                            <i class="${iconClass} text-white"></i>
                        </div>
                        <div class="activity-content">
                            <div class="flex justify-between items-start">
                                <p class="font-medium text-gray-800">${activity.description}</p>
                                <span class="text-xs text-gray-500">${activity.time}</span>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                <span class="bg-gray-100 text-xs px-2 py-1 rounded">${activity.type}</span>
                                <span>by ${activity.user}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            timelineEl.innerHTML = html;
            
            // Show/hide load more button
            document.getElementById('load-more').classList.toggle('hidden', activities.length < 10);
        }
        
        function getIconClass(type) {
            const typePrefix = type.split('.')[0];
            switch (typePrefix) {
                case 'member': return 'fas fa-user';
                case 'event': return 'fas fa-calendar';
                case 'announcement': return 'fas fa-bullhorn';
                case 'auth': return 'fas fa-shield-alt';
                case 'system': return 'fas fa-cog';
                default: return 'fas fa-info-circle';
            }
        }
        
        function getSampleActivityData() {
            // Sample data for demonstration
            return [
                {
                    id: 1,
                    type: 'member.created',
                    description: 'New member registered: John Doe',
                    user: 'System',
                    time: '5 minutes ago',
                    timestamp: new Date()
                },
                {
                    id: 2,
                    type: 'event.updated',
                    description: 'Event "Annual Club Meeting" was updated',
                    user: 'Admin User',
                    time: '1 hour ago',
                    timestamp: new Date()
                },
                {
                    id: 3,
                    type: 'announcement.created',
                    description: 'New announcement posted: "Important Updates"',
                    user: 'Admin User',
                    time: '3 hours ago',
                    timestamp: new Date()
                },
                {
                    id: 4,
                    type: 'auth.login',
                    description: 'Admin logged in',
                    user: 'Admin User',
                    time: '5 hours ago',
                    timestamp: new Date()
                },
                {
                    id: 5,
                    type: 'system.settings',
                    description: 'System settings updated',
                    user: 'Admin User',
                    time: '1 day ago',
                    timestamp: new Date()
                },
                {
                    id: 6,
                    type: 'member.updated',
                    description: 'Member profile updated: Jane Smith',
                    user: 'Admin User',
                    time: '2 days ago',
                    timestamp: new Date()
                },
                {
                    id: 7,
                    type: 'event.created',
                    description: 'New event created: "Debugging Competition"',
                    user: 'Admin User',
                    time: '3 days ago',
                    timestamp: new Date()
                }
            ];
        }
    });
</script>
@endsection 