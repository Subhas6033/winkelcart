<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.APP_NAME', 'Winkel') }}</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="{{ asset('assets_admin/vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets_admin/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets_admin/css/argon.css?v=1.2.0') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets_admin/css/dataTables.bootstrap4.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets_admin/css/jquery-clockpicker.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets_admin/css/app.css?'.uniqid()) }}" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="{{ asset('assets_admin/js/components/Component.js')."?v126" }}"></script>
  <script src="{{ asset('assets_admin/js/image-file-display.js')."?v2" }}"></script>
  <script src="{{ asset('assets_admin/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/js-cookie/js.cookie.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ url('/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ url('/ckeditor/ckfinder/ckfinder.js') }}"></script>
  <script type="text/javascript">
    let itineraryImages = [];
    itineraryImages.push(new ItemList());
  </script>
  <link rel="stylesheet" href="{{ asset('assets_admin/css/styles.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('assets_admin/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets_admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets_admin/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse">
  <div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
      <!-- <h1 class="animation__wobble"><img width="150px" src="{{asset('assets_admin/images/Penguin-Logo-15.jpg')}}" alt=""></h1> -->
      <h1 class="animation__wobble">Winkel</h1>
    </div>
    <nav class="main-header navbar navbar-expand  navbar-dark justify-content-between" style=" background: #2a3c88;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <div class="media align-items-center ml-2">
          <span class="avatar avatar-sm rounded-circle">
            <img alt="Image placeholder" src="{{ asset('assets_admin/img/theme/team-4.png') }}">
          </span>
          <div class="media-body ml-2 d-none d-lg-block">
            <span class="mb-0 text-sm font-weight-bold text-white">{{ Auth::user()->name }}</span>
          </div>
        </div>
        <li class="nav-item d-none d-sm-inline-block ml-8">
          <a href="{{ url('admin/dashboard') }}" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="" class="nav-link"></a>
        </li>
      </ul>
      <ul class="navbar-nav">

        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar  elevation-4" style="background: #2a3c88;">
      <a class="brand-link text-center" href="javascript:void(0)">
        <!-- <img class="logo" src="{{asset('assets_admin/images/ab.png')}}" alt="">
        <img class="logoSq" src="{{asset('assets_admin/images/a.png')}}" alt=""> -->
        <h4 class="logoSq">Winkel</h4>
      </a>
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
              <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            @php
            $menus = DB::select("select * from `menus` where `deleted` = 0 and `status` = 1");
            @endphp
            @canany(['user-list', 'user-create', 'user-edit', 'user-delete'])
            <li class="nav-item {{ request()->is('admin/users') ? 'active' : '' }} {{ request()->is('admin/users/*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('users.index') }}">
                <i class="nav-icon fas fa-th"></i>
                <span class="nav-link-text">User Management</span>
              </a>
            </li>
            @endcanany
            @canany(['role-list', 'role-create', 'role-edit', 'role-delete'])
            <li class="nav-item {{ request()->is('admin/roles') ? 'active' : '' }} {{ request()->is('admin/roles/*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('roles.index') }}">
                <i class="nav-icon fas fa-th"></i>
                <span class="nav-link-text">Role Management</span>
              </a>
            </li>
            @endcanany
            @canany(['menu-list', 'menu-create', 'menu-edit', 'menu-delete'])
            <li class="nav-item {{ request()->is('admin/menus') ? 'active' : '' }} {{ request()->is('admin/menus/*') ? 'active' : '' }}" style="display:none">
              <a class="nav-link" href="{{ route('menus.index') }}">
                <i class="ni ni-planet"></i>
                <span class="nav-link-text">Menu</span>
              </a>
            </li>
            @endcanany
            @canany(['category-list', 'category-create', 'category-edit', 'category-delete'])
            <li class="nav-item {{ request()->is('admin/categories') ? 'active' : '' }} {{ request()->is('admin/categories/*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('categories.index') }}">
                <i class="nav-icon fas fa-th"></i>
                <span class="nav-link-text">Category Management</span>
              </a>
            </li>
            @endcanany            
            @canany(['product-list', 'product-create', 'product-edit', 'product-delete'])
            <li class="nav-item {{ request()->is('admin/products') ? 'active' : '' }} {{ request()->is('admin/products/*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('products.index') }}">
                <i class="nav-icon fas fa-th"></i>
                <span class="nav-link-text">Product Management</span>
              </a>
            </li>
            @endcanany
            @canany(['order-list', 'order-create', 'order-edit', 'order-delete'])
            <li class="nav-item {{ request()->is('admin/orders') ? 'active' : '' }} {{ request()->is('admin/orders/*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('orders.index') }}">
                <i class="nav-icon fas fa-th"></i>
                <span class="nav-link-text">Order Management</span>
              </a>
            </li>
            @endcanany
            @canany(['sell-report-list', 'sell-report-create', 'sell-report-edit', 'sell-report-delete'])
            <li class="nav-item {{ request()->is('admin/sell-report') ? 'active' : '' }} {{ request()->is('admin/sell-report/*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('sell-report.index') }}">
                <i class="nav-icon fas fa-th"></i>
                <span class="nav-link-text">Sell Report</span>
              </a>
            </li>
            @endcanany

            
          </ul>
        </nav>
      </div>
    </aside>
    @yield('content')
  </div>
  <div class="content-wrapper px-4 py-3 h-auto footer">
    <div class="container-fluid">
      <footer class="footer p-0 ">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center  text-lg-left  text-muted">
              &copy; 2025 <a href="#" class="font-weight-bold ml-1" target="_blank">Winkel</a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script src="{{ asset('assets_admin/vendor/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/chart.js/dist/Chart.extension.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets_admin/js/argon.js?v=1.2.0') }}"></script>
  <script src="{{ asset('assets_admin/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets_admin/js/jquery-clockpicker.min.js') }}"></script>
  <script src="{{ asset('assets_admin/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
  <script src="{{ asset('assets_admin/js/main.js') }}"></script>
  @yield('script_scetion')
</body>
</script>
<script src="{{ asset('assets_admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets_admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets_admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('assets_admin/dist/js/adminlte.js') }}"></script>
<script src="{{ asset('assets_admin/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('assets_admin/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('assets_admin/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('assets_admin/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<script src="{{ asset('assets_admin/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets_admin/dist/js/pages/dashboard2.js') }}"></script>

</html>
@stack('js')