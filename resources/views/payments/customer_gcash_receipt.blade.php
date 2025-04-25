@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @guest
                <div class="alert alert-danger text-center p-4">
                    <i class="fas fa-lock fa-2x mb-3"></i>
                    <h4 class="mb-0">Please <a href="{{ route('login') }}" class="alert-link">login</a> first to see your GCASH receipt!</h4>
                </div>
            @else
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white py-4 text-center">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-receipt fa-3x mb-3"></i>
                            <h3 class="mb-1">GCASH RECEIPTS</h3>
                            <small class="opacity-75">View and manage your payment history</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i>Back to Home
                            </a>
                        </div>
                        
                        @if($orders->count() > 0)
                            <div class="row justify-content-center g-4">
                                @foreach($orders as $order)
                                    <div class="col-md-10">
                                        <div class="receipt-container h-100">
                                            <div class="receipt-header text-center">
                                                <div class="receipt-logo mx-auto mb-3">
                                                    <i class="fas fa-receipt fa-2x text-primary"></i>
                                                </div>
                                                <div class="receipt-title">
                                                    <h4 class="mb-2">GCASH Payment Receipt</h4>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <small class="text-muted me-2">Ref: {{ $order->reference_number }}</small>
                                                        <span class="status-badge {{ $order->payment_status == 'Paid' ? 'status-paid' : 'status-pending' }}">
                                                            {{ $order->payment_status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="receipt-body">
                                                <div class="receipt-section">
                                                    <h5 class="section-title text-center">
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
                                                    <h5 class="section-title text-center">
                                                        <i class="fas fa-user me-2"></i>Customer Details
                                                    </h5>
                                                    <div class="receipt-row">
                                                        <span class="label">Name</span>
                                                        <span class="value">{{ $order->name ?? Auth::user()->name }}</span>
                                                    </div>
                                                    <div class="receipt-row">
                                                        <span class="label">Email</span>
                                                        <span class="value">{{ $order->email ?? Auth::user()->email }}</span>
                                                    </div>
                                                    @if(isset($order->number))
                                                    <div class="receipt-row">
                                                        <span class="label">Contact</span>
                                                        <span class="value">
                                                            <i class="fas fa-phone me-2"></i>{{ $order->number }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                    @if(isset($order->address))
                                                    <div class="receipt-row">
                                                        <span class="label">Address</span>
                                                        <span class="value">
                                                            <i class="fas fa-map-marker-alt me-2"></i>{{ $order->address }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>

                                                <div class="receipt-section">
                                                    <h5 class="section-title text-center">
                                                        <i class="fas fa-money-bill-wave me-2"></i>Payment Details
                                                    </h5>
                                                    <div class="receipt-row">
                                                        <span class="label">Total Price</span>
                                                        <span class="value text-primary">₱{{ number_format($order->total_price, 2) }}</span>
                                                    </div>
                                                    <div class="receipt-row">
                                                        <span class="label">GCash Amount</span>
                                                        <span class="value text-success">₱{{ number_format($order->gcash_amount, 2) }}</span>
                                                    </div>
                                                    <div class="receipt-row">
                                                        <span class="label">Change Amount</span>
                                                        <span class="value text-info">₱{{ number_format($order->change_amount, 2) }}</span>
                                                    </div>
                                                </div>

                                                @if(isset($order->total_products))
                                                <div class="receipt-section">
                                                    <h5 class="section-title text-center">
                                                        <i class="fas fa-shopping-cart me-2"></i>Order Details
                                                    </h5>
                                                    <div class="receipt-row">
                                                        <span class="label">Products</span>
                                                        <span class="value">{{ $order->total_products }}</span>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            @if($order->payment_status == 'Paid')
                                                <div class="receipt-footer text-center">
                                                    <button class="btn btn-primary print-btn" onclick="window.print()">
                                                        <i class="fas fa-print me-2"></i>Print Receipt
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-receipt fa-4x text-muted mb-4"></i>
                                    <h4 class="mb-3">No GCASH payments found</h4>
                                    <p class="text-muted mb-4">You haven't made any GCASH payments yet.</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary">
                                        <i class="fas fa-home me-2"></i>Go to Home
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .btn, nav, footer, .card-header {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
        .receipt-container {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #e0e0e0 !important;
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
        border-bottom: 1px solid #e0e0e0;
    }

    .receipt-logo {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e3f2fd;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .receipt-title {
        margin-top: 1rem;
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

    .empty-state {
        padding: 3rem;
        background-color: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .empty-state i {
        color: #adb5bd;
    }
</style>
@endsection 