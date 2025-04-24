@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Create Letter</h1>
                        <p class="text-gray-600 mt-1">Generate official correspondence with letterhead</p>
                    </div>
                    <div>
                        <a href="{{ route('letters.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Letters
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('letters.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Letter Title</label>
                            <input type="text" name="title" id="title" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter letter title" required>
                        </div>
                        
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Letter Type</label>
                            <select id="type" name="type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select Type</option>
                                <option value="coa">Calendar of Activities</option>
                                <option value="Propreq">Proposal/Request</option>
                                <option value="Financial">Financial Report</option>
                                <option value="Postact">Post Activity Report</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <div>
                            <label for="recipient" class="block text-sm font-medium text-gray-700 mb-1">Recipient</label>
                            <input type="text" name="recipient" id="recipient" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Recipient name and/or organization" required>
                        </div>
                        
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" id="date" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Letter Content</label>
                        <textarea name="content" id="content" rows="12" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter the body of your letter here..." required></textarea>
                    </div>
                    
                    <div>
                        <label for="file_path" class="block text-sm font-medium text-gray-700 mb-1">Attachment (optional)</label>
                        <input type="file" name="file_path" id="file_path" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Accepted file types: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-gray-200">
                        <h2 class="text-lg font-medium text-gray-800 mb-3">Letter Preview</h2>
                        <div id="letter-preview" class="border border-gray-300 rounded-lg p-8 bg-white shadow-sm min-h-[600px]">
                            <!-- Letterhead -->
                            <div class="flex justify-between items-center border-b border-gray-300 pb-4 mb-6">
                                <div class="w-16 h-16">
                                    <img src="{{ asset('img/npc-logo.png') }}" alt="NPC Logo" class="w-16 h-16 object-contain" onerror="this.src='https://via.placeholder.com/64x64?text=NPC'">
                                </div>
                                <div class="text-center">
                                    <div class="text-xs text-gray-600">REPUBLIC OF THE PHILIPPINES</div>
                                    <div class="text-xs text-gray-600">CITY OF NAVOTAS</div>
                                    <div class="text-sm font-bold">NAVOTAS POLYTECHNIC COLLEGE</div>
                                    <div class="text-xs text-gray-600">Bangus Street, Corner Apahap Street, North Bay Boulevard North, Navotas City</div>
                                    <div class="font-bold text-base mt-2">ICS ORGANIZATION</div>
                                    <div class="text-xs text-blue-600">icsnavotas.request@npc.edu.ph</div>
                                </div>
                                <div class="w-16 h-16">
                                    <img src="{{ asset('img/ics-logo.png') }}" alt="ICS Logo" class="w-16 h-16 object-contain" onerror="this.src='https://via.placeholder.com/64x64?text=ICS'">
                                </div>
                            </div>
                            
                            <!-- Letter Content -->
                            <div id="preview-content" class="space-y-4">
                                <div class="text-right" id="preview-date">{{ date('F d, Y') }}</div>
                                <div id="preview-recipient" class="font-semibold"></div>
                                <div id="preview-body" class="mt-4 whitespace-pre-line"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="preview-btn" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                            <i class="fas fa-eye mr-2"></i> Preview
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            <i class="fas fa-save mr-2"></i> Save Letter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const previewBtn = document.getElementById('preview-btn');
        const recipientInput = document.getElementById('recipient');
        const dateInput = document.getElementById('date');
        const contentInput = document.getElementById('content');
        
        const previewRecipient = document.getElementById('preview-recipient');
        const previewDate = document.getElementById('preview-date');
        const previewBody = document.getElementById('preview-body');
        
        previewBtn.addEventListener('click', function() {
            // Update preview with current form values
            previewRecipient.textContent = recipientInput.value || 'Recipient Name';
            
            if (dateInput.value) {
                const formattedDate = new Date(dateInput.value).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                previewDate.textContent = formattedDate;
            }
            
            previewBody.textContent = contentInput.value || 'Letter content will appear here...';
            
            // Scroll to preview
            document.getElementById('letter-preview').scrollIntoView({ behavior: 'smooth' });
        });
        
        // Real-time preview updates
        [recipientInput, dateInput, contentInput].forEach(input => {
            input.addEventListener('input', function() {
                if (recipientInput.value) {
                    previewRecipient.textContent = recipientInput.value;
                }
                
                if (dateInput.value) {
                    const formattedDate = new Date(dateInput.value).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    previewDate.textContent = formattedDate;
                }
                
                if (contentInput.value) {
                    previewBody.textContent = contentInput.value;
                }
            });
        });
    });
</script>
@endsection 