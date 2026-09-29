@extends('front.layouts.app')

@section('content')

<style>
    /* ===================================================
       PRODUCT PAGE SPECIFIC STYLES
       (Global Header/Footer styles are inherited from app.blade.php)
    =================================================== */

    /* BREADCRUMB */
    .breadcrumb-section {
        background: white;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 0;
        margin-bottom: 30px;
    }

    .breadcrumb-inner {
        max-width: 1350px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-light);
        flex-wrap: wrap;
    }

    .breadcrumb-inner a { color: var(--wk-green); font-weight: 500; }
    .breadcrumb-inner a:hover { color: var(--wk-dark-green); }
    .breadcrumb-inner .sep { color: #ccc; }
    .breadcrumb-inner .current { color: var(--text-main); font-weight: 600; }

    /* PRODUCT WRAPPER */
    .product-page {
        max-width: 1350px;
        margin: 0 auto 60px;
        padding: 0 24px;
    }

    /* MAIN PRODUCT CARD */
    .product-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 28px rgba(0,0,0,.08);
        border: 1px solid #eee;
        overflow: hidden;
        display: grid;
        grid-template-columns: 440px 1fr;
    }

    @media (max-width: 900px) {
        .product-card { grid-template-columns: 1fr; }
    }

    /* LEFT: Image Panel */
    .product-image-panel {
        background: linear-gradient(145deg, #f9fbe7, #f1f8e9);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 30px;
        position: relative;
        border-right: 1px solid #f0f0f0;
        gap: 16px;
    }

    .product-badge-sale {
        position: absolute; top: 20px; left: 20px;
        background: var(--wk-pink); color: white;
        font-size: 11px; font-weight: 700; padding: 5px 12px;
        border-radius: 6px; letter-spacing: .5px; text-transform: uppercase;
    }

    .product-badge-new {
        position: absolute; top: 20px; right: 20px;
        background: var(--wk-green); color: white;
        font-size: 11px; font-weight: 700; padding: 5px 12px;
        border-radius: 6px; letter-spacing: .5px; text-transform: uppercase;
    }

    .main-product-img {
        width: 280px; height: 280px; object-fit: contain;
        border-radius: 16px; transition: transform .4s ease;
        filter: drop-shadow(0 10px 24px rgba(0,0,0,.12));
    }

    .main-product-img:hover { transform: scale(1.06); }

    /* Thumbnails */
    .thumb-row { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
    .thumb-img {
        width: 56px; height: 56px; object-fit: cover;
        border-radius: 10px; border: 2px solid #e0e0e0;
        cursor: pointer; transition: .2s;
    }
    .thumb-img:hover, .thumb-img.active {
        border-color: var(--wk-green); box-shadow: 0 0 0 3px rgba(67,160,71,.15);
    }

    /* RIGHT: Info Panel */
    .product-info-panel { padding: 40px 40px; display: flex; flex-direction: column; gap: 18px; }

    .product-category-tag {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; font-weight: 600; color: var(--wk-green);
        background: var(--wk-light-green); padding: 4px 12px;
        border-radius: 20px; text-transform: uppercase; letter-spacing: .8px; width: fit-content;
    }

    .product-name { font-size: 26px; font-weight: 800; color: var(--text-main); line-height: 1.25; }

    .rating-row { display: flex; align-items: center; gap: 12px; }
    .stars { color: #fdd835; font-size: 15px; letter-spacing: 1px; }
    .rating-count { font-size: 13px; color: var(--text-light); font-weight: 500; }
    .in-stock {
        font-size: 12px; font-weight: 700; color: var(--wk-green);
        background: var(--wk-light-green); padding: 3px 10px; border-radius: 4px;
    }

    /* Price block */
    .price-block { display: flex; align-items: baseline; gap: 14px; flex-wrap: wrap; }
    .price-current { font-size: 34px; font-weight: 800; color: var(--text-main); }
    .price-original { font-size: 18px; font-weight: 500; color: #bdbdbd; text-decoration: line-through; }
    .price-discount {
        font-size: 14px; font-weight: 700; color: var(--wk-pink);
        background: #fce4ec; padding: 3px 10px; border-radius: 6px;
    }

    .info-sep { height: 1px; background: #f0f0f0; border: none; }
    .product-desc { font-size: 14px; color: #555; line-height: 1.75; }

    /* Features */
    .feature-list { list-style: none; display: flex; flex-direction: column; gap: 8px; }
    .feature-list li { display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: #444; }
    .feature-list li::before {
        content: '✓'; display: inline-flex; align-items: center; justify-content: center;
        width: 20px; height: 20px; background: var(--wk-light-green);
        color: var(--wk-green); border-radius: 50%; font-size: 11px; font-weight: 800; flex-shrink: 0;
    }

    /* Delivery */
    .delivery-strip {
        background: #fff8e1; border: 1px solid #ffe082;
        border-radius: 12px; padding: 14px 18px;
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    }
    .delivery-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #555; font-weight: 500; }
    .delivery-item i { color: var(--wk-green); font-size: 16px; }

    /* CTA Buttons */
    .cta-row { display: flex; gap: 14px; flex-wrap: wrap; }
    .btn-add-cart {
        flex: 1; min-width: 160px; padding: 14px 20px;
        background: var(--wk-blue); color: white; border: none;
        border-radius: 12px; font-size: 15px; font-weight: 700;
        cursor: pointer; transition: .3s; display: flex;
        align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 14px rgba(30,136,229,.35);
    }
        .btn-buy-now {
            flex: 1; min-width: 160px; padding: 14px 20px;
            background: var(--wk-green); color: white; border: none;
            border-radius: 12px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: .3s; display: flex;
            align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 14px rgba(56,142,60,.35);
        }
        .btn-buy-now:hover {
            background: #388e3c; transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(56,142,60,.4);
        }
    .btn-add-cart:hover {
        background: #1565c0; transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30,136,229,.4);
    }

    /* Seller info */
    .seller-card {
        background: #fafafa; border: 1px solid #eee;
        border-radius: 12px; padding: 16px 18px;
        display: flex; align-items: center; gap: 14px;
    }
    .seller-avatar {
        width: 46px; height: 46px; background: var(--wk-light-green);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .seller-info p { margin: 0; }
    .seller-info .seller-label { font-size: 11px; color: var(--text-light); text-transform: uppercase; letter-spacing: .8px; font-weight: 600; }
    .seller-info .seller-name { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .seller-info .seller-loc { font-size: 12px; color: var(--text-light); }

    /* TABS SECTION */
    .tabs-section {
        margin-top: 36px; background: white; border-radius: 20px;
        border: 1px solid #eee; box-shadow: 0 4px 20px rgba(0,0,0,.05);
        overflow: hidden;
    }
    .tabs-nav { display: flex; border-bottom: 2px solid #f0f0f0; overflow-x: auto; }
    .tab-btn {
        padding: 16px 28px; font-size: 14px; font-weight: 600;
        color: var(--text-light); border: none; background: transparent;
        cursor: pointer; border-bottom: 3px solid transparent;
        margin-bottom: -2px; white-space: nowrap; transition: .2s;
    }
    .tab-btn:hover { color: var(--wk-green); }
    .tab-btn.active { color: var(--wk-green); border-bottom-color: var(--wk-green); }

    .tab-content-panel { padding: 32px; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }
    .tab-pane p { font-size: 14px; color: #555; line-height: 1.8; }

    /* Specs Table */
    .specs-table { width: 100%; border-collapse: collapse; }
    .specs-table tr { border-bottom: 1px solid #f5f5f5; }
    .specs-table td { padding: 12px 16px; font-size: 14px; }
    .specs-table td:first-child { color: var(--text-light); font-weight: 600; width: 38%; background: #fafafa; }
    .specs-table td:last-child { color: var(--text-main); }

    /* Reviews */
    .review-card {
        background: #fafafa; border: 1px solid #eee;
        border-radius: 12px; padding: 18px 20px; margin-bottom: 16px;
    }
    .review-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .reviewer-name { font-weight: 700; font-size: 14px; color: var(--text-main); }
    .reviewer-date { font-size: 12px; color: var(--text-light); margin-top: 2px; }
    .review-stars { color: #fdd835; font-size: 14px; }
    .review-text { font-size: 14px; color: #555; line-height: 1.65; }

    @media (max-width: 900px) {
        .product-info-panel { padding: 28px 22px; }
        .product-image-panel { padding: 30px 20px; }
        .main-product-img { width: 220px; height: 220px; }
    }

    @media (max-width: 768px) {
        .product-page { padding: 0 14px; margin-top: 20px; }
        .product-name { font-size: 20px; }
        .price-current { font-size: 26px; }
        .cta-row { flex-direction: column; gap: 10px; }
        .btn-add-cart, .btn-buy-now { width: 100%; min-width: 100%; min-height: 48px; }
    }

    @media (max-width: 576px) {
        .product-page { padding: 0 10px; margin-top: 15px; }
        .product-card { border-radius: 14px; }
        .product-image-panel { padding: 20px 15px; }
        .product-info-panel { padding: 22px 15px; }
        .main-product-img { width: 180px; height: 180px; }
        .product-name { font-size: 18px; }
        .price-current { font-size: 24px; }
        .trust-item { font-size: 12px; }
        .delivery-strip { padding: 12px; gap: 10px; }
        .delivery-item { font-size: 12px; gap: 5px; }
        .seller-card { padding: 12px; }
        .tabs-nav { overflow-x: auto; }
        .tab-btn { padding: 12px 18px; font-size: 13px; }
        .tab-content-panel { padding: 20px 15px; }
    }

    @media (max-width: 400px) {
        .product-page { padding: 0 8px; margin-top: 10px; }
        .product-image-panel { padding: 15px 10px; }
        .product-info-panel { padding: 18px 12px; }
        .main-product-img { width: 150px; height: 150px; }
        .product-name { font-size: 16px; }
        .price-current { font-size: 22px; }
        .cta-row { gap: 8px; }
        .btn-add-cart, .btn-buy-now { padding: 12px 16px; font-size: 14px; border-radius: 10px; }
    }
    
    @media (max-width: 359px) {
        .product-page { padding: 0 6px; margin-top: 8px; }
        .product-card { border-radius: 10px; }
        .product-image-panel { padding: 12px 8px; }
        .product-info-panel { padding: 14px 10px; }
        .main-product-img { width: 120px; height: 120px; }
        .thumb-row { gap: 6px; }
        .thumb-img { width: 45px; height: 45px; border-radius: 6px; }
        .product-name { font-size: 14px; line-height: 1.3; }
        .price-current { font-size: 20px; }
        .price-original { font-size: 13px; }
        .discount-tag { font-size: 10px; padding: 3px 6px; }
        .trust-row { gap: 8px; flex-wrap: wrap; }
        .trust-item { font-size: 11px; }
        .delivery-strip { padding: 10px; gap: 8px; flex-wrap: wrap; }
        .delivery-item { font-size: 11px; }
        .qty-box { gap: 8px; }
        .qty-btn { width: 32px; height: 32px; font-size: 14px; }
        .qty-input { width: 45px; padding: 6px; font-size: 14px; }
        .btn-add-cart, .btn-buy-now { padding: 10px 14px; font-size: 13px; border-radius: 8px; min-height: 44px; }
        .seller-card { padding: 10px; border-radius: 8px; }
        .seller-name { font-size: 13px; }
        .seller-details span { font-size: 11px; }
        .tabs-nav { gap: 0; }
        .tab-btn { padding: 10px 12px; font-size: 12px; }
        .tab-content-panel { padding: 14px 10px; }
        .spec-row span:first-child { font-size: 12px; min-width: 90px; }
        .spec-row span:last-child { font-size: 12px; }
        .review-card { padding: 12px; }
        .reviewer-name { font-size: 13px; }
        .review-text { font-size: 13px; }
    }
    
    @media (max-width: 280px) {
        .product-page { padding: 0 4px; }
        .main-product-img { width: 100px; height: 100px; }
        .thumb-row { display: none; }
        .product-name { font-size: 13px; }
        .price-current { font-size: 18px; }
        .cta-row { flex-direction: column; }
        .btn-add-cart, .btn-buy-now { width: 100%; font-size: 12px; padding: 10px; }
        .tab-btn { padding: 8px 10px; font-size: 11px; }
    }
</style>

<!-- ======================== BREADCRUMB ======================== -->
<div class="breadcrumb-section">
    <div class="breadcrumb-inner">
        <a href="{{ url('/') }}">Home</a>
        <span class="sep">&#8250;</span>
        <a href="#">Products</a>
        <span class="sep">&#8250;</span>
        <span class="current">{{ $product->name ?? 'Product Detail' }}</span>
    </div>
</div>

<!-- ======================== PRODUCT PAGE CONTENT ======================== -->
<div class="product-page">

    <!-- Main Product Card -->
    <div class="product-card">

        <!-- LEFT: Image Panel -->
        <div class="product-image-panel">
            @if(isset($product->total_price) && isset($product->offer_price) && $product->total_price > $product->offer_price)
                <span class="product-badge-sale">Sale</span>
            @endif
            <span class="product-badge-new">&#10024; New</span>

            @php
                $images = $product->productImages && count($product->productImages) ? $product->productImages : collect([(object)['image' => $product->image]]);
            @endphp
            <img class="main-product-img" src="{{ asset('uploads/products/' . $images[0]->image) }}" alt="{{ $product->name }}" id="main-img">

            <!-- Thumbnail row -->
            <div class="thumb-row">
                @foreach($images as $idx => $img)
                    <img class="thumb-img{{ $idx === 0 ? ' active' : '' }}" src="{{ asset('uploads/products/' . $img->image) }}" alt="View {{ $idx+1 }}" onclick="document.getElementById('main-img').src=this.src; document.querySelectorAll('.thumb-img').forEach(t=>t.classList.remove('active')); this.classList.add('active');">
                @endforeach
            </div>
        </div>

        <!-- RIGHT: Info Panel -->
        <div class="product-info-panel">

            <!-- Category Tag -->
            <span class="product-category-tag">
                &#127978; WinkelKart Product
            </span>

            <!-- Product Name -->
            <h2 class="product-name">{{ $product->name ?? 'Sample Product Name' }}</h2>

            <!-- Rating Row -->
            <div class="rating-row">
                <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                <span class="rating-count">4.0 / 5.0 &bull; reviews</span>
                @if(isset($product->quantity) && $product->quantity <= 0)
                <span class="out-of-stock" style="color:#dc3545;font-weight:600;">&#10007; Not Available</span>
                @else
                <span class="in-stock">&#10003; In Stock</span>
                @endif
            </div>

            <hr class="info-sep">

            <!-- Price Block -->
            <div class="price-block">
                <span class="price-current">&#8377;{{ number_format($product->final_price, 2) }}</span>
                @if(isset($product->total_price) && isset($product->final_price) && $product->total_price > $product->final_price)
                    <span class="price-original">&#8377;{{ number_format($product->total_price, 2) }}</span>
                    <span class="price-discount">-{{ round((($product->total_price - $product->final_price) / $product->total_price) * 100) }}% OFF</span>
                @endif
                <span class="ml-3 badge badge-success">Final Price</span>
            </div>

            <!-- Description -->
            @if(!empty($product->desc))
            <p class="product-desc">{{ $product->desc }}</p>
            @endif

            <!-- Feature bullets -->
            <ul class="feature-list">
                <li>Genuine product — verified by WinkelKart</li>
                <li>Easy 7-day returns &amp; exchange policy</li>
                <li>Secure checkout &amp; encrypted payment</li>
                <li>Trusted seller with verified ratings</li>
            </ul>

            <hr class="info-sep">

            <!-- Delivery Strip -->
            <div class="delivery-strip">
                <div class="delivery-item">
                    <i class="fas fa-truck"></i>
                    <span>Free delivery above ₹499</span>
                </div>
                <div class="delivery-item">
                    <i class="fas fa-undo"></i>
                    <span>7-day returns</span>
                </div>
                <div class="delivery-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>{{ !empty($product->warranty) ? $product->warranty : '1-year warranty' }}</span>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="cta-row">
                @if(isset($product->quantity) && $product->quantity <= 0)
                    <button type="button" class="btn-add-cart" disabled style="opacity:0.5;cursor:not-allowed;">
                        <i class="bi bi-cart-plus"></i> Out of Stock
                    </button>
                @else
                <form action="{{ url('cart_add', $product->id ?? 1) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn-add-cart">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
                    <form action="{{ url('buy_now', $product->id ?? 1) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn-buy-now">
                            <i class="bi bi-bag-check"></i> Buy Now
                        </button>
                    </form>
                @endif
            </div>

            <hr class="info-sep">

            <!-- Seller Card -->
            <div class="seller-card">
                <div class="seller-avatar">&#127978;</div>
                <div class="seller-info">
                    <p class="seller-label">Sold by</p>
                    <p class="seller-name">
                        @if($product->seller)
                            {{ $product->seller->name }}
                        @else
                            {{ $product->company ?? 'WinkelKart Store' }}
                        @endif
                    </p>
                    <p class="seller-loc">&#128204; Verified WinkelKart Seller</p>
                </div>
            </div>

        </div><!-- /product-info-panel -->
    </div><!-- /product-card -->

    <!-- ======================== TABS ======================== -->
    <div class="tabs-section">
        <div class="tabs-nav">
            <button class="tab-btn active" onclick="showTab('desc', this)">Description</button>
            <button class="tab-btn" onclick="showTab('specs', this)">Specifications</button>
            <button class="tab-btn" onclick="showTab('reviews', this)">Reviews</button>
        </div>
        <div class="tab-content-panel">

            <!-- Description Tab -->
            <div class="tab-pane active" id="tab-desc">
                <p>{{ $product->desc ?? 'No description available for this product.' }}</p>
                <br>
                <p>WinkelKart ensures every listed product undergoes quality checks before being available for purchase. Shop with confidence knowing your order is backed by our buyer protection policy.</p>
            </div>

            <!-- Specs Tab -->
            <div class="tab-pane" id="tab-specs">
                <table class="specs-table">
                    <tr><td>Product Name</td><td>{{ $product->name ?? '—' }}</td></tr>
                    <tr><td>Brand / Store</td><td>{{ $product->company ?? '—' }}</td></tr>
                    <tr><td>MRP</td><td>&#8377;{{ number_format($product->total_price ?? 0, 2) }}</td></tr>
                    <tr><td>Final Price</td><td>&#8377;{{ number_format($product->final_price ?? 0, 2) }}</td></tr>
                    <tr><td>Availability</td><td>@if(isset($product->quantity) && $product->quantity <= 0)<span style="color:#dc3545;font-weight:600;">Not Available</span>@else<span style="color:#28a745;font-weight:600;">In Stock</span>@endif</td></tr>
                    <tr><td>Delivery</td><td>Free above ₹499</td></tr>
                    <tr><td>Return Policy</td><td>7-day returns</td></tr>
                    <tr><td>Warranty</td><td>{{ !empty($product->warranty) ? $product->warranty : '—' }}</td></tr>
                </table>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane" id="tab-reviews">
                @auth
                <div class="mb-4">
                    <form action="{{ url('product/' . $product->id . '/review') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="rating">Your Rating:</label>
                            <select name="rating" id="rating" class="form-control" required style="max-width:120px;display:inline-block;">
                                <option value="">Select</option>
                                @for($i=5;$i>=1;$i--)
                                    <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Your Review:</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" maxlength="1000" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Submit Review</button>
                    </form>
                </div>
                @else
                <div class="mb-4 p-3" style="background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <p style="margin-bottom: 10px; color: #555;">Want to share your experience with this product?</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Login to Write a Review</a>
                </div>
                @endauth
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @forelse($product->reviews as $review)
                    <div class="review-card">
                        <div class="review-top">
                            <div>
                                <div class="reviewer-name">{{ $review->user->name ?? 'Anonymous' }}</div>
                                <div class="reviewer-date">{{ $review->created_at->format('F Y') }}</div>
                            </div>
                            <span class="review-stars">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $review->rating)
                                        &#9733;
                                    @else
                                        &#9734;
                                    @endif
                                @endfor
                            </span>
                        </div>
                        <p class="review-text">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p>No reviews yet for this product.</p>
                @endforelse
            </div>

        </div>
    </div><!-- /tabs-section -->

</div><!-- /product-page -->

<script>
    // Tab switching logic specific to this page
    function showTab(id, btn) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + id).classList.add('active');
        btn.classList.add('active');
    }
</script>

@endsection