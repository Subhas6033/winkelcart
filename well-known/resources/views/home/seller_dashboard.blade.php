@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid mt-4">
    <h2 class="mb-4">Seller Dashboard</h2>
    
    @if($isHotelSeller)
    {{-- Hotel Seller Dashboard --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-primary shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-hotel fa-2x mb-2"></i>
                    <h5 class="card-title">My Hotels</h5>
                    <p class="display-4">{{ $hotelCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-success shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="display-4">{{ $bookingCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-info shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-rupee-sign fa-2x mb-2"></i>
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="display-4">₹{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-warning shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h5 class="card-title">Pending Bookings</h5>
                    <p class="display-4">{{ $pendingBookings }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card text-white bg-gradient-success shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h5 class="card-title">Confirmed Bookings</h5>
                    <p class="display-4">{{ $confirmedBookings }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt mr-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.hotels.create') }}" class="btn btn-success mr-2 mb-2">
                        <i class="fas fa-plus mr-1"></i> Add New Hotel
                    </a>
                    <a href="{{ route('admin.hotels.index') }}" class="btn btn-primary mr-2 mb-2">
                        <i class="fas fa-hotel mr-1"></i> Manage Hotels
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-info mr-2 mb-2">
                        <i class="fas fa-calendar-check mr-1"></i> View Bookings
                    </a>
                    <a href="{{ url('admin/sell-report?seller_id=' . Auth::id()) }}" class="btn btn-warning mr-2 mb-2">
                        <i class="fas fa-chart-bar mr-1"></i> Sell Report
                    </a>
                    <a href="{{ route('seller.seller_settlements.index') }}" class="btn btn-dark mr-2 mb-2">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Seller Settlements
                    </a>
                    <a href="{{ route('seller.kyc.edit') }}" class="btn btn-secondary mr-2 mb-2">
                        <i class="fas fa-id-card mr-1"></i> Seller KYC
                    </a>
                    <a href="{{ route('seller.guide.download') }}" class="btn btn-outline-danger mr-2 mb-2">
                        <i class="fas fa-file-pdf mr-1"></i> Seller Guide (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>

    @else
    {{-- Product Seller Dashboard --}}

    {{-- ===== SUBSCRIPTION PLAN BANNER ===== --}}
    @php
        $planIsPro   = $isPro ?? false;
        $planLimit   = $productLimit ?? 50;
        $planUsed    = $productCount ?? 0;
        $planPercent = $planIsPro ? 0 : min(100, round($planUsed / max($planLimit, 1) * 100));
    @endphp
    @if($planIsPro)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #6f42c1, #7950f2); color: #fff; border-radius: 14px;">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
                    <div class="d-flex align-items-center" style="gap: 14px;">
                        <div style="width:52px;height:52px;background:rgba(255,255,255,0.18);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:26px;">
                            ⭐
                        </div>
                        <div>
                            <div style="font-size:13px;opacity:0.85;text-transform:uppercase;letter-spacing:1px;font-weight:600;">Your Current Plan</div>
                            <div style="font-size:22px;font-weight:800;">Pro Seller <span style="background:rgba(255,255,255,0.22);padding:2px 10px;border-radius:20px;font-size:13px;vertical-align:middle;">✔ Verified</span></div>
                            <div style="font-size:13px;opacity:0.85;margin-top:2px;">Unlimited products · Advanced analytics · Featured listings · 7% commission</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <span style="background:rgba(255,255,255,0.2);padding:6px 18px;border-radius:20px;font-size:13px;font-weight:700;">
                            <i class="fas fa-infinity mr-1"></i> Unlimited Products
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 14px; border-left: 5px solid #6c757d;">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
                    <div class="d-flex align-items-center" style="gap: 14px;">
                        <div style="width:52px;height:52px;background:#dee2e6;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:26px;">
                            🆓
                        </div>
                        <div>
                            <div style="font-size:13px;color:#6c757d;text-transform:uppercase;letter-spacing:1px;font-weight:600;">Your Current Plan</div>
                            <div style="font-size:20px;font-weight:800;color:#343a40;">Free Plan</div>
                            <div style="font-size:13px;color:#6c757d;margin-top:2px;">{{ $planUsed }} / {{ $planLimit }} products used · 10% commission</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div class="mb-2">
                            <div class="progress" style="width:200px;height:8px;border-radius:6px;">
                                <div class="progress-bar {{ $planPercent >= 90 ? 'bg-danger' : ($planPercent >= 70 ? 'bg-warning' : 'bg-success') }}"
                                     role="progressbar" style="width:{{ $planPercent }}%" aria-valuenow="{{ $planPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">{{ $planLimit - $planUsed }} product slots remaining</small>
                        </div>
                        <a href="{{ route('seller.upgrade-to-pro') }}" class="btn btn-sm" style="background:linear-gradient(135deg,#6f42c1,#7950f2);color:#fff;font-weight:700;border-radius:20px;padding:6px 18px;">
                            ⚡ Upgrade to Pro — ₹999/yr
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- ===== END SUBSCRIPTION PLAN BANNER ===== --}}

    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-primary shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-box fa-2x mb-2"></i>
                    <h5 class="card-title">My Products</h5>
                    <p class="display-4">{{ $productCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-success shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                    <h5 class="card-title">Total Orders</h5>
                    <p class="display-4">{{ $orderCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-info shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-rupee-sign fa-2x mb-2"></i>
                    <h5 class="card-title">Total Sales</h5>
                    <p class="display-4">₹{{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-gradient-warning shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-wallet fa-2x mb-2"></i>
                    <h5 class="card-title">Amount Received</h5>
                    <p class="display-4">₹{{ number_format($totalReceivedAmount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt mr-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('products.create') }}" class="btn btn-success mr-2 mb-2">
                        <i class="fas fa-plus mr-1"></i> Add New Product
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mr-2 mb-2">
                        <i class="fas fa-box mr-1"></i> Manage Products
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-info mr-2 mb-2">
                        <i class="fas fa-shopping-cart mr-1"></i> View Orders
                    </a>
                    <a href="{{ url('admin/sell-report?seller_id=' . Auth::id()) }}" class="btn btn-warning mr-2 mb-2">
                        <i class="fas fa-chart-bar mr-1"></i> Sell Report
                    </a>
                    <a href="{{ route('seller.seller_settlements.index') }}" class="btn btn-dark mr-2 mb-2">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Seller Settlements
                    </a>
                    <a href="{{ route('seller.kyc.edit') }}" class="btn btn-secondary mr-2 mb-2">
                        <i class="fas fa-id-card mr-1"></i> Seller KYC
                    </a>
                    <a href="{{ route('seller.guide.download') }}" class="btn btn-outline-danger mr-2 mb-2">
                        <i class="fas fa-file-pdf mr-1"></i> Seller Guide (PDF)
                    </a>
                    @if(!($isPro ?? false))
                    <span class="btn btn-outline-secondary mr-2 mb-2 disabled" title="Available on Pro Plan">
                        <i class="fas fa-lock mr-1"></i> Featured Listings <span class="badge badge-warning ml-1">Pro</span>
                    </span>
                    <span class="btn btn-outline-secondary mr-2 mb-2 disabled" title="Available on Pro Plan">
                        <i class="fas fa-bullhorn mr-1"></i> Advertising Tools <span class="badge badge-warning ml-1">Pro</span>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @php
      $seller = Auth::user()->loadMissing('kycVerification');
      $sellerAddress = optional($seller->user_info)->full_address ?? optional($seller->user_info)->address;
      $kyc = $seller->kycVerification;
      $kycStatus = optional($kyc)->status ?? 'Not Submitted';
      $kycBadgeClass = 'badge-secondary';
      $kycStatusText = 'Please submit your KYC details to start selling without interruptions.';

      if ($kycStatus === 'Pending') {
          $kycBadgeClass = 'badge-warning';
          $kycStatusText = 'Your KYC is under admin review. You will be able to sell after verification.';
      } elseif ($kycStatus === 'Verified') {
          $kycBadgeClass = 'badge-success';
          $kycStatusText = 'Your KYC is fully verified. Selling actions are enabled.';
      } elseif ($kycStatus === 'Rejected') {
          $kycBadgeClass = 'badge-danger';
          $kycStatusText = 'Your KYC was rejected. Please review admin feedback and re-submit.';
      }
    @endphp

    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-id-card mr-2"></i>My KYC Status</h5>
                    <span class="badge {{ $kycBadgeClass }}">{{ $kycStatus }}</span>
                </div>
                <div class="card-body">
                    <p class="mb-2">{{ $kycStatusText }}</p>

                    @if($kyc && $kyc->verified_at)
                        <p class="mb-2 text-muted">
                            Last Verified At: {{ $kyc->verified_at->format('d M Y H:i') }}
                        </p>
                    @endif

                    @if($kyc && $kyc->admin_note)
                        <div class="alert alert-light border mb-3">
                            <small class="text-muted d-block">Admin Feedback</small>
                            <span>{{ \Illuminate\Support\Str::limit($kyc->admin_note, 180) }}</span>
                        </div>
                    @endif

                    <a href="{{ route('seller.kyc.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-pen mr-1"></i>
                        {{ $kycStatus === 'Rejected' ? 'Re-submit KYC' : 'Manage KYC Details' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-edit mr-2"></i>Edit Profile</h5>
                    <a href="{{ route('seller.profile.edit') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-pen mr-1"></i> Edit Now
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Name</small>
                            <strong>{{ $seller->name }}</strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ $seller->email }}</strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Mobile</small>
                            <strong>{{ $seller->mobile ?: 'Not set' }}</strong>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block">Primary Address</small>
                        <span>{{ $sellerAddress ?: 'No address added yet' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SHIPROCKET SHIPPING SECTION (Product Sellers only) ===== --}}
    @if(!$isHotelSeller)
    @php
      $pickupSynced   = optional($sellerKyc ?? null)->pickup_sync_status === 'synced';
      $pickupName     = optional($sellerKyc ?? null)->shiprocket_pickup_location;
      $pickupAddress  = trim(implode(', ', array_filter([
          optional($sellerKyc ?? null)->pickup_address,
          optional($sellerKyc ?? null)->pickup_city,
          optional($sellerKyc ?? null)->pickup_state,
          optional($sellerKyc ?? null)->pickup_pincode,
      ])));
      $pickupPhone    = optional($sellerKyc ?? null)->pickup_phone ?? Auth::user()->mobile;
    @endphp

    {{-- Pickup Address Card --}}
    <div class="row mt-3">
      <div class="col-12">
        <div class="card shadow border-left-{{ $pickupSynced ? 'success' : 'warning' }}">
          <div class="card-header bg-{{ $pickupSynced ? 'success' : 'warning' }} text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Shiprocket Pickup Address</h5>
            @if($pickupSynced)
              <span class="badge badge-light text-success"><i class="fas fa-check-circle mr-1"></i> Registered in Shiprocket</span>
            @else
              <span class="badge badge-light text-warning"><i class="fas fa-exclamation-triangle mr-1"></i> Not Verified</span>
            @endif
          </div>
          <div class="card-body">
            @if($pickupName)
              <div class="row mb-3">
                <div class="col-md-4">
                  <small class="text-muted d-block">Pickup Location Name</small>
                  <strong>{{ $pickupName }}</strong>
                </div>
                <div class="col-md-5">
                  <small class="text-muted d-block">Address</small>
                  <strong>{{ $pickupAddress ?: '—' }}</strong>
                </div>
                <div class="col-md-3">
                  <small class="text-muted d-block">Phone (for OTP)</small>
                  <strong>{{ $pickupPhone ?: '—' }}</strong>
                </div>
              </div>
              @if(!$pickupSynced)
                <div class="alert alert-warning mb-3">
                  <i class="fas fa-exclamation-triangle mr-2"></i>
                  <strong>Action Required:</strong> Shiprocket requires you to <strong>verify this pickup address by OTP</strong> on their website before they can ship your orders.
                  <ol class="mb-0 mt-2">
                    <li>Go to <a href="https://app.shiprocket.in/seller/settings/pickup-addresses" target="_blank"><strong>Shiprocket → Settings → Pickup Addresses</strong></a></li>
                    <li>Find <strong>"{{ $pickupName }}"</strong> and click <strong>Verify Now</strong></li>
                    <li>Enter the OTP sent to <strong>{{ $pickupPhone ?: 'your registered phone' }}</strong></li>
                  </ol>
                </div>
              @else
                <div class="alert alert-success mb-0 py-2">
                  <i class="fas fa-check-circle mr-1"></i> Pickup address is verified and active in Shiprocket. Couriers will visit this address to collect packages.
                </div>
              @endif
            @else
              <div class="alert alert-danger mb-2">
                <i class="fas fa-times-circle mr-2"></i> No pickup address registered yet. Please complete your <a href="{{ route('seller.kyc.edit') }}"><strong>Seller KYC</strong></a> with a pickup address, then contact admin to sync it with Shiprocket.
              </div>
            @endif
            <a href="https://app.shiprocket.in/seller/settings/pickup-addresses" target="_blank" class="btn btn-outline-primary btn-sm">
              <i class="fas fa-external-link-alt mr-1"></i> Manage Pickup Addresses on Shiprocket
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Shiprocket Shipments Table --}}
    <div class="row mt-3">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-shipping-fast mr-2"></i>Shipping Management (Shiprocket)</h5>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="srFilterOrderNo" class="small text-muted">Filter by Order / AWB</label>
                  <input type="text" id="srFilterOrderNo" class="form-control form-control-sm" placeholder="Order No / AWB Code">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="srFilterStatus" class="small text-muted">Filter by Status</label>
                  <select id="srFilterStatus" class="form-control form-control-sm">
                    <option value="">All Statuses</option>
                    <option value="ORDER CREATED - AWAITING AWB">Order Created - Awaiting AWB</option>
                    <option value="Label Created">Label Created</option>
                    <option value="Pickup Scheduled">Pickup Scheduled</option>
                    <option value="In Transit">In Transit</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-outline-secondary btn-sm w-100" onclick="location.reload()">
                  <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Shiprocket Shipments Table --}}
    <div class="row mt-3">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Orders Pushed to Shiprocket</h5>
            <small class="text-white-50">Last 20 orders</small>
          </div>
          <div class="card-body p-0">
            @if(isset($shiprocketOrders) && $shiprocketOrders->count() > 0)
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0" id="srShipmentsTable">
                <thead class="thead-light">
                  <tr>
                    <th>Order No</th>
                    <th>Date</th>
                    <th>SR Order ID</th>
                    <th>AWB Code</th>
                    <th>Courier</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="srShipmentsBody">
                  @foreach($shiprocketOrders as $sOrder)
                  @php
                    $sAwb     = $sOrder->awb_code;
                    $sStatus  = $sOrder->tracking_status ?? 'Processing';
                    $sBadge   = 'secondary';
                    if (in_array($sStatus, ['Delivered'])) $sBadge = 'success';
                    elseif (in_array($sStatus, ['In Transit', 'Label Created'])) $sBadge = 'info';
                    elseif (in_array($sStatus, ['Pickup Scheduled', 'Pickup Requested'])) $sBadge = 'warning';
                    elseif (str_contains(strtolower($sStatus ?? ''), 'cancel')) $sBadge = 'danger';
                  @endphp
                  <tr>
                    <td><strong>{{ $sOrder->order_number }}</strong></td>
                    <td><small>{{ $sOrder->created_at->format('d M Y') }}</small></td>
                    <td><small class="text-muted">{{ $sOrder->shiprocket_order_id ?? '—' }}</small></td>
                    <td>
                      @if($sAwb)
                        <code style="background:#f5f5f5;padding:3px 6px;border-radius:3px;">{{ $sAwb }}</code>
                      @else
                        <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Awaiting AWB</span>
                      @endif
                    </td>
                    <td><small>{{ $sOrder->courier_name ?? '—' }}</small></td>
                    <td><span class="badge badge-{{ $sBadge }}">{{ $sStatus }}</span></td>
                    <td>
                      <div class="btn-group btn-group-sm" role="group">
                        @if($sAwb)
                          <a href="https://track.shiprocket.in/awb/{{ $sAwb }}" target="_blank"
                             class="btn btn-outline-info" title="Track on Shiprocket">
                            <i class="fas fa-map-marker-alt"></i> Track
                          </a>
                        @endif
                        @if($sOrder->shipping_label_url)
                          <a href="{{ $sOrder->shipping_label_url }}" target="_blank"
                             class="btn btn-outline-secondary" title="Download label">
                            <i class="fas fa-print"></i> Label
                          </a>
                        @endif
                        <a href="{{ route('orders.show', $sOrder->id) }}"
                           class="btn btn-outline-primary" title="View order">
                          <i class="fas fa-eye"></i> View
                        </a>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
              <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                <p class="mb-0">No orders pushed to Shiprocket yet.</p>
                <small>Orders will appear here once they're assigned to Shiprocket for shipment.</small>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <script>
    // Filter Shiprocket orders by order number or status
    document.getElementById('srFilterOrderNo').addEventListener('keyup', filterShiprocketTable);
    document.getElementById('srFilterStatus').addEventListener('change', filterShiprocketTable);

    function filterShiprocketTable() {
      const orderFilter = document.getElementById('srFilterOrderNo').value.toUpperCase();
      const statusFilter = document.getElementById('srFilterStatus').value;
      const table = document.getElementById('srShipmentsTable');
      const rows = document.querySelectorAll('#srShipmentsBody tr');

      rows.forEach(row => {
        const orderNo = row.cells[0].textContent.toUpperCase();
        const awbCode = row.cells[3].textContent.toUpperCase();
        const status = row.cells[5].textContent;
        
        const orderMatch = orderNo.includes(orderFilter) || awbCode.includes(orderFilter);
        const statusMatch = !statusFilter || status.includes(statusFilter);

        row.style.display = (orderMatch && statusMatch) ? '' : 'none';
      });
    }
    </script>

    {{-- Shiprocket Shipments Table (old) --}}
    <div class="row mt-3">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-shipping-fast mr-2"></i>My Shipments (Shiprocket)</h5>
            <small class="text-white-50">Last 10 orders pushed to Shiprocket</small>
          </div>
          <div class="card-body p-0">
            @if(isset($shiprocketOrders) && $shiprocketOrders->count() > 0)
            <div class="table-responsive">
              <table class="table table-sm table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Order No</th>
                    <th>Date</th>
                    <th>SR Order ID</th>
                    <th>AWB Code</th>
                    <th>Courier</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($shiprocketOrders as $sOrder)
                  @php
                    $sAwb     = $sOrder->awb_code;
                    $sStatus  = $sOrder->tracking_status ?? 'Processing';
                    $sBadge   = 'secondary';
                    if (in_array($sStatus, ['Delivered'])) $sBadge = 'success';
                    elseif (in_array($sStatus, ['In Transit', 'Label Created'])) $sBadge = 'info';
                    elseif (in_array($sStatus, ['Pickup Scheduled', 'Pickup Requested'])) $sBadge = 'warning';
                    elseif (str_contains(strtolower($sStatus ?? ''), 'cancel')) $sBadge = 'danger';
                  @endphp
                  <tr>
                    <td><strong>{{ $sOrder->order_number }}</strong></td>
                    <td><small>{{ $sOrder->created_at->format('d M Y') }}</small></td>
                    <td><small class="text-muted">{{ $sOrder->shiprocket_order_id ?? '—' }}</small></td>
                    <td>
                      @if($sAwb)
                        <code>{{ $sAwb }}</code>
                      @else
                        <span class="text-warning"><i class="fas fa-clock"></i> Pending</span>
                      @endif
                    </td>
                    <td><small>{{ $sOrder->courier_name ?? '—' }}</small></td>
                    <td><span class="badge badge-{{ $sBadge }}">{{ $sStatus }}</span></td>
                    <td>
                      @if($sAwb)
                        <a href="https://track.shiprocket.in/awb/{{ $sAwb }}" target="_blank"
                           class="btn btn-xs btn-outline-info" style="font-size:11px;padding:2px 7px;">
                          <i class="fas fa-search"></i> Track
                        </a>
                      @elseif(!$sOrder->awb_code && $sOrder->shiprocket_shipment_id)
                        <span class="text-muted" style="font-size:11px;">AWB not yet assigned</span>
                      @endif
                      @if($sOrder->shipping_label_url)
                        <a href="{{ $sOrder->shipping_label_url }}" target="_blank"
                           class="btn btn-xs btn-outline-secondary ml-1" style="font-size:11px;padding:2px 7px;">
                          <i class="fas fa-print"></i> Label
                        </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
              <div class="text-center py-4 text-muted">
                <i class="fas fa-box fa-2x mb-2 d-block"></i>
                No shipments pushed to Shiprocket yet.
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Delivery Address Note --}}
    <div class="row mt-3">
      <div class="col-12">
        <div class="card shadow border-left-info">
          <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-map-pin mr-2"></i>How Delivery Addresses Work</h5>
          </div>
          <div class="card-body">
            <p class="mb-2">When a customer places an order, their <strong>registered delivery address</strong> (city, state, pincode) is automatically sent to Shiprocket as the destination address. No action is needed from you for the delivery address.</p>
            <p class="mb-0 text-muted"><i class="fas fa-info-circle mr-1"></i> If a customer has an incomplete address (missing pincode or city), the AWB assignment may fail. Customers can update their address from their profile page.</p>
          </div>
        </div>
      </div>
    </div>
    @endif
    {{-- ===== END SHIPROCKET SECTION ===== --}}

    {{-- Seller Guide Download --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow border-left-primary">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                    <div class="mb-2 mb-md-0">
                        <h5 class="mb-1"><i class="fas fa-book-open text-primary mr-2"></i>Seller Guide &amp; Tax Information</h5>
                        <p class="text-muted mb-0">Download the complete seller handbook covering how to sell, tax (GST) details, commission structure, shipping guidelines, and legal disclaimer.</p>
                    </div>
                    <a href="{{ route('seller.guide.download') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-file-pdf mr-1"></i> Download Seller Guide (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>

  </div>
</div>
@endsection
