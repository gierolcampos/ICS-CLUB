@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Edit Letter</h1>
                        <p class="text-gray-600 mt-1">Update letter information and content</p>
                    </div>
                    <div>
                        <a href="{{ route('letters.show', $letter->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Letter
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('letters.update', $letter->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Letter Title</label>
                            <input type="text" name="title" id="title" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $letter->title }}" required>
                        </div>
                        
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Letter Type</label>
                            <select id="type" name="type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select Type</option>
                                <option value="coa" {{ $letter->type == 'coa' ? 'selected' : '' }}>Calendar of Activities</option>
                                <option value="Propreq" {{ $letter->type == 'Propreq' ? 'selected' : '' }}>Proposal/Request</option>
                                <option value="Financial" {{ $letter->type == 'Financial' ? 'selected' : '' }}>Financial Report</option>
                                <option value="Postact" {{ $letter->type == 'Postact' ? 'selected' : '' }}>Post Activity Report</option>
                                <option value="others" {{ $letter->type == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>

                        <div>
                            <label for="recipient" class="block text-sm font-medium text-gray-700 mb-1">Recipient</label>
                            <input type="text" name="recipient" id="recipient" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $letter->recipient }}" required>
                        </div>
                        
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" id="date" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $letter->date }}" required>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="draft" {{ $letter->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ $letter->status == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="approved" {{ $letter->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $letter->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Letter Content</label>
                        <textarea name="content" id="content" rows="12" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>{{ $letter->content }}</textarea>
                    </div>
                    
                    <div>
                        <label for="file_path" class="block text-sm font-medium text-gray-700 mb-1">Attachment (optional)</label>
                        @if($letter->file_path)
                        <div class="mb-2 flex items-center">
                            <span class="text-sm">Current file: </span>
                            <a href="{{ Storage::url($letter->file_path) }}" target="_blank" class="ml-2 text-indigo-600 hover:text-indigo-800 text-sm">
                                <i class="fas fa-paperclip mr-1"></i> {{ basename($letter->file_path) }}
                            </a>
                        </div>
                        @endif
                        <input type="file" name="file_path" id="file_path" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Accepted file types: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('letters.show', $letter->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                <i class="fas fa-save mr-2"></i> Update Letter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
