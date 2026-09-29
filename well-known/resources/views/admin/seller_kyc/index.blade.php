@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-info mb-3">
            <i class="fas fa-info-circle mr-1"></i>
            Sellers can only list and sell products after their KYC is marked as <strong>Verified</strong> by admin.
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <strong>Seller KYC / Verification</strong>
                    <form method="GET" action="{{ route('admin.seller_kyc.index') }}" class="form-inline mt-2 mt-md-0">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search seller">
                        <select name="status" class="form-control mr-2">
                            <option value="">All Status</option>
                            @foreach($statusOptions as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @forelse($sellers as $seller)
                    @php
                        $kyc = $seller->kycVerification;
                        $status = optional($kyc)->status ?? 'Not Submitted';
                        $statusClass = 'badge-secondary';
                        if ($status === 'Verified') $statusClass = 'badge-success';
                        if ($status === 'Rejected') $statusClass = 'badge-danger';
                        if ($status === 'Pending') $statusClass = 'badge-warning';

                        $bankPassbookReference = optional($kyc)->bank_passbook_reference;
                        $idCardReference = optional($kyc)->id_card_reference;
                        $panCardReference = optional($kyc)->pan_card_reference;
                        $gstCertificateReference = optional($kyc)->gst_certificate_reference;
                        $paymentScreenshotReference = optional($kyc)->membership_payment_screenshot_reference;

                        $canSell = $status === 'Verified';
                    @endphp
                    <div class="border-bottom p-3">
                        {{-- Seller Header --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1">
                                    <strong>{{ $seller->name }}</strong>
                                    <span class="badge {{ $statusClass }} ml-2">{{ $status }}</span>
                                    @if($canSell)
                                        <span class="badge badge-success ml-1"><i class="fas fa-check-circle"></i> Can Sell</span>
                                    @else
                                        <span class="badge badge-dark ml-1"><i class="fas fa-ban"></i> Selling Blocked</span>
                                    @endif
                                </h6>
                                <small class="text-muted">
                                    {{ $seller->email }} &middot; {{ $seller->mobile ?: 'No mobile' }}
                                    @if(optional($seller->user_info)->address)
                                        &middot; {{ optional($seller->user_info)->address }}
                                    @endif
                                </small>
                                @if($kyc && $kyc->verified_at)
                                    <br><small class="text-muted">Verified at: {{ $kyc->verified_at->format('d M Y H:i') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            {{-- LEFT: KYC Details (read-only) --}}
                            <div class="col-lg-6 mb-3">
                                <div class="card border h-100">
                                    <div class="card-header py-2 bg-light">
                                        <strong>Submitted KYC Details</strong>
                                    </div>
                                    <div class="card-body py-2" style="font-size: 0.875rem;">
                                        @if($status === 'Not Submitted')
                                            <p class="text-muted mb-0">Seller has not submitted KYC yet.</p>
                                        @else
                                            <div class="row mb-2">
                                                <div class="col-sm-6"><strong>Legal Name:</strong> {{ optional($kyc)->legal_name ?? '-' }}</div>
                                                <div class="col-sm-6"><strong>GST:</strong> {{ optional($kyc)->gst_number ?? '-' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-6"><strong>PAN:</strong> {{ optional($kyc)->pan_number ?? '-' }}</div>
                                                <div class="col-sm-6"><strong>Aadhaar:</strong> {{ optional($kyc)->aadhaar_number ?? '-' }}</div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-6"><strong>Bank Holder:</strong> {{ optional($kyc)->bank_account_holder ?? '-' }}</div>
                                                <div class="col-sm-6"><strong>Bank Name:</strong> {{ optional($kyc)->bank_name ?? '-' }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-6"><strong>Bank A/C:</strong> {{ optional($kyc)->bank_account_number ?? '-' }}</div>
                                                <div class="col-sm-6"><strong>IFSC:</strong> {{ optional($kyc)->bank_ifsc_code ?? '-' }}</div>
                                            </div>
                                            <hr class="my-2">
                                            <strong>Uploaded Documents:</strong>
                                            <ul class="list-unstyled mt-1 mb-1 pl-2">
                                                <li>
                                                    @if(optional($kyc)->payment_status === 'Verified' && optional($kyc)->razorpay_payment_id)
                                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                        Membership Payment (₹999)
                                                        <span class="badge badge-success ml-1">Paid via Razorpay</span>
                                                        <br><small class="text-muted ml-3">Payment ID: {{ $kyc->razorpay_payment_id }}</small>
                                                        @if(optional($kyc)->membership_expiry)
                                                            <br><small class="text-muted ml-3">Expires: {{ $kyc->membership_expiry->format('d M Y') }}</small>
                                                        @endif
                                                    @elseif($paymentScreenshotReference && \Illuminate\Support\Str::startsWith($paymentScreenshotReference, 'uploads/'))
                                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                        <a href="{{ asset($paymentScreenshotReference) }}" target="_blank" rel="noopener">Payment Screenshot (₹999)</a>
                                                        @if(optional($kyc)->membership_payment_verified)
                                                            <span class="badge badge-success ml-1">Payment Approved</span>
                                                        @else
                                                            <span class="badge badge-warning ml-1">Payment Not Approved</span>
                                                        @endif
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i></span> Membership Payment (₹999): <em class="text-muted">Not paid yet</em>
                                                    @endif
                                                </li>
                                            </ul>

                                            {{-- Razorpay Payment Info / Legacy Screenshot Preview --}}
                                            @if(optional($kyc)->razorpay_payment_id)
                                                <div class="mt-2 mb-2 p-2 border rounded bg-white">
                                                    <small class="d-block font-weight-bold mb-1" style="color: #1a73e8;"><i class="fas fa-credit-card mr-1"></i> Razorpay Payment Details:</small>
                                                    <table class="table table-sm table-borderless mb-0" style="font-size: 0.82rem;">
                                                        <tr><td class="py-0 text-muted" style="width:130px;">Payment ID:</td><td class="py-0"><code>{{ $kyc->razorpay_payment_id }}</code></td></tr>
                                                        @if(optional($kyc)->razorpay_order_id)
                                                            <tr><td class="py-0 text-muted">Order ID:</td><td class="py-0"><code>{{ $kyc->razorpay_order_id }}</code></td></tr>
                                                        @endif
                                                        <tr><td class="py-0 text-muted">Payment Status:</td><td class="py-0"><span class="badge badge-{{ optional($kyc)->payment_status === 'Verified' ? 'success' : 'warning' }}">{{ optional($kyc)->payment_status ?? 'Pending' }}</span></td></tr>
                                                        @if(optional($kyc)->membership_start)
                                                            <tr><td class="py-0 text-muted">Active From:</td><td class="py-0">{{ $kyc->membership_start->format('d M Y') }}</td></tr>
                                                        @endif
                                                        @if(optional($kyc)->membership_expiry)
                                                            <tr><td class="py-0 text-muted">Expires:</td><td class="py-0">{{ $kyc->membership_expiry->format('d M Y') }}
                                                                @if($kyc->membership_expiry->isPast()) <span class="badge badge-danger ml-1">Expired</span> @else <span class="badge badge-success ml-1">Active</span> @endif
                                                            </td></tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            @elseif($paymentScreenshotReference && \Illuminate\Support\Str::startsWith($paymentScreenshotReference, 'uploads/'))
                                                <div class="mt-2 mb-2 p-2 border rounded bg-white">
                                                    <small class="d-block text-muted mb-1"><strong>Legacy Payment Screenshot:</strong></small>
                                                    <a href="{{ asset($paymentScreenshotReference) }}" target="_blank" rel="noopener">
                                                        <img src="{{ asset($paymentScreenshotReference) }}" alt="Payment Screenshot" class="img-fluid rounded border" style="max-width: 280px; max-height: 300px;">
                                                    </a>
                                                </div>
                                            @else
                                                <div class="mt-2 mb-2 p-2 border rounded bg-white text-center text-muted">
                                                    <i class="fas fa-rupee-sign fa-2x mb-1"></i>
                                                    <small class="d-block">No payment recorded yet. Seller needs to pay ₹999 via Razorpay.</small>
                                                </div>
                                            @endif

                                            <strong>Other Documents:</strong>
                                            <ul class="list-unstyled mt-1 mb-1 pl-2">
                                                <li>
                                                    @if($bankPassbookReference && \Illuminate\Support\Str::startsWith($bankPassbookReference, 'uploads/'))
                                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                        <a href="{{ asset($bankPassbookReference) }}" target="_blank" rel="noopener">Bank Passbook</a>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i></span> Bank Passbook: <em class="text-muted">{{ $bankPassbookReference ?: 'Not uploaded' }}</em>
                                                    @endif
                                                </li>
                                                <li>
                                                    @if($idCardReference && \Illuminate\Support\Str::startsWith($idCardReference, 'uploads/'))
                                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                        <a href="{{ asset($idCardReference) }}" target="_blank" rel="noopener">ID Card</a>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i></span> ID Card: <em class="text-muted">{{ $idCardReference ?: 'Not uploaded' }}</em>
                                                    @endif
                                                </li>
                                                <li>
                                                    @if($panCardReference && \Illuminate\Support\Str::startsWith($panCardReference, 'uploads/'))
                                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                        <a href="{{ asset($panCardReference) }}" target="_blank" rel="noopener">PAN Card</a>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i></span> PAN Card: <em class="text-muted">{{ $panCardReference ?: 'Not uploaded' }}</em>
                                                    @endif
                                                </li>
                                                <li>
                                                    @if($gstCertificateReference && \Illuminate\Support\Str::startsWith($gstCertificateReference, 'uploads/'))
                                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                        <a href="{{ asset($gstCertificateReference) }}" target="_blank" rel="noopener">GST Certificate</a>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i></span> GST Certificate: <em class="text-muted">{{ $gstCertificateReference ?: 'Not uploaded' }}</em>
                                                    @endif
                                                </li>
                                            </ul>
                                            <div class="row">
                                                <div class="col-sm-6"><strong>Mobile Verified:</strong> {{ $kyc && $kyc->mobile_verified_at ? 'Yes' : 'No' }}</div>
                                                <div class="col-sm-6"><strong>Email Verified:</strong> {{ $kyc && $kyc->email_verified_at ? 'Yes' : 'No' }}</div>
                                            </div>
                                            @if(optional($kyc)->admin_note)
                                                <hr class="my-2">
                                                <strong>Last Admin Note:</strong> <span class="text-muted">{{ optional($kyc)->admin_note }}</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: Verification Action Form --}}
                            <div class="col-lg-6 mb-3">
                                <div class="card border h-100">
                                    <div class="card-header py-2 bg-light">
                                        <strong>Verification Action</strong>
                                    </div>
                                    <div class="card-body py-2">
                                        <form method="POST" action="{{ route('admin.seller_kyc.upsert', $seller->id) }}">
                                            @csrf

                                            {{-- Status & Admin Note (primary action) --}}
                                            <div class="row mb-2">
                                                <div class="col-5">
                                                    <label class="small font-weight-bold mb-1">Status</label>
                                                    <select name="status" class="form-control form-control-sm" required>
                                                        @foreach(['Pending', 'Verified', 'Rejected'] as $option)
                                                            <option value="{{ $option }}" {{ $status === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-7">
                                                    <label class="small font-weight-bold mb-1">Admin Note</label>
                                                    <input type="text" name="admin_note" class="form-control form-control-sm" value="{{ optional($kyc)->admin_note }}" placeholder="Reason for approval/rejection">
                                                </div>
                                            </div>

                                            {{-- Payment Approval --}}
                                            <div class="mb-2 p-2 border rounded bg-white">
                                                <small class="d-block font-weight-bold mb-1"><i class="fas fa-receipt mr-1"></i> Membership Payment (₹999):</small>
                                                @if(optional($kyc)->razorpay_payment_id)
                                                    <div class="alert alert-success py-2 mb-2">
                                                        <i class="fas fa-check-circle mr-1"></i> <strong>Paid via Razorpay</strong>
                                                        <br><small>Payment ID: <code>{{ $kyc->razorpay_payment_id }}</code></small>
                                                        @if(optional($kyc)->membership_expiry)
                                                            <br><small>Expires: {{ $kyc->membership_expiry->format('d M Y') }}</small>
                                                        @endif
                                                    </div>
                                                @elseif($paymentScreenshotReference && \Illuminate\Support\Str::startsWith($paymentScreenshotReference, 'uploads/'))
                                                    <a href="{{ asset($paymentScreenshotReference) }}" target="_blank" rel="noopener">
                                                        <img src="{{ asset($paymentScreenshotReference) }}" alt="Payment Screenshot" class="img-fluid rounded border mb-1" style="max-width: 100%; max-height: 250px;">
                                                    </a>
                                                    <small class="d-block text-muted mb-2">Screenshot uploaded — review and approve below.</small>
                                                @else
                                                    <div class="text-center text-muted py-2">
                                                        <i class="fas fa-exclamation-triangle fa-2x mb-1" style="color: #eab308;"></i>
                                                        <small class="d-block">Seller has not made the payment yet.</small>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mb-2 p-2 border rounded {{ optional($kyc)->membership_payment_verified ? 'bg-success text-white' : 'bg-light' }}">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="hidden" name="membership_payment_verified" value="0">
                                                    @if(optional($kyc)->razorpay_payment_id)
                                                        <input type="hidden" name="membership_payment_verified" value="1">
                                                        <input type="checkbox" class="custom-control-input" id="payment_verified_{{ $seller->id }}" checked disabled>
                                                    @else
                                                        <input type="checkbox" class="custom-control-input" id="payment_verified_{{ $seller->id }}" name="membership_payment_verified" value="1" {{ optional($kyc)->membership_payment_verified ? 'checked' : '' }}>
                                                    @endif
                                                    <label class="custom-control-label font-weight-bold" for="payment_verified_{{ $seller->id }}">
                                                        <i class="fas fa-rupee-sign mr-1"></i> Approve Membership Payment (₹999)
                                                    </label>
                                                </div>
                                                <small class="{{ optional($kyc)->membership_payment_verified ? '' : 'text-muted' }}">
                                                    @if(optional($kyc)->razorpay_payment_id)
                                                        Auto-verified via Razorpay payment.
                                                    @elseif(optional($kyc)->membership_payment_verified)
                                                        Payment has been verified and approved.
                                                    @else
                                                        Review the payment screenshot above, then check this box to approve.
                                                    @endif
                                                </small>
                                            </div>

                                            <hr class="my-2">
                                            <small class="text-muted d-block mb-2">Override KYC fields (optional — only if correcting seller data):</small>

                                            <div class="mb-2">
                                                <input type="text" name="legal_name" class="form-control form-control-sm" value="{{ optional($kyc)->legal_name ?? $seller->name }}" placeholder="Legal name">
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="shiprocket_pickup_location" class="form-control form-control-sm" value="{{ optional($kyc)->shiprocket_pickup_location }}" placeholder="Shiprocket Pickup Location Name">
                                                    <small class="text-muted">Must match Shiprocket dashboard</small>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="shiprocket_pickup_id" class="form-control form-control-sm" value="{{ optional($kyc)->shiprocket_pickup_id }}" placeholder="Shiprocket Pickup Location ID (optional)">
                                                    <small class="text-muted">(Optional) Use for more reliable mapping</small>
                                                </div>
                                            </div>

                                            {{-- Pickup Address for Shiprocket Registration --}}
                                            <div class="border rounded p-2 mb-2" style="background:#f8f9ff;">
                                                <small class="font-weight-bold text-primary d-block mb-1">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    Shiprocket Pickup Address
                                                    @if(optional($kyc)->pickup_sync_status === 'synced')
                                                        <span class="badge badge-success ml-1">Synced</span>
                                                    @elseif(optional($kyc)->pickup_sync_status === 'failed')
                                                        <span class="badge badge-danger ml-1">Sync Failed</span>
                                                    @else
                                                        <span class="badge badge-secondary ml-1">Not synced</span>
                                                    @endif
                                                </small>
                                                <small class="text-danger d-block mb-2">
                                                    ⚠ Shiprocket requires "House No. / Flat No. / Road No." in Address Line 1 (e.g. "House No. 5, MG Road").
                                                    Saving here resets the sync status so it will be re-registered on next sync.
                                                </small>
                                                <div class="mb-1">
                                                    <input type="text" name="pickup_address" class="form-control form-control-sm" value="{{ optional($kyc)->pickup_address }}" placeholder="Address Line 1 — must include House No. / Flat No. / Road No.">
                                                </div>
                                                <div class="mb-1">
                                                    <input type="text" name="pickup_address_2" class="form-control form-control-sm" value="{{ optional($kyc)->pickup_address_2 }}" placeholder="Address Line 2 (locality / area)">
                                                </div>
                                                <div class="row">
                                                    <div class="col-4 mb-1">
                                                        <input type="text" name="pickup_city" class="form-control form-control-sm" value="{{ optional($kyc)->pickup_city }}" placeholder="City">
                                                    </div>
                                                    <div class="col-4 mb-1">
                                                        <input type="text" name="pickup_state" class="form-control form-control-sm" value="{{ optional($kyc)->pickup_state }}" placeholder="State">
                                                    </div>
                                                    <div class="col-4 mb-1">
                                                        <input type="text" name="pickup_pincode" class="form-control form-control-sm" value="{{ optional($kyc)->pickup_pincode }}" placeholder="Pincode">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mb-1">
                                                        <input type="text" name="pickup_phone" class="form-control form-control-sm" value="{{ optional($kyc)->pickup_phone ?? optional($seller->user_info)->phone }}" placeholder="Contact Phone (10 digits)">
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <input type="text" name="pickup_email" class="form-control form-control-sm" value="{{ optional($kyc)->pickup_email ?? $seller->email }}" placeholder="Contact Email">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="pan_number" class="form-control form-control-sm" value="{{ optional($kyc)->pan_number }}" placeholder="PAN number">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="aadhaar_number" class="form-control form-control-sm" value="{{ optional($kyc)->aadhaar_number }}" placeholder="Aadhaar number">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="bank_account_holder" class="form-control form-control-sm" value="{{ optional($kyc)->bank_account_holder }}" placeholder="Bank account holder">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="bank_name" class="form-control form-control-sm" value="{{ optional($kyc)->bank_name }}" placeholder="Bank name">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="bank_account_number" class="form-control form-control-sm" value="{{ optional($kyc)->bank_account_number }}" placeholder="Bank account number">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <input type="text" name="bank_ifsc_code" class="form-control form-control-sm" value="{{ optional($kyc)->bank_ifsc_code }}" placeholder="IFSC code">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="gst_number" class="form-control form-control-sm" value="{{ optional($kyc)->gst_number ?? optional($seller->user_info)->gst_no }}" placeholder="GST number">
                                            </div>

                                            <hr class="my-2">
                                            <strong class="d-block mb-2"><i class="fas fa-folder-open mr-1"></i> Uploaded Documents</strong>

                                            {{-- Document previews in a 2-column grid --}}
                                            <div class="row">
                                                {{-- Bank Passbook --}}
                                                <div class="col-6 mb-2">
                                                    <div class="border rounded p-2 bg-white h-100">
                                                        <small class="d-block font-weight-bold mb-1">Bank Passbook</small>
                                                        @if($bankPassbookReference && \Illuminate\Support\Str::startsWith($bankPassbookReference, 'uploads/'))
                                                            @if(\Illuminate\Support\Str::endsWith(strtolower($bankPassbookReference), '.pdf'))
                                                                <a href="{{ asset($bankPassbookReference) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm btn-block"><i class="fas fa-file-pdf mr-1"></i> View PDF</a>
                                                            @else
                                                                <a href="{{ asset($bankPassbookReference) }}" target="_blank" rel="noopener">
                                                                    <img src="{{ asset($bankPassbookReference) }}" alt="Bank Passbook" class="img-fluid rounded border" style="max-height: 140px;">
                                                                </a>
                                                            @endif
                                                        @else
                                                            <div class="text-center text-muted py-2"><i class="fas fa-times-circle"></i> <small>Not uploaded</small></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- ID Card --}}
                                                <div class="col-6 mb-2">
                                                    <div class="border rounded p-2 bg-white h-100">
                                                        <small class="d-block font-weight-bold mb-1">ID Card</small>
                                                        @if($idCardReference && \Illuminate\Support\Str::startsWith($idCardReference, 'uploads/'))
                                                            @if(\Illuminate\Support\Str::endsWith(strtolower($idCardReference), '.pdf'))
                                                                <a href="{{ asset($idCardReference) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm btn-block"><i class="fas fa-file-pdf mr-1"></i> View PDF</a>
                                                            @else
                                                                <a href="{{ asset($idCardReference) }}" target="_blank" rel="noopener">
                                                                    <img src="{{ asset($idCardReference) }}" alt="ID Card" class="img-fluid rounded border" style="max-height: 140px;">
                                                                </a>
                                                            @endif
                                                        @else
                                                            <div class="text-center text-muted py-2"><i class="fas fa-times-circle"></i> <small>Not uploaded</small></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- PAN Card --}}
                                                <div class="col-6 mb-2">
                                                    <div class="border rounded p-2 bg-white h-100">
                                                        <small class="d-block font-weight-bold mb-1">PAN Card</small>
                                                        @if($panCardReference && \Illuminate\Support\Str::startsWith($panCardReference, 'uploads/'))
                                                            @if(\Illuminate\Support\Str::endsWith(strtolower($panCardReference), '.pdf'))
                                                                <a href="{{ asset($panCardReference) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm btn-block"><i class="fas fa-file-pdf mr-1"></i> View PDF</a>
                                                            @else
                                                                <a href="{{ asset($panCardReference) }}" target="_blank" rel="noopener">
                                                                    <img src="{{ asset($panCardReference) }}" alt="PAN Card" class="img-fluid rounded border" style="max-height: 140px;">
                                                                </a>
                                                            @endif
                                                        @else
                                                            <div class="text-center text-muted py-2"><i class="fas fa-times-circle"></i> <small>Not uploaded</small></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- GST Certificate --}}
                                                <div class="col-6 mb-2">
                                                    <div class="border rounded p-2 bg-white h-100">
                                                        <small class="d-block font-weight-bold mb-1">GST Certificate</small>
                                                        @if($gstCertificateReference && \Illuminate\Support\Str::startsWith($gstCertificateReference, 'uploads/'))
                                                            @if(\Illuminate\Support\Str::endsWith(strtolower($gstCertificateReference), '.pdf'))
                                                                <a href="{{ asset($gstCertificateReference) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm btn-block"><i class="fas fa-file-pdf mr-1"></i> View PDF</a>
                                                            @else
                                                                <a href="{{ asset($gstCertificateReference) }}" target="_blank" rel="noopener">
                                                                    <img src="{{ asset($gstCertificateReference) }}" alt="GST Certificate" class="img-fluid rounded border" style="max-height: 140px;">
                                                                </a>
                                                            @endif
                                                        @else
                                                            <div class="text-center text-muted py-2"><i class="fas fa-times-circle"></i> <small>Not uploaded</small></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-2">
                                            <small class="text-muted d-block mb-2">Override document references (optional):</small>
                                            <div class="mb-2">
                                                <input type="text" name="membership_payment_screenshot_reference" class="form-control form-control-sm" value="{{ $paymentScreenshotReference }}" placeholder="Payment screenshot reference">
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="bank_passbook_reference" class="form-control form-control-sm" value="{{ optional($kyc)->bank_passbook_reference ?? optional($kyc)->document_reference }}" placeholder="Bank passbook reference">
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="id_card_reference" class="form-control form-control-sm" value="{{ optional($kyc)->id_card_reference }}" placeholder="ID card reference">
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="pan_card_reference" class="form-control form-control-sm" value="{{ optional($kyc)->pan_card_reference }}" placeholder="PAN card reference">
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="gst_certificate_reference" class="form-control form-control-sm" value="{{ optional($kyc)->gst_certificate_reference }}" placeholder="GST certificate reference">
                                            </div>

                                            <button type="submit" class="btn btn-sm btn-primary mt-1">
                                                <i class="fas fa-save mr-1"></i> Save Verification
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">No sellers found.</div>
                @endforelse
            </div>
            <div class="card-footer">
                {{ $sellers->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
