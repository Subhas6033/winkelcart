<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password | WinkelKart</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('assets/images/logo/Winkel_Shop__2_-removebg-preview.png') }}" type="image/png">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .shell {
      display: flex;
      max-width: 900px;
      width: 100%;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 25px 60px rgba(22,163,74,0.15), 0 0 0 1px rgba(22,163,74,0.05);
      overflow: hidden;
    }

    /* ================= LEFT PANEL ================= */
    .panel-left {
      flex: 0 0 42%;
      background: linear-gradient(160deg, #16a34a 0%, #15803d 50%, #14532d 100%);
      padding: 40px 32px;
      color: #fff;
      display: flex;
      flex-direction: column;
    }

    .brand-logo {
      margin-bottom: 28px;
    }
    .brand-logo img {
      max-width: 150px;
      height: auto;
    }

    .panel-left h2 {
      font-size: 28px;
      font-weight: 800;
      line-height: 1.25;
      margin-bottom: 10px;
      letter-spacing: -0.5px;
    }

    .panel-left p {
      font-size: 14px;
      line-height: 1.6;
      opacity: 0.9;
      margin-bottom: 28px;
    }

    .feature-list {
      margin-top: auto;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 14px;
      font-size: 13.5px;
      opacity: 0.9;
    }

    .feature-icon {
      width: 32px;
      height: 32px;
      background: rgba(255,255,255,0.15);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .feature-icon svg {
      width: 16px; height: 16px;
      stroke: #fff;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
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

    /* Form */
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

    .input-wrap {
      position: relative;
    }

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

    .form-control-custom.has-toggle { padding-right: 44px; }

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

    .invalid-feedback {
      display: block;
      font-size: 12px;
      color: #f43f5e;
      margin-top: 5px;
    }

    /* Password strength indicator */
    .password-strength {
      margin-top: 8px;
      font-size: 12px;
    }
    .password-strength .bar {
      height: 4px;
      border-radius: 2px;
      background: #e2e8f0;
      margin-bottom: 4px;
    }
    .password-strength .bar-fill {
      height: 100%;
      border-radius: 2px;
      width: 0;
      transition: width 0.3s, background 0.3s;
    }

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

    /* Back to login */
    .back-link {
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
    .back-link:hover {
      border-color: #16a34a;
      color: #16a34a;
      background: #f0fdf4;
    }

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

      <h2>Create New Password</h2>
      <p>Choose a strong password with at least 6 characters including letters and numbers.</p>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <span>Use 6+ characters</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
          </div>
          <span>Mix letters & numbers</span>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          </div>
          <span>Keep it unique & private</span>
        </div>
      </div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="panel-right">

      <div class="panel-heading">
        <h3>Set New Password</h3>
        <p>Enter your email and create a new password for your account.</p>
      </div>
      <div class="heading-line"></div>

      {{-- Success Message --}}
      @if(Session::has('success'))
      <div class="alert alert-success">
        {{ Session::get('success') }}
      </div>
      @endif

      {{-- Error Message --}}
      @if(Session::has('error'))
      <div class="alert alert-danger">
        {{ Session::get('error') }}
      </div>
      @endif

      {{-- Validation Errors --}}
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <!-- Form -->
      <form role="form" method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <div class="form-group">
          <label class="form-label-custom">Email Address</label>
          <input class="form-control-custom"
                 type="email"
                 name="email"
                 value="{{ old('email') }}"
                 autocomplete="email"
                 placeholder="Enter your registered email"
                 required>
          @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <!-- New Password -->
        <div class="form-group">
          <label class="form-label-custom">New Password</label>
          <div class="input-wrap">
            <input class="form-control-custom has-toggle"
                   type="password"
                   id="password"
                   name="password"
                   autocomplete="new-password"
                   placeholder="Enter new password"
                   required
                   minlength="6">
            <button type="button" class="pw-toggle" onclick="togglePassword('password', 'eye-icon-1')" title="Show/hide password">
              <svg id="eye-icon-1" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
          <label class="form-label-custom">Confirm Password</label>
          <div class="input-wrap">
            <input class="form-control-custom has-toggle"
                   type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   autocomplete="new-password"
                   placeholder="Confirm new password"
                   required
                   minlength="6">
            <button type="button" class="pw-toggle" onclick="togglePassword('password_confirmation', 'eye-icon-2')" title="Show/hide password">
              <svg id="eye-icon-2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-submit">
          Reset Password <span class="btn-arrow">→</span>
        </button>

        <!-- Back to Login -->
        <a class="back-link" href="{{ url('login') }}">← Back to Sign In</a>

      </form>

      <p class="footer-note">&copy; 2026 WinkelKart &mdash; A UNIT OF SRD TECHNOLOGIES INDIA.</p>

    </div><!-- /panel-right -->

  </div><!-- /shell -->

  <script>
    function togglePassword(fieldId, iconId) {
      const field = document.getElementById(fieldId);
      const icon = document.getElementById(iconId);
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
