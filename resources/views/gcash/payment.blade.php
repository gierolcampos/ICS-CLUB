@extends('layouts.app')

@section('styles')
<style>
    .gcash-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .gcash-logo {
        max-width: 200px;
    }
    .gcash-color {
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
<div class="py-12 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">GCASH Payment</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/gcash-logo.png') }}" alt="GCash Logo" class="gcash-logo">
                            <h4 class="mt-3 gcash-color">Pay with GCASH</h4>
                        </div>

                        <form action="{{ route('payments.store') }}" method="POST" class="gcash-container">
                            @csrf
                            <input type="hidden" name="payment_method" value="GCASH">
                            
                            <div class="mb-3">
                                <label for="total_price" class="form-label">Payment Amount (₱)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="total_price" name="total_price" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="gcash_name" class="form-label">GCASH Account Name</label>
                                <input type="text" class="form-control" id="gcash_name" name="gcash_name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="gcash_num" class="form-label">GCASH Account Number</label>
                                <input type="tel" class="form-control" id="gcash_num" name="gcash_num" pattern="[0-9]{11}" placeholder="e.g. 09123456789" required>
                                <div class="form-text">Please enter a valid 11-digit phone number</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="gcash_amount" class="form-label">Amount Paid (₱)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="gcash_amount" name="gcash_amount" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Confirm Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Payments
                    </a>
                </div>
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
    // Calculate change automatically
    document.addEventListener('DOMContentLoaded', function() {
        const totalPriceInput = document.getElementById('total_price');
        const gcashAmountInput = document.getElementById('gcash_amount');
        
        function calculateChange() {
            const totalPrice = parseFloat(totalPriceInput.value) || 0;
            const gcashAmount = parseFloat(gcashAmountInput.value) || 0;
            
            if (gcashAmount < totalPrice) {
                gcashAmountInput.setCustomValidity('Amount paid must be greater than or equal to the payment amount');
            } else {
                gcashAmountInput.setCustomValidity('');
            }
        }
        
        totalPriceInput.addEventListener('input', calculateChange);
        gcashAmountInput.addEventListener('input', calculateChange);
    });

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