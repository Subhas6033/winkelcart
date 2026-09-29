@extends('layouts.app')

@section('content')
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Booking Details</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $booking->booking_code }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-neutral">
                        <i class="fas fa-arrow-left"></i> Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-8">
            <!-- Booking Information -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Booking Information</h3>
                        </div>
                        <div class="col text-right">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'success',
                                    'completed' => 'info',
                                    'cancelled' => 'danger',
                                ];
                                $statusColor = $statusColors[strtolower($booking->status)] ?? 'secondary';
                            @endphp
                            <span class="badge badge-lg badge-{{ $statusColor }}">{{ ucfirst($booking->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-2">Booking Code</h5>
                            <h3 class="text-primary">{{ $booking->booking_code }}</h3>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <h5 class="text-muted mb-2">Booked On</h5>
                            <p class="mb-0">{{ $booking->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-uppercase text-muted mb-3">Hotel Details</h5>
                            <p class="mb-1"><strong>Hotel:</strong> {{ $booking->hotel->name ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Location:</strong> {{ $booking->hotel->location ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Room Type:</strong> {{ $booking->room->room_type ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Max Guests:</strong> {{ $booking->room->max_guests ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-uppercase text-muted mb-3">Stay Details</h5>
                            <p class="mb-1"><strong>Check-in:</strong> {{ $booking->checkin ? $booking->checkin->format('D, d M Y') : 'N/A' }}</p>
                            <p class="mb-1"><strong>Check-out:</strong> {{ $booking->checkout ? $booking->checkout->format('D, d M Y') : 'N/A' }}</p>
                            <p class="mb-1"><strong>Nights:</strong> {{ $booking->nights }} Night{{ $booking->nights > 1 ? 's' : '' }}</p>
                            <p class="mb-1"><strong>Guests:</strong> {{ $booking->guests }} Guest{{ $booking->guests > 1 ? 's' : '' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-uppercase text-muted mb-3">Customer Details</h5>
                            <p class="mb-1"><strong>Name:</strong> {{ $booking->user->name ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $booking->user->email ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $booking->user->mobile ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-uppercase text-muted mb-3">Payment Details</h5>
                            @if($booking->razorpay_payment_id)
                                <p class="mb-1"><strong>Method:</strong> <span class="badge badge-info">Razorpay</span></p>
                                <p class="mb-1">
                                    <strong>Status:</strong>
                                    <span class="badge badge-{{ ($booking->payment_status ?? '') == 'Paid' ? 'success' : 'warning' }}">
                                        {{ $booking->payment_status ?? 'Pending' }}
                                    </span>
                                </p>
                                <p class="mb-1"><strong>Payment ID:</strong> <code>{{ $booking->razorpay_payment_id }}</code></p>
                                @if($booking->razorpay_order_id)
                                    <p class="mb-1"><strong>Order ID:</strong> <code>{{ $booking->razorpay_order_id }}</code></p>
                                @endif
                            @else
                                <p class="mb-1"><strong>Method:</strong> {{ $booking->payment->payment_method ?? 'N/A' }}</p>
                                <p class="mb-1">
                                    <strong>Status:</strong> 
                                    <span class="badge badge-{{ $booking->payment && $booking->payment->payment_status == 'Paid' ? 'success' : 'warning' }}">
                                        {{ $booking->payment->payment_status ?? 'N/A' }}
                                    </span>
                                </p>
                                @if($booking->payment && $booking->payment->reference_id)
                                    <p class="mb-1"><strong>Reference:</strong> {{ $booking->payment->reference_id }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Price Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Price Summary</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Room Price (per night)</span>
                        <span>₹{{ number_format($booking->room->price ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Number of Nights</span>
                        <span>x {{ $booking->nights }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Amount</strong>
                        <strong class="text-primary h3">₹{{ number_format($booking->total_price, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Actions</h3>
                </div>
                <div class="card-body">
                    @if(strtolower($booking->status) === 'pending')
                        <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Confirm Booking
                            </button>
                        </form>
                    @endif

                    @if(strtolower($booking->status) === 'confirmed')
                        <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-check-double"></i> Mark as Completed
                            </button>
                        </form>
                    @endif

                    @if(in_array(strtolower($booking->status), ['pending', 'confirmed']))
                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times"></i> Cancel Booking
                            </button>
                        </form>
                    @endif

                    @if(strtolower($booking->status) === 'cancelled')
                        <div class="alert alert-danger text-center mb-0">
                            <i class="fas fa-ban"></i> This booking has been cancelled.
                        </div>
                    @endif

                    @if(strtolower($booking->status) === 'completed')
                        <div class="alert alert-info text-center mb-0">
                            <i class="fas fa-check-circle"></i> This booking is completed.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-one-side">
                        <div class="timeline-block">
                            <span class="timeline-step badge-success">
                                <i class="fas fa-plus"></i>
                            </span>
                            <div class="timeline-content">
                                <small class="text-muted font-weight-bold">Booking Created</small>
                                <p class="text-sm mt-1 mb-0">{{ $booking->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        @if($booking->updated_at != $booking->created_at)
                            <div class="timeline-block">
                                <span class="timeline-step badge-info">
                                    <i class="fas fa-sync"></i>
                                </span>
                                <div class="timeline-content">
                                    <small class="text-muted font-weight-bold">Last Updated</small>
                                    <p class="text-sm mt-1 mb-0">{{ $booking->updated_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
