@extends('front.layouts.app')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
  :root {
    --green:       #2d6a4f;
    --green-mid:   #40916c;
    --green-light: #74c69d;
    --green-pale:  #d8f3dc;
    --gold:        #f4a261;
    --dark:        #1a1a2e;
    --ink:         #2b2d42;
    --muted:       #8d99ae;
    --surface:     #f8faf8;
    --white:       #ffffff;
    --danger:      #e63946;
    --blue:        #1565c0;
    --warning:     #ff9800;
    --orange:      #e76f51;
  }

  body {
    font-family: 'DM Sans', 'Poppins', sans-serif;
    background: var(--surface) !important;
    color: var(--ink);
  }

  /* Hero Banner */
  .track-hero {
    background: linear-gradient(135deg, var(--dark) 0%, #16213e 60%, var(--green) 140%);
    padding: 50px 40px;
    position: relative;
    overflow: hidden;
  }

  .track-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      radial-gradient(circle at 20% 50%, rgba(116,198,157,.12) 0%, transparent 55%),
      radial-gradient(circle at 80% 20%, rgba(244,162,97,.08) 0%, transparent 50%);
    pointer-events: none;
  }

  .hero-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    position: relative;
    z-index: 1;
  }

  .hero-left {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .hero-icon {
    font-size: 60px;
    filter: drop-shadow(0 8px 20px rgba(116,198,157,.4));
  }

  .hero-text h1 {
    font-family: 'Playfair Display', serif;
    font-size: 34px;
    font-weight: 900;
    color: var(--white);
    margin-bottom: 6px;
  }
  .hero-text p { font-size: 15px; color: rgba(255,255,255,.55); }
  .hero-text .order-num { color: var(--gold); font-weight: 700; }

  .btn-track-another {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(10px);
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s;
    border: 1px solid rgba(255,255,255,.2);
  }

  .btn-track-another:hover {
    background: rgba(255,255,255,.25);
    color: white;
    transform: translateY(-2px);
  }

  /* Page Body */
  .page-body {
    max-width: 1100px;
    margin: 0 auto;
    padding: 40px 24px 70px;
  }

  /* Order Status Badge */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .status-pending {
    background: linear-gradient(135deg, #fff3cd, #ffeeba);
    color: #856404;
  }

  .status-placed {
    background: linear-gradient(135deg, #fff3cd, #ffeeba);
    color: #856404;
  }

  .status-processing {
    background: linear-gradient(135deg, #cce5ff, #b8daff);
    color: #004085;
  }

  .status-shipped {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
  }

  .status-done, .status-delivered {
    background: linear-gradient(135deg, var(--green-pale), #b7e4c7);
    color: var(--green);
  }

  .status-cancelled {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: var(--danger);
  }

  /* Tracking Timeline */
  .tracking-card {
    background: var(--white);
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 4px 20px rgba(45,106,79,.06);
    border: 1px solid #e8f0e8;
    margin-bottom: 30px;
  }

  .tracking-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    color: var(--ink);
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    padding: 0 20px;
  }

  .timeline::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 60px;
    right: 60px;
    height: 4px;
    background: #e8f0e8;
    border-radius: 2px;
  }

  .timeline-progress {
    position: absolute;
    top: 30px;
    left: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--green), var(--green-mid));
    border-radius: 2px;
    transition: width 0.5s ease;
  }

  .timeline-progress.progress-step-0 { width: 0%; }
  .timeline-progress.progress-step-1 { width: 33.33%; }
  .timeline-progress.progress-step-2 { width: 66.66%; }
  .timeline-progress.progress-step-3 { width: 100%; }

  .timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
  }

  .step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: #e8f0e8;
    color: var(--muted);
    border: 4px solid var(--white);
    box-shadow: 0 4px 15px rgba(0,0,0,.1);
    transition: all 0.3s;
    margin-bottom: 14px;
  }

  .timeline-step.active .step-icon,
  .timeline-step.completed .step-icon {
    background: linear-gradient(135deg, var(--green), var(--green-mid));
    color: white;
  }

  .timeline-step.cancelled .step-icon {
    background: linear-gradient(135deg, var(--danger), #ff6b6b);
    color: white;
  }

  .step-title {
    font-weight: 700;
    font-size: 14px;
    color: var(--muted);
    text-align: center;
    margin-bottom: 4px;
  }

  .timeline-step.active .step-title,
  .timeline-step.completed .step-title {
    color: var(--green);
  }

  .timeline-step.cancelled .step-title {
    color: var(--danger);
  }

  .step-date {
    font-size: 12px;
    color: var(--muted);
  }

  /* Order Details Card */
  .details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
  }

  .detail-card {
    background: var(--white);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(45,106,79,.06);
    border: 1px solid #e8f0e8;
  }

  .detail-card h4 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: var(--ink);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .detail-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px dashed #e8f0e8;
  }

  .detail-row:last-child {
    border-bottom: none;
  }

  .detail-label {
    color: var(--muted);
    font-size: 14px;
  }

  .detail-value {
    font-weight: 600;
    color: var(--ink);
    font-size: 14px;
    text-align: right;
  }

  /* Order Items */
  .items-card {
    background: var(--white);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(45,106,79,.06);
    border: 1px solid #e8f0e8;
    margin-top: 24px;
  }

  .items-card h4 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: var(--ink);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .order-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--surface);
    border-radius: 14px;
    margin-bottom: 12px;
    transition: all 0.3s;
  }

  .order-item:hover {
    background: var(--green-pale);
  }

  .order-item:last-child {
    margin-bottom: 0;
  }

  .item-image {
    width: 70px;
    height: 70px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #e8f0e8;
  }

  .item-details {
    flex: 1;
  }

  .item-name {
    font-weight: 700;
    font-size: 15px;
    color: var(--ink);
    margin-bottom: 4px;
  }

  .item-meta {
    font-size: 13px;
    color: var(--muted);
  }

  .item-price {
    font-weight: 700;
    font-size: 16px;
    color: var(--green);
    text-align: right;
  }

  /* Total Row */
  .total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, var(--green-pale), #e8f5e8);
    border-radius: 14px;
    margin-top: 20px;
  }

  .total-label {
    font-weight: 700;
    font-size: 16px;
    color: var(--ink);
  }

  .total-value {
    font-family: 'Playfair Display', serif;
    font-weight: 900;
    font-size: 24px;
    color: var(--green);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .track-hero { padding: 30px 20px; }
    .hero-content { flex-direction: column; text-align: center; }
    .hero-left { flex-direction: column; }
    .hero-text h1 { font-size: 26px; }
    .page-body { padding: 30px 16px 50px; }
    
    .timeline { flex-direction: column; gap: 20px; padding: 0; }
    .timeline::before { display: none; }
    .timeline-progress { display: none; }
    .timeline-step { flex-direction: row; gap: 16px; }
    .step-icon { width: 50px; height: 50px; font-size: 20px; margin-bottom: 0; }
    
    .details-grid { grid-template-columns: 1fr; }
    .order-item { flex-direction: column; text-align: center; }
    .item-price { margin-top: 10px; }
  }
