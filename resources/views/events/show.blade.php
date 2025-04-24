@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('events.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Events
            </a>
            <a href="{{ route('events.edit', $event) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg transition">
                <i class="fas fa-edit mr-2"></i> Edit Event
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Event Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Event Image -->
                @if($event->image)
                    <div class="h-64 overflow-hidden">
                        <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Event Details -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($event->status == 'Upcoming') bg-green-100 text-green-800 
                            @elseif($event->status == 'Ongoing') bg-blue-100 text-blue-800 
                            @elseif($event->status == 'Completed') bg-gray-100 text-gray-800 
                            @else bg-red-100 text-red-800 @endif">
                            {{ $event->status }}
                        </span>
                        @if($event->is_featured)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                <i class="fas fa-star mr-1"></i> Featured
                            </span>
                        @endif
                    </div>

                    <p class="text-gray-700 mb-6">{{ $event->description }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <h3 class="text-gray-500 font-medium mb-1">Date & Time</h3>
                            <p class="text-gray-900">
                                <i class="far fa-calendar-alt mr-2"></i> 
                                {{ $event->start_date->format('F j, Y') }}
                            </p>
                            <p class="text-gray-900">
                                <i class="far fa-clock mr-2"></i> 
                                {{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-gray-500 font-medium mb-1">Location</h3>
                            <p class="text-gray-900">
                                <i class="fas fa-map-marker-alt mr-2"></i> 
                                {{ $event->location }}
                            </p>
                        </div>

                        @if($event->organizer_id && $event->organizer)
                            <div>
                                <h3 class="text-gray-500 font-medium mb-1">Organizer</h3>
                                <p class="text-gray-900">
                                    <i class="fas fa-user mr-2"></i> 
                                    {{ $event->organizer->first_name }} {{ $event->organizer->last_name }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-gray-500 font-medium mb-1">Participants</h3>
                            <p class="text-gray-900">
                                <i class="fas fa-users mr-2"></i> 
                                {{ $event->attendances->count() }} 
                                @if($event->max_participants)
                                    / {{ $event->max_participants }}
                                @endif
                                registered
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Attendance</h2>
                
                @if($event->status == 'Upcoming' || $event->status == 'Ongoing')
                    <form action="{{ route('events.attendance', $event) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <label for="member_id" class="block text-sm font-medium text-gray-700 mb-1">Select Member</label>
                            <select name="member_id" id="member_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                <option value="">-- Select Member --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="attendance_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="attendance_status" id="attendance_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Late">Late</option>
                                <option value="Excused">Excused</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition">
                            Mark Attendance
                        </button>
                    </form>
                @endif
                
                <!-- Attendees List -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Attendees</h3>
                    
                    @if($event->attendances->count() > 0)
                        <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                            @foreach($event->attendances as $attendance)
                                <div class="border-b border-gray-200 last:border-0 pb-2">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">{{ $attendance->member->first_name }} {{ $attendance->member->last_name }}</span>
                                        <span class="px-2 py-1 rounded text-xs font-medium 
                                            @if($attendance->status == 'Present') bg-green-100 text-green-800 
                                            @elseif($attendance->status == 'Absent') bg-red-100 text-red-800 
                                            @elseif($attendance->status == 'Late') bg-yellow-100 text-yellow-800 
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ $attendance->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Marked: {{ $attendance->created_at->format('M j, Y g:i A') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">No attendees registered yet.</p>
                    @endif
                </div>
            </div>
            
            <!-- Admin Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Actions</h2>
                
                <div class="space-y-3">
                    <!-- Delete event button (with confirmation) -->
                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg shadow-sm transition">
                            <i class="fas fa-trash-alt mr-2"></i> Delete Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 