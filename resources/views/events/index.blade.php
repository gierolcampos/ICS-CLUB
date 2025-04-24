@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">Events</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage club events and activities</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-2">
                        <a href="{{ route('events.calendar') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-calendar mr-1"></i> Calendar View
                        </a>
                        <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-1"></i> New Event
                        </a>
                    </div>
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
                    <form method="GET" action="{{ route('events.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Search by title, description, location...">
                            </div>
                        </div>
                        <div class="w-full md:w-1/4">
                            <label for="filter" class="block text-sm font-medium text-gray-700 mb-1">Filter</label>
                            <select id="filter" name="filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="" {{ request('filter') == '' ? 'selected' : '' }}>All Events</option>
                                <option value="upcoming" {{ request('filter') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('filter') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="past" {{ request('filter') == 'past' ? 'selected' : '' }}>Past</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/4 flex items-end">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Events Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($events as $event)
                    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                        @if($event->image)
                        <div class="h-48 w-full overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                        </div>
                        @else
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-4xl text-gray-400"></i>
                        </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $event->title }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full {{ $event->status === 'Upcoming' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $event->status === 'Ongoing' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $event->status === 'Completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $event->status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $event->status }}
                                </span>
                            </div>
                            
                            <div class="mt-2 text-sm text-gray-500">
                                <div class="flex items-center mb-1">
                                    <i class="far fa-calendar-alt w-4 mr-1 text-indigo-500"></i>
                                    <span>{{ $event->start_date->format('M d, Y - h:i A') }}</span>
                                </div>
                                <div class="flex items-center mb-1">
                                    <i class="far fa-clock w-4 mr-1 text-indigo-500"></i>
                                    <span>{{ $event->duration }} hour(s)</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-4 mr-1 text-indigo-500"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                                
                                @if($event->max_participants)
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-users w-4 mr-1 text-indigo-500"></i>
                                    <span>{{ $event->confirmed_attendees_count }}/{{ $event->max_participants }} attendees</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-4 flex justify-end space-x-2">
                                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-12 flex flex-col items-center justify-center text-gray-500">
                        <i class="fas fa-calendar-times text-5xl mb-4"></i>
                        <p class="text-xl">No events found</p>
                        <p class="text-sm mt-1">Create a new event to get started</p>
                        <a href="{{ route('events.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-1"></i> Create Event
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 