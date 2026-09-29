<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Register Your Business — WinkelKart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    /* ================= BASE ================= */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Outfit', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px 16px;
      background: #0f1729;
      position: relative;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }

    /* Ambient background glows */
    body::before {
      content: '';
      position: fixed;
      top: -20%;
      left: -10%;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(255,80,30,0.18) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }
    body::after {
      content: '';
      position: fixed;
      bottom: -20%;
      right: -10%;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }

    /* ================= OUTER SHELL ================= */
    .shell {
      width: 100%;
      max-width: 1060px;
      border-radius: 24px;
      overflow: hidden;
      display: flex;
      box-shadow: 0 40px 80px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.05);
      position: relative;
      z-index: 1;
      animation: fadeUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ================= LEFT PANEL ================= */
    .panel-left {
      flex: 0 0 320px;
      background: linear-gradient(155deg, #ff6230 0%, #ff3366 55%, #c0185e 100%);
      padding: 36px 28px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    /* Decorative blobs on left panel */
    .panel-left::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 200px; height: 200px;
      background: rgba(255,255,255,0.08);
      border-radius: 50%;
      pointer-events: none;
    }
    .panel-left::after {
      content: '';
      position: absolute;
      bottom: -80px; left: -40px;
      width: 260px; height: 260px;
      background: rgba(255,255,255,0.06);
      border-radius: 50%;
      pointer-events: none;
    }

    .brand-logo {
      background: #fde047;
      border-radius: 16px;
      padding: 10px 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 18px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
      position: relative;
      z-index: 1;
    }

    .brand-logo img {
      height: 72px;
      width: auto;
      object-fit: contain;
    }

    .panel-left h2 {
      font-size: 22px;
      font-weight: 900;
      color: #ffffff;
      margin-bottom: 8px;
      line-height: 1.2;
      letter-spacing: -0.5px;
      position: relative;
      z-index: 1;
    }

    .panel-left p {
      font-size: 13px;
      color: rgba(255,255,255,0.82);
      margin-bottom: 0;
      line-height: 1.5;
      position: relative;
      z-index: 1;
    }

    /* Feature list */
    .feature-list {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 8px;
      position: relative;
      z-index: 1;
      margin-top: 24px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 10px;
      padding: 9px 14px;
      text-align: left;
      transition: background 0.2s;
    }
    .feature-item:hover { background: rgba(255,255,255,0.18); }

    .feature-icon {
      width: 32px; height: 32px;
      border-radius: 8px;
      background: rgba(255,255,255,0.18);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }

    .feature-icon svg {
      width: 16px; height: 16px;
      fill: none;
      stroke: rgba(255,255,255,0.95);
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .feature-item span {
      font-size: 13px;
      font-weight: 600;
      color: rgba(255,255,255,0.95);
    }

    /* ================= RIGHT PANEL ================= */
    .panel-right {
      flex: 1;
      background: #ffffff;
      padding: 30px 38px 28px;
      overflow-y: auto;
      max-height: 100vh;
    }

    /* Step Indicator */
    .step-bar {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0;
      margin-bottom: 24px;
    }

    .step {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .step-num {
      width: 34px; height: 34px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px;
      font-weight: 800;
      flex-shrink: 0;
      transition: all 0.3s;
    }

    .step.active .step-num {
      background: transparent;
      border: 2.5px solid #f97316;
      color: #f97316;
    }

    .step.inactive .step-num {
      background: transparent;
      border: 2.5px solid #cbd5e1;
      color: #94a3b8;
    }

    .step-label {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.8px;
      text-transform: uppercase;
    }

    .step.active .step-label { color: #1e293b; }
    .step.inactive .step-label { color: #94a3b8; }

    .step-connector {
      flex: 0 0 60px;
      height: 2px;
      background: linear-gradient(90deg, #f97316, #e2e8f0);
      margin: 0 10px;
    }

    /* Alerts */
    .alert {
      border-radius: 12px;
      font-size: 13.5px;
      padding: 13px 16px;
      margin-bottom: 22px;
      border: none;
    }
    .alert-success {
      background: #ecfdf5;
      border-left: 4px solid #10b981;
      color: #065f46;
    }
    .alert-danger {
      background: #fff1f2;
      border-left: 4px solid #f43f5e;
      color: #9f1239;
    }
    .alert ul { padding-left: 16px; margin: 0; }

    /* Section chip */
    .section-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: #f97316;
      background: #fff7ed;
      border: 1px solid #fed7aa;
      padding: 4px 12px;
      border-radius: 50px;
      margin-bottom: 14px;
    }

    /* Two-col grid */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0 18px;
    }
    .full { grid-column: 1 / -1; }

    /* Form group */
    .fgroup { margin-bottom: 13px; }

    .flabel {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 7px;
      letter-spacing: 0.3px;
      text-transform: uppercase;
    }

    .req { color: #f43f5e; margin-left: 2px; }

    /* Inputs */
    .finput,
    .fselect {
      width: 100%;
      padding: 12px 16px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-size: 14.5px;
      font-family: 'Outfit', sans-serif;
      color: #1e293b;
      background: #f8fafc;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      -webkit-appearance: none;
      appearance: none;
    }

    .fselect {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 16px center;
      background-color: #f8fafc;
      padding-right: 38px;
      cursor: pointer;
    }

    .finput:focus,
    .fselect:focus {
      border-color: #f97316;
      background: #ffffff;
      box-shadow: 0 0 0 3.5px rgba(249,115,22,0.12);
    }

    .finput::placeholder { color: #cbd5e1; }

    /* Terms checkbox */
    .terms-box {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 16px;
      background: #fff7ed;
      border: 1.5px solid #fed7aa;
      border-radius: 10px;
      margin-bottom: 14px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .terms-box:hover { background: #ffedd5; }

    .terms-box input[type="checkbox"] {
      width: 17px; height: 17px;
      accent-color: #f97316;
      flex-shrink: 0;
      cursor: pointer;
      margin: 0;
    }

    .terms-box label {
      font-size: 13px;
      color: #475569;
      font-weight: 500;
      margin: 0;
      cursor: pointer;
      line-height: 1.4;
    }

    .terms-box label a { color: #f97316; font-weight: 700; }
    .terms-box label a:hover { color: #ea580c; text-decoration: underline; }

    /* reCAPTCHA */
    .recaptcha-wrap {
      margin-bottom: 16px;
    }
    .text-danger { font-size: 12px; color: #f43f5e; display: block; margin-top: 4px; }

    /* Submit */
    .btn-submit {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 800;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      letter-spacing: 0.3px;
      transition: all 0.3s ease;
      box-shadow: 0 6px 20px rgba(37,99,235,0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-submit:hover {
      background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(37,99,235,0.4);
    }
    .btn-submit:active { transform: translateY(0); }

    .btn-arrow {
      font-size: 20px;
      transition: transform 0.25s ease;
    }
    .btn-submit:hover .btn-arrow { transform: translateX(4px); }

    /* Divider */
    .or-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 14px 0 10px;
    }
    .or-divider::before, .or-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e2e8f0;
    }
    .or-divider span {
      font-size: 12px;
      color: #94a3b8;
      font-weight: 500;
      white-space: nowrap;
    }

    /* Footer note */
    .footer-note {
      text-align: center;
      font-size: 11.5px;
      color: #94a3b8;
      margin-top: 10px;
    }

    /* ================= PLAN CARDS ================= */
    .plan-step-header {
      text-align: center;
      margin-bottom: 24px;
    }
    .plan-step-header h3 {
      font-size: 20px;
      font-weight: 800;
      color: #1e293b;
      margin-bottom: 6px;
    }
    .plan-step-header p {
      font-size: 13.5px;
      color: #64748b;
      margin: 0;
    }

    .plan-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 20px;
    }

    .plan-card {
      border: 2px solid #e2e8f0;
      border-radius: 18px;
      padding: 22px 20px 20px;
      cursor: pointer;
      position: relative;
      transition: border-color 0.25s, box-shadow 0.25s, transform 0.2s;
      background: #fff;
      display: flex;
      flex-direction: column;
      user-select: none;
    }
    .plan-card:hover {
      border-color: #f97316;
      box-shadow: 0 6px 24px rgba(249,115,22,0.12);
      transform: translateY(-3px);
    }
    .plan-card.selected {
      border-color: #f97316;
      box-shadow: 0 8px 30px rgba(249,115,22,0.2);
      background: #fff7ed;
    }
    .plan-card.selected-pro {
      border-color: #2563eb;
      box-shadow: 0 8px 30px rgba(37,99,235,0.2);
      background: #eff6ff;
    }
    .plan-card:hover.plan-card-pro {
      border-color: #2563eb;
      box-shadow: 0 6px 24px rgba(37,99,235,0.12);
    }

    .plan-badge {
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      padding: 4px 14px;
      border-radius: 50px;
      white-space: nowrap;
    }
    .badge-free {
      background: #dcfce7;
      color: #166534;
      border: 1.5px solid #86efac;
    }
    .badge-pro {
      background: linear-gradient(90deg, #2563eb, #7c3aed);
      color: #fff;
    }

    .plan-icon {
      width: 44px; height: 44px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 12px;
      font-size: 22px;
    }
    .plan-icon-free { background: #dcfce7; }
    .plan-icon-pro  { background: #dbeafe; }

    .plan-name {
      font-size: 15px;
      font-weight: 800;
      color: #1e293b;
      margin-bottom: 4px;
    }
    .plan-subtitle {
      font-size: 12px;
      color: #64748b;
      margin-bottom: 12px;
    }

    .plan-price {
      display: flex;
      align-items: baseline;
      gap: 4px;
      margin-bottom: 14px;
    }
    .plan-price .amount {
      font-size: 26px;
      font-weight: 900;
      color: #1e293b;
    }
    .plan-price .period {
      font-size: 12px;
      color: #94a3b8;
      font-weight: 500;
    }

    .plan-features {
      list-style: none;
      padding: 0; margin: 0;
      display: flex;
      flex-direction: column;
      gap: 7px;
      flex: 1;
    }
    .plan-features li {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: 12.5px;
      color: #475569;
      font-weight: 500;
      line-height: 1.35;
    }
    .plan-features li .fi {
      flex-shrink: 0;
      font-size: 13px;
      margin-top: 0.5px;
    }
    .fi-check { color: #22c55e; }
    .fi-dash  { color: #cbd5e1; }

    .plan-divider {
      height: 1px;
      background: #f1f5f9;
      margin: 14px 0;
    }

    .plan-limit {
      font-size: 11.5px;
      color: #94a3b8;
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    .plan-select-btn {
      width: 100%;
      margin-top: 16px;
      padding: 10px 14px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 800;
      font-family: 'Outfit', sans-serif;
      border: 2px solid;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .btn-plan-free {
      background: transparent;
      border-color: #22c55e;
      color: #16a34a;
    }
    .btn-plan-free:hover,
    .plan-card.selected .btn-plan-free {
      background: #22c55e;
      color: #fff;
    }
    .btn-plan-pro {
      background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
      border-color: transparent;
      color: #fff;
      box-shadow: 0 4px 14px rgba(37,99,235,0.3);
    }
    .btn-plan-pro:hover {
      background: linear-gradient(135deg, #1d4ed8 0%, #6d28d9 100%);
      box-shadow: 0 6px 20px rgba(37,99,235,0.4);
      transform: translateY(-1px);
    }
    .plan-card.selected-pro .btn-plan-pro {
      background: linear-gradient(135deg, #1d4ed8 0%, #6d28d9 100%);
    }

    .plan-selected-check {
      position: absolute;
      top: 12px;
      right: 14px;
      width: 22px; height: 22px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px;
      opacity: 0;
      transition: opacity 0.2s;
    }
    .check-free { background: #22c55e; color: #fff; }
    .check-pro  { background: #2563eb; color: #fff; }
    .plan-card.selected .check-free,
    .plan-card.selected-pro .check-pro { opacity: 1; }

    .plan-continue-btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 800;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 6px 20px rgba(249,115,22,0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      opacity: 0.5;
      pointer-events: none;
    }
    .plan-continue-btn.enabled {
      opacity: 1;
      pointer-events: all;
    }
    .plan-continue-btn.enabled:hover {
      background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(249,115,22,0.4);
    }

    .step-back-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: none;
      border: none;
      font-size: 13px;
      font-weight: 700;
      color: #94a3b8;
      cursor: pointer;
      padding: 0;
      margin-bottom: 16px;
      font-family: 'Outfit', sans-serif;
      transition: color 0.2s;
    }
    .step-back-btn:hover { color: #f97316; }

    .chosen-plan-banner {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      border-radius: 10px;
      margin-bottom: 16px;
      font-size: 13px;
      font-weight: 700;
    }
    .cpb-free {
      background: #dcfce7;
      border: 1.5px solid #86efac;
      color: #166534;
    }
    .cpb-pro {
      background: #dbeafe;
      border: 1.5px solid #93c5fd;
      color: #1e40af;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 820px) {
      .shell { flex-direction: column; max-width: 560px; }
      .panel-left {
        flex: none;
        padding: 32px 28px;
        border-radius: 0;
      }
      .feature-list { display: none; }
      .panel-left p { margin-bottom: 0; }
      .panel-right { padding: 30px 24px; }
    }

    @media (max-width: 560px) {
      .form-grid { grid-template-columns: 1fr; }
      .full { grid-column: 1; }
      .step-connector { flex: 0 0 30px; }
      .step-label { display: none; }
      .plan-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>

  <div class="shell">

    <!-- ===== LEFT PANEL ===== -->
    <div class="panel-left">
      <div class="brand-logo">
        <a href="{{ url('/') }}">
          <img src="./assets/images/logo/Winkel_Shop__2_-removebg-preview.png" alt="WinkelKart Logo">
        </a>
      </div>

      <h2>Sell on WinkelKart</h2>
      <p>Register your business and start selling to millions of customers across India.</p>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          </div>
          <span>Create your online store</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
          </div>
          <span>Grow your business</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          </div>
          <span>Earn competitive margins</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <span>Easy logistics support</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          </div>
          <span>Real-time analytics</span>
        </div>
      </div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="panel-right">

      <!-- Step Indicator -->
      <div class="step-bar">
        <div class="step active" id="step-dot-1">
          <div class="step-num">1</div>
          <div class="step-label">Choose Plan</div>
        </div>
        <div class="step-connector" id="step-connector-1"></div>
        <div class="step inactive" id="step-dot-2">
          <div class="step-num">2</div>
          <div class="step-label">Business Details</div>
        </div>
      </div>

      {{-- Success message --}}
      @if (session('success'))
        <div class="alert alert-success">
          ✓ {{ session('success') }}
        </div>
      @endif

      {{-- Validation errors --}}
      @if ($errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- ===== STEP 1: Choose Plan ===== -->
      <div id="plan-step">

        <div class="plan-step-header">
          <h3>Choose Your Seller Plan</h3>
          <p>Select the plan that best fits your business. You can upgrade anytime.</p>
        </div>

        <div class="plan-grid">

          <!-- FREE PLAN CARD -->
          <div class="plan-card" id="card-free" onclick="selectPlan('free')">
            <span class="plan-badge badge-free">Free</span>
            <div class="plan-selected-check check-free" id="check-free">✓</div>
            <div class="plan-icon plan-icon-free">🛍️</div>
            <div class="plan-name">Free Seller Plan</div>
            <div class="plan-subtitle">Perfect for starters &amp; small shops</div>
            <div class="plan-price">
              <span class="amount">₹0</span>
              <span class="period">/ forever</span>
            </div>
            <ul class="plan-features">
              <li><span class="fi fi-check">✔</span>Seller account registration</li>
              <li><span class="fi fi-check">✔</span>Basic store profile</li>
              <li><span class="fi fi-check">✔</span>Standard customer support</li>
              <li><span class="fi fi-check">✔</span>Basic analytics &amp; reports</li>
              <li><span class="fi fi-check">✔</span>Normal product visibility</li>
              <li><span class="fi fi-check">✔</span>Standard payout cycle</li>
              <li><span class="fi fi-dash">–</span>Product limit: <strong>50 products</strong></li>
              <li><span class="fi fi-dash">–</span>No featured listings</li>
              <li><span class="fi fi-dash">–</span>Limited ad access</li>
            </ul>
            <div class="plan-divider"></div>
            <div class="plan-limit">Suitable for: New sellers, local shops, individuals</div>
            <button type="button" class="plan-select-btn btn-plan-free" onclick="selectPlan('free')">
              <span>🚀</span> Start Free
            </button>
          </div>

          <!-- PRO PLAN CARD -->
          <div class="plan-card plan-card-pro" id="card-pro" onclick="selectPlan('paid')">
            <span class="plan-badge badge-pro">⭐ Most Popular</span>
            <div class="plan-selected-check check-pro" id="check-pro">✓</div>
            <div class="plan-icon plan-icon-pro">💎</div>
            <div class="plan-name">Pro Seller Plan</div>
            <div class="plan-subtitle">For serious &amp; growing businesses</div>
            <div class="plan-price">
              <span class="amount">₹999</span>
              <span class="period">/ year</span>
            </div>
            <ul class="plan-features">
              <li><span class="fi fi-check">✔</span>Unlimited product listings</li>
              <li><span class="fi fi-check">✔</span>Verified / Premium seller badge</li>
              <li><span class="fi fi-check">✔</span>Featured product promotion</li>
              <li><span class="fi fi-check">✔</span>Advanced sales analytics</li>
              <li><span class="fi fi-check">✔</span>Priority customer support</li>
              <li><span class="fi fi-check">✔</span>Lower commission fees</li>
              <li><span class="fi fi-check">✔</span>Faster payout settlement</li>
              <li><span class="fi fi-check">✔</span>Marketing &amp; advertising tools</li>
              <li><span class="fi fi-check">✔</span>Inventory management tools</li>
            </ul>
            <div class="plan-divider"></div>
            <div class="plan-limit">Suitable for: Brands, wholesalers, high-volume sellers</div>
            <button type="button" class="plan-select-btn btn-plan-pro" onclick="selectPlan('paid')">
              <span>💎</span> Go Pro
            </button>
          </div>

        </div><!-- /plan-grid -->

        <button type="button" class="plan-continue-btn" id="planContinueBtn" onclick="goToStep2()">
          Continue with Selected Plan
          <span style="font-size:18px">→</span>
        </button>

      </div><!-- /plan-step -->

      <!-- ===== STEP 2: Business Details Form ===== -->
      <div id="form-step" style="display:none;">

        <!-- Back button -->
        <button type="button" class="step-back-btn" onclick="goToStep1()">
          ← Back to Plan Selection
        </button>

        <!-- Chosen plan banner -->
        <div id="chosen-plan-banner" class="chosen-plan-banner cpb-free">
          <span id="chosen-plan-icon">🛍️</span>
          <span id="chosen-plan-text">Free Seller Plan selected</span>
        </div>

      <!-- Form -->
      <form action="{{ route('register-buisness.store') }}" id="loan_form" name="loan_form" method="POST">
        @csrf
        <input type="hidden" name="subscription_plan" id="subscription_plan_input" value="free">
        <input type="hidden" name="razorpay_payment_id" id="reg_razorpay_payment_id" value="">
        <input type="hidden" name="razorpay_order_id"   id="reg_razorpay_order_id"   value="">
        <input type="hidden" name="razorpay_signature"  id="reg_razorpay_signature"  value="">

        <div class="section-chip">🏢 Business Info</div>

        <div class="form-grid">

          <!-- Business Name -->
          <div class="fgroup full">
            <label class="flabel">Business Name <span class="req">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" maxlength="50" required
              class="finput" placeholder="Enter your business name">
          </div>

          <!-- Address -->
          <div class="fgroup full">
            <label class="flabel">Address <span class="req">*</span></label>
            <input type="text" name="address" value="{{ old('address') }}" required
              class="finput" placeholder="Enter full business address">
          </div>

          <!-- Phone -->
          <div class="fgroup">
            <label class="flabel">Phone <span class="req">*</span></label>
            <input type="text" class="allow-only-numeric finput" name="mobile"
              maxlength="10" value="{{ old('mobile') }}" required placeholder="10-digit number">
          </div>

          <!-- Email -->
          <div class="fgroup">
            <label class="flabel">Email <span class="req">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" maxlength="50" required
              class="finput" placeholder="Business email address">
          </div>

          <!-- Business Type -->
          <div class="fgroup">
            <label class="flabel">Business Type <span class="req">*</span></label>
            <select name="business_category_id" required class="fselect">
              <option value="">Select business type</option>
              @foreach($categories as $category)
                <option value="{{ $category['id'] }}" {{ old('business_category_id') == $category['id'] ? 'selected' : '' }}>{{ $category['name'] }}</option>
              @endforeach
            </select>
          </div>

          <!-- GSTIN -->
          <div class="fgroup">
            <label class="flabel">GSTIN No <span class="req">*</span></label>
            <input type="text" name="gst_no" value="{{ old('gst_no') }}" maxlength="20" required
              class="finput" placeholder="e.g. 22ABCDE1234F1Z5">
          </div>

          <!-- Country (India only) -->
          <div class="fgroup">
            <label class="flabel">Country <span class="req">*</span></label>
            <input type="text" class="finput" value="India" readonly>
            <input type="hidden" name="country_id" value="103">
          </div>

          <!-- State -->
          <div class="fgroup">
            <label class="flabel">State <span class="req">*</span></label>
            <select name="state_id" required class="fselect">
              <option value="">Select state</option>
              @foreach($states as $state)
                <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- District -->
          <div class="fgroup">
            <label class="flabel">Dist <span class="req">*</span></label>
            <input type="text" name="district" value="{{ old('district') }}" maxlength="100" required
              class="finput" placeholder="Enter district">
          </div>

        </div><!-- /form-grid -->

        <!-- Terms -->
        <div class="terms-box">
          <input type="checkbox" name="terms" value="{{ old('terms') }}" required id="terms">
          <label for="terms">
            By continuing, I agree to WinkelKart's
            <a href="#">Terms &amp; Conditions</a> &amp; <a href="#">Privacy Policy</a>
          </label>
        </div>

        <!-- reCAPTCHA DISABLED FOR LOCAL TESTING -->
        <!-- <div class="recaptcha-wrap">
          <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
          @error('g-recaptcha-response')
            <span class="text-danger">{{ $message }}</span>
          @enderror
        </div> -->

        <!-- Submit -->
        <button type="submit" id="registerSubmitBtn" class="btn-submit">
          Register &amp; Continue
          <span class="btn-arrow">→</span>
        </button>

      </form>

      </div><!-- /form-step -->

      <div class="or-divider">
        <span>Already have an account?</span>
      </div>

      <p style="text-align:center; font-size:13.5px; color:#475569;">
        <a href="{{ url('login') }}" style="color:#f97316; font-weight:700;">Login to your account</a>
        &nbsp;or&nbsp;
        <a href="{{ url('register') }}" style="color:#f97316; font-weight:700;">Register as Buyer</a>
      </p>

      <p class="footer-note">&copy; 2026 WinkelKart &mdash; Designed by SRD Techno</p>

    </div><!-- /panel-right -->

  </div><!-- /shell -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    var selectedPlan = null;
    var PRO_AMOUNT   = 99900; // ₹999 in paise

    /* ─── Plan selection ─────────────────────────────── */
    function selectPlan(plan) {
      selectedPlan = plan;

      document.getElementById('card-free').classList.remove('selected');
      document.getElementById('card-pro').classList.remove('selected-pro');

      if (plan === 'free') {
        document.getElementById('card-free').classList.add('selected');
      } else {
        document.getElementById('card-pro').classList.add('selected-pro');
      }

      var btn = document.getElementById('planContinueBtn');
      btn.classList.add('enabled');
      btn.textContent = plan === 'free'
        ? 'Continue with Free Plan →'
        : 'Continue with Pro Plan →';

      document.getElementById('subscription_plan_input').value = plan;
    }

    /* ─── Step navigation ────────────────────────────── */
    function goToStep2() {
      if (!selectedPlan) return;

      document.getElementById('step-dot-1').classList.replace('active', 'inactive');
      document.getElementById('step-dot-2').classList.replace('inactive', 'active');
      document.getElementById('step-connector-1').style.background =
        'linear-gradient(90deg, #f97316, #f97316)';

      var banner = document.getElementById('chosen-plan-banner');
      var icon   = document.getElementById('chosen-plan-icon');
      var text   = document.getElementById('chosen-plan-text');
      if (selectedPlan === 'free') {
        banner.className  = 'chosen-plan-banner cpb-free';
        icon.textContent  = '🛍️';
        text.textContent  = 'Free Seller Plan selected — up to 50 products';
      } else {
        banner.className  = 'chosen-plan-banner cpb-pro';
        icon.textContent  = '💎';
        text.textContent  = 'Pro Seller Plan selected — ₹999/year · unlimited products & premium features';
      }

      // Update submit button appearance for Pro plan
      updateSubmitButton(selectedPlan);

      document.getElementById('plan-step').style.display = 'none';
      document.getElementById('form-step').style.display = 'block';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goToStep1() {
      document.getElementById('step-dot-2').classList.replace('active', 'inactive');
      document.getElementById('step-dot-1').classList.replace('inactive', 'active');
      document.getElementById('step-connector-1').style.background = '';

      document.getElementById('form-step').style.display = 'none';
      document.getElementById('plan-step').style.display = 'block';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateSubmitButton(plan) {
      var btn = document.getElementById('registerSubmitBtn');
      if (!btn) return;
      if (plan === 'paid') {
        btn.innerHTML = '<span>💳</span> Pay ₹999 &amp; Register <span style="font-size:18px">→</span>';
        btn.style.background  = 'linear-gradient(135deg, #2563eb 0%, #7c3aed 100%)';
        btn.style.boxShadow   = '0 6px 20px rgba(37,99,235,0.35)';
      } else {
        btn.innerHTML = 'Register &amp; Continue <span class="btn-arrow">→</span>';
        btn.style.background  = '';
        btn.style.boxShadow   = '';
      }
    }

    /* ─── Razorpay payment for Pro plan ─────────────── */
    function initiateRegistrationPayment(e) {
      e.preventDefault();

      var btn = document.getElementById('registerSubmitBtn');
      if (btn) { btn.disabled = true; btn.innerHTML = '⏳ Preparing payment...'; }

      var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch('{{ route("register.create_payment") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
      })
      .then(function(res) { return res.json(); })
      .then(function(data) {
        if (data.error) {
          Swal.fire({ icon: 'error', title: 'Payment Error', text: data.error });
          resetProBtn(btn);
          return;
        }

        var options = {
          key:         data.key,
          amount:      data.amount,
          currency:    data.currency,
          name:        data.name,
          description: data.description,
          order_id:    data.order_id,
          prefill: {
            name:    document.querySelector('[name="name"]').value    || '',
            email:   document.querySelector('[name="email"]').value   || '',
            contact: document.querySelector('[name="mobile"]').value  || ''
          },
          theme: { color: '#2563eb' },
          handler: function(response) {
            // Set hidden fields and submit form
            document.getElementById('reg_razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('reg_razorpay_order_id').value   = response.razorpay_order_id;
            // Signature is optional - Razorpay checkout may or may not return it
            document.getElementById('reg_razorpay_signature').value  = response.razorpay_signature || '';

            if (btn) { btn.innerHTML = '✅ Payment done — Registering...'; btn.disabled = true; }
            document.getElementById('loan_form').submit();
          },
          modal: {
            ondismiss: function() { resetProBtn(btn); }
          }
        };

        var rzp = new Razorpay(options);
        rzp.on('payment.failed', function(resp) {
          Swal.fire({
            icon: 'error',
            title: 'Payment Failed',
            text: (resp.error && resp.error.description) ? resp.error.description : 'Payment was not completed. Please try again.'
          });
          resetProBtn(btn);
        });
        rzp.open();
      })
      .catch(function() {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Could not start payment. Please check your connection and try again.' });
        resetProBtn(btn);
      });
    }

    function resetProBtn(btn) {
      if (!btn) return;
      btn.disabled = false;
      btn.innerHTML = '<span>💳</span> Pay ₹999 &amp; Register <span style="font-size:18px">→</span>';
    }

    /* ─── Form submit interception ───────────────────── */
    document.addEventListener('DOMContentLoaded', function() {
      // If this is an upgrade, auto-select Pro plan and go to step 2
      @if(isset($isUpgrade) && $isUpgrade)
        selectedPlan = 'paid';
        document.getElementById('subscription_plan_input').value = 'paid';
        document.getElementById('card-pro').classList.add('selected-pro');
        setTimeout(function() {
          goToStep2();
        }, 300);
      @endif
      
      document.getElementById('loan_form').addEventListener('submit', function(e) {
        if (selectedPlan === 'paid') {
          var pid = document.getElementById('reg_razorpay_payment_id').value;
          if (!pid) {
            // Payment not done yet — trigger Razorpay
            initiateRegistrationPayment(e);
          }
          // If pid is already set (payment completed), let the form submit normally
        }
      });

      // If returning with errors, restore step 2
      @if($errors->any() || session('success'))
        selectedPlan = '{{ old('subscription_plan', 'free') }}';
        document.getElementById('subscription_plan_input').value = selectedPlan;
        if (selectedPlan === 'paid') {
          document.getElementById('card-pro').classList.add('selected-pro');
        } else {
          document.getElementById('card-free').classList.add('selected');
        }
        goToStep2();
      @endif
    });

    /* ─── Numeric-only fields ────────────────────────── */
    $('.allow-only-numeric').keyup(function() {
      var node = $(this);
      node.val(node.val().replace(/[^0-9]/g,''));
    });
  </script>

</body>
</html>