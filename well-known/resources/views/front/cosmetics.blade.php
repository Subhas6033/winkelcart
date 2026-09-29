@extends('front.layouts.app')
@section('content')

<style>
  /* ================= RESET & VARIABLES ================= */
  :root {
    --wk-green: #43a047;
    --wk-dark-green: #2e7d32;
    --wk-light-green: #e8f5e9;
    --wk-pink: #e91e63;
    --wk-purple: #9c27b0;
    --wk-yellow: #fdd835;
    --text-main: #212121;
    --text-light: #757575;
    --bg-body: #f9f9f9;
    --white: #ffffff;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Poppins', Arial, sans-serif;
    background-color: var(--bg-body);
    color: var(--text-main);
    overflow-x: hidden;
  }

  a { text-decoration: none; color: inherit; transition: 0.3s; }
  ul { list-style: none; }
  img { max-width: 100%; display: block; }


  /* ================= COSMETICS HERO BANNER ================= */
  .cosmetics-hero {
    background: linear-gradient(135deg, #e91e63 0%, #9c27b0 100%);
    padding: 55px 20px;
    color: white;
    text-align: center;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
  }

  .cosmetics-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px);
    background-size: 22px 22px;
    opacity: 0.5;
  }

  .hero-content { position: relative; z-index: 2; }

  .hero-content h1 {
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 10px;
    letter-spacing: 1px;
  }

  .hero-content p {
    font-size: 18px;
    opacity: 0.9;
    margin-bottom: 22px;
  }

  .hero-btn {
    background: white;
    color: var(--wk-pink);
    padding: 12px 35px;
    border-radius: 30px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 14px;
    display: inline-block;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: 0.3s;
  }
  .hero-btn:hover { transform: translateY(-3px); background: #fce4ec; }

  /* ================= CATEGORY CIRCLES ================= */
  .cosmetics-categories {
    display: flex;
    justify-content: center;
    gap: 35px;
    margin-bottom: 40px;
    flex-wrap: wrap;
    padding: 0 15px;
  }

  .cat-circle {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: 0.3s;
  }
  .cat-circle:hover { transform: translateY(-5px); }

  .cat-img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: white;
    border: 3px solid #f8bbd0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 5px 15px rgba(233,30,99,0.1);
    margin-bottom: 10px;
    overflow: hidden;
    font-size: 36px;
    line-height: 1;
  }

  .cat-name {
    font-weight: 700;
    font-size: 13px;
    color: var(--text-main);
    text-align: center;
  }

  /* ================= FILTER SECTION ================= */
  .filter-section {
    max-width: 1350px;
    margin: 0 auto 35px auto;
    padding: 0 15px;
  }

  .filter-card {
    background: white;
    border-radius: 16px;
    padding: 25px 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    border: 1px solid #eee;
  }

  .filter-card h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .filter-card h3 span { color: var(--wk-pink); font-size: 20px; }

  .filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-end;
  }

  .filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
    min-width: 150px;
  }

  .filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .filter-select,
  .filter-input {
    padding: 10px 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    color: var(--text-main);
    background: #fafafa;
    outline: none;
    font-family: inherit;
    transition: 0.2s;
    width: 100%;
  }

  .filter-select:focus,
  .filter-input:focus {
    border-color: var(--wk-pink);
    background: white;
    box-shadow: 0 0 0 3px rgba(233,30,99,0.1);
  }

  .filter-actions {
    display: flex;
    gap: 10px;
    align-items: flex-end;
    flex-shrink: 0;
  }

  .btn-apply {
    background: var(--wk-pink);
    color: white;
    border: none;
    padding: 11px 28px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    font-family: inherit;
  }
  .btn-apply:hover { background: #c2185b; }

  .btn-reset {
    background: transparent;
    color: var(--text-light);
    border: 1.5px solid #ddd;
    padding: 11px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    font-family: inherit;
  }
  .btn-reset:hover { border-color: #999; color: var(--text-main); }

  .price-range-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid #f0f0f0;
    flex-wrap: wrap;
  }

  .price-range-row label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-light);
    white-space: nowrap;
  }

  .price-range-row input[type="range"] {
    flex: 1;
    min-width: 150px;
    accent-color: var(--wk-pink);
    height: 4px;
    cursor: pointer;
  }

  .price-display {
    font-size: 13px;
    font-weight: 700;
    color: var(--wk-pink);
    white-space: nowrap;
    min-width: 120px;
  }

  /* Quick Tags */
  .filter-tags-row {
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid #f0f0f0;
  }

  .filter-tags-row label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 10px;
  }

  .filter-tags { display: flex; flex-wrap: wrap; gap: 10px; }

  .filter-tag {
    padding: 8px 18px;
    border-radius: 20px;
    border: 1.5px solid #ddd;
    background: white;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.2s;
    color: var(--text-main);
  }
  .filter-tag:hover, .filter-tag.active {
    background: var(--wk-pink);
    color: white;
    border-color: var(--wk-pink);
  }

  /* ================= SECTION LAYOUT ================= */
  .section-container {
    max-width: 1350px;
    margin: 0 auto 55px auto;
    padding: 0 15px;
  }

  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px;
  }

  .section-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-main);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-title .icon { color: var(--wk-pink); font-size: 22px; }

  .view-all { color: var(--wk-pink); font-weight: 600; font-size: 14px; }

  /* ================= PRODUCT GRID ================= */
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
  }

  /* ================= PRODUCT CARD ================= */
  .product-card-wrap {
    background: white;
    border-radius: 12px;
    border: 1px solid #eee;
    overflow: hidden;
    position: relative;
    transition: 0.3s;
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  .product-card-wrap:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
  }

  /* The <a> tag from backend becomes the card */
  .product-grid > a {
    background: white;
    border-radius: 12px;
    border: 1px solid #eee;
    overflow: hidden;
    position: relative;
    transition: 0.3s;
    display: flex;
    flex-direction: column;
    padding: 15px;
    color: var(--text-main);
  }
  .product-grid > a:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
  }

  .product-grid > a img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 12px;
    transition: transform 0.4s ease;
  }
  .product-grid > a:hover img { transform: scale(1.05); }

  .product-grid > a h5 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .product-grid > a h6 {
    font-size: 11px;
    font-weight: 600;
    color: var(--wk-pink);
    text-transform: uppercase;
    margin-bottom: 8px;
    letter-spacing: 0.3px;
  }

  .product-grid > a .price {
    font-size: 17px;
    font-weight: 700;
    color: var(--text-main);
    margin-top: auto;
  }

  .product-grid > a .original-price {
    font-size: 13px;
    font-weight: 400;
    text-decoration: line-through;
    color: #999;
    margin-left: 6px;
  }

  /* ================= FOOTER MOBILE ================= */
  @media (max-width: 768px) {
    .footer-nav .container {
      display: flex; flex-direction: column; gap: 10px; padding: 10px 15px; background-color: #1c1c1c;
    }
    .footer-nav-list { border-bottom: 1px solid #333; padding: 10px 0; }
    .nav-title { font-size: 16px; font-weight: 700; color: #fff; display: flex; justify-content: space-between; cursor: pointer; }
    .footer-links { display: none; margin-top: 8px; }
    .footer-links a { display: block; font-size: 14px; color: #bbb; text-decoration: none; margin-bottom: 6px; }
    .footer-links a:hover { color: #ff9500; }
    .footer-nav-list.active .footer-links { display: block; }
    .footer-bottom { background-color: #141414; text-align: center; padding: 15px; border-top: 1px solid #333; }
    .footer-bottom .copyright { font-size: 13px; color: #bbb; }

    .cosmetics-hero { padding: 35px 15px; }
    .hero-content h1 { font-size: 28px; }
    .hero-content p { font-size: 14px; }
    .cat-img { width: 75px; height: 75px; font-size: 26px; }
    .cosmetics-categories { gap: 18px; }
    .filter-row { flex-direction: column; }
    .filter-group { min-width: 100%; }
    .filter-actions { width: 100%; }
    .btn-apply, .btn-reset { flex: 1; text-align: center; }
    .product-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; }
    .product-grid > a img { height: 160px; }
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

<body>

  <!-- ===== SEARCH BANNER (shown only when searching) ===== -->
  @if(!empty($searchQuery))
  <div class="search-banner">
    <h2>🔍 Search Results for "{{ $searchQuery }}"</h2>
    <p>Showing cosmetics matching your search. <a href="{{ url('/cosmetics') }}">Clear search</a></p>
  </div>
  @endif

  <!-- =============== CATEGORY CIRCLES =============== -->
  <div class="cosmetics-categories">
    <div class="cat-circle">
      <div class="cat-img">&#128137;</div>
      <span class="cat-name">Skincare</span>
    </div>
    <div class="cat-circle">
      <div class="cat-img">&#128144;</div>
      <span class="cat-name">Makeup</span>
    </div>
    <div class="cat-circle">
      <div class="cat-img">&#128579;</div>
      <span class="cat-name">Haircare</span>
    </div>
    <div class="cat-circle">
      <div class="cat-img">&#129717;</div>
      <span class="cat-name">Fragrance</span>
    </div>
    <div class="cat-circle">
      <div class="cat-img">&#10024;</div>
      <span class="cat-name">Offers</span>
    </div>
  </div>

  <!-- =============== FILTER SECTION =============== -->
  <div class="filter-section">
    <form method="GET" action="{{ url('/cosmetics') }}" id="filterForm" class="filter-card">
      @if($searchQuery ?? false)<input type="hidden" name="q" value="{{ $searchQuery }}">@endif
      <h3><span>&#128269;</span> Filter Products</h3>

      <div class="filter-row">

        <!-- Category -->
        <div class="filter-group">
          <label>Category</label>
          <select class="filter-select" name="category">
            <option value="">All Categories</option>
            <option value="skincare" {{ ($filterCategory ?? '') == 'skincare' ? 'selected' : '' }}>Skincare</option>
            <option value="makeup" {{ ($filterCategory ?? '') == 'makeup' ? 'selected' : '' }}>Makeup</option>
            <option value="haircare" {{ ($filterCategory ?? '') == 'haircare' ? 'selected' : '' }}>Haircare</option>
            <option value="fragrance" {{ ($filterCategory ?? '') == 'fragrance' ? 'selected' : '' }}>Fragrance</option>
          </select>
        </div>

        <!-- Brand / Store -->
        <div class="filter-group">
          <label>Brand / Store</label>
          <select class="filter-select" name="brands[]">
            <option value="">All Brands</option>
            @foreach($availableBrands ?? ['Lakme', 'Nykaa', 'Maybelline', 'LOreal', 'Nivea', 'Garnier', 'Biotique', 'Himalaya'] as $brand)
            <option value="{{ $brand }}" {{ in_array($brand, $brands ?? []) ? 'selected' : '' }}>{{ $brand }}</option>
            @endforeach
          </select>
        </div>

        <!-- Sort By -->
        <div class="filter-group">
          <label>Sort By</label>
          <select class="filter-select" name="sort_by">
            <option value="newest" {{ ($sortBy ?? '') == 'newest' ? 'selected' : '' }}>Newest First</option>
            <option value="price_asc" {{ ($sortBy ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="price_desc" {{ ($sortBy ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            <option value="discount" {{ ($sortBy ?? '') == 'discount' ? 'selected' : '' }}>Biggest Discount</option>
          </select>
        </div>

        <!-- Price Range -->
        <div class="filter-group">
          <label>Max Price (₹)</label>
          <input type="number" class="filter-input" name="max_price" placeholder="e.g. 5000" value="{{ $maxPrice ?? '' }}" min="0">
        </div>

        <!-- Actions -->
        <div class="filter-actions">
          <a href="{{ url('/cosmetics') }}{{ ($searchQuery ?? false) ? '?q='.$searchQuery : '' }}" class="btn-reset">Reset</a>
          <button type="submit" class="btn-apply">Apply Filters</button>
        </div>
      </div>

    </form>
  </div>

  <!-- =============== COSMETICS SECTION (BACKEND) =============== -->
  <section class="section-container" id="cosmetics-section">
    <div class="section-header">
      <h3 class="section-title">
        <span class="icon">&#10024;</span> Cosmetics
      </h3>
      <a href="#" class="view-all">View All &rarr;</a>
    </div>
    <div class="product-grid">
      @foreach($other_cosmetics->products as $product)
      <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
        <h5>{{ $product->name }}</h5>
        <div>
          <h6>Store: {{ $product->company }}</h6>
        </div>
        <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
      </a>
      @endforeach
    </div>
  </section>

  <!-- =============== SKINCARE SECTION (BACKEND) =============== -->
  <section class="section-container">
    <div class="section-header">
      <h3 class="section-title">
        <span class="icon">&#127807;</span> Skincare
      </h3>
      <a href="#" class="view-all">View All &rarr;</a>
    </div>
    <div class="product-grid">
      @foreach($skincare->products as $product)
      <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
        <h5>{{ $product->name }}</h5>
        <div>
          <h6>Store: {{ $product->company }}</h6>
        </div>
        <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
      </a>
      @endforeach
    </div>
  </section>

  <!-- =============== MAKEUP SECTION (BACKEND) =============== -->
  <section class="section-container">
    <div class="section-header">
      <h3 class="section-title">
        <span class="icon">&#128144;</span> Makeup
      </h3>
      <a href="#" class="view-all">View All &rarr;</a>
    </div>
    <div class="product-grid">
      @foreach($makeup->products as $product)
      <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
        <h5>{{ $product->name }}</h5>
        <div>
          <h6>Store: {{ $product->company }}</h6>
        </div>
        <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
      </a>
      @endforeach
    </div>
  </section>

  <!-- =============== HAIRCARE SECTION (BACKEND) =============== -->
  <section class="section-container">
    <div class="section-header">
      <h3 class="section-title">
        <span class="icon">&#128579;</span> Haircare
      </h3>
      <a href="#" class="view-all">View All &rarr;</a>
    </div>
    <div class="product-grid">
      @foreach($haircare->products as $product)
      <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
        <h5>{{ $product->name }}</h5>
        <div>
          <h6>Store: {{ $product->company }}</h6>
        </div>
        <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
      </a>
      @endforeach
    </div>
  </section>

</body>

<script>
  /* ---- Quick tag toggle ---- */
  function setTag(btn) {
    document.querySelectorAll('.filter-tag').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
  }

  /* ---- Apply Filters (front-end feedback – extend with backend/AJAX as needed) ---- */
  function applyFilters() {
    const category = document.getElementById('filterCategory').value;
    const brand    = document.getElementById('filterBrand').value;
    const sort     = document.getElementById('filterSort').value;
    const skin     = document.getElementById('filterSkin').value;
    const price    = document.getElementById('priceRange').value;
    const quickTag = document.querySelector('.filter-tag.active')?.textContent?.trim() || 'All';

    // Extend this to submit a form or use fetch/AJAX with Laravel routes
    console.log('Filters applied:', { category, brand, sort, skin, price, quickTag });

    const btn = document.querySelector('.btn-apply');
    btn.textContent = '✓ Applied!';
    setTimeout(() => btn.textContent = 'Apply Filters', 1500);
  }

  /* ---- Reset Filters ---- */
  function resetFilters() {
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterBrand').value    = '';
    document.getElementById('filterSort').value     = '';
    document.getElementById('filterSkin').value     = '';
    document.getElementById('priceRange').value     = 5000;
    document.getElementById('priceDisplay').textContent = '₹50 – ₹5,000';
    document.querySelectorAll('.filter-tag').forEach((t, i) => {
      t.classList.toggle('active', i === 0);
    });
  }
</script>

@endsection