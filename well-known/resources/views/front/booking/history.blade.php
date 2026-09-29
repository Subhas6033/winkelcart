@extends('front.layouts.app')

@section('content')
<style>
    .bookings-history-bg {
        min-height: 100vh;
        padding: 40px 0;
    }
    .bookings-container {
        background: rgba(255,255,255,0.97);
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        max-width: 1000px;
        margin: 0 auto;
        padding: 36px 32px 32px 32px;
    }
    .bookings-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--wk-blue);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .booking-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
        transition: all 0.2s;
    }
    .booking-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }
    .booking-hotel-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
    }
    .booking-code {
        font-size: 0.9rem;
        color: #1565c0;
        font-weight: 600;
        background: #e3f2fd;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .booking-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }
    .booking-info-item {
        display: flex;
        flex-direction: column;
    }
    .booking-info-label {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 3px;
    }
    .booking-info-value {
        font-weight: 600;
        color: #333;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
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
    .status-completed {
        background: #e3f2fd;
        color: #1565c0;
    }
    .booking-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }
    .btn-booking {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-view {
        background: var(--wk-blue);
        color: #fff;
    }
    .btn-view:hover {
        background: #1565c0;
        color: #fff;
    }
    .btn-cancel {
        background: #ffebee;
        color: #c62828;
    }
    .btn-cancel:hover {
        background: #ffcdd2;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 60px;
        color: #ccc;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        color: #666;
        margin-bottom: 15px;
    }
    .empty-state p {
        color: #888;
        margin-bottom: 25px;
    }
    .btn-browse {
        display: inline-block;
        padding: 12px 28px;
        background: var(--wk-blue);
        color: #fff;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-browse:hover {
        background: #1565c0;
        color: #fff;
    }
</style>

<div class="bookings-history-bg">
    <div class="bookings-container">
        <h1 class="bookings-title">
            <i class="fa fa-calendar-check"></i>
            My Bookings
        </h1>

        @if(session('success'))
            <div class="alert alert-success" style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        @if($bookings->count() > 0)
            @foreach($bookings as $booking)
                <div class="booking-card">
                    <div class="booking-header">
                        <div>
                            <div class="booking-hotel-name">{{ $booking->hotel->name ?? 'Hotel N/A' }}</div>
                            <span class="booking-code">{{ $booking->booking_code }}</span>
                        </div>
                        <span class="status-badge status-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                    </div>
                    
                    <div class="booking-info">
                        <div class="booking-info-item">
                            <span class="booking-info-label">Room Type</span>
                            <span class="booking-info-value">{{ $booking->room->room_type ?? 'N/A' }}</span>
                        </div>
                        <div class="booking-info-item">
                            <span class="booking-info-label">Check-in</span>
                            <span class="booking-info-value">{{ $booking->checkin ? $booking->checkin->format('d M Y') : 'N/A' }}</span>
                        </div>
                        <div class="booking-info-item">
                            <span class="booking-info-label">Check-out</span>
                            <span class="booking-info-value">{{ $booking->checkout ? $booking->checkout->format('d M Y') : 'N/A' }}</span>
                        </div>
                        <div class="booking-info-item">
                            <span class="booking-info-label">Guests</span>
                            <span class="booking-info-value">{{ $booking->guests }} Guest{{ $booking->guests > 1 ? 's' : '' }}</span>
                        </div>
                        <div class="booking-info-item">
                            <span class="booking-info-label">Total Amount</span>
                            <span class="booking-info-value" style="color: #1565c0;">₹{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                        <div class="booking-info-item">
                            <span class="booking-info-label">Payment</span>
                            <span class="booking-info-value">{{ $booking->payment->payment_method ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="booking-actions">
                        <a href="{{ route('booking.confirmation', $booking->booking_code) }}" class="btn-booking btn-view">
                            <i class="fa fa-eye"></i> View Details
                        </a>
                        @if(strtolower($booking->status) === 'pending')
                            <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                @csrf
                                <button type="submit" class="btn-booking btn-cancel">
                                    <i class="fa fa-times"></i> Cancel Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 30px;">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-calendar-times"></i>
                <h3>No Bookings Found</h3>
                <p>You haven't made any hotel bookings yet. Start exploring our hotels!</p>
                <a href="{{ route('hotels.index') }}" class="btn-browse">
                    <i class="fa fa-hotel"></i> Browse Hotels
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
