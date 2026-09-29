@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <h2 class="mb-3">Shipping and Delivery Policy</h2>
    <p class="text-muted">Last updated: {{ date('d M Y') }}</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5>1. Dispatch Timeline</h5>
            <p>Orders are generally dispatched within 1-3 business days after confirmation, subject to stock availability.</p>

            <h5>2. Delivery Coverage</h5>
            <p>Delivery is available to serviceable pin codes. Some remote areas may have extended timelines.</p>

            <h5>3. Tracking</h5>
            <p>You can track order status from My Orders or the Track Order section using the order number.</p>

            <h5>4. Delivery Delays</h5>
            <p>Delays due to weather, strikes, or transport disruptions may occur. We will notify users where possible.</p>

            <h5>5. Failed Delivery Attempts</h5>
            <p>After repeated failed attempts, orders may be returned and refunded as per policy.</p>
        </div>
    </div>
</div>
@endsection
