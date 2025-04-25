@extends('layouts.app')

@section('styles')
<style>
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

    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }

    .order-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .order-header {
        background-color: #f8f9fa;
        padding: 1.25rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .order-body {
        padding: 1.25rem;
    }

    .order-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e9ecef;
    }

    .order-row:last-child {
        border-bottom: none;
    }

    .order-label {
        color: #6c757d;
        font-weight: 500;
    }

    .order-value {
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

    .action-btn {
        transition: all 0.3s ease;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #e9ecef;
        margin-bottom: 1rem;
    }

    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">Your GCash Payments</h3>
                            <small class="opacity-75">View and manage your GCash payment history</small>
                        </div>
                        <a href="{{ route('gcash.payment') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus-circle me-2"></i> New Payment
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(count($orders) > 0)
                        <div class="row g-4">
                            @foreach($orders as $order)
                                <div class="col-md-6">
                                    <div class="order-card">
                                        <div class="order-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1">Order #{{ $order->reference_number }}</h5>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($order->placed_on)->format('M d, Y h:i A') }}
                                                    </small>
                                                </div>
                                                <span class="status-badge {{ $order->payment_status === 'Paid' ? 'status-paid' : 'status-pending' }}">
                                                    {{ $order->payment_status }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="order-body">
                                            <div class="order-row">
                                                <span class="order-label">Total Amount</span>
                                                <span class="order-value text-primary">₱{{ number_format($order->total_price, 2) }}</span>
                                            </div>
                                            <div class="order-row">
                                                <span class="order-label">GCash Number</span>
                                                <span class="order-value">
                                                    <i class="fas fa-phone me-2"></i>{{ $order->gcash_num }}
                                                </span>
                                            </div>
                                            <div class="order-row">
                                                <span class="order-label">Amount Paid</span>
                                                <span class="order-value text-success">₱{{ number_format($order->gcash_amount, 2) }}</span>
                                            </div>
                                            <div class="order-row">
                                                <span class="order-label">Change Amount</span>
                                                <span class="order-value text-info">₱{{ number_format($order->change_amount, 2) }}</span>
                                            </div>
                                            <div class="mt-3 d-flex justify-content-end gap-2">
                                                <a href="{{ route('gcash.confirmation', ['order_id' => $order->id]) }}" class="btn btn-primary btn-sm action-btn">
                                                    <i class="fas fa-eye me-2"></i>View Details
                                                </a>
                                                @if($order->payment_status === 'Paid')
                                                    <a href="{{ route('gcash.receipt') }}" class="btn btn-outline-primary btn-sm action-btn">
                                                        <i class="fas fa-receipt me-2"></i>Receipt
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h4 class="empty-state-title">No GCash Payments Found</h4>
                            <p class="empty-state-text">You haven't made any GCash payments yet.</p>
                            <a href="{{ route('gcash.payment') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Make a Payment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Payment Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<div class="loading">
    <img src="{{ asset('images/loading.gif') }}" alt="Loading">
</div>
@endsection

@section('scripts')
<script>
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