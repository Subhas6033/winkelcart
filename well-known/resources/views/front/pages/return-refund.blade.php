@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <h2 class="mb-3">Return and Refund Policy</h2>
    <p class="text-muted">Last updated: {{ date('d M Y') }}</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5>1. Return Window</h5>
            <p>Eligible products can be returned within the specified return window shown on the product/order page.</p>

            <h5>2. Return Eligibility</h5>
            <p>Items must be unused and in original condition with packaging, invoice, and accessories.</p>

            <h5>3. Non-Returnable Items</h5>
            <p>Perishable goods, intimate products, and items marked non-returnable are excluded unless damaged or incorrect.</p>

            <h5>4. Refund Timeline</h5>
            <p>Approved refunds are processed to the original payment method or wallet within 5-10 business days.</p>

            <h5>5. Disputes</h5>
            <p>If a refund is delayed, raise a support ticket with your order number for investigation.</p>
        </div>
    </div>
</div>
@endsection
