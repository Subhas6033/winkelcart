@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <strong>CMS Pages Manager</strong>
                <div class="d-flex mt-2 mt-md-0">
                    <form method="GET" action="{{ route('admin.cms_pages.index') }}" class="form-inline mr-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search title/slug">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>
                    <a href="{{ route('admin.cms_pages.create') }}" class="btn btn-primary">Add New Page</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                                <tr>
                                    <td>{{ $page->id }}</td>
                                    <td>{{ $page->title }}</td>
                                    <td><code>{{ $page->slug }}</code></td>
                                    <td>
                                        @if($page->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $page->updated_at ? $page->updated_at->format('d M Y H:i') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.cms_pages.edit', $page->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form method="POST" action="{{ route('admin.cms_pages.destroy', $page->id) }}" style="display:inline-block;" onsubmit="return confirm('Delete this page?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No CMS pages found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $pages->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
