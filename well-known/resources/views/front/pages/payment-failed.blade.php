@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 760px;">
    <div class="card border-0 shadow-sm rounded-4 text-center">
        <div class="card-body p-5">
            <div style="font-size: 54px; color: #c62828;">X</div>
            <h2 class="mt-2">Payment Failed</h2>
            <p class="text-muted">We could not process your payment. Please try again or contact support.</p>

            <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                <a href="{{ route('cart_list') }}" class="btn btn-danger">Retry Payment</a>
                <a href="{{ route('support.index') }}" class="btn btn-outline-secondary">Contact Support</a>
                <a href="{{ url('/') }}" class="btn btn-outline-primary">Back To Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
