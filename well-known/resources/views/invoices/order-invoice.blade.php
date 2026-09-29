<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .invoice-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }
        .gstin {
            font-size: 11px;
            color: #555;
            font-weight: bold;
        }
        .invoice-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #e74c3c;
            margin: 15px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .invoice-meta {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice-meta-left, .invoice-meta-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-meta-right {
            text-align: right;
        }
        .meta-label {
            font-weight: bold;
            color: #555;
        }
        .address-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .billing-address, .shipping-address {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        .billing-address {
            border-right: none;
        }
        .address-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 13px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #2c3e50;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }
        .items-table th.text-right,
        .items-table td.text-right {
            text-align: right;
        }
        .items-table th.text-center,
        .items-table td.text-center {
            text-align: center;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        .items-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .product-name {
            font-weight: bold;
            color: #2c3e50;
        }
        .seller-info {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
        .summary-section {
            width: 100%;
            display: table;
        }
        .summary-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .summary-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        .totals-table .label {
            background: #f5f5f5;
            font-weight: bold;
            width: 60%;
        }
        .totals-table .value {
            text-align: right;
            width: 40%;
        }
        .grand-total {
            background: #2c3e50 !important;
            color: white !important;
            font-size: 14px;
        }
        .grand-total .label,
        .grand-total .value {
            background: #2c3e50 !important;
            color: white !important;
        }
        .payment-info {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .payment-info-title {
            font-weight: bold;
            color: #0c5460;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 2px solid #2c3e50;
            padding-top: 15px;
        }
        .footer-text {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        .thank-you {
            font-size: 16px;
            font-weight: bold;
            color: #27ae60;
            margin-bottom: 10px;
        }
        .note {
            font-size: 10px;
            color: #888;
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">WinkelKart</div>
                <div class="company-tagline">A UNIT OF SRD TECHNOLOGIES INDIA</div>
                <div class="gstin">GSTIN: 19DFEPR9642F1ZY</div>
            </div>
        </div>

        <div class="invoice-title">Tax Invoice</div>

        <!-- Invoice Meta -->
        <div class="invoice-meta">
            <div class="invoice-meta-left">
                <p><span class="meta-label">Invoice No:</span> INV-{{ $order->order_number }}</p>
                <p><span class="meta-label">Order No:</span> {{ $order->order_number }}</p>
                <p><span class="meta-label">Order Date:</span> {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div class="invoice-meta-right">
                <p><span class="meta-label">Payment Method:</span> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                <p><span class="meta-label">Payment Status:</span> {{ $order->order_status ?? 'Pending' }}</p>
            </div>
        </div>

        <!-- Addresses -->
        <div class="address-section">
            <div class="billing-address">
                <div class="address-title">Bill To:</div>
                <p><strong>{{ $buyer->name }}</strong></p>
                <p>{{ $buyer->email }}</p>
                @if($buyer->user_info)
                    <p>{{ $buyer->user_info->phone ?? '' }}</p>
                    <p>{{ $buyer->user_info->address ?? '' }}</p>
                    <p>{{ $buyer->user_info->city ?? '' }}{{ $buyer->user_info->state ? ', ' . $buyer->user_info->state : '' }}</p>
                    <p>{{ $buyer->user_info->pincode ?? '' }}</p>
                @endif
            </div>
            <div class="shipping-address">
                <div class="address-title">Ship To:</div>
                <p><strong>{{ $buyer->name }}</strong></p>
                <p>{{ $order->shipping_address ?? ($buyer->user_info->address ?? 'N/A') }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Product</th>
                    <th class="text-right" style="width: 10%;">MRP</th>
                    <th class="text-right" style="width: 10%;">Base Price</th>
                    <th class="text-right" style="width: 10%;">Platform Fee</th>
                    <th class="text-center" style="width: 5%;">Qty</th>
                    <th class="text-right" style="width: 8%;">Tax %</th>
                    <th class="text-right" style="width: 10%;">Tax Amt</th>
                    <th class="text-right" style="width: 12%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $totalTax = 0;
                    $totalPlatformFee = 0;
                @endphp
                @foreach($orderItems as $index => $item)
                    @php
                        $product = $item->product;
                        $basePrice = $product->offer_price ?? 0;
                        $mrp = $product->total_price ?? 0;
                        $taxPercent = $product->tax ?? 0;
                        $platformFee = ($basePrice * 10) / 100;
                        $offerPrice = $basePrice + $platformFee;
                        $taxAmount = ($offerPrice * $taxPercent) / 100;
                        $finalPrice = $offerPrice + $taxAmount;
                        
                        $itemTotal = $finalPrice * $item->quantity;
                        $itemTax = $taxAmount * $item->quantity;
                        $itemPlatformFee = $platformFee * $item->quantity;
                        
                        $subtotal += ($basePrice * $item->quantity);
                        $totalTax += $itemTax;
                        $totalPlatformFee += $itemPlatformFee;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="product-name">{{ $product->name }}</div>
                            @if($product->seller)
                                <div class="seller-info">Seller: {{ $product->seller->name }}</div>
                            @endif
                        </td>
                        <td class="text-right">₹{{ number_format($mrp, 2) }}</td>
                        <td class="text-right">₹{{ number_format($basePrice, 2) }}</td>
                        <td class="text-right">₹{{ number_format($platformFee, 2) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ $taxPercent }}%</td>
                        <td class="text-right">₹{{ number_format($itemTax, 2) }}</td>
                        <td class="text-right">₹{{ number_format($basePrice, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-left">
                <div class="payment-info">
                    <div class="payment-info-title">Payment Information</div>
                    <p><strong>Method:</strong> {{ ucfirst($order->payment_method ?? 'Cash on Delivery') }}</p>
                    <p><strong>Payment Status:</strong> {{ $order->payment_status ?? 'Pending' }}</p>
                    @if($order->razorpay_payment_id)
                        <p><strong>Transaction ID:</strong> {{ $order->razorpay_payment_id }}</p>
                    @endif
                    @if($order->razorpay_order_id)
                        <p><strong>Order Ref:</strong> {{ $order->razorpay_order_id }}</p>
                    @endif
                </div>

                @if(!empty($order->shipping_breakdown) && is_array($order->shipping_breakdown) && count($order->shipping_breakdown) > 0)
                <div class="payment-info" style="background:#f0fdf4; border-color:#86efac; margin-top:10px;">
                    <div class="payment-info-title" style="color:#166534;">Shipping Details (per Seller)</div>
                    @foreach($order->shipping_breakdown as $s)
                        <p>
                            <strong>{{ $s['seller_name'] ?? 'Seller' }}:</strong>
                            @if(!empty($s['no_service']))
                                No courier available
                            @else
                                ₹{{ number_format((float)($s['shipping_cost'] ?? 0), 2) }}
                                @if(!empty($s['weight_kg']))
                                    &nbsp;({{ $s['weight_kg'] }} kg)
                                @endif
                                @if(!empty($s['available_couriers'][0]['courier_name']))
                                    &mdash; {{ $s['available_couriers'][0]['courier_name'] }}
                                @endif
                                @if(!empty($s['available_couriers'][0]['estimated_delivery_days']))
                                    &nbsp;· Est. {{ $s['available_couriers'][0]['estimated_delivery_days'] }} day(s)
                                @endif
                            @endif
                        </p>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="summary-right">
                <table class="totals-table">
                    <tr>
                        <td class="label">Subtotal (Base Price)</td>
                        <td class="value">₹{{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Platform Fee</td>
                        <td class="value">₹{{ number_format($totalPlatformFee, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Tax (GST)</td>
                        <td class="value">₹{{ number_format($totalTax, 2) }}</td>
                    </tr>
                    @php
                        $shippingCost = (float) ($order->shipping_cost ?? 0);
                        $grandTotal   = (float) ($order->grand_total ?? $order->total_amount ?? ($subtotal + $totalPlatformFee + $totalTax + $shippingCost));
                    @endphp
                    <tr>
                        <td class="label">Shipping Cost
                            @if($shippingCost == 0) <span style="font-weight:normal; color:#16a34a;">(Free)</span>@endif
                        </td>
                        <td class="value">₹{{ number_format($shippingCost, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="label">Grand Total</td>
                        <td class="value">₹{{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Thank You for Shopping with WinkelKart!</div>
            <div class="footer-text">WinkelKart - A UNIT OF SRD TECHNOLOGIES INDIA</div>
            <div class="footer-text">GSTIN: 19DFEPR9642F1ZY</div>
            <div class="footer-text">For any queries, please contact support@winkelkart.com</div>
            <div class="note">This is a computer-generated invoice and does not require a signature.</div>
        </div>
    </div>
</body>
</html>
