@extends('front.layouts.app')

@section('content')
<div class="container py-4" style="max-width: 800px;">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="mb-3">Edit Profile</h4>
            <div class="mb-3 p-3 rounded-3" style="background: #f1f8e9; border: 1px solid #dcedc8;">
                <small class="text-muted d-block">Current Login Email</small>
                <strong>{{ $user->email }}</strong>
            </div>

            @foreach (['name', 'email', 'mobile', 'address', 'profile_image'] as $field)
                @error($field)
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            @endforeach

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    @if(optional($user->user_info)->profile_image)
                        <div class="mb-2">
                            <img src="{{ asset('uploads/profile/' . $user->user_info->profile_image) }}" alt="Profile Picture" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 1px solid #d9d9d9;">
                        </div>
                    @endif
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                    <small class="text-muted">Allowed: jpg, jpeg, png, webp (max 500 KB)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address (Used for login and order updates)</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Primary Address</label>
                    <textarea name="address" class="form-control" rows="4" placeholder="Enter your complete address">{{ old('address', optional($user->user_info)->address) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <a href="{{ route('profile_buyer') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
