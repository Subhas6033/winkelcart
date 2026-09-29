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
                <h5 class="info-box-text mb-0"><b>Products</b></h5>
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
            <form action="{{ route('products.store') }}" for_mode="{{ $last_segment }}" id="product_form" name="product_form" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="pl-lg-4">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-name">Name <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_name"></span>
                      <input type="hidden" name="product_id" id="product_id" class="form-control" value="{{ isset($product->id)  ? $product->id : ''; }}">
                      <input type="text" name="name" class="form-control" value="{{ old('name', isset($product->name) ? $product->name : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-image">Product Image <span class="required-red">*</span></label>
                        <span class="validation-error" id="error_msg_image"></span>
                        <input type="file" name="image" id="image" class="form-control">
                        @if(isset($product->image) && $product->image != '')
                          <div class="mt-2">
                            <img src="{{ asset('uploads/products/'.$product->image) }}" 
                                alt="Product Image" width="100" class="img-thumbnail">
                          </div>
                        @endif
                      </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-name">Total Price <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_name"></span>
                      <input type="text" name="total_price" class="form-control" value="{{ old('total_price', isset($product->total_price) ? $product->total_price : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-name">Offer Price <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_name"></span>
                      <input type="text" name="offer_price" class="form-control" value="{{ old('offer_price', isset($product->offer_price) ? $product->offer_price : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-category">Category <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_category"></span>
                      <select name="category_id" id="category_id" class="form-control">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $id => $name)
                          <option value="{{ $id }}" 
                            {{ old('category_id', isset($product->category_id) ? $product->category_id : '') == $id ? 'selected' : '' }}>
                            {{ $name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-name">Company</label>
                      <span class="validation-error" id="error_msg_company"></span>
                      <input type="text" name="company" class="form-control" value="{{ old('company', isset($product->company) ? $product->company : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-desc">Description</label>
                      <span class="validation-error" id="error_msg_desc"></span>
                      <textarea name="desc" id="desc" class="form-control">{{ old('desc', isset($product->desc) ? $product->desc : '') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">Save</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
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