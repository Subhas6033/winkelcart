@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Edit Setting</h2>
    <form method="POST" action="{{ route('admin.settings.update', $setting->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Key</label>
            <input type="text" class="form-control" value="{{ $setting->key }}" disabled>
        </div>
        <div class="form-group">
            <label>Value</label>
            <input type="text" name="value" class="form-control" value="{{ $setting->value }}">
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" value="{{ $setting->description }}" disabled>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
