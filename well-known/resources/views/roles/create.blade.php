@extends('layouts.app')

@section('content')
<div class="content-wrapper">
  <div class="container-fluid">

    <div class="row">
      <div class="col-xl-12 mt-3 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12">
                <h3 class="mb-0">Add Role</h3>
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
            <form action="{{ route('roles.store') }}" method="POST">
              @csrf
              <div class="pl-lg-4">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-control-label" for="input-username">Name</label>
                      <input type="text" name="name" class="form-control" required>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-control-label mb-0" for="permission-1">Permission</label>
                        <div class="form-check mb-0">
                          <input class="form-check-input" id="check-all-permissions" type="checkbox" value="1">
                          <label class="form-check-label" for="check-all-permissions">Check All</label>
                        </div>
                      </div>

                      <div class="row">
                        @forelse($permission as $perm)
                        <div class="col-md-4 mb-2">
                          <div class="form-check">
                            <input class="form-check-input permission-checkbox" name="permission[]" type="checkbox" value="{{ $perm->id }}" id="permission-{{ $perm->id }}">
                            <label class="form-check-label" for="permission-{{ $perm->id }}">{{ $perm->name }}</label>
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