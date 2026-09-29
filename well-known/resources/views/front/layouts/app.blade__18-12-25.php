<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkel - Own Shopperstop</title>


    <!--- favicon -->
    <link rel="shortcut icon" href="./assets/images/logo/Winkel_Shop__2_-removebg-preview.png" type="image/x-ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--- custom css link -->
    <link rel="stylesheet" href="./assets/css/style-prefix.css">

    <!--- google font link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- font awosome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />    
    <style>
        .footer,
        .footer * {
            background-color: #f3e24f !important;
        }
        /* General menu font size */
        .menu-title {
            font-size: 14px !important;  /* adjust as needed */
            font-weight: 500;
        }

        /* For desktop menu */
        .desktop-menu-category-list .menu-title {
            font-size: 15px !important;
        }

        /* For mobile menu (slightly smaller if needed) */
        @media (max-width: 768px) {
            .mobile-menu .menu-title {
                font-size: 13px !important;
            }
        }

    </style>
</head>

<body style="background-color:hsl(57 79.3% 60.5%);">

    <header>

        <marquee width="100%" height="30%">
            <P style="color:red;">FREEDOM SALE UPTO 80% OFF</P>
        </marquee>


        <!-- <div class="header-main" style="background-color:hsl(28 100% 92.9%);" >-->
        <div class="container">

            <a href="{{url('/')}}" class="header-logo">
                <img src="./assets/images/logo/Winkel_Shop__2_-removebg-preview.png" alt="Winkel's logo" width="200" height="200">
            </a>

            <div class="header-search-container">

                <input type="search" name="search" class="search-field" placeholder="Enter your product name...">

                <button class="search-btn">
                    <ion-icon name="search-outline"></ion-icon>
                </button>

            </div>
            
            <nav>
                <div class="mobile-menu" id="mobile-menu">
                    <div class="container">

                        <ul class="desktop-menu-category-list">

                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/all.png') }}')">
                                <a href="{{url('/')}}" class="menu-title">Hot Deals</a>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/dealzone.png') }}')">
                                <a href="{{url('computers')}}" class="menu-title">Computers & Laptops</a>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/elec.png') }}')">
                                <a href="{{url('electronics')}}" class="menu-title">Electronics & Accessories</a>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/babystore.png') }}')">
                                <a href="{{url('groceries')}}" class="menu-title">Groceries</a>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/home.png') }}')">
                                <a href="{{url('hotels-and-resorts')}}" class="menu-title">Hotel and resort</a>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-contact-25.png') }}')">
                                <a href="{{url('contact')}}" class="menu-title">Contact</a>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-registration-50.png') }}')">
                                <a href="{{url('register-buisness')}}" class="menu-title">Register Your Business</a>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-rent-24.png') }}')">
                                <a href="{{url('register-buyer')}}" class="menu-title">Register Buyer</a>
                            </li>
                            {{-- 👇 Conditional Login / Logout --}}
                            @if(Auth::check())                            
                                <li class="menu-category dropdown" style="list-style-image:url('{{ asset('assets/images/icons/icons8-registration-50.png') }}')">
                                    <a href="#" class="menu-title dropdown-toggle" id="mobileAccountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        My Account
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="mobileAccountDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/') }}">{{ Auth::user()->name }}</a>
                                        </li>
                                        <li>
                                            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/dealzone.png') }}')">
                                    <a class="menu-title" href="{{ url('/cart_list') }}">Cart</a>
                                </li>
                            @else
                                <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-login-24.png') }}')">
                                    <a href="{{ url('login') }}" class="menu-title">Login</a>
                                </li>
                            @endif
                            <!-- <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-rent-24.png') }}')">
                                <a href="{{url('login')}}" class="menu-title">Login</a>
                            </li> -->                            
                        </ul>
                    </div>
                </div>
            </nav>


            <div class="menu-toggle" id="menu-toggle">&#9776;</div>
            <nav class="desktop-navigation-menu">

                <div class="container">

                    <ul class="desktop-menu-category-list">
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/all.png') }}')">
                            <a href="{{url('/')}}" class="menu-title">Hot Deals</a>
                        </li>
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/dealzone.png') }}')">
                            <a href="{{url('computers')}}" class="menu-title">Computers & Laptops</a>
                        </li>
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/elec.png') }}')">
                            <a href="{{url('electronics')}}" class="menu-title">Electronics & Accessories</a>
                        </li>
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/babystore.png') }}')">
                            <a href="{{url('groceries')}}" class="menu-title">Groceries</a>
                        </li>
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/home.png') }}')">
                            <a href="{{url('hotels-and-resorts')}}" class="menu-title">Hotel and resort</a>
                        </li>
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-contact-25.png') }}')">
                            <a href="{{url('contact')}}" class="menu-title">Contact</a>
                        </li>
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-registration-50.png') }}')">
                            <a href="{{url('register-buisness')}}" class="menu-title">Register Your Business</a>
                        </li>
                        <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-rent-24.png') }}')">
                            <a href="{{url('register-buyer')}}" class="menu-title">Register Buyer</a>
                        </li>
                        {{-- 👇 Conditional Login / Logout --}}
                        @if(Auth::check())                        
                            <li class="menu-category dropdown" style="list-style-image:url('{{ asset('assets/images/icons/icons8-registration-50.png') }}')">
                                <a href="#" 
                                class="menu-title dropdown-toggle" 
                                id="accountDropdown" 
                                role="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                                    My Account
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/') }}">{{ Auth::user()->name }}</a>
                                    </li>
                                    <li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/dealzone.png') }}')">
                                <a class="menu-title" href="{{ url('/cart_list') }}">Cart</a>
                            </li>
                        @else
                            <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/icons8-login-24.png') }}')">
                                <a href="{{ url('login') }}" class="menu-title">Login</a>
                            </li>
                        @endif
                        <!-- <li class="menu-category" style="list-style-image:url('{{ asset('assets/images/icons/mobile.png') }}')">
                            <a href="{{url('login')}}" class="menu-title">Login</a>
                        </li> -->
                        <i class="bi bi-list mobile-nav-toggle"></i>
                    </ul>
                </div>

            </nav>

    </header>

    @yield('content')
    
    <!--- FOOTER -->

    <footer class="footer">

        <div class="footer-nav">

            <div class="container">

                <ul class="footer-nav-list">
                    <li class="footer-nav-link">
                        <p style="color:blue;"><u>Populer Categories</u></p>
                    </li>
                    <div class="footer-links">
                        <a href="{{url('/')}}" class="footer-nav-link">
                            <p style="color:black;">Hot Deals</p>
                        </a>
                        <a href="{{url('computers')}}" class="footer-nav-link">
                            <p style="color:black;">Computers</p>
                        </a>
                        <a href="{{url('computers')}}" class="footer-nav-link">
                            <p style="color:black;">Laptops</p>
                        </a>
                        <a href="{{url('computers')}}" class="footer-nav-link">
                            <p style="color:black;">Ipod</p>
                        </a>
                        <a href="{{url('computers')}}" class="footer-nav-link">
                            <p style="color:black;">Watches</p>
                        </a>
                    </div>
                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-link">
                        <p style="color:blue;"><u>Products</u></p>
                    </li>
                    <div class="footer-links">
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">New products</p>
                        </a>
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">Best sales</p>
                        </a>
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">Contact us</p>
                        </a>
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">Sitemap</p>
                        </a>
                    </div>
                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-link">
                        <p style="color:blue;"><u>Our Company</u></p>
                    </li>
                    <div class="footer-links">
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">Delivery</p>
                        </a>
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">Legal Notice</p>
                        </a>
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">Terms and conditions</p>
                        </a>
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">About us</p>
                        </a>
                        <a href="#" class="footer-nav-link">
                            <p style="color:black;">Secure payment</p>
                        </a>
                    </div>

                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-link">
                        <p style="color:blue;"><u>Contact</u></p>
                    </li>
                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="location-outline"></ion-icon>
                        </div>

                        <address class="content">
                            <p style="color:black;"> SRD TECHNOLOGIES INDIA</p>
                            </br>
                            <p style="color:black;"> Kulsum Complex,Station More, Bagdogra,
                                Pin:734014,West Bengal</p>
                        </address>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="call-outline"></ion-icon>
                        </div>

                        <a href="tel:+607936-8058" class="footer-nav-link">
                            <p style="color:black;">6294693931</p>
                        </a>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="mail-outline"></ion-icon>
                        </div>

                        <a href="mailto:example@gmail.com" class="footer-nav-link">
                            <p style="color:black;">info@winkelkart.com</p>
                        </a>
                    </li>

                </ul>

                <ul class="footer-nav-list">

                    <li class="footer-nav-item">
                        <h2 class="nav-title">Follow Us</h2>
                    </li>

                    <li>
                        <ul class="social-link">

                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link">
                                    <ion-icon name="logo-facebook"></ion-icon>
                                </a>
                            </li>

                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link">
                                    <ion-icon name="logo-twitter"></ion-icon>
                                </a>
                            </li>

                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link">
                                    <ion-icon name="logo-linkedin"></ion-icon>
                                </a>
                            </li>

                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link">
                                    <ion-icon name="logo-instagram"></ion-icon>
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>

            </div>

        </div>

        <div class="footer-bottom">

            <div class="container">

                <img src="./assets/images/payment.png" alt="payment method" class="payment-img">

                <p class="copyright">
                    Copyright &copy; <a href="#">Winkel</a> all rights reserved.
                </p>

            </div>

        </div>
        </div>
    </footer>



    <!-- footer script-->
    <script>
        document.querySelectorAll('.nav-title').forEach(title => {
            title.addEventListener('click', () => {
                title.parentElement.classList.toggle('active');
            });
        });
    </script>

    <!--- custom js link -->
    <script src="./assets/js/script.js"></script>

    <!--- ionicon link -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
    <!-- for product-detail page -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    

    <!--  SCRIPT  FOR MOBILE MENU NAV -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("menu-toggle");
            const mobileMenu = document.getElementById("mobile-menu");

            toggleBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("show");
                mobileMenu.style.display = mobileMenu.classList.contains("show") ? "flex" : "none";
            });
        });
    </script>

</body>

</html>