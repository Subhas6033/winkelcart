@extends('layouts.app')

@section('content')

<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row mt-5">
      <div class="col-xl-12 mt-3">
        @if(Session::has('message'))
        <div>
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        </div>
        @endif

        @if ($message = Session::get('success'))
        <div>
          <p class="alert alert-success">{{ $message }}</p>
        </div>
        @endif
        <div class="card">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>User Role</b></h5>
              </div>
              <div class="col text-right">
                <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main" method="GET">
                  <div class="form-group mb-0">
                    <div class="input-group input-group-alternative input-group-merge searchBar">
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
              @can('role-create')
              <div class="col-auto text-right">
                <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">Add Role</a>
              </div>
              @endcan
            </div>
          </div>
          <div class="table-responsive">
            <!-- Projects table -->
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col" width="2%">Sl No</th>
                  <th scope="col">Role</th>
                  <th scope="col" width="10%">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty(@$roles))
                @foreach ($roles as $key => $role)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $role->name }}</td>
                  <td>
                    <!-- <a class="btn btn-sm btn-info" href="{{ route('roles.show',$role->id) }}">Show</a> -->
                    {{--@can('role-edit')--}}
                    <a class="btn btn-sm btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                    {{--@endcan--}}
                    {{--@can('role-delete')
                    {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
                    {!! Form::close() !!}
                    @endcan--}}
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
            {{ $roles->appends(Request::all())->links("pagination::bootstrap-4"); }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection