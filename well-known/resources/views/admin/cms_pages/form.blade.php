@extends('layouts.app')

@section('content')
@php
    $isEdit = !empty($page->id);
@endphp
<div class="content-wrapper">
    <div class="container-fluid py-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="cms-page-meta" data-is-edit="{{ $isEdit ? '1' : '0' }}" style="display: none;"></div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ $isEdit ? 'Edit CMS Page' : 'Create CMS Page' }}</strong>
                <a href="{{ route('admin.cms_pages.index') }}" class="btn btn-sm btn-secondary">Back</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $isEdit ? route('admin.cms_pages.update', $page->id) : route('admin.cms_pages.store') }}">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" required>
                            <small class="text-muted">Use lowercase and hyphens, e.g. <code>privacy-policy</code>.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea id="content-editor" name="content" class="form-control" rows="14">{{ old('content', $page->content) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meta Title (optional)</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meta Description (optional)</label>
                            <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description) }}">
                        </div>
                    </div>

                    <div class="form-group form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $isEdit ? $page->is_active : true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Page is active</label>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Page' : 'Create Page' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var metaNode = document.getElementById('cms-page-meta');
        var isEdit = metaNode && metaNode.dataset.isEdit === '1';
        var titleInput = document.getElementById('title');
        var slugInput = document.getElementById('slug');

        if (!isEdit && titleInput && slugInput) {
            titleInput.addEventListener('input', function () {
                var slug = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slugInput.value = slug;
            });
        }

        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('content-editor');
        }
    });
</script>
@endpush
