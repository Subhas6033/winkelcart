@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <strong><i class="fas fa-bell mr-2"></i>All Notifications</strong>
                </div>
                <div class="d-flex flex-wrap mt-2 mt-md-0">
                    <form method="GET" action="{{ route('admin.notifications.index') }}" class="form-inline mr-2 mb-2">
                        <select name="type" class="form-control form-control-sm mr-2">
                            <option value="">All Types</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="form-control form-control-sm mr-2">
                            <option value="">All Status</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                    </form>
                    <form method="POST" action="{{ route('admin.notifications.mark_all_read') }}" class="d-inline mr-2 mb-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-check-double mr-1"></i>Mark All Read
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.notifications.clear_read') }}" class="d-inline mb-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete all read notifications?')">
                            <i class="fas fa-trash mr-1"></i>Clear Read
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item {{ $notification->is_read ? '' : 'bg-light' }}">
                            <div class="d-flex align-items-start">
                                <div class="mr-3">
                                    <span class="badge badge-{{ $notification->icon_color }} p-2" style="font-size: 16px;">
                                        <i class="{{ $notification->icon }}"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 {{ $notification->is_read ? 'text-muted' : 'font-weight-bold' }}">
                                                {{ $notification->title }}
                                                @if(!$notification->is_read)
                                                    <span class="badge badge-primary badge-sm ml-1">New</span>
                                                @endif
                                            </h6>
                                            <p class="mb-1 text-muted" style="font-size: 14px;">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                @if($notification->read_at)
                                                    <span class="ml-2"><i class="fas fa-eye mr-1"></i>Read {{ $notification->read_at->diffForHumans() }}</span>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="ml-3 d-flex">
                                            @if($notification->link)
                                                <a href="{{ route('admin.notifications.mark_read', $notification->id) }}" class="btn btn-sm btn-outline-primary mr-1" title="View">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                            @if(!$notification->is_read)
                                                <a href="{{ route('admin.notifications.mark_read', $notification->id) }}" class="btn btn-sm btn-outline-success mr-1" title="Mark as Read">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @endif
                                            <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this notification?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No notifications found</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
