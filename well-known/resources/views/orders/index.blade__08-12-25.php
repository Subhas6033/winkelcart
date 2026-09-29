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
                <h5 class="info-box-text mb-0"><b>Orders</b></h5>
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
              @can('order-create')
              <!-- <div class="col-auto text-right">

                <a href="{{ route('categories.create') }}" class="btn btn-block bg-gradient-primary btn-sm">Add Order</a>
              </div> -->
              @endcan
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Sl No</th>
                  <th scope="col">Order No</th>
                  <th scope="col">Buyer</th>
                  <th scope="col">Address</th>
                  <th scope="col">Products</th>
                  <th scope="col">Total Amount</th>
                  <th scope="col">Payment Receipt</th>
                  <th scope="col">Payment Status</th>
                  <th scope="col">Status</th>
                  <th scope="col" align="right">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty(@$data))
                @foreach ($data as $key => $order)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $order->order_number }}</td>
                  <td>{{ $order->buyer->name }}</td>
                  <td>{{ $order->buyer->user_info->address ?? 'N/A' }}</td>
                  <td>
                      @foreach ($order->items as $item)
                        @php
                            $isSeller = Auth::user()->hasRole('Seller') && $item->product->created_by == Auth::id();
                            $isAdmin = Auth::user()->hasRole('Admin');
                        @endphp

                        @if ($isAdmin || $isSeller)
                            <div class="mb-3">
                                <strong>{{ $item->product->name }}</strong>
                                ({{ $item->quantity }} × ₹{{ $item->price }}) <br>

                                {{-- ✅ Delivery Date Section --}}
                                @if($isSeller)
                                    @if(!$item->delivery_date)
                                        <label class="mt-1"><b>Delivery Date:</b></label>
                                        <input type="date"
                                              class="form-control form-control-sm delivery-date"
                                              data-itemid="{{ $item->id }}"
                                              value="">
                                    @else
                                        <small><b>Delivery Date:</b> {{ \Carbon\Carbon::parse($item->delivery_date)->format('Y-m-d') }}</small><br>
                                    @endif
                                @elseif($isAdmin)
                                    <small><b>Delivery Date:</b>
                                        {{ $item->delivery_date ? \Carbon\Carbon::parse($item->delivery_date)->format('Y-m-d') : 'Not set' }}
                                    </small><br>
                                @endif

                                {{-- 🧾 Seller info --}}
                                @if($item->product->seller)
                                    <small class="text-muted">
                                        Seller: {{ $item->product->seller->name }} ({{ $item->product->seller->email }})
                                    </small><br>
                                @endif

                                {{-- 💰 Payment Section --}}
                                @if($isAdmin)
                                    <label class="mt-2"><b>Payment to Seller (₹):</b></label>
                                    <input type="text"                                          
                                          class="form-control form-control-sm payment-to-seller allow-only-numeric"
                                          data-itemid="{{ $item->id }}"
                                          value="{{ $item->admin_paid_amount ?? '' }}" maxlength="6">
                                @elseif($isSeller)
                                    <small><b>Payment Received:</b> ₹{{ $item->admin_paid_amount ?? '0.00' }}</small>
                                @endif
                            </div>
                            <hr>
                        @endif
                      @endforeach

                  </td>
                  <td>
                      @if(Auth::user()->hasRole('Seller'))
                          ₹{{ number_format($order->seller_total, 2) }}
                      @else
                          ₹{{ number_format($order->total_amount, 2) }}
                      @endif
                  </td>
                  <td>                    
                    @if ($order->payment_image)
                        <img src="{{ asset('uploads/payments/'.$order->payment_image) }}"
                            width="50" height="50"
                            class="img-thumbnail view-image"
                            data-image="{{ asset('uploads/payments/'.$order->payment_image) }}"
                            style="cursor: pointer;">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                  </td>
                  <td id="td_order_status{{ $order->id }}">
                    @can('order-edit')
                    <?php
                    if ($order->order_status == 'Done') {
                      echo '<a class="btn btn-sm btn-primary paymentChange" data-fieldid="' . $order->id . '" href="javascript:void(0);">Done</a>';
                    } else {
                      echo '<a class="btn btn-sm btn-danger paymentChange" data-fieldid="' . $order->id . '" href="javascript:void(0);">Pending</a>';
                    }
                    ?>
                    @endcan
                    @if(!Gate::check('order-edit'))
                    {{ ($order->order_status=='Pending')?"Pending":"Done"; }}
                    @endif
                  </td>
                  <td id="td_status{{ $order->id }}">
                    @can('order-edit')
                    <?php
                    if ($order->status == '0') {
                      echo '<a class="btn btn-sm btn-primary statusChange" data-fieldid="' . $order->id . '" href="javascript:void(0);">Active</a>';
                    } else {
                      echo '<a class="btn btn-sm btn-danger statusChange" data-fieldid="' . $order->id . '" href="javascript:void(0);">Inactive</a>';
                    }
                    ?>
                    @endcan
                    @if(!Gate::check('order-edit'))
                    {{ ($order->status==0)?"Active":"Inactive"; }}
                    @endif
                  </td>
                  <td class="icons">
                    @can('order-edit')
                    <!-- <a class="btn btn-sm btn-primary" href="{{ route('categories.edit',$order->id) }}">Edit</a> -->
                    @endcan

                    @can('order-delete')
                    {!! Form::open(['method' => 'DELETE','route' => ['categories.destroy', $order->id],'style'=>'display:inline']) !!}
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

          <!-- Payment Image Modal -->
          <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-body text-center">
                  <img id="previewImage" src="" class="img-fluid rounded shadow">
                </div>
              </div>
            </div>
          </div>
          <!-- End of Payment Image Modal -->
           
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".view-image").forEach(img => {
      img.addEventListener("click", function() {
        const modalImg = document.getElementById("previewImage");
        modalImg.src = this.dataset.image;
        const modal = new bootstrap.Modal(document.getElementById("imagePreviewModal"));
        modal.show();
      });
    });
  });

  $(document).ready(function() {
    var APP_URL = "{{ url('') }}";    
    //$(".statusChange").click(function(e) {
    $(document).on("click", ".statusChange", function(e) {
      var fieldid = $(this).data("fieldid");
      $.ajax({
        type: "POST",
        url: APP_URL + "/order_status_change",
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

    $(document).on("click", ".paymentChange", function(e) {
      var fieldid = $(this).data("fieldid");
      $.ajax({
        type: "POST",
        url: APP_URL + "/payment_status_change",
        //dataType: "json",
        data: {
          "_token": "{{ csrf_token() }}",
          "fieldid": fieldid,
        },        
        success: function(data, textStatus, jqXHR) {
          var text_status = (data == 'Pending') ? "Pending" : "Done";
          var text_color = (data == 'Pending') ? "btn-danger" : "btn-primary";
          $("#td_order_status" + fieldid).html('<a class="btn btn-sm ' + text_color + ' paymentChange" data-fieldid="' + fieldid + '" href="javascript:void(0);">' + text_status + '</a>');
          //$(".submitting").html('<p>Thanks for your request - we will be in touch soon!</p>');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          //$(".submitting").html('<p>Message failed to send. Please try again!</p>');
        }
      });
    });

  })

  $(document).on("change", ".delivery-date", function() {
      let itemId = $(this).data("itemid");
      let deliveryDate = $(this).val();

      $.ajax({
          type: "POST",
          url: "{{ url('/update-delivery-date') }}",
          data: {
              _token: "{{ csrf_token() }}",
              item_id: itemId,
              delivery_date: deliveryDate
          },
          success: function(res) {              
              showToast('Delivery date added successfully ✅', 'success');
          },
          error: function() {              
              showToast('Failed to update delivery date', 'error');
          }
      });
  });

  $(document).on("change", ".payment-to-seller", function() {
    let itemId = $(this).data("itemid");
    let amount = $(this).val();

    $.ajax({
        type: "POST",
        url: "{{ url('/update-seller-payment') }}",
        data: {
            _token: "{{ csrf_token() }}",
            item_id: itemId,
            amount: amount
        },
        success: function() {            
            showToast('Payment added successfully ✅', 'success');
        },
        error: function() {            
            showToast('Failed to add payment', 'error');
        }
    });
  });
  $('.allow-only-numeric').keyup(function() {
    var node = $(this);
    node.val(node.val().replace(/[^0-9]/g,''));
  });

  function showToast(message, type = 'success') {
        const colors = {
            success: '#28a745',
            danger: '#dc3545',
            warning: '#ffc107'
        };

        const toast = $(`
          <div class="custom-toast shadow-lg" style="
              position: fixed;
              top: 20px;
              right: 20px;
              z-index: 9999;
              background-color: ${colors[type] || '#17a2b8'};
              color: #fff;
              padding: 10px 18px;
              border-radius: 8px;
              font-weight: 500;
              display: flex;
              align-items: center;
              gap: 8px;
              box-shadow: 0 2px 10px rgba(0,0,0,0.15);
              opacity: 0;
              transform: translateY(-10px);
              transition: all 0.3s ease-in-out;
          ">
              ${message}
          </div>
      `);

      $('body').append(toast);

      // Animate in
      setTimeout(() => {
          toast.css({ opacity: 1, transform: 'translateY(0)' });
      }, 50);

      // Auto remove after 3 seconds
      setTimeout(() => {
          toast.css({ opacity: 0, transform: 'translateY(-10px)' });
          setTimeout(() => toast.remove(), 300);
      }, 3000);
  }
  
</script>
@endpush