<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index(Request $request)
    {
        $query = Announcement::query();
        
        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->ofType($request->type);
        }
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->current();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->orderBy('publish_date', 'desc')->paginate(10)->withQueryString();
        
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:General,Event,Important,Urgent',
            'is_active' => 'boolean',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:publish_date',
            'image' => 'nullable|image|max:2048',
        ]);

        // Convert dates to Carbon instances
        $validated['publish_date'] = Carbon::parse($validated['publish_date']);
        if (isset($validated['expiry_date']) && $validated['expiry_date']) {
            $validated['expiry_date'] = Carbon::parse($validated['expiry_date']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('announcement-images', 'public');
        }

        // Set is_active if it doesn't exist in the request
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : true;

        // Set the created_by to the current user (assuming auth system is in place)
        if (auth()->check()) {
            $validated['created_by'] = auth()->id();
        }

        // Create the announcement
        $announcement = Announcement::create($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:General,Event,Important,Urgent',
            'is_active' => 'boolean',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:publish_date',
            'image' => 'nullable|image|max:2048',
        ]);

        // Convert dates to Carbon instances
        $validated['publish_date'] = Carbon::parse($validated['publish_date']);
        if (isset($validated['expiry_date']) && $validated['expiry_date']) {
            $validated['expiry_date'] = Carbon::parse($validated['expiry_date']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $validated['image'] = $request->file('image')->store('announcement-images', 'public');
        }

        // Set is_active if it doesn't exist in the request
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : false;

        // Update the announcement
        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Delete the announcement image if it exists
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }
        
        // Delete the announcement
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Display the latest announcements on the home page.
     */
    public function latest()
    {
        $announcements = Announcement::current()
            ->orderBy('publish_date', 'desc')
            ->take(5)
            ->get();
            
        return view('announcements.latest', compact('announcements'));
    }

    /**
     * Toggle the active status of an announcement.
     */
    public function toggleStatus(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Announcement has been {$status} successfully.");
    }
}
