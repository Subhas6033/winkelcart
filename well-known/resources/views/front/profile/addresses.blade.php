@extends('front.layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-3">📍 Manage Delivery Address</h4>
            <p class="text-muted">Update your primary delivery address for faster checkout and accurate delivery.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (optional($user->user_info)->address && !optional($user->user_info)->address_line_1)
                <div class="alert alert-warning mb-3">
                    <strong>⚠️ Please update your address!</strong>
                    <p class="mb-1">Your current saved address: <strong>{{ $user->user_info->address }}</strong></p>
                    <p class="mb-0">Please fill in the detailed address fields below for accurate delivery.</p>
                </div>
            @endif

            <form action="{{ route('profile.addresses.update') }}" method="POST">                @csrf
                
                <div class="row g-3">
                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" 
                               value="{{ old('full_name', optional($user->user_info)->full_name ?? $user->name) }}" 
                               placeholder="Enter recipient's full name" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" 
                               value="{{ old('phone', optional($user->user_info)->phone ?? $user->mobile) }}" 
                               placeholder="10-digit mobile number" required>
                    </div>

                    <!-- Address Line 1 -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" name="address_line_1" class="form-control" 
                               value="{{ old('address_line_1', optional($user->user_info)->address_line_1) }}" 
                               placeholder="House No, Building, Street Name" required>
                    </div>

                    <!-- Address Line 2 -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Address Line 2 <span class="text-muted">(Optional)</span></label>
                        <input type="text" name="address_line_2" class="form-control" 
                               value="{{ old('address_line_2', optional($user->user_info)->address_line_2) }}" 
                               placeholder="Apartment, Suite, Floor, etc.">
                    </div>

                    <!-- Landmark -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Landmark <span class="text-muted">(Optional)</span></label>
                        <input type="text" name="landmark" class="form-control" 
                               value="{{ old('landmark', optional($user->user_info)->landmark) }}" 
                               placeholder="Near school, hospital, etc.">
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">City / Town <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" 
                               value="{{ old('city', optional($user->user_info)->city) }}" 
                               placeholder="Enter city or town" required>
                    </div>

                    <!-- State -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                        <select name="state" class="form-select" required>
                            <option value="">Select State</option>
                            @php
                                $states = ['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli', 'Daman and Diu', 'Delhi', 'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry'];
                            @endphp
                            @foreach($states as $state)
                                <option value="{{ $state }}" {{ old('state', optional($user->user_info)->state) == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pincode -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pincode <span class="text-danger">*</span></label>
                        <input type="text" name="pincode" class="form-control" 
                               value="{{ old('pincode', optional($user->user_info)->pincode) }}" 
                               placeholder="6-digit pincode" maxlength="6" pattern="[0-9]{6}" required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> Save Address
                    </button>
                    <a href="{{ route('profile_buyer') }}" class="btn btn-outline-secondary">Back To Profile</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
