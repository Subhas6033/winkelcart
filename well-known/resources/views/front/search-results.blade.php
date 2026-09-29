@extends('front.layouts.app')

@section('content')
<style>
    .search-results-page {
        max-width: 1350px;
        margin: 0 auto;
        padding: 30px 15px;
    }
    .search-header {
        background: linear-gradient(135deg, #43a047, #2e7d32);
        padding: 30px;
        border-radius: 16px;
        color: white;
        margin-bottom: 30px;
    }
    .search-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 10px;
    }
    .search-header .search-stats {
        font-size: 14px;
        opacity: 0.9;
    }
    .search-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 0;
    }
    .search-tab {
        padding: 12px 24px;
        background: transparent;
        border: none;
        font-size: 15px;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s;
    }
    .search-tab:hover {
        color: #43a047;
    }
    .search-tab.active {
        color: #43a047;
        border-bottom-color: #43a047;
    }
    .search-tab .count {
        background: #e8f5e9;
        color: #43a047;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin-left: 8px;
    }
    .search-tab.active .count {
        background: #43a047;
        color: white;
    }
    .search-content {
        display: flex;
        gap: 30px;
    }
    .search-filters {
        width: 280px;
        flex-shrink: 0;
    }
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    .filter-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin: 0 0 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .filter-card select, .filter-card input {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 12px;
    }
    .filter-card select:focus, .filter-card input:focus {
        border-color: #43a047;
        outline: none;
    }
    .filter-btn {
        width: 100%;
        padding: 12px;
        background: #43a047;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    .filter-btn:hover {
        background: #2e7d32;
    }
    .search-results {
        flex: 1;
    }
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }
    .result-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    .result-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .result-card .card-body {
        padding: 15px;
    }
    .result-card .card-type {
        display: inline-block;
        padding: 3px 10px;
        background: #e8f5e9;
        color: #43a047;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .result-card .card-type.hotel {
        background: #e3f2fd;
        color: #1976d2;
    }
    .result-card h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 8px;
        color: #333;
    }
    .result-card .card-meta {
        font-size: 13px;
        color: #666;
        margin-bottom: 10px;
    }
    .result-card .card-price {
        font-size: 18px;
        font-weight: 700;
        color: #43a047;
    }
    .result-card .card-price .original {
        font-size: 13px;
        color: #999;
        text-decoration: line-through;
        margin-left: 8px;
    }
    .result-card .card-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }
    .result-card .btn-view {
        flex: 1;
        padding: 10px;
        text-align: center;
        background: #43a047;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
    }
    .result-card .btn-view:hover {
        background: #2e7d32;
    }
    .no-results {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
    }
    .no-results .icon {
        font-size: 60px;
        margin-bottom: 20px;
    }
    .no-results h3 {
        color: #333;
        margin-bottom: 10px;
    }
    .no-results p {
        color: #666;
    }
    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    @media (max-width: 992px) {
        .search-content {
            flex-direction: column;
        }
        .search-filters {
            width: 100%;
        }
    }
    @media (max-width: 576px) {
        .search-tabs {
            overflow-x: auto;
        }
        .results-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="search-results-page">
    <!-- Search Header -->
    <div class="search-header">
        <h1>Search Results for "{{ $query }}"</h1>
        <p class="search-stats">Found {{ $totalResults }} results ({{ $totalProducts }} products, {{ $totalHotels }} hotels)</p>
    </div>

    <!-- Search Tabs -->
    <div class="search-tabs">
        <button class="search-tab {{ $activeTab == 'all' ? 'active' : '' }}" onclick="switchTab('all')">
            All <span class="count">{{ $totalResults }}</span>
        </button>
        <button class="search-tab {{ $activeTab == 'products' ? 'active' : '' }}" onclick="switchTab('products')">
            Products <span class="count">{{ $totalProducts }}</span>
        </button>
        <button class="search-tab {{ $activeTab == 'hotels' ? 'active' : '' }}" onclick="switchTab('hotels')">
            Hotels <span class="count">{{ $totalHotels }}</span>
        </button>
    </div>

    <div class="search-content">
        <!-- Filters Sidebar -->
        <div class="search-filters">
            <form action="{{ route('product.search') }}" method="GET">
                <input type="hidden" name="q" value="{{ $query }}">
                
                <div class="filter-card">
                    <h4>Category</h4>
                    <select name="category_id">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-card">
                    <h4>Price Range</h4>
                    <input type="number" name="min_price" placeholder="Min Price" value="{{ request('min_price') }}">
                    <input type="number" name="max_price" placeholder="Max Price" value="{{ request('max_price') }}">
                </div>

                <div class="filter-card">
                    <h4>Brand</h4>
                    <input type="text" name="company" placeholder="Brand name..." value="{{ request('company') }}">
                </div>

                <div class="filter-card">
                    <h4>Hotel Type</h4>
                    <select name="property_type">
                        <option value="">All Types</option>
                        <option value="hotel" {{ request('property_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                        <option value="resort" {{ request('property_type') == 'resort' ? 'selected' : '' }}>Resort</option>
                        <option value="villa" {{ request('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                    </select>
                </div>

                <div class="filter-card">
                    <h4>Hotel Rating</h4>
                    <select name="hotel_rating">
                        <option value="">Any Rating</option>
                        <option value="5" {{ request('hotel_rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('hotel_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                        <option value="3" {{ request('hotel_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                    </select>
                </div>

                <button type="submit" class="filter-btn">Apply Filters</button>
            </form>
        </div>

        <!-- Search Results -->
        <div class="search-results">
            <!-- All Results Tab -->
            <div class="tab-content {{ $activeTab == 'all' ? 'active' : '' }}" id="tab-all">
                @if($totalResults > 0)
                    <div class="results-grid">
                        @foreach($products->take(6) as $product)
                            <div class="result-card">
                                @php
                                    $productImg = $product->image ? asset('uploads/products/' . $product->image) : asset('uploads/not_found.jpg');
                                @endphp
                                <img src="{{ $productImg }}" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <span class="card-type">Product</span>
                                    <h3>{{ Str::limit($product->name, 40) }}</h3>
                                    <p class="card-meta">{{ $product->category->name ?? 'General' }}</p>
                                    <div class="card-price">
                                        ₹{{ number_format($product->final_price ?? $product->total_price, 2) }}
                                        @if($product->total_price > ($product->final_price ?? $product->total_price))
                                            <span class="original">₹{{ number_format($product->total_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="card-actions">
                                        <a href="{{ route('product.show', $product->id) }}" class="btn-view">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach($hotels->take(6) as $hotel)
                            <div class="result-card">
                                @php
                                    $hotelImg = $hotel->image ? asset('uploads/products/' . $hotel->image) : asset('uploads/not_found.jpg');
                                @endphp
                                <img src="{{ $hotelImg }}" alt="{{ $hotel->name }}">
                                <div class="card-body">
                                    <span class="card-type hotel">Hotel</span>
                                    <h3>{{ Str::limit($hotel->name, 40) }}</h3>
                                    <p class="card-meta">📍 {{ $hotel->location }} @if($hotel->rating) • ⭐ {{ $hotel->rating }} @endif</p>
                                    @if($hotel->min_price)
                                    <div class="card-price">
                                        From ₹{{ number_format($hotel->min_price) }}<span style="font-size:12px;color:#666;">/night</span>
                                    </div>
                                    @endif
                                    <div class="card-actions">
                                        <a href="{{ url('hotel-details/'.$hotel->id) }}" class="btn-view">View Hotel</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-results">
                        <div class="icon">🔍</div>
                        <h3>No results found</h3>
                        <p>Try different keywords or remove filters</p>
                    </div>
                @endif
            </div>

            <!-- Products Tab -->
            <div class="tab-content {{ $activeTab == 'products' ? 'active' : '' }}" id="tab-products">
                @if($products->count() > 0)
                    <div class="results-grid">
                        @foreach($products as $product)
                            <div class="result-card">
                                @php
                                    $productImg = $product->image ? asset('uploads/products/' . $product->image) : asset('uploads/not_found.jpg');
                                @endphp
                                <img src="{{ $productImg }}" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <span class="card-type">Product</span>
                                    <h3>{{ Str::limit($product->name, 40) }}</h3>
                                    <p class="card-meta">{{ $product->category->name ?? 'General' }}</p>
                                    <div class="card-price">
                                        ₹{{ number_format($product->final_price ?? $product->total_price, 2) }}
                                        @if($product->total_price > ($product->final_price ?? $product->total_price))
                                            <span class="original">₹{{ number_format($product->total_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="card-actions">
                                        <a href="{{ route('product.show', $product->id) }}" class="btn-view">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pagination-wrapper">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="no-results">
                        <div class="icon">📦</div>
                        <h3>No products found</h3>
                        <p>Try different keywords or check our hotels</p>
                    </div>
                @endif
            </div>

            <!-- Hotels Tab -->
            <div class="tab-content {{ $activeTab == 'hotels' ? 'active' : '' }}" id="tab-hotels">
                @if($hotels->count() > 0)
                    <div class="results-grid">
                        @foreach($hotels as $hotel)
                            <div class="result-card">
                                @php
                                    $hotelImg = $hotel->image ? asset('uploads/products/' . $hotel->image) : asset('uploads/not_found.jpg');
                                @endphp
                                <img src="{{ $hotelImg }}" alt="{{ $hotel->name }}">
                                <div class="card-body">
                                    <span class="card-type hotel">{{ ucfirst($hotel->property_type ?? 'Hotel') }}</span>
                                    <h3>{{ Str::limit($hotel->name, 40) }}</h3>
                                    <p class="card-meta">📍 {{ $hotel->location }} @if($hotel->rating) • ⭐ {{ $hotel->rating }} @endif</p>
                                    @if($hotel->amenities && count($hotel->amenities) > 0)
                                    <p class="card-meta">{{ implode(', ', array_slice($hotel->amenities, 0, 3)) }}</p>
                                    @endif
                                    @if($hotel->min_price)
                                    <div class="card-price">
                                        From ₹{{ number_format($hotel->min_price) }}<span style="font-size:12px;color:#666;">/night</span>
                                    </div>
                                    @endif
                                    <div class="card-actions">
                                        <a href="{{ url('hotel-details/'.$hotel->id) }}" class="btn-view">View Hotel</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pagination-wrapper">
                        {{ $hotels->links() }}
                    </div>
                @else
                    <div class="no-results">
                        <div class="icon">🏨</div>
                        <h3>No hotels found</h3>
                        <p>Try different location or keywords</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    // Update tab buttons
    document.querySelectorAll('.search-tab').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    
    // Update URL without reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.pushState({}, '', url);
}
</script>
@endsection
