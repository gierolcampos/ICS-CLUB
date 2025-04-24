@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @guest
                <div class="alert alert-danger text-center">
                    <h4>Please <a href="{{ route('login') }}" class="alert-link">login</a> first to see your GCASH receipt!</h4>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center mb-0">GCASH RECEIPT</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('home') }}" class="btn btn-secondary mb-3">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </a>
                        
                        @if(isset($order) && $order)
                            <div class="receipt-container">
                                <div class="receipt-item">
                                    <p><strong>Date:</strong> <span>{{ \Carbon\Carbon::parse($order->placed_on)->format('M d, Y h:i A') }}</span></p>
                                    <p><strong>Name:</strong> <span>{{ $order->name ?? Auth::user()->name }}</span></p>
                                    <p><strong>Email:</strong> <span>{{ $order->email ?? Auth::user()->email }}</span></p>
                                    @if(isset($order->number))
                                    <p><strong>Number:</strong> <span>{{ $order->number }}</span></p>
                                    @endif
                                    @if(isset($order->address))
                                    <p><strong>Address:</strong> <span>{{ $order->address }}</span></p>
                                    @endif
                                    <p><strong>Payment Method:</strong> <span>{{ $order->method }}</span></p>
                                    <p><strong>Reference Number:</strong> <span class="text-danger">{{ $order->reference_number }}</span></p>
                                    @if(isset($order->total_products))
                                    <p><strong>Your Orders:</strong> <span>{{ $order->total_products }}</span></p>
                                    @endif
                                    <p><strong>Total Price:</strong> <span>₱{{ number_format($order->total_price, 2) }}</span></p>
                                    <p><strong>GCash Amount:</strong> <span>₱{{ number_format($order->gcash_amount, 2) }}</span></p>
                                    <p><strong>Change Amount:</strong> <span>₱{{ number_format($order->change_amount, 2) }}</span></p>
                                    <p><strong>Payment Status:</strong> 
                                        <span class="{{ $order->payment_status == 'Paid' ? 'text-success' : 'text-danger' }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </p>
                                    
                                    @if($order->payment_status == 'Paid')
                                        <button class="btn btn-primary print-btn" onclick="window.print()">
                                            <i class="fas fa-print"></i> Print Receipt
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-center">No GCASH payments found.</p>
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
    }

    .receipt-container {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    
    .receipt-item p {
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px dashed #ccc;
        padding-bottom: 5px;
    }

    .print-btn {
        margin-top: 20px;
        width: 100%;
    }
</style>
@endsection 