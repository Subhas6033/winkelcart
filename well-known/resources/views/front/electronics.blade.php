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

    body { background-color: var(--bg-body) !important; font-family: 'Poppins', sans-serif; overflow-x: hidden; }
    * { box-sizing: border-box; }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; display: block; }

    /* ===== CATEGORY BANNER ===== */
    .category-banner {
        background: linear-gradient(135deg, #0d47a1 0%, #1e88e5 100%);
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
        border-bottom: 2px solid var(--wk-blue);
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

    .filter-group label:hover { color: var(--wk-blue); }

    .filter-group input[type="checkbox"] {
        accent-color: var(--wk-blue);
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
        border-color: var(--wk-blue);
        outline: none;
    }

    .price-range-inputs span { color: #999; font-size: 12px; }

    .filter-divider { border: none; border-top: 1px solid #eee; margin: 15px 0; }

    .apply-filter-btn {
        width: 100%;
        padding: 10px;
        background: var(--wk-blue);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: background 0.2s;
    }

    .apply-filter-btn:hover { background: #1565c0; }

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

    /* ===== BRAND GRID (electronics1) ===== */
    .brand-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
    }

    .brand-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        border: 1px solid #eee;
        transition: 0.3s;
        cursor: pointer;
    }

    .brand-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: var(--wk-blue);
    }

    .brand-card img {
        width: 100%;
        height: 100px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    .brand-card .product-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 3px;
    }

    .brand-card h6 {
        font-size: 11px;
        color: #999;
        font-weight: 400;
    }

    /* ===== VIDEO + PRODUCTS BANNER (electronics2) ===== */
    .video-section {
        display: flex;
        gap: 20px;
        align-items: stretch;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        border: 1px solid #eee;
        margin-bottom: 45px;
    }

    .video-panel {
        flex: 1;
        min-width: 0;
    }

    .video-panel video {
        width: 100%;
        height: 100%;
        max-height: 320px;
        object-fit: cover;
        display: block;
    }

    .video-products-scroll {
        flex: 2;
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding: 15px;
        scroll-behavior: smooth;
    }

    .video-products-scroll::-webkit-scrollbar { height: 6px; }
    .video-products-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

    /* ===== HORIZONTAL SCROLL SECTIONS (mobile_accessories, headphones) ===== */
    .scroll-section { margin-bottom: 45px; }

    .products-scroll-row {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding-bottom: 10px;
        scroll-behavior: smooth;
    }

    .products-scroll-row::-webkit-scrollbar { height: 6px; }
    .products-scroll-row::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

    /* ===== PRODUCT CARD (shared) ===== */
    .product-card {
        min-width: 180px;
        background: white;
        border-radius: 10px;
        padding: 14px;
        border: 1px solid #eee;
        flex-shrink: 0;
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
        height: 130px;
        object-fit: contain;
        margin-bottom: 10px;
        transition: 0.4s;
    }

    .product-card:hover img { transform: scale(1.06); }

    .product-card .product-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .product-card h6 {
        font-size: 11px;
        color: #999;
        font-weight: 400;
        margin-bottom: 8px;
    }

    .price-box {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 6px;
        margin-bottom: 10px;
    }

    .price-curr { font-size: 15px; font-weight: 700; color: var(--text-main); }
    .price-old { font-size: 11px; text-decoration: line-through; color: #bbb; }

    .card-btn {
        display: block;
        width: 100%;
        padding: 8px;
        background: var(--wk-blue);
        color: white;
        text-align: center;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        transition: 0.3s;
        text-decoration: none;
    }

    .card-btn:hover { background: #1565c0; color: white; }

    /* ===== MOBILE FILTER TOGGLE ===== */
    .filter-toggle-btn {
        display: none;
        width: 100%;
        padding: 12px;
        background: var(--wk-blue);
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
        .brand-grid { grid-template-columns: repeat(2, 1fr); }
        .video-section { flex-direction: column; }
        .video-panel video { max-height: 200px; }
        .category-banner h1 { font-size: 26px; }
        .main-content { width: 100%; max-width: 100%; }
        .products-scroll-row { max-width: calc(100vw - 24px); }
    }

    @media (max-width: 768px) {
        .category-banner { padding: 30px 20px 24px; }
        .category-banner h1 { font-size: 24px; }
        .category-banner p { font-size: 14px; }
        .products-scroll-row { gap: 12px; }
        .product-card { min-width: 160px; }
        .brand-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .video-panel { padding: 16px; }
    }

    @media (max-width: 576px) {
        .category-banner { padding: 24px 15px 20px; }
        .category-banner h1 { font-size: 20px; }
        .category-banner p { font-size: 13px; }
        .breadcrumb-bar { font-size: 12px; }
        .filter-toggle-btn { padding: 10px 16px; font-size: 13px; }
        .filter-sidebar { padding: 16px; }
        .filter-sidebar h3 { font-size: 16px; }
        .product-card { min-width: 140px; }
        .product-card h4 { font-size: 13px; }
        .product-card .price { font-size: 14px; }
        .section-title { font-size: 18px; }
        .brand-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
        .brand-card { padding: 10px; }
        .brand-card img { height: 35px; }
        .video-panel video { max-height: 180px; }
    }

    @media (max-width: 400px) {
        .category-banner h1 { font-size: 18px; }
        .product-card { min-width: 130px; }
        .brand-grid { gap: 8px; }
    }

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
</style>

<!-- ===== SEARCH BANNER (shown only when searching) ===== -->
@if(!empty($searchQuery))
<div class="search-banner">
    <h2>🔍 Search Results for "{{ $searchQuery }}"</h2>
    <p>Showing electronics matching your search. <a href="{{ url('/electronics') }}">Clear search</a></p>
</div>
@endif

<!-- ===== CATEGORY BANNER ===== -->
<div class="category-banner">
    <h1>&#9889; Electronics &amp; Accessories</h1>
    <p>Top brands, latest gadgets, headphones, mobiles and more.</p>
    <div class="breadcrumb-bar">
        <a href="{{ url('/') }}">Home</a> / <span>Electronics</span>
    </div>
</div>

<!-- ===== PAGE WRAPPER ===== -->
<div class="page-wrapper">

    <!-- Mobile Filter Toggle -->
    <button class="filter-toggle-btn" onclick="document.getElementById('filterSidebar').classList.toggle('show')">
        &#9776; Filter Products
    </button>

    <!-- ===== FILTER SIDEBAR ===== -->
    <aside class="filter-sidebar" id="filterSidebar">
        <form method="GET" action="{{ url('/electronics') }}" id="filterForm">
            @if($searchQuery ?? false)<input type="hidden" name="q" value="{{ $searchQuery }}">@endif
            <h3>&#9889; Filters</h3>

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
                @foreach($availableBrands ?? ['Sony', 'boAt', 'JBL', 'Samsung', 'Realme', 'Xiaomi', 'Apple'] as $brand)
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
            <a href="{{ url('/electronics') }}{{ ($searchQuery ?? false) ? '?q='.$searchQuery : '' }}" class="clear-filter-btn" style="display:block; text-align:center; text-decoration:none;">Clear All</a>
        </form>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">

        <!-- ===== SECTION 1: Electronics Brand Grid (backend untouched) ===== -->
        <div class="section-container">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="flash-outline" style="color:var(--wk-blue)"></ion-icon>
                    Featured Electronics
                </h3>
            </div>
            <div class="brand-grid">
                @foreach($electronics1->products as $product)
                    <a href="{{ url('product/'.$product->id) }}" target="_blank" class="brand-card">
                        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="product-title">{{ $product->name }}</div>
                        <h6>Store: {{ $product->company }}</h6>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- ===== SECTION 2: Video Banner + Electronics2 (backend untouched) ===== -->
        <div class="section-container">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="headset-outline" style="color:var(--wk-pink)"></ion-icon>
                    Top Picks
                </h3>
            </div>
            <div class="video-section">
                <!-- Video Panel (backend untouched) -->
                <div class="video-panel">
                    <video autoplay muted loop playsinline>
                        <source src="assets/images/videoplay.mp4" type="video/mp4">
                        Your browser does not support HTML5 video.
                    </video>
                </div>

                <!-- Scrollable Products (backend untouched) -->
                <div class="video-products-scroll">
                    @foreach($electronics2->products as $product)
                        <div class="product-card">
                            <a href="{{ url('product/'.$product->id) }}" target="_blank">
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                                <div class="product-title">{{ $product->name }}</div>
                                <h6>Store: {{ $product->company }}</h6>
                                @if(isset($product->offer_price))
                                    <div class="price-box">
                                        <span class="price-curr">₹{{ number_format($product->offer_price, 2) }}</span>
                                        @if(isset($product->total_price) && $product->total_price > $product->offer_price)
                                            <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="card-btn">Buy Now</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ===== SECTION 3: Mobile Accessories (backend untouched) ===== -->
        <div class="scroll-section">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="phone-portrait-outline" style="color:var(--wk-green)"></ion-icon>
                    Mobile Accessories
                </h3>
            </div>
            <div class="products-scroll-row">
                @foreach($mobile_accessories->products as $product)
                    <div class="product-card">
                        <a href="{{ url('product/'.$product->id) }}" target="_blank">
                            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                            <div class="product-title">{{ $product->name }}</div>
                            <h6>Store: {{ $product->company }}</h6>
                            @if(isset($product->offer_price))
                                <div class="price-box">
                                    <span class="price-curr">₹{{ number_format($product->offer_price, 2) }}</span>
                                    @if(isset($product->total_price) && $product->total_price > $product->offer_price)
                                        <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="card-btn">Buy Now</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ===== SECTION 4: Headphones (backend untouched) ===== -->
        <div class="scroll-section">
            <div class="section-header">
                <h3 class="section-title">
                    <ion-icon name="musical-notes-outline" style="color:var(--wk-yellow)"></ion-icon>
                    Headphones
                </h3>
            </div>
            <div class="products-scroll-row">
                @foreach($headphones->products as $product)
                    <div class="product-card">
                        <a href="{{ url('product/'.$product->id) }}" target="_blank">
                            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                            <div class="product-title">{{ $product->name }}</div>
                            <h6>Store: {{ $product->company }}</h6>
                            @if(isset($product->offer_price))
                                <div class="price-box">
                                    <span class="price-curr">₹{{ number_format($product->offer_price, 2) }}</span>
                                    @if(isset($product->total_price) && $product->total_price > $product->offer_price)
                                        <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="card-btn">Buy Now</div>
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