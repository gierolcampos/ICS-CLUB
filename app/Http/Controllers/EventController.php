<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {
        $query = Event::query();
        
        // Filter by status
        if ($request->has('filter') && $request->filter) {
            switch ($request->filter) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'past':
                    $query->past();
                    break;
                case 'ongoing':
                    $query->ongoing();
                    break;
            }
        } else {
            // Default: upcoming first, then ongoing, then past
            $query->orderByRaw("
                CASE 
                    WHEN status = 'Upcoming' THEN 1 
                    WHEN status = 'Ongoing' THEN 2 
                    WHEN status = 'Completed' THEN 3
                    ELSE 4
                END
            ")->orderBy('start_date', 'asc');
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(9)->withQueryString();
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $members = Member::where('membership_status', 'Active')->orderBy('first_name')->get();
        return view('events.create', compact('members'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:Upcoming,Ongoing,Completed,Cancelled',
            'organizer_id' => 'nullable|exists:members,id',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        // Convert dates to Carbon instances
        $validated['start_date'] = Carbon::parse($validated['start_date']);
        $validated['end_date'] = Carbon::parse($validated['end_date']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('event-images', 'public');
        }

        // Set is_featured if it doesn't exist in the request
        $validated['is_featured'] = $request->has('is_featured') ? $request->is_featured : false;

        // Create the event
        $event = Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load('attendances.member');
        $members = Member::where('membership_status', 'Active')->orderBy('first_name')->get();
        
        return view('events.show', compact('event', 'members'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $members = Member::where('membership_status', 'Active')->orderBy('first_name')->get();
        return view('events.edit', compact('event', 'members'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:Upcoming,Ongoing,Completed,Cancelled',
            'organizer_id' => 'nullable|exists:members,id',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        // Convert dates to Carbon instances
        $validated['start_date'] = Carbon::parse($validated['start_date']);
        $validated['end_date'] = Carbon::parse($validated['end_date']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('event-images', 'public');
        }

        // Set is_featured if it doesn't exist in the request
        $validated['is_featured'] = $request->has('is_featured') ? $request->is_featured : false;

        // Update the event
        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Delete the event image if it exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        // Delete the event
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Display the calendar view of events.
     */
    public function calendar()
    {
        $events = Event::all();
        return view('events.calendar', compact('events'));
    }

    /**
     * RSVP to an event.
     */
    public function rsvp(Request $request, Event $event)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'status' => 'required|in:Going,Maybe,Not Going',
        ]);

        // Check if the attendance record already exists
        $attendance = Attendance::where('event_id', $event->id)
            ->where('member_id', $validated['member_id'])
            ->first();

        if ($attendance) {
            // Update existing attendance
            $attendance->update(['status' => $validated['status']]);
            $message = 'Your RSVP has been updated.';
        } else {
            // Create new attendance
            Attendance::create([
                'event_id' => $event->id,
                'member_id' => $validated['member_id'],
                'status' => $validated['status'],
            ]);
            $message = 'You have successfully RSVP\'d to this event.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Mark attendance for members at an event.
     */
    public function markAttendance(Request $request, Event $event)
    {
        $validated = $request->validate([
            'attendees' => 'required|array',
            'attendees.*' => 'exists:members,id',
        ]);

        // Get all existing attendances for this event
        $attendances = Attendance::where('event_id', $event->id)->get();
        
        // Reset all has_attended to false
        foreach ($attendances as $attendance) {
            $attendance->update(['has_attended' => false]);
        }
        
        // Mark the selected members as attended
        foreach ($validated['attendees'] as $memberId) {
            $attendance = Attendance::where('event_id', $event->id)
                ->where('member_id', $memberId)
                ->first();
                
            if ($attendance) {
                $attendance->update(['has_attended' => true]);
            } else {
                // Create a new attendance record if one doesn't exist
                Attendance::create([
                    'event_id' => $event->id,
                    'member_id' => $memberId,
                    'status' => 'Going',
                    'has_attended' => true,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Attendance has been marked successfully.');
    }

    /**
     * Get data for the calendar view.
     */
    public function calendarData()
    {
        $events = Event::all();
        
        $calendarEvents = $events->map(function ($event) {
            $color = '';
            switch ($event->status) {
                case 'Upcoming':
                    $color = '#4F46E5'; // indigo-600
                    break;
                case 'Ongoing':
                    $color = '#059669'; // emerald-600
                    break;
                case 'Completed':
                    $color = '#6B7280'; // gray-500
                    break;
                case 'Cancelled':
                    $color = '#DC2626'; // red-600
                    break;
            }
            
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->format('Y-m-d\TH:i:s'),
                'end' => $event->end_date->format('Y-m-d\TH:i:s'),
                'url' => route('events.show', $event),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'location' => $event->location,
                    'status' => $event->status,
                    'attendees' => $event->confirmed_attendees_count,
                    'description' => $event->description,
                    'duration' => $event->duration,
                    'max_participants' => $event->max_participants,
                    'attendees_count' => $event->confirmed_attendees_count,
                ]
            ];
        });
        
        return response()->json($calendarEvents);
    }

    /**
     * Get the list of attendees for an event.
     */
    public function attendees(Event $event)
    {
        $event->load('attendances.member');
        $attendees = $event->attendances;
        
        return view('events.attendees', compact('event', 'attendees'));
    }
}