</style>

@php
  $timelineCollection = collect($order->timelines ?? [])->sortBy('id');
  $latestTimelineStatus = optional($timelineCollection->last())->status;

  $rawStatus = strtolower($latestTimelineStatus ?? ($order->order_status ?? 'pending'));
  $statusMap = [
    'pending' => 'placed',
    'placed' => 'placed',
    'processing' => 'processing',
    'shipped' => 'shipped',
    'delivered' => 'delivered',
    'done' => 'delivered',
    'cancelled' => 'cancelled',
  ];

  $orderStatus = $statusMap[$rawStatus] ?? 'placed';
  $isCancelled = ($orderStatus === 'cancelled');

  $steps = ['placed', 'processing', 'shipped', 'delivered'];
  $currentStep = array_search($orderStatus, $steps);
  if ($currentStep === false) {
    $currentStep = 0;
  }

  $timelineByStatus = $timelineCollection->keyBy(function ($timeline) {
    return strtolower($timeline->status);
  });

  $placedDate = optional($timelineByStatus->get('placed'))->created_at ?? $order->created_at;
  $processingDate = optional($timelineByStatus->get('processing'))->created_at;
  $shippedDate = optional($timelineByStatus->get('shipped'))->created_at;
  $deliveredDate = optional($timelineByStatus->get('delivered'))->created_at;
  $cancelledDate = optional($timelineByStatus->get('cancelled'))->created_at;

  $progressClass = 'progress-step-' . $currentStep;
@endphp

<!-- Hero Section -->
<section class="track-hero">
  <div class="hero-content">
    <div class="hero-left">
      <div class="hero-icon">📦</div>
      <div class="hero-text">
        <h1>Order Tracking</h1>
        <p>Order Number: <span class="order-num">{{ $order->order_number }}</span></p>
      </div>
    </div>
    <a href="{{ route('track_order') }}" class="btn-track-another">
      <i class="fas fa-search"></i> Track Another Order
    </a>
  </div>
</section>

<div class="page-body">
  
  <!-- Order Status -->
  <div class="tracking-card">
    <h3>
      <i class="fas fa-route"></i> Tracking Status
      <span class="status-badge status-{{ $orderStatus }}">
        @if($isCancelled)
          <i class="fas fa-times-circle"></i> Cancelled
        @elseif($orderStatus === 'delivered')
          <i class="fas fa-check-circle"></i> Delivered
        @elseif($orderStatus === 'shipped')
          <i class="fas fa-shipping-fast"></i> Shipped
        @elseif($orderStatus === 'processing')
          <i class="fas fa-cog"></i> Processing
        @else
          <i class="fas fa-clock"></i> Placed
        @endif
      </span>
    </h3>

    @if($isCancelled)
      <div style="background: #ffeaea; padding: 20px; border-radius: 14px; text-align: center; color: var(--danger);">
        <i class="fas fa-times-circle fa-2x mb-2"></i>
        <p style="margin: 10px 0 0; font-weight: 600;">This order has been cancelled{{ $cancelledDate ? ' on ' . $cancelledDate->format('d M Y, h:i A') : '' }}.</p>
      </div>
    @else
      <div class="timeline">
        <div class="timeline-progress {{ $progressClass }}"></div>
        
        <div class="timeline-step {{ $currentStep >= 0 ? 'completed' : '' }}">
          <div class="step-icon"><i class="fas fa-clipboard-list"></i></div>
          <span class="step-title">Order Placed</span>
          <span class="step-date">{{ $placedDate ? $placedDate->format('d M Y') : 'Pending' }}</span>
        </div>
        
        <div class="timeline-step {{ $currentStep >= 1 ? 'completed' : '' }}">
          <div class="step-icon"><i class="fas fa-box-open"></i></div>
          <span class="step-title">Processing</span>
          <span class="step-date">{{ $currentStep >= 1 ? ($processingDate ? $processingDate->format('d M Y') : 'In Progress') : 'Pending' }}</span>
        </div>
        
        <div class="timeline-step {{ $currentStep >= 2 ? 'completed' : '' }}">
          <div class="step-icon"><i class="fas fa-shipping-fast"></i></div>
          <span class="step-title">Shipped</span>
          <span class="step-date">{{ $currentStep >= 2 ? ($shippedDate ? $shippedDate->format('d M Y') : 'On the way') : 'Pending' }}</span>
        </div>
        
        <div class="timeline-step {{ $currentStep >= 3 ? 'completed' : '' }}">
          <div class="step-icon"><i class="fas fa-check-circle"></i></div>
          <span class="step-title">Delivered</span>
          <span class="step-date">{{ $currentStep >= 3 ? (($deliveredDate ?: $order->updated_at)->format('d M Y')) : 'Pending' }}</span>
        </div>
      </div>
    @endif
  </div>

  <!-- Order Details Grid -->
  <div class="details-grid">
    <div class="detail-card">
      <h4><i class="fas fa-info-circle" style="color: var(--blue);"></i> Order Information</h4>
      <div class="detail-row">
        <span class="detail-label">Order Number</span>
        <span class="detail-value">{{ $order->order_number }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Order Date</span>
        <span class="detail-value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Payment Method</span>
        <span class="detail-value">{{ $order->payment_method ?? 'Cash on Delivery' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Total Items</span>
        <span class="detail-value">{{ $order->items->count() }} item(s)</span>
      </div>
    </div>

    <div class="detail-card">
      <h4><i class="fas fa-map-marker-alt" style="color: var(--danger);"></i> Shipping Address</h4>
      @php
        $address = $order->shipping_address;
        if (is_string($address)) {
          $address = json_decode($address, true);
        }
      @endphp
      @if(is_array($address))
        <div class="detail-row">
          <span class="detail-label">Name</span>
          <span class="detail-value">{{ $address['name'] ?? 'N/A' }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Phone</span>
          <span class="detail-value">{{ $address['phone'] ?? 'N/A' }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Address</span>
          <span class="detail-value">{{ $address['address'] ?? ($address['street'] ?? 'N/A') }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">City/State</span>
          <span class="detail-value">{{ ($address['city'] ?? '') . ', ' . ($address['state'] ?? '') }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">PIN Code</span>
          <span class="detail-value">{{ $address['pincode'] ?? ($address['zip'] ?? 'N/A') }}</span>
        </div>
      @else
        <p style="color: var(--muted); font-size: 14px;">{{ $order->shipping_address ?? 'Address not available' }}</p>
      @endif
    </div>
  </div>

  <!-- Order Items -->
  <div class="items-card">
    <h4><i class="fas fa-shopping-bag" style="color: var(--gold);"></i> Order Items</h4>
    
    @foreach($order->items as $item)
      <div class="order-item">
        @if($item->product && $item->product->image)
          <img src="{{ asset('uploads/products/' . $item->product->image) }}" alt="{{ $item->product->name ?? 'Product' }}" class="item-image">
        @else
          <div class="item-image" style="background: var(--green-pale); display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-box" style="font-size: 24px; color: var(--green);"></i>
          </div>
        @endif
        <div class="item-details">
          <div class="item-name">{{ $item->product->name ?? 'Product' }}</div>
          <div class="item-meta">
            Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}
            @if($item->product && $item->product->seller && $item->product->seller->user_info)
              • Seller: {{ $item->product->seller->user_info->shop_name ?? 'N/A' }}
            @endif
          </div>
        </div>
        <div class="item-price">₹{{ number_format($item->quantity * $item->price, 2) }}</div>
      </div>
    @endforeach

    <div class="total-row">
      <span class="total-label">Total Amount</span>
      <span class="total-value">₹{{ number_format($order->total_amount, 2) }}</span>
    </div>
  </div>

  <!-- Back to Orders -->
  @auth
    <div style="text-align: center; margin-top: 30px;">
      <a href="{{ route('my_orders') }}" style="display: inline-flex; align-items: center; gap: 8px; background: var(--green); color: white; padding: 14px 28px; border-radius: 50px; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 6px 20px rgba(45,106,79,.3); transition: all 0.3s;">
        <i class="fas fa-arrow-left"></i> Back to My Orders
      </a>
    </div>
  @endauth

</div>

@endsection
