<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Winkel: Login</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('assets_admin/img/brand/favicon.png') }}" type="image/png">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets_admin/vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets_admin/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">

  <!-- Argon + AdminLTE (kept from original) -->
  <link rel="stylesheet" href="{{ asset('assets_admin/css/argon.css?v=1.2.0') }}" type="text/css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('assets_admin/plugins/overlayScrollbars/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets_admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets_admin/dist/css/adminlte.min.css') }}">

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
      max-width: 900px;
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
      padding: 40px 28px;
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
      margin-bottom: 20px;
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
      margin-top: 28px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 10px;
      padding: 10px 14px;
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
      padding: 44px 44px 36px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    /* Panel heading */
    .panel-heading { margin-bottom: 6px; }
    .panel-heading h3 {
      font-size: 26px;
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
      margin: 14px 0 26px;
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
    .form-group { margin-bottom: 16px; }

    .form-label-custom {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 7px;
      letter-spacing: 0.3px;
      text-transform: uppercase;
    }

    /* Input wrapper for password toggle */
    .input-wrap {
      position: relative;
    }

    /* Inputs */
    .form-control-custom {
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
    }

    .form-control-custom:focus {
      border-color: #16a34a;
      background: #ffffff;
      box-shadow: 0 0 0 3.5px rgba(22,163,74,0.12);
    }

    .form-control-custom::placeholder { color: #cbd5e1; }

    /* Password toggle button */
    .pw-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
      color: #94a3b8;
      display: flex;
      align-items: center;
      transition: color 0.2s;
    }
    .pw-toggle:hover { color: #16a34a; }
    .pw-toggle svg {
      width: 18px; height: 18px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .form-control-custom.has-toggle { padding-right: 44px; }

    .invalid-feedback {
      display: block;
      font-size: 12px;
      color: #f43f5e;
      margin-top: 5px;
    }

    /* Forgot password link */
    .forgot-link {
      display: block;
      text-align: right;
      font-size: 12.5px;
      font-weight: 600;
      color: #16a34a;
      margin-top: -8px;
      margin-bottom: 20px;
      text-decoration: none;
      transition: color 0.2s;
    }
    .forgot-link:hover { color: #15803d; text-decoration: underline; }

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
      margin-bottom: 10px;
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

    /* Cancel */
    .btn-cancel {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 12px;
      background: transparent;
      color: #64748b;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 700;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      transition: all 0.25s ease;
      margin-bottom: 20px;
    }
    .btn-cancel:hover {
      border-color: #fca5a5;
      color: #ef4444;
      background: #fff1f2;
    }

    /* Divider */
    .or-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
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

    /* Footer links */
    .card-footer-links {
      text-align: center;
      font-size: 13.5px;
      color: #475569;
    }
    .card-footer-links a {
      color: #16a34a;
      font-weight: 700;
      text-decoration: none;
    }
    .card-footer-links a:hover { color: #15803d; text-decoration: underline; }

    .footer-note {
      text-align: center;
      font-size: 11.5px;
      color: #94a3b8;
      margin-top: 12px;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 820px) {
      .shell { flex-direction: column; max-width: 480px; }
      .panel-left { flex: none; padding: 28px 24px; }
      .feature-list { display: none; }
      .panel-right { padding: 30px 24px; }
    }
  </style>
</head>

<body>

  <div class="shell">

    <!-- ===== LEFT PANEL ===== -->
    <div class="panel-left">
      <div class="brand-logo">
        <a href="{{ url('/') }}">
          <img src="{{ asset('assets/images/logo/Winkel_Shop__2_-removebg-preview.png') }}" alt="Winkel's logo">
        </a>
      </div>

      <h2>Welcome Back!</h2>
      <p>Sign in to access your account and continue your shopping journey.</p>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/></svg>
          </div>
          <span>Track your orders easily</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
          </div>
          <span>Access your wishlist</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <span>Secured account &amp; data</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          </div>
          <span>Exclusive member deals</span>
        </div>
      </div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="panel-right">

      <div class="panel-heading">
        <h3>Sign In</h3>
        <p>Enter your credentials to access your account.</p>
      </div>
      <div class="heading-line"></div>

      {{-- Validation Errors --}}
      @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      {{-- Session Error --}}
      @if(Session::has('error_login'))
      <div>
        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error_login') }}</p>
      </div>
      @endif

      <!-- Form -->
      <form role="form" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="form-group">
          <label class="form-label-custom">Email Address</label>
          <div class="input-wrap">
            <input class="form-control-custom form-control-user"
                   type="text"
                   name="email"
                   value="{{ old('email') }}"
                   autocomplete="email"
                   placeholder="Enter your email address"
                   autofocus>
          </div>
          @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>


        <!-- Password -->
        <div class="form-group" style="margin-bottom: 6px;">
          <label class="form-label-custom">Password</label>
          <div class="input-wrap">
            <input class="form-control-custom form-control-user has-toggle"
                   type="password"
                   id="password-field"
                   name="password"
                   autocomplete="current-password"
                   placeholder="Enter your password">
            <button type="button" class="pw-toggle" onclick="togglePassword()" title="Show/hide password">
              <svg id="eye-icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        @if (Route::has('password.request'))
        <div style="margin-bottom: 18px; margin-top: -8px;">
          <a class="forgot-link" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
        @endif

        <!-- Buttons -->
        <button type="submit" class="btn-submit">
          Sign In <span class="btn-arrow">→</span>
        </button>
        <a class="btn-cancel" href="{{ url('login') }}">✕ Cancel</a>

      </form>

      <div class="or-divider">
        <span>New to WinkelKart?</span>
      </div>

      <div class="card-footer-links">
        <a href="{{ url('register-buyer') }}">Create an account</a>
        &nbsp;or&nbsp;
        <a href="{{ url('register-buisness') }}">Register as Seller</a>
      </div>

      <p class="footer-note">&copy; 2026 WinkelKart &mdash; A UNIT OF SRD TECHNOLOGIES INDIA.</p>

    </div><!-- /panel-right -->

  </div><!-- /shell -->

  <!-- Core Scripts (all unchanged from original) -->
  <script src="{{ asset('assets_admin/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/js-cookie/js.cookie.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets_admin/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
  <script src="{{ asset('assets_admin/js/argon.js?v=1.2.0') }}"></script>

  <script>
    function togglePassword() {
      const field = document.getElementById('password-field');
      const icon = document.getElementById('eye-icon');
      if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
      } else {
        field.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      }
    }
  </script>

</body>
</html>