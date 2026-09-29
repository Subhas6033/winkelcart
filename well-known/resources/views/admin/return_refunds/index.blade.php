@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header"><strong>Create Return/Refund Request</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.return_refunds.store') }}" class="row">
                    @csrf
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="user_id" class="form-control">
                            <option value="">Select Customer (optional)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Order Number</label>
                        <input type="text" name="order_number" class="form-control" value="{{ old('order_number') }}" placeholder="Optional">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Request Type</label>
                        <select name="request_type" class="form-control" required>
                            @foreach(['Return', 'Refund'] as $type)
                                <option value="{{ $type }}" {{ old('request_type', 'Return') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Refund Amount (optional)</label>
                        <input type="number" step="0.01" min="0" name="refund_amount" class="form-control" value="{{ old('refund_amount') }}">
                    </div>
                    <div class="col-md-10 mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="2" required>{{ old('reason') }}</textarea>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Create</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <strong>Return/Refund Requests</strong>
                    <form method="GET" action="{{ route('admin.return_refunds.index') }}" class="form-inline mt-2 mt-md-0">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search">
                        <select name="type" class="form-control mr-2">
                            <option value="">All Types</option>
                            @foreach(['Return', 'Refund'] as $type)
                                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="form-control mr-2">
                            <option value="">All Status</option>
                            @foreach(['Pending', 'Under Review', 'Approved', 'Rejected', 'Completed'] as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Order</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Refund Amount</th>
                                <th>Requested</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $item)
                                @php
                                    $statusClass = 'badge-secondary';
                                    if (in_array($item->status, ['Approved', 'Completed'])) $statusClass = 'badge-success';
                                    if (in_array($item->status, ['Rejected'])) $statusClass = 'badge-danger';
                                    if (in_array($item->status, ['Under Review'])) $statusClass = 'badge-warning';
                                @endphp
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        {{ $item->user->name ?? 'N/A' }}<br>
                                        <small>{{ $item->user->email ?? '-' }}</small>
                                    </td>
                                    <td>{{ $item->order_number ?? '-' }}</td>
                                    <td>{{ $item->request_type }}</td>
                                    <td style="max-width: 260px; white-space: pre-wrap;">{{ $item->reason }}</td>
                                    <td><span class="badge {{ $statusClass }}">{{ $item->status }}</span></td>
                                    <td>{{ $item->refund_amount !== null ? 'Rs. ' . number_format((float)$item->refund_amount, 2) : '-' }}</td>
                                    <td>{{ $item->requested_at ? $item->requested_at->format('d M Y H:i') : ($item->created_at ? $item->created_at->format('d M Y H:i') : '-') }}</td>
                                    <td style="min-width: 290px;">
                                        <form method="POST" action="{{ route('admin.return_refunds.update_status', $item->id) }}">
                                            @csrf
                                            <div class="mb-2">
                                                <select name="status" class="form-control form-control-sm" required>
                                                    @foreach(['Pending', 'Under Review', 'Approved', 'Rejected', 'Completed'] as $status)
                                                        <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="refund_amount" value="{{ $item->refund_amount }}" placeholder="Refund amount">
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="admin_note" class="form-control form-control-sm" value="{{ $item->admin_note }}" placeholder="Admin note">
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">No return/refund requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $requests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
