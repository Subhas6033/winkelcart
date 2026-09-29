<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Has Been Shipped – WinkelKart</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
        .email-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #0288d1 0%, #01579b 100%); color: white; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 26px; font-weight: 700; }
        .header p { margin: 10px 0 0 0; font-size: 15px; opacity: 0.9; }
        .order-badge { display: inline-block; background: #fdd835; color: #333; padding: 6px 18px; border-radius: 20px; font-weight: 700; margin-top: 10px; font-size: 15px; }
        .content { padding: 30px; }
        .greeting { font-size: 20px; font-weight: 600; color: #01579b; margin-bottom: 15px; }
        .tracking-box { background: #e1f5fe; border: 2px solid #0288d1; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: center; }
        .tracking-box .awb { font-size: 22px; font-weight: 700; color: #01579b; letter-spacing: 2px; }
        .tracking-box .label { font-size: 12px; color: #555; text-transform: uppercase; margin-bottom: 5px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; font-size: 14px; }
        .info-row span:first-child { font-weight: 600; color: #555; }
        .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; font-size: 13px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🚚 Your Order is on the Way!</h1>
            <p>Your package has been shipped and is heading your way</p>
            <div class="order-badge">Order #{{ $order->order_number }}</div>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $user->name }},</p>

            <p>Great news! Your order <strong>#{{ $order->order_number }}</strong> has been shipped and is on its way to you.</p>

            @if($awbCode)
                <div class="tracking-box">
                    <div class="label">Tracking Number (AWB)</div>
                    <div class="awb">{{ $awbCode }}</div>
                    @if($courierName)
                        <div style="margin-top: 8px; color: #555; font-size: 14px;">Courier: <strong>{{ $courierName }}</strong></div>
                    @endif
                </div>
            @endif

            <p><strong>Shipment Summary:</strong></p>
            <div class="info-row"><span>Order Number</span><span>#{{ $order->order_number }}</span></div>
            <div class="info-row"><span>Shipped Date</span><span>{{ now()->format('d M Y') }}</span></div>
            @if($awbCode)
                <div class="info-row"><span>AWB / Tracking No.</span><span>{{ $awbCode }}</span></div>
            @endif
            @if($courierName)
                <div class="info-row"><span>Courier Partner</span><span>{{ $courierName }}</span></div>
            @endif
            <div class="info-row"><span>Delivery Address</span>
                <span>
                    @if($order->buyer?->user_info)
                        {{ optional($order->buyer->user_info)->address }},
                        {{ optional($order->buyer->user_info)->city }}
                    @else
                        As provided at checkout
                    @endif
                </span>
            </div>

            <p style="color: #666; margin-top: 20px;">
                You can track your shipment using the AWB number above on the courier's website.
                Delivery typically takes 3–7 business days depending on your location.
            </p>

            <p style="color: #666;">If you have any questions about your delivery, contact us at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
