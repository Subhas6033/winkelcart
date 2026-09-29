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
                  <h5 class="info-box-text mb-0"><b>Users</b></h5>
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
              <form action="{{ route('users.store') }}" for_mode="{{ $last_segment }}" id="user_form" name="user_form" method="POST">
                @csrf
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-name">Name</label>
                        <span class="validation-error" id="error_msg_name"></span>
                        <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{ isset($user->id)  ? $user->id : ''; }}">
                        <input type="text" name="name" class="form-control" value="{{ isset($user->name)  ? $user->name : ''; }}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email</label>
                        <span class="validation-error" id="error_msg_email"></span>
                        <input type="text" name="email" class="form-control" value="{{ isset($user->email)  ? $user->email : ''; }}">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-password">Password</label>
                        <span class="validation-error" id="error_msg_password"></span>
                        <input type="password" name="password" class="form-control" placeholder="{{ isset($user->password)  ? '********' : ''; }}" autocomplete="off">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-cpassword">Confirm Password</label>
                        <span class="validation-error" id="error_msg_cpassword"></span>
                        <input type="password" name="confirm-password" class="form-control" placeholder="{{ isset($user->password)  ? '********' : ''; }}">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-mobile">Mobile</label>
                        <span class="validation-error" id="error_msg_mobile"></span>                        
                        <input type="text" name="mobile" class="form-control" value="{{ isset($user->mobile)  ? $user->mobile : ''; }}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-roles">Role</label>
                        <span class="validation-error" id="error_msg_roles"></span>
                        @if(!empty($userRole))
                        {!! Form::select('roles', $roles,$userRole, array('class' => 'form-control','','id'=>'roles')) !!}
                        @else
                        {!! Form::select('roles', $roles,[], array('class' => 'form-control','','id'=>'roles')) !!}
                        @endif
                      </div>
                    </div>
                  </div>
                </div>



                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4">Save</button>
                  <a href="{{ route('users.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
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
    $("#user_form").submit(function() {
      //e.preventDefault();
      $('.validation-error').hide();
      var for_mode = $("#user_form").attr('for_mode');
      var name = $('input[name="name"]').val();
      var email = $('input[name="email"]').val();
      var password = $('input[name="password"]').val();
      var confirm_password = $('input[name="confirm-password"]').val();
      var roles = $('select[name="roles"]').val();
      //var offer_description = CKEDITOR.instances.offer_description.getData();
      if ($.trim(name) == '') {
        $("#error_msg_name").show().text("User Name is not set!");
        return false;
      } else if ($.trim(email) == '') {
        $("#error_msg_email").show().text("Email is not set!");
        return false;
      } else if (for_mode == 'create' && password == '') {
        $("#error_msg_password").show().text("Password is not set!");
        return false;
      } else if (for_mode == 'create' && confirm_password == '') {
        $("#error_msg_cpassword").show().text("Confirm password is not set!");
        return false;
      } else if (password != '' && confirm_password != '' && confirm_password != password) {
        $("#error_msg_cpassword").show().text("Passwords does not match");
        return false;
      } else if (roles == '') {
        $("#error_msg_roles").show().text("Roles is not set!");
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