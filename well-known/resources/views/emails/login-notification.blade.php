<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Notification - WinkelKart</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .logo {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #666;
            width: 40%;
        }
        .security-note {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
        .footer a {
            color: #43a047;
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #43a047;
            color: white !important;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🛒</div>
            <h1>WinkelKart</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Hello, {{ $user->name }}!</p>
            
            <p>We detected a new login to your WinkelKart account. Here are the details:</p>
            
            <table class="info-table">
                <tr>
                    <td>📅 Date & Time</td>
                    <td>{{ $loginTime }}</td>
                </tr>
                <tr>
                    <td>🌐 IP Address</td>
                    <td>{{ $ipAddress }}</td>
                </tr>
                <tr>
                    <td>💻 Device</td>
                    <td>{{ Str::limit($userAgent, 50) }}</td>
                </tr>
            </table>
            
            <div class="alert-box">
                <strong>⚠️ Was this you?</strong><br>
                If you did not log in, please change your password immediately to secure your account.
            </div>
            
            <div class="security-note">
                <strong>🔒 Keep your account secure:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Never share your password with anyone</li>
                    <li>Use a strong, unique password</li>
                    <li>Log out from shared devices</li>
                </ul>
            </div>
            
            <center>
                <a href="{{ url('/') }}" class="btn">Visit WinkelKart</a>
            </center>
        </div>
        
        <div class="footer">
            <p>This is an automated security notification from WinkelKart.</p>
            <p>© {{ date('Y') }} WinkelKart - India's Own Shopping Destination</p>
            <p><a href="{{ url('/contact') }}">Contact Us</a> | <a href="{{ url('/') }}">Visit Store</a></p>
        </div>
    </div>
</body>
</html>
