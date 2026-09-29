@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <h2 class="mb-3">Cancellation Policy</h2>
    <p class="text-muted">Last updated: {{ date('d M Y') }}</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5>1. Customer Cancellation</h5>
            <p>Orders can be cancelled while they are in pending/confirmed stage before dispatch.</p>

            <h5>2. Seller or Platform Cancellation</h5>
            <p>Orders may be cancelled due to stock unavailability, payment risk checks, or operational constraints.</p>

            <h5>3. Refund on Cancellation</h5>
            <p>Prepaid orders cancelled successfully are refunded as per return/refund policy timelines.</p>

            <h5>4. Repeated Abuse</h5>
            <p>Repeated fraudulent cancellations may lead to temporary account restrictions.</p>
        </div>
    </div>
</div>
@endsection
