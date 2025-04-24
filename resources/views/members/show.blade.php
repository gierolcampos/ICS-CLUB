@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Member Details</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('members.edit', $member) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('members.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-arrow-left mr-1"></i> Back
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

                <div class="bg-gray-50 rounded-lg shadow overflow-hidden">
                    <!-- Header with profile info -->
                    <div class="bg-indigo-700 text-white p-6">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-shrink-0 mb-4 md:mb-0">
                                @if($member->profile_photo)
                                    <img class="h-24 w-24 rounded-full object-cover border-4 border-white" src="{{ Storage::url($member->profile_photo) }}" alt="{{ $member->full_name }}">
                                @else
                                    <div class="h-24 w-24 rounded-full bg-indigo-500 flex items-center justify-center border-4 border-white">
                                        <i class="fas fa-user-circle text-4xl text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="md:ml-6 text-center md:text-left">
                                <h2 class="text-2xl font-bold">{{ $member->full_name }}</h2>
                                <p class="text-indigo-200">{{ $member->course ?: 'No course specified' }}</p>
                                <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-2">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $member->membership_status === 'Active' ? 'bg-green-200 text-green-800' : '' }}
                                        {{ $member->membership_status === 'Inactive' ? 'bg-red-200 text-red-800' : '' }}
                                        {{ $member->membership_status === 'Pending' ? 'bg-yellow-200 text-yellow-800' : '' }}">
                                        {{ $member->membership_status }}
                                    </span>
                                    @if($member->year_level)
                                    <span class="px-2 py-1 text-xs rounded-full bg-indigo-200 text-indigo-800">
                                        {{ $member->year_level }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-envelope text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Email:</span>
                                    <span class="ml-2">{{ $member->email }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-phone text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Phone:</span>
                                    <span class="ml-2">{{ $member->phone ?: 'Not provided' }}</span>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="flex items-start text-gray-700">
                                    <i class="fas fa-map-marker-alt text-indigo-500 mr-2 mt-1"></i>
                                    <span class="font-medium mr-2">Address:</span>
                                    <span>{{ $member->address ?: 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Information -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-id-card text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Student ID:</span>
                                    <span class="ml-2">{{ $member->student_id ?: 'Not provided' }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-graduation-cap text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Course:</span>
                                    <span class="ml-2">{{ $member->course ?: 'Not provided' }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-user-graduate text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Year Level:</span>
                                    <span class="ml-2">{{ $member->year_level ?: 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Membership Information -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Membership Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-certificate text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Status:</span>
                                    <span class="ml-2">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $member->membership_status === 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $member->membership_status === 'Inactive' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $member->membership_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ $member->membership_status }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-calendar-day text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Membership Date:</span>
                                    <span class="ml-2">{{ $member->membership_date ? $member->membership_date->format('M d, Y') : 'Not set' }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-calendar-times text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Membership Expiry:</span>
                                    <span class="ml-2">{{ $member->membership_expiry ? $member->membership_expiry->format('M d, Y') : 'Not set' }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-birthday-cake text-indigo-500 mr-2"></i>
                                    <span class="font-medium">Date of Birth:</span>
                                    <span class="ml-2">{{ $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-tools text-indigo-500 mr-2"></i> Skills
                                </h4>
                                <div class="bg-gray-100 p-3 rounded">
                                    {{ $member->skills ?: 'No skills listed' }}
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-lightbulb text-indigo-500 mr-2"></i> Interests
                                </h4>
                                <div class="bg-gray-100 p-3 rounded">
                                    {{ $member->interests ?: 'No interests listed' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Member Button -->
                <div class="mt-6 flex justify-end">
                    <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-trash-alt mr-2"></i> Delete Member
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 