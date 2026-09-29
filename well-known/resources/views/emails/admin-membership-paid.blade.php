<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Payment Received – Admin Alert – WinkelKart</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
        .email-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%); color: white; padding: 35px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .header p { margin: 8px 0 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 30px; }
        .success-box { background: #e8f5e9; border-left: 4px solid #43a047; padding: 15px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .success-box p { margin: 0; color: #2e7d32; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        td:first-child { font-weight: 600; color: #555; width: 40%; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white !important; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; margin: 15px 0; }
        .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; font-size: 13px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>💰 ₹999 Membership Received</h1>
            <p>A seller has paid their annual membership fee</p>
        </div>
        <div class="content">
            <div class="success-box">
                <p>₹999 membership payment received and auto-verified via Razorpay.</p>
            </div>

            <p><strong>Seller Details:</strong></p>
            <table>
                <tr><td>Name</td><td>{{ $seller->name }}</td></tr>
                <tr><td>Email</td><td>{{ $seller->email }}</td></tr>
                <tr><td>Mobile</td><td>{{ $seller->mobile ?: 'Not provided' }}</td></tr>
                <tr><td>Amount</td><td>₹999.00</td></tr>
                @if(optional($kyc)->razorpay_payment_id)
                    <tr><td>Razorpay Payment ID</td><td><code>{{ $kyc->razorpay_payment_id }}</code></td></tr>
                @endif
                @if(optional($kyc)->razorpay_order_id)
                    <tr><td>Razorpay Order ID</td><td><code>{{ $kyc->razorpay_order_id }}</code></td></tr>
                @endif
                @if(optional($kyc)->membership_expiry)
                    <tr><td>Membership Valid Until</td><td>{{ $kyc->membership_expiry->format('d M Y') }}</td></tr>
                @endif
                <tr><td>Payment Time</td><td>{{ now()->format('d M Y, H:i') }}</td></tr>
            </table>

            <p style="color: #666;">The seller's KYC documents still require admin review before they can start selling. Once you approve the KYC, the seller will receive their approval email.</p>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('admin.seller_kyc.index') }}" class="cta-button">Review KYC →</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated admin alert. Do not share this email.</p>
        </div>
    </div>
</body>
</html>
