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
                <h5 class="info-box-text mb-0"><b>banks</b></h5>
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
            <form action="{{ route('banks.store') }}" for_mode="{{ $last_segment }}" id="bank_form" name="bank_form" method="POST">
              @csrf
              <div class="pl-lg-4">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-name">Name</label>
                      <span class="validation-error" id="error_msg_name"></span>
                      <input type="hidden" name="bank_id" id="bank_id" class="form-control" value="{{ isset($bank->id)  ? $bank->id : ''; }}">
                      <input type="text" name="name" class="form-control" value="{{ isset($bank->name)  ? $bank->name : ''; }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-desc">Description</label>
                      <span class="validation-error" id="error_msg_desc"></span>
                      <textarea name="desc" id="desc" class="form-control">{{ isset($bank->desc)  ? $bank->desc : ''; }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">Save</button>
                <a href="{{ route('banks.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
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
    $("#bank_form").submit(function() {
      //e.preventDefault();
      $('.validation-error').hide();
      var for_mode = $("#bank_form").attr('for_mode');
      var name = $('input[name="name"]').val();
      //var offer_description = CKEDITOR.instances.offer_description.getData();
      if ($.trim(name) == '') {
        $("#error_msg_name").show().text("bank Name is not set!");
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