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
            <div class="row align-items-center">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Banks</b></h5>
              </div>
              <div class="col text-right">
                <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main" method="GET">
                  <div class="form-group mb-0">
                    <div class="input-group input-group-alternative input-group-merge searchBar ">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input class="form-control" placeholder="Search" id="search" name="search" type="text" value="{{ !empty($_GET['search'])?$_GET['search']:''; }}">
                    </div>
                  </div>
                  <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </form>
              </div>
              @can('bank-create')
              <div class="col-auto text-right">

                <a href="{{ route('loans.create') }}" class="btn btn-block bg-gradient-primary btn-sm">Add Loan</a>
              </div>
              @endcan
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Sl No</th>
                  <th scope="col">Name</th>
                  <th scope="col">Priduct Name</th>
                  <th scope="col">Bank Name</th>
                  <th scope="col">Loan Amount</th>
                  <th scope="col">EMI Amount</th>
                  <th scope="col">Tenure</th>
                  <th scope="col">Status</th>
                  <th scope="col" align="right">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty(@$data))
                @foreach ($data as $key => $loan)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $loan->borrower_name }}</td>
                  <td>{{ $loan->product->name }}</td>
                  <td>{{ $loan->bank->name }}</td>
                  <td>{{ $loan->amount_sactioin }}</td>
                  <td>{{ $loan->amount_emi }}</td>
                  <td>{{ $loan->tenour }}</td>
                  <td id="td_status{{ $loan->id }}">
                    @can('loan-edit')
                    <?php
                    if ($loan->status == '0') {
                      echo '<a class="btn btn-sm btn-primary statusChange" data-fieldid="' . $loan->id . '" href="javascript:void(0);">Active</a>';
                    } else {
                      echo '<a class="btn btn-sm btn-danger statusChange" data-fieldid="' . $loan->id . '" href="javascript:void(0);">Inactive</a>';
                    }
                    ?>
                    @endcan
                    @if(!Gate::check('loan-edit'))
                    {{ ($loan->status==0)?"Active":"Inactive"; }}
                    @endif
                  </td>
                  <td class="icons">
                    @can('loan-edit')
                    <a class="btn btn-sm btn-primary" href="{{ route('loans.edit',$loan->id) }}">Edit</a>
                    @endcan

                    @can('loan-delete')
                    {!! Form::open(['method' => 'DELETE','route' => ['loans.destroy', $loan->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger delete_row']) !!}
                    {!! Form::close() !!}
                    @endcan
                  </td>
                </tr>
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
        url: APP_URL + "/loan_status_change",
        //dataType: "json",
        data: {
          "_token": "{{ csrf_token() }}",
          "fieldid": fieldid,
        },
        // beforeSend: function() {
        //   $("#loader3").show();
        // },
        // complete: function() {
        //   $("#loader3").hide();
        // },
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

  })
</script>
@endpush