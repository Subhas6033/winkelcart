@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        <h2 class="mb-3">Contact Queries</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <strong>Customer Contact Queries</strong>
                <form method="GET" action="{{ route('admin.contact_queries.index') }}" class="form-inline mt-2 mt-md-0">
                    <label for="status" class="mr-2 mb-0">Status</label>
                    <select name="status" id="status" class="form-control mr-2">
                        <option value="">All</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Solved" {{ request('status') === 'Solved' ? 'selected' : '' }}>Solved</option>
                    </select>
                    <button type="submit" class="btn btn-primary mr-2">Apply</button>
                    <a href="{{ route('admin.contact_queries.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Business Name</th>
                                <th>Email</th>
                                <th>Contact Person</th>
                                <th>Address</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Submitted At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($queries as $query)
                            <tr>
                                <td>{{ $query->id }}</td>
                                <td>{{ $query->business_name }}</td>
                                <td>{{ $query->email }}</td>
                                <td>{{ $query->contact_person }}</td>
                                <td>{{ $query->address }}</td>
                                <td>{{ $query->state_type }}</td>
                                <td>{{ $query->country_type }}</td>
                                <td>
                                    @php $status = $query->status ?? 'Pending'; @endphp
                                    <span class="badge {{ $status === 'Solved' ? 'badge-success' : 'badge-warning' }}">{{ $status }}</span>
                                </td>
                                <td>{{ optional($query->created_at)->format('Y-m-d H:i') }}</td>
                                <td style="min-width: 220px;">
                                    <form method="POST" action="{{ route('admin.contact_queries.update_status', $query->id) }}" class="form-inline">
                                        @csrf
                                        <select name="status" class="form-control form-control-sm mr-2" style="min-width: 120px;">
                                            <option value="Pending" {{ ($query->status ?? 'Pending') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Solved" {{ ($query->status ?? 'Pending') === 'Solved' ? 'selected' : '' }}>Solved</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No contact queries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                {{ $queries->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
