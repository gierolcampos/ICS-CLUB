<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with stats and recent activity.
     */
    public function dashboard()
    {
        // Get counts for dashboard stats
        $membersCount = Member::count();
        $eventsCount = Event::where('start_date', '>=', now())->count();
        $announcementsCount = Announcement::count();
        
        // Get new members count this month
        $newMembersCount = Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Get next event days
        $nextEvent = Event::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->first();
        
        $nextEventDays = $nextEvent ? now()->diffInDays($nextEvent->start_date) : null;
        
        // Get recent announcements count
        $recentAnnouncements = Announcement::where('created_at', '>=', now()->subDays(7))->count();
        
        return view('admin.dashboard', compact(
            'membersCount', 
            'eventsCount', 
            'announcementsCount', 
            'newMembersCount', 
            'nextEventDays', 
            'recentAnnouncements'
        ));
    }

    /**
     * Get dashboard stats for API endpoint.
     */
    public function dashboardStats()
    {
        $stats = [
            'members' => [
                'total' => Member::count(),
                'new' => Member::whereMonth('created_at', now()->month)->count(),
                'active' => Member::where('status', 'active')->count(),
                'growth' => $this->calculateGrowthRate('members')
            ],
            'events' => [
                'upcoming' => Event::where('start_date', '>=', now())->count(),
                'thisMonth' => Event::whereMonth('start_date', now()->month)->count(),
                'attendance' => $this->calculateAverageAttendance(),
            ],
            'announcements' => [
                'total' => Announcement::count(),
                'recent' => Announcement::where('created_at', '>=', now()->subDays(7))->count(),
                'engagement' => 65, // This would typically be calculated from user interaction data
            ],
            'metrics' => [
                'memberRetention' => 92,
                'eventAttendance' => 78,
                'announcementEngagement' => 65,
                'newMemberGrowth' => 45,
                'budgetUtilization' => 83,
                'volunteerParticipation' => 60,
            ]
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Get dashboard activity for API endpoint.
     */
    public function dashboardActivity()
    {
        $activities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'type' => $activity->type,
                    'description' => $activity->description,
                    'user' => $activity->user ? $activity->user->name : 'System',
                    'time' => $activity->created_at->diffForHumans(),
                    'timestamp' => $activity->created_at
                ];
            });
            
        return response()->json($activities);
    }
    
    /**
     * Get upcoming events for dashboard display.
     */
    public function upcomingEvents()
    {
        $events = Event::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'date' => $event->start_date->format('M d'),
                    'time' => $event->start_date->format('g:i A'),
                    'location' => $event->location,
                    'category' => $event->category,
                    'attendees' => $event->attendees_count ?? rand(5, 30) // Placeholder for demo
                ];
            });
            
        return response()->json($events);
    }
    
    /**
     * Show admin activity log page.
     */
    public function activity()
    {
        return view('admin.activity');
    }
    
    /**
     * Refresh activity log data.
     */
    public function refreshActivity(Request $request)
    {
        $type = $request->query('type');
        $user = $request->query('user');
        $days = $request->query('days');
        
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');
            
        // Apply type filter
        if ($type) {
            $query->where('type', 'like', $type . '%');
        }
        
        // Apply user filter
        if ($user) {
            if ($user === 'system') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $user);
            }
        }
        
        // Apply date filter
        if ($days && $days !== 'all') {
            $query->where('created_at', '>=', now()->subDays((int)$days));
        }
        
        // Get activities
        $activities = $query->take(10)->get()->map(function($activity) {
            return [
                'id' => $activity->id,
                'type' => $activity->type,
                'description' => $activity->description,
                'user' => $activity->user ? $activity->user->name : 'System',
                'time' => $activity->created_at->diffForHumans(),
                'timestamp' => $activity->created_at
            ];
        });
            
        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }
    
    /**
     * Show analytics dashboard.
     */
    public function analytics()
    {
        $memberGrowth = $this->getMemberGrowthData();
        $eventAttendance = $this->getEventAttendanceData();
        
        return view('admin.analytics', compact('memberGrowth', 'eventAttendance'));
    }
    
    /**
     * Get analytics metrics.
     */
    public function getMetrics()
    {
        $metrics = [
            'memberGrowth' => $this->getMemberGrowthData(),
            'eventAttendance' => $this->getEventAttendanceData(),
            'announcementEngagement' => $this->getAnnouncementEngagementData()
        ];
        
        return response()->json($metrics);
    }
    
    /**
     * Show the user profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }
    
    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Update password if provided
        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return back()->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Show the system settings page.
     */
    public function settings()
    {
        $settings = [
            'club_name' => 'Club Management System',
            'contact_email' => 'info@clubmanagement.com',
            'timezone' => 'UTC',
            'enable_notifications' => true,
            'auto_approve_members' => false,
            'allow_guest_rsvp' => true,
        ];
        
        return view('admin.settings', compact('settings'));
    }
    
    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'club_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'timezone' => 'required|string',
            'enable_notifications' => 'boolean',
            'auto_approve_members' => 'boolean',
            'allow_guest_rsvp' => 'boolean',
        ]);
        
        // In a real app, this would save to a settings table or config file
        // For demo purposes, we'll just return success
        
        return back()->with('success', 'Settings updated successfully.');
    }
    
    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    
    /**
     * Helper function to calculate member growth rate.
     */
    private function calculateGrowthRate($type)
    {
        if ($type === 'members') {
            $lastMonth = Carbon::now()->subMonth();
            $currentMonth = Carbon::now();
            
            $lastMonthCount = Member::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count();
                
            $currentMonthCount = Member::whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentMonth->year)
                ->count();
            
            if ($lastMonthCount === 0) {
                return 100; // Infinite growth, capped at 100%
            }
            
            return round((($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100);
        }
        
        return 0;
    }
    
    /**
     * Helper function to calculate average event attendance.
     */
    private function calculateAverageAttendance()
    {
        // In a real app, calculate from actual attendance records
        // For demo purposes, return a placeholder value
        return 78;
    }
    
    /**
     * Get member growth data for charts.
     */
    private function getMemberGrowthData()
    {
        $months = collect([]);
        $counts = collect([]);
        
        // Get the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M'));
            
            $count = Member::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
                
            $counts->push($count);
        }
        
        return [
            'labels' => $months,
            'data' => $counts
        ];
    }
    
    /**
     * Get event attendance data for charts.
     */
    private function getEventAttendanceData()
    {
        // In a real app, this would be calculated from actual attendance records
        // For demo purposes, return placeholder data
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [65, 70, 62, 79, 76, 78]
        ];
    }
    
    /**
     * Get announcement engagement data for charts.
     */
    private function getAnnouncementEngagementData()
    {
        // In a real app, this would be calculated from actual user interactions
        // For demo purposes, return placeholder data
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [45, 52, 49, 60, 55, 65]
        ];
    }
} 