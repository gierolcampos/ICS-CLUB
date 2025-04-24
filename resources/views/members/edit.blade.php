@extends('layouts.app')

@section('styles')
<style>
    .form-section {
        background-color: #f9fafb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid rgba(229, 231, 235, 0.7);
        transition: all 0.2s ease;
    }
    
    .form-section:hover {
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    
    .input-field {
        @apply mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md;
    }
    
    .input-label {
        @apply block text-sm font-medium text-gray-700 mb-1;
    }
    
    .required-mark {
        @apply text-red-500;
    }
    
    .photo-preview {
        position: relative;
        overflow: hidden;
        height: 5rem;
        width: 5rem;
        border-radius: 9999px;
        background-color: #f3f4f6;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease-in-out;
    }
    
    .photo-preview:hover {
        transform: scale(1.05);
        border-color: #c7d2fe;
    }
</style>
@endsection

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Edit Member</h1>
                        <p class="text-gray-600 mt-1">Update member information and preferences</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('members.show', ['member' => $member->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            <i class="fas fa-eye mr-1"></i> View Profile
                        </a>
                        <a href="{{ route('members.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-arrow-left mr-1"></i> Back to List
                        </a>
                    </div>
                </div>
                
                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">Please fix the following errors:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('members.update', ['member' => $member->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-section">
                        <div class="flex items-center text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                            <h3>Personal Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="input-label">First Name <span class="required-mark">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $member->first_name) }}" class="input-field" required>
                            </div>
                            
                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="input-label">Last Name <span class="required-mark">*</span></label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $member->last_name) }}" class="input-field" required>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="input-label">Email <span class="required-mark">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" class="input-field" required>
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="input-label">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $member->phone) }}" class="input-field">
                            </div>
                            
                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="input-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}" class="input-field">
                            </div>
                            
                            <!-- Address -->
                            <div>
                                <label for="address" class="input-label">Address</label>
                                <textarea name="address" id="address" rows="3" class="input-field">{{ old('address', $member->address) }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="flex items-center text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-graduation-cap mr-2 text-emerald-600"></i>
                            <h3>Academic Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student ID -->
                            <div>
                                <label for="student_id" class="input-label">Student ID</label>
                                <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $member->student_id) }}" class="input-field">
                            </div>
                            
                            <!-- Course -->
                            <div>
                                <label for="course" class="input-label">Course</label>
                                <input type="text" name="course" id="course" value="{{ old('course', $member->course) }}" class="input-field">
                            </div>
                            
                            <!-- Year Level -->
                            <div>
                                <label for="year_level" class="input-label">Year Level</label>
                                <select name="year_level" id="year_level" class="input-field">
                                    <option value="">-- Select Year Level --</option>
                                    <option value="1st Year" {{ (old('year_level', $member->year_level) == '1st Year') ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ (old('year_level', $member->year_level) == '2nd Year') ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ (old('year_level', $member->year_level) == '3rd Year') ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ (old('year_level', $member->year_level) == '4th Year') ? 'selected' : '' }}>4th Year</option>
                                    <option value="5th Year" {{ (old('year_level', $member->year_level) == '5th Year') ? 'selected' : '' }}>5th Year</option>
                                    <option value="Graduate" {{ (old('year_level', $member->year_level) == 'Graduate') ? 'selected' : '' }}>Graduate</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="flex items-center text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-id-card mr-2 text-amber-600"></i>
                            <h3>Membership Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Membership Status -->
                            <div>
                                <label for="membership_status" class="input-label">Membership Status</label>
                                <select name="membership_status" id="membership_status" class="input-field">
                                    <option value="Pending" {{ (old('membership_status', $member->membership_status) == 'Pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="Active" {{ (old('membership_status', $member->membership_status) == 'Active') ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ (old('membership_status', $member->membership_status) == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            
                            <!-- Role -->
                            <div>
                                <label for="role" class="input-label">Role</label>
                                <select name="role" id="role" class="input-field">
                                    <option value="Member" {{ (old('role', $member->role) == 'Member') ? 'selected' : '' }}>Member</option>
                                    <option value="President" {{ (old('role', $member->role) == 'President') ? 'selected' : '' }}>President</option>
                                    <option value="Vice President" {{ (old('role', $member->role) == 'Vice President') ? 'selected' : '' }}>Vice President</option>
                                    <option value="Secretary" {{ (old('role', $member->role) == 'Secretary') ? 'selected' : '' }}>Secretary</option>
                                    <option value="Treasurer" {{ (old('role', $member->role) == 'Treasurer') ? 'selected' : '' }}>Treasurer</option>
                                    <option value="Officer" {{ (old('role', $member->role) == 'Officer') ? 'selected' : '' }}>Officer</option>
                                </select>
                            </div>
                            
                            <!-- Membership Date -->
                            <div>
                                <label for="membership_date" class="input-label">Membership Date</label>
                                <input type="date" name="membership_date" id="membership_date" value="{{ old('membership_date', $member->membership_date ? $member->membership_date->format('Y-m-d') : '') }}" class="input-field">
                            </div>
                            
                            <!-- Membership Expiry -->
                            <div>
                                <label for="membership_expiry" class="input-label">Membership Expiry</label>
                                <input type="date" name="membership_expiry" id="membership_expiry" value="{{ old('membership_expiry', $member->membership_expiry ? $member->membership_expiry->format('Y-m-d') : '') }}" class="input-field">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="flex items-center text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            <h3>Additional Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Skills -->
                            <div>
                                <label for="skills" class="input-label">Skills</label>
                                <textarea name="skills" id="skills" rows="3" class="input-field" placeholder="e.g. Programming, Graphic Design, etc.">{{ old('skills', $member->skills) }}</textarea>
                            </div>
                            
                            <!-- Interests -->
                            <div>
                                <label for="interests" class="input-label">Interests</label>
                                <textarea name="interests" id="interests" rows="3" class="input-field" placeholder="e.g. Web Development, AI, etc.">{{ old('interests', $member->interests) }}</textarea>
                            </div>
                            
                            <!-- Profile Photo -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="profile_photo" class="input-label">Profile Photo</label>
                                <div class="mt-1 flex items-center">
                                    <div class="photo-preview">
                                        @if($member->profile_photo)
                                            <img src="{{ Storage::url($member->profile_photo) }}" alt="{{ $member->full_name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-500">
                                                <i class="fas fa-user text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-5">
                                        <input type="file" name="profile_photo" id="profile_photo" class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <p class="mt-1 text-xs text-gray-500">Leave empty to keep current photo. Max 2MB.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('members.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition border border-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-sm">
                            <i class="fas fa-save mr-2"></i> Update Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 