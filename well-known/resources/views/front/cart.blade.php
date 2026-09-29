@extends('front.layouts.app')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
  /* ===================================================
     ROOT VARIABLES
  =================================================== */
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
  }

  body {
    font-family: 'DM Sans', 'Poppins', sans-serif;
    background: var(--surface) !important;
    color: var(--ink);
  }

  /* ===================================================
     CART HERO BANNER
  =================================================== */
  .cart-hero {
    background: linear-gradient(135deg, var(--dark) 0%, #16213e 60%, var(--green) 140%);
    padding: 52px 40px 46px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 24px;
  }

  .cart-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      radial-gradient(circle at 20% 50%, rgba(116,198,157,.12) 0%, transparent 55%),
      radial-gradient(circle at 80% 20%, rgba(244,162,97,.08) 0%, transparent 50%);
    pointer-events: none;
  }

  .cart-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 40px 40px;
  }

  .hero-icon {
    font-size: 64px;
    position: relative;
    z-index: 1;
    line-height: 1;
    filter: drop-shadow(0 8px 20px rgba(116,198,157,.4));
  }

  .hero-text { position: relative; z-index: 1; }

  .hero-text h1 {
    font-family: 'Playfair Display', serif;
    font-size: 38px;
    font-weight: 900;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 6px;
  }
  .hero-text h1 em { font-style: normal; color: var(--gold); }
  .hero-text p { font-size: 15px; color: rgba(255,255,255,.55); }

  /* ===================================================
     PAGE BODY
  =================================================== */
  .page-body {
    max-width: 1200px;
    margin: 0 auto;
    padding: 44px 24px 70px;
  }

  /* ===================================================
     ALERT
  =================================================== */
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

  /* ===================================================
     EMPTY CART
  =================================================== */
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

  /* ===================================================
     CART LAYOUT
  =================================================== */
  .cart-layout {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 28px;
    align-items: start;
  }

  /* ===================================================
     PANEL
  =================================================== */
  .panel {
    background: var(--white);
    border-radius: 20px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 4px 24px rgba(45,106,79,.07);
    overflow: hidden;
  }

  .panel-header {
    display: flex;
    align-items: center;
    padding: 20px 28px;
    border-bottom: 1px solid #f0f5f0;
    background: linear-gradient(135deg, var(--green-pale), #eafaf1);
  }

  .panel-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 800;
    color: var(--green);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* ===================================================
     CART ITEM ROW
  =================================================== */
  .cart-item {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 20px 28px;
    border-bottom: 1px solid #f5f9f5;
    transition: background .2s;
  }
  .cart-item:last-child { border-bottom: none; }
  .cart-item:hover { background: #fafff8; }

  .cart-item-link {
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
    color: inherit;
    flex: 1;
    min-width: 0;
  }
  .cart-item-link:hover .cart-item-name {
    color: var(--accent);
  }

  .cart-item-img {
    width: 80px; height: 80px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e8f0e8;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
    transition: transform .3s;
    flex-shrink: 0;
  }
  .cart-item:hover .cart-item-img { transform: scale(1.04); }

  .cart-item-info {
    flex: 1;
    min-width: 0;
  }

  .cart-item-name { font-weight: 700; font-size: 15px; color: var(--ink); margin-bottom: 4px; line-height: 1.3; transition: color .2s; }
  .cart-item-price { font-size: 13px; color: var(--muted); font-weight: 500; }

  .qty-display {
    font-weight: 600;
    font-size: 14px;
    padding: 8px 16px;
    background: #f0f7f0;
    border-radius: 8px;
    color: var(--ink);
    white-space: nowrap;
  }

  .item-subtotal {
    font-weight: 700;
    font-size: 16px;
    color: var(--ink);
    min-width: 100px;
    text-align: right;
  }

  .qty-form { display: flex; align-items: center; gap: 6px; }

  .qty-input {
    width: 58px;
    padding: 8px 10px;
    border: 1.5px solid #d4e8d4;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    font-weight: 600;
    text-align: center;
    color: var(--ink);
    background: var(--surface);
    outline: none;
    transition: .2s;
  }
  .qty-input:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(45,106,79,.12); }

  .btn-update {
    padding: 8px 14px;
    background: var(--green-pale);
    color: var(--green);
    border: 1.5px solid var(--green-light);
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
    font-family: inherit;
    white-space: nowrap;
  }
  .btn-update:hover { background: var(--green); color: white; border-color: var(--green); }

  .item-subtotal {
    font-size: 16px;
    font-weight: 800;
    color: var(--green);
    white-space: nowrap;
    min-width: 90px;
    text-align: right;
  }

  .btn-remove {
    width: 34px; height: 34px;
    background: #fff0f0;
    border: 1.5px solid #ffc9c9;
    border-radius: 50%;
    color: var(--danger);
    font-size: 13px;
    cursor: pointer;
    transition: .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .btn-remove:hover { background: var(--danger); color: white; border-color: var(--danger); transform: scale(1.1); }

  /* ===================================================
     SUMMARY SIDEBAR
  =================================================== */
  .summary-panel {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 100px;
  }

  .total-card {
    background: linear-gradient(145deg, var(--dark), #16213e);
    border-radius: 20px;
    padding: 28px;
    color: white;
    position: relative;
    overflow: hidden;
  }
  .total-card::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 120px; height: 120px;
    background: radial-gradient(circle, rgba(116,198,157,.2), transparent 70%);
  }
  .total-card::after {
    content: '';
    position: absolute;
    bottom: -20px; left: -20px;
    width: 100px; height: 100px;
    background: radial-gradient(circle, rgba(244,162,97,.15), transparent 70%);
  }

  .total-label {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255,255,255,.5);
    margin-bottom: 8px;
  }
  .total-amount {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    font-weight: 900;
    color: var(--gold);
    line-height: 1;
    margin-bottom: 6px;
    position: relative;
    z-index: 1;
  }
  .total-note { font-size: 12px; color: rgba(255,255,255,.4); position: relative; z-index: 1; }

  .payment-card {
    background: var(--white);
    border-radius: 20px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 4px 24px rgba(45,106,79,.07);
    padding: 28px;
  }
  .payment-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    font-weight: 800;
    color: var(--ink);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .qr-wrap { display: flex; justify-content: center; margin-bottom: 20px; }
  .qr-wrap img {
    width: 160px; height: 160px;
    object-fit: contain;
    border-radius: 16px;
    border: 3px solid var(--green-pale);
    box-shadow: 0 8px 24px rgba(45,106,79,.15);
    padding: 8px;
    background: white;
  }

  .upload-zone {
    background: var(--surface);
    border: 2px dashed #b7ddb7;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 18px;
    transition: .2s;
  }
  .upload-zone:hover { border-color: var(--green-mid); background: var(--green-pale); }
  .upload-zone label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
  }
  .upload-zone .form-control {
    border: 1.5px solid #d4e8d4;
    border-radius: 10px;
    font-size: 13px;
    padding: 9px 13px;
    font-family: inherit;
    transition: .2s;
    background: white;
  }
  .upload-zone .form-control:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(45,106,79,.1); }
  .upload-zone .invalid-feedback { font-size: 12px; color: var(--danger); margin-top: 5px; }

  .btn-place-order {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--green), var(--green-mid));
    color: white;
    border: none;
    border-radius: 14px;
    font-family: 'Playfair Display', serif;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    transition: .3s;
    box-shadow: 0 6px 20px rgba(45,106,79,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .btn-place-order:hover {
    background: linear-gradient(135deg, var(--green-mid), var(--green));
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(45,106,79,.4);
  }

  .btn-continue-shop {
    width: 100%;
    padding: 12px;
    background: transparent;
    color: var(--green);
    border: 2px solid #b7ddb7;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: .2s;
    margin-top: 10px;
    text-align: center;
    display: block;
    text-decoration: none;
  }
  .btn-continue-shop:hover { background: var(--green-pale); border-color: var(--green); color: var(--green); }

  /* ===================================================
     ORDERS SECTION
  =================================================== */
  .orders-section { margin-top: 50px; }

  .orders-heading {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 900;
    color: var(--ink);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .orders-heading::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--green-pale), transparent);
    border-radius: 2px;
  }

  .order-card {
    background: var(--white);
    border-radius: 20px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 4px 20px rgba(45,106,79,.06);
    margin-bottom: 20px;
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
    padding: 10px 0;
    border-bottom: 1px solid #f5f5f5;
  }
  .order-item-row:last-child { border-bottom: none; }
  .order-item-row img { width: 54px; height: 54px; object-fit: cover; border-radius: 10px; border: 1px solid #e8f0e8; }
  .order-item-details { flex: 1; }
  .order-item-details strong { font-size: 14px; color: var(--ink); display: block; margin-bottom: 3px; }
  .order-item-details span { font-size: 12px; color: var(--muted); }

  .order-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 26px;
    background: #fafff8;
    border-top: 1px solid #eef5ee;
    flex-wrap: wrap;
    gap: 10px;
  }
  .status-row { display: flex; align-items: center; gap: 12px; }

  .badge-paid {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
  }
  .badge-yes { background: #d8f3dc; color: var(--green); }
  .badge-no  { background: #fff3cd; color: #856404; }

  .payment-thumb {
    width: 44px; height: 44px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    cursor: pointer;
    transition: .2s;
  }
  .payment-thumb:hover { border-color: var(--blue); transform: scale(1.08); }

  .btn-dl-invoice {
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
    font-family: inherit;
  }
  .btn-dl-invoice:hover { background: var(--blue); color: white; }

  .btn-pending {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: #f5f5f5;
    color: #bbb;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: not-allowed;
    font-family: inherit;
  }

  /* ===================================================
     CUSTOM PAYMENT IMAGE MODAL
     (Replaces Bootstrap modal — never auto-opens)
  =================================================== */
  .wk-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 99999;
    align-items: center;
    justify-content: center;
  }
  .wk-modal-overlay.active {
    display: flex;
  }
  .wk-modal-box {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    max-width: 500px;
    width: 92%;
    box-shadow: 0 24px 70px rgba(0,0,0,0.35);
    animation: wkModalIn .25s ease;
  }
  @keyframes wkModalIn {
    from { transform: scale(0.88); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
  }
  .wk-modal-header {
    background: linear-gradient(135deg, var(--green-pale), #eafaf1);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #d8f3dc;
  }
  .wk-modal-title {
    font-family: 'Playfair Display', serif;
    font-weight: 800;
    color: var(--green);
    font-size: 17px;
  }
  .wk-modal-close {
    background: none;
    border: none;
    font-size: 22px;
    line-height: 1;
    cursor: pointer;
    color: var(--ink);
    padding: 0 4px;
    transition: color .2s;
  }
  .wk-modal-close:hover { color: var(--danger); }
  .wk-modal-body {
    padding: 22px;
    text-align: center;
  }
  .wk-modal-body img {
    max-width: 100%;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0,0,0,.12);
  }

  /* ===================================================
     RESPONSIVE
  =================================================== */
  @media (max-width: 1024px) {
    .cart-layout { grid-template-columns: 1fr; }
    .panel-header { padding: 16px 20px; }
  }

  @media (max-width: 768px) {
    .cart-hero { padding: 36px 20px 30px; }
    .hero-icon { font-size: 44px; }
    .hero-text h1 { font-size: 26px; }
    .cart-item { 
      flex-wrap: wrap;
      gap: 12px;
      padding: 16px;
    }
    .cart-item-link { flex: 1 1 100%; }
    .cart-item-img { width: 72px; height: 72px; }
    .qty-display { font-size: 13px; padding: 6px 12px; }
    .item-subtotal { font-size: 15px; min-width: auto; }
    .page-body { padding: 24px 14px 40px; }
    .orders-heading { font-size: 22px; }
    .order-card-header, .order-card-body, .order-card-footer { padding: 14px 16px; }
    .total-amount { font-size: 28px; }
    .panel-title { font-size: 1.2rem; }
    .summary-row { padding: 12px 16px; }
  }

  @media (max-width: 576px) {
    .cart-hero { padding: 28px 15px 24px; }
    .hero-icon { font-size: 36px; margin-bottom: 10px; }
    .hero-text h1 { font-size: 22px; }
    .hero-text p { font-size: 13px; }
    .cart-item {
      gap: 10px;
      padding: 12px;
    }
    .cart-item-img { width: 60px; height: 60px; border-radius: 10px; }
    .cart-item-name { font-size: 14px; }
    .cart-item-price { font-size: 12px; }
    .qty-display { font-size: 12px; padding: 5px 10px; }
    .item-subtotal { font-size: 14px; }
    .page-body { padding: 16px 10px 30px; }
    .panel { border-radius: 14px; }
    .panel-header { padding: 14px 16px; }
    .total-amount { font-size: 24px; }
    .btn-checkout { padding: 14px; font-size: 14px; }
    .summary-row { flex-direction: column; gap: 8px; padding: 12px 14px; }
    .wk-alert-success { padding: 12px 14px; font-size: 13px; }
  }

  @media (max-width: 400px) {
    .cart-item {
      gap: 8px;
      padding: 10px;
    }
    .cart-item-img { width: 50px; height: 50px; }
    .hero-text h1 { font-size: 20px; }
    .qty-display { font-size: 11px; padding: 4px 8px; }
  }
  
  @media (max-width: 359px) {
    .cart-hero { padding: 20px 10px 18px; }
    .hero-icon { font-size: 30px; margin-bottom: 8px; }
    .hero-text h1 { font-size: 18px; }
    .hero-text p { font-size: 12px; }
    .page-body { padding: 12px 8px 24px; }
    .cart-item {
      gap: 6px;
      padding: 8px;
    }
    .cart-item-img { width: 45px; height: 45px; border-radius: 8px; }
    .cart-item-name { font-size: 13px; -webkit-line-clamp: 2; }
    .cart-item-price { font-size: 11px; }
    .panel { border-radius: 10px; }
    .panel-header { padding: 12px 14px; }
    .panel-title { font-size: 1rem; }
    .summary-row { padding: 10px 12px; }
    .summary-row span { font-size: 13px; }
    .total-amount { font-size: 20px; }
    .btn-checkout { padding: 12px; font-size: 13px; border-radius: 10px; }
    .empty-state { padding: 30px 15px; }
    .empty-emoji { font-size: 50px; }
    .empty-state h3 { font-size: 18px; }
    .empty-state p { font-size: 13px; }
    .btn-shop-now { padding: 10px 20px; font-size: 13px; }
  }
  
  @media (max-width: 280px) {
    .cart-hero { padding: 16px 8px 14px; }
    .hero-text h1 { font-size: 16px; }
    .hero-text p { font-size: 11px; }
    .page-body { padding: 10px 6px 20px; }
    .cart-item {
      gap: 5px;
      padding: 6px;
    }
    .cart-item-img { width: 40px; height: 40px; }
    .cart-item-name { font-size: 12px; }
    .qty-form { flex-direction: column; }
    .qty-input { width: 100%; }
    .btn-update { width: 100%; }
    .summary-row { flex-direction: column; align-items: flex-start; gap: 4px; }
    .total-amount { font-size: 18px; }
    .btn-checkout { font-size: 12px; }
  }

  .invoice-container { display: none; }
</style>

<!-- ===== CART HERO ===== -->
<div class="cart-hero">
  <div class="cart-hero-grid"></div>
  <div class="hero-icon">&#128722;</div>
  <div class="hero-text">
    <h1>Your Shopping <em>Cart</em></h1>
    <p>Review your items, then proceed to checkout below.</p>
  </div>
</div>

<!-- ===== PAGE BODY ===== -->
<div class="page-body">

  @if (session('success'))
    <div class="wk-alert-success" id="order-success-alert">
      <span>&#10003;</span> {{ session('success') }}
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var msg = '{{ session('success') }}';
            var title = msg === 'Item removed from cart!' ? 'Removed from Cart' : 'Added to Cart!';
            Swal.fire({
              icon: 'success',
              title: title,
              text: msg,
              showConfirmButton: false,
              timer: 2200,
              customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom'
              }
            });
        }, 400);
      });
    </script>
  @endif

  <!-- Add SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @if (!$hasCompleteAddress)
    <div class="alert alert-warning d-flex align-items-center gap-3 mb-3" style="background: #fff3cd; border: 1px solid #ffecb5; border-radius: 12px; padding: 16px 20px;">
      <span style="font-size: 24px;">⚠️</span>
      <div>
        <strong>Complete your delivery address!</strong>
        <p class="mb-0" style="font-size: 14px; color: #856404;">You need to add your complete address (including pincode, city, and state) before placing an order.</p>
      </div>
      <a href="{{ route('profile.addresses') }}" class="btn btn-warning btn-sm ms-auto" style="white-space: nowrap;">Add Address</a>
    </div>
  @endif

  @if ($cartItems->isEmpty())
    <div class="empty-state">
      <span class="empty-emoji">&#128717;</span>
      <h3>Your cart is empty!</h3>
      <p>Looks like you haven't added anything yet. Start exploring!</p>
      <a href="{{ url('/') }}" class="btn-shop-now">&#8592; Continue Shopping</a>
    </div>

  @else
    <div class="cart-layout">

      <!-- LEFT: Cart Items -->
      <div>
        <div class="panel">
          <div class="panel-header">
            <span class="panel-title">&#128722; Cart Items</span>
          </div>

          @foreach ($cartItems as $item)
          <div class="cart-item">
            @if($item->products)
              <a href="{{ route('product.show', $item->products->id) }}" class="cart-item-link">
                <img class="cart-item-img"
                     src="{{ asset('uploads/products/'.$item->products->image) }}"
                     alt="{{ $item->products->name }}">
                <div class="cart-item-info">
                  <div class="cart-item-name">{{ $item->products->name }}</div>
                  <div class="cart-item-price">&#8377;{{ number_format($item->price, 2) }} per unit</div>
                </div>
              </a>
            @else
              <div class="cart-item-name">Product not found</div>
            @endif
            <div class="qty-display">Qty: {{ $item->quantity }}</div>
            <div class="item-subtotal">&#8377;{{ number_format($item->price * $item->quantity, 2) }}</div>
            <form action="{{ url('cart_remove', $item->id) }}" method="get">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-remove" title="Remove">&#10005;</button>
            </form>
          </div>
          @endforeach
        </div>
      </div>

      <!-- RIGHT: Summary + Payment -->
      <div class="summary-panel">
        <!-- Order Subtotal -->
        <div class="total-card" id="cartSubtotalCard">
          <div class="total-label">Cart Subtotal</div>
          <div class="total-amount" id="cartSubtotalDisplay">&#8377;{{ number_format($total, 2) }}</div>
          <div class="total-note" id="shippingStatusNote">Calculating shipping...</div>
        </div>

        <!-- Shipping Breakdown (dynamic) -->
        <div id="shippingBreakdownSection" style="margin: 0 0 16px; display:none;">
          <div id="shippingBreakdownContent"></div>
        </div>

        <!-- Grand Total -->
        <div class="total-card" id="grandTotalCard" style="display:none; background: linear-gradient(135deg, #2d6a4f, #40916c); color: #fff;">
          <div class="total-label" style="color:rgba(255,255,255,.8);">Grand Total</div>
          <div class="total-amount" id="grandTotalDisplay" style="color:#fff;"></div>
          <div class="total-note" id="freeShippingNote" style="color:rgba(255,255,255,.85); display:none;">🎉 Free Shipping Applied!</div>
        </div>

        <!-- No-service error block -->
        <div id="noServiceAlert" style="display:none; background:#fff3cd; border:1px solid #ffc107; border-radius:10px; padding:12px 16px; margin-bottom:12px; font-size:14px; color:#664d03;">
          <strong>⚠️ Delivery Not Available</strong><br>
          <span id="noServiceMsg">No courier service is available for your delivery address. Please update your address and try again.</span>
        </div>

        <div class="payment-card">
          <div class="payment-card-title">🛒 Checkout</div>
          <form id="cartOrderForm" action="{{ route('place_order') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="total_amount" value="{{ $total }}">
            <input type="hidden" name="shipping_cost" id="hiddenShippingCost" value="0">

            <!-- Delivery Address -->
            <div class="checkout-field" style="margin-bottom: 16px;">
              <label style="font-weight: 600; color: #333; display: block; margin-bottom: 6px;">📍 Delivery Address</label>
              <input type="text" name="shipping_address" class="form-control" 
                     value="{{ optional($user->user_info)->full_address ?? optional($user->user_info)->address ?? '' }}" 
                     placeholder="Enter your full delivery address" required readonly
                     style="padding: 10px 14px; border-radius: 8px; border: 1px solid #ddd; background: #f8f9fa;">
              <a href="{{ route('profile.addresses') }}" style="font-size: 12px; color: #1d4ed8; margin-top: 4px; display: inline-block;">Change Address</a>
            </div>

            <!-- Payment Method Selection -->
            <div class="checkout-field" style="margin-bottom: 16px;">
              <label style="font-weight: 600; color: #333; display: block; margin-bottom: 8px;">💳 Payment Method</label>
              <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <label class="payment-option" style="display: flex; align-items: center; gap: 6px; padding: 8px 14px; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: .2s;">
                  <input type="radio" name="payment_method" value="razorpay" checked> 
                  <span>Pay Online (Razorpay)</span>
                </label>
                <label class="payment-option" style="display: flex; align-items: center; gap: 6px; padding: 8px 14px; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: .2s;">
                  <input type="radio" name="payment_method" value="cod"> 
                  <span>Cash on Delivery</span>
                </label>
              </div>
            </div>

            @if ($hasCompleteAddress)
              <button type="submit" class="btn-place-order" id="placeOrderBtn">✓ Place Order</button>
            @else
              <a href="{{ route('profile.addresses') }}" class="btn-place-order" style="background: #ffc107; color: #333; text-decoration: none; display: block; text-align: center;">📍 Add Address First</a>
            @endif
          </form>
          <a href="{{ url('/') }}" class="btn-continue-shop">&#8592; Continue Shopping</a>
        </div>
      </div>

      {{-- ── Shipping calculation JS ─────────────────────────────────────── --}}
      <script>
      (function () {
        'use strict';

        var deliveryPincode = '{{ optional($user->user_info)->pincode ?? '' }}';
        var hasAddress      = {{ $hasCompleteAddress ? 'true' : 'false' }};
        var calcUrl         = '{{ route('shipping.calculate_cart') }}';
        var csrfToken       = '{{ csrf_token() }}';

        function getPaymentMethod() {
          var el = document.querySelector('input[name="payment_method"]:checked');
          return el ? el.value : 'razorpay';
        }

        function renderShippingBreakdown(sellers) {
          if (!sellers || sellers.length === 0) return '';
          var html = '<div style="background:#f8faf8; border:1px solid #d8f3dc; border-radius:10px; padding:12px 14px; font-size:13px;">';
          html += '<div style="font-weight:600; color:#2d6a4f; margin-bottom:8px;">🚚 Shipping per Seller</div>';
          sellers.forEach(function (s) {
            html += '<div style="display:flex; justify-content:space-between; padding:4px 0; border-bottom:1px dashed #e0e0e0;">';
            html += '<span style="color:#555;">' + (s.seller_name || 'Seller') + '</span>';
            if (s.no_service) {
              html += '<span style="color:#e63946; font-weight:600;">No Service</span>';
            } else {
              html += '<span style="color:#2d6a4f; font-weight:600;">₹' + parseFloat(s.shipping_cost).toFixed(2) + '</span>';
            }
            html += '</div>';
            if (s.available_couriers && s.available_couriers.length > 0) {
              var cheapest = s.available_couriers[0];
              if (cheapest.estimated_delivery_days) {
                html += '<div style="color:#888; font-size:11px; padding:2px 0;">📅 Est. delivery: ' + cheapest.estimated_delivery_days + ' day(s)</div>';
              }
            }
          });
          html += '</div>';
          return html;
        }

        function calculateShipping() {
          if (!hasAddress || !deliveryPincode || deliveryPincode.length !== 6) {
            document.getElementById('shippingStatusNote').textContent = 'Add complete address to see shipping cost.';
            return;
          }

          var paymentMethod = getPaymentMethod();
          document.getElementById('shippingStatusNote').textContent = '⏳ Calculating shipping...';
          document.getElementById('grandTotalCard').style.display = 'none';
          document.getElementById('noServiceAlert').style.display = 'none';

          fetch(calcUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              delivery_pincode: deliveryPincode,
              payment_method: paymentMethod
            })
          })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (!data.success) {
              document.getElementById('shippingStatusNote').textContent = data.message || 'Shipping calculation failed.';
              return;
            }

            // Shipping note
            if (data.free_shipping) {
              document.getElementById('shippingStatusNote').textContent = '🎉 Free shipping on this order!';
              document.getElementById('freeShippingNote').style.display = 'block';
            } else {
              document.getElementById('shippingStatusNote').textContent = '+ Shipping: ' + data.formatted.shipping;
              document.getElementById('freeShippingNote').style.display = 'none';
            }

            // Seller breakdown
            var breakdownHtml = renderShippingBreakdown(data.sellers);
            document.getElementById('shippingBreakdownContent').innerHTML = breakdownHtml;
            document.getElementById('shippingBreakdownSection').style.display = breakdownHtml ? 'block' : 'none';

            // Grand total
            document.getElementById('grandTotalDisplay').textContent = data.formatted.grand_total;
            document.getElementById('grandTotalCard').style.display = 'block';

            // Update hidden input
            document.getElementById('hiddenShippingCost').value = data.total_shipping;

            // No-service handling
            var placeBtn = document.getElementById('placeOrderBtn');
            if (data.no_service) {
              document.getElementById('noServiceAlert').style.display = 'block';
              document.getElementById('noServiceMsg').textContent =
                'No courier service available for your pincode (' + deliveryPincode + '). Please update your address.';
              if (placeBtn) { placeBtn.disabled = true; placeBtn.style.opacity = '.5'; }
            } else {
              document.getElementById('noServiceAlert').style.display = 'none';
              if (placeBtn) { placeBtn.disabled = false; placeBtn.style.opacity = '1'; }
            }
          })
          .catch(function () {
            document.getElementById('shippingStatusNote').textContent = 'Shipping calculation unavailable.';
          });
        }

        // Run on page load
        calculateShipping();

        // Re-run when payment method changes (COD vs Prepaid affects shipping)
        document.querySelectorAll('input[name="payment_method"]').forEach(function (radio) {
          radio.addEventListener('change', calculateShipping);
        });
      }());
      </script>

    </div><!-- /cart-layout -->
  @endif

  <!-- ===== ORDERS SECTION ===== -->
  @if(!$orders->isEmpty())
  <div class="orders-section">
    <h3 class="orders-heading">&#128230; Your Orders</h3>

    @foreach($orders as $order)
    <div class="order-card">
      <div class="order-card-header">
        <div>
          <div class="order-num">{{ $order->order_number }}</div>
          <div class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="order-total-chip">&#8377;{{ number_format($order->total_amount, 2) }}</div>
      </div>

      <div class="order-card-body">
        @foreach($order->items as $item)
        <div class="order-item-row">
          @if($item->product)
            <img src="{{ asset('uploads/products/'.$item->product->image) }}"
                 alt="{{ $item->product->name }}">
            <div class="order-item-details">
              <strong>{{ $item->product->name }}</strong>
              <span>Qty: {{ $item->quantity }} &times; &#8377;{{ number_format($item->price, 2) }} &nbsp;|&nbsp;
                Delivery: {{ $item->delivery_date ? date('d M, Y', strtotime($item->delivery_date)) : 'Not set' }}
              </span>
            </div>
          @else
            <div class="order-item-details">
              <strong>Product not found</strong>
              <span>Qty: {{ $item->quantity }} &times; &#8377;{{ number_format($item->price, 2) }} &nbsp;|&nbsp;
                Delivery: {{ $item->delivery_date ? date('d M, Y', strtotime($item->delivery_date)) : 'Not set' }}
              </span>
            </div>
          @endif
        </div>
        @endforeach
      </div>

      <div class="order-card-footer">
        <div class="status-row">
          @if($order->payment_image)
            {{-- FIX: Removed data-bs-toggle/target; now uses pure JS onclick --}}
            <img src="{{ asset('uploads/payments/'.$order->payment_image) }}"
                 alt="Payment Proof"
                 class="payment-thumb"
                 onclick="openPaymentModal('wkModal{{ $order->id }}')"
                 title="Click to view payment receipt">
          @else
            <span style="font-size:13px; color:var(--muted);">No receipt</span>
          @endif
          <span class="badge-paid {{ $order->order_status == 'Pending' ? 'badge-no' : 'badge-yes' }}">
            {{ $order->order_status == 'Pending' ? '⏳ Pending' : '✓ Received' }}
          </span>
        </div>
        @if($order->order_status == 'Pending')
          <button class="btn-pending" disabled>&#128203; Invoice Pending</button>
        @else
          <button class="btn-dl-invoice" onclick="downloadInvoice({{ (int) $order->id }})">&#8681; Download Invoice</button>
        @endif
      </div>

    </div><!-- /order-card -->
    @endforeach

    {{-- ===== CUSTOM MODALS — rendered outside order-card, never auto-open ===== --}}
    @foreach($orders as $order)
      @if($order->payment_image)
      <div class="wk-modal-overlay" id="wkModal{{ $order->id }}">
        <div class="wk-modal-box">
          <div class="wk-modal-header">
            <span class="wk-modal-title">&#128247; Payment Receipt</span>
            <button class="wk-modal-close" onclick="closePaymentModal('wkModal{{ $order->id }}')">&times;</button>
          </div>
          <div class="wk-modal-body">
            <img src="{{ asset('uploads/payments/'.$order->payment_image) }}" alt="Payment Proof">
          </div>
        </div>
      </div>
      @endif
    @endforeach

    <!-- Hidden Invoice Templates -->
    @foreach($orders as $order)
    <div id="invoice-{{ $order->id }}"
         class="invoice-container"
         style="display:none; background:#fff; padding:25px; color:#000; font-family:Arial, sans-serif; font-size:12px;">
      <div style="text-align:center; margin-bottom:10px;">
        <h3 style="margin:0;">Tax Invoice</h3>
      </div>
      <table width="100%" style="margin-bottom:10px;">
        <tr>
          <td width="60%" style="vertical-align:top;">
            <strong>Sold By:</strong><br>
            @php
              $sellers = $order->items->map(function ($item) {
                return ($item->product && $item->product->seller) ? $item->product->seller : null;
              })->filter()->unique('id');
            @endphp
            @foreach($sellers as $seller)
              @if($seller)
                {{ $seller->name ?? 'Seller Name' }}<br>
                {{ optional($seller->user_info)->address ?? 'Seller Address' }}<br>
                <small>Email: {{ $seller->email ?? '-' }}</small><br>
                <small>Phone: {{ $seller->mobile ?? '-' }}</small><br>
                <small>GST: {{ optional($seller->user_info)->gst_no ?? '-' }}</small>
              @else
                <span>Seller not found</span><br>
              @endif
              @if(!$loop->last)<hr style="margin:8px 0; border-top:1px dashed #ccc;">@endif
            @endforeach
          </td>
          <td width="40%" style="vertical-align:top;">
            <strong>Billing Address:</strong><br>
            {{ optional($order->buyer)->name ?? 'Buyer Name' }}<br>
            {{ optional(optional($order->buyer)->user_info)->address ?? 'Buyer Address' }}<br>
            <small>Email: {{ optional($order->buyer)->email ?? '-' }}</small><br>
            <small>Phone: {{ optional($order->buyer)->mobile ?? '-' }}</small>
          </td>
        </tr>
      </table>
      <table width="100%" style="margin-bottom:10px;">
        <tr>
          <td><strong>Order No:</strong> {{ $order->order_number }}</td>
          <td><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</td>
        </tr>
      </table>
      <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; text-align:center;">
        <thead style="background:#f2f2f2;">
          <tr>
            <th>Product</th><th>Qty</th><th>Price (&#8377;)</th><th>Total (&#8377;)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $item)
          <tr>
            <td style="text-align:left;">{{ optional($item->product)->name ?? 'Product Not Found' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price, 2) }}</td>
            <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div style="text-align:right; margin-top:15px;">
        <h4>Grand Total: &#8377;{{ number_format($order->total_amount, 2) }}</h4>
      </div>
      <div style="margin-top:20px; text-align:center;">
        <small>Thank you for shopping with us!</small>
      </div>
    </div>
    @endforeach

  </div><!-- /orders-section -->
  @endif

