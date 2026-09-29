<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Rejected – WinkelKart</title>
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
            background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%);
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
            color: #b71c1c;
            margin-bottom: 15px;
        }
        .reason-box {
            background: #ffebee;
            border-left: 4px solid #e53935;
            padding: 15px 20px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .reason-box strong {
            color: #b71c1c;
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
            content: '→';
            position: absolute;
            left: 0;
            color: #e53935;
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
            <h1>KYC Verification Rejected</h1>
            <p>Your seller KYC requires attention</p>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $seller->name }},</p>
            <p>
                We regret to inform you that your Seller KYC verification has been <strong>rejected</strong> by our admin team.
                Please review the details below and re-submit your KYC.
            </p>

            @if($adminNote)
                <div class="reason-box">
                    <strong>Reason from Admin:</strong>
                    <p style="margin: 8px 0 0 0;">{{ $adminNote }}</p>
                </div>
            @else
                <div class="reason-box">
                    <strong>Note:</strong>
                    <p style="margin: 8px 0 0 0;">No specific reason was provided. Please ensure all documents and details are correct and re-submit.</p>
                </div>
            @endif

            <p><strong>What you can do:</strong></p>
            <ul class="steps">
                <li>Review the admin's feedback above</li>
                <li>Correct any issues with your documents or details</li>
                <li>Re-upload clear, valid documents</li>
                <li>Re-submit your KYC for verification</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ url('seller/kyc') }}" class="cta-button">Re-submit KYC →</a>
            </div>

            <p style="margin-top: 25px; color: #666;">
                Once your KYC is approved, you will be able to list and sell products on WinkelKart. If you believe this was a mistake, please contact our support team.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
