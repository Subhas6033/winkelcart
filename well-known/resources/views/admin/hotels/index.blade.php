@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Hotel Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-links mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hotels</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Hotel
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0">All Hotels</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Rating</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($hotels as $hotel)
                            <tr>
                                <td>{{ $hotel->id }}</td>
                                <td><strong>{{ $hotel->name }}</strong></td>
                                <td>{{ $hotel->location }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas fa-star text-warning"></i> {{ $hotel->rating }}
                                    </span>
                                </td>
                                <td>{{ $hotel->contact }}</td>
                                <td>
                                    <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this hotel?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-hotel fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No hotels found. Click "Add Hotel" to create one.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
