@extends('front.layouts.app')

@section('content')
<style>
    .booking-confirmation-bg {
        min-height: 100vh;
        padding: 40px 0;
    }
    .confirmation-container {
        background: rgba(255,255,255,0.97);
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        max-width: 700px;
        margin: 0 auto;
        padding: 36px 32px 32px 32px;
        text-align: center;
    }
    .confirmation-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #43a047, #66bb6a);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .confirmation-icon i {
        font-size: 40px;
        color: #fff;
    }
    .confirmation-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #388e3c;
        margin-bottom: 10px;
    }
    .booking-code {
        background: #e8f5e9;
        border-radius: 10px;
        padding: 15px 25px;
        display: inline-block;
        margin: 20px 0;
    }
    .booking-code-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }
    .booking-code-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1565c0;
        letter-spacing: 2px;
    }
    .booking-details-card {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        text-align: left;
    }
    .booking-details-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .booking-details-row:last-child {
        border-bottom: none;
    }
    .booking-details-label {
        color: #666;
        font-weight: 500;
    }
    .booking-details-value {
        font-weight: 600;
        color: #333;
    }
    .booking-total {
        background: #e3f2fd;
        border-radius: 10px;
        padding: 15px 20px;
        margin: 20px 0;
    }
    .booking-total-label {
        font-size: 1rem;
        color: #666;
    }
    .booking-total-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1565c0;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending {
        background: #fff3e0;
        color: #e65100;
    }
    .status-confirmed {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .status-cancelled {
        background: #ffebee;
        color: #c62828;
    }
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 25px;
        flex-wrap: wrap;
    }
    .btn-action {
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-primary-action {
        background: var(--wk-blue);
        color: #fff;
    }
    .btn-primary-action:hover {
        background: #1565c0;
        color: #fff;
    }
    .btn-secondary-action {
        background: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
    }
    .btn-secondary-action:hover {
        background: #e0e0e0;
        color: #333;
    }
</style>

<div class="booking-confirmation-bg">
    <div class="confirmation-container">
        <div class="confirmation-icon">
            <i class="fa fa-check"></i>
        </div>
        <h1 class="confirmation-title">Booking Confirmed!</h1>
        <p style="color: #666; margin-bottom: 20px;">Your booking has been successfully placed. Please save your booking code for reference.</p>
        
        <div class="booking-code">
            <div class="booking-code-label">Booking Code</div>
            <div class="booking-code-value">{{ $booking->booking_code }}</div>
        </div>

        <div class="booking-details-card">
            <div class="booking-details-row">
                <span class="booking-details-label">Hotel</span>
                <span class="booking-details-value">{{ $booking->hotel->name ?? 'N/A' }}</span>
            </div>
            <div class="booking-details-row">
                <span class="booking-details-label">Room Type</span>
                <span class="booking-details-value">{{ $booking->room->room_type ?? 'N/A' }}</span>
            </div>
            <div class="booking-details-row">
                <span class="booking-details-label">Check-in Date</span>
                <span class="booking-details-value">{{ $booking->checkin->format('D, d M Y') }}</span>
            </div>
            <div class="booking-details-row">
                <span class="booking-details-label">Check-out Date</span>
                <span class="booking-details-value">{{ $booking->checkout->format('D, d M Y') }}</span>
            </div>
            <div class="booking-details-row">
                <span class="booking-details-label">Number of Nights</span>
                <span class="booking-details-value">{{ $booking->nights }} Night{{ $booking->nights > 1 ? 's' : '' }}</span>
            </div>
            <div class="booking-details-row">
                <span class="booking-details-label">Guests</span>
                <span class="booking-details-value">{{ $booking->guests }} Guest{{ $booking->guests > 1 ? 's' : '' }}</span>
            </div>
            <div class="booking-details-row">
                <span class="booking-details-label">Payment Method</span>
                <span class="booking-details-value">{{ $booking->payment->payment_method ?? 'N/A' }}</span>
            </div>
            <div class="booking-details-row">
                <span class="booking-details-label">Status</span>
                <span class="status-badge status-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
            </div>
        </div>

        <div class="booking-total">
            <div class="booking-total-label">Total Amount</div>
            <div class="booking-total-value">₹{{ number_format($booking->total_price, 2) }}</div>
        </div>

        <p style="color: #888; font-size: 0.9rem; margin-bottom: 15px;">
            <i class="fa fa-info-circle"></i> 
            @if(strtolower($booking->status) === 'pending')
                Your booking is pending confirmation. You will receive a confirmation email shortly.
            @else
                Your booking is confirmed! We look forward to hosting you.
            @endif
        </p>

        <div class="action-buttons">
            <a href="{{ route('user.bookings') }}" class="btn-action btn-primary-action">
                <i class="fa fa-list"></i> View My Bookings
            </a>
            <a href="{{ route('hotels.index') }}" class="btn-action btn-secondary-action">
                <i class="fa fa-hotel"></i> Browse Hotels
            </a>
            <a href="{{ route('index') }}" class="btn-action btn-secondary-action">
                <i class="fa fa-home"></i> Go to Home
            </a>
        </div>
    </div>
</div>
@endsection
