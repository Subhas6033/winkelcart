@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 760px;">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 text-center">
        <div class="card-body p-5">
            <div style="font-size: 54px; color: #2e7d32;">OK</div>
            <h2 class="mt-2">Order Placed Successfully</h2>
            <p class="text-muted">Thank you for shopping with WinkelKart.</p>

            @if($order)
                <div class="alert alert-light border mt-4">
                    <strong>Order Number:</strong> {{ $order->order_number }}<br>
                    <strong>Total Amount:</strong> Rs {{ number_format($order->total_amount, 2) }}<br>
                    <strong>Status:</strong> {{ $order->order_status }}
                </div>
            @endif

            <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                <a href="{{ route('my_orders') }}" class="btn btn-success">Go To My Orders</a>
                @if($order)
                    <a href="{{ route('track_order_direct', $order->order_number) }}" class="btn btn-outline-primary">Track This Order</a>
                @endif
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
