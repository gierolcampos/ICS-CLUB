@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">Announcements</h1>
                        <p class="mt-1 text-sm text-gray-600">Stay updated with club announcements and news</p>
                    </div>
                    @auth
                        @if(auth()->user()->isAdmin())
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-1"></i> New Announcement
                            </a>
                        </div>
                        @endif
                    @endauth
                </div>

                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Search & Filter -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <form method="GET" action="{{ route('announcements.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Search by title or content...">
                            </div>
                        </div>
                        <div class="w-full md:w-1/4">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="category" name="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="" {{ request('category') == '' ? 'selected' : '' }}>All Categories</option>
                                <option value="General" {{ request('category') == 'General' ? 'selected' : '' }}>General</option>
                                <option value="Event" {{ request('category') == 'Event' ? 'selected' : '' }}>Event</option>
                                <option value="News" {{ request('category') == 'News' ? 'selected' : '' }}>News</option>
                                <option value="Urgent" {{ request('category') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/4 flex items-end">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Announcements List -->
                <div class="space-y-6">
                    @forelse($announcements as $announcement)
                    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $announcement->category === 'General' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $announcement->category === 'Event' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $announcement->category === 'News' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $announcement->category === 'Urgent' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $announcement->category }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $announcement->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            
                            <div class="mt-4 prose max-w-none">
                                {!! $announcement->summary !!}
                            </div>
                            
                            <div class="mt-6 flex justify-between items-center">
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $announcement->author->name }}</span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> Read More
                                    </a>
                                    
                                    @auth
                                        @if(auth()->user()->isAdmin())
                                        <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 flex flex-col items-center justify-center text-gray-500">
                        <i class="fas fa-bullhorn text-5xl mb-4"></i>
                        <p class="text-xl">No announcements found</p>
                        @auth
                            @if(auth()->user()->isAdmin())
                            <p class="text-sm mt-1">Create a new announcement to get started</p>
                            <a href="{{ route('announcements.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-1"></i> Create Announcement
                            </a>
                            @endif
                        @endauth
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 