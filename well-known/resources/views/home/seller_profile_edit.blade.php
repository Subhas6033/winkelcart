@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-user-edit mr-2"></i>Edit Seller Profile</h5>
                        <a href="{{ url('admin/dashboard') }}" class="btn btn-light btn-sm">Back to Dashboard</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @foreach (['name', 'email', 'mobile', 'address'] as $field)
                            @error($field)
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        @endforeach

                        <form action="{{ route('seller.profile.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Primary Address</label>
                                <textarea name="address" class="form-control" rows="4" placeholder="Enter your complete address">{{ old('address', optional($user->user_info)->address) }}</textarea>
                            </div>

                            <div class="d-flex align-items-center">
                                <button type="submit" class="btn btn-success mr-2">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                                <a href="{{ url('admin/dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
