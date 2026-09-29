<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - WinkelKart</title>
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
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .order-number {
            font-size: 16px;
            opacity: 0.9;
        }
        .success-badge {
            display: inline-block;
            background: #fdd835;
            color: #333;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 15px;
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
        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-summary h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 18px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .item-name {
            flex: 1;
            font-weight: 500;
        }
        .item-qty {
            color: #666;
            margin: 0 15px;
        }
        .item-price {
            font-weight: 600;
            color: #2e7d32;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #2e7d32;
            color: white;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
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
        .status-badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 600;
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
        .delivery-info {
            background: #e3f2fd;
            border-left: 4px solid #1976d2;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div style="font-size: 40px; margin-bottom: 10px;">✅</div>
            <h1>Order Confirmed!</h1>
            <p class="order-number">Order #{{ $order->order_number }}</p>
            <span class="success-badge">🎉 Thank you for your purchase!</span>
        </div>
        
        <div class="content">
            <p class="greeting">Hello, {{ $user->name }}!</p>
            
            <p>Great news! Your order has been placed successfully. We're getting it ready for you!</p>
            
            <div class="order-summary">
                <h3>📦 Order Summary</h3>
                
                @foreach($orderItems as $item)
                <div class="item-row">
                    <span class="item-name">{{ $item->product->name ?? 'Product' }}</span>
                    <span class="item-qty">x{{ $item->quantity }}</span>
                    <span class="item-price">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
                
                <div class="total-row">
                    <span>Total Amount</span>
                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
            
            <table class="info-table">
                <tr>
                    <td>📅 Order Date</td>
                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <td>💳 Payment Method</td>
                    <td>{{ ucfirst($order->payment_method ?? 'COD') }}</td>
                </tr>
                <tr>
                    <td>📍 Delivery Address</td>
                    <td>{{ $order->shipping_address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>📊 Order Status</td>
                    <td><span class="status-badge">{{ $order->order_status ?? 'Pending' }}</span></td>
                </tr>
            </table>
            
            <div class="delivery-info">
                <strong>🚚 Delivery Information</strong><br>
                Your order will be delivered within 5-7 business days. You'll receive tracking updates via email.
            </div>
            
            <div class="delivery-info" style="background: #e8f5e9; border-left-color: #43a047;">
                <strong>📄 Invoice Attached</strong><br>
                Your tax invoice is attached to this email as a PDF. Please keep it for your records.
            </div>
            
            <center>
                <a href="{{ url('/my-orders') }}" class="btn">Track Your Order</a>
            </center>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with WinkelKart! 🛒</p>
            <p><strong>WinkelKart</strong> - A UNIT OF SRD TECHNOLOGIES INDIA</p>
            <p style="font-size: 11px; color: #888;">GSTIN: 19DFEPR9642F1ZY</p>
            <p>© {{ date('Y') }} WinkelKart - India's Own Shopping Destination</p>
            <p><a href="{{ url('/contact') }}">Need Help?</a> | <a href="{{ url('/') }}">Continue Shopping</a></p>
        </div>
    </div>
</body>
</html>
