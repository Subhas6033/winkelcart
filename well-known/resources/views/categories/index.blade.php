@extends('layouts.app')

@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row mt-5">
      <div class="col-xl-12 mt-3">
        @if(Session::has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
          </div>
        @endif

        <div class="card">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Category Management</b></h5>
                <small class="text-muted">5 main categories are fixed. Add sub-categories under each.</small>
              </div>
            </div>
          </div>
          <div class="card-body p-0">

            @php
              $mainCategories = \App\Models\SubCategory::MAIN_CATEGORIES;
              $allSubs = \App\Models\SubCategory::where('deleted', 0)->orderBy('name')->get()->groupBy('category_id');
            @endphp

            <div class="accordion" id="categoryAccordion">
              @foreach($mainCategories as $catId => $catName)
              @php $subs = $allSubs->get($catId, collect()); @endphp
              <div class="card mb-0" style="border-radius:0; border-bottom:1px solid #e9ecef;">
                <div class="card-header d-flex align-items-center justify-content-between py-3 px-4"
                     id="heading{{ $catId }}"
                     style="background:#f8f9fa; cursor:pointer;"
                     data-toggle="collapse" data-target="#collapse{{ $catId }}"
                     aria-expanded="true">
                  <div>
                    <span class="badge badge-primary mr-2">Main</span>
                    <strong style="font-size:15px;">{{ $catName }}</strong>
                    <span class="badge badge-secondary ml-2">{{ $subs->count() }} sub-categories</span>
                  </div>
                  <div class="d-flex align-items-center">
                    @can('category-create')
                    <a href="{{ route('sub_categories.create', ['category_id' => $catId]) }}"
                       class="btn btn-sm btn-success mr-2" onclick="event.stopPropagation();">
                      + Add Sub-category
                    </a>
                    @endcan
                    <i class="fas fa-chevron-down text-muted"></i>
                  </div>
                </div>

                <div id="collapse{{ $catId }}" class="collapse show">
                  <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th style="width:50px;">#</th>
                          <th>Sub-category Name</th>
                          <th style="width:130px;">Status</th>
                          <th style="width:160px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($subs->isEmpty())
                          <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                              No sub-categories yet.
                              @can('category-create')
                              <a href="{{ route('sub_categories.create', ['category_id' => $catId]) }}">Add one now</a>
                              @endcan
                            </td>
                          </tr>
                        @else
                          @foreach($subs as $i => $sub)
                          <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $sub->name }}</td>
                            <td id="td_sub_status{{ $sub->id }}">
                              @can('category-edit')
                              @if($sub->status == 0)
                                <a class="btn btn-sm btn-primary subStatusChange" data-fieldid="{{ $sub->id }}" href="javascript:void(0);">Active</a>
                              @else
                                <a class="btn btn-sm btn-danger subStatusChange" data-fieldid="{{ $sub->id }}" href="javascript:void(0);">Inactive</a>
                              @endif
                              @endcan
                              @if(!Gate::check('category-edit'))
                                {{ $sub->status == 0 ? 'Active' : 'Inactive' }}
                              @endif
                            </td>
                            <td>
                              @can('category-edit')
                              <a class="btn btn-sm btn-primary" href="{{ route('sub_categories.edit', $sub->id) }}">Edit</a>
                              @endcan
                              @can('category-delete')
                              {!! Form::open(['method' => 'DELETE', 'route' => ['sub_categories.destroy', $sub->id], 'style' => 'display:inline']) !!}
                              {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger delete_sub_row']) !!}
                              {!! Form::close() !!}
                              @endcan
                            </td>
                          </tr>
                          @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @endforeach
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
  var APP_URL = "{{ url('') }}";

  $(document).on('click', '.subStatusChange', function() {
    var fieldid = $(this).data('fieldid');
    $.ajax({
      type: 'POST',
      url: APP_URL + '/sub_category_status_change',
      data: { '_token': '{{ csrf_token() }}', 'fieldid': fieldid },
      success: function(data) {
        var label = (data == 1) ? 'Inactive' : 'Active';
        var cls   = (data == 1) ? 'btn-danger' : 'btn-primary';
        $('#td_sub_status' + fieldid).html(
          '<a class="btn btn-sm ' + cls + ' subStatusChange" data-fieldid="' + fieldid + '" href="javascript:void(0);">' + label + '</a>'
        );
      }
    });
  });

  $(document).on('click', '.delete_sub_row', function(e) {
    if (!confirm('Are you sure you want to delete this sub-category?')) {
      e.preventDefault();
    }
  });
});
</script>
@endpush