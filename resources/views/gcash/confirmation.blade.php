@extends('layouts.app')

@section('styles')
<style>
    /* Custom styles for printing */
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
    
    /* Custom styles for Gcash page */
    .user-icon {
        color: #007bff;
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
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            @if(!isset($order))
                <script>window.location.href = "{{ route('gcash.payment') }}";</script>
            @elseif($order->method !== 'GCASH')
                <div class="alert alert-danger text-center py-3">
                    Invalid payment method. This page is for GCASH payments only.
                </div>
            @else
                <img style="display: block; margin: 0 auto;" src="{{ asset('images/gcash-logo.png') }}" alt="GCash Logo">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mt-3">Successfully Paid To:</h3>
                    </div>
                    <div class="card-body">
                        <i class="fas fa-user fa-10x user-icon d-flex justify-content-center mb-3"></i>
                        <h4 style="text-align: center; font-weight: 900;">Reference Number <span style="color: red;">{{ $order->reference_number }}</span></h4>
                        <p style="color: black;">GCASH Name: <span style="color: red; font-weight: 800;">{{ $order->maskedName }}</span></p>
                        <p style="color: black;">GCASH Number: <span style="color: red; font-weight: 800;">{{ $order->gcash_num }}</span></p>
                        <p style="color: black;">Total payment: <span style="color: red; font-weight: 800">₱{{ $order->total_price }}</span></p>
                        <p style="color: black;">Your payment: <span style="color: red; font-weight: 800;">₱{{ $order->gcash_amount }}</span></p>
                        <p style="color: black;">Your change: <span style="color: red; font-weight: 800">₱{{ $order->change_amount }}</span></p>
                        <p style="color: black;">Date: <span style="color: red; font-weight: 800;">{{ $order->placed_on }}</span></p>
                        @auth
                            <a href="{{ route('gcash.orders') }}" class="btn btn-primary no-print">Check your orders</a>
                        @else
                            <div class="alert alert-info text-center mt-3">
                                <p class="mb-2">Want to see your order history?</p>
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login or Register</a>
                            </div>
                        @endauth
                        <div>
                            <button class="print-btn btn btn-warning mt-2 text-white no-print" onclick="window.print()">Print Receipt</button>
                        </div>
                    </div>
                </div>
                <h6 style="text-align: end; color: red; margin-top: 5px; font-weight: bolder;">"NOTE: This is a GCASH payment confirmation system"</h6>
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