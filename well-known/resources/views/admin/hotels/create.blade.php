@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Add New Hotel</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-links mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hotels.index') }}">Hotels</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Hotel</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.hotels.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-hotel mr-2"></i>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Hotel Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter hotel name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Property Type</label>
                                    <select name="property_type" class="form-control">
                                        <option value="hotel" {{ old('property_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                        <option value="resort" {{ old('property_type') == 'resort' ? 'selected' : '' }}>Resort</option>
                                        <option value="villa" {{ old('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                        <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                        <option value="homestay" {{ old('property_type') == 'homestay' ? 'selected' : '' }}>Homestay</option>
                                        <option value="guesthouse" {{ old('property_type') == 'guesthouse' ? 'selected' : '' }}>Guesthouse</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Location / Address <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" value="{{ old('location') }}" required placeholder="Enter full address">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="desc" class="form-control" rows="4" placeholder="Enter hotel description, highlights, nearby attractions...">{{ old('desc') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-phone-alt mr-2"></i>Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" required placeholder="+91 XXXXX XXXXX">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="hotel@example.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="url" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://www.example.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Google Maps Link</label>
                                    <input type="url" name="map_link" class="form-control" value="{{ old('map_link') }}" placeholder="https://maps.google.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-rupee-sign mr-2"></i>Pricing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minimum Price (per night)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" name="min_price" class="form-control" value="{{ old('min_price') }}" placeholder="1000" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Maximum Price (per night)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" name="max_price" class="form-control" value="{{ old('max_price') }}" placeholder="5000" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-concierge-bell mr-2"></i>Amenities & Facilities</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $commonAmenities = [
                                    'wifi' => ['icon' => 'fa-wifi', 'label' => 'Free WiFi'],
                                    'parking' => ['icon' => 'fa-parking', 'label' => 'Free Parking'],
                                    'pool' => ['icon' => 'fa-swimming-pool', 'label' => 'Swimming Pool'],
                                    'gym' => ['icon' => 'fa-dumbbell', 'label' => 'Gym/Fitness'],
                                    'spa' => ['icon' => 'fa-spa', 'label' => 'Spa & Wellness'],
                                    'restaurant' => ['icon' => 'fa-utensils', 'label' => 'Restaurant'],
                                    'bar' => ['icon' => 'fa-glass-martini-alt', 'label' => 'Bar/Lounge'],
                                    'room_service' => ['icon' => 'fa-concierge-bell', 'label' => '24/7 Room Service'],
                                    'ac' => ['icon' => 'fa-snowflake', 'label' => 'Air Conditioning'],
                                    'tv' => ['icon' => 'fa-tv', 'label' => 'Flat Screen TV'],
                                    'laundry' => ['icon' => 'fa-tshirt', 'label' => 'Laundry Service'],
                                    'airport_shuttle' => ['icon' => 'fa-shuttle-van', 'label' => 'Airport Shuttle'],
                                    'business_center' => ['icon' => 'fa-briefcase', 'label' => 'Business Center'],
                                    'conference_room' => ['icon' => 'fa-users', 'label' => 'Conference Room'],
                                    'pet_friendly' => ['icon' => 'fa-paw', 'label' => 'Pet Friendly'],
                                    'kids_area' => ['icon' => 'fa-child', 'label' => 'Kids Play Area'],
                                ];
                            @endphp
                            @foreach($commonAmenities as $key => $amenity)
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="amenity_{{ $key }}" name="amenities[]" value="{{ $key }}" {{ in_array($key, old('amenities', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="amenity_{{ $key }}">
                                            <i class="fas {{ $amenity['icon'] }} mr-1"></i> {{ $amenity['label'] }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Rating & Timing -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-star mr-2"></i>Rating & Timing</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Star Rating</label>
                            <select name="rating" class="form-control">
                                <option value="">Select Rating</option>
                                <option value="5.0" {{ old('rating') == '5.0' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Star</option>
                                <option value="4.0" {{ old('rating') == '4.0' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Star</option>
                                <option value="3.0" {{ old('rating') == '3.0' ? 'selected' : '' }}>⭐⭐⭐ 3 Star</option>
                                <option value="2.0" {{ old('rating') == '2.0' ? 'selected' : '' }}>⭐⭐ 2 Star</option>
                                <option value="1.0" {{ old('rating') == '1.0' ? 'selected' : '' }}>⭐ 1 Star</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Check-in Time</label>
                            <input type="time" name="checkin_time" class="form-control" value="{{ old('checkin_time', '14:00') }}">
                        </div>
                        <div class="form-group">
                            <label>Check-out Time</label>
                            <input type="time" name="checkout_time" class="form-control" value="{{ old('checkout_time', '11:00') }}">
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-images mr-2"></i>Hotel Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Main Image</label>
                            <input type="file" name="image" class="form-control-file" accept="image/*">
                            <small class="text-muted">Recommended: 800x600px, Max 2MB</small>
                        </div>
                        <div class="form-group">
                            <label>Gallery Images</label>
                            <input type="file" name="gallery[]" class="form-control-file" accept="image/*" multiple>
                            <small class="text-muted">You can select multiple images</small>
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags mr-2"></i>Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Hotel Tags</label>
                            <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" placeholder="beach, luxury, family-friendly">
                            <small class="text-muted">Separate tags with commas</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-save mr-2"></i>Save Hotel
                        </button>
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection
