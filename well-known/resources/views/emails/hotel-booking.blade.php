<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - WinkelKart Hotels</title>
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
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .booking-code {
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
            color: #1976d2;
            margin-bottom: 20px;
        }
        .hotel-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .hotel-card h2 {
            margin: 0 0 10px 0;
            color: #0d47a1;
            font-size: 24px;
        }
        .hotel-card .room-type {
            font-size: 16px;
            color: #1565c0;
            font-weight: 500;
        }
        .booking-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            font-weight: 500;
            color: #333;
        }
        .dates-section {
            display: flex;
            justify-content: space-around;
            background: #fff;
            border: 2px solid #1976d2;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .date-box {
            text-align: center;
        }
        .date-box .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .date-box .date {
            font-size: 18px;
            font-weight: 700;
            color: #1976d2;
            margin-top: 5px;
        }
        .date-arrow {
            font-size: 24px;
            color: #1976d2;
            display: flex;
            align-items: center;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #1976d2;
            color: white;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
        }
        .info-note {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
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
            <div style="font-size: 40px; margin-bottom: 10px;">🏨</div>
            <h1>Booking Confirmed!</h1>
            <p class="booking-code">Booking Code: {{ $booking->booking_code }}</p>
            <span class="success-badge">🎉 Your stay is booked!</span>
        </div>
        
        <div class="content">
            <p class="greeting">Hello, {{ $user->name }}!</p>
            
            <p>Great news! Your hotel booking has been confirmed. Here are your booking details:</p>
            
            <div class="hotel-card">
                <div style="font-size: 30px; margin-bottom: 10px;">🏰</div>
                <h2>{{ $hotel->name ?? 'Hotel' }}</h2>
                <p class="room-type">{{ $room->room_type ?? 'Standard Room' }}</p>
                @if($hotel->location ?? false)
                <p style="color: #666; font-size: 14px;">📍 {{ $hotel->location }}</p>
                @endif
            </div>
            
            <div class="dates-section">
                <div class="date-box">
                    <div class="label">Check-in</div>
                    <div class="date">{{ \Carbon\Carbon::parse($booking->checkin)->format('d M Y') }}</div>
                </div>
                <div class="date-arrow">→</div>
                <div class="date-box">
                    <div class="label">Check-out</div>
                    <div class="date">{{ \Carbon\Carbon::parse($booking->checkout)->format('d M Y') }}</div>
                </div>
            </div>
            
            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">👥 Guests</span>
                    <span class="detail-value">{{ $booking->guests }} Guest(s)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">🌙 Nights</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->checkin)->diffInDays(\Carbon\Carbon::parse($booking->checkout)) }} Night(s)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">📊 Status</span>
                    <span class="detail-value" style="color: #ff9800; font-weight: 600;">{{ ucfirst($booking->status) }}</span>
                </div>
                
                <div class="total-row">
                    <span>Total Amount</span>
                    <span>₹{{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
            
            <div class="info-note">
                <strong>📋 Important Information</strong><br>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Check-in time: 2:00 PM</li>
                    <li>Check-out time: 11:00 AM</li>
                    <li>Please carry a valid ID proof</li>
                    <li>Show this email or booking code at the reception</li>
                </ul>
            </div>
            
            <center>
                <a href="{{ url('/my-bookings') }}" class="btn">View My Bookings</a>
            </center>
        </div>
        
        <div class="footer">
            <p>Thank you for booking with WinkelKart Hotels! 🏨</p>
            <p>© {{ date('Y') }} WinkelKart - India's Own Shopping Destination</p>
            <p><a href="{{ url('/contact') }}">Need Help?</a> | <a href="{{ url('/hotels-and-resorts') }}">Explore More Hotels</a></p>
        </div>
    </div>
</body>
</html>
