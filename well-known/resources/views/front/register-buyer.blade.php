<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Buyer — WinkelKart</title>
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
      top: -20%; left: -10%;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(34,197,94,0.15) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }
    body::after {
      content: '';
      position: fixed;
      bottom: -20%; right: -10%;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(21,128,61,0.12) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }

    /* ================= OUTER SHELL ================= */
    .shell {
      width: 100%;
      max-width: 960px;
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
      background: linear-gradient(155deg, #16a34a 0%, #15803d 50%, #14532d 100%);
      padding: 36px 28px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .panel-left::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 200px; height: 200px;
      background: rgba(255,255,255,0.07);
      border-radius: 50%;
      pointer-events: none;
    }
    .panel-left::after {
      content: '';
      position: absolute;
      bottom: -80px; left: -40px;
      width: 260px; height: 260px;
      background: rgba(255,255,255,0.05);
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
      padding: 38px 44px 32px;
      overflow-y: auto;
      max-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    /* Step Indicator */
    .step-bar {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 28px;
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
    }

    .step.active .step-num {
      background: transparent;
      border: 2.5px solid #16a34a;
      color: #16a34a;
    }

    .step-label {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      color: #1e293b;
    }

    /* Panel heading */
    .panel-heading {
      margin-bottom: 22px;
    }
    .panel-heading h3 {
      font-size: 22px;
      font-weight: 900;
      color: #0f172a;
      letter-spacing: -0.5px;
      margin-bottom: 4px;
    }
    .panel-heading p {
      font-size: 13px;
      color: #94a3b8;
    }

    /* Heading accent line */
    .heading-line {
      height: 3px;
      width: 48px;
      background: linear-gradient(90deg, #16a34a, #86efac);
      border-radius: 3px;
      margin-bottom: 22px;
    }

    /* Alerts */
    .alert {
      border-radius: 12px;
      font-size: 13.5px;
      padding: 13px 16px;
      margin-bottom: 18px;
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

    /* Form group */
    .fgroup { margin-bottom: 14px; }

    .flabel {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 6px;
      letter-spacing: 0.3px;
      text-transform: uppercase;
    }

    .req { color: #f43f5e; margin-left: 2px; }

    /* Two-col grid for name + phone */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0 16px;
    }
    .full { grid-column: 1 / -1; }

    /* Inputs */
    .finput {
      width: 100%;
      padding: 11px 15px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-size: 14.5px;
      font-family: 'Outfit', sans-serif;
      color: #1e293b;
      background: #f8fafc;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .finput:focus {
      border-color: #16a34a;
      background: #ffffff;
      box-shadow: 0 0 0 3.5px rgba(22,163,74,0.12);
    }

    .finput::placeholder { color: #cbd5e1; }

    /* Terms checkbox */
    .terms-box {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 16px;
      background: #f0fdf4;
      border: 1.5px solid #bbf7d0;
      border-radius: 10px;
      margin-bottom: 14px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .terms-box:hover { background: #dcfce7; }

    .terms-box input[type="checkbox"] {
      width: 17px; height: 17px;
      accent-color: #16a34a;
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

    .terms-box label a { color: #16a34a; font-weight: 700; }
    .terms-box label a:hover { color: #15803d; text-decoration: underline; }

    /* reCAPTCHA */
    .recaptcha-wrap { margin-bottom: 16px; }
    .text-danger { font-size: 12px; color: #f43f5e; display: block; margin-top: 4px; }

    /* Submit */
    .btn-submit {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 800;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      letter-spacing: 0.3px;
      transition: all 0.3s ease;
      box-shadow: 0 6px 20px rgba(22,163,74,0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-submit:hover {
      background: linear-gradient(135deg, #15803d 0%, #14532d 100%);
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(22,163,74,0.4);
    }
    .btn-submit:active { transform: translateY(0); }

    .btn-arrow {
      font-size: 20px;
      transition: transform 0.25s ease;
      display: inline-block;
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

    /* ================= RESPONSIVE ================= */
    @media (max-width: 820px) {
      .shell { flex-direction: column; max-width: 520px; }
      .panel-left { flex: none; padding: 28px 24px; }
      .feature-list { display: none; }
      .panel-left p { margin-bottom: 0; }
      .panel-right { padding: 28px 22px; justify-content: flex-start; }
    }

    @media (max-width: 520px) {
      .form-grid { grid-template-columns: 1fr; }
      .full { grid-column: 1; }
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

      <h2>Shop on WinkelKart</h2>
      <p>Create your account and start shopping from thousands of products across India.</p>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/></svg>
          </div>
          <span>Shop from 1000+ products</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <span>Secure &amp; safe payments</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <span>Fast doorstep delivery</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          </div>
          <span>Exclusive deals &amp; offers</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
          </div>
          <span>24/7 customer support</span>
        </div>
      </div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="panel-right">

      <!-- Step bar -->
      <div class="step-bar">
        <div class="step active">
          <div class="step-num">1</div>
          <div class="step-label">Create Account</div>
        </div>
      </div>

      <!-- Heading -->
      <div class="panel-heading">
        <h3>Create Your Account</h3>
        <p>Join WinkelKart — Shop smarter, live better.</p>
      </div>
      <div class="heading-line"></div>

      {{-- Show success message --}}
      @if (session('success'))
        <div class="alert alert-success">
          ✓ {{ session('success') }}
        </div>
      @endif

      {{-- Show validation errors --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Form -->
      <form action="{{ url('buyer-store') }}" id="loan_form" name="loan_form" method="POST">
        @csrf

        <div class="form-grid">

          <!-- Name -->
          <div class="fgroup full">
            <label class="flabel">Full Name <span class="req">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required
              class="finput" placeholder="Enter your full name">
          </div>

          <!-- Phone -->
          <div class="fgroup">
            <label class="flabel">Phone <span class="req">*</span></label>
            <input type="text" class="allow-only-numeric finput" name="mobile"
              maxlength="10" value="{{ old('mobile') }}" required placeholder="10-digit number"
              pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">
          </div>

          <!-- Email -->
          <div class="fgroup">
            <label class="flabel">Email <span class="req">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" maxlength="50" required
              class="finput" placeholder="Your email address">
          </div>

        </div>

        <!-- Terms -->
        <div class="terms-box">
          <input type="checkbox" name="terms" value="{{ old('terms') }}" required id="terms">
          <label for="terms">
            I agree to the <a href="#">Terms &amp; Conditions</a> &amp; <a href="#">Privacy Policy</a>
          </label>
        </div>

        <!-- reCAPTCHA -->
        <!-- reCAPTCHA disabled -->

        <!-- Submit -->
        <button type="submit" class="btn-submit">
          Create Account
          <span class="btn-arrow">→</span>
        </button>

      </form>

      <div class="or-divider">
        <span>Already have an account?</span>
      </div>

      <p style="text-align:center; font-size:13.5px; color:#475569;">
        <a href="{{ url('login') }}" style="color:#16a34a; font-weight:700;">Login to your account</a>
        &nbsp;or&nbsp;
        <a href="{{ url('register-buisness') }}" style="color:#16a34a; font-weight:700;">Register as Seller</a>
      </p>

      <p class="footer-note">&copy; 2026 WinkelKart &mdash; A UNIT OF SRD TECHNOLOGIES INDIA</p>

    </div><!-- /panel-right -->

  </div><!-- /shell -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    $('.allow-only-numeric').on('keyup input paste', function() {
      var node = $(this);
      node.val(node.val().replace(/[^0-9]/g,''));
    });
    $('.allow-only-numeric').on('keypress', function(e) {
      if (e.key && e.key.length === 1 && !/[0-9]/.test(e.key)) {
        e.preventDefault();
      }
    });
  </script>

</body>
</html>