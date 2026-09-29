<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - WinkelKart</title>
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
            background: linear-gradient(135deg, #1976d2 0%, #43a047 100%);
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
        .status-badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #e8f5e9; color: #2e7d32; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #1976d2;
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
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
        .footer a {
            color: #1976d2;
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #1976d2;
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
            <div style="font-size: 40px; margin-bottom: 10px;">
                @if(strtolower($status) === 'processing') 🛠️
                @elseif(strtolower($status) === 'shipped') 🚚
                @elseif(strtolower($status) === 'delivered') 🎉
                @elseif(strtolower($status) === 'cancelled') ❌
                @else 📦
                @endif
            </div>
            <h1>
                @if(strtolower($status) === 'processing') Order Processing
                @elseif(strtolower($status) === 'shipped') Order Shipped
                @elseif(strtolower($status) === 'delivered') Order Delivered
                @elseif(strtolower($status) === 'cancelled') Order Cancelled
                @else Order Update
                @endif
            </h1>
            <p class="order-number">Order #{{ $order->order_number }}</p>
            <span class="status-badge status-{{ strtolower($status) }}">{{ ucfirst($status) }}</span>
        </div>
        <div class="content">
            <p class="greeting">Hello, {{ $user->name }}!</p>
            <p>
                @if(strtolower($status) === 'processing')
                    Your order is now being processed. Our team is preparing your items for shipment.
                @elseif(strtolower($status) === 'shipped')
                    Good news! Your order has been shipped and is on its way to you.
                @elseif(strtolower($status) === 'delivered')
                    Hooray! Your order has been delivered. We hope you enjoy your purchase.
                @elseif(strtolower($status) === 'cancelled')
                    We're sorry to inform you that your order has been cancelled.
                @else
                    Your order status has been updated to <strong>{{ ucfirst($status) }}</strong>.
                @endif
            </p>
            @if($note)
                <div style="background: #fffbe6; border-left: 4px solid #ffe082; padding: 12px 18px; border-radius: 8px; margin: 18px 0; color: #856404;">
                    <strong>Note:</strong> {{ $note }}
                </div>
            @endif
            <div class="order-summary">
                <h3>📦 Order Summary</h3>
                @foreach($order->items as $item)
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
