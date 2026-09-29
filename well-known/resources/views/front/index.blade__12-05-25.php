@extends('front.layouts.app')
@section('content')
    <!-- banner -->
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







        /*Best deals banner*/

        body {
            font-family: Arial, sans-serif;
            background: #fef7e0;
            margin: 0;
            padding: 20px;
        }

        .banner {
            text-align: center;
            padding: 20px;
        }

        .banner h1 {
            font-size: 42px;
            color: #ffffff;
            margin-bottom: 30px;
            font-weight: 900;
            background: linear-gradient(to right, #0049ff, #353bcecf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow:
                2px 2px 0 #2c44df,
                4px 4px 0 #000000,
                6px 6px 5px rgba(0, 0, 0, 0.2);
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .banner h1 span {
            display: block;
            font-size: 60px;
            font-weight: 900;
            background: linear-gradient(to right, #ffd700, #ff9500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow:
                2px 2px 0 #cc8400,
                4px 4px 0 #000000,
                6px 6px 5px rgba(0, 0, 0, 0.2);
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card {
            width: 160px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            color: black;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 180px;
            border-radius: 6px;
        }

        .card h3 {
            font-size: 16px;
            margin: 10px 0 5px;
        }

        .old-price {
            text-decoration: line-through;
            color: gray;
            font-size: 14px;
        }

        .new-price {
            color: #d10000;
            font-weight: bold;
            font-size: 16px;
        }

        /* banner no.1 */

        .banner-container {
            max-width: 1330px;
            margin: 50px auto;
            padding: 0 1px;
        }

        .banner1 {
            background: #fff8f0;
            ;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .banner-text {
            max-width: 60%;
        }

        .banner-text h2 {
            font-size: 32px;
            color: #104d1b;
            margin: 0 0 10px 0;
        }

        .banner-text p {
            font-size: 16px;
            color: #1a3320;
            margin-bottom: 20px;
        }

        .buy-btn {
            max-width: 150px;
            background-color: #ffffff;
            color: #0b5e2a;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .buy-btn:hover {

            background-color: #a9d3a9;
        }

        .banner-img img {

            width: 100%;
            height: 200px;
            display: block;
        }

        /*  top deals banner for mobile */
        @media (max-width: 768px) {
            .products {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                gap: 15px;
                padding: 10px;
                scroll-snap-type: x mandatory;
            }

            .products::-webkit-scrollbar {
                display: none;
                /* Hide scrollbar */
            }

            .card {
                flex: 0 0 auto;
                width: 160px;
                scroll-snap-align: start;
            }
        }


        /* banner no. 2 */

        .banner2 {
            max-width: 1330px;
            margin: 30px auto;
            background: #fff8f0;
            border-radius: 12px;
            padding: 20px;
            position: relative;
        }

        .banner-left {
            display: inline-block;
            vertical-align: top;
            width: 150px;
            padding-right: 20px;
        }

        .banner-left h4 {
            margin: 0 0 6px;
            font-size: 14px;
            color: #a05315;
            text-transform: uppercase;
        }

        .banner-left h2 {
            margin: 0 0 12px;
            font-size: 22px;
            color: #c05211;
            line-height: 1.2;
        }

        .banner-left a {
            display: inline-block;
            background: #e96d24;
            color: #fff;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
        }

        .product-slider {
            display: inline-flex;
            overflow-x: auto;
            overflow-y: hidden;
            gap: 16px;
            padding-bottom: 5px;
            width: calc(100% - 170px);
            scroll-behavior: smooth;
        }

        .product-slider::-webkit-scrollbar {
            display: none;
        }

        .product-card {
            flex: 0 0 156px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 8px;
            text-align: center;
            min-width: 210px;
            max-width: 210px;
        }

        .product-card img {
            width: 100%;
            height: 120px;
            object-fit: contain;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .product-name {
            font-size: 13px;
            font-weight: bold;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .price {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .old-price {
            font-size: 12px;
            color: #999;
            text-decoration: line-through;
            margin-left: 4px;
        }

        .discount {
            font-size: 12px;
            color: green;
            margin: 2px 0;
        }

        .rating {
            font-size: 12px;
            color: #555;
        }

        .volume {
            font-size: 11px;
            color: #777;
            margin-bottom: 6px;
        }

        .add-btn {
            font-size: 12px;
            padding: 4px 10px;
            background: #fff;
            color: #e94d2c;
            border: 1px solid #e94d2c;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
        }

        .add-btn:hover {
            background: #ffe4dc;
        }

        /* Navigation Arrows */
        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 28px;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            user-select: none;
        }

        .arrow:hover {
            background: rgba(124, 121, 121, 0.792);
        }

        .arrow-left {
            left: 150px;
        }

        .arrow-right {
            right: 10px;
        }

        /*banner 3*/

        .banner-section {
            max-width: 1330px;
            margin: 30px auto;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            background: #fff8f0;
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

        /* .product-card {
            min-width: 220px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            flex-shrink: 0;
            transition: transform 0.2s ease;
            }
        */
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
    
    <!-- Hot deals banner-->    
    <div class="banner">
        <h1>TOP DEALS OF<br><span>THE WEEK</span></h1>
        <div class="products">
            <!-- Product Card -->             
             @foreach($top_deals->products as $product)
                <a href="{{ url('product/'.$product->id) }}" class="card">
                    <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}">
                    <h3>{{ $product->name }}</h3>
                    <p class="old-price">
                        @if($product->total_price > $product->offer_price)
                            ₹{{ number_format($product->total_price, 2) }}
                        @endif
                    </p>
                    <p class="new-price">₹{{ number_format($product->offer_price, 2) }}</p>
                </a>
            @endforeach
            <!-- <a href="#" class="card">
                <img src="{{ asset ('assets/images/products/mobile1.jpg') }}" alt="Galaxy M36 5G">
                <h3>Galaxy M36 5G</h3>
                <p class="old-price"></p>
                <p class="new-price"></p>
            </a> -->
        </div>
    </div>


    <!-- banner no.1-->
    <!-- <div class="banner-container">
        <div class="banner1">
            <div class="banner-text">
                <h2>Deals Today</h2>
                <p>Get smoking accessories, fresheners & more in just 10 minutes this monsoon with us!</p>
                <a href="#" class="buy-btn">Buy Now</a>
            </div>
            <div class="banner-img">
                <img src="{{ asset('assets/images/Adobe Express - file.png') }}" alt="Products">
            </div>
        </div>
    </div> -->

    <!-- banner no.2 -->
    <div class="banner2">
        <div class="banner-left">
            <h4>SHOPING LOVERS</h4>
            <h2>Dive into the world of fresh brew</h2>
            <a href="#">More Items ></a>
        </div>

        <div class="product-slider" id="slider">
            @foreach($electronics->products as $product)
            <div class="product-card">
                <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
                    <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}">
                    <div class="product-name">{{$product->name}}</div>
                    <div>
                        <h6>{{$product->company}}</h6>
                    </div>
                    <div class="price">₹{{$product->offer_price}} <span class="old-price">₹{{$product->total_price}}</span></div>                    
                    <!-- <div class="add-btn">
                        <i class="fab fa-whatsapp"></i>
                        <span>BUY</span>
                    </div> -->
                </a>
            </div>
            @endforeach            
        </div>
        <div class="arrow arrow-left" onclick="document.getElementById('slider').scrollBy({ left: -200, behavior: 'smooth' });">
            ‹
        </div>
        <div class="arrow arrow-right" onclick="document.getElementById('slider').scrollBy({ left: 200, behavior: 'smooth' });">
            ›
        </div>
    </div>

    <!--banner 3 -->
    <div class="banner-section">
        <div class="banner-header">
            <h2>Computers & More</h2>
            <!-- <a href="computers.html" class="see-all-btn">See All</a> -->
        </div>

        <div class="slider">
            @foreach($computers->products as $product)
            <div class="product-card">
                <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
                    <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}">
                    <h5>{{ $product->name }}</h5>
                    <div>                        
                        <h6>{{$product->company}}</h6>
                    </div>
                    <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
                    <!-- <div class="add-btn">
                        <i class="fab fa-whatsapp"></i>
                        <span>BUY</span>
                    </div> -->
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!--banner 4 -->
    <div class="banner-section">
        <div class="banner-header">
            <h2>Monitors, Keyboard & Mouse</h2>
            <!-- <a href="computers.html" class="see-all-btn">See All</a> -->
        </div>

        <div class="slider">
            @foreach($monitor_mouse as $data)
            @foreach($data->products as $product)
            <div class="product-card">
                <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
                    <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}">
                    <h5>{{ $product->name }}</h5>
                    <div>
                        <h6>{{$product->company}}</h6>
                    </div>
                    <p>₹{{ $product->offer_price }}</p>
                    <!-- <div class="add-btn">
                        <i class="fab fa-whatsapp"></i>
                        <span>BUY</span>
                    </div> -->
                </a>
            </div>
            @endforeach
            @endforeach
            
        </div>
    </div>

    <!--banner 5 -->
    <div class="banner-section">
        <div class="banner-header">
            <h2>Electronics & More </h2>
            <!-- <a href="computers.html" class="see-all-btn">See All</a> -->
        </div>

        <div class="slider">
            @foreach($electronics_more as $data)
            @foreach($data->products as $product)
            <div class="product-card">
                <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                    <div class="product-title">{{ $product->name }}</div>
                    <div>
                        <h6>Store:{{ $product->company }}</h6>
                    </div>
                    <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
                    <!-- <div class="add-btn">
                        <i class="fab fa-whatsapp"></i>
                        <span>BUY</span>
                    </div> -->
                </a>
            </div>
            @endforeach
            @endforeach            
        </div>
    </div>

    <!--banner 6 -->
    <div class="banner-section">
        <div class="banner-header">
            <h2>Discover your new favourite stay<h2>
                <!-- <a href="Hotels&Resorts.html" class="see-all-btn">See All</a> -->
        </div>

        <div class="slider">
            @foreach($hotels_resort->products as $product)
            <div class="product-card">
                <a href="{{ url('product/'.$product->id) }}" target="_blank" class="product">
                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                    <div class="stay-label">{{ $product->name }}</div>
                    <div class="price">₹{{ $product->offer_price }} <span class="original-price">₹{{ $product->total_price }}</span></div>
                    <!-- <div class="add-btn">
                        <i class="fab fa-whatsapp"></i>
                        <span>BUY</span>
                    </div> -->
                </a>
            </div>
            @endforeach
        </div>
    </div>


@endsection