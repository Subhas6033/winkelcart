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
        @if(Session::has('error'))
        <div>
          <p class="alert alert-danger">{{ Session::get('error') }}</p>
        </div>
        @endif

        @if(Auth::user()->hasRole('Seller') && !Auth::user()->isFullyKycVerified())
        <div class="alert alert-warning mb-3">
          <i class="fas fa-exclamation-triangle mr-1"></i>
          <strong>KYC Verification Required:</strong> Your seller account is not yet verified by admin. You cannot add or edit products until your KYC is approved.
          <a href="{{ route('seller.kyc.edit') }}" class="alert-link ml-1">Submit KYC &rarr;</a>
        </div>
        @endif

        <div class="card">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Products</b></h5>
              </div>
                <div class="col text-right">
                  <form class="form-inline flex-wrap" id="product-filter-form" method="GET" action="{{ route('products.index') }}">
                    <div class="form-row align-items-end w-100 justify-content-end">
                      {{-- Keyword --}}
                      <div class="form-group mb-1 mr-2">
                        <div class="input-group input-group-sm">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                          </div>
                          <input class="form-control" name="search" type="text"
                            placeholder="Name, description, brand"
                            value="{{ $search ?? '' }}" style="min-width:180px;">
                        </div>
                      </div>

                      {{-- Category (only for Admin) --}}
                      @if(Auth::user()->hasRole('Admin'))
                      <div class="form-group mb-1 mr-2">
                        <select name="category_id" class="form-control form-control-sm">
                          <option value="">All Categories</option>
                          @foreach([1=>'Electronics',2=>'Mobiles',3=>'Fashion',5=>'Appliances',4=>'Hotels Resorts'] as $cid => $cname)
                            <option value="{{ $cid }}" {{ ($categoryFilter ?? '') == $cid ? 'selected' : '' }}>{{ $cname }}</option>
                          @endforeach
                        </select>
                      </div>
                      @endif

                      {{-- Brand/Company --}}
                      <div class="form-group mb-1 mr-2">
                        <select name="company" class="form-control form-control-sm">
                          <option value="">All Brands</option>
                          @foreach($companyNames as $company)
                            <option value="{{ $company }}" {{ ($companyFilter ?? '') === $company ? 'selected' : '' }}>{{ $company }}</option>
                          @endforeach
                        </select>
                      </div>

                      {{-- Status --}}
                      <div class="form-group mb-1 mr-2">
                        <select name="status" class="form-control form-control-sm">
                          <option value="">All Status</option>
                          <option value="0" {{ isset($statusFilter) && $statusFilter === '0' ? 'selected' : '' }}>Active</option>
                          <option value="1" {{ isset($statusFilter) && $statusFilter === '1' ? 'selected' : '' }}>Inactive</option>
                        </select>
                      </div>

                      {{-- Buttons --}}
                      <div class="form-group mb-1" style="gap:4px; display:flex;">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                      </div>
                    </div>
                  </form>
                </div>

              @can('product-create')
              <div class="col-auto text-right">
                  <a href="{{ route('products.create') }}" class="btn btn-block bg-gradient-primary btn-sm">
                      Add Product
                  </a>
              </div>
              @endcan

            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Sl No</th>
                  <th>Name</th>
                  <th>Brand/Company</th>
                  <th>Offer Price</th>
                  <th>Final Price</th>
                  <th>Top Deal</th>
                  <th>Priority</th>
                  <th>Category</th>
                  <!-- <th>Description</th> -->
                  <th>Image</th>
                  <th scope="col">Status</th>
                  <th scope="col" align="right">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty(@$data))
                @php
                  $isAdminViewer = auth()->user() && auth()->user()->hasRole('Admin');
                @endphp
                @foreach ($data as $key => $product)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->company ?? '-' }}</td>
                  <td>{{ $product->offer_price }}</td>
                  <td>{{ $product->final_price }}</td>
                  <td>
                    @if($isAdminViewer)
                      <div class="custom-control custom-switch mb-0" style="min-height: 20px;">
                        <input type="checkbox"
                              class="custom-control-input topDealToggle"
                              id="topDealSwitch{{ $product->id }}"
                              data-productid="{{ $product->id }}"
                              {{ ((int) ($product->is_top_deal ?? 0) === 1) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="topDealSwitch{{ $product->id }}"></label>
                      </div>
                    @else
                      @if((int) ($product->is_top_deal ?? 0) === 1)
                        <span class="badge badge-success">Yes</span>
                      @else
                        <span class="badge badge-secondary">No</span>
                      @endif
                    @endif
                  </td>
                  <td>
                    @if($isAdminViewer)
                      <div class="d-flex align-items-center" style="gap:6px;">
                        <input type="number"
                              min="0"
                              max="9999"
                              class="form-control form-control-sm topDealPriority"
                              id="topDealPriority{{ $product->id }}"
                              data-productid="{{ $product->id }}"
                              value="{{ $product->top_deal_priority ?? 0 }}"
                              style="width: 82px;">
                        <button type="button"
                                class="btn btn-sm btn-outline-primary topDealSave"
                                data-productid="{{ $product->id }}">Save</button>
                      </div>
                    @else
                      {{ $product->top_deal_priority ?? 0 }}
                    @endif
                  </td>
                  <td>{{ $product->category ? $product->category->name : '-' }}</td>
                  <!-- <td>{{ $product->desc }}</td> -->
                  <td>
                    @if($product->image)
                      <img src="{{ asset('uploads/products/'.$product->image) }}" width="60" class="img-thumbnail">
                    @else
                      <span class="text-muted">No Image</span>
                    @endif
                  </td>
                  <td id="td_status{{ $product->id }}">
                    @can('product-edit')
                    <?php
                    if ($product->status == '0') {
                      echo '<a class="btn btn-sm btn-primary statusChange" data-fieldid="' . $product->id . '" href="javascript:void(0);">Active</a>';
                    } else {
                      echo '<a class="btn btn-sm btn-danger statusChange" data-fieldid="' . $product->id . '" href="javascript:void(0);">Inactive</a>';
                    }
                    ?>
                    @endcan
                    @if(!Gate::check('product-edit'))
                    {{ ($product->status==0)?"Active":"Inactive" }}
                    @endif
                  </td>
                  <td class="icons">
                    @can('product-edit')
                    <a class="btn btn-sm btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
                    @endcan

                    @can('product-delete')
                    {!! Form::open(['method' => 'DELETE','route' => ['products.destroy', $product->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger delete_row']) !!}
                    {!! Form::close() !!}
                    @endcan
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
            {{ $data->appends(Request::all())->links("pagination::bootstrap-4") }}
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
    var topDealUpdateUrlTemplate = "{{ route('products.top_deal.update', ['product' => '__ID__']) }}";

    function updateTopDeal(productId, triggerBtn) {
      var isTopDeal = $("#topDealSwitch" + productId).is(":checked") ? 1 : 0;
      var priority = $("#topDealPriority" + productId).val();
      var updateUrl = topDealUpdateUrlTemplate.replace('__ID__', productId);

      $.ajax({
        type: "POST",
        url: updateUrl,
        data: {
          "_token": "{{ csrf_token() }}",
          "is_top_deal": isTopDeal,
          "top_deal_priority": priority,
        },
        success: function(response) {
          if (triggerBtn) {
            var originalText = triggerBtn.text();
            triggerBtn.text('Saved');
            setTimeout(function() {
              triggerBtn.text(originalText);
            }, 1200);
          }
        },
        error: function(xhr) {
          alert('Unable to update top deal settings right now.');
        }
      });
    }

    $(document).on("change", ".topDealToggle", function() {
      var productId = $(this).data("productid");
      updateTopDeal(productId, null);
    });

    $(document).on("click", ".topDealSave", function() {
      var productId = $(this).data("productid");
      updateTopDeal(productId, $(this));
    });

    $(document).on("keypress", ".topDealPriority", function(e) {
      if (e.which === 13) {
        e.preventDefault();
        var productId = $(this).data("productid");
        updateTopDeal(productId, null);
      }
    });

    //$(".statusChange").click(function(e) {
    $(document).on("click", ".statusChange", function(e) {
      var fieldid = $(this).data("fieldid");
      $.ajax({
        type: "POST",
        url: APP_URL + "/product_status_change",
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