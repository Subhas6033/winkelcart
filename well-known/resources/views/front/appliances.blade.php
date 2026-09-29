@extends('front.layouts.app')
@section('content')

<style>
    :root {
        --wk-green: #43a047;
        --wk-dark-green: #2e7d32;
        --wk-light-green: #e8f5e9;
        --wk-yellow: #fdd835;
        --wk-pink: #e91e63;
        --wk-blue: #1e88e5;
        --wk-orange: #ff5722;
        --text-main: #212121;
        --bg-body: #f9f9f9;
    }

    body { background-color: var(--bg-body) !important; font-family: 'Poppins', sans-serif; overflow-x: hidden; }
    * { box-sizing: border-box; }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; display: block; }

    /* ===== CATEGORY BANNER ===== */
    .category-banner {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at 14% 18%, rgba(255, 255, 255, 0.26) 0 14%, transparent 14.5%),
            radial-gradient(circle at 86% 82%, rgba(255, 255, 255, 0.2) 0 16%, transparent 16.5%),
            linear-gradient(120deg, #bf360c 0%, #e65100 38%, #fb8c00 100%);
        padding: 56px 20px 48px;
        color: white;
        text-align: center;
        margin-bottom: 34px;
        box-shadow: 0 18px 40px rgba(191, 54, 12, 0.28);
    }

    .category-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.14) 1.2px, transparent 1.2px);
        background-size: 24px 24px;
        opacity: 0.45;
        pointer-events: none;
    }

    .category-banner::after {
        content: '';
        position: absolute;
        width: 360px;
        height: 360px;
        top: -170px;
        right: -130px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0) 72%);
        pointer-events: none;
    }

    .category-banner-inner {
        position: relative;
        z-index: 2;
        max-width: 940px;
        margin: 0 auto;
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.45);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 14px;
    }

    .category-banner h1 {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: 0.4px;
        text-shadow: 0 3px 18px rgba(0, 0, 0, 0.22);
    }

    .category-banner p {
        font-size: 18px;
        opacity: 0.95;
        margin: 0 auto 16px;
        max-width: 700px;
    }

    .banner-highlights {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin: 0 auto 18px;
    }

    .feature-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.34);
        backdrop-filter: blur(2px);
    }

    .banner-actions {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 999px;
        background: #ffffff;
        color: #bf360c;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .hero-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.22);
        color: #a22f0a;
    }

    .hero-btn-outline {
        background: transparent;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: none;
    }

    .hero-btn-outline:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.16);
        box-shadow: none;
    }

    .breadcrumb-bar {
        margin-top: 2px;
        font-size: 13px;
        opacity: 0.95;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .breadcrumb-bar a { color: white; }
    .breadcrumb-bar a:hover { text-decoration: underline; }

    /* ===== PAGE LAYOUT ===== */
    .page-wrapper {
        max-width: 1350px;
        margin: 0 auto;
        padding: 0 15px 50px;
        display: flex;
        gap: 25px;
        align-items: flex-start;
        overflow-x: hidden;
        width: 100%;
    }

    /* ===== FILTER SIDEBAR ===== */
    .filter-sidebar {
        width: 240px;
        flex-shrink: 0;
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        border: 1px solid #eee;
        position: sticky;
        top: 80px;
    }

    .filter-sidebar h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--wk-orange);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group { margin-bottom: 22px; }

    .filter-group h4 {
        font-size: 13px;
        font-weight: 700;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .filter-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #444;
        margin-bottom: 8px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .filter-group label:hover { color: var(--wk-orange); }

    .filter-group input[type="checkbox"] {
        accent-color: var(--wk-orange);
        width: 15px;
        height: 15px;
        cursor: pointer;
    }

    .price-range-inputs {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .price-range-inputs input {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        color: var(--text-main);
    }

    .price-range-inputs input:focus {
        border-color: var(--wk-orange);
        outline: none;
    }

    .price-range-inputs span { color: #999; font-size: 12px; }

    .filter-divider { border: none; border-top: 1px solid #eee; margin: 15px 0; }

    .apply-filter-btn {
        width: 100%;
        padding: 10px;
        background: var(--wk-orange);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: background 0.2s;
    }

    .apply-filter-btn:hover { background: #e64a19; }

    .clear-filter-btn {
        width: 100%;
        padding: 9px;
        background: white;
        color: #666;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        margin-top: 8px;
        transition: 0.2s;
    }

    .clear-filter-btn:hover { border-color: var(--wk-pink); color: var(--wk-pink); }

    /* ===== MAIN CONTENT ===== */
    .main-content { flex: 1; min-width: 0; }

    /* ===== SECTION CONTAINER ===== */
    .section-container { margin-bottom: 45px; }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===== PRODUCT GRID ===== */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 18px;
    }

    /* ===== PRODUCT CARD ===== */
    .product-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #eee;
        transition: 0.3s;
        text-align: center;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.09);
        border-color: transparent;
    }

    .product-card a { color: var(--text-main); text-decoration: none; }

    .product-card img {
        width: 100%;
        height: 150px;
        object-fit: contain;
        margin-bottom: 12px;
        transition: 0.4s;
    }

    .product-card:hover img { transform: scale(1.06); }

    .product-card .product-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 4px;
        display: -webkit-box;
        line-clamp: 2;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .product-card h6 {
        font-size: 12px;
        color: #999;
        font-weight: 400;
        margin-bottom: 10px;
    }

    .price-box {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 6px;
        margin-bottom: 12px;
    }

    .price-curr { font-size: 17px; font-weight: 700; color: var(--text-main); }
    .price-old { font-size: 12px; text-decoration: line-through; color: #bbb; }

    .card-btn {
        display: block;
        width: 100%;
        padding: 10px;
        background: var(--wk-orange);
        color: white;
        text-align: center;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: 0.3s;
        text-decoration: none;
    }

    .card-btn:hover { background: #e64a19; color: white; }

    /* ===== CATEGORY CHIPS ===== */
    .category-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 25px;
        padding: 0 15px;
        max-width: 1350px;
        margin: 0 auto 25px;
    }

    .category-chip {
        background: white;
        padding: 10px 20px;
        border-radius: 25px;
        border: 1px solid #ddd;
        font-size: 13px;
        font-weight: 500;
        color: #555;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .category-chip:hover, .category-chip.active {
        background: var(--wk-orange);
        color: white;
        border-color: var(--wk-orange);
    }

    /* ===== MOBILE FILTER TOGGLE ===== */
    .filter-toggle-btn {
        display: none;
        width: 100%;
        padding: 12px;
        background: var(--wk-orange);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 15px;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* ===== SEARCH BANNER ===== */
    .search-banner {
        background: linear-gradient(135deg, #e65100 0%, #ff9800 100%);
        padding: 20px;
        color: white;
        text-align: center;
        margin-bottom: 20px;
        border-radius: 12px;
        max-width: 1350px;
        margin: 0 auto 20px;
    }
    .search-banner h2 { font-size: 20px; font-weight: 600; margin: 0 0 8px; }
    .search-banner p { margin: 0; opacity: 0.9; }
    .search-banner a { color: #fdd835; font-weight: 600; }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        border: 1px solid #eee;
    }
    .empty-state .icon { font-size: 80px; margin-bottom: 20px; }
    .empty-state h3 { color: var(--text-main); margin-bottom: 10px; font-size: 22px; }
    .empty-state p { color: #666; font-size: 15px; margin-bottom: 25px; }
    .empty-state .btn {
        display: inline-block;
        background: var(--wk-orange);
        color: white;
        padding: 14px 35px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
    }
    .empty-state .btn:hover { background: #e64a19; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .page-wrapper { flex-direction: column; padding: 0 12px 40px; }
        .filter-sidebar { width: 100%; max-width: 100%; position: static; display: none; }
        .filter-sidebar.show { display: block; }
        .filter-toggle-btn { display: flex; }
        .products-grid { grid-template-columns: repeat(2, 1fr); }
        .category-banner { padding: 42px 16px 36px; }
        .category-banner h1 { font-size: 34px; }
        .category-banner p { font-size: 15px; }
        .main-content { width: 100%; max-width: 100%; }
        .category-chips { padding: 0 12px; }
    }

    @media (max-width: 768px) {
        .category-banner { padding: 34px 16px 28px; }
        .hero-kicker { font-size: 11px; letter-spacing: 0.5px; }
        .category-banner h1 { font-size: 28px; }
        .category-banner p { font-size: 14px; }
        .feature-chip { font-size: 11px; padding: 6px 10px; }
        .hero-btn { font-size: 12px; padding: 9px 14px; }
        .products-grid { gap: 12px; }
        .product-card { padding: 12px; }
        .product-card img { height: 120px; }
    }

    @media (max-width: 576px) {
        .category-banner { padding: 28px 14px 22px; }
        .category-banner h1 { font-size: 24px; }
        .category-banner p { font-size: 13px; }
        .banner-highlights { gap: 8px; }
        .feature-chip { font-size: 10px; }
        .breadcrumb-bar { font-size: 12px; padding: 6px 11px; }
        .filter-toggle-btn { padding: 10px 16px; font-size: 13px; }
        .filter-sidebar { padding: 16px; }
        .product-card { padding: 10px; }
        .product-card img { height: 100px; }
        .section-title { font-size: 18px; }
        .category-chip { padding: 8px 14px; font-size: 12px; }
    }

    @media (max-width: 400px) {
        .category-banner h1 { font-size: 18px; }
        .product-card img { height: 80px; }
        .products-grid { gap: 10px; }
    }
</style>

<!-- ===== SEARCH BANNER ===== -->
@if(!empty($searchQuery))
<div class="search-banner">
    <h2>🔍 Search Results for "{{ $searchQuery }}"</h2>
    <p>Showing home appliances matching your search. <a href="{{ url('/appliances') }}">Clear search</a></p>
</div>
@endif

<!-- ===== CATEGORY CHIPS ===== -->
<div class="category-chips">
    <a href="{{ url('/appliances') }}" class="category-chip {{ empty($searchQuery) ? 'active' : '' }}">🔥 All Appliances</a>
    <a href="{{ url('/appliances?q=fan') }}" class="category-chip">🌬️ Fans</a>
    <a href="{{ url('/appliances?q=cooler') }}" class="category-chip">❄️ Coolers</a>
    <a href="{{ url('/appliances?q=ac') }}" class="category-chip">🌡️ Air Conditioners</a>
    <a href="{{ url('/appliances?q=mixer') }}" class="category-chip">🍳 Kitchen Appliances</a>
    <a href="{{ url('/appliances?q=water purifier') }}" class="category-chip">💧 Water Purifiers</a>
    <a href="{{ url('/appliances?q=washing') }}" class="category-chip">🧺 Washing Machines</a>
    <a href="{{ url('/appliances?q=iron') }}" class="category-chip">👔 Irons</a>
</div>

<!-- ===== PAGE WRAPPER ===== -->
<div class="page-wrapper" id="appliances-sections">

    <!-- Mobile Filter Toggle -->
    <button class="filter-toggle-btn" onclick="document.getElementById('filterSidebar').classList.toggle('show')">
        ☰ Filter Appliances
    </button>

    <!-- ===== FILTER SIDEBAR ===== -->
    <aside class="filter-sidebar" id="filterSidebar">
        <form method="GET" action="{{ url('/appliances') }}" id="filterForm">
            @if($searchQuery ?? false)<input type="hidden" name="q" value="{{ $searchQuery }}">@endif
            <h3>🏠 Filters</h3>

            <!-- Price Range -->
            <div class="filter-group">
                <h4>Price Range (₹)</h4>
                <div class="price-range-inputs">
                    <input type="number" name="min_price" placeholder="Min" min="0" value="{{ $minPrice ?? '' }}">
                    <span>—</span>
                    <input type="number" name="max_price" placeholder="Max" min="0" value="{{ $maxPrice ?? '' }}">
                </div>
            </div>

            <hr class="filter-divider">

            <!-- Brand Filter -->
            <div class="filter-group">
                <h4>Brand</h4>
                @foreach($availableBrands ?? [] as $brand)
                <label><input type="checkbox" name="brands[]" value="{{ $brand }}" {{ in_array($brand, $brands ?? []) ? 'checked' : '' }}> {{ $brand }}</label>
                @endforeach
            </div>

            <hr class="filter-divider">

            <!-- Sort By -->
            <div class="filter-group">
                <h4>Sort By</h4>
                <select name="sort_by" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ddd;">
                    <option value="newest" {{ ($sortBy ?? '') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="price_asc" {{ ($sortBy ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ ($sortBy ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ ($sortBy ?? '') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                </select>
            </div>

            <button type="submit" class="apply-filter-btn">Apply Filters</button>
            <a href="{{ url('/appliances') }}" class="clear-filter-btn" style="display:block; text-align:center; text-decoration:none;">Clear All</a>
        </form>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">

        <!-- ===== APPLIANCES SECTION ===== -->
        @if($all_appliances->count() > 0)
        <div class="section-container">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="home-outline" style="color:var(--wk-orange)"></ion-icon>
                    Home Appliances
                    <span style="font-size: 14px; color: #999; font-weight: 400; margin-left: 10px;">({{ $all_appliances->total() }} products)</span>
                </h3>
            </div>
            <div class="products-grid">
                @foreach($all_appliances as $product)
                    <div class="product-card">
                        <a href="{{ url('product/'.$product->id) }}">
                            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                            <div class="product-title">{{ Str::limit($product->name, 45) }}</div>
                            <h6>{{ $product->company ?? 'Brand' }}</h6>
                            <div class="price-box">
                                <span class="price-curr">₹{{ number_format($product->final_price ?? $product->offer_price ?? $product->total_price, 2) }}</span>
                                @if(($product->total_price ?? 0) > ($product->final_price ?? $product->offer_price ?? $product->total_price))
                                    <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                @endif
                            </div>
                        </a>
                        <a href="{{ url('product/'.$product->id) }}" class="card-btn">View Details</a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($all_appliances->hasPages())
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $all_appliances->links() }}
            </div>
            @endif
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="icon">🏠</div>
            <h3>No Appliances Found</h3>
            <p>We're adding more home appliances soon! Check back later or browse other categories.</p>
            <a href="{{ url('/') }}" class="btn">Browse All Products</a>
        </div>
        @endif

    </div>
</div>

@endsection
