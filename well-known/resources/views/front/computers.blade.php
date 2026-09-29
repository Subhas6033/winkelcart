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
        --text-main: #212121;
        --bg-body: #f9f9f9;
    }

    html, body { 
        background-color: var(--bg-body) !important; 
        font-family: 'Poppins', sans-serif; 
        overflow-x: hidden; 
        max-width: 100vw;
    }
    * { box-sizing: border-box; }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; display: block; }

    /* ===== SEARCH BANNER ===== */
    .search-banner {
        background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%);
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

    /* ===== CATEGORY BANNER ===== */
    .category-banner {
        background: linear-gradient(135deg, #1b5e20 0%, #43a047 100%);
        padding: 40px 0;
        color: white;
        text-align: center;
        margin-bottom: 30px;
    }

    .category-banner h1 { font-size: 36px; font-weight: 800; margin-bottom: 10px; }
    .category-banner p { font-size: 16px; opacity: 0.9; }

    .breadcrumb-bar {
        margin-top: 12px;
        font-size: 13px;
        opacity: 0.8;
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
        border-bottom: 2px solid var(--wk-green);
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

    .filter-group label:hover { color: var(--wk-green); }

    .filter-group input[type="checkbox"] {
        accent-color: var(--wk-green);
        width: 15px;
        height: 15px;
        cursor: pointer;
    }

    /* Price Range */
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
        border-color: var(--wk-green);
        outline: none;
    }

    .price-range-inputs span { color: #999; font-size: 12px; }

    /* Filter Divider */
    .filter-divider {
        border: none;
        border-top: 1px solid #eee;
        margin: 15px 0;
    }

    /* Apply Filter Button */
    .apply-filter-btn {
        width: 100%;
        padding: 10px;
        background: var(--wk-green);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: background 0.2s;
    }

    .apply-filter-btn:hover { background: var(--wk-dark-green); }

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

    /* ===== MAIN CONTENT AREA ===== */
    .main-content { flex: 1; min-width: 0; overflow-x: hidden; max-width: 100%; }

    /* ===== SECTION CONTAINER ===== */
    .section-container { margin-bottom: 45px; max-width: 100%; overflow-x: hidden; }

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
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        width: 100%;
        max-width: 100%;
    }

    /* ===== PRODUCT CARD ===== */
    .product-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        min-width: 0; /* Prevent overflow */
        border: 1px solid #eee;
        position: relative;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.09);
        border-color: transparent;
    }

    .product-card a { color: var(--text-main); text-decoration: none; display: flex; flex-direction: column; flex: 1; }

    .badge {
        position: absolute;
        top: 12px; left: 12px;
        background: var(--wk-yellow);
        color: black;
        font-size: 10px; font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        z-index: 2;
    }

    .discount {
        position: absolute;
        top: 12px; right: 12px;
        background: var(--wk-pink);
        color: white;
        font-size: 10px; font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
        z-index: 2;
    }

    .product-img {
        height: 170px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .product-img img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: 0.4s;
    }

    .product-card:hover .product-img img { transform: scale(1.07); }

    .product-info { flex: 1; display: flex; flex-direction: column; }

    .product-info h3, .product-info h5 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--text-main);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .store-name, .product-info h6 {
        font-size: 11px;
        color: #999;
        font-weight: 400;
        margin-bottom: 8px;
    }

    .price-box {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 12px;
        margin-top: auto;
    }

    .price-curr, .price {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-main);
    }

    .price-old, .original-price {
        font-size: 12px;
        text-decoration: line-through;
        color: #bbb;
    }

    .card-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 10px;
        background: var(--wk-green);
        color: white;
        text-align: center;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        transition: 0.3s;
        text-decoration: none;
    }

    .card-btn:hover { background: var(--wk-dark-green); color: white; }

    /* ===== MOBILE FILTER TOGGLE ===== */
    .filter-toggle-btn {
        display: none;
        width: 100%;
        padding: 12px;
        background: var(--wk-green);
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .page-wrapper { flex-direction: column; padding: 0 12px 40px; }
        .filter-sidebar { width: 100%; max-width: 100%; position: static; display: none; }
        .filter-sidebar.show { display: block; }
        .filter-toggle-btn { display: flex; }
        .product-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .category-banner h1 { font-size: 26px; }
        .main-content { width: 100%; max-width: 100%; }
    }

    @media (max-width: 576px) {
        .category-banner { padding: 25px 15px; }
        .category-banner h1 { font-size: 22px; }
        .category-banner p { font-size: 13px; }
        .breadcrumb-bar { font-size: 12px; }
        .filter-toggle-btn { padding: 10px 16px; font-size: 13px; }
        .product-grid { gap: 10px; }
    }

    @media (max-width: 480px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .category-banner h1 { font-size: 18px; }
        .category-banner p { font-size: 12px; }
        .page-wrapper { padding: 0 10px 30px; }
        .filter-toggle-btn { padding: 8px 14px; font-size: 12px; }
        .search-banner { padding: 15px; border-radius: 8px; margin: 0 10px 15px; }
        .search-banner h2 { font-size: 16px; }
        .search-banner p { font-size: 12px; }
    }
    
    @media (max-width: 399px) {
        .category-banner { padding: 20px 12px; }
        .category-banner h1 { font-size: 16px; }
        .category-banner p { font-size: 11px; }
        .breadcrumb-bar { font-size: 11px; }
        .page-wrapper { padding: 0 8px 25px; }
        .product-grid { gap: 8px; }
        .filter-toggle-btn { padding: 8px 12px; font-size: 11px; width: 100%; justify-content: center; }
    }
    
    /* Samsung Galaxy and similar 360px devices */
    @media (max-width: 375px) {
        .page-wrapper { 
            padding: 0 6px 25px; 
            overflow-x: hidden;
            max-width: 100%;
            box-sizing: border-box;
        }
        .main-content {
            overflow-x: hidden;
            max-width: 100%;
        }
        .section-container {
            overflow-x: hidden;
            max-width: 100%;
        }
        .product-grid { 
            grid-template-columns: repeat(2, 1fr); 
            gap: 6px;
            width: 100%;
            max-width: 100%;
        }
        .product-card {
            padding: 8px;
            border-radius: 8px;
            min-width: 0;
            overflow: hidden;
        }
        .product-img { height: 110px; margin-bottom: 8px; }
        .product-info { min-width: 0; }
        .product-info h3, .product-info h5 { 
            font-size: 11px; 
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .store-name { font-size: 9px; }
        .price { font-size: 12px; }
        .original-price, .price-old { font-size: 9px; }
        .card-btn { 
            padding: 7px 8px; 
            font-size: 10px; 
            gap: 4px;
        }
        .section-title { font-size: 15px; gap: 5px; }
        .section-header { margin-bottom: 10px; padding-bottom: 6px; }
        .section-container { margin-bottom: 25px; }
        .badge, .discount { font-size: 8px; padding: 2px 5px; top: 6px; }
        .badge { left: 6px; }
        .discount { right: 6px; }
    }
    
    @media (max-width: 359px) {
        .category-banner h1 { font-size: 15px; }
        .category-banner p { font-size: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-grid { grid-template-columns: 1fr; gap: 8px; }
        .page-wrapper { padding: 0 6px 20px; }
    }
</style>

<!-- ===== SEARCH BANNER (shown only when searching) ===== -->
@if(!empty($searchQuery))
<div class="search-banner">
    <h2>🔍 Search Results for "{{ $searchQuery }}"</h2>
    <p>Showing computers matching your search. <a href="{{ url('/computers') }}">Clear search</a></p>
</div>
@endif

<!-- ===== CATEGORY BANNER ===== -->
<div class="category-banner">
    <h1>&#128187; Computers &amp; Laptops</h1>
    <p>High-performance Desktops, Monitors, and Accessories for work and gaming.</p>
    <div class="breadcrumb-bar">
        <a href="{{ url('/') }}">Home</a> / <span>Computers &amp; Laptops</span>
    </div>
</div>

<!-- ===== PAGE WRAPPER ===== -->
<div class="page-wrapper">

    <!-- ===== FILTER SIDEBAR ===== -->
    <button class="filter-toggle-btn" onclick="document.getElementById('filterSidebar').classList.toggle('show')">
        &#9776; Filter Products
    </button>

    <aside class="filter-sidebar" id="filterSidebar">
        <form method="GET" action="{{ url('/computers') }}" id="filterForm">
            @if($searchQuery)<input type="hidden" name="q" value="{{ $searchQuery }}">@endif
            <h3>&#127793; Filters</h3>

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
                @foreach($availableBrands ?? ['Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Apple'] as $brand)
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
            <a href="{{ url('/computers') }}{{ $searchQuery ? '?q='.$searchQuery : '' }}" class="clear-filter-btn" style="display:block; text-align:center; text-decoration:none;">Clear All</a>
        </form>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">

        <!-- Desktops Section (backend untouched) -->
        <div class="section-container">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="desktop-outline" style="color:var(--wk-blue)"></ion-icon>
                    Desktops
                </h3>
            </div>
            <div class="product-grid">
                @foreach($desktops->products as $product)
                    <div class="product-card">
                        @if($product->total_price > $product->offer_price)
                            <span class="discount">-{{ round((($product->total_price - $product->offer_price) / $product->total_price) * 100) }}%</span>
                        @endif
                        <a href="{{ url('product/'.$product->id) }}" target="_blank">
                            <div class="product-img">
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                            </div>
                            <div class="product-info">
                                <h5>{{ $product->name }}</h5>
                                <h6>Store: {{ $product->company }}</h6>
                                <div class="price-box">
                                    <span class="price-curr">₹{{ number_format($product->offer_price, 2) }}</span>
                                    @if($product->total_price > $product->offer_price)
                                        <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="card-btn">Buy Now</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Monitors Section (backend untouched) -->
        <div class="section-container">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="tv-outline" style="color:var(--wk-pink)"></ion-icon>
                    Monitors
                </h3>
            </div>
            <div class="product-grid">
                @foreach($monitors->products as $product)
                    <div class="product-card">
                        @if($product->total_price > $product->offer_price)
                            <span class="discount">-{{ round((($product->total_price - $product->offer_price) / $product->total_price) * 100) }}%</span>
                        @endif
                        <a href="{{ url('product/'.$product->id) }}" target="_blank">
                            <div class="product-img">
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                            </div>
                            <div class="product-info">
                                <h5>{{ $product->name }}</h5>
                                <h6>Store: {{ $product->company }}</h6>
                                <div class="price-box">
                                    <span class="price-curr">₹{{ number_format($product->offer_price, 2) }}</span>
                                    @if($product->total_price > $product->offer_price)
                                        <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="card-btn">Buy Now</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Mouse Section (backend untouched) -->
        <div class="section-container">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="hardware-chip-outline" style="color:var(--wk-yellow)"></ion-icon>
                    Mouse &amp; Keyboard
                </h3>
            </div>
            <div class="product-grid">
                @foreach($mouses->products as $product)
                    <div class="product-card">
                        @if($product->total_price > $product->offer_price)
                            <span class="discount">-{{ round((($product->total_price - $product->offer_price) / $product->total_price) * 100) }}%</span>
                        @endif
                        <a href="{{ url('product/'.$product->id) }}" target="_blank">
                            <div class="product-img">
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                            </div>
                            <div class="product-info">
                                <h5>{{ $product->name }}</h5>
                                <h6>Store: {{ $product->company }}</h6>
                                <div class="price-box">
                                    <span class="price-curr">₹{{ number_format($product->offer_price, 2) }}</span>
                                    @if($product->total_price > $product->offer_price)
                                        <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="card-btn">Buy Now</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    <!-- end main-content -->

</div>
<!-- end page-wrapper -->

@endsection