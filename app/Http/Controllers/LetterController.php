<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    /**
     * Display a listing of letters.
     */
    public function index(Request $request)
    {
        $query = Letter::with('sender')->latest();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('recipient', 'like', "%{$search}%");
            });
        }
        
        // Apply type filter
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }
        
        $letters = $query->paginate(10);
        
        return view('letters.index', compact('letters'));
    }

    /**
     * Show the form for creating a new letter.
     */
    public function create()
    {
        return view('letters.create');
    }

    /**
     * Store a newly created letter in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|max:50',
            'recipient' => 'required|string|max:255',
            'date' => 'required|date',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Handle file upload if present
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filepath = $file->storeAs('letter_attachments', $filename, 'public');
            $validated['file_path'] = $filepath;
        }

        // Set sender as current user
        $validated['sender_id'] = auth()->id();
        $validated['status'] = 'draft';

        // Create letter
        Letter::create($validated);

        return redirect()->route('letters.index')
            ->with('success', 'Letter created successfully.');
    }

    /**
     * Display the specified letter.
     */
    public function show($id)
    {
        $letter = Letter::with('sender')->findOrFail($id);
        return view('letters.show', compact('letter'));
    }

    /**
     * Show the form for editing the specified letter.
     */
    public function edit($id)
    {
        $letter = Letter::findOrFail($id);
        return view('letters.edit', compact('letter'));
    }

    /**
     * Update the specified letter in storage.
     */
    public function update(Request $request, $id)
    {
        $letter = Letter::findOrFail($id);
        
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|max:50',
            'recipient' => 'required|string|max:255',
            'date' => 'required|date',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'sometimes|string|in:draft,sent,approved,rejected',
        ]);

        // Handle file upload if present
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }
            
            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filepath = $file->storeAs('letter_attachments', $filename, 'public');
            $validated['file_path'] = $filepath;
        }

        // Update letter
        $letter->update($validated);

        return redirect()->route('letters.show', $letter->id)
            ->with('success', 'Letter updated successfully.');
    }

    /**
     * Remove the specified letter from storage.
     */
    public function destroy($id)
    {
        $letter = Letter::findOrFail($id);
        
        // Delete attachment if exists
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }
        
        $letter->delete();
        
        return redirect()->route('letters.index')
            ->with('success', 'Letter deleted successfully.');
    }
} 