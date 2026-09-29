<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'frontendList']]);
        $this->middleware('seller.kyc.verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    private function constrainHotelsForSeller($query)
    {
        $user = Auth::user();

        if ($user instanceof User && $user->hasRole('Seller') && Schema::hasColumn('hotels', 'created_by')) {
            $query->where('created_by', $user->id);
        }

        return $query;
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

    // Frontend: Show hotel details
    public function show($id)
    {
        $hotel = Hotel::with(['rooms', 'seller.kycVerification'])->find($id);

        if (!$hotel) {
            return redirect()->route('hotels.index')->with('error', 'Hotel not found.');
        }

        return view('front.hotel-details', compact('hotel'));
    }

    // Admin/Seller: List all hotels (admin panel)
    public function index(Request $request)
    {

        $query = Hotel::query();
        // Sellers only see their own hotels due to constrainHotelsForSeller
        $this->constrainHotelsForSeller($query);

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        if ($request->filled('min_price')) {
            $query->where('min_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('max_price', '<=', $request->max_price);
        }
        $hotels = $query->orderBy('id', 'desc')->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    // Frontend: List all hotels for website
    public function frontendList(Request $request)
    {
        $query = Hotel::query()->fromVerifiedSellers();
        
        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        // Price filters
        if ($request->filled('min_price')) {
            $query->where('min_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('min_price', '<=', $request->max_price);
        }
        
        // Property type filter
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }
        
        // Star rating filter
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }
        
        // Amenities filter (JSON field)
        if ($request->filled('amenities')) {
            $amenityMapping = [
                'WiFi' => 'wifi',
                'Swimming Pool' => 'pool',
                'Spa' => 'spa',
                'Gym' => 'gym',
                'Free Parking' => 'parking',
                'Breakfast Included' => 'breakfast',
                'AC' => 'ac',
                'Pet Friendly' => 'pet_friendly',
            ];
            foreach ($request->amenities as $amenity) {
                $key = $amenityMapping[$amenity] ?? strtolower(str_replace(' ', '_', $amenity));
                $query->whereJsonContains('amenities', $key);
            }
        }
        
        // Tag filter (luxury, beach, mountain, etc.)
        if ($request->filled('tag') && $request->tag !== 'all') {
            $query->whereJsonContains('tags', $request->tag);
        }
        
        // Sorting
        $sort = $request->input('sort', '');
        switch ($sort) {
            case 'price-asc':
                $query->orderBy('min_price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('min_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }
        
        $hotels = $query->paginate(12);
        $hotels->appends($request->query());
        $hotels_banner = null;
        
        // Get unique locations for autocomplete
        $locations = Hotel::query()
            ->fromVerifiedSellers()
            ->select('location')
            ->distinct()
            ->pluck('location');
        
        return view('front.hotels-resort', compact('hotels', 'hotels_banner', 'locations'));
    }

    // Admin: Show create form
    public function create()
    {
        return view('admin.hotels.create');
    }

    // Admin: Store new hotel
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'property_type' => 'nullable|string|max:50',
            'location'      => 'required|string|max:255',
            'rating'        => 'nullable|numeric|min:0|max:5',
            'contact'       => 'required|string|max:50',
            'email'         => 'nullable|email|max:255',
            'website'       => 'nullable|url|max:255',
            'map_link'      => 'nullable|url|max:500',
            'desc'          => 'nullable|string',
            'min_price'     => 'nullable|numeric|min:0',
            'max_price'     => 'nullable|numeric|min:0',
            'checkin_time'  => 'nullable|string|max:10',
            'checkout_time' => 'nullable|string|max:10',
            'amenities'     => 'nullable|array',
            'tags'          => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/hotels'), $imageName);
            $data['image'] = $imageName;
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $galleryImages = [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path('uploads/hotels/gallery'), $galleryName);
                $galleryImages[] = $galleryName;
            }
            $data['gallery'] = $galleryImages;
        }

        // Handle tags - convert from comma-separated string to array
        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        $user = Auth::user();
        if ($user instanceof User && $user->hasRole('Seller') && Schema::hasColumn('hotels', 'created_by')) {
            $data['created_by'] = $user->id;
        }

        Hotel::create($data);
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel added successfully!');
    }

    // Admin: Show edit form
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $this->abortIfSellerCannotManageHotel($hotel);

        return view('admin.hotels.edit', compact('hotel'));
    }

    // Admin: Update hotel
    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $this->abortIfSellerCannotManageHotel($hotel);
        
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'property_type' => 'nullable|string|max:50',
            'location'      => 'required|string|max:255',
            'rating'        => 'nullable|numeric|min:0|max:5',
            'contact'       => 'required|string|max:50',
            'email'         => 'nullable|email|max:255',
            'website'       => 'nullable|url|max:255',
            'map_link'      => 'nullable|url|max:500',
            'desc'          => 'nullable|string',
            'min_price'     => 'nullable|numeric|min:0',
            'max_price'     => 'nullable|numeric|min:0',
            'checkin_time'  => 'nullable|string|max:10',
            'checkout_time' => 'nullable|string|max:10',
            'amenities'     => 'nullable|array',
            'tags'          => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($hotel->image && file_exists(public_path('uploads/hotels/' . $hotel->image))) {
                unlink(public_path('uploads/hotels/' . $hotel->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/hotels'), $imageName);
            $data['image'] = $imageName;
        }

        // Handle gallery images (append to existing)
        if ($request->hasFile('gallery')) {
            $existingGallery = is_array($hotel->gallery) ? $hotel->gallery : [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path('uploads/hotels/gallery'), $galleryName);
                $existingGallery[] = $galleryName;
            }
            $data['gallery'] = $existingGallery;
        }

        // Handle tags - convert from comma-separated string to array
        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        } else {
            $data['tags'] = [];
        }

        // Handle empty amenities
        if (!isset($data['amenities'])) {
            $data['amenities'] = [];
        }

        $hotel->update($data);
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully!');
    }

    // Admin: Delete hotel
    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $this->abortIfSellerCannotManageHotel($hotel);
        
        // Delete images
        if ($hotel->image && file_exists(public_path('uploads/hotels/' . $hotel->image))) {
            unlink(public_path('uploads/hotels/' . $hotel->image));
        }
        if (is_array($hotel->gallery)) {
            foreach ($hotel->gallery as $galleryImage) {
                if (file_exists(public_path('uploads/hotels/gallery/' . $galleryImage))) {
                    unlink(public_path('uploads/hotels/gallery/' . $galleryImage));
                }
            }
        }
        
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully!');
    }
}