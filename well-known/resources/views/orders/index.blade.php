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
            <div class="row align-items-center mb-2">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Orders</b></h5>
              </div>
            </div>
            <form method="GET" action="{{ route('orders.index') }}" id="order-filter-form">
              <div class="form-row align-items-end">
                {{-- Keyword search --}}
                <div class="form-group col-md-3 mb-2">
                  <label class="small mb-1">Search</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control" name="search" type="text"
                      placeholder="Order #, buyer, seller, product"
                      value="{{ $search ?? '' }}">
                  </div>
                </div>

                {{-- Payment status --}}
                <div class="form-group col-md-2 mb-2">
                  <label class="small mb-1">Payment Status</label>
                  <select name="payment_status" class="form-control form-control-sm">
                    <option value="">All Payment Status</option>
                    @foreach(['Pending','Paid','Done','Failed'] as $ps)
                      <option value="{{ $ps }}" {{ ($paymentStatus ?? '') === $ps ? 'selected' : '' }}>{{ $ps }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Order/delivery status --}}
                <div class="form-group col-md-2 mb-2">
                  <label class="small mb-1">Order Status</label>
                  <select name="order_status" class="form-control form-control-sm">
                    <option value="">All Order Status</option>
                    @foreach(['Pending','Processing','Shipped','Delivered','Cancelled'] as $os)
                      <option value="{{ $os }}" {{ ($orderStatus ?? '') === $os ? 'selected' : '' }}>{{ $os }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Payment method --}}
                <div class="form-group col-md-2 mb-2">
                  <label class="small mb-1">Payment Method</label>
                  <select name="payment_method" class="form-control form-control-sm">
                    <option value="">All Methods</option>
                    <option value="razorpay" {{ ($paymentMethod ?? '') === 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                    <option value="cod" {{ ($paymentMethod ?? '') === 'cod' ? 'selected' : '' }}>COD</option>
                  </select>
                </div>

                {{-- Date range --}}
                <div class="form-group col-md-1 mb-2">
                  <label class="small mb-1">From</label>
                  <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom ?? '' }}">
                </div>
                <div class="form-group col-md-1 mb-2">
                  <label class="small mb-1">To</label>
                  <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo ?? '' }}">
                </div>

                {{-- Buttons --}}
                <div class="form-group col-md-1 mb-2 d-flex align-items-end" style="gap:4px;">
                  <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                  <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                </div>
              </div>
            </form>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Sl No</th>
                  <th scope="col">Order No</th>
                  <th scope="col">Buyer</th>
                  <th scope="col">Address</th>
                  <th scope="col">Payment Method</th>
                  <th scope="col">Products</th>
                  <th scope="col">Total Amount</th>
                  <th scope="col">Payment Info</th>
                  <th scope="col">Payment Status</th>
                  <th scope="col">Status</th>
                  <th scope="col" align="right">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty($data))
                @foreach ($data as $key => $order)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $order->order_number ?? 'N/A' }}</td>

                  {{-- Null-safe buyer --}}
                  <td>{{ $order->buyer->name ?? 'N/A' }}</td>

                  <td>{{ $order->shipping_address ?? 'N/A' }}</td>
                  <td>
                    @if(strtolower($order->payment_method ?? '') === 'razorpay')
                      <span class="badge badge-info"><i class="fas fa-credit-card"></i> Razorpay</span>
                    @elseif(strtolower($order->payment_method ?? '') === 'cod')
                      <span class="badge badge-secondary"><i class="fas fa-money-bill-wave"></i> COD</span>
                    @else
                      {{ ucfirst($order->payment_method ?? 'N/A') }}
                    @endif
                  </td>

                  <td>
                    @foreach ($order->items as $item)
                    <div class="mb-3">

                      {{-- Null-safe product --}}
                      <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                      ({{ $item->quantity }} × ₹{{ $item->price }})<br>
                      <small>Subtotal: ₹{{ number_format($item->quantity * $item->price, 2) }}</small><br>

                      {{-- Null-safe seller --}}
                      @if($item->product && $item->product->seller)
                        <small class="text-muted">
                          Seller: {{ $item->product->seller->name }} ({{ $item->product->seller->email }})
                        </small><br>
                      @else
                        <small class="text-muted">Seller: N/A</small><br>
                      @endif

                      <small><b>Delivery Date:</b> {{ $item->delivery_date ? \Carbon\Carbon::parse($item->delivery_date)->format('Y-m-d') : 'Not set' }}</small><br>

                      @if(Auth::user()->hasRole('Admin'))
                        <label class="mt-2"><b>Payment to Seller (₹):</b></label>
                        <input type="text" class="form-control form-control-sm payment-to-seller allow-only-numeric" data-itemid="{{ $item->id }}" value="{{ $item->admin_paid_amount ?? '' }}" maxlength="6">
                      @elseif(Auth::user()->hasRole('Seller'))
                        <small><b>Payment Received:</b> ₹{{ $item->admin_paid_amount ?? '0.00' }}</small>
                      @endif

                    </div>
                    <hr>
                    @endforeach
                  </td>

                  <td>
                    @if(Auth::user()->hasRole('Seller'))
                      ₹{{ number_format($order->seller_total ?? 0, 2) }}
                    @else
                      ₹{{ number_format($order->total_amount ?? 0, 2) }}
                    @endif
                  </td>

                  <td>
                    @if ($order->razorpay_payment_id)
                      <span class="badge badge-info" title="Razorpay Payment ID"><i class="fas fa-credit-card"></i> Razorpay</span>
                      <br><small><code>{{ $order->razorpay_payment_id }}</code></small>
                      @if($order->razorpay_order_id)
                        <br><small class="text-muted">Order: {{ $order->razorpay_order_id }}</small>
                      @endif
                    @elseif ($order->payment_image)
                      <img src="{{ asset('uploads/payments/'.$order->payment_image) }}"
                          width="50" height="50"
                          class="img-thumbnail view-image"
                          data-image="{{ asset('uploads/payments/'.$order->payment_image) }}"
                          style="cursor: pointer;">
                    @else
                      <span class="text-muted">No Payment Info</span>
                    @endif
                  </td>

                  <td id="td_order_status{{ $order->id }}">
                    @can('order-edit')
                    @php
                      $paymentStatus = $order->payment_status ?? $order->order_status;
                      $isPaid = in_array($paymentStatus, ['Paid', 'Done']);
                    @endphp
                    @if($order->razorpay_payment_id && $isPaid)
                      <span class="badge badge-success"><i class="fas fa-check-circle"></i> Paid</span>
                      <br><small class="text-muted">via Razorpay</small>
                    @elseif($isPaid)
                      <a class="btn btn-sm btn-primary paymentChange" data-fieldid="{{ $order->id }}" href="javascript:void(0);">Done</a>
                    @else
                      <a class="btn btn-sm btn-danger paymentChange" data-fieldid="{{ $order->id }}" href="javascript:void(0);">Pending</a>
                    @endif
                    @endcan
                    @if(!Gate::check('order-edit'))
                      @php $paymentStatus = $order->payment_status ?? $order->order_status; @endphp
                      @if(in_array($paymentStatus, ['Paid', 'Done']))
                        <span class="badge badge-success">Paid</span>
                      @else
                        <span class="badge badge-warning">Pending</span>
                      @endif
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
                      {{ ($order->status == 0) ? "Active" : "Inactive" }}
                    @endif
                  </td>

                  <td class="icons">
                    @can('order-edit')
                    <!-- Edit button placeholder -->
                    @endcan

                    @can('order-delete')
                    {!! Form::open(['method' => 'DELETE', 'route' => ['orders.destroy', $order->id], 'style' => 'display:inline']) !!}
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

    $(document).on("click", ".statusChange", function(e) {
      var fieldid = $(this).data("fieldid");
      $.ajax({
        type: "POST",
        url: APP_URL + "/order_status_change",
        data: {
          "_token": "{{ csrf_token() }}",
          "fieldid": fieldid,
        },
        success: function(data, textStatus, jqXHR) {
          var text_status = (data == 1) ? "Inactive" : "Active";
          var text_color  = (data == 1) ? "btn-danger" : "btn-primary";
          $("#td_status" + fieldid).html('<a class="btn btn-sm ' + text_color + ' statusChange" data-fieldid="' + fieldid + '" href="javascript:void(0);">' + text_status + '</a>');
        },
        error: function(jqXHR, textStatus, errorThrown) {}
      });
    });

    $(document).on("click", ".paymentChange", function(e) {
      var fieldid = $(this).data("fieldid");
      $.ajax({
        type: "POST",
        url: APP_URL + "/payment_status_change",
        data: {
          "_token": "{{ csrf_token() }}",
          "fieldid": fieldid,
        },
        success: function(data, textStatus, jqXHR) {
          var isPaid = (data == 'Done');
          var text_status = isPaid ? "Done" : "Pending";
          var text_color  = isPaid ? "btn-primary" : "btn-danger";
          $("#td_order_status" + fieldid).html('<a class="btn btn-sm ' + text_color + ' paymentChange" data-fieldid="' + fieldid + '" href="javascript:void(0);">' + text_status + '</a>');
        },
        error: function(jqXHR, textStatus, errorThrown) {}
      });
    });

    // Confirm before deleting
    $(document).on("click", ".delete_row", function(e) {
      if (!confirm('Are you sure you want to delete this order?')) {
        e.preventDefault();
      }
    });

  });

  $(document).on("change", ".delivery-date", function() {
    let itemId       = $(this).data("itemid");
    let deliveryDate = $(this).val();

    $.ajax({
      type: "POST",
      url: "{{ url('/update-delivery-date') }}",
      data: {
        _token:        "{{ csrf_token() }}",
        item_id:       itemId,
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
        _token:  "{{ csrf_token() }}",
        item_id: itemId,
        amount:  amount
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
    node.val(node.val().replace(/[^0-9]/g, ''));
  });

  function showToast(message, type = 'success') {
    const colors = {
      success: '#28a745',
      danger:  '#dc3545',
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

    setTimeout(() => {
      toast.css({ opacity: 1, transform: 'translateY(0)' });
    }, 50);

    setTimeout(() => {
      toast.css({ opacity: 0, transform: 'translateY(-10px)' });
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

</script>
@endpush