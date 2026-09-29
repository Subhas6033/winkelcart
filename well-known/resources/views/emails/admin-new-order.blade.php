<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Placed – Admin Alert – WinkelKart</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
        .email-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #6a1b9a 0%, #4a148c 100%); color: white; padding: 35px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .header p { margin: 8px 0 0 0; font-size: 14px; opacity: 0.9; }
        .order-number { display: inline-block; background: #fdd835; color: #333; padding: 6px 18px; border-radius: 20px; font-weight: 700; margin-top: 10px; font-size: 16px; }
        .content { padding: 30px; }
        .info-box { background: #f3e5f5; border-left: 4px solid #6a1b9a; padding: 15px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .info-box p { margin: 0; color: #4a148c; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        td:first-child { font-weight: 600; color: #555; width: 40%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background: #f5f5f5; padding: 8px 12px; text-align: left; font-size: 13px; }
        .items-table td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        .total-row td { font-weight: 700; background: #fafafa; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #6a1b9a, #4a148c); color: white !important; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; margin: 15px 0; }
        .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; font-size: 13px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🛒 New Order Placed!</h1>
            <div class="order-number">#{{ $order->order_number }}</div>
        </div>
        <div class="content">
            <div class="info-box">
                <p>A new order has been placed on WinkelKart. Please process it promptly.</p>
            </div>

            <p><strong>Customer Details:</strong></p>
            <table>
                <tr><td>Name</td><td>{{ optional($buyer)->name ?: 'Guest' }}</td></tr>
                <tr><td>Email</td><td>{{ optional($buyer)->email ?: 'N/A' }}</td></tr>
                <tr><td>Mobile</td><td>{{ optional($buyer)->mobile ?: 'N/A' }}</td></tr>
                <tr><td>Order Date</td><td>{{ $order->created_at->format('d M Y, H:i') }}</td></tr>
                <tr><td>Payment Method</td><td>{{ $order->payment_method ?? 'N/A' }}</td></tr>
                <tr><td>Payment Status</td><td>{{ $order->payment_status ?? 'N/A' }}</td></tr>
            </table>

            <p><strong>Order Items:</strong></p>
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
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td style="text-align:right;">₹{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ url('admin/orders') }}" class="cta-button">View Order →</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated admin alert. Do not share this email.</p>
        </div>
    </div>
</body>
</html>
