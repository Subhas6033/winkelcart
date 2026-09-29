<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\HotelBookingNotification;
use App\Models\AdminNotification;
use Carbon\Carbon;

class BookingController extends Controller
{
    private function isHotelBookableByVerifiedSeller(Hotel $hotel): bool
    {
        return $hotel->isSellableByVerifiedSeller();
    }

    /**
     * Show booking form for a room (direct room booking)
     */
    public function create($room_id)
    {
        $room = Room::with('hotel.seller.kycVerification')->findOrFail($room_id);

        if (!$room->hotel || !$this->isHotelBookableByVerifiedSeller($room->hotel)) {
            return redirect()->route('hotels_resorts')->with('error', 'This hotel is currently unavailable for booking.');
        }

        return view('front.booking.create', compact('room'));
    }

    /**
     * Store a new booking (from hotel details page or room booking page)
     */
    public function store(Request $request, $room_id)
    {
        // Validate input
        $data = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:20',
            'payment_method' => 'required|in:cod,razorpay',
        ]);

        // Get room and hotel
        $room = Room::with('hotel.seller.kycVerification')->findOrFail($room_id);
        $hotel = $room->hotel;

        if (!$hotel || !$this->isHotelBookableByVerifiedSeller($hotel)) {
            return back()->withErrors(['error' => 'This hotel is currently unavailable for booking.'])->withInput();
        }

        // Validate guest count against room capacity
        if ($data['guests'] > $room->max_guests) {
            return back()->withErrors(['guests' => "This room accommodates maximum {$room->max_guests} guests."])->withInput();
        }

        // Check room availability
        if (!Booking::isRoomAvailable($room_id, $data['check_in'], $data['check_out'])) {
            return back()->withErrors(['dates' => 'Room is not available for the selected dates. Please choose different dates.'])->withInput();
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to book a room.');
        }

        // Calculate pricing
        $checkin = Carbon::parse($data['check_in']);
        $checkout = Carbon::parse($data['check_out']);
        $nights = $checkin->diffInDays($checkout);
        $total_price = $room->price * $nights;

        // Map payment method
        $paymentMethodMap = [
            'cod' => 'Cash on Arrival',
            'razorpay' => 'Razorpay',
        ];
        $paymentMethod = $paymentMethodMap[$data['payment_method']] ?? 'Cash on Arrival';

        // Create booking using transaction
        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'hotel_id' => $hotel->id,
                'room_id' => $room_id,
                'checkin' => $data['check_in'],
                'checkout' => $data['check_out'],
                'guests' => $data['guests'],
                'total_price' => $total_price,
                'status' => 'pending',
                'booking_code' => Booking::generateBookingCode(),
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $paymentMethod,
                'payment_status' => 'Pending',
            ]);

            // Update booking with payment_id
            $booking->update(['payment_id' => $payment->id]);

            DB::commit();

            // Send booking confirmation email
            try {
                $user = Auth::user();
                $booking->load(['hotel', 'room']);
                Mail::to($user->email)->send(new HotelBookingNotification($user, $booking));
            } catch (\Exception $mailError) {
                \Log::error('Hotel booking confirmation email failed: ' . $mailError->getMessage());
            }

            // Create admin notification for hotel booking
            AdminNotification::notify(
                AdminNotification::TYPE_BOOKING_MADE,
                'New Hotel Booking',
                'Booking #' . $booking->booking_code . ' made by ' . Auth::user()->name . ' at ' . $hotel->name . ' for ₹' . number_format($total_price, 2),
                [
                    'link' => route('admin.bookings.index'),
                    'related_id' => $booking->id,
                    'related_type' => Booking::class,
                ]
            );

            // For Razorpay, return booking ID so frontend can initiate payment
            if ($data['payment_method'] === 'razorpay') {
                return response()->json([
                    'success' => true,
                    'payment_required' => true,
                    'booking_id' => $booking->id,
                    'amount' => $total_price,
                    'booking_code' => $booking->booking_code,
                ]);
            }

            return redirect()->route('booking.confirmation', $booking->booking_code)
                ->with('success', 'Your booking has been placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while processing your booking. Please try again.'])->withInput();
        }
    }

    /**
     * Show booking confirmation page
     */
    public function confirmation($bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->with(['hotel', 'room', 'user', 'payment'])
            ->firstOrFail();

        // Security: Only allow the booking owner or admin to view
        if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('Admin')) {
            abort(403, 'Unauthorized access.');
        }

        return view('front.booking.confirmation', compact('booking'));
    }

    /**
     * User: View booking history
     */
    public function userBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['hotel', 'room', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('front.booking.history', compact('bookings'));
    }

    /**
     * User: Cancel a pending booking
     */
    public function cancel($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        if (strtolower($booking->status) === 'pending') {
            $booking->update(['status' => 'cancelled']);
            $booking->payment()->update(['payment_status' => 'Cancelled']);

            return back()->with('success', 'Your booking has been cancelled successfully.');
        }

        return back()->withErrors(['error' => 'Only pending bookings can be cancelled.']);
    }

    /**
     * Admin: List all bookings
     */
    public function index(Request $request)
    {

        $query = Booking::with(['user', 'hotel', 'room', 'payment']);

        // Restrict for sellers
        $user = Auth::user();
        if ($user && $user->hasRole('Seller')) {
            $query->whereHas('hotel', function($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by hotel
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('checkin', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('checkout', '<=', $request->to_date);
        }

        // Search by booking code or user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
        $hotels = Hotel::orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'hotels'));
    }

    /**
     * Admin: Show single booking details
     */
    public function show($id)
    {
        $booking = Booking::with(['user', 'hotel', 'room', 'payment'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Admin: Confirm a pending booking
     */
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        if (strtolower($booking->status) === 'pending') {
            $booking->update(['status' => 'confirmed']);
            $booking->payment()->update(['payment_status' => 'Paid']);

            return back()->with('success', 'Booking confirmed successfully.');
        }

        return back()->withErrors(['error' => 'Only pending bookings can be confirmed.']);
    }

    /**
     * Admin: Mark booking as completed
     */
    public function complete($id)
    {
        $booking = Booking::findOrFail($id);

        if (strtolower($booking->status) === 'confirmed') {
            $booking->update(['status' => 'completed']);

            return back()->with('success', 'Booking marked as completed.');
        }

        return back()->withErrors(['error' => 'Only confirmed bookings can be marked as completed.']);
    }

    /**
     * Admin: Cancel a booking
     */
    public function adminCancel($id)
    {
        $booking = Booking::findOrFail($id);

        if (in_array(strtolower($booking->status), ['pending', 'confirmed'])) {
            $booking->update(['status' => 'cancelled']);
            $booking->payment()->update(['payment_status' => 'Cancelled']);

            return back()->with('success', 'Booking cancelled successfully.');
        }

        return back()->withErrors(['error' => 'This booking cannot be cancelled.']);
    }

    /**
     * AJAX: Check room availability
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $available = Booking::isRoomAvailable(
            $request->room_id,
            $request->check_in,
            $request->check_out
        );

        $room = Room::with('hotel.seller.kycVerification')->find($request->room_id);

        if (!$room || !$room->hotel || !$this->isHotelBookableByVerifiedSeller($room->hotel)) {
            return response()->json([
                'available' => false,
                'message' => 'This hotel is currently unavailable for booking.',
            ], 403);
        }

        $nights = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));

        return response()->json([
            'available' => $available,
            'nights' => $nights,
            'price_per_night' => $room->price,
            'total_price' => $room->price * $nights,
        ]);
    }
}
