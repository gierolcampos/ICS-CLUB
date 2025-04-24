@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section with breadcrumbs -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('announcements.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Announcements</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-blue-600 md:ml-2">Create New</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page header -->
        <div class="md:flex md:items-center md:justify-between mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Create Announcement
                </h1>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Use this form to create an official announcement for club members
                    </div>
                </div>
            </div>
            <div class="mt-5 flex md:mt-0 md:ml-4">
                <a href="{{ route('announcements.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Return to List
                </a>
            </div>
        </div>

        <!-- Form with status indicator -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-5">
            <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-50 to-white border-b border-gray-200">
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Announcement Details
                    </h3>
                </div>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Fill out all required fields marked with an asterisk (*).
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data" class="mb-12">
            @csrf
            
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <!-- Left column - Main information -->
                <div class="md:col-span-2">
                    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <!-- Title -->
                                <div class="col-span-6">
                                    <label for="title" class="block text-sm font-medium text-gray-700">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                            placeholder="Enter a concise, descriptive title" required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">Choose a clear, attention-grabbing title that summarizes the announcement.</p>
                                </div>

                                <!-- Content -->
                                <div class="col-span-6">
                                    <label for="content" class="block text-sm font-medium text-gray-700">
                                        Content <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="content" name="content" rows="10" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md" 
                                            placeholder="Provide detailed information about this announcement" required>{{ old('content') }}</textarea>
                                    </div>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">
                                        Provide comprehensive information with all necessary details. You can use basic formatting like paragraphs and lists.
                                    </p>
                                </div>

                                <!-- Image -->
                                <div class="col-span-6">
                                    <label for="image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload a file</span>
                                                    <input id="image" name="image" type="file" class="sr-only" accept="image/png, image/jpeg, image/gif">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                PNG, JPG, GIF up to 2MB
                                            </p>
                                        </div>
                                    </div>
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">
                                        An image will make your announcement more engaging. Use high-quality, relevant images.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right column - Settings and publishing -->
                <div class="md:col-span-1 mt-5 md:mt-0">
                    <div class="space-y-6">
                        <!-- Publishing Settings Card -->
                        <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-white px-4 py-3 border-b border-gray-200">
                                <h3 class="text-base font-medium text-gray-900">
                                    Publishing Settings
                                </h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <!-- Type -->
                                <div class="mb-6">
                                    <label for="type" class="block text-sm font-medium text-gray-700">
                                        Announcement Type <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative">
                                        <select id="type" name="type" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="General" {{ old('type') == 'General' ? 'selected' : '' }}>General</option>
                                            <option value="Event" {{ old('type') == 'Event' ? 'selected' : '' }}>Event</option>
                                            <option value="Important" {{ old('type') == 'Important' ? 'selected' : '' }}>Important</option>
                                            <option value="Urgent" {{ old('type') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                    </div>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">
                                        This determines how the announcement will be displayed to members.
                                    </p>
                                </div>

                                <!-- Publish Date -->
                                <div class="mb-6">
                                    <label for="publish_date" class="block text-sm font-medium text-gray-700">
                                        Publish Date <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative">
                                        <input type="datetime-local" name="publish_date" id="publish_date" 
                                            value="{{ old('publish_date', now()->format('Y-m-d\TH:i')) }}" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                    </div>
                                    @error('publish_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">
                                        When this announcement should become visible to members.
                                    </p>
                                </div>

                                <!-- Expiry Date -->
                                <div class="mb-6">
                                    <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                    <div class="mt-1 relative">
                                        <input type="datetime-local" name="expiry_date" id="expiry_date" 
                                            value="{{ old('expiry_date') }}" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    @error('expiry_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">
                                        When this announcement should no longer be displayed. Leave blank to keep it active indefinitely.
                                    </p>
                                </div>

                                <!-- Is Active -->
                                <div class="relative flex items-start mb-4">
                                    <div class="flex items-center h-5">
                                        <input id="is_active" name="is_active" type="checkbox" value="1" 
                                            {{ old('is_active', '1') ? 'checked' : '' }} 
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_active" class="font-medium text-gray-700">Active Status</label>
                                        <p class="text-gray-500">Will be visible to members on the publish date if checked.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Card -->
                        <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-white px-4 py-3 border-b border-gray-200">
                                <h3 class="text-base font-medium text-gray-900">
                                    Actions
                                </h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex flex-col space-y-3">
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Publish Announcement
                                    </button>
                                    <button type="button" onclick="window.location.href='{{ route('announcements.index') }}'" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="bg-blue-50 shadow-sm rounded-lg border border-blue-100 overflow-hidden">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-sm font-medium text-blue-800 mb-2">
                                    <svg class="inline-block h-5 w-5 mr-1 -mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tips for Effective Announcements
                                </h3>
                                <div class="text-xs text-blue-700 space-y-1">
                                    <p>• Keep titles clear and concise</p>
                                    <p>• Include all relevant details in content</p>
                                    <p>• Use appropriate announcement type</p>
                                    <p>• Set strategic publish and expiry dates</p>
                                    <p>• Add an image for better engagement</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 