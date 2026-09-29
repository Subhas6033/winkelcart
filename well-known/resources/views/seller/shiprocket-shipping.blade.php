@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid mt-4">
    <h2 class="mb-4"><i class="fas fa-shipping-fast mr-2"></i>Shiprocket Shipping Management</h2>

    {{-- Shiprocket Pickup Process Timeline --}}
    <div class="row mb-4">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>How Shiprocket Delivery Works</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="timeline" style="display: flex; justify-content: space-between; margin: 20px 0;">
                  <div style="text-align: center; flex: 1;">
                    <div style="background: #28a745; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 10px; border: 3px solid #20c997;">
                      ✓
                    </div>
                    <strong>1. Order Created</strong>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">Order received & pushed to Shiprocket</p>
                  </div>
                  <div style="display: flex; align-items: center; color: #ddd; font-size: 24px;">→</div>
                  <div style="text-align: center; flex: 1;">
                    <div style="background: #17a2b8; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 10px; border: 3px solid #117a8b;">
                      🏢
                    </div>
                    <strong>2. AWB Assigned</strong>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">Courier assigned & label created</p>
                  </div>
                  <div style="display: flex; align-items: center; color: #ddd; font-size: 24px;">→</div>
                  <div style="text-align: center; flex: 1;">
                    <div style="background: #ffc107; color: #333; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 10px; border: 3px solid #e0a800;">
                      🚗
                    </div>
                    <strong>3. Pickup Scheduled</strong>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">Courier will visit your pickup address</p>
                  </div>
                  <div style="display: flex; align-items: center; color: #ddd; font-size: 24px;">→</div>
                  <div style="text-align: center; flex: 1;">
                    <div style="background: #007bff; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 10px; border: 3px solid #0056b3;">
                      📦
                    </div>
                    <strong>4. In Transit</strong>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">Package on the way to customer</p>
                  </div>
                  <div style="display: flex; align-items: center; color: #ddd; font-size: 24px;">→</div>
                  <div style="text-align: center; flex: 1;">
                    <div style="background: #28a745; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 10px; border: 3px solid #1e7e34;">
                      ✔️
                    </div>
                    <strong>5. Delivered</strong>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">Package reached customer</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Filter Section --}}
    <div class="row mb-3">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="filterOrderNo" class="small text-muted">Filter by Order No / AWB Code</label>
                  <input type="text" id="filterOrderNo" class="form-control form-control-sm" placeholder="e.g., JMG00IHXXZ or SR12345678">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="filterStatus" class="small text-muted">Filter by Status</label>
                  <select id="filterStatus" class="form-control form-control-sm">
                    <option value="">All Statuses</option>
                    <option value="ORDER CREATED">Order Created - Awaiting AWB</option>
                    <option value="Label Created">Label Created</option>
                    <option value="Pickup">Pickup Requested / Scheduled</option>
                    <option value="In Transit">In Transit</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary btn-sm w-100" onclick="location.reload()">
                  <i class="fas fa-sync-alt mr-1"></i> Refresh Orders
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Shipments Table --}}
    <div class="row">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Your Shipments</h5>
          </div>
          <div class="card-body p-0">
            @if(isset($shiprocketOrders) && $shiprocketOrders->count() > 0)
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0" id="shiprocketTable">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 15%;">Order No</th>
                    <th style="width: 10%;">Order Date</th>
                    <th style="width: 12%;">AWB Code</th>
                    <th style="width: 15%;">Courier</th>
                    <th style="width: 18%;">Current Status</th>
                    <th style="width: 20%;">Pickup Schedule</th>
                    <th style="width: 10%;">Actions</th>
                  </tr>
                </thead>
                <tbody id="shiprocketTableBody">
                  @foreach($shiprocketOrders as $order)
                  @php
                    $status = $order->tracking_status ?? 'Awaiting AWB';
                    $badgeClass = 'secondary';
                    $icon = '⏳';

                    if (str_contains(strtolower($status), 'delivered')) {
                      $badgeClass = 'success';
                      $icon = '✔️';
                    } elseif (str_contains(strtolower($status), 'in transit') || str_contains(strtolower($status), 'label created')) {
                      $badgeClass = 'info';
                      $icon = '📦';
                    } elseif (str_contains(strtolower($status), 'pickup')) {
                      $badgeClass = 'warning';
                      $icon = '🚗';
                    } elseif (str_contains(strtolower($status), 'cancel')) {
                      $badgeClass = 'danger';
                      $icon = '❌';
                    } elseif (str_contains(strtolower($status), 'order created') || str_contains(strtolower($status), 'awaiting')) {
                      $badgeClass = 'secondary';
                      $icon = '⏳';
                    }

                    $pickupScheduled = $order->pickup_scheduled_date ?? null;
                    $pickupDateFormatted = $pickupScheduled ? \Carbon\Carbon::parse($pickupScheduled)->format('d M Y, h:i A') : 'Pending';
                  @endphp
                  <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                      @if($order->awb_code)
                        <code style="background: #f5f5f5; padding: 3px 6px; border-radius: 3px;">{{ $order->awb_code }}</code>
                      @else
                        <span class="badge badge-secondary">Not Assigned</span>
                      @endif
                    </td>
                    <td>
                      @if($order->courier_name)
                        <strong>{{ $order->courier_name }}</strong>
                        @if($order->tracking_id)
                          <br><small class="text-muted">ID: {{ $order->tracking_id }}</small>
                        @endif
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td>
                      <span class="badge badge-{{ $badgeClass }}" style="font-size: 12px; padding: 5px 8px;">
                        {{ $icon }} {{ $status }}
                      </span>
                    </td>
                    <td>
                      @if(str_contains(strtolower($status), 'pickup'))
                        <div style="font-size: 12px;">
                          <strong style="color: #ffc107;">{{ $pickupDateFormatted }}</strong>
                          <br><small class="text-muted">Keep package ready for pickup</small>
                        </div>
                      @elseif(str_contains(strtolower($status), 'label created'))
                        <div style="font-size: 12px;">
                          <strong style="color: #ffc107;">Coming Soon</strong>
                          <br><small class="text-muted">Awaiting pickup schedule</small>
                        </div>
                      @elseif(str_contains(strtolower($status), 'in transit'))
                        <div style="font-size: 12px;">
                          <strong style="color: #17a2b8;">On the Way</strong>
                          <br><small class="text-muted">Customer will receive soon</small>
                        </div>
                      @elseif(str_contains(strtolower($status), 'delivered'))
                        <div style="font-size: 12px;">
                          <strong style="color: #28a745;">Completed</strong>
                          <br><small class="text-muted">Delivered to customer</small>
                        </div>
                      @else
                        <div style="font-size: 12px;">
                          <span class="text-muted">Pending</span>
                        </div>
                      @endif
                    </td>
                    <td>
                      <div class="btn-group btn-group-sm" role="group">
                        @if($order->awb_code)
                          <a href="https://track.shiprocket.in/awb/{{ $order->awb_code }}" target="_blank"
                             class="btn btn-outline-info" title="Track on Shiprocket">
                            <i class="fas fa-map-marker-alt"></i> Track
                          </a>
                        @endif
                        @if($order->shipping_label_url)
                          <a href="{{ $order->shipping_label_url }}" target="_blank"
                             class="btn btn-outline-secondary" title="Download shipping label">
                            <i class="fas fa-print"></i> Label
                          </a>
                        @else
                          <button class="btn btn-outline-secondary disabled" title="Label not generated yet">
                            <i class="fas fa-print"></i> Label
                          </button>
                        @endif
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
              <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                <p class="mb-0"><strong>No shipments yet</strong></p>
                <small>Once you push orders to Shiprocket, they will appear here</small>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Quick Info Cards --}}
    <div class="row mt-4">
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-left-info">
          <div class="card-body">
            <div class="text-info font-weight-bold" style="font-size: 24px;">
              {{ $shiprocketOrders ? $shiprocketOrders->count() : 0 }}
            </div>
            <small class="text-muted">Total Shipments</small>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-left-warning">
          <div class="card-body">
            <div class="text-warning font-weight-bold" style="font-size: 24px;">
              {{ $shiprocketOrders ? $shiprocketOrders->whereIn('tracking_status', ['Pickup Requested', 'Pickup Scheduled', 'Label Created'])->count() : 0 }}
            </div>
            <small class="text-muted">Awaiting Pickup</small>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-left-primary">
          <div class="card-body">
            <div class="text-primary font-weight-bold" style="font-size: 24px;">
              {{ $shiprocketOrders ? $shiprocketOrders->where('tracking_status', 'In Transit')->count() : 0 }}
            </div>
            <small class="text-muted">In Transit</small>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-left-success">
          <div class="card-body">
            <div class="text-success font-weight-bold" style="font-size: 24px;">
              {{ $shiprocketOrders ? $shiprocketOrders->where('tracking_status', 'Delivered')->count() : 0 }}
            </div>
            <small class="text-muted">Delivered</small>
          </div>
        </div>
      </div>
    </div>

    {{-- Pickup Address Card --}}
    <div class="row mt-4">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Your Pickup Address</h5>
          </div>
          <div class="card-body">
            @php
              $kyc = Auth::user()->kycVerification;
              $pickupAddress = $kyc ? trim(implode(', ', array_filter([
                  $kyc->pickup_address ?? null,
                  $kyc->pickup_city ?? null,
                  $kyc->pickup_state ?? null,
                  $kyc->pickup_pincode ?? null,
              ]))) : null;
            @endphp
            @if($pickupAddress)
              <div class="row">
                <div class="col-md-8">
                  <p class="mb-2"><strong>{{ $kyc->shiprocket_pickup_location ?? 'Pickup Location' }}</strong></p>
                  <p class="mb-2">{{ $pickupAddress }}</p>
                  <p class="mb-0"><strong>Phone:</strong> {{ $kyc->pickup_phone ?? Auth::user()->mobile }}</p>
                </div>
                <div class="col-md-4 text-right">
                  <a href="{{ route('seller.kyc.edit') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-edit mr-1"></i> Update Address
                  </a>
                </div>
              </div>
              <div class="alert alert-info mt-3 mb-0">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Important:</strong> Couriers will pick up packages from this address. Ensure someone is available during pickup hours.
              </div>
            @else
              <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>No pickup address found.</strong> Please add your pickup address in <a href="{{ route('seller.kyc.edit') }}">Seller KYC</a> first.
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
// Real-time filtering
document.getElementById('filterOrderNo').addEventListener('keyup', filterTable);
document.getElementById('filterStatus').addEventListener('change', filterTable);

function filterTable() {
  const orderFilter = document.getElementById('filterOrderNo').value.toUpperCase();
  const statusFilter = document.getElementById('filterStatus').value;
  const table = document.getElementById('shiprocketTable');
  const rows = document.querySelectorAll('#shiprocketTableBody tr');

  rows.forEach(row => {
    const orderNo = row.cells[0].textContent.toUpperCase();
    const awbCode = row.cells[2].textContent.toUpperCase();
    const status = row.cells[4].textContent;

    const orderMatch = orderNo.includes(orderFilter) || awbCode.includes(orderFilter);
    const statusMatch = !statusFilter || status.includes(statusFilter);

    row.style.display = (orderMatch && statusMatch) ? '' : 'none';
  });
}
</script>
@endsection
