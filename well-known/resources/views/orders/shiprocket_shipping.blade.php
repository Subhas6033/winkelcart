@extends('layouts.app')

@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row mt-5">
      <div class="col-xl-12">

        @if(Session::has('success'))
        <div class="alert alert-success">
          <p class="mb-0">{{ Session::get('success') }}</p>
        </div>
        @endif

        @if(Session::has('error'))
        <div class="alert alert-danger">
          <p class="mb-0">{{ Session::get('error') }}</p>
        </div>
        @endif

        {{-- HOW SHIPROCKET WORKS --}}
        <div class="card mb-3" style="border-left: 4px solid #5e72e4;">
          <div class="card-body py-3">
            <h6 class="mb-2 text-primary"><i class="fas fa-info-circle"></i> How Shiprocket Delivery Works</h6>
            <div class="d-flex flex-wrap align-items-center" style="gap:6px;">
              <span class="badge badge-secondary px-3 py-2"><i class="fas fa-plus-circle"></i> 1. Order Created in Shiprocket</span>
              <i class="fas fa-arrow-right text-muted"></i>
              <span class="badge badge-info px-3 py-2"><i class="fas fa-barcode"></i> 2. AWB Code Assigned (courier selected)</span>
              <i class="fas fa-arrow-right text-muted"></i>
              <span class="badge badge-warning px-3 py-2" style="color:#333;"><i class="fas fa-truck"></i> 3. Pickup Requested (courier visits seller)</span>
              <i class="fas fa-arrow-right text-muted"></i>
              <span class="badge badge-primary px-3 py-2"><i class="fas fa-shipping-fast"></i> 4. In Transit</span>
              <i class="fas fa-arrow-right text-muted"></i>
              <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i> 5. Delivered</span>
            </div>
            <p class="mb-0 mt-2 text-muted" style="font-size:0.82rem;">
              <strong>For the seller:</strong> Once "Pickup Requested" is shown, the courier will come to the seller address to collect the package.
              The seller must keep the package ready with the printed shipping label attached.
            </p>
          </div>
        </div>

        <div class="card">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Shipping Management (Shiprocket)</b></h5>
              </div>
              <div class="col text-right">
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
              </div>
            </div>
          </div>

          {{-- Search / filter --}}
          <div class="card-body border-bottom py-2">
            <form method="GET" action="{{ route('admin.shiprocket_shipping.index') }}" class="form-inline">
              <input type="text" name="search" class="form-control form-control-sm mr-2"
                placeholder="Order No / AWB" value="{{ request('search') }}">
              <select name="status" class="form-control form-control-sm mr-2">
                <option value="">All Statuses</option>
                @foreach(['NEW','Delivered','In Transit','Out for Delivery','RTO','Pending'] as $s)
                  <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
              </select>
              <button class="btn btn-sm btn-primary mr-1" type="submit"><i class="fas fa-search"></i> Filter</button>
              <a href="{{ route('admin.shiprocket_shipping.index') }}" class="btn btn-sm btn-light">Reset</a>
            </form>
          </div>

          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th>Order No</th>
                  <th>Customer</th>
                  <th style="min-width:150px;">Shiprocket Status</th>
                  <th>AWB / Courier</th>
                  <th>Docs</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($orders as $order)
                <tr>
                  <td>
                    <strong>{{ $order->order_number }}</strong><br>
                    <small class="text-muted">Rs.{{ number_format($order->total_amount, 2) }}</small><br>
                    <small class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y') : '' }}</small>
                  </td>
                  <td>
                    {{ $order->buyer->name ?? 'N/A' }}<br>
                    <small class="text-muted">{{ mb_strimwidth($order->shipping_address ?? '', 0, 55, '...') }}</small>
                  </td>

                  {{-- Status column --}}
                  <td>
                    @php
                      $srOrderId    = $order->shiprocket_order_id    ?? null;
                      $srShipmentId = $order->shiprocket_shipment_id ?? null;
                      $awb          = $order->awb_code               ?? null;
                      $pickupReq    = $order->pickup_requested        ?? false;
                      $trackStatus  = $order->tracking_status         ?? null;
                    @endphp

                    @if(!$srOrderId)
                      <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Not sent to Shiprocket</span>
                    @elseif(!$awb)
                      <span class="badge badge-secondary"><i class="fas fa-clock"></i> Order created &mdash; awaiting AWB</span>
                      <br><small class="text-muted" style="font-size:.75rem;">SR Order: {{ $srOrderId }}</small>
                      <br><small class="text-warning" style="font-size:.72rem;"><i class="fas fa-exclamation-triangle"></i> Use Actions &rarr; Assign AWB</small>
                    @elseif($trackStatus === 'Delivered')
                      <span class="badge badge-success"><i class="fas fa-check-circle"></i> Delivered</span>
                    @elseif(in_array($trackStatus, ['In Transit','Out for Delivery']))
                      <span class="badge badge-primary"><i class="fas fa-shipping-fast"></i> {{ $trackStatus }}</span>
                    @elseif($trackStatus === 'RTO')
                      <span class="badge badge-danger"><i class="fas fa-undo"></i> Returned (RTO)</span>
                    @elseif($pickupReq)
                      <span class="badge badge-info"><i class="fas fa-truck"></i> Pickup Requested</span>
                      <br><small class="text-muted" style="font-size:.75rem;">
                        {{ $order->pickup_requested_at ? $order->pickup_requested_at->format('d M Y, h:i A') : '' }}
                      </small>
                      <br><small class="text-success" style="font-size:.72rem;"><i class="fas fa-box"></i> Courier will collect from seller</small>
                    @else
                      <span class="badge badge-warning" style="color:#333;"><i class="fas fa-barcode"></i> AWB assigned &mdash; pickup pending</span>
                    @endif
                  </td>

                  {{-- AWB + courier --}}
                  <td>
                    @if($awb)
                      <span class="badge badge-info">{{ $awb }}</span><br>
                      <small class="text-muted">{{ $order->courier_name ?? '' }}</small>
                    @else
                      <span class="text-muted">&mdash;</span>
                    @endif
                  </td>

                  {{-- Docs --}}
                  <td>
                    @if($order->shipping_label_url)
                      <a href="{{ $order->shipping_label_url }}" target="_blank" class="btn btn-sm btn-info mb-1">
                        <i class="fas fa-print"></i> Label
                      </a><br>
                    @endif
                    @if($order->shipping_invoice_url)
                      <a href="{{ $order->shipping_invoice_url }}" target="_blank" class="btn btn-sm btn-success mb-1">
                        <i class="fas fa-file-invoice"></i> Invoice
                      </a>
                    @endif
                    @if(!$order->shipping_label_url && !$order->shipping_invoice_url)
                      <span class="text-muted">&mdash;</span>
                    @endif
                  </td>

                  {{-- Actions --}}
                  <td style="min-width:130px;">
                    <div class="d-flex flex-column" style="gap:4px;">

                      @if($srShipmentId && !$awb)
                        <button type="button" class="btn btn-sm btn-warning"
                          onclick="assignAwb({{ $srShipmentId }}, '{{ $order->order_number }}')">
                          <i class="fas fa-barcode"></i> Assign AWB
                        </button>
                      @endif

                      @if(!$pickupReq && $awb && $srShipmentId)
                        <button type="button" class="btn btn-sm btn-success"
                          onclick="requestPickup({{ $srShipmentId }}, '{{ $order->order_number }}')">
                          <i class="fas fa-truck"></i> Request Pickup
                        </button>
                      @endif

                      @if($awb)
                        <button type="button" class="btn btn-sm btn-info"
                          onclick="refreshTracking('{{ $awb }}', '{{ $order->order_number }}')">
                          <i class="fas fa-sync"></i> Refresh
                        </button>
                        <a class="btn btn-sm btn-light"
                          href="https://track.shiprocket.in/awb/{{ $awb }}" target="_blank">
                          <i class="fas fa-external-link-alt"></i> Track
                        </a>
                      @endif

                      @if($order->shipping_label_url)
                        <a class="btn btn-sm btn-info" href="{{ $order->shipping_label_url }}" target="_blank">
                          <i class="fas fa-print"></i> Label
                        </a>
                      @elseif($awb && $srShipmentId)
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                          onclick="generateLabel({{ $srShipmentId }}, '{{ $order->order_number }}')">
                          <i class="fas fa-tag"></i> Get Label
                        </button>
                      @endif

                      @if($order->shipping_invoice_url)
                        <a class="btn btn-sm btn-success" href="{{ $order->shipping_invoice_url }}" target="_blank">
                          <i class="fas fa-file-invoice"></i> Invoice
                        </a>
                      @elseif($srOrderId)
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                          onclick="generateInvoice({{ $srOrderId }}, '{{ $order->order_number }}')">
                          <i class="fas fa-file-invoice"></i> Get Invoice
                        </button>
                      @endif

                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No orders with Shiprocket shipping found.</p>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            {{ $orders->links() }}
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function assignAwb(shipmentId, orderNumber) {
  if (confirm('Assign AWB / select courier for order ' + orderNumber + '?')) {
    fetch('{{ route("admin.shiprocket.assign_awb") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify({ shipment_id: shipmentId, order_number: orderNumber })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) { alert('AWB assigned: ' + data.awb_code); location.reload(); }
      else { alert('Error: ' + data.message); }
    })
    .catch(() => alert('Failed to assign AWB. Please try again.'));
  }
}

