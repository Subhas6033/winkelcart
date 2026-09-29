@extends('layouts.app')

@section('content')
<?php
$last_segment = request()->segment(count(request()->segments()));
?>
<div class="content-wrapper">
  <div class="container-fluid ">
    <div class="row">
      <div class="col-xl-12  mt-3 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12">
                <h5 class="info-box-text mb-0"><b>Category</b></h5>
              </div>
            </div>
          </div>
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <div class="card-body">
            <form action="{{ route('categories.store') }}" for_mode="{{ $last_segment }}" id="category_form" name="category_form" method="POST">
              @csrf
              <div class="pl-lg-4">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-name">Name <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_name"></span>
                      <input type="hidden" name="category_id" id="category_id" class="form-control" value="{{ isset($category->id)  ? $category->id : ''; }}">
                      <input type="text" name="name" class="form-control" value="{{ isset($category->name)  ? $category->name : ''; }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-desc">Description</label>
                      <span class="validation-error" id="error_msg_desc"></span>
                      <textarea name="desc" id="desc" class="form-control">{{ isset($category->desc)  ? $category->desc : ''; }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">Save</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
              </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@push('js')
<script type="text/javascript">
  $(document).ready(function() {
    $("#product_form").submit(function() {
      //e.preventDefault();
      $('.validation-error').hide();
      var for_mode = $("#product_form").attr('for_mode');
      var name = $('input[name="name"]').val();
      //var offer_description = CKEDITOR.instances.offer_description.getData();
      if ($.trim(name) == '') {
        $("#error_msg_name").show().text("product Name is not set!");
        return false;
      } else {
        $('#roles').prop('disabled', false);
        $(this).find("button[type='submit']").prop('disabled', true);
        return true;
      }

    });
  });
</script>
@endpush