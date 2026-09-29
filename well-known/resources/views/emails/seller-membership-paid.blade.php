<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Payment Confirmed – WinkelKart</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
        .email-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%); color: white; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 26px; font-weight: 700; }
        .header p { margin: 10px 0 0 0; font-size: 15px; opacity: 0.9; }
        .content { padding: 30px; }
        .greeting { font-size: 20px; font-weight: 600; color: #2e7d32; margin-bottom: 15px; }
        .success-box { background: #e8f5e9; border-left: 4px solid #43a047; padding: 15px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .success-box p { margin: 0; color: #2e7d32; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        td:first-child { font-weight: 600; color: #555; width: 40%; }
        .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; font-size: 13px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>💳 Payment Confirmed!</h1>
            <p>Your ₹999 membership fee has been received</p>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $seller->name }},</p>

            <p>Your WinkelKart Seller Membership payment of <strong>₹999</strong> has been successfully received and verified.</p>

            <div class="success-box">
                <p>✅ Your 1-year seller membership is now active!</p>
            </div>

            <p><strong>Payment Details:</strong></p>
            <table>
                <tr><td>Amount Paid</td><td>₹999.00</td></tr>
                <tr><td>Payment Method</td><td>Razorpay (Online)</td></tr>
                @if(optional($kyc)->razorpay_payment_id)
                    <tr><td>Payment ID</td><td><code>{{ $kyc->razorpay_payment_id }}</code></td></tr>
                @endif
                @if(optional($kyc)->membership_start)
                    <tr><td>Membership Start</td><td>{{ $kyc->membership_start->format('d M Y') }}</td></tr>
                @endif
                @if(optional($kyc)->membership_expiry)
                    <tr><td>Valid Until</td><td>{{ $kyc->membership_expiry->format('d M Y') }}</td></tr>
                @endif
            </table>

            <p style="color: #666; margin-top: 15px;">
                Your KYC documents are still being reviewed by our admin team. You will receive another email once your full KYC is approved and you can start selling.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
