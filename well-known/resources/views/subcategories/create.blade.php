@extends('layouts.app')

@section('content')
<?php $last_segment = request()->segment(count(request()->segments())); ?>
<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-8 offset-xl-2 mt-4">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0"><b>{{ isset($subCategory) ? 'Edit Sub-category' : 'Add Sub-category' }}</b></h5>
          </div>

          @if ($errors->any())
          <div class="alert alert-danger m-3">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <div class="card-body">
            <form action="{{ route('sub_categories.store') }}" method="POST" id="sub_cat_form">
              @csrf
              <input type="hidden" name="sub_category_id" value="{{ isset($subCategory->id) ? $subCategory->id : '' }}">

              <div class="form-group">
                <label class="form-control-label">Main Category <span class="required-red">*</span></label>
                <select name="category_id" class="form-control" required>
                  <option value="">-- Select Main Category --</option>
                  @foreach(\App\Models\SubCategory::MAIN_CATEGORIES as $id => $name)
                    <option value="{{ $id }}"
                      {{ (old('category_id', $selectedCategoryId) == $id) ? 'selected' : '' }}>
                      {{ $name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label class="form-control-label">Sub-category Name <span class="required-red">*</span></label>
                <input type="text" name="name" class="form-control"
                  value="{{ old('name', isset($subCategory->name) ? $subCategory->name : '') }}"
                  placeholder="e.g. Smartphones, LED TVs, T-Shirts ..." required>
              </div>

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Save</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
