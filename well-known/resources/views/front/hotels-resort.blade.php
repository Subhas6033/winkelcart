@extends('front.layouts.app')
@section('content')

<style>
  :root {
    --wk-green: #43a047;
    --wk-dark-green: #2e7d32;
    --wk-light-green: #e8f5e9;
    --wk-blue: #0288d1;
    --wk-dark-blue: #01579b;
    --wk-yellow: #fdd835;
    --wk-pink: #e91e63;
    --text-main: #212121;
    --text-light: #757575;
    --bg-body: #f9f9f9;
    --white: #ffffff;
  }

  * { box-sizing: border-box; }

  body {
    font-family: 'Poppins', Arial, sans-serif;
    background-color: var(--bg-body);
    color: var(--text-main);
    overflow-x: hidden;
    margin: 0;
    padding: 0;
  }

  a { text-decoration: none; color: inherit; transition: 0.3s; }
  ul { list-style: none; }
  img { max-width: 100%; display: block; }


  .travel-hero {
    background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1600') center/cover no-repeat;
    height: 420px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    margin-bottom: 40px;
    position: relative;
  }
  .hero-content h1 { font-size: 42px; font-weight: 800; margin-bottom: 10px; text-shadow: 0 2px 10px rgba(0,0,0,0.5); }
  .hero-content p { font-size: 18px; margin-bottom: 30px; text-shadow: 0 2px 5px rgba(0,0,0,0.5); }
  .booking-bar {
    background: white;
    padding: 10px 20px;
    border-radius: 50px;
    display: flex;
    gap: 15px;
    align-items: center;
    max-width: 820px;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    flex-wrap: wrap;
  }
  .booking-input {
    border: none; outline: none; padding: 10px; flex: 1;
    font-family: inherit; border-right: 1px solid #eee; min-width: 120px; color: var(--text-main);
  }
  .booking-input:last-of-type { border-right: none; }
  .booking-btn {
    background: var(--wk-blue); color: white; border: none; padding: 12px 30px;
    border-radius: 40px; font-weight: 600; cursor: pointer; transition: 0.3s; white-space: nowrap;
  }
  .booking-btn:hover { background: var(--wk-dark-blue); }

  .benefits-section { max-width: 1350px; margin: 0 auto 50px auto; padding: 0 15px; }
  .benefits-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
  .benefit-card {
    background: white; border-radius: 15px; height: 105px; display: flex; align-items: center;
    gap: 0; box-shadow: 0 5px 15px rgba(0,0,0,0.06); border: 1px solid #eee; overflow: hidden; transition: 0.3s;
  }
  .benefit-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
  .benefit-card a.product { display: flex; align-items: center; width: 100%; height: 100%; color: inherit; }
  .benefit-card .banner-img img { width: 140px; height: 100px; object-fit: cover; border-radius: 15px; margin: 0 10px 0 4px; display: block; flex-shrink: 0; }
  .benefit-card .text { padding: 0 10px; }
  .benefit-card .text h4 { font-size: 15px; margin: 0 0 8px; color: var(--text-main); font-weight: 700; }
  .benefit-card .text a { font-size: 13px; color: var(--wk-blue); font-weight: 700; }

  .filter-section { max-width: 1350px; margin: 0 auto 35px auto; padding: 0 15px; }
  .filter-card { background: white; border-radius: 16px; padding: 25px 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.06); border: 1px solid #eee; }
  .filter-card h3 { font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
  .filter-card h3 span { color: var(--wk-blue); font-size: 20px; }
  .filter-row { display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end; }
  .filter-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 160px; }
  .filter-group label { font-size: 12px; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; }
  .filter-select, .filter-input {
    padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 14px;
    color: var(--text-main); background: #fafafa; outline: none; font-family: inherit; transition: 0.2s; width: 100%;
  }
  .filter-select:focus, .filter-input:focus { border-color: var(--wk-blue); background: white; box-shadow: 0 0 0 3px rgba(2,136,209,0.1); }
  .filter-tags { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; flex: 2; min-width: 200px; }
  .filter-tag { padding: 8px 18px; border-radius: 20px; border: 1.5px solid #ddd; background: white; font-size: 13px; font-weight: 500; cursor: pointer; transition: 0.2s; color: var(--text-main); }
  .filter-tag:hover, .filter-tag.active { background: var(--wk-blue); color: white; border-color: var(--wk-blue); }
  .filter-actions { display: flex; gap: 10px; align-items: flex-end; flex-shrink: 0; }
  .btn-apply { background: var(--wk-blue); color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.3s; font-family: inherit; }
  .btn-apply:hover { background: var(--wk-dark-blue); }
  .btn-reset { background: transparent; color: var(--text-light); border: 1.5px solid #ddd; padding: 11px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.3s; font-family: inherit; }
  .btn-reset:hover { border-color: #999; color: var(--text-main); }
  .price-range-row { display: flex; align-items: center; gap: 10px; margin-top: 18px; padding-top: 18px; border-top: 1px solid #f0f0f0; flex-wrap: wrap; }
  .price-range-row label { font-size: 13px; font-weight: 600; color: var(--text-light); white-space: nowrap; }
  .price-range-row input[type="range"] { flex: 1; min-width: 150px; accent-color: var(--wk-blue); height: 4px; cursor: pointer; }
  .price-display { font-size: 13px; font-weight: 700; color: var(--wk-blue); white-space: nowrap; min-width: 120px; }
  .filter-amenities { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; padding-top: 18px; border-top: 1px solid #f0f0f0; }
  .amenity-chip { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 20px; border: 1.5px solid #e0e0e0; font-size: 13px; cursor: pointer; transition: 0.2s; background: white; color: var(--text-main); user-select: none; }
  .amenity-chip input[type="checkbox"] { accent-color: var(--wk-blue); width: 14px; height: 14px; cursor: pointer; }
  .amenity-chip:hover { border-color: var(--wk-blue); color: var(--wk-blue); }
  .amenity-chip.checked { background: var(--wk-light-green); border-color: var(--wk-green); color: var(--wk-green); }

  .stay-section { position: relative; max-width: 1350px; margin: 0 auto 50px auto; padding: 0 15px; }
  .stay-section h2 { font-size: 22px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; border-bottom: 2px solid #e0e0e0; padding-bottom: 10px; }
  .stay-slider { display: flex; overflow-x: auto; scroll-behavior: smooth; gap: 15px; padding-bottom: 10px; scrollbar-width: none; }
  .stay-slider::-webkit-scrollbar { display: none; }
  .stay-card { min-width: 180px; height: 240px; border-radius: 12px; overflow: hidden; flex-shrink: 0; position: relative; cursor: pointer; transition: transform 0.3s; display: block; }
  .stay-card:hover { transform: scale(1.05); }
  .stay-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .stay-label { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); color: white; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; backdrop-filter: blur(2px); }
  .slider-button { position: absolute; top: calc(50% + 20px); transform: translateY(-50%); background-color: white; border: none; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.2); width: 38px; height: 38px; cursor: pointer; z-index: 10; font-size: 16px; transition: 0.2s; }
  .slider-button:hover { background: var(--wk-blue); color: white; }
  .left-button { left: 5px; }
  .right-button { right: 5px; }

  @media (max-width: 768px) {
    .footer-nav .container { display: flex; flex-direction: column; gap: 10px; padding: 10px 15px; background-color: #1c1c1c; }
    .footer-nav-list { border-bottom: 1px solid #333; padding: 10px 0; }
    .nav-title { font-size: 16px; font-weight: 700; color: #fff; display: flex; justify-content: space-between; cursor: pointer; }
    .footer-links { display: none; margin-top: 8px; }
    .footer-links a { display: block; font-size: 14px; color: #bbb; text-decoration: none; margin-bottom: 6px; }
    .footer-links a:hover { color: #ff9500; }
    .footer-nav-list.active .footer-links { display: block; }
    .footer-bottom { background-color: #141414; text-align: center; padding: 15px; border-top: 1px solid #333; }
    .footer-bottom .payment-img { max-width: 200px; margin-bottom: 10px; }
    .footer-bottom .copyright { font-size: 13px; color: #bbb; }
    .footer-bottom .copyright a { color: #ff9500; text-decoration: none; }
    .travel-hero { height: 320px; }
    .hero-content h1 { font-size: 28px; }
    .hero-content p { font-size: 14px; }
    .booking-bar { border-radius: 14px; flex-direction: column; width: 90%; }
    .booking-input { width: 100%; border-right: none; border-bottom: 1px solid #eee; }
    .booking-btn { width: 100%; }
    .stay-card { min-width: 140px; height: 180px; }
    .filter-row { flex-direction: column; }
    .filter-group { min-width: 100%; }
    .filter-actions { width: 100%; }
    .btn-apply, .btn-reset { flex: 1; text-align: center; }
  }

  @media (max-width: 576px) {
    .travel-hero { height: 280px; padding: 0 15px; }
    .hero-content h1 { font-size: 22px; }
    .hero-content p { font-size: 13px; margin-bottom: 16px; }
    .booking-bar { width: 95%; padding: 12px; gap: 8px; }
    .booking-input { padding: 10px 12px; font-size: 14px; }
    .booking-btn { padding: 12px; font-size: 14px; }
    .stay-card { min-width: 120px; height: 160px; }
    .stay-card h4 { font-size: 13px; }
    .hotels-grid { grid-template-columns: 1fr; gap: 16px; }
    .hotel-card { border-radius: 12px; }
    .hotel-card img { height: 180px; }
    .hotel-info { padding: 14px; }
    .hotel-name { font-size: 16px; }
    .hotel-location { font-size: 13px; }
    .hotel-price { font-size: 18px; }
    .filter-card { padding: 14px; margin-bottom: 16px; }
    .benefits-section { padding: 16px 12px; }
    .benefits-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .benefit-card h4 { font-size: 13px; }
  }

  @media (max-width: 400px) {
    .travel-hero { height: 260px; }
    .hero-content h1 { font-size: 20px; }
    .booking-bar { padding: 10px; }
    .booking-input { padding: 8px 10px; font-size: 13px; }
    .stay-card { min-width: 100px; height: 140px; }
    .hotel-card img { height: 150px; }
    .benefits-grid { grid-template-columns: 1fr; }
  }
  
  @media (max-width: 359px) {
    .travel-hero { height: 240px; padding: 0 10px; }
    .hero-content h1 { font-size: 18px; }
    .hero-content p { font-size: 12px; }
    .booking-bar { width: 100%; padding: 8px; gap: 6px; border-radius: 10px; }
    .booking-input { padding: 8px; font-size: 12px; border-radius: 6px; }
    .booking-btn { padding: 10px; font-size: 13px; border-radius: 6px; }
    .stay-card { min-width: 90px; height: 120px; border-radius: 8px; }
    .stay-card h4 { font-size: 11px; }
    .slider-button { width: 30px; height: 30px; font-size: 12px; }
    .hotels-grid { gap: 12px; }
    .hotel-card img { height: 130px; }
    .hotel-info { padding: 10px; }
    .hotel-name { font-size: 14px; }
    .hotel-location { font-size: 12px; }
    .hotel-price { font-size: 16px; }
    .filter-card { padding: 12px; border-radius: 10px; }
    .filter-actions button { padding: 8px 14px; font-size: 12px; }
    .benefits-section { padding: 12px 10px; }
    .benefit-card { padding: 10px; border-radius: 8px; }
    .benefit-card h4 { font-size: 12px; }
  }
  
  @media (max-width: 280px) {
    .travel-hero { height: 220px; }
    .hero-content h1 { font-size: 16px; }
    .hero-content p { font-size: 11px; }
    .booking-bar { padding: 6px; }
    .booking-input { padding: 6px; font-size: 11px; }
    .booking-btn { padding: 8px; font-size: 12px; }
    .stay-slider { display: none; }
    .slider-button { display: none; }
    .hotel-card img { height: 110px; }
    .hotel-info { padding: 8px; }
    .hotel-name { font-size: 13px; }
  }
</style>

<body>

  <!-- =============== TRAVEL HERO BANNER =============== -->
  <div class="travel-hero">
    <div class="hero-content">
      <h1>Find Your Next Getaway</h1>
      <p>From cozy cottages to luxury resorts, discover places to stay.</p>
      <form action="{{ route('hotels_resorts') }}" method="GET" class="booking-bar">
        <input type="text" name="location" class="booking-input" placeholder="&#128205; Where are you going?" value="{{ request('location') }}">
        <input type="date" name="checkin" class="booking-input" placeholder="&#128197; Check-in" value="{{ request('checkin') }}" min="{{ date('Y-m-d') }}">
        <input type="date" name="checkout" class="booking-input" placeholder="&#128197; Check-out" value="{{ request('checkout') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
        <input type="number" name="guests" class="booking-input" placeholder="&#128101; Guests" min="1" max="10" value="{{ request('guests') }}">
        <button type="submit" class="booking-btn">Search</button>
      </form>
    </div>
  </div>

  <!-- =============== TOP BANNER (only shown if hotels_banner is set and has products) =============== -->
  @if(!empty($hotels_banner) && !empty($hotels_banner->products))
  <div class="benefits-section">
    <div class="benefits-grid">
      @foreach($hotels_banner->products as $product)
      <div class="benefit-card">
        <a href="{{ url('hotel-details/'.$product->id) }}" class="product">
          <div class="banner-img">
            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
          </div>
          <div class="text">
            <h4>{{ $product->desc }}</h4>
            <a href="{{ url('hotel-details/'.$product->id) }}">Book now &rarr;</a>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- =============== FILTER SECTION =============== -->
  <div class="filter-section">
    <form action="{{ route('hotels_resorts') }}" method="GET" id="filterForm" class="filter-card">
      <h3><span>&#9776;</span> Filter Properties</h3>

      <!-- Preserve hero search params -->
      @if(request('checkin'))
      <input type="hidden" name="checkin" value="{{ request('checkin') }}">
      @endif
      @if(request('checkout'))
      <input type="hidden" name="checkout" value="{{ request('checkout') }}">
      @endif
      @if(request('guests'))
      <input type="hidden" name="guests" value="{{ request('guests') }}">
      @endif

      <div class="filter-row">
        <div class="filter-group">
          <label>Property Type</label>
          <select class="filter-select" id="filterType" name="property_type">
            <option value="">All Types</option>
            <option value="resort" {{ request('property_type') == 'resort' ? 'selected' : '' }}>Resort</option>
            <option value="hotel" {{ request('property_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
            <option value="villa" {{ request('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
            <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
            <option value="spa" {{ request('property_type') == 'spa' ? 'selected' : '' }}>Spa &amp; Wellness</option>
            <option value="apart-hotel" {{ request('property_type') == 'apart-hotel' ? 'selected' : '' }}>Apart Hotel</option>
          </select>
        </div>

        <div class="filter-group">
          <label>Location</label>
          <input type="text" class="filter-input" id="filterLocation" name="location" placeholder="e.g. Goa, Mumbai..." value="{{ request('location') }}" list="locationList">
          <datalist id="locationList">
            @if(isset($locations))
              @foreach($locations as $loc)
                <option value="{{ $loc }}">
              @endforeach
            @endif
          </datalist>
        </div>

        <div class="filter-group">
          <label>Sort By</label>
          <select class="filter-select" id="filterSort" name="sort">
            <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Recommended</option>
            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
          </select>
        </div>

        <div class="filter-group">
          <label>Star Rating</label>
          <select class="filter-select" id="filterRating" name="rating">
            <option value="">Any Rating</option>
            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>&#11088;&#11088;&#11088;&#11088;&#11088; 5 Stars</option>
            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>&#11088;&#11088;&#11088;&#11088; 4+ Stars</option>
            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>&#11088;&#11088;&#11088; 3+ Stars</option>
          </select>
        </div>

        <div class="filter-actions">
          <a href="{{ route('hotels_resorts') }}" class="btn-reset">Reset</a>
          <button type="submit" class="btn-apply">Apply Filters</button>
        </div>
      </div>

      <div class="price-range-row">
        <label>Price Range (per night):</label>
        <input type="range" id="priceRange" name="max_price" min="500" max="50000" value="{{ request('max_price', 50000) }}" step="500"
          oninput="document.getElementById('priceDisplay').textContent = '&#8377;500 \u2013 &#8377;' + parseInt(this.value).toLocaleString('en-IN')">
        <span class="price-display" id="priceDisplay">&#8377;500 &ndash; &#8377;{{ number_format(request('max_price', 50000)) }}</span>
      </div>

      <div class="filter-amenities">
        <label style="font-size:12px; font-weight:600; color:var(--text-light); text-transform:uppercase; letter-spacing:0.5px; width:100%; margin-bottom:4px;">Amenities</label>
        @php $selectedAmenities = request('amenities', []); @endphp
        <label class="amenity-chip {{ in_array('WiFi', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="WiFi" {{ in_array('WiFi', $selectedAmenities) ? 'checked' : '' }}> WiFi</label>
        <label class="amenity-chip {{ in_array('Swimming Pool', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="Swimming Pool" {{ in_array('Swimming Pool', $selectedAmenities) ? 'checked' : '' }}> Swimming Pool</label>
        <label class="amenity-chip {{ in_array('Spa', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="Spa" {{ in_array('Spa', $selectedAmenities) ? 'checked' : '' }}> Spa</label>
        <label class="amenity-chip {{ in_array('Gym', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="Gym" {{ in_array('Gym', $selectedAmenities) ? 'checked' : '' }}> Gym</label>
        <label class="amenity-chip {{ in_array('Free Parking', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="Free Parking" {{ in_array('Free Parking', $selectedAmenities) ? 'checked' : '' }}> Free Parking</label>
        <label class="amenity-chip {{ in_array('Breakfast Included', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="Breakfast Included" {{ in_array('Breakfast Included', $selectedAmenities) ? 'checked' : '' }}> Breakfast Included</label>
        <label class="amenity-chip {{ in_array('AC', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="AC" {{ in_array('AC', $selectedAmenities) ? 'checked' : '' }}> Air Conditioning</label>
        <label class="amenity-chip {{ in_array('Pet Friendly', $selectedAmenities) ? 'checked' : '' }}"><input type="checkbox" name="amenities[]" value="Pet Friendly" {{ in_array('Pet Friendly', $selectedAmenities) ? 'checked' : '' }}> Pet Friendly</label>
      </div>

      <div style="margin-top:18px; padding-top:18px; border-top:1px solid #f0f0f0;">
        <label style="font-size:12px; font-weight:600; color:var(--text-light); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:10px;">Quick Filter</label>
        <div class="filter-tags">
          <button type="submit" name="tag" value="all" class="filter-tag {{ request('tag', 'all') == 'all' ? 'active' : '' }}">All</button>
          <button type="submit" name="tag" value="luxury" class="filter-tag {{ request('tag') == 'luxury' ? 'active' : '' }}">&#10024; Luxury</button>
          <button type="submit" name="tag" value="beach" class="filter-tag {{ request('tag') == 'beach' ? 'active' : '' }}">&#127944; Beach</button>
          <button type="submit" name="tag" value="mountain" class="filter-tag {{ request('tag') == 'mountain' ? 'active' : '' }}">&#127956; Mountain</button>
          <button type="submit" name="tag" value="budget" class="filter-tag {{ request('tag') == 'budget' ? 'active' : '' }}">&#128176; Budget Stays</button>
          <button type="submit" name="tag" value="family" class="filter-tag {{ request('tag') == 'family' ? 'active' : '' }}">&#128106; Family</button>
          <button type="submit" name="tag" value="honeymoon" class="filter-tag {{ request('tag') == 'honeymoon' ? 'active' : '' }}">&#10084; Honeymoon</button>
          <button type="submit" name="tag" value="business" class="filter-tag {{ request('tag') == 'business' ? 'active' : '' }}">&#128188; Business</button>
        </div>
      </div>

    </form>
  </div>

  <!-- =============== DISCOVER STAYS SLIDER =============== -->
  <div class="stay-section">
    <h2>Discover your new favourite stay 
      @if(request()->hasAny(['location', 'property_type', 'rating', 'amenities', 'tag']))
        <span style="font-size: 14px; color: var(--text-light); font-weight: 400;">({{ $hotels->total() }} results found)</span>
      @endif
    </h2>
    
    <!-- Grid View for filtered results -->
    <div class="hotels-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
      @if(!empty($hotels) && count($hotels) > 0)
        @foreach($hotels as $hotel)
        <div class="hotel-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;">
          <a href="{{ url('hotel-details/'.$hotel->id) }}" style="text-decoration: none; color: inherit;">
            @php
              $imgName = trim($hotel->image ?? '');
              $imgPath = public_path('uploads/hotels/' . $imgName);
              $imgAsset = file_exists($imgPath) && $imgName ? asset('uploads/hotels/' . $imgName) : asset('uploads/not_found.jpg');
            @endphp
            <div style="position: relative; height: 200px; overflow: hidden;">
              <img src="{{ $imgAsset }}" alt="{{ $hotel->name }}" style="width: 100%; height: 100%; object-fit: cover;">
              @if($hotel->property_type)
              <span style="position: absolute; top: 12px; left: 12px; background: var(--wk-blue); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase;">{{ $hotel->property_type }}</span>
              @endif
              @if($hotel->rating)
              <span style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.7); color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">⭐ {{ number_format($hotel->rating, 1) }}</span>
              @endif
            </div>
            <div style="padding: 16px;">
              <h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: var(--text-main);">{{ $hotel->name }}</h3>
              <p style="margin: 0 0 10px; font-size: 13px; color: var(--text-light);">
                <span style="margin-right: 4px;">📍</span>{{ $hotel->location }}
              </p>
              @if($hotel->amenities && is_array($hotel->amenities) && count($hotel->amenities) > 0)
              @php
                $amenityLabels = [
                    'wifi' => 'Free WiFi',
                    'parking' => 'Free Parking',
                    'pool' => 'Swimming Pool',
                    'gym' => 'Gym',
                    'spa' => 'Spa',
                    'restaurant' => 'Restaurant',
                    'bar' => 'Bar',
                    'room_service' => '24/7 Room Service',
                    'ac' => 'AC',
                    'tv' => 'TV',
                    'laundry' => 'Laundry',
                    'airport_shuttle' => 'Airport Shuttle',
                    'business_center' => 'Business Center',
                    'conference_room' => 'Conference Room',
                    'pet_friendly' => 'Pet Friendly',
                    'kids_area' => 'Kids Area',
                ];
              @endphp
              <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px;">
                @foreach(array_slice($hotel->amenities, 0, 3) as $amenity)
                <span style="background: var(--wk-light-green); color: var(--wk-dark-green); padding: 4px 10px; border-radius: 12px; font-size: 11px;">{{ $amenityLabels[$amenity] ?? ucfirst(str_replace('_', ' ', $amenity)) }}</span>
                @endforeach
                @if(count($hotel->amenities) > 3)
                <span style="color: var(--text-light); font-size: 11px; padding: 4px;">+{{ count($hotel->amenities) - 3 }} more</span>
                @endif
              </div>
              @endif
              <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #f0f0f0;">
                <div>
                  @php
                    $minPrice = $hotel->min_price ?? $hotel->rooms()->min('price') ?? 0;
                  @endphp
                  @if($minPrice > 0)
                  <span style="font-size: 11px; color: var(--text-light);">Starting from</span>
                  <p style="margin: 0; font-size: 18px; font-weight: 700; color: var(--wk-blue);">₹{{ number_format($minPrice) }}<span style="font-size: 12px; font-weight: 400; color: var(--text-light);">/night</span></p>
                  @endif
                </div>
                <span style="background: var(--wk-blue); color: white; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;">View Details</span>
              </div>
            </div>
          </a>
        </div>
        @endforeach
      @else
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
          <div style="font-size: 60px; margin-bottom: 20px;">🏨</div>
          <h3 style="color: var(--text-main); margin-bottom: 10px;">No hotels found</h3>
          <p style="color: var(--text-light);">Try adjusting your filters or search criteria</p>
          <a href="{{ route('hotels_resorts') }}" style="display: inline-block; margin-top: 20px; background: var(--wk-blue); color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600;">Clear All Filters</a>
        </div>
      @endif
    </div>

    <!-- Pagination -->
    @if($hotels instanceof \Illuminate\Pagination\LengthAwarePaginator && $hotels->hasPages())
    <div style="display: flex; justify-content: center; margin-top: 30px;">
      {{ $hotels->links('pagination::bootstrap-4') }}
    </div>
    @endif
  </div>

</body>

<script>
  // Toggle amenity chip styling
  document.querySelectorAll('.amenity-chip input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
      this.closest('.amenity-chip').classList.toggle('checked', this.checked);
    });
  });

  // Hotel card hover effect
  document.querySelectorAll('.hotel-card').forEach(function(card) {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-8px)';
      this.style.boxShadow = '0 15px 40px rgba(0,0,0,0.15)';
    });
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 5px 20px rgba(0,0,0,0.08)';
    });
  });

  // Validate check-in/check-out dates
  const checkinInput = document.querySelector('input[name="checkin"]');
  const checkoutInput = document.querySelector('input[name="checkout"]');
  
  if (checkinInput && checkoutInput) {
    checkinInput.addEventListener('change', function() {
      if (this.value) {
        const nextDay = new Date(this.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkoutInput.min = nextDay.toISOString().split('T')[0];
        if (checkoutInput.value && checkoutInput.value <= this.value) {
          checkoutInput.value = nextDay.toISOString().split('T')[0];
        }
      }
    });
  }
</script>

@endsection