<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hotel;
use App\Models\Amenity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('seller.kyc.verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    private function abortIfSellerCannotManageHotel(Hotel $hotel): void
    {
        $user = Auth::user();

        if (!$user instanceof User || !$user->hasRole('Seller') || !Schema::hasColumn('hotels', 'created_by')) {
            return;
        }

        if ((int) $hotel->created_by !== (int) $user->id) {
            abort(403, 'Unauthorized access.');
        }
    }

    // Admin: List all rooms for a hotel
    public function index($hotel_id)
    {
        $hotel = Hotel::findOrFail($hotel_id);
        $this->abortIfSellerCannotManageHotel($hotel);

        $rooms = $hotel->rooms()->with('amenities')->get();
        return view('admin.rooms.index', compact('hotel', 'rooms'));
    }

    // Admin: Show create form
    public function create($hotel_id)
    {
        $hotel = Hotel::findOrFail($hotel_id);
        $this->abortIfSellerCannotManageHotel($hotel);

        $amenities = Amenity::all();
        return view('admin.rooms.create', compact('hotel', 'amenities'));
    }

    // Admin: Store new room
    public function store(Request $request, $hotel_id)
    {
        $hotel = Hotel::findOrFail($hotel_id);
        $this->abortIfSellerCannotManageHotel($hotel);

        $data = $request->validate([
            'room_type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'is_available' => 'boolean',
            'amenities' => 'array',
        ]);
        $room = $hotel->rooms()->create($data);
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->input('amenities'));
        }
        return redirect()->route('admin.rooms.index', $hotel_id)->with('success', 'Room added successfully!');
    }

    // Admin: Show edit form
    public function edit($hotel_id, $id)
    {
        $hotel = Hotel::findOrFail($hotel_id);
        $this->abortIfSellerCannotManageHotel($hotel);

        $room = Room::where('hotel_id', $hotel_id)->findOrFail($id);
        $amenities = Amenity::all();
        return view('admin.rooms.edit', compact('hotel', 'room', 'amenities'));
    }

    // Admin: Update room
    public function update(Request $request, $hotel_id, $id)
    {
        $hotel = Hotel::findOrFail($hotel_id);
        $this->abortIfSellerCannotManageHotel($hotel);

        $room = Room::where('hotel_id', $hotel_id)->findOrFail($id);
        $data = $request->validate([
            'room_type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'is_available' => 'boolean',
            'amenities' => 'array',
        ]);
        $room->update($data);
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->input('amenities'));
        }
        return redirect()->route('admin.rooms.index', $hotel_id)->with('success', 'Room updated successfully!');
    }

    // Admin: Delete room
    public function destroy($hotel_id, $id)
    {
        $hotel = Hotel::findOrFail($hotel_id);
        $this->abortIfSellerCannotManageHotel($hotel);

        $room = Room::where('hotel_id', $hotel_id)->findOrFail($id);
        $room->delete();
        return redirect()->route('admin.rooms.index', $hotel_id)->with('success', 'Room deleted successfully!');
    }
}