</div><!-- /page-body -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
  $('.allow-only-numeric').keyup(function() {
    var node = $(this);
    node.val(node.val().replace(/[^0-9]/g,''));
  });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
  /* ===== Custom Modal Controls ===== */
  function openPaymentModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.add('active');
  }

  function closePaymentModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.remove('active');
  }

  /* Close when clicking the dark backdrop */
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('wk-modal-overlay')) {
      e.target.classList.remove('active');
    }
  });

  /* Close on Escape key */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.wk-modal-overlay.active').forEach(function(el) {
        el.classList.remove('active');
      });
    }
  });

  /* ===== Cart Order with Razorpay ===== */
  document.getElementById('cartOrderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var btn = document.getElementById('placeOrderBtn');
    var formData = new FormData(form);
    var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

    btn.disabled = true;
    btn.textContent = 'Processing...';

    Swal.fire({
        title: 'Processing...',
        html: 'Please wait while we place your order ⏳',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: function () { Swal.showLoading(); }
    });

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success && data.payment_required && paymentMethod === 'razorpay') {
            Swal.close();
            WinkelKartPay.initiate({
                type: 'order',
                reference_id: data.order_id,
                amount: data.amount,
                onSuccess: function() {
                    btn.disabled = false;
                    btn.textContent = '✓ Place Order';
                },
                onFailure: function() {
                    btn.disabled = false;
                    btn.textContent = '✓ Place Order';
                }
            });
        } else if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '🎉 Order Placed!',
                html: '<b>' + (data.message || 'Your order has been placed successfully!') + '</b>',
                confirmButtonText: '👍 Awesome!',
                confirmButtonColor: '#2d6a4f',
                allowOutsideClick: false
            }).then(function(result) {
                if (result.isConfirmed && data.redirect) window.location.href = data.redirect;
            });
            form.reset();
        } else {
            Swal.fire({ icon: 'error', title: 'Oops!', text: data.message || 'Something went wrong.' });
        }
        btn.disabled = false;
        btn.textContent = '✓ Place Order';
    })
    .catch(function() {
        Swal.fire({ icon: 'error', title: 'Connection Error', text: 'Could not reach the server.' });
        btn.disabled = false;
        btn.textContent = '✓ Place Order';
    });
  });

  /* ===== Invoice Download ===== */
  async function downloadInvoice(orderId) {
    const { jsPDF } = window.jspdf;
    const invoice = document.getElementById('invoice-' + orderId);
    invoice.style.display = 'block';
    const canvas = await html2canvas(invoice, { scale: 2 });
    const imgData = canvas.toDataURL('image/png');
    const pdf = new jsPDF('p', 'mm', 'a4');
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    pdf.save('Invoice_' + orderId + '.pdf');
    invoice.style.display = 'none';
  }
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{ asset('js/razorpay-payment.js') }}"></script>

@endsection