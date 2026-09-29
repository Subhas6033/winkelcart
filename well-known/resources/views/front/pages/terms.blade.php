@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <h2 class="mb-3">Terms and Conditions</h2>
    <p class="text-muted">Last updated: {{ date('d M Y') }}</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5>1. Platform Usage</h5>
            <p>By using WinkelKart, you agree to provide correct information, keep your account secure, and use the platform lawfully.</p>

            <h5>2. Orders and Pricing</h5>
            <p>Prices and availability may change without prior notice. Orders are confirmed only after successful validation and acceptance.</p>

            <h5>3. Seller Responsibility</h5>
            <p>Sellers are responsible for product quality, legal compliance, and order fulfillment timelines.</p>

            <h5>4. Account Actions</h5>
            <p>WinkelKart may suspend accounts involved in fraud, abuse, policy violations, or suspicious transactions.</p>

            <h5>5. Limitation of Liability</h5>
            <p>WinkelKart is not liable for indirect losses arising from delays, third-party courier failures, or force majeure conditions.</p>
        </div>
    </div>
</div>
@endsection
