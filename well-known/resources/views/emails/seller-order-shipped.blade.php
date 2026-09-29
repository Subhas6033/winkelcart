<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Dispatched – WinkelKart</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
        .email-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #00695c 0%, #004d40 100%); color: white; padding: 35px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .header p { margin: 8px 0 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 30px; }
        .greeting { font-size: 20px; font-weight: 600; color: #00695c; margin-bottom: 15px; }
        .success-box { background: #e0f2f1; border-left: 4px solid #00695c; padding: 15px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .success-box p { margin: 0; color: #004d40; font-weight: 600; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background: #f5f5f5; padding: 8px 12px; text-align: left; font-size: 13px; }
        .items-table td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        table.info-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table.info-table td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        table.info-table td:first-child { font-weight: 600; color: #555; width: 40%; }
        .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; font-size: 13px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📦 Order Dispatched!</h1>
            <p>Your order #{{ $order->order_number }} has been picked up by courier</p>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $seller->name }},</p>

            <p>An order from your store has been <strong>dispatched</strong> and picked up by the courier.</p>

            <div class="success-box">
                <p>✅ Order #{{ $order->order_number }} is on its way to the customer!</p>
            </div>

            <p><strong>Shipment Details:</strong></p>
            <table class="info-table">
                <tr><td>Order Number</td><td>#{{ $order->order_number }}</td></tr>
                @if($order->awb_code)
                    <tr><td>AWB / Tracking No.</td><td>{{ $order->awb_code }}</td></tr>
                @endif
                @if($order->courier_name)
                    <tr><td>Courier</td><td>{{ $order->courier_name }}</td></tr>
                @endif
                <tr><td>Dispatch Date</td><td>{{ now()->format('d M Y') }}</td></tr>
                <tr><td>Total Amount</td><td>₹{{ number_format($order->total_amount, 2) }}</td></tr>
            </table>

            <p><strong>Items in this order:</strong></p>
            <table class="items-table">
                <tr>
                    <th>Product</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:right;">Price</th>
                </tr>
                @foreach($orderItems as $item)
                    <tr>
                        <td>{{ optional($item->product)->product_name ?? 'Product' }}</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">₹{{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </table>

            <p style="color: #666; margin-top: 20px;">Settlement for this order will be processed after successful delivery. If you have questions, contact our seller support team.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
