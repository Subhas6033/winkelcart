@extends('layouts.app')
@section('content')

<div class="header header-bg bg-primary mar-left pb-6 mt-5">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Default</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Menus</a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6 mar-left">
  <div class="row">
    <div class="col-xl-12">
      @if(Session::has('success'))
      <div>
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
      </div>
      @endif
      <div class="card">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="mb-0">Menus</h3>
            </div>
            <div class="col text-right">
              <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main" method="GET">
                <div class="form-group mb-0">
                  <div class="input-group input-group-alternative input-group-merge">
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
            @can('menu-create')
            <div class="col-auto text-right">
              <a href="{{ route('menus.create') }}" class="btn btn-sm btn-primary">Add Menu</a>
            </div>
            @endcan
          </div>
        </div>
        <div class="table-responsive">
          <!-- Projects table -->
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col">Menu</th>
                <th scope="col">URLs</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty(@$menus))
              @foreach(@$menus as $row)
              <tr>
                <td>{{ $row->menu_title }}</td>
                <td>{{ $row->urls }}</td>
                <td id="td_status{{ $row->id }}">
                  @can('menu-edit')
                  <?php
                  if ($row->status == '0') {
                    echo '<a class="btn btn-sm btn-primary statusChange" data-fieldid="' . $row->id . '" href="javascript:void(0);">Active</a>';
                  } else {
                    echo '<a class="btn btn-sm btn-danger statusChange" data-fieldid="' . $row->id . '" href="javascript:void(0);">Inactive</a>';
                  }
                  ?>
                  @endcan
                  @if(!Gate::check('menu-edit'))
                  {{ ($row->status==0)?"Active":"Inactive"; }}
                  @endif
                </td>
                <td class="icons">
                  @can('menu-edit')
                  <!-- <a href="{{ route('menus.edit',$row->id) }}"><i class="fas fa-edit" title="Edit"></i></a> -->
                  <a class="btn btn-sm btn-primary" href="{{ route('menus.edit',$row->id) }}">Edit</a>
                  @endcan

                  @csrf
                  @can('menu-delete')
                  {!! Form::open(['method' => 'DELETE','route' => ['menus.destroy', $row->id],'style'=>'display:inline']) !!}
                  {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
                  {!! Form::close() !!}
                  @endcan
                </td>
              </tr>
              @endforeach
              @endif
            </tbody>
          </table>
          {{ $menus->appends(Request::all())->links("pagination::bootstrap-4"); }}
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <!-- <footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6">
        <div class="copyright text-center  text-lg-left  text-muted">
          &copy; 2025 <a href="#" class="font-weight-bold ml-1" target="_blank">Winkel</a>
        </div>
      </div>
    </div>
  </footer> -->
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
        url: APP_URL + "/menu_status_change",
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