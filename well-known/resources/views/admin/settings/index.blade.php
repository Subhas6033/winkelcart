@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Site Settings</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Key</th>
                <th>Value</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $setting)
            <tr>
                <td>{{ $setting->key }}</td>
                <td>{{ $setting->value }}</td>
                <td>{{ $setting->description }}</td>
                <td>
                    <a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