function requestPickup(shipmentId, orderNumber) {
  if (confirm('Request pickup for order ' + orderNumber + '?\nThe courier will be scheduled to collect from the seller.')) {
    fetch('{{ route("admin.shiprocket.request_pickup") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify({ shipment_id: shipmentId, order_number: orderNumber })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) { alert('Pickup requested! The courier will visit the seller to collect the package.'); location.reload(); }
      else { alert('Error: ' + data.message); }
    })
    .catch(() => alert('Failed to request pickup. Please try again.'));
  }
}

function refreshTracking(awbCode, orderNumber) {
  if (confirm('Refresh tracking status for order ' + orderNumber + '?')) {
    fetch('{{ route("admin.shiprocket.refresh_tracking") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify({ awb_code: awbCode, order_number: orderNumber })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) { alert('Tracking refreshed! Status: ' + data.data.tracking_status); location.reload(); }
      else { alert('Error: ' + data.message); }
    })
    .catch(() => alert('Failed to refresh tracking. Please try again.'));
  }
}

function generateLabel(shipmentId, orderNumber) {
  if (confirm('Generate shipping label for order ' + orderNumber + '?')) {
    fetch('{{ route("admin.shiprocket.generate_label") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify({ shipment_id: shipmentId, order_number: orderNumber })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) { alert('Label generated!'); location.reload(); }
      else { alert('Error: ' + data.message); }
    })
    .catch(() => alert('Failed to generate label. Please try again.'));
  }
}

function generateInvoice(shiprocketOrderId, orderNumber) {
  if (confirm('Generate invoice for order ' + orderNumber + '?')) {
    fetch('{{ route("admin.shiprocket.generate_invoice") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify({ shiprocket_order_id: shiprocketOrderId, order_number: orderNumber })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) { alert('Invoice generated!'); location.reload(); }
      else { alert('Error: ' + data.message); }
    })
    .catch(() => alert('Failed to generate invoice. Please try again.'));
  }
}
</script>
@endpush
