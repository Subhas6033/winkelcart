@extends('front.layouts.app')

@section('content')
<div class="container py-4" style="max-width: 1100px;">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    @if(optional($user->user_info)->profile_image)
                        <img src="{{ asset('uploads/profile/' . $user->user_info->profile_image) }}" alt="Profile Picture" style="width: 80px; height: 80px; margin: 0 auto 12px; border-radius: 50%; object-fit: cover; border: 2px solid #e8f5e9; display: block;">
                    @else
                        <div style="width: 80px; height: 80px; margin: 0 auto 12px; border-radius: 50%; background: #e8f5e9; color: #2e7d32; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-success">Buyer Account</span>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-success btn-sm">Edit Profile</a>
                        <a href="{{ route('profile.addresses') }}" class="btn btn-outline-success btn-sm">Manage Address</a>
                        <a href="{{ route('my_orders') }}" class="btn btn-outline-primary btn-sm">My Orders</a>
                        <a href="{{ route('support.index') }}" class="btn btn-outline-secondary btn-sm">Support</a>
                        <a href="{{ route('user.bookings') }}" class="btn btn-outline-info btn-sm">My Bookings</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h4 class="mb-3">Profile Overview</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Name</label>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Email</label>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Mobile</label>
                            <div class="fw-semibold">{{ $user->mobile ?: 'Not set' }}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted mb-1">Primary Address</label>
                            <div class="fw-semibold">{{ optional($user->user_info)->address ?: 'No address added yet.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Quick Stats</h5>
                        <small class="text-muted">Member since {{ $user->created_at->format('d M Y') }}</small>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f1f8e9;">
                                <div class="text-muted">Total Orders</div>
                                <div class="h5 mb-0">{{ $orderCount }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #e3f2fd;">
                                <div class="text-muted">Items In Cart</div>
                                <div class="h5 mb-0">{{ $cartItems->sum('quantity') }}</div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-2">Recent Cart Items</h6>
                    @if($cartItems->isEmpty())
                        <p class="text-muted mb-0">No items in your cart.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($cartItems as $item)
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span>{{ optional($item->products)->name ?: 'Product' }}</span>
                                    <strong>x{{ $item->quantity }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Hotel Bookings Section -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><ion-icon name="bed-outline" style="vertical-align: middle; margin-right: 8px;"></ion-icon>Hotel Bookings</h5>
                        <span class="badge bg-info">{{ $bookingCount }} Total</span>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #fff3e0;">
                                <div class="text-muted">Pending</div>
                                <div class="h5 mb-0">{{ $bookings->where('status', 'pending')->count() }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #e8f5e9;">
                                <div class="text-muted">Confirmed</div>
                                <div class="h5 mb-0">{{ $bookings->where('status', 'confirmed')->count() }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: #e3f2fd;">
                                <div class="text-muted">Completed</div>
                                <div class="h5 mb-0">{{ $bookings->where('status', 'completed')->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-2">Recent Bookings</h6>
                    @if($bookings->isEmpty())
                        <p class="text-muted mb-0">No hotel bookings yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Booking Code</th>
                                        <th>Hotel</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td><a href="{{ route('booking.confirmation', $booking->booking_code) }}" class="text-primary fw-semibold">{{ $booking->booking_code }}</a></td>
                                        <td>{{ optional($booking->hotel)->name ?: 'N/A' }}</td>
                                        <td>{{ $booking->checkin->format('d M Y') }}</td>
                                        <td>{{ $booking->checkout->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status_badge }}">{{ ucfirst($booking->status) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-2">
                            <a href="{{ route('user.bookings') }}" class="btn btn-sm btn-outline-primary">View All Bookings</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
