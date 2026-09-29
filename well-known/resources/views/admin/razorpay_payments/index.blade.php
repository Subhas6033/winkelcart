@extends('layouts.app')

@section('content')
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Razorpay Payments</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Razorpay Payments</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-neutral">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <!-- Summary Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Transactions</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $totalCount }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Paid</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $paidCount }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Revenue</h5>
                            <span class="h2 font-weight-bold mb-0">₹{{ number_format($paidAmount / 100, 2) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                <i class="fas fa-rupee-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Pending</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $pendingCount }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.razorpay_payments.index') }}" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-control-label small">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Order ID, Payment ID, User..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-control-label small">Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="created" {{ request('status') == 'created' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-control-label small">Type</label>
                    <select name="type" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>Order</option>
                        <option value="booking" {{ request('type') == 'booking' ? 'selected' : '' }}>Booking</option>
                        <option value="seller_membership" {{ request('type') == 'seller_membership' ? 'selected' : '' }}>Seller Membership</option>
                        <option value="cart" {{ request('type') == 'cart' ? 'selected' : '' }}>Cart</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.razorpay_payments.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header border-0">
            <h3 class="mb-0">All Razorpay Transactions</h3>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Razorpay Order ID</th>
                        <th>Payment ID</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
                            <td><code class="small">{{ $payment->razorpay_order_id }}</code></td>
                            <td>
                                @if($payment->razorpay_payment_id)
                                    <code class="small">{{ $payment->razorpay_payment_id }}</code>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ optional($payment->user)->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ optional($payment->user)->email ?? '' }}</small>
                            </td>
                            <td>
                                @php
                                    $typeBadges = [
                                        'order' => 'badge-primary',
                                        'booking' => 'badge-info',
                                        'seller_membership' => 'badge-purple',
                                    ];
                                    $badge = $typeBadges[$payment->type] ?? 'badge-light';
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst(str_replace('_', ' ', $payment->type ?? 'N/A')) }}</span>
                                @if($payment->reference_id)
                                    <br><small class="text-muted">Ref: {{ $payment->reference_id }}</small>
                                @endif
                            </td>
                            <td><strong>₹{{ number_format($payment->amount / 100, 2) }}</strong></td>
                            <td>{{ strtoupper($payment->currency ?? 'INR') }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'paid' => 'success',
                                        'created' => 'warning',
                                        'failed' => 'danger',
                                        'refunded' => 'info',
                                    ];
                                    $statusColor = $statusColors[$payment->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $statusColor }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td><small>{{ optional($payment->created_at)->format('d M Y, h:i A') }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-credit-card fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                No Razorpay transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="card-footer py-3">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
