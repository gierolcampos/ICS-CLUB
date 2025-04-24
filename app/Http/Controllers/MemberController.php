<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request)
    {
        $query = Member::query();
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('student_id', 'like', "%{$searchTerm}%")
                  ->orWhere('course', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by membership status
        if ($request->has('status') && $request->status != '') {
            $query->where('membership_status', $request->status);
        }
        
        // Filter by course
        if ($request->has('course') && $request->course != '') {
            $query->where('course', $request->course);
        }
        
        $members = $query->latest()->paginate(10)->withQueryString();
        
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|max:255|unique:members,student_id',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'membership_status' => 'nullable|string|in:Active,Inactive,Pending',
            'membership_date' => 'nullable|date',
            'membership_expiry' => 'nullable|date',
            'skills' => 'nullable|string',
            'interests' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|max:255|unique:members,student_id,' . $member->id,
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'membership_status' => 'nullable|string|in:Active,Inactive,Pending',
            'membership_date' => 'nullable|date',
            'membership_expiry' => 'nullable|date',
            'skills' => 'nullable|string',
            'interests' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($member->profile_photo) {
                Storage::disk('public')->delete($member->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Member $member)
    {
        // Delete profile photo if exists
        if ($member->profile_photo) {
            Storage::disk('public')->delete($member->profile_photo);
        }
        
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }
}
