@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <strong>Audit Logs</strong>
                    <form method="GET" action="{{ route('admin.audit_logs.index') }}" class="form-inline mt-2 mt-md-0">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search">
                        <select name="entity_type" class="form-control mr-2">
                            <option value="">All Entity</option>
                            @foreach($entityTypes as $entityType)
                                <option value="{{ $entityType }}" {{ request('entity_type') === $entityType ? 'selected' : '' }}>{{ $entityType }}</option>
                            @endforeach
                        </select>
                        <select name="action" class="form-control mr-2">
                            <option value="">All Action</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control mr-2">
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control mr-2">
                        <button type="submit" class="btn btn-secondary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>Before</th>
                                <th>After</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at ? $log->created_at->format('d M Y H:i:s') : '-' }}</td>
                                    <td>
                                        {{ $log->user->name ?? 'System' }}<br>
                                        <small>{{ $log->user->email ?? '-' }}</small>
                                    </td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->entity_type }} #{{ $log->entity_id }}</td>
                                    <td style="min-width: 200px;">
                                        @if($log->old_values)
                                            <details>
                                                <summary>View</summary>
                                                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </details>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="min-width: 200px;">
                                        @if($log->new_values)
                                            <details>
                                                <summary>View</summary>
                                                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </details>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $log->ip_address ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
