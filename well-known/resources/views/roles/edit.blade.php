@extends('layouts.app')

@section('content')

<!-- <div class="header header-bg bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Default</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li> /
              <li class="breadcrumb-item"><a href="#">Edit Role</a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div> -->
<!-- Page content -->
<div class="content-wrapper">
  <div class="container-fluid">

    <div class="row">
      <div class="col-xl-12 mt-3 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12">
   
                <h5 class="info-box-text mb-0"><b>Edit Role</b></h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
            <div class="pl-lg-4">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="form-control-label" for="input-username">Name</label>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <label class="form-control-label mb-0" for="permission-edit-1">Permission</label>
                      <div class="form-check mb-0">
                        <input class="form-check-input" id="check-all-permissions" type="checkbox" value="1">
                        <label class="form-check-label" for="check-all-permissions">Check All</label>
                      </div>
                    </div>

                    <div class="row">
                      @forelse($permission as $perm)
                      <div class="col-md-4 mb-2">
                        <div class="form-check">
                          <input class="form-check-input permission-checkbox" name="permission[]" type="checkbox" value="{{ $perm->id }}" id="permission-edit-{{ $perm->id }}" {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                          <label class="form-check-label" for="permission-edit-{{ $perm->id }}">{{ $perm->name }}</label>
                        </div>
                      </div>
                      @empty
                      <div class="col-12">
                        <p class="text-muted mb-0">No permissions available.</p>
                      </div>
                      @endforelse
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary my-4">Save</button>
              <a href="{{ route('roles.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->



@endsection


@section('script_scetion')
<script>
  $('#check-all-permissions').on('change', function() {
    $('.permission-checkbox').prop('checked', $(this).prop('checked'));
  });
</script>
@endsection