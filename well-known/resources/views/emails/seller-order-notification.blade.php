<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received - WinkelKart</title>
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
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
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
        .new-order-badge {
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
            color: #1565c0;
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
            color: #1565c0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #1565c0;
            color: white;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
        }
        .info-section {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-section h3 {
            margin: 0 0 15px 0;
            color: #1565c0;
            font-size: 16px;
        }
        .info-row {
            padding: 8px 0;
            border-bottom: 1px dashed #90caf9;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #333;
        }
        .info-value {
            color: #555;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #1565c0;
            text-decoration: none;
        }
        .alert-box {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .alert-box strong {
            color: #e65100;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🛒 WinkelKart</h1>
            <div class="order-number">Order #{{ $order->order_number }}</div>
            <div class="new-order-badge">✨ New Order Received!</div>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $seller->name }}!</p>
            
            <p>Great news! You have received a new order for your products. Please review the order details below and prepare the items for shipping.</p>
            
            <div class="alert-box">
                <strong>Action Required:</strong> Please update the order status in your seller dashboard once you start processing this order.
            </div>
            
            <div class="order-summary">
                <h3>📦 Your Products in This Order</h3>
                @foreach($sellerItems as $item)
                <div class="item-row">
                    <span class="item-name">{{ $item->product->name ?? 'Product' }}</span>
                    <span class="item-qty">× {{ $item->quantity }}</span>
                    <span class="item-price">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
                
                <div class="total-row">
                    <span>Your Earnings</span>
                    <span>₹{{ number_format($sellerTotal, 2) }}</span>
                </div>
            </div>
            
            <div class="info-section">
                <h3>👤 Customer Details</h3>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $buyer->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $buyer->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Delivery Address:</span>
                    <span class="info-value">{{ $order->shipping_address }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('seller/order-status-timelines') }}" class="cta-button">
                    View Order in Dashboard →
                </a>
            </div>
            
            <p style="color: #666; font-size: 14px; margin-top: 20px;">
                Please ensure timely dispatch of the products. If you have any questions, please contact our support team.
            </p>
        </div>
        
        <div class="footer">
            <p>Thank you for being a WinkelKart seller!</p>
            <p>© {{ date('Y') }} WinkelKart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
