@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-id-card mr-2"></i>Seller KYC Submission</h5>
                        <a href="{{ url('admin/dashboard') }}" class="btn btn-light btn-sm">Back to Dashboard</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
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

                        {{-- Removed old red alert about membership payment being mandatory --}}

                        @php
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
                            $isHotelCategory = optional($user->user_info)->business_category_id === 4;
                        @endphp

                        <div class="mb-4 p-3 rounded border bg-light">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <strong>Current Status:</strong>
                                    <span class="badge {{ $statusClass }} ml-1">{{ $status }}</span>
                                </div>
                                <small class="text-muted">
                                    Last Verified At:
                                    {{ optional($kyc)->verified_at ? optional($kyc)->verified_at->format('d M Y H:i') : '-' }}
                                </small>
                            </div>
                            <div class="mt-2 text-muted">
                                <strong>Registered Mobile:</strong> {{ $user->mobile ?: '-' }}
                                <span class="mx-2">|</span>
                                <strong>Registered Email:</strong> {{ $user->email ?: '-' }}
                            </div>

                            @if(optional($kyc)->admin_note)
                                <div class="mt-2">
                                    <strong>Admin Note:</strong>
                                    <span class="text-muted">{{ optional($kyc)->admin_note }}</span>
                                </div>
                            @endif

                            @if($status === 'Verified')
                                <small class="text-muted d-block mt-2">
                                    Updating any KYC details will re-submit this profile and reset status to Pending until admin reviews.
                                </small>
                            @endif
                        </div>

                        <form action="{{ route('seller.kyc.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @if(!$isHotelCategory)
                                <h6 class="font-weight-bold text-primary mb-3">Identity Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Shiprocket Pickup Location Name <span class="text-danger">*</span></label>
                                            <input type="text" name="shiprocket_pickup_location" class="form-control" value="{{ old('shiprocket_pickup_location', optional($kyc)->shiprocket_pickup_location) }}" required>
                                            <small class="text-muted">This must exactly match the pickup location name in your Shiprocket dashboard.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Shiprocket Pickup Location ID</label>
                                            <input type="text" name="shiprocket_pickup_id" class="form-control" value="{{ old('shiprocket_pickup_id', optional($kyc)->shiprocket_pickup_id) }}">
                                            <small class="text-muted">(Optional) Enter the Shiprocket pickup location ID if available for more reliable mapping.</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pickup physical address (used to register the pickup location in Shiprocket) --}}
                                <div class="card border-primary mb-3">
                                    <div class="card-header bg-primary text-white py-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i> Pickup Address
                                        <small class="d-block mt-1 font-weight-normal">This address will be registered as your pickup location in Shiprocket. Orders will be picked up from here.</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning py-2 mb-3">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            <strong>Address Line 1 must include a House No., Flat No., or Road No.</strong><br>
                                            Example: <em>House No. 5, MG Road</em> &nbsp;or&nbsp; <em>Flat 3B, Railway Station More</em>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Address Line 1 <span class="text-danger">*</span></label>
                                                    <input type="text" name="pickup_address" class="form-control" value="{{ old('pickup_address', optional($kyc)->pickup_address) }}" placeholder="e.g. House No. 5, MG Road" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Address Line 2 <small class="text-muted">(Locality / Area)</small></label>
                                                    <input type="text" name="pickup_address_2" class="form-control" value="{{ old('pickup_address_2', optional($kyc)->pickup_address_2) }}" placeholder="e.g. Railway Station More">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">City <span class="text-danger">*</span></label>
                                                    <input type="text" name="pickup_city" class="form-control" value="{{ old('pickup_city', optional($kyc)->pickup_city ?? optional($user->user_info)->city) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">State <span class="text-danger">*</span></label>
                                                    <input type="text" name="pickup_state" class="form-control" value="{{ old('pickup_state', optional($kyc)->pickup_state ?? optional($user->user_info)->state) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Pincode <span class="text-danger">*</span></label>
                                                    <input type="text" name="pickup_pincode" class="form-control" value="{{ old('pickup_pincode', optional($kyc)->pickup_pincode ?? optional($user->user_info)->pincode) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Contact Phone <span class="text-danger">*</span></label>
                                                    <input type="text" name="pickup_phone" class="form-control" value="{{ old('pickup_phone', optional($kyc)->pickup_phone ?? optional($user->user_info)->phone) }}" placeholder="10-digit mobile number" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Contact Email <span class="text-danger">*</span></label>
                                                    <input type="email" name="pickup_email" class="form-control" value="{{ old('pickup_email', optional($kyc)->pickup_email ?? $user->email) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Legal Name <span class="text-danger">*</span></label>
                                        <input type="text" name="legal_name" class="form-control" value="{{ old('legal_name', optional($kyc)->legal_name ?? $user->name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">PAN Number <span class="text-danger">*</span></label>
                                        <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number', optional($kyc)->pan_number) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Aadhaar Number <span class="text-danger">*</span></label>
                                        <input type="text" name="aadhaar_number" class="form-control" value="{{ old('aadhaar_number', optional($kyc)->aadhaar_number) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">GST Number <span class="text-danger">*</span></label>
                                        <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number', optional($kyc)->gst_number ?? optional($user->user_info)->gst_no) }}" required>
                                    </div>
                                </div>
                            </div>

                            <h6 class="font-weight-bold text-primary mb-3 mt-2">Bank Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Bank Account Holder Name <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_account_holder" class="form-control" value="{{ old('bank_account_holder', optional($kyc)->bank_account_holder) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Bank Name <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', optional($kyc)->bank_name) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Bank Account Number <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', optional($kyc)->bank_account_number) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">IFSC Code <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_ifsc_code" class="form-control" value="{{ old('bank_ifsc_code', optional($kyc)->bank_ifsc_code) }}" required>
                                    </div>
                                </div>
                            </div>

                            <h6 class="font-weight-bold text-primary mb-3 mt-2">Mandatory Documents</h6>
                            <p class="text-muted mb-3">All four documents below are mandatory for seller KYC approval.</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Front Page of Bank Passbook <span class="text-danger">*</span></label>
                                        <input type="file" name="bank_passbook_file" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        <small class="text-muted">PDF/JPG/JPEG/PNG/WEBP, max 500KB.</small>
                                        @if($bankPassbookReference && \Illuminate\Support\Str::startsWith($bankPassbookReference, 'uploads/'))
                                            <div class="mt-1">
                                                <a href="{{ asset($bankPassbookReference) }}" target="_blank" rel="noopener">View current bank passbook</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">ID Card <span class="text-danger">*</span></label>
                                        <input type="file" name="id_card_file" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        <small class="text-muted">PDF/JPG/JPEG/PNG/WEBP, max 4MB.</small>
                                        @if($idCardReference && \Illuminate\Support\Str::startsWith($idCardReference, 'uploads/'))
                                            <div class="mt-1">
                                                <a href="{{ asset($idCardReference) }}" target="_blank" rel="noopener">View current ID card</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">PAN Card <span class="text-danger">*</span></label>
                                        <input type="file" name="pan_card_file" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        <small class="text-muted">PDF/JPG/JPEG/PNG/WEBP, max 4MB.</small>
                                        @if($panCardReference && \Illuminate\Support\Str::startsWith($panCardReference, 'uploads/'))
                                            <div class="mt-1">
                                                <a href="{{ asset($panCardReference) }}" target="_blank" rel="noopener">View current PAN card</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">GST Registration Certificate (GSTIN) <span class="text-danger">*</span></label>
                                        <input type="file" name="gst_certificate_file" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        <small class="text-muted">PDF/JPG/JPEG/PNG/WEBP, max 4MB.</small>
                                        @if($gstCertificateReference && \Illuminate\Support\Str::startsWith($gstCertificateReference, 'uploads/'))
                                            <div class="mt-1">
                                                <a href="{{ asset($gstCertificateReference) }}" target="_blank" rel="noopener">View current GST certificate</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <button type="submit" class="btn btn-success mr-2">
                                    <i class="fas fa-paper-plane mr-1"></i> Submit Mandatory KYC
                                </button>
                                <a href="{{ url('admin/dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
