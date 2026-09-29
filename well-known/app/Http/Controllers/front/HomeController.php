<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index(){
        $dealBaseQuery = Product::query()
            ->where('status', 0)
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->where('total_price', '>', 0)
            ->whereNotNull('final_price')
            ->select('products.*')
            ->selectRaw('CASE WHEN products.total_price > 0 THEN ((products.total_price - COALESCE(products.final_price, products.offer_price, products.total_price)) / products.total_price) * 100 ELSE 0 END AS deal_discount_pct')
            ->selectRaw('(products.total_price - COALESCE(products.final_price, products.offer_price, products.total_price)) AS deal_saving_amount')
            ->orderByDesc(Schema::hasColumn('products', 'top_deal_priority') ? 'top_deal_priority' : 'id')
            ->orderByDesc('deal_discount_pct')
            ->orderByDesc('deal_saving_amount')
            ->orderBy('final_price', 'asc')
            ->orderByDesc('updated_at');

        // Top deals are admin-curated and then ranked by discount/value quality.
        $topDealProducts = Schema::hasColumn('products', 'is_top_deal')
            ? (clone $dealBaseQuery)->where('is_top_deal', 1)->take(6)->get()
            : collect();

        $top_deals = (object) ['products' => $topDealProducts];

        // Hero slider top deals - Get the top 4 deals with images for the hero carousel
        $heroTopDeals = Schema::hasColumn('products', 'is_top_deal')
            ? (clone $dealBaseQuery)
                ->where('is_top_deal', 1)
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->take(4)
                ->get()
            : collect();

        $electronics = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'status'  => '0',
                'id'      => 1
            ])->first();

        $computers = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'status'  => '0',
                'id'      => 6
            ])->first();

        $monitor_mouse = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'status'  => '0',
            ])
            ->whereIn('id', [7,8])
            ->get();

        $electronics_more = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(10);
            }])->where([
                'status'  => '0',
            ])
            ->whereIn('id', [1,5,10,11,12])
            ->get();

        // Fetch hotels from the hotels table
        $hotels = \App\Models\Hotel::query()
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // Ensure category variables always expose a `products` collection to the view
        $top_deals = $top_deals ?: (object) ['products' => collect()];
        $electronics = $electronics ?: (object) ['products' => collect()];
        $computers = $computers ?: (object) ['products' => collect()];

        return view("front.index", compact('top_deals', 'heroTopDeals', 'electronics', 'computers', 'monitor_mouse', 'electronics_more', 'hotels'));
    }

    private function getHeroTopDeals($limit = 4)
    {
        return Product::with('category')
            ->where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->whereNotNull('image')
            ->where('image','<>','')
            ->where('total_price', '>', 0)
            ->whereNotNull('final_price')
            ->orderByDesc('is_top_deal')
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    public function computers(Request $request){
        $searchQuery = $request->input('q');
        $brands = $request->input('brands', []); // Array of selected brands
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'newest');
        
        // Build filter closure for product queries
        $applyFilters = function ($query) use ($searchQuery, $brands, $minPrice, $maxPrice, $sortBy) {
            // Search filter
            if ($searchQuery) {
                $keywords = preg_split('/\s+/', $searchQuery, -1, PREG_SPLIT_NO_EMPTY);
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('detail', 'like', "%{$word}%")
                          ->orWhere('company', 'like', "%{$word}%");
                    }
                });
            }
            // Brand filter
            if (!empty($brands)) {
                $query->where(function($q) use ($brands) {
                    foreach ($brands as $brand) {
                        $q->orWhere('company', 'like', "%{$brand}%");
                    }
                });
            }
            // Price filter
            if ($minPrice) {
                $query->where('offer_price', '>=', $minPrice);
            }
            if ($maxPrice) {
                $query->where('offer_price', '<=', $maxPrice);
            }
            // Sorting
            switch ($sortBy) {
                case 'price_asc':
                    $query->orderBy('offer_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('offer_price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                default: // newest
                    $query->orderBy('id', 'desc');
            }
        };

        $elevates = Product::with('category')
            ->where(['status' => '0'])
            ->where('deleted', 0)
            ->fromVerifiedSellers();
        $applyFilters($elevates);
        $elevates = $elevates->take(10)->get();

        $desktops = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) {
                    $query->take(12);
                }
            }])->where(['status' => '0', 'id' => 6])->first();

        $monitors = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) {
                    $query->take(6);
                }
            }])->where(['status' => '0', 'id' => 7])->first();

        $mouses = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) {
                    $query->take(6);
                }
            }])->where(['status' => '0', 'id' => 8])->first();

        $desktops = $desktops ?: (object) ['products' => collect()];
        $monitors = $monitors ?: (object) ['products' => collect()];
        $mouses = $mouses ?: (object) ['products' => collect()];

        // Available brands for filter sidebar (dynamic from current product set)
        $availableBrands = Product::where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->whereHas('category', function($q) {
                $q->whereIn('id', [6, 7, 8]);
            })
            ->whereNotNull('company')
            ->where('company', '<>', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        $heroTopDeals = $this->getHeroTopDeals();

        return view("front.computers", compact('elevates', 'desktops', 'monitors', 'mouses', 'searchQuery', 'brands', 'minPrice', 'maxPrice', 'sortBy', 'availableBrands', 'heroTopDeals')); 
    }

    public function electronics(Request $request){
        $searchQuery = $request->input('q');
        $brands = $request->input('brands', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'newest');
        
        // Build filter closure
        $applyFilters = function ($query) use ($searchQuery, $brands, $minPrice, $maxPrice, $sortBy) {
            if ($searchQuery) {
                $keywords = preg_split('/\s+/', $searchQuery, -1, PREG_SPLIT_NO_EMPTY);
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('detail', 'like', "%{$word}%")
                          ->orWhere('company', 'like', "%{$word}%");
                    }
                });
            }
            if (!empty($brands)) {
                $query->where(function($q) use ($brands) {
                    foreach ($brands as $brand) {
                        $q->orWhere('company', 'like', "%{$brand}%");
                    }
                });
            }
            if ($minPrice) { $query->where('offer_price', '>=', $minPrice); }
            if ($maxPrice) { $query->where('offer_price', '<=', $maxPrice); }
            switch ($sortBy) {
                case 'price_asc': $query->orderBy('offer_price', 'asc'); break;
                case 'price_desc': $query->orderBy('offer_price', 'desc'); break;
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                default: $query->orderBy('id', 'desc');
            }
        };

        $electronics1 = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) { $query->take(7); }
            }])->where(['status' => '0', 'id' => 1])->first();

        $electronics2 = Category::with(['products' => function ($query) use ($applyFilters) {
                $applyFilters($query);
            }])->where(['status' => '0', 'id' => 11])->first();

        $mobile_accessories = Category::with(['products' => function ($query) use ($applyFilters) {
                $applyFilters($query);
            }])->where(['status' => '0', 'id' => 12])->first();
            
        $headphones = Category::with(['products' => function ($query) use ($applyFilters) {
                $applyFilters($query);
            }])->where(['status' => '0', 'id' => 11])->first();

        $electronics1 = $electronics1 ?: (object) ['products' => collect()];
        $electronics2 = $electronics2 ?: (object) ['products' => collect()];
        $mobile_accessories = $mobile_accessories ?: (object) ['products' => collect()];
        $headphones = $headphones ?: (object) ['products' => collect()];

        $availableBrands = Product::where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->whereHas('category', function($q) {
                $q->whereIn('id', [1, 11, 12]);
            })
            ->whereNotNull('company')
            ->where('company', '<>', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        $heroTopDeals = $this->getHeroTopDeals();

        return view("front.electronics", compact('electronics1', 'electronics2', 'mobile_accessories', 'headphones', 'searchQuery', 'brands', 'minPrice', 'maxPrice', 'sortBy', 'availableBrands', 'heroTopDeals')); 
    }

    public function groceries(Request $request){
        $searchQuery = $request->input('q');
        $brands = $request->input('brands', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'newest');
        
        // Build filter closure
        $applyFilters = function ($query) use ($searchQuery, $brands, $minPrice, $maxPrice, $sortBy) {
            if ($searchQuery) {
                $keywords = preg_split('/\s+/', $searchQuery, -1, PREG_SPLIT_NO_EMPTY);
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('detail', 'like', "%{$word}%")
                          ->orWhere('company', 'like', "%{$word}%");
                    }
                });
            }
            if (!empty($brands)) {
                $query->where(function($q) use ($brands) {
                    foreach ($brands as $brand) {
                        $q->orWhere('company', 'like', "%{$brand}%");
                    }
                });
            }
            if ($minPrice) { $query->where('offer_price', '>=', $minPrice); }
            if ($maxPrice) { $query->where('offer_price', '<=', $maxPrice); }
            switch ($sortBy) {
                case 'price_asc': $query->orderBy('offer_price', 'asc'); break;
                case 'price_desc': $query->orderBy('offer_price', 'desc'); break;
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                default: $query->orderBy('id', 'desc');
            }
        };

        $coocking_oil = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) { $query->take(7); }
            }])->where(['status' => '0', 'id' => 13])->first();

        $rice_grains = Category::with(['products' => function ($query) use ($applyFilters) {
                $applyFilters($query);
            }])->where(['status' => '0', 'id' => 14])->first();

        $spices_masala = Category::with(['products' => function ($query) use ($applyFilters) {
                $applyFilters($query);
            }])->where(['status' => '0', 'id' => 15])->first();
            
        $snacks_buiscuits = Category::with(['products' => function ($query) use ($applyFilters) {
                $applyFilters($query);
            }])->where(['status' => '0', 'id' => 16])->first();

        $coocking_oil = $coocking_oil ?: (object) ['products' => collect()];
        $rice_grains = $rice_grains ?: (object) ['products' => collect()];
        $spices_masala = $spices_masala ?: (object) ['products' => collect()];
        $snacks_buiscuits = $snacks_buiscuits ?: (object) ['products' => collect()];

        $availableBrands = Product::where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->whereHas('category', function ($q) {
                $q->whereIn('id', [13, 14, 15, 16]);
            })
            ->whereNotNull('company')
            ->where('company', '<>', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        $heroTopDeals = $this->getHeroTopDeals();

        return view("front.groceries", compact('coocking_oil', 'rice_grains', 'spices_masala', 'snacks_buiscuits', 'searchQuery', 'brands', 'minPrice', 'maxPrice', 'sortBy', 'availableBrands', 'heroTopDeals')); 
    }

