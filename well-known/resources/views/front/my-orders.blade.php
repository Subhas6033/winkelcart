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
  }

  body {
    font-family: 'DM Sans', 'Poppins', sans-serif;
    background: var(--surface) !important;
    color: var(--ink);
  }

  /* Hero Banner */
  .orders-hero {
    background: linear-gradient(135deg, var(--dark) 0%, #16213e 60%, var(--green) 140%);
    padding: 52px 40px 46px;
    position: relative;
    overflow: hidden;
  }

  .orders-hero::before {
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
    gap: 24px;
    position: relative;
    z-index: 1;
  }

  .hero-icon {
    font-size: 64px;
    filter: drop-shadow(0 8px 20px rgba(116,198,157,.4));
  }

  .hero-text h1 {
    font-family: 'Playfair Display', serif;
    font-size: 38px;
    font-weight: 900;
    color: var(--white);
    margin-bottom: 6px;
  }
  .hero-text h1 em { font-style: normal; color: var(--gold); }
  .hero-text p { font-size: 15px; color: rgba(255,255,255,.55); }

  /* Page Body */
  .page-body {
    max-width: 1200px;
    margin: 0 auto;
    padding: 44px 24px 70px;
  }

  /* Alert */
  .wk-alert-success {
    background: linear-gradient(135deg, #d8f3dc, #b7e4c7);
    border: 1px solid var(--green-light);
    color: var(--green);
    padding: 14px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* Empty State */
  .empty-state { text-align: center; padding: 80px 20px; }
  .empty-state .empty-emoji {
    font-size: 90px;
    margin-bottom: 20px;
    display: block;
    animation: floatY 3s ease-in-out infinite;
  }
  @keyframes floatY {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-12px); }
  }
  .empty-state h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: var(--ink);
    margin-bottom: 10px;
  }
  .empty-state p { color: var(--muted); font-size: 15px; margin-bottom: 28px; }
  .btn-shop-now {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--green);
    color: white;
    padding: 13px 30px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 14px;
    transition: .3s;
    box-shadow: 0 6px 20px rgba(45,106,79,.3);
    text-decoration: none;
  }
  .btn-shop-now:hover { background: var(--green-mid); color: white; transform: translateY(-2px); }

  /* Order Cards */
  .order-card {
    background: var(--white);
    border-radius: 20px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 4px 20px rgba(45,106,79,.06);
    margin-bottom: 24px;
    overflow: hidden;
    transition: .3s;
  }
  .order-card:hover { box-shadow: 0 8px 32px rgba(45,106,79,.12); transform: translateY(-2px); }

  .order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 26px;
    background: linear-gradient(135deg, #f0f9f4, #e8f3e8);
    border-bottom: 1px solid #e0ede0;
    flex-wrap: wrap;
    gap: 12px;
  }
  .order-num { font-family: 'Playfair Display', serif; font-weight: 800; font-size: 17px; color: var(--green); }
  .order-date { font-size: 13px; color: var(--muted); font-weight: 500; }
  .order-total-chip { background: var(--green); color: white; font-size: 14px; font-weight: 800; padding: 6px 16px; border-radius: 50px; }

  .order-card-body { padding: 22px 26px; }

  .order-item-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f5f5f5;
  }
  .order-item-row:last-child { border-bottom: none; }
  .order-item-row img { width: 64px; height: 64px; object-fit: cover; border-radius: 10px; border: 1px solid #e8f0e8; transition: transform .2s; }
  .order-item-link {
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
    color: inherit;
    flex: 1;
  }
  .order-item-link:hover img { transform: scale(1.05); }
  .order-item-link:hover .order-item-details strong { color: var(--green); }
  .order-item-details { flex: 1; }
  .order-item-details strong { font-size: 15px; color: var(--ink); display: block; margin-bottom: 4px; transition: color .2s; }
  .order-item-details span { font-size: 13px; color: var(--muted); }
  .order-item-price { font-weight: 700; color: var(--green); font-size: 15px; }

  .order-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 26px;
    background: #fafff8;
    border-top: 1px solid #eef5ee;
    flex-wrap: wrap;
    gap: 12px;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
  }
  .status-pending { background: #fff3cd; color: #856404; }
  .status-done { background: #d4edda; color: #155724; }
  .status-cancelled { background: #f8d7da; color: #721c24; }

  .btn-view-detail {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: white;
    color: var(--blue);
    border: 2px solid var(--blue);
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
    text-decoration: none;
  }
  .btn-view-detail:hover { background: var(--blue); color: white; }

  .btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: white;
    color: var(--danger);
    border: 2px solid var(--danger);
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
    text-decoration: none;
  }
  .btn-cancel:hover { background: var(--danger); color: white; }

  .btn-dl-invoice {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: white;
    color: var(--green);
    border: 2px solid var(--green);
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
    text-decoration: none;
  }
  .btn-dl-invoice:hover { background: var(--green); color: white; }

  .btn-track-order {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: linear-gradient(135deg, #ff9800, #f57c00);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .3s;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(255,152,0,.3);
  }
  .btn-track-order:hover { 
    background: linear-gradient(135deg, #f57c00, #ef6c00);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(255,152,0,.4);
  }
  .btn-track-order i { font-size: 14px; }

  /* Payment Method Badge */
  .payment-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    background: #e3f2fd;
    color: #1565c0;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  /* Delivery Address */
  .shipping-info {
    font-size: 13px;
    color: var(--muted);
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed #e0e0e0;
  }
  .shipping-info strong { color: var(--ink); }

  /* Pagination */
  .pagination-wrap { margin-top: 30px; display: flex; justify-content: center; }
  .pagination { gap: 6px; }
  .pagination .page-link {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    color: var(--ink);
    padding: 8px 14px;
    font-weight: 600;
  }
  .pagination .page-item.active .page-link {
    background: var(--green);
    border-color: var(--green);
    color: white;
  }

  /* Invoice Container (hidden) */
  .invoice-container { display: none; }

  /* ================= MOBILE RESPONSIVE ================= */
  @media (max-width: 991px) {
    .page-body { padding: 30px 20px 50px; }
  }

  @media (max-width: 768px) {
    .orders-hero { padding: 36px 20px 30px; }
    .hero-icon { font-size: 44px; }
    .hero-text h1 { font-size: 26px; }
    .order-card-header, .order-card-body, .order-card-footer { padding: 14px 16px; }
    .order-item-row { flex-wrap: wrap; gap: 12px; }
    .order-item-img { width: 60px; height: 60px; }
    .order-item-details { flex: 1; min-width: 150px; }
    .order-num { font-size: 16px; }
    .page-body { padding: 24px 15px 40px; }
  }

  @media (max-width: 576px) {
    .orders-hero { padding: 28px 15px 24px; }
    .hero-icon { font-size: 36px; }
    .hero-text h1 { font-size: 22px; }
    .hero-text p { font-size: 13px; }
    .hero-content { gap: 16px; }
    .order-card { border-radius: 14px; }
    .order-card-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .order-card-header, .order-card-body, .order-card-footer { padding: 12px 14px; }
    .order-item-row { gap: 10px; }
    .order-item-img { width: 50px; height: 50px; }
    .order-item-name { font-size: 14px; }
    .order-item-price { font-size: 13px; }
    .order-amount { font-size: 18px; }
    .page-body { padding: 20px 12px 35px; }
    .empty-state { padding: 50px 15px; }
    .empty-state .empty-emoji { font-size: 60px; }
    .empty-state h3 { font-size: 20px; }
    .btn-shop-now { padding: 12px 24px; font-size: 13px; }
  }

  @media (max-width: 400px) {
    .hero-text h1 { font-size: 20px; }
    .order-card-header, .order-card-body, .order-card-footer { padding: 10px 12px; }
    .order-item-img { width: 45px; height: 45px; }
  }
</style>

<!-- Hero Banner -->
<div class="orders-hero">
  <div class="hero-content">
    <div class="hero-icon">📦</div>
    <div class="hero-text">
      <h1>My <em>Orders</em></h1>
      <p>Track your orders and view order history</p>
    </div>
  </div>
</div>

<!-- Page Body -->
<div class="page-body">

  @if (session('success'))
    <div class="wk-alert-success">
      <span>✓</span> {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="wk-alert-success" style="background: #f8d7da; border-color: #f5c6cb; color: #721c24;">
      <span>✕</span> {{ session('error') }}
    </div>
  @endif

  @if($orders->isEmpty())
    <div class="empty-state">
      <span class="empty-emoji">📭</span>
      <h3>No orders yet!</h3>
      <p>You haven't placed any orders. Start shopping to see your orders here.</p>
      <a href="{{ url('/') }}" class="btn-shop-now">← Start Shopping</a>
    </div>
  @else

    @foreach($orders as $order)
    <div class="order-card">
      <div class="order-card-header">
        <div>
          <div class="order-num">#{{ $order->order_number }}</div>
          <div class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
          <span class="payment-badge">{{ strtoupper($order->payment_method ?? 'N/A') }}</span>
          <span class="order-total-chip">₹{{ number_format($order->total_amount, 2) }}</span>
        </div>
      </div>

      <div class="order-card-body">
        @foreach($order->items as $item)
        <div class="order-item-row">
          @if($item->product)
            <a href="{{ route('product.show', $item->product->id) }}" class="order-item-link">
              <img src="{{ asset('uploads/products/'.$item->product->image) }}" alt="{{ $item->product->name }}">
              <div class="order-item-details">
                <strong>{{ $item->product->name }}</strong>
                <span>Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</span>
                @if($item->delivery_date)
                  <br><span style="color: var(--green);">📅 Delivery: {{ date('d M, Y', strtotime($item->delivery_date)) }}</span>
                @endif
              </div>
            </a>
            <div class="order-item-price">₹{{ number_format($item->quantity * $item->price, 2) }}</div>
          @else
            <div class="order-item-details">
              <strong>Product not available</strong>
              <span>Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</span>
            </div>
          @endif
        </div>
        @endforeach

        <div class="shipping-info">
          <strong>📍 Delivery Address:</strong> {{ $order->shipping_address ?? 'Not provided' }}
        </div>
      </div>

      <div class="order-card-footer">
        <div style="display: flex; align-items: center; gap: 12px;">
          @if($order->order_status == 'Pending')
            <span class="status-badge status-pending">⏳ Pending</span>
          @elseif($order->order_status == 'Cancelled')
            <span class="status-badge status-cancelled">✕ Cancelled</span>
          @else
            <span class="status-badge status-done">✓ {{ $order->order_status }}</span>
          @endif
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
          <!-- Track Order Button -->
          <a href="{{ route('track_order_direct', $order->order_number) }}" class="btn-track-order">
            <i class="fas fa-shipping-fast"></i> Track Order
          </a>

          <!-- Download Invoice Button (always available) -->
          <a href="{{ route('download_invoice', $order->order_number) }}" class="btn-dl-invoice">
            <i class="fas fa-file-pdf"></i> Download Invoice
          </a>

          @php
            $processingOrLater = optional($order->timelines)->contains(function($timeline) {
              return in_array(strtolower($timeline->status), ['processing', 'shipped', 'delivered']);
            });
          @endphp
          @if($order->order_status == 'Pending' && !$processingOrLater)
            <form action="{{ route('cancel_order', $order->order_number) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
              @csrf
              <button type="submit" class="btn-cancel">✕ Cancel Order</button>
            </form>
          @else
            <button class="btn-cancel" title="Order cannot be cancelled after processing" disabled style="opacity:0.6;cursor:not-allowed;">✕ Cancel Unavailable</button>
          @endif
        </div>
      </div>
    </div>
    @endforeach

    <!-- Pagination -->
    <div class="pagination-wrap">
      {{ $orders->links('pagination::bootstrap-4') }}
    </div>

  @endif

</div>

@endsection
