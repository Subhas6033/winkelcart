@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Booking Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-links mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bookings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">All Bookings</h3>
                        </div>
                    </div>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.bookings.index') }}" class="mt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="hotel_id" class="form-control form-control-sm">
                                    <option value="">All Hotels</option>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="from_date" class="form-control form-control-sm" placeholder="From Date" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="to_date" class="form-control form-control-sm" placeholder="To Date" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search code/user..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mx-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mx-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>Booking Code</th>
                                <th>User</th>
                                <th>Hotel / Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Guests</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold text-primary">{{ $booking->booking_code }}</span>
                                    </td>
                                    <td>
                                        <div>{{ $booking->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $booking->hotel->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $booking->room->room_type ?? '' }}</small>
                                    </td>
                                    <td>{{ $booking->checkin ? $booking->checkin->format('d M Y') : 'N/A' }}</td>
                                    <td>{{ $booking->checkout ? $booking->checkout->format('d M Y') : 'N/A' }}</td>
                                    <td>{{ $booking->guests }}</td>
                                    <td><strong>₹{{ number_format($booking->total_price, 2) }}</strong></td>
                                    <td>
                                        @if($booking->razorpay_payment_id)
                                            <div><span class="badge badge-info"><i class="fas fa-credit-card"></i> Razorpay</span></div>
                                            <small class="badge badge-{{ ($booking->payment_status ?? '') == 'Paid' ? 'success' : 'warning' }}">
                                                {{ $booking->payment_status ?? 'Pending' }}
                                            </small>
                                            <br><small class="text-muted"><code>{{ $booking->razorpay_payment_id }}</code></small>
                                        @elseif($booking->payment)
                                            <div>{{ $booking->payment->payment_method ?? 'N/A' }}</div>
                                            <small class="badge badge-{{ $booking->payment->payment_status == 'Paid' ? 'success' : 'warning' }}">
                                                {{ $booking->payment->payment_status ?? 'N/A' }}
                                            </small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'completed' => 'info',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusColor = $statusColors[strtolower($booking->status)] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $statusColor }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.bookings.show', $booking->id) }}">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                                @if(strtolower($booking->status) === 'pending')
                                                    <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-check"></i> Confirm
                                                        </button>
                                                    </form>
                                                @endif
                                                @if(strtolower($booking->status) === 'confirmed')
                                                    <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-info">
                                                            <i class="fas fa-check-double"></i> Mark Complete
                                                        </button>
                                                    </form>
                                                @endif
                                                @if(in_array(strtolower($booking->status), ['pending', 'confirmed']))
                                                    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-times"></i> Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No bookings found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
