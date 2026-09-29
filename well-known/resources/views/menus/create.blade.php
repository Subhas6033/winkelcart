@extends('layouts.app')

@section('content')

<div class="header header-bg bg-primary pb-6 mar-left mt-5">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Default</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Add Menu</a></li>
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
    <div class="col-xl-12 order-xl-1">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-12">
              <h3 class="mb-0">Add Menu</h3>
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
          <form action="{{ route('menus.store') }}" method="POST" id="menu_store" name="menu_store">
            @csrf
            <div class="pl-lg-4">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <input type="hidden" name="menu_id" id="menu_id" class="form-control" value="{{ isset($menu->id)  ? $menu->id : ''; }}">
                    <label class="form-control-label" for="input-username">Menu</label>
                    <span class="validation-error" id="error_msg_menu_title"></span>
                    <input type="text" name="menu_title" id="menu_title" class="form-control" value="{{ !empty($menu->menu_title)?$menu->menu_title:''}}">
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="form-control-label" for="input-username">URLs</label>
                    <span class="validation-error" id="error_msg_urls"></span>
                    <textarea type="text" name="urls" id="urls" class="form-control" placeholder="Sepatated by comma(,)">{{ !empty($menu->urls)?$menu->urls:''}}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary my-4">Save</button>
              <a href="{{ route('menus.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Footer -->
<footer class="footer pt-0">
  <div class="row align-items-center justify-content-lg-between">
    <div class="col-lg-6">
      <div class="copyright text-center  text-lg-left  text-muted">
        &copy; 2022 <a href="#" class="font-weight-bold ml-1" target="_blank"></a>
      </div>
    </div>
  </div>
</footer>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#menu_store").submit(function() {
      //e.preventDefault();
      $('.validation-error').hide();
      //var for_mode =$( "#offer_store" ).attr('for_mode');
      var menu_title = $('#menu_title').val();
      var urls = $('#urls').val();
      if ($.trim(menu_title) == '') {
        $("#error_msg_menu_title").show().text("Menu is not set!");
        return false;
      } else if ($.trim(urls) == '') {
        $("#error_msg_urls").show().text("Url is not set!");
        return false;
      } else {
        $(this).find("button[type='submit']").prop('disabled', true);
        return true;
      }

    });
  });
</script>
@endsection