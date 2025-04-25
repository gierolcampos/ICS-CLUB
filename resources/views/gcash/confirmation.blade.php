@extends('layouts.app')

@section('styles')
<style>
    @media print {
        body {
            background: white !important;
        }
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
            background: white !important;
        }
        .card-header {
            background: #0d6efd !important;
            color: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .receipt-container {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #000 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        .receipt-header {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .receipt-footer {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .status-badge {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .status-paid {
            background-color: #d4edda !important;
            color: #155724 !important;
        }
        .status-pending {
            background-color: #fff3cd !important;
            color: #856404 !important;
        }
        .container {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .row {
            margin: 0 !important;
        }
        .col-md-8 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
        }
        .py-5 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        .receipt-section {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }

    .receipt-container {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 600px;
        margin: 0 auto;
    }

    .receipt-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .receipt-header {
        background-color: #f8f9fa;
        padding: 1.5rem;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .receipt-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e3f2fd;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .receipt-body {
        padding: 1.5rem;
    }

    .receipt-section {
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
        text-align: center;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e9ecef;
    }

    .receipt-row:last-child {
        border-bottom: none;
    }

    .label {
        color: #6c757d;
        font-weight: 500;
    }

    .value {
        color: #212529;
        font-weight: 500;
        text-align: right;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-paid {
        background-color: #d4edda;
        color: #155724;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .receipt-footer {
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #e0e0e0;
        text-align: center;
    }

    .print-btn {
        transition: all 0.3s ease;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        min-width: 200px;
    }

    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .loading {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 10000;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(!isset($order))
                <script>window.location.href = "{{ route('gcash.payment') }}";</script>
            @elseif($order->method !== 'GCASH')
                <div class="alert alert-danger text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                    <h4 class="mb-0">Invalid payment method. This page is for GCASH payments only.</h4>
                </div>
            @else
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white py-4 text-center">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-{{ $order->payment_status === 'Paid' ? 'check-circle' : 'clock' }} fa-3x mb-3"></i>
                            <h3 class="mb-1">Payment {{ $order->payment_status === 'Paid' ? 'Confirmation' : 'Pending' }}</h3>
                            <small class="opacity-75">
                                @if($order->payment_status === 'Paid')
                                    Your GCASH payment has been received
                                @else
                                    Your GCASH payment is pending confirmation
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="receipt-container">
                            <div class="receipt-header">
                                <div class="receipt-logo">
                                    <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash Logo" style="max-width: 100%; height: auto;">
                                </div>
                                <div class="receipt-title">
                                    <h4 class="mb-2">GCASH Payment Receipt</h4>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <small class="text-muted me-2">Ref: {{ $order->reference_number }}</small>
                                        <span class="status-badge {{ $order->payment_status === 'Paid' ? 'status-paid' : 'status-pending' }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="receipt-body">
                                <div class="receipt-section">
                                    <h5 class="section-title">
                                        <i class="fas fa-calendar-alt me-2"></i>Order Information
                                    </h5>
                                    <div class="receipt-row">
                                        <span class="label">Date & Time</span>
                                        <span class="value">{{ \Carbon\Carbon::parse($order->placed_on)->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="receipt-row">
                                        <span class="label">Payment Method</span>
                                        <span class="value">
                                            <i class="fas fa-mobile-alt me-2"></i>{{ $order->method }}
                                        </span>
                                    </div>
                                </div>

                                <div class="receipt-section">
                                    <h5 class="section-title">
                                        <i class="fas fa-user me-2"></i>Customer Details
                                    </h5>
                                    <div class="receipt-row">
                                        <span class="label">GCash Name</span>
                                        <span class="value">{{ $order->gcash_name }}</span>
                                    </div>
                                    <div class="receipt-row">
                                        <span class="label">GCash Number</span>
                                        <span class="value">
                                            <i class="fas fa-phone me-2"></i>{{ $order->gcash_num }}
                                        </span>
                                    </div>
                                </div>

                                <div class="receipt-section">
                                    <h5 class="section-title">
                                        <i class="fas fa-money-bill-wave me-2"></i>Payment Details
                                    </h5>
                                    <div class="receipt-row">
                                        <span class="label">Total Price</span>
                                        <span class="value text-primary">₱{{ number_format($order->total_price, 2) }}</span>
                                    </div>
                                    <div class="receipt-row">
                                        <span class="label">Amount Paid</span>
                                        <span class="value text-success">₱{{ number_format($order->gcash_amount, 2) }}</span>
                                    </div>
                                    <div class="receipt-row">
                                        <span class="label">Change Amount</span>
                                        <span class="value text-info">₱{{ number_format($order->change_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="receipt-footer">
                                <button class="btn btn-primary print-btn no-print" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>Print Confirmation
                                </button>
                                @auth
                                    <div class="mt-3">
                                        <a href="{{ route('gcash.orders') }}" class="btn btn-outline-primary no-print">
                                            <i class="fas fa-list me-2"></i>View Order History
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-info mt-3 text-center">
                                        <p class="mb-2">Want to track your payments?</p>
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm no-print">
                                            <i class="fas fa-sign-in-alt me-2"></i>Login or Register
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="loading">
    <img src="{{ asset('images/loading.gif') }}" alt="Loading">
</div>
@endsection

@section('scripts')
<script>
    // For print functionality
    window.onbeforeprint = function() {
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = 'none';
        });
    }
    window.onafterprint = function() {
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = '';
        });
    }

    // For loading animation
    function loading() {
        document.querySelector('.loading').style.display = 'none';
    }

    function fadeOut() {
        setTimeout(loading, 2000);
    }

    window.onload = fadeOut;
</script>
@endsection 