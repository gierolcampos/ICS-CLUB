@extends('layouts.app')

@section('styles')
<style>
    /* Enhanced styling for members table */
    .members-table {
        border-spacing: 0;
        border-collapse: separate;
    }
    .members-table th {
        position: sticky;
        top: 0;
        background-color: #f9fafb;
        z-index: 10;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border-bottom: 2px solid #e5e7eb;
        color: #4b5563;
    }
    .table-row {
        transition: all 0.2s ease;
    }
    .table-row:hover {
        background-color: #f3f4f6;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 500;
        font-size: 0.75rem;
    }
    .status-badge-active {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .status-badge-inactive {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .status-badge-pending {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .checkbox-container {
        display: block;
        position: relative;
        cursor: pointer;
        user-select: none;
        width: 20px;
        height: 20px;
    }
    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #fff;
        border: 2px solid #e5e7eb;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    .checkbox-container:hover input ~ .checkmark {
        border-color: #4F46E5;
    }
    .checkbox-container input:checked ~ .checkmark {
        background-color: #4F46E5;
        border-color: #4F46E5;
    }
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    .checkbox-container input:checked ~ .checkmark:after {
        display: block;
    }
    .checkbox-container .checkmark:after {
        left: 6px;
        top: 2px;
        width: 6px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    .table-container {
        overflow-x: auto;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        border: 1px solid rgba(229, 231, 235, 0.5);
    }
    
    /* Custom action buttons */
    .action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2rem;
        width: 2rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        color: #6B7280;
    }
    .action-icon:hover {
        background-color: #F3F4F6;
    }
    .action-icon.view {
        color: #4F46E5;
    }
    .action-icon.view:hover {
        background-color: rgba(79, 70, 229, 0.1);
    }
    .action-icon.edit {
        color: #F59E0B;
    }
    .action-icon.edit:hover {
        background-color: rgba(245, 158, 11, 0.1);
    }
    .action-icon.delete {
        color: #EF4444;
    }
    .action-icon.delete:hover {
        background-color: rgba(239, 68, 68, 0.1);
    }
    
    /* Stats Cards */
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
    .stat-card-icon {
        transition: all 0.3s ease;
    }
    .stat-card:hover .stat-card-icon {
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Members Management</h1>
                        <p class="text-gray-600 mt-1">Manage and organize all club members</p>
                    </div>
                    <div class="flex flex-wrap mt-4 md:mt-0 gap-3">
                        <button id="exportBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </button>
                        <div class="relative" id="bulkActionDropdown">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                                <i class="fas fa-cog mr-2"></i> Bulk Actions <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10" id="bulkActionMenu">
                                <div class="py-1">
                                    <button class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i> Set as Active
                                    </button>
                                    <button class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-times-circle text-red-500 mr-2"></i> Set as Inactive
                                    </button>
                                    <button class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-trash-alt text-red-500 mr-2"></i> Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('members.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            <i class="fas fa-plus mr-2"></i> Add Member
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button class="ml-auto" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times text-green-500"></i>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Search & Filter -->
                <div class="mb-6 bg-gray-50 p-5 rounded-xl shadow-sm border border-gray-100">
                    <form method="GET" action="{{ route('members.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search by name, email or ID..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm">
                                <option value="">All Statuses</option>
                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <select id="course" name="course" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm">
                                <option value="">All Courses</option>
                                <optgroup label="Four-year Courses">
                                    <option value="Bachelor of Science in Business Administration Major in Marketing Management" {{ request('course') == 'Bachelor of Science in Business Administration Major in Marketing Management' ? 'selected' : '' }}>BS in Business Administration Major in Marketing Management</option>
                                    <option value="Bachelor of Science in Business Administration Major in Human Resource Development and Management" {{ request('course') == 'Bachelor of Science in Business Administration Major in Human Resource Development and Management' ? 'selected' : '' }}>BS in Business Administration Major in Human Resource Development and Management</option>
                                    <option value="Bachelor of Science in Business Administration Major in Financial Management" {{ request('course') == 'Bachelor of Science in Business Administration Major in Financial Management' ? 'selected' : '' }}>BS in Business Administration Major in Financial Management</option>
                                    <option value="Bachelor of Secondary Education Major in Filipino" {{ request('course') == 'Bachelor of Secondary Education Major in Filipino' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Filipino</option>
                                    <option value="Bachelor of Secondary Education Major in English" {{ request('course') == 'Bachelor of Secondary Education Major in English' ? 'selected' : '' }}>Bachelor of Secondary Education Major in English</option>
                                    <option value="Bachelor of Secondary Education Major in Mathematics" {{ request('course') == 'Bachelor of Secondary Education Major in Mathematics' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Mathematics</option>
                                    <option value="Bachelor of Elementary Education" {{ request('course') == 'Bachelor of Elementary Education' ? 'selected' : '' }}>Bachelor of Elementary Education</option>
                                </optgroup>
                                <optgroup label="Two-year Course">
                                    <option value="Associate in Information Systems (AIS)" {{ request('course') == 'Associate in Information Systems (AIS)' ? 'selected' : '' }}>Associate in Information Systems (AIS)</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="md:col-span-3 flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                <i class="fas fa-filter mr-2"></i> Apply Filters
                            </button>
                            <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                                <i class="fas fa-redo mr-2"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Selected count -->
                <div id="selectedCount" class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-3 rounded-lg mb-6 hidden shadow-sm">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span id="countText" class="font-medium">0 members selected</span>
                        </div>
                        <button id="clearSelection" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition">
                            Clear selection
                        </button>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="table-container">
                    <table class="min-w-full divide-y divide-gray-200 members-table">
                        <thead>
                            <tr>
                                <th class="text-left">
                                    <label class="checkbox-container">
                                        <input type="checkbox" id="selectAll">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th class="text-left">Name</th>
                                <th class="text-left">Email</th>
                                <th class="text-left">Student ID</th>
                                <th class="text-left">Course</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Joined</th>
                                <th class="text-right w-72">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($members as $member)
                            <tr class="table-row">
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <label class="checkbox-container">
                                        <input type="checkbox" class="member-checkbox" data-id="{{ $member->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($member->profile_photo)
                                                <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" src="{{ Storage::url($member->profile_photo) }}" alt="{{ $member->full_name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-sm">
                                                    {{ substr($member->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $member->full_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $member->role ?? 'Member' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    {{ $member->student_id ?: 'N/A' }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm">
                                    @if($member->course)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $member->course }}
                                    </span>
                                    @else
                                    <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="status-badge 
                                        {{ $member->membership_status === 'Active' ? 'status-badge-active' : '' }}
                                        {{ $member->membership_status === 'Inactive' ? 'status-badge-inactive' : '' }}
                                        {{ $member->membership_status === 'Pending' ? 'status-badge-pending' : '' }}">
                                        <i class="fas {{ $member->membership_status === 'Active' ? 'fa-check-circle' : 
                                                        ($member->membership_status === 'Inactive' ? 'fa-times-circle' : 'fa-clock') }} mr-1"></i>
                                        {{ $member->membership_status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 font-medium">{{ $member->created_at ? $member->created_at->format('M d, Y') : 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $member->created_at ? $member->created_at->diffForHumans() : '' }}</div>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('members.show', ['member' => $member->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-xs">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        <a href="{{ route('members.edit', ['member' => $member->id]) }}" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition text-xs">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('members.destroy', ['member' => $member->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-xs">
                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <div class="bg-gray-100 rounded-full p-4 mb-4">
                                            <i class="fas fa-users text-3xl text-gray-400"></i>
                                        </div>
                                        <p class="text-lg font-medium mb-1">No members found</p>
                                        <p class="text-sm mb-3">Try adjusting your search or filter to find what you're looking for.</p>
                                        <a href="{{ route('members.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                                            <i class="fas fa-plus mr-2"></i> Add your first member
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between">
                    <div class="text-sm text-gray-700 font-medium mb-4 sm:mb-0">
                        Showing <span class="font-bold text-indigo-600">{{ $members->firstItem() ?? 0 }}</span> to <span class="font-bold text-indigo-600">{{ $members->lastItem() ?? 0 }}</span> of <span class="font-bold text-indigo-600">{{ $members->total() }}</span> members
                    </div>
                    {{ $members->links() }}
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Members -->
            <div class="bg-white rounded-xl shadow-md p-6 stat-card border border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Members</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $members->total() }}</p>
                        <div class="mt-2 flex items-center">
                            <div class="text-sm text-green-600 font-medium flex items-center mr-2">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i> 8%
                            </div>
                            <span class="text-xs text-gray-500">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full border border-indigo-200 stat-card-icon">
                        <i class="fas fa-users text-xl text-indigo-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Active Members -->
            <div class="bg-white rounded-xl shadow-md p-6 stat-card border border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Members</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $members->where('membership_status', 'Active')->count() }}</p>
                        <div class="mt-2 flex items-center">
                            <div class="text-sm text-green-600 font-medium flex items-center mr-2">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i> 5%
                            </div>
                            <span class="text-xs text-gray-500">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full border border-green-200 stat-card-icon">
                        <i class="fas fa-user-check text-xl text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Courses -->
            <div class="bg-white rounded-xl shadow-md p-6 stat-card border border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Different Courses</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $members->unique('course')->count() }}</p>
                        <div class="mt-2 flex items-center">
                            <span class="text-xs text-gray-500">Unique programs represented</span>
                        </div>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full border border-purple-200 stat-card-icon">
                        <i class="fas fa-book text-xl text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- New Members -->
            <div class="bg-white rounded-xl shadow-md p-6 stat-card border border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">New This Month</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">12</p>
                        <div class="mt-2 flex items-center">
                            <div class="text-sm text-green-600 font-medium flex items-center mr-2">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i> 11%
                            </div>
                            <span class="text-xs text-gray-500">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full border border-blue-200 stat-card-icon">
                        <i class="fas fa-user-plus text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle bulk action dropdown
        const bulkActionBtn = document.querySelector('#bulkActionDropdown button');
        const bulkActionMenu = document.getElementById('bulkActionMenu');
        if (bulkActionBtn && bulkActionMenu) {
            bulkActionBtn.addEventListener('click', function() {
                bulkActionMenu.classList.toggle('hidden');
            });
            
            // Close the menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!bulkActionBtn.contains(event.target) && !bulkActionMenu.contains(event.target)) {
                    bulkActionMenu.classList.add('hidden');
                }
            });
        }
        
        // Select all checkboxes
        const selectAllCheckbox = document.getElementById('selectAll');
        const memberCheckboxes = document.querySelectorAll('.member-checkbox');
        const selectedCountElem = document.getElementById('selectedCount');
        const countTextElem = document.getElementById('countText');
        const clearSelectionBtn = document.getElementById('clearSelection');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                memberCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateSelectedCount();
            });
        }
        
        memberCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        if (clearSelectionBtn) {
            clearSelectionBtn.addEventListener('click', function() {
                memberCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
                updateSelectedCount();
            });
        }
        
        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
            if (countTextElem) countTextElem.textContent = `${checkedCount} ${checkedCount === 1 ? 'member' : 'members'} selected`;
            if (selectedCountElem) {
                if (checkedCount > 0) {
                    selectedCountElem.classList.remove('hidden');
                } else {
                    selectedCountElem.classList.add('hidden');
                }
            }
        }
    });
</script>
@endsection 