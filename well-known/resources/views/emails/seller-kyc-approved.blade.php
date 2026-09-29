<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Approved – WinkelKart</title>
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
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 22px;
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 15px;
        }
        .highlight-box {
            background: #e8f5e9;
            border-left: 4px solid #43a047;
            padding: 15px 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .highlight-box p {
            margin: 0;
            font-weight: 600;
            color: #2e7d32;
        }
        .steps {
            margin: 20px 0;
            padding: 0;
            list-style: none;
        }
        .steps li {
            padding: 8px 0 8px 30px;
            position: relative;
        }
        .steps li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #43a047;
            font-weight: bold;
            font-size: 18px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #43a047, #2e7d32);
            color: white !important;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 KYC Verified!</h1>
            <p>Your seller account is now fully approved</p>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $seller->name }},</p>
            <p>
                Great news! Your Seller KYC verification has been <strong>approved</strong> by our admin team.
                Your membership payment has been confirmed and all your documents have been verified.
            </p>

            <div class="highlight-box">
                <p>✅ You can now list and sell products on WinkelKart!</p>
            </div>

            <p><strong>Here's what you can do now:</strong></p>
            <ul class="steps">
                <li>Add new products from your Product Management dashboard</li>
                <li>Set prices, upload images, and manage inventory</li>
                <li>Track your orders and settlements</li>
                <li>Manage your seller profile and business details</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ url('admin/products/create') }}" class="cta-button">Start Adding Products →</a>
            </div>

            <p style="margin-top: 25px; color: #666;">
                If you have any questions, feel free to reach out to our support team. We're here to help you succeed!
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
