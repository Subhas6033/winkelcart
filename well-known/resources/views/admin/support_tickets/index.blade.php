@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        @if (session('success'))
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

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <strong>Customer Support Tickets</strong>
                <form method="GET" action="{{ route('admin.support_tickets.index') }}" class="form-inline mt-2 mt-md-0">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search by subject/email/order">
                    <select name="status" class="form-control mr-2">
                        <option value="">All Status</option>
                        @foreach(['Open', 'In Progress', 'Resolved', 'Closed'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Order Number</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Closing Date</th>
                                <th>Admin Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                @php
                                    $statusClass = 'badge-secondary';
                                    if ($ticket->status === 'Open') $statusClass = 'badge-warning';
                                    if ($ticket->status === 'In Progress') $statusClass = 'badge-info';
                                    if ($ticket->status === 'Resolved') $statusClass = 'badge-success';
                                    if ($ticket->status === 'Closed') $statusClass = 'badge-dark';
                                @endphp
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->name }}</td>
                                    <td>{{ $ticket->email }}</td>
                                    <td>{{ $ticket->order_number ?: 'N/A' }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td style="max-width: 280px; white-space: pre-wrap;">{{ $ticket->message }}</td>
                                    <td><span class="badge {{ $statusClass }}">{{ $ticket->status }}</span></td>
                                    <td>{{ $ticket->created_at ? $ticket->created_at->format('d M Y H:i') : '-' }}</td>
                                    <td>
                                        @if ($ticket->resolved_at)
                                            {{ (\Carbon\Carbon::hasFormat($ticket->resolved_at, 'Y-m-d H:i:s') ? \Carbon\Carbon::parse($ticket->resolved_at)->format('d M Y H:i') : $ticket->resolved_at) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $ticket->admin_note ?: '-' }}</td>
                                    <td style="min-width: 280px;">
                                        <form action="{{ route('admin.support_tickets.update_status', $ticket->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-2">
                                                <select name="status" class="form-control form-control-sm ticket-status-select" required>
                                                    @foreach(['Open', 'In Progress', 'Resolved', 'Closed'] as $status)
                                                        <option value="{{ $status }}" {{ $ticket->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="admin_note" class="form-control form-control-sm admin-note-input" value="{{ $ticket->admin_note }}" placeholder="Admin note (required for Close)">
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                @push('scripts')
                                <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    document.querySelectorAll('form[action*="support_tickets/update_status"]').forEach(function(form) {
                                        var statusSelect = form.querySelector('.ticket-status-select');
                                        var noteInput = form.querySelector('.admin-note-input');
                                        form.addEventListener('submit', function(e) {
                                            if (statusSelect.value === 'Closed' && (!noteInput.value || noteInput.value.trim() === '')) {
                                                e.preventDefault();
                                                alert('Admin note is required when closing a ticket.');
                                                noteInput.focus();
                                            }
                                        });
                                    });
                                });
                                </script>
                                @endpush
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">No support tickets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                {{ $tickets->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
