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
    .gcash-badge {
        background-color: #007bff;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    .paid-badge {
        background-color: #28a745;
    }
    .pending-badge {
        background-color: #ffc107;
        color: #212529;
    }
</style>
@endsection

@section('content')
<div class="py-12 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Your GCASH Payments</h3>
                        <a href="{{ route('gcash.payment') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> New Payment
                        </a>
                    </div>
                    <div class="card-body">
                        @if(count($orders) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Reference No.</th>
                                            <th>Amount</th>
                                            <th>GCASH Number</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->placed_on }}</td>
                                                <td>{{ $order->reference_number }}</td>
                                                <td>₱{{ $order->total_price }}</td>
                                                <td>{{ $order->gcash_num }}</td>
                                                <td>
                                                    @if($order->payment_status === 'Paid')
                                                        <span class="gcash-badge paid-badge">Paid</span>
                                                    @else
                                                        <span class="gcash-badge pending-badge">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('gcash.confirmation') }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                                <h4>No GCASH Payments Found</h4>
                                <p class="text-muted">You haven't made any GCASH payments yet.</p>
                                <a href="{{ route('gcash.payment') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus-circle me-1"></i> Make a Payment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Payment Dashboard
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