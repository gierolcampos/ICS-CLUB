@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Payment Details</h1>
                        <p class="text-gray-600 mt-1">View payment transaction information</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Payments
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <!-- Transaction Details -->
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Transaction ID</p>
                                <p class="mt-1 text-sm text-gray-900">#{{ $payment->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Payment Status</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $payment->payment_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $payment->payment_status }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Payment Method</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $payment->method === 'CASH' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $payment->method }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Date & Time</p>
                                <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->placed_on)->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Amount</p>
                                <p class="mt-1 text-sm text-gray-900">₱{{ number_format($payment->total_price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Member</p>
                                <p class="mt-1 text-sm text-gray-900">{{ optional($payment->user)->name ?? 'Guest' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Officer in-charge</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->officer_in_charge ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Receipt Control Number</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->receipt_control_number ?? 'N/A' }}</p>
                            </div>
                            @if($payment->description)
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-500">Description</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Method Specific Details -->
                    @if($payment->method === 'GCASH')
                    <div class="px-6 py-4 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">GCash Payment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">GCash Account Name</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->gcash_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">GCash Number</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->gcash_num }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Amount Paid</p>
                                <p class="mt-1 text-sm text-gray-900">₱{{ number_format($payment->gcash_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Change</p>
                                <p class="mt-1 text-sm text-gray-900">₱{{ number_format($payment->change_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Reference Number</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->reference_number }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($payment->method === 'CASH')
                    <div class="px-6 py-4 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cash Payment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Payment Status</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $payment->payment_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $payment->payment_status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    @if($payment->method === 'GCASH')
                    <a href="{{ route('gcash.receipt') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 transition">
                        <i class="fas fa-receipt mr-2"></i> View GCash Receipt
                    </a>
                    @endif
                    <a href="{{ route('payments.edit', $payment->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this payment? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 transition">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 