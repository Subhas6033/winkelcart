<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    public function show($id)
    {
        $hotel = Product::findOrFail($id);
        // You can add more logic to fetch hotel-specific data, images, amenities, etc.
        return view('front.hotel-details', compact('hotel'));
    }

    public function book(Request $request, $id)
    {
        $request->validate([
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'guests' => 'required|integer|min:1',
            'room' => 'required|string',
        ]);

        // Save booking logic here (e.g., create a HotelBooking model, send email, etc.)
        // For now, just redirect back with a success message
        return back()->with('success', 'Hotel booking request submitted! We will contact you soon.');
    }
}