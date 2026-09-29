@extends('front.layouts.app')
@section('content')

  <style>
    /* Mobile Footer Styling */
    @media (max-width: 768px) {
      .footer-nav .container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px 15px;
        background-color: #1c1c1c;
      }

      .footer-nav-list {
        border-bottom: 1px solid #333;
        padding: 10px 0;
      }

      .nav-title {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        display: flex;
        justify-content: space-between;
        cursor: pointer;
      }

      .footer-links {
        display: none;
        margin-top: 8px;
      }

      .footer-links a {
        display: block;
        font-size: 14px;
        color: #bbb;
        text-decoration: none;
        margin-bottom: 6px;
      }

      .footer-links a:hover {
        color: #ff9500;
      }

      /* Active (Expanded) */
      .footer-nav-list.active .footer-links {
        display: block;
      }

      /* Footer Bottom */
      .footer-bottom {
        background-color: #141414;
        text-align: center;
        padding: 15px;
        border-top: 1px solid #333;
      }

      .footer-bottom .payment-img {
        max-width: 200px;
        margin-bottom: 10px;
      }

      .footer-bottom .copyright {
        font-size: 13px;
        color: #bbb;
      }

      .footer-bottom .copyright a {
        color: #ff9500;
        text-decoration: none;
      }
    }

    /* MOBILE NAV BTNS CSS*/

    .mobile-menu {
      display: none;
      /* Initially hidden */
      flex-direction: column;
      background: #222;
      padding: 1rem;
    }

    .mobile-menu.show {
      display: flex;
      /* Shown when "show" class is added */
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }

    a {
      text-decoration: none;
    }

    /* ========= TOP BANNER ========= */
    .top-banner {
      background: white;
      color: red;
      text-align: right;
      padding: 0.5rem 2rem;
      font-weight: bold;
      font-size: 0.9rem;
    }

    /* ========= HEADER ========= */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #222;
      color: white;
      padding: 1rem;
      position: relative;
      z-index: 100;
    }

    .header img {
      height: 80px;
    }

    .logo-text {
      font-size: 1.5rem;
      color: #d72b2b;
      margin-top: 0.3rem;
    }

    .search-container {
      display: flex;
      justify-content: center;
      margin-top: 1rem;
    }

    .search-box {
      width: 60%;
      display: flex;
      background: white;
      border: 1px solid #ccc;
      border-radius: 25px;
      overflow: hidden;
    }

    .search-box input {
      flex: 1;
      padding: 0.7rem;
      border: none;
      outline: none;
      font-size: 1rem;
    }

    .search-box button {
      background: white;
      border: none;
      padding: 0 1rem;
      cursor: pointer;
    }

    .menu-category {
      vertical-align: middle;
      font-size: 20px;
    }

    /* ========= NAVIGATION BAR (DESKTOP) ========= */
    nav.desktop-nav {
      background: #222;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      padding: 0.5rem 1rem;
      gap: 1.5rem;
    }

    nav.desktop-nav a {
      color: white;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.95rem;
      transition: color 0.2s ease;
    }

    nav.desktop-nav a:hover {
      color: #4caf50;
    }


    .menu-toggle {
      display: none;
      font-size: 2rem;
      position: absolute;
      top: 0;
      left: 10;
      transform: ;
      cursor: pointer;
      z-index: 9999;
    }


    .mobile-menu {
      display: none;
      flex-direction: column;
      background: transparent;
      color: white;
      padding: 20px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 9999;
    }

    .mobile-menu.show {
      display: flex;
    }



    .mobile-menu nav {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .mobile-menu nav a {
      color: white;
      display: inline-block;
      align-items: center;
      gap: 0.4rem;
      font-size: 1rem;
      padding: 4px 0;
    }


    .main {
      text-align: center;
      padding: 2rem;
    }

    .main h1 {
      font-size: 2.2rem;
      color: #1e3dd3;
    }

    .main h2 {
      font-size: 2.5rem;
      color: #ff6600;
      margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
      nav.desktop-nav {
        display: none;
      }

      .search-container {
        display: none;
      }

      .menu-toggle {
        display: block;
      }
    }

    @media (min-width: 769px) {
      .mobile-menu {
        display: none;
      }
    }

    h2 {
      text-align: center;
      margin: 40px 0 20px;
    }

    .section {
      padding: 10px 5%;
    }




    .product {


      text-align: center;
      transition: transform 0.3s;
      cursor: pointer;
    }

    .product:hover {
      transform: translateY(-5px);
    }

    .product img {
      width: 200px;
      height: 200px;
      object-fit: cover;
    }

    .product h3 {
      font-size: 16px;
      margin: 10px 0;
      color: #333;
    }

    .product p {
      font-size: 14px;
      margin-bottom: 10px;
      color: #666;
    }

    @media (max-width: 600px) {
      .product img {
        height: 150px;
      }
    }


    /*banner1*/
    .banner-section {
      max-width: 1330px;
      margin: 30px auto;
      border-radius: 12px;
      padding: 20px;
      background: transparent;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .banner-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .banner-header h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
    }

    .see-all-btn {
      font-size: 14px;
      font-weight: bold;
      color: #007bff;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .see-all-btn:hover {
      color: #0056b3;
    }

    .slider {
      display: flex;
      overflow-x: auto;
      gap: 15px;
      scroll-behavior: smooth;
      padding-bottom: 10px;
    }

    .slider::-webkit-scrollbar {
      height: 8px;
    }

    .slider::-webkit-scrollbar-thumb {
      background: #ccc;
      border-radius: 10px;
    }

    .product-card {
      min-width: 220px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 15px;
      flex-shrink: 0;
      transition: transform 0.2s ease;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      width: 100%;
      height: 120px;
      object-fit: contain;
      border-radius: 6px;
      margin-bottom: 10px;
      transition: transform 0.4s ease;
    }

    .product-card:hover img {
      transform: scale(1.1);
    }

    .product-title {
      font-size: 14px;
      font-weight: 500;
      height: 40px;
      overflow: hidden;
    }

    .price {
      font-size: 16px;
      font-weight: bold;
      margin-top: 5px;
    }

    .original-price {
      font-size: 12px;
      color: #999;
      text-decoration: line-through;
    }

    /* banner*/

    * {
      box-sizing: border-box;
    }



    .banner-container {
      justify-content: center;
      display: flex;
      align-items: stretch;
      /* ensures equal height */
      padding: 20px;
      background-color: transparent;
      gap: 20px;
    }



    .products-scroll {
      flex: 2;
      display: flex;
      overflow-x: auto;
      gap: 15px;
      background-color: transparent;
      border-radius: 10px;
      padding: 10px;
    }

    .product {
      min-width: 180px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      padding: 10px;
      cursor: pointer;
      flex-shrink: 0;
    }

    .product img {
      width: 100%;
      height: 150px;
      object-fit: contain;
    }

    .product-title {
      font-size: 14px;
      margin-top: 8px;
    }

    .rating {
      font-size: 13px;
      color: #f90;
    }

    .deal {
      font-size: 12px;
      color: red;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .banner-container {
        flex-direction: column;
      }


    }

    .add-btn {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #fff;
      border: 2px solid #ff3e6c;
      color: #ff3e6c;
      font-weight: bold;
      border-radius: 20px;
      cursor: pointer;
      transition: 0.3s;
      text-align: center;
      width: 100%;
    }

    .add-btn:hover {
      background-color: #ff3e6c;
      color: #fff;
    }
  </style>

  
  <div class="banner-section">
    <div class="banner-header">
      <h2>Elevate Yourself</h2>
      <!-- <a href="#" class="see-all-btn">See All</a> -->
    </div>

    <div class="slider">
      @foreach($elevates as $product)
      <div class="product-card">
        <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
          <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
          <div class="product-title">{{ $product->name }}</div>
          <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
        </a>
      </div>
      @endforeach

    </div>
  </div>

    

  <!-- Desktops Section -->
  <section class="section">
    <h2>Desktops</h2>
    <!-- <a href="#" class="see-all-btn">See All</a> -->
    <div class="product-grid">
      @foreach($desktops->products as $product)
      <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
        <h5>{{ $product->name }}</h5>
        <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
        <!-- <div class="add-btn">
          <i class="fab fa-whatsapp"></i>
          <span>BUY</span>
        </div> -->
      </a>
      @endforeach      
    </div>
  </section>


  <!-- Monitor Section -->
  <section class="section">
    <h2>Monitor 19 Inch</h2>
    <!-- <a href="#" class="see-all-btn">See All</a> -->
    <div class="product-grid">
      @foreach($monitors->products as $product)
      <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
        <h5>{{ $product->name }}</h5>
        <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
        <!-- <div class="add-btn">
          <i class="fab fa-whatsapp"></i>
          <span>BUY</span>
        </div> -->
      </a>
      @endforeach      
      
    </div>
  </section>

  <!-- Monitor Section -->
  <section class="section">
    <h2>Mouse</h2>
    <!-- <a href="#" class="see-all-btn">See All</a> -->
    <div class="product-grid">
      @foreach($mouses->products as $product)
      <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
        <h5>{{ $product->name }}</h5>
        <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
        <!-- <div class="add-btn">
          <i class="fab fa-whatsapp"></i>
          <span>BUY</span>
        </div> -->
      </a>
      @endforeach
    </div>
  </section>
    
@endsection