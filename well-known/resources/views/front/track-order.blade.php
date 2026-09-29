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
  .track-hero {
    background: linear-gradient(135deg, var(--dark) 0%, #16213e 60%, var(--green) 140%);
    padding: 60px 40px 70px;
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
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 1;
  }

  .hero-icon {
    font-size: 80px;
    margin-bottom: 20px;
    filter: drop-shadow(0 8px 20px rgba(116,198,157,.4));
  }

  .hero-text h1 {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    font-weight: 900;
    color: var(--white);
    margin-bottom: 10px;
  }
  .hero-text h1 em { font-style: normal; color: var(--gold); }
  .hero-text p { font-size: 16px; color: rgba(255,255,255,.65); margin-bottom: 30px; }

  /* Search Box */
  .track-search-box {
    background: var(--white);
    padding: 40px;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,.15);
    max-width: 600px;
    margin: -50px auto 0;
    position: relative;
    z-index: 10;
  }

  .track-search-box h3 {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: var(--ink);
    margin-bottom: 8px;
    text-align: center;
  }

  .track-search-box p.subtitle {
    color: var(--muted);
    font-size: 14px;
    text-align: center;
    margin-bottom: 25px;
  }

  .search-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .input-group-track {
    position: relative;
  }

  .input-group-track input {
    width: 100%;
    padding: 16px 20px 16px 50px;
    font-size: 16px;
    border: 2px solid #e8f0e8;
    border-radius: 14px;
    outline: none;
    transition: all 0.3s;
    font-family: 'DM Sans', sans-serif;
  }

  .input-group-track input:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 4px rgba(45,106,79,.1);
  }

  .input-group-track .input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: var(--muted);
  }

  .btn-track {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--green), var(--green-mid));
    color: white;
    padding: 16px 32px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(45,106,79,.3);
  }

  .btn-track:hover {
    background: linear-gradient(135deg, var(--green-mid), var(--green));
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(45,106,79,.4);
  }

  .btn-track i {
    font-size: 18px;
  }

  /* Alert Messages */
  .alert-error {
    background: linear-gradient(135deg, #ffeaea, #ffd6d6);
    border: 1px solid #ffb3b3;
    color: var(--danger);
    padding: 14px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* Info Section */
  .info-section {
    max-width: 800px;
    margin: 50px auto 70px;
    padding: 0 24px;
  }

  .info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
  }

  .info-card {
    background: var(--white);
    padding: 24px;
    border-radius: 16px;
    border: 1px solid #e8f0e8;
    text-align: center;
    transition: all 0.3s;
  }

  .info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(45,106,79,.1);
  }

  .info-card .icon {
    width: 60px;
    height: 60px;
    background: var(--green-pale);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 24px;
    color: var(--green);
  }

  .info-card h4 {
    font-weight: 700;
    font-size: 16px;
    color: var(--ink);
    margin-bottom: 8px;
  }

  .info-card p {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.5;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .track-hero { padding: 40px 20px 50px; }
    .hero-text h1 { font-size: 32px; }
    .track-search-box { margin: -30px 20px 0; padding: 30px 24px; }
    .info-section { margin: 40px auto 50px; }
  }
</style>

<!-- Hero Section -->
<section class="track-hero">
  <div class="hero-content">
    <div class="hero-icon">📦</div>
    <div class="hero-text">
      <h1>Track Your <em>Order</em></h1>
      <p>Enter your order number to get real-time updates on your delivery</p>
    </div>
  </div>
</section>

<!-- Search Box -->
<div class="track-search-box">
  <h3>🔍 Order Tracking</h3>
  <p class="subtitle">Enter the order number from your confirmation email or order history</p>

  @if(session('error'))
    <div class="alert-error">
      <i class="fas fa-exclamation-circle"></i>
      {{ session('error') }}
    </div>
  @endif

  <form action="{{ route('track_order_search') }}" method="POST" class="search-form">
    @csrf
    <div class="input-group-track">
      <i class="fas fa-hashtag input-icon"></i>
      <input type="text" name="order_number" placeholder="Enter your Order Number (e.g., WK1234567890)" 
             value="{{ old('order_number') }}" required autofocus>
    </div>
    <button type="submit" class="btn-track">
      <i class="fas fa-search"></i>
      Track Order
    </button>
  </form>
</div>

<!-- Info Section -->
<section class="info-section">
  <div class="info-cards">
    <div class="info-card">
      <div class="icon"><i class="fas fa-clipboard-list"></i></div>
      <h4>Order Placed</h4>
      <p>Your order has been received and is being processed</p>
    </div>
    <div class="info-card">
      <div class="icon"><i class="fas fa-box-open"></i></div>
      <h4>Processing</h4>
      <p>Your items are being prepared for shipment</p>
    </div>
    <div class="info-card">
      <div class="icon"><i class="fas fa-shipping-fast"></i></div>
      <h4>Shipped</h4>
      <p>Your order is on its way to you</p>
    </div>
    <div class="info-card">
      <div class="icon"><i class="fas fa-check-circle"></i></div>
      <h4>Delivered</h4>
      <p>Your order has been delivered successfully</p>
    </div>
  </div>
</section>

@endsection
