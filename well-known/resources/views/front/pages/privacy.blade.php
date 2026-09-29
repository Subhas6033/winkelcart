@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <h2 class="mb-3">Privacy Policy</h2>
    <p class="text-muted">Last updated: {{ date('d M Y') }}</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5>1. Information We Collect</h5>
            <p>We collect account details, order information, contact data, and support communications required to operate the marketplace.</p>

            <h5>2. How We Use Data</h5>
            <p>Your data is used for order fulfillment, account security, transaction processing, and customer support.</p>

            <h5>3. Data Sharing</h5>
            <p>Data is shared only with required partners (payment providers, logistics, and compliance authorities) to complete your transactions.</p>

            <h5>4. Security</h5>
            <p>We use technical and operational controls to protect personal data. Users should keep passwords private and report suspicious activity.</p>

            <h5>5. User Rights</h5>
            <p>You may request updates or deletion of your profile information where legally applicable by contacting support.</p>
        </div>
    </div>
</div>
@endsection
