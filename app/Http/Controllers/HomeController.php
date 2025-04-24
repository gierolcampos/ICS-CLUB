<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Event;
use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get stats for the dashboard
        $membersCount = Member::count();
        $upcomingEventsCount = Event::upcoming()->count();
        $ongoingEventsCount = Event::ongoing()->count();
        $announcementsCount = Announcement::current()->count();
        
        // Get upcoming events
        $upcomingEvents = Event::with('organizer')
            ->upcoming()
            ->take(3)
            ->get();
            
        // Get latest announcements
        $latestAnnouncements = Announcement::current()
            ->latest('publish_date')
            ->take(5)
            ->get();
            
        return view('home', compact(
            'membersCount', 
            'upcomingEventsCount', 
            'ongoingEventsCount', 
            'announcementsCount',
            'upcomingEvents',
            'latestAnnouncements'
        ));
    }
}