public function cosmetics(Request $request){
    $searchQuery = $request->input('q');
    $brands = $request->input('brands', []);
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');
    $sortBy = $request->input('sort_by', 'newest');
    $filterCategory = $request->input('category'); // skincare, makeup, haircare, etc.
    
    // Build filter closure
    $applyFilters = function ($query) use ($searchQuery, $brands, $minPrice, $maxPrice, $sortBy) {
        if ($searchQuery) {
            $keywords = preg_split('/\s+/', $searchQuery, -1, PREG_SPLIT_NO_EMPTY);
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('name', 'like', "%{$word}%")
                      ->orWhere('detail', 'like', "%{$word}%")
                      ->orWhere('company', 'like', "%{$word}%");
                }
            });
        }
        if (!empty($brands)) {
            $query->where(function($q) use ($brands) {
                foreach ($brands as $brand) {
                    $q->orWhere('company', 'like', "%{$brand}%");
                }
            });
        }
        if ($minPrice) { $query->where('offer_price', '>=', $minPrice); }
        if ($maxPrice) { $query->where('offer_price', '<=', $maxPrice); }
        switch ($sortBy) {
            case 'price_asc': $query->orderBy('offer_price', 'asc'); break;
            case 'price_desc': $query->orderBy('offer_price', 'desc'); break;
            case 'discount': $query->orderByRaw('(total_price - offer_price) DESC'); break;
            case 'name_asc': $query->orderBy('name', 'asc'); break;
            default: $query->orderBy('id', 'desc');
        }
    };

    $skincare = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands, $filterCategory) {
            $applyFilters($query);
            if (!$searchQuery && empty($brands) && $filterCategory != 'skincare') { $query->take(7); }
        }])->where(['status' => '0', 'id' => 17])->first();

    $makeup = Category::with(['products' => function ($query) use ($applyFilters, $filterCategory) {
            $applyFilters($query);
        }])->where(['status' => '0', 'id' => 18])->first();

    $haircare = Category::with(['products' => function ($query) use ($applyFilters, $filterCategory) {
            $applyFilters($query);
        }])->where(['status' => '0', 'id' => 19])->first();
        
    $other_cosmetics = Category::with(['products' => function ($query) use ($applyFilters, $filterCategory) {
            $applyFilters($query);
        }])->where(['status' => '0', 'id' => 20])->first();

    $skincare = $skincare ?: (object) ['products' => collect()];
    $makeup = $makeup ?: (object) ['products' => collect()];
    $haircare = $haircare ?: (object) ['products' => collect()];
    $other_cosmetics = $other_cosmetics ?: (object) ['products' => collect()];

    $availableBrands = Product::where('status', '0')
        ->where('deleted', 0)
        ->fromVerifiedSellers()
        ->whereHas('category', function ($q) {
            $q->whereIn('id', [17, 18, 19, 20]);
        })
        ->whereNotNull('company')
        ->where('company','<>','')
        ->distinct()
        ->orderBy('company')
        ->pluck('company');

    $heroTopDeals = $this->getHeroTopDeals();
    return view("front.cosmetics", compact('skincare', 'makeup', 'haircare', 'other_cosmetics', 'searchQuery', 'brands', 'minPrice', 'maxPrice', 'sortBy', 'filterCategory', 'availableBrands', 'heroTopDeals')); 
}


    public function hotels_resorts(Request $request){
        $hotels_banner = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc')->take(3);
            }])->where([
                'status'  => '0',
                'id'      => 4
            ])->first();

        $hotels_banner = $hotels_banner ?: (object) ['products' => collect()];

        // Build query with filters (show all hotels, no KYC filter)
        $query = \App\Models\Hotel::query();

        // Filter by property type
        if ($request->filled('property_type')) {
            $query->ofType($request->property_type);
        }

        // Filter by location (from hero search bar or filter section)
        if ($request->filled('location')) {
            $query->inLocation($request->location);
        }

        // Filter by minimum rating
        if ($request->filled('rating')) {
            $query->minRating($request->rating);
        }

        // Filter by max price
        if ($request->filled('max_price')) {
            $query->priceRange(null, $request->max_price);
        }

        // Filter by amenities (array of amenity values)
        if ($request->filled('amenities')) {
            $amenities = is_array($request->amenities) ? $request->amenities : explode(',', $request->amenities);
            $query->withAmenities($amenities);
        }

        // Filter by quick tag
        if ($request->filled('tag') && $request->tag !== 'all') {
            $query->withTags($request->tag);
        }

        // Filter by check-in/check-out dates (for availability - hotels with available rooms)
        if ($request->filled('checkin') && $request->filled('checkout')) {
            $checkin = $request->checkin;
            $checkout = $request->checkout;
            // Filter hotels that have at least one available room during the dates
            $query->whereHas('rooms', function($q) use ($checkin, $checkout) {
                $q->whereDoesntHave('bookings', function($bq) use ($checkin, $checkout) {
                    $bq->where(function($dateQ) use ($checkin, $checkout) {
                        $dateQ->whereBetween('checkin', [$checkin, $checkout])
                              ->orWhereBetween('checkout', [$checkin, $checkout])
                              ->orWhere(function($rangeQ) use ($checkin, $checkout) {
                                  $rangeQ->where('checkin', '<=', $checkin)
                                         ->where('checkout', '>=', $checkout);
                              });
                    })->whereIn('status', ['pending', 'confirmed']);
                });
            });
        }

        // Filter by number of guests
        if ($request->filled('guests')) {
            $query->whereHas('rooms', function($q) use ($request) {
                $q->where('capacity', '>=', $request->guests);
            });
        }

        // Sorting
        switch ($request->sort) {
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

        $hotels = $query->paginate(12)->withQueryString();

        // Get unique locations for autocomplete
        $locations = \App\Models\Hotel::query()
            ->fromVerifiedSellers()
            ->distinct()
            ->pluck('location')
            ->filter()
            ->values();

        return view("front.hotels-resort", compact('hotels', 'hotels_banner', 'locations'));
    }

    public function register_buisness(){        
        $categories = DB::table('categories')->where(['status'=>'0'])->get();
        $states = DB::table('states')->where(['status'=>'1'])->get();        
        $countries = DB::table('countries')->where(['status'=>'1'])->get();
        return view("front.register-buisness", compact('categories', 'states', 'countries'));
    }

    public function contact()
    {
        return view('front.contact_us');
    }

    /**
     * Electronics page - Shows Computers + Electronics combined
     */
    public function electronics_combined(Request $request){
        $searchQuery = $request->input('q');
        $brands = $request->input('brands', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'newest');
        
        // Build filter closure for product queries
        $applyFilters = function ($query) use ($searchQuery, $brands, $minPrice, $maxPrice, $sortBy) {
            if ($searchQuery) {
                $keywords = preg_split('/\s+/', $searchQuery, -1, PREG_SPLIT_NO_EMPTY);
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('detail', 'like', "%{$word}%")
                          ->orWhere('company', 'like', "%{$word}%");
                    }
                });
            }
            if (!empty($brands)) {
                $query->where(function($q) use ($brands) {
                    foreach ($brands as $brand) {
                        $q->orWhere('company', 'like', "%{$brand}%");
                    }
                });
            }
            if ($minPrice) { $query->where('offer_price', '>=', $minPrice); }
            if ($maxPrice) { $query->where('offer_price', '<=', $maxPrice); }
            switch ($sortBy) {
                case 'price_asc': $query->orderBy('offer_price', 'asc'); break;
                case 'price_desc': $query->orderBy('offer_price', 'desc'); break;
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                default: $query->orderBy('id', 'desc');
            }
        };

        // Computers categories (IDs: 6=Computers, 7=Monitors, 8=Mouse & Keyboard)
        $desktops = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) { $query->take(8); }
            }])->where(['status' => '0', 'id' => 6])->first();

        $monitors = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) { $query->take(6); }
            }])->where(['status' => '0', 'id' => 7])->first();

        $peripherals = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) { $query->take(6); }
            }])->where(['status' => '0', 'id' => 8])->first();

        // Electronics categories (IDs: 1=Electronics, 11=Category 11, 12=Category 12)
        $electronics = Category::with(['products' => function ($query) use ($applyFilters, $searchQuery, $brands) {
                $applyFilters($query);
                if (!$searchQuery && empty($brands)) { $query->take(8); }
            }])->where(['status' => '0', 'id' => 1])->first();

        $mobile_accessories = Category::with(['products' => function ($query) use ($applyFilters) {
                $applyFilters($query);
            }])->where(['status' => '0', 'id' => 12])->first();

        // Default empty collections if categories don't exist
        $desktops = $desktops ?: (object) ['products' => collect(), 'name' => 'Computers'];
        $monitors = $monitors ?: (object) ['products' => collect(), 'name' => 'Monitors'];
        $peripherals = $peripherals ?: (object) ['products' => collect(), 'name' => 'Mouse & Keyboard'];
        $electronics = $electronics ?: (object) ['products' => collect(), 'name' => 'Electronics'];
        $mobile_accessories = $mobile_accessories ?: (object) ['products' => collect(), 'name' => 'Mobile Accessories'];

        $availableBrands = Product::where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->whereHas('category', function($q) {
                $q->whereIn('id', [1, 5, 10, 11, 12]);
            })
            ->whereNotNull('company')
            ->where('company', '<>', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        $heroTopDeals = $this->getHeroTopDeals();

        return view("front.electronics-combined", compact(
            'desktops', 'monitors', 'peripherals', 'electronics', 'mobile_accessories',
            'searchQuery', 'brands', 'minPrice', 'maxPrice', 'sortBy', 'availableBrands', 'heroTopDeals'
        ));
    }

    /**
     * Appliances page - Shows Home Appliances
     */
    public function appliances(Request $request){
        $searchQuery = $request->input('q');
        $brands = $request->input('brands', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'newest');
        
        // Build filter closure
        $applyFilters = function ($query) use ($searchQuery, $brands, $minPrice, $maxPrice, $sortBy) {
            if ($searchQuery) {
                $keywords = preg_split('/\s+/', $searchQuery, -1, PREG_SPLIT_NO_EMPTY);
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('detail', 'like', "%{$word}%")
                          ->orWhere('company', 'like', "%{$word}%")
                          ->orWhereHas('category', function($c) use ($word) {
                              $c->where('name', 'like', "%{$word}%");
                          });
                    }
                });
            }
            if (!empty($brands)) {
                $query->where(function($q) use ($brands) {
                    foreach ($brands as $brand) {
                        $q->orWhere('company', 'like', "%{$brand}%");
                    }
                });
            }
            if ($minPrice) { $query->where('offer_price', '>=', $minPrice); }
            if ($maxPrice) { $query->where('offer_price', '<=', $maxPrice); }
            switch ($sortBy) {
                case 'price_asc': $query->orderBy('offer_price', 'asc'); break;
                case 'price_desc': $query->orderBy('offer_price', 'desc'); break;
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                default: $query->orderBy('id', 'desc');
            }
        };

        // Home Appliances - search in multiple categories or a specific appliances category
        // For now, fetch from categories that might contain home appliances
        // Using category IDs: 9=Category 9, 10=Category 10 (can be repurposed as appliance categories)
        $all_appliances = Product::with('category')
            ->where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->where(function($q) {
                // Search in name/description/category for appliance-related keywords
                $q->where('name', 'like', '%appliance%')
                  ->orWhere('name', 'like', '%fan%')
                  ->orWhere('name', 'like', '%heater%')
                  ->orWhere('name', 'like', '%cooler%')
                  ->orWhere('name', 'like', '%mixer%')
                  ->orWhere('name', 'like', '%grinder%')
                  ->orWhere('name', 'like', '%iron%')
                  ->orWhere('name', 'like', '%vacuum%')
                  ->orWhere('name', 'like', '%washing%')
                  ->orWhere('name', 'like', '%refrigerator%')
                  ->orWhere('name', 'like', '%microwave%')
                  ->orWhere('name', 'like', '%toaster%')
                  ->orWhere('name', 'like', '%kettle%')
                  ->orWhere('name', 'like', '%air conditioner%')
                  ->orWhere('name', 'like', '%AC%')
                  ->orWhere('name', 'like', '%geyser%')
                  ->orWhere('name', 'like', '%water purifier%')
                  ->orWhere('detail', 'like', '%home appliance%')
                  ->orWhereHas('category', function($c) {
                        $c->where('name', 'like', '%appliance%')
                          ->orWhere('name', 'like', '%fan%')
                          ->orWhere('name', 'like', '%heater%')
                          ->orWhere('name', 'like', '%cooler%')
                          ->orWhere('name', 'like', '%mixer%')
                          ->orWhere('name', 'like', '%grinder%')
                          ->orWhere('name', 'like', '%iron%')
                          ->orWhere('name', 'like', '%vacuum%')
                          ->orWhere('name', 'like', '%washing%')
                          ->orWhere('name', 'like', '%refrigerator%')
                          ->orWhere('name', 'like', '%microwave%')
                          ->orWhere('name', 'like', '%toaster%')
                          ->orWhere('name', 'like', '%kettle%')
                          ->orWhere('name', 'like', '%air conditioner%')
                          ->orWhere('name', 'like', '%AC%')
                          ->orWhere('name', 'like', '%geyser%')
                          ->orWhere('name', 'like', '%water purifier%');
                  });
            });
        $applyFilters($all_appliances);
        $all_appliances = $all_appliances->paginate(20);

        $availableBrands = Product::where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->where(function($q) {
                $q->where('name', 'like', '%appliance%')
                  ->orWhere('name', 'like', '%fan%')
                  ->orWhere('name', 'like', '%heater%')
                  ->orWhere('name', 'like', '%cooler%')
                  ->orWhere('name', 'like', '%mixer%')
                  ->orWhere('name', 'like', '%grinder%')
                  ->orWhere('name', 'like', '%iron%')
                  ->orWhere('name', 'like', '%vacuum%')
                  ->orWhere('name', 'like', '%washing%')
                  ->orWhere('name', 'like', '%refrigerator%')
                  ->orWhere('name', 'like', '%microwave%')
                  ->orWhere('name', 'like', '%toaster%')
                  ->orWhere('name', 'like', '%kettle%')
                  ->orWhere('name', 'like', '%air conditioner%')
                  ->orWhere('name', 'like', '%AC%')
                  ->orWhere('name', 'like', '%geyser%')
                  ->orWhere('name', 'like', '%water purifier%')
                  ->orWhere('detail', 'like', '%home appliance%')
                  ->orWhereHas('category', function($c) {
                      $c->where('name', 'like', '%appliance%')
                        ->orWhere('name', 'like', '%fan%')
                        ->orWhere('name', 'like', '%heater%')
                        ->orWhere('name', 'like', '%cooler%')
                        ->orWhere('name', 'like', '%mixer%')
                        ->orWhere('name', 'like', '%grinder%')
                        ->orWhere('name', 'like', '%iron%')
                        ->orWhere('name', 'like', '%vacuum%')
                        ->orWhere('name', 'like', '%washing%')
                        ->orWhere('name', 'like', '%refrigerator%')
                        ->orWhere('name', 'like', '%microwave%')
                        ->orWhere('name', 'like', '%toaster%')
                        ->orWhere('name', 'like', '%kettle%')
                        ->orWhere('name', 'like', '%air conditioner%')
                        ->orWhere('name', 'like', '%AC%')
                        ->orWhere('name', 'like', '%geyser%')
                        ->orWhere('name', 'like', '%water purifier%');
                  });
            })
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        $heroTopDeals = $this->getHeroTopDeals();

        return view("front.appliances", compact(
            'all_appliances', 'searchQuery', 'brands', 'minPrice', 'maxPrice', 'sortBy', 'availableBrands', 'heroTopDeals'
        ));
    }

    public function mobile(Request $request){
        $searchQuery = $request->input('q');
        $brands = $request->input('brands', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'newest');
        
        // Build filter closure
        $applyFilters = function ($query) use ($searchQuery, $brands, $minPrice, $maxPrice, $sortBy) {
            if ($searchQuery) {
                $keywords = preg_split('/\s+/', $searchQuery, -1, PREG_SPLIT_NO_EMPTY);
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('detail', 'like', "%{$word}%")
                          ->orWhere('company', 'like', "%{$word}%");
                    }
                });
            }
            if (!empty($brands)) {
                $query->where(function($q) use ($brands) {
                    foreach ($brands as $brand) {
                        $q->orWhere('company', 'like', "%{$brand}%");
                    }
                });
            }
            if ($minPrice) { $query->where('offer_price', '>=', $minPrice); }
            if ($maxPrice) { $query->where('offer_price', '<=', $maxPrice); }
            switch ($sortBy) {
                case 'price_asc': $query->orderBy('offer_price', 'asc'); break;
                case 'price_desc': $query->orderBy('offer_price', 'desc'); break;
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                default: $query->orderBy('id', 'desc');
            }
        };

        // Mobile Phones - search in name/description for mobile-related keywords
        $all_mobiles = Product::with('category')
            ->where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->where(function($q) {
                $q->where('name', 'like', '%mobile%')
                  ->orWhere('name', 'like', '%phone%')
                  ->orWhere('name', 'like', '%smartphone%')
                  ->orWhere('name', 'like', '%iphone%')
                  ->orWhere('name', 'like', '%samsung galaxy%')
                  ->orWhere('name', 'like', '%oneplus%')
                  ->orWhere('name', 'like', '%redmi%')
                  ->orWhere('name', 'like', '%realme%')
                  ->orWhere('name', 'like', '%vivo%')
                  ->orWhere('name', 'like', '%oppo%')
                  ->orWhere('name', 'like', '%nokia%')
                  ->orWhere('name', 'like', '%motorola%')
                  ->orWhere('name', 'like', '%poco%')
                  ->orWhere('name', 'like', '%pixel%')
                  ->orWhere('name', 'like', '%nothing phone%')
                  ->orWhere('name', 'like', '%iqoo%')
                  ->orWhere('detail', 'like', '%smartphone%')
                  ->orWhere('detail', 'like', '%mobile phone%');
            });
        $applyFilters($all_mobiles);
        $all_mobiles = $all_mobiles->paginate(20);

        $availableBrands = Product::where('status', '0')
            ->where('deleted', 0)
            ->fromVerifiedSellers()
            ->where(function($q) {
                $q->where('name', 'like', '%mobile%')
                  ->orWhere('name', 'like', '%phone%')
                  ->orWhere('name', 'like', '%smartphone%')
                  ->orWhere('name', 'like', '%iphone%')
                  ->orWhere('name', 'like', '%samsung galaxy%')
                  ->orWhere('name', 'like', '%oneplus%')
                  ->orWhere('name', 'like', '%redmi%')
                  ->orWhere('name', 'like', '%realme%')
                  ->orWhere('name', 'like', '%vivo%')
                  ->orWhere('name', 'like', '%oppo%')
                  ->orWhere('name', 'like', '%nokia%')
                  ->orWhere('name', 'like', '%motorola%')
                  ->orWhere('name', 'like', '%poco%')
                  ->orWhere('name', 'like', '%pixel%')
                  ->orWhere('name', 'like', '%nothing phone%')
                  ->orWhere('name', 'like', '%iqoo%')
                  ->orWhere('detail', 'like', '%smartphone%')
                  ->orWhere('detail', 'like', '%mobile phone%');
            })
            ->whereNotNull('company')
            ->where('company', '<>', '')
            ->distinct()
            ->orderBy('company')
            ->pluck('company');

        $heroTopDeals = $this->getHeroTopDeals();

        return view("front.mobile", compact(
            'all_mobiles', 'searchQuery', 'brands', 'minPrice', 'maxPrice', 'sortBy', 'availableBrands', 'heroTopDeals'
        ));
    }

}
