@extends('layouts.app')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&display=swap');

    .page-header {
        background: linear-gradient(to right, #1e40af, #3b82f6);
        padding: 1.5rem 0;
        margin-bottom: 2rem;
    }

    .page-title {
        color: white;
        font-size: 1.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #e5e7eb;
        font-size: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .action-button i {
        margin-right: 0.5rem;
    }

    .action-button.primary {
        background-color: #4f46e5;
        color: white;
        border: none;
    }

    .action-button.primary:hover {
        background-color: #4338ca;
    }

    .action-button.secondary {
        background-color: white;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .action-button.secondary:hover {
        background-color: #f9fafb;
    }

    .action-button.success {
        background-color: #059669;
        color: white;
        border: none;
    }

    .action-button.success:hover {
        background-color: #047857;
    }

    .letter-container {
        position: relative;
        min-height: 600px;
        background: white;
        padding: 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .letterhead {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .letterhead-logo {
        width: 100px;
        height: 100px;
        object-fit: contain;
    }

    .letterhead-center {
        text-align: center;
        flex: 1;
        padding: 0 1rem;
        font-family: 'Crimson Pro', serif;
    }

    .letterhead-header {
        font-size: 12px;
        line-height: 1.2;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .letterhead-title {
        font-size: 24px;
        line-height: 1.2;
        font-weight: bold;
        color: #000;
        margin: 8px 0;
        text-transform: uppercase;
    }

    .letterhead-address {
        font-size: 12px;
        line-height: 1.4;
        color: #000;
        margin: 4px 0;
    }

    .letterhead-org {
        font-size: 24px;
        line-height: 1.2;
        font-weight: bold;
        color: #000;
        margin: 8px 0;
    }

    .letterhead-email {
        font-size: 12px;
        line-height: 1.4;
        color: #0066cc;
        text-decoration: underline;
    }

    .letter-content {
        line-height: 1.6;
        font-size: 1rem;
        color: #374151;
        padding: 0 1rem;
    }

    .letter-date {
        text-align: right;
        margin-bottom: 1.5rem;
        color: #4b5563;
        font-family: 'Crimson Pro', serif;
        font-size: 1.1rem;
    }

    .letter-recipient {
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #1f2937;
        font-family: 'Crimson Pro', serif;
        font-size: 1.1rem;
    }

    .letter-body {
        font-family: 'Crimson Pro', serif;
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: justify;
        margin-bottom: 2rem;
    }

    .letter-attachment {
        margin-top: 2rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background-color: #f9fafb;
    }

    .attachment-link {
        display: inline-flex;
        align-items: center;
        color: #4f46e5;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .attachment-link:hover {
        color: #4338ca;
    }

    .letter-info {
        margin-top: 2rem;
        padding: 1.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
    }

    .info-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .info-value {
        font-size: 0.925rem;
        color: #111827;
        font-weight: 500;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-approved {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-sent {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-draft {
        background-color: #f3f4f6;
        color: #4b5563;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .page-header {
            display: none;
        }
        .letter-container {
            border: none;
            box-shadow: none;
            padding: 0;
        }
        .letter-info {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="py-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $letter->title ?? 'View Letter' }}</h2>
                <p class="mt-1 text-sm text-gray-600">Type: {{ ucfirst($letter->type ?? 'Unknown') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('letters.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back
                </a>
                @if(isset($letter))
                <a href="{{ route('letters.edit', $letter->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                @endif
            </div>
        </div>

        <!-- Letter Paper -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Letterhead -->
            <div class="border-b border-gray-200">
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between">
                        <img src="{{ asset('img/npc-logo.png') }}" alt="NPC Logo" class="h-20 w-20 object-contain" onerror="this.src='{{ asset('img/placeholder.png') }}'">
                        
                        <div class="text-center flex-1 mx-8">
                            <p class="text-xs font-semibold tracking-wide text-gray-900 uppercase">Republic of the Philippines</p>
                            <p class="text-xs font-semibold tracking-wide text-gray-900 uppercase">City of Navotas</p>
                            <h1 class="mt-2 text-2xl font-bold text-gray-900">NAVOTAS POLYTECHNIC COLLEGE</h1>
                            <p class="mt-1 text-sm text-gray-600">Bangus Street, Corner Apahap Street, North Bay Boulevard South, Navotas City</p>
                            <h2 class="mt-2 text-xl font-bold text-gray-900">ICS ORGANIZATION</h2>
                            <a href="mailto:ics@navotaspolytechniccollege.edu.ph" class="text-sm text-blue-600 hover:text-blue-800">
                                ics@navotaspolytechniccollege.edu.ph
                            </a>
                        </div>

                        <img src="{{ asset('img/ics-logo.png') }}" alt="ICS Logo" class="h-20 w-20 object-contain" onerror="this.src='{{ asset('img/placeholder.png') }}'">
                    </div>
                </div>
            </div>

            <!-- Letter Content -->
            <div class="px-8 py-6">
                <div class="text-right text-gray-600 mb-6">
                    {{ isset($letter) && $letter->date ? date('F d, Y', strtotime($letter->date)) : date('F d, Y') }}
                </div>

                <div class="mb-6">
                    <p class="text-lg font-medium text-gray-900">{{ $letter->recipient ?? 'Recipient' }}</p>
                </div>

                <div class="prose prose-sm max-w-none">
                    <div class="text-gray-900 whitespace-pre-line leading-relaxed">
                        {{ $letter->content ?? 'No content available.' }}
                    </div>
                </div>

                @if(isset($letter) && $letter->file_path)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Attachment</h3>
                            <a href="{{ Storage::url($letter->file_path) }}" target="_blank" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                {{ basename($letter->file_path) }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if(isset($letter))
            <!-- Letter Information -->
            <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                <h3 class="text-base font-medium text-gray-900 mb-4">Letter Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created by</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $letter->sender->name ?? 'Unknown' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created on</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $letter->created_at ? $letter->created_at->format('M d, Y h:i A') : 'Unknown' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $letter->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($letter->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                                   ($letter->status == 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($letter->status ?? 'Draft') }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $letter->updated_at ? $letter->updated_at->format('M d, Y h:i A') : 'Unknown' }}
                        </dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background: white;
        }
        .bg-gray-100,
        .shadow-sm,
        .rounded-lg {
            background: white !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        .max-w-4xl {
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .py-6:first-child > div > div:first-child,
        .bg-gray-50 {
            display: none !important;
        }
        .border-gray-200 {
            border-color: #000 !important;
        }
        @page {
            margin: 1.5cm;
            size: A4;
        }
        .prose {
            font-size: 12pt !important;
            line-height: 1.6 !important;
        }
    }
</style>
@endsection
