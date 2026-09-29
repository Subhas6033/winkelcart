@extends('layouts.app')

@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row  mt-5">
      <div class="col-xl-12  mt-3">
        @if(Session::has('success'))
        <div>
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
        </div>
        @endif
        <div class="card">
          <div class="card-header border-0">
            <div class="row align-items-center mb-2">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Users</b></h5>
              </div>
            </div>
            <form method="GET" action="{{ route('users.index') }}" id="user-filter-form">
              <div class="form-row align-items-end">
                {{-- Keyword search --}}
                <div class="form-group col-md-3 mb-2">
                  <label class="small mb-1">Search</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control" name="search" type="text"
                      placeholder="Name, email or mobile"
                      value="{{ $search ?? '' }}">
                  </div>
                </div>

                {{-- Role filter --}}
                <div class="form-group col-md-2 mb-2">
                  <label class="small mb-1">Role</label>
                  <select name="role" class="form-control form-control-sm">
                    <option value="">All Roles</option>
                    @foreach($roles as $roleName)
                      <option value="{{ $roleName }}" {{ ($roleFilter ?? '') === $roleName ? 'selected' : '' }}>{{ $roleName }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Account status --}}
                <div class="form-group col-md-2 mb-2">
                  <label class="small mb-1">Account Status</label>
                  <select name="status" class="form-control form-control-sm">
                    <option value="">All Status</option>
                    <option value="0" {{ isset($status) && $status === '0' ? 'selected' : '' }}>Active</option>
                    <option value="1" {{ isset($status) && $status === '1' ? 'selected' : '' }}>Inactive</option>
                  </select>
                </div>

                {{-- Seller approved --}}
                <div class="form-group col-md-2 mb-2">
                  <label class="small mb-1">Seller Approved</label>
                  <select name="approved" class="form-control form-control-sm">
                    <option value="">All</option>
                    <option value="1" {{ isset($approved) && $approved === '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ isset($approved) && $approved === '0' ? 'selected' : '' }}>Not Approved</option>
                  </select>
                </div>

                {{-- Buttons --}}
                <div class="form-group col-md-2 mb-2 d-flex align-items-end" style="gap:4px;">
                  <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                  <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                </div>
              </div>
            </form>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Sl No</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Mobile</th>
                  <th scope="col">GST no</th>
                  <th scope="col">Roles</th>
                  <th scope="col">Is Approved</th>
                  <th scope="col">Status</th>
                  <th scope="col" align="right">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty(@$data))
                @foreach ($data as $key => $user)
                  @if($user->id != 845)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->user_info->gst_no ?? '' }}</td>
                    <td>
                      @if(!empty($user->getRoleNames()))
                      @foreach($user->getRoleNames() as $v)
                      <label class="badge badge-success">{{ $v }}</label>
                      @endforeach
                      @endif
                    </td>
                    <td id="td_approval{{ $user->id }}">
                      @if(!empty($user->getRoleNames()))
                      @foreach($user->getRoleNames() as $v)
                        @if($v == 'Seller')
                        <?php
                        if ($user->is_seller == '0') {
                          echo '<a class="btn btn-sm btn-danger sellerApproval" data-fieldid="' . $user->id . '" href="javascript:void(0);">No</a>';
                        } else {
                          echo '<a class="btn btn-sm btn-primary sellerApproval" data-fieldid="' . $user->id . '" href="javascript:void(0);">Yes</a>';
                        }
                        ?>                      
                        @endif
                      @endforeach
                      @endif                    
                    </td>
                    <td id="td_status{{ $user->id }}">
                      @can('user-edit')
                      <?php
                      if ($user->status == '0') {
                        echo '<a class="btn btn-sm btn-primary statusChange" data-fieldid="' . $user->id . '" href="javascript:void(0);">Active</a>';
                      } else {
                        echo '<a class="btn btn-sm btn-danger statusChange" data-fieldid="' . $user->id . '" href="javascript:void(0);">Inactive</a>';
                      }
                      ?>
                      @endcan
                      @if(!Gate::check('user-edit'))
                      {{ ($user->status==0)?"Active":"Inactive"; }}
                      @endif
                    </td>
                    <td class="icons">
                      @can('user-edit')
                      <a class="btn btn-sm btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                      @endcan

                      @can('user-delete')
                      {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                      {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger delete_row']) !!}
                      {!! Form::close() !!}
                      @endcan
                    </td>
                  </tr>
                  @endif
                @endforeach
                @endif
              </tbody>
            </table>
            {{ $data->appends(Request::all())->links("pagination::bootstrap-4"); }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@push('js')
<script type="text/javascript">
  $(document).ready(function() {
    var APP_URL = "{{ url('') }}";
    //$(".statusChange").click(function(e) {
    $(document).on("click", ".statusChange", function(e) {
      var fieldid = $(this).data("fieldid");
      $.ajax({
        type: "POST",
        url: APP_URL + "/user_status_change",
        //dataType: "json",
        data: {
          "_token": "{{ csrf_token() }}",
          "fieldid": fieldid,
        },
        success: function(data, textStatus, jqXHR) {
          var text_status = (data == 1) ? "Inactive" : "Active";
          var text_color = (data == 1) ? "btn-danger" : "btn-primary";
          $("#td_status" + fieldid).html('<a class="btn btn-sm ' + text_color + ' statusChange" data-fieldid="' + fieldid + '" href="javascript:void(0);">' + text_status + '</a>');
          //$(".submitting").html('<p>Thanks for your request - we will be in touch soon!</p>');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          //$(".submitting").html('<p>Message failed to send. Please try again!</p>');
        }
      });
    });


    $(document).on("click", ".sellerApproval", function(e) {
      var fieldid = $(this).data("fieldid");
      $.ajax({
        type: "POST",
        url: APP_URL + "/user_seller_approval",
        //dataType: "json",
        data: {
          "_token": "{{ csrf_token() }}",
          "fieldid": fieldid,
        },
        success: function(data, textStatus, jqXHR) {
          var text_status = (data == 1) ? "Yes" : "No";
          var text_color = (data == 1) ? "btn-primary" : "btn-danger";
          $("#td_approval" + fieldid).html('<a class="btn btn-sm ' + text_color + ' sellerApproval" data-fieldid="' + fieldid + '" href="javascript:void(0);">' + text_status + '</a>');
          //$(".submitting").html('<p>Thanks for your request - we will be in touch soon!</p>');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          //$(".submitting").html('<p>Message failed to send. Please try again!</p>');
        }
      });
    });

  })
</script>
@endpush