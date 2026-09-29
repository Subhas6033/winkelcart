<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#4F46E5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>WinkelKart - Online Shopping</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./assets/images/logo/Winkel_Shop__2_-removebg-preview.png" type="image/x-ico">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./assets/css/style-prefix.css">
    
    <!-- Mobile Responsive CSS - Comprehensive mobile fixes for all devices -->
    <link rel="stylesheet" href="{{ asset('assets/css/mobile-responsive.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <style>


        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body) !important;
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
            padding-top: 0;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: 0.3s;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* ================= TOP MARQUEE BAR ================= */
        .top-branding {
            background-color: #5b5f06 !important;
            color: white;
            padding: 7px 0;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            letter-spacing: 1px;
            overflow: hidden;
        }

        .top-branding marquee p {
            color: white !important;
            margin: 0;
            display: inline;
            font-size: 13px;
            font-weight: 600;
        }

        /* ================= HEADER ================= */
        header {
            width: 100%;
            /* ✅ FIXED: High z-index and translateZ ensure it never vanishes behind content */
            position: fixed !important;
            top: 0 !important;
            left: 0;
            right: 0;
            z-index: 99999 !important; 
            background: var(--wk-green) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transform: translateZ(0); 
            -webkit-transform: translateZ(0);
        }

        /* Keep top-branding inside header so it also sticks with the fixed navbar */
        .top-branding {
            position: relative;
            z-index: 1;
        }

        .header-main {
            background-color: var(--wk-green) !important;
            padding: 14px 0;
        }

        .header-wrapper {
            max-width: 1350px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        /* Logo */
        .header-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .header-logo img {
            height: 75px;
            width: auto;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            transition: 0.3s;
        }

        .logo-text-container {
            display: flex;
            flex-direction: column;
            margin-left: 8px;
            justify-content: center;
        }

        .logo-main-text {
            line-height: 1;
            margin-bottom: 2px;
        }

        .logo-tagline {
            font-size: 11px;
            color: #c7d2fe;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
        }

        /* Search Bar */
        .header-search-container {
            flex: 1;
            position: relative;
            max-width: 580px;
        }

        .search-field {
            width: 100%;
            padding: 11px 50px 11px 20px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            color: var(--text-main);
        }

        .search-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: #F97316;
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: background 0.2s;
        }

        .search-btn:hover {
            background: #ea6b0e;
        }

        /* Header Right Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: auto;
        }

        .header-actions a,
        .header-actions .action-item {
            color: rgba(255, 255, 255, 0.92);
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
        }

        .header-actions a:hover,
        .header-actions .action-item:hover {
            color: white;
            transform: scale(1.05);
        }

        .header-actions ion-icon {
            font-size: 22px;
            margin-bottom: 2px;
        }

        /* Sell Button Styling */
        .sell-btn {
            background: #F97316;
            color: white !important;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            flex-direction: row !important;
            font-size: 13px !important;
            white-space: nowrap;
        }

        .sell-btn:hover {
            background: #ea6b0e !important;
        }

        /* Download App Button */
        .download-app-btn {
            background: #22c55e;
            color: white !important;
            padding: 8px 14px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            text-decoration: none !important;
        }
        .download-app-btn:hover {
            background: #16a34a !important;
            color: white !important;
        }
        .download-app-btn .app-text { display: inline; }
        .download-app-btn .app-icon { display: none; font-size: 18px; }

        /* Mobile Hamburger */
        .menu-toggle {
            display: none;
            font-size: 28px;
            color: white;
            cursor: pointer;
            background: none;
            border: none;
            line-height: 1;
        }

        /* ================= DESKTOP NAV BAR ================= */
        .desktop-navigation-menu {
            background: white;
            border-bottom: 1px solid #E5E7EB;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        /* Indigo to Purple Gradient Sub-Navbar */
        .sub-navbar {
            background: linear-gradient(90deg, #EEF2FF 0%, #e0e7ff 100%) !important;
            border-bottom: 1px solid #E5E7EB;
            padding: 8px 0;
        }

        .desktop-navigation-menu .container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 5px 0;
            flex-wrap: wrap;
            margin: 0;
            list-style: none;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #3730a3;
            padding: 8px 16px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.5);
        }

        .nav-item a:hover {
            background-color: #ffffff;
            color: #3730a3;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            object-fit: contain;
            filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.1));
        }

        /* Dropdown */
        .menu-category.dropdown .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border: 1px solid #E5E7EB;
            min-width: 180px;
        }

        .menu-category.dropdown .dropdown-item {
            font-size: 13px;
            padding: 9px 16px;
            color: #444;
            font-family: 'Poppins', sans-serif;
        }

        .menu-category.dropdown .dropdown-item:hover {
            background-color: var(--wk-light-green);
            color: var(--wk-green);
        }

        .menu-category.dropdown .dropdown-item button {
            background: none;
            border: none;
            padding: 0;
            font-size: 13px;
            color: #444;
            width: 100%;
            text-align: left;
            font-family: 'Poppins', sans-serif;
        }

        /* ================= MOBILE MENU ================= */
        .mobile-menu {
            display: none;
            flex-direction: column;
            background: white;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 10px 0;
            max-height: 80vh;
            overflow-y: auto;
        }

        .mobile-menu.show {
            display: flex;
        }

        .mobile-menu .container {
            max-width: 100%;
            padding: 0;
        }

        .mobile-menu .desktop-menu-category-list {
            flex-direction: column;
            align-items: flex-start;
            gap: 0;
            padding: 0;
        }

        .mobile-menu .menu-category {
            width: 100%;
            border-bottom: 1px solid #F3F4F6;
            list-style: none;
        }

        .mobile-menu a {
            display: block;
            padding: 12px 20px;
            font-weight: 500;
            color: #444;
            text-decoration: none;
        }

        .mobile-menu .dropdown-menu {
            position: static;
            box-shadow: none;
            border: none;
            background: #F9FAFB;
            border-radius: 0;
        }

        /* ================= FOOTER ================= */
        .footer {
            background: #111827 !important;
            padding-top: 50px;
        }

        .footer * {
            background-color: transparent !important;
            color: inherit;
        }

        .footer-wrapper {
            max-width: 1350px;
            margin: 0 auto;
            padding: 0 20px 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 35px;
        }

        .footer-col h3 {
            color: white;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-col h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 38px;
            height: 3px;
            background: #4F46E5;
        }

        .footer-col > p {
            font-size: 13px;
            line-height: 1.7;
            color: #9CA3AF;
        }

        .footer-col .social-icons {
            margin-top: 18px;
            display: flex;
            gap: 12px;
        }

        .footer-col .social-icons a ion-icon {
            font-size: 22px;
            color: #9CA3AF;
            cursor: pointer;
            transition: color 0.2s;
        }

        .footer-col .social-icons a:hover ion-icon {
            color: #4F46E5;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: #9CA3AF;
            font-size: 13px;
            text-decoration: none;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-col ul li a:hover {
            color: #4F46E5;
            padding-left: 5px;
        }

        .footer-col ul li ion-icon {
            font-size: 16px;
            color: #4F46E5;
            flex-shrink: 0;
        }

        .footer-bottom {
            background: #0d131e !important;
            border-top: 1px solid #1F2937;
            padding: 20px;
            text-align: center;
        }

        .footer-bottom .container {
            max-width: 1350px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .footer-bottom .payment-img {
            max-height: 30px;
            opacity: 0.6;
        }

        .footer-bottom .copyright {
            color: #6B7280;
            font-size: 13px;
        }

        .footer-bottom .copyright a {
            color: #6B7280;
        }

        .footer-bottom .copyright a:hover {
            color: #4F46E5;
        }

        /* ================= PARTICLE CANVAS STYLE ================= */
        body {
            position: relative;
            background-color: #F3F4F6 !important;
        }

        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
            opacity: 0.6;
        }

        header, .top-branding, main, footer, .mobile-menu, .container {
            position: relative;
            z-index: 10;
        }

        /* Fix horizontal overflow on all pages */
        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        main {
            overflow-x: hidden;
            width: 100%;
        }

        /* ================= RESPONSIVE MEDIA QUERIES (MOBILE FIXES) ================= */
        @media (max-width: 991px) {

            /* ✅ JS dynamically sets body padding-top to match header height on all screen sizes */

            /* --- HEADER LAYOUT --- */
            .header-wrapper {
                padding: 0 12px;
                justify-content: space-between;
                gap: 10px;
            }

            header {
                overflow-x: visible;
                overflow-y: visible;
            }

            .header-search-container {
                display: none;
            }

            .desktop-navigation-menu {
                display: none;
            }

            .sub-navbar {
                display: none;
            }

            .menu-toggle {
                display: block;
            }

            /* --- LOGO ADJUSTMENTS --- */
            .header-logo img {
                height: 40px;
            }

            .logo-tagline {
                display: none;
            }

            .logo-text-container {
                margin-left: 5px;
            }

            .logo-main-text span {
                font-size: 20px !important;
            }

            /* --- HEADER ACTIONS --- */
            .header-actions {
                gap: 12px;
            }

            /* Fix Sell Button for Mobile */
            .sell-btn {
                display: inline-flex !important;
                padding: 5px 10px;
                font-size: 11px !important;
                height: 30px;
                align-items: center;
                white-space: nowrap;
            }

            .action-item span {
                display: none;
            }

            .action-item ion-icon {
                font-size: 24px;
                color: white;
            }

            /* --- FOOTER MOBILE ADJUSTMENTS --- */
            .footer-wrapper {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 30px;
            }

            .footer-col h3::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-col .social-icons {
                justify-content: center;
            }

            .footer-col ul li a {
                justify-content: center;
            }

            /* Fix mobile dropdowns */
            .mobile-menu .dropdown-menu {
                position: static !important;
                transform: none !important;
                width: 100%;
                text-align: center;
            }
        }

        @media (min-width: 992px) {
            .mobile-menu {
                display: none !important;
            }
        }

        /* ================= USER DROPDOWN STYLING ================= */

        /* Enhanced Account Dropdown Styling */
        .custom-dropdown-menu {
            background: #fff !important;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(60, 60, 60, 0.15);
            padding: 28px 0 18px 0;
            min-width: 270px;
            margin-top: 22px !important;
        }
        .dropdown-user-header {
            font-size: 1.1rem;
            font-weight: 700;
            color: #222;
            padding: 0 32px 8px 32px;
            margin-bottom: 2px;
            border-bottom: none;
            background: none;
            text-align: left;
        }
        .dropdown-user-header strong {
            color: #4F46E5;
            display: block;
            font-size: 1.05rem;
            margin-top: 2px;
        }
        .dropdown-user-img {
            display: block;
            margin: 12px auto 10px auto;
            border-radius: 12px;
            width: 90px;
            height: 90px;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(60,60,60,0.08);
        }
        .custom-dropdown-item {
            padding: 12px 32px;
            font-size: 15px;
            color: #222 !important;
            background: none;
            opacity: 1 !important;
            visibility: visible !important;
            font-weight: 600;
        }
        .logout-btn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 12px 32px;
            font-size: 15px;
            color: #EF4444;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.2s;
        }
        .logout-btn:hover {
            background: #FEF2F2;
            color: #DC2626;
            text-decoration: underline;
        }

        /* Remove default Bootstrap arrow */
        .dropdown-toggle::after {
            display: none !important;
        }

        /* The Trigger (Icon + Name) */
        .user-action-btn {
            color: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            background: transparent;
            border: none;
        }

        .user-action-btn:hover, .user-action-btn[aria-expanded="true"] {
            color: white;
            transform: scale(1.05);
        }

        .user-action-btn ion-icon {
            font-size: 22px;
            margin-bottom: 2px;
        }

        /* The Dropdown Menu Box */
        .custom-dropdown-menu {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
            padding: 18px 0 10px 0;
            min-width: 260px;
            background: #fff !important;
            overflow: hidden;
            pointer-events: auto;
        }

        /* Dropdown Header (Welcome Text) */
        .dropdown-user-header {
            font-size: 17px;
            font-weight: 700;
            color: #212121;
            padding: 0 24px 12px 24px;
            margin-bottom: 8px;
            border-bottom: 1px solid #F3F4F6;
            background: none;
        }

        .dropdown-user-header strong {
            color: #4F46E5;
        }

        /* Dropdown Items */
        .custom-dropdown-item {
            padding: 12px 24px;
            font-size: 15px;
            color: #555;
            border-radius: 0;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            background: none;
        }

        .custom-dropdown-item:hover {
            background: #F3F4F6;
            color: #4F46E5 !important;
        }

        /* Hover Effects */
        .custom-dropdown-item:hover ion-icon {
            color: #4F46E5;
        }

        /* Logout Button Specifics */
        .logout-btn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 12px 24px;
            font-size: 15px;
            color: #EF4444;
        }

        .logout-btn:hover {
            background: #FEF2F2;
            color: #DC2626;
        }

        .logout-btn:hover ion-icon {
            color: #DC2626;
        }

        /* Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* =====================================================
           ✅ MOBILE RESPONSIVE STYLES
           ===================================================== */

        /* --- Mobile Search (Inside Mobile Menu - Primary Search on Mobile) --- */
        .mobile-search {
            display: none; /* Hidden by default, shown only in mobile menu */
            padding: 16px;
            background: linear-gradient(135deg, #4F46E5 0%, #111827 100%);
        }

        @media (max-width: 991px) {
            .mobile-search {
                display: block;
            }
        }

        .mobile-search form {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .mobile-search input {
            flex: 1;
            padding: 14px 18px;
            border-radius: 12px;
            border: 2px solid rgba(255,255,255,0.3);
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            color: var(--text-main);
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .mobile-search input:focus {
            border-color: #F97316;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .mobile-search input::placeholder {
            color: #888;
            font-size: 14px;
        }

        .mobile-search button {
            background: #F97316;
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
            transition: all 0.3s ease;
        }

        .mobile-search button:hover {
            background: #ea6b0e;
            transform: scale(1.05);
        }

/* --- Sell button & Download App: hide text on very small screens, show icon only --- */
        @media (max-width: 400px) {
            .sell-btn .sell-text {
                display: none;
            }
            .sell-btn .sell-icon {
                display: inline !important;
                font-size: 18px;
            }
            .sell-btn {
                padding: 5px 8px !important;
                width: 34px;
                height: 34px;
                justify-content: center;
            }
            .download-app-btn .app-text { display: none; }
            .download-app-btn .app-icon { display: inline !important; }
            .download-app-btn {
                padding: 5px 8px !important;
                width: 34px;
                height: 34px;
                justify-content: center;
                gap: 0;
            }
            .header-actions {
                gap: 8px;
            }
        }

        @media (min-width: 401px) {
            .sell-btn .sell-icon {
                display: none;
            }
        }

        /* --- Mobile menu: bigger touch targets & better spacing --- */
        @media (max-width: 991px) {
            .mobile-menu a,
            .mobile-menu button {
                padding: 14px 20px;
                font-size: 15px;
                min-height: 48px;
                display: flex;
                align-items: center;
            }

            .mobile-menu .menu-category {
                border-bottom: 1px solid #F3F4F6;
            }

            /* User greeting in mobile menu */
            .mobile-user-greeting {
                background: #EEF2FF;
                padding: 14px 20px;
                display: flex;
                align-items: center;
                gap: 10px;
                border-bottom: 2px solid #c7d2fe;
            }

            .mobile-user-greeting ion-icon {
                font-size: 28px;
                color: #4F46E5;
            }

            .mobile-user-greeting span {
                font-size: 15px;
                font-weight: 600;
                color: #4F46E5;
            }
        }

        /* --- Header: tighter on very small phones (320px - 380px) --- */
        @media (max-width: 380px) {
            .header-main {
                padding: 10px 0;
            }
            .header-wrapper {
                padding: 0 10px;
                gap: 8px;
            }
            .header-logo img {
                height: 34px;
            }
            .logo-main-text span {
                font-size: 17px !important;
            }
            .action-item ion-icon {
                font-size: 22px;
            }
            .menu-toggle {
                font-size: 26px;
            }
        }

        /* --- Top branding bar font size for mobile --- */
        @media (max-width: 576px) {
            .top-branding {
                font-size: 11px;
                padding: 5px 0;
            }
            .top-branding marquee p {
                font-size: 11px;
            }
        }

        /* --- Footer bottom: stack payment and copyright on mobile --- */
        @media (max-width: 576px) {
            .footer-bottom {
                padding: 16px 12px;
            }
            .footer-bottom .copyright {
                font-size: 11px;
                line-height: 1.6;
            }
            .footer-wrapper {
                padding: 0 15px 30px;
                gap: 24px;
            }
            .footer-col h3 {
                font-size: 15px;
            }
            .footer-col > p,
            .footer-col ul li a {
                font-size: 12px;
            }
        }

        /* ================= FIXED: Account Dropdown (Desktop + Mobile) ================= */

        /* Desktop dropdown */
        #accountDropdownMenu {
            display: none;
            position: absolute !important;
            top: calc(100% + 8px) !important;
            right: 0 !important;
            left: auto !important;
            transform: none !important;
            width: 280px;
            border-radius: 14px;
            z-index: 99999 !important;
            background: #fff !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            margin: 0 !important;
            overflow: hidden;
        }

        #accountDropdownMenu.show {
            display: block !important;
        }

        /* Make the wrap relative so dropdown positions correctly */
        #accountDropdownWrap {
            position: relative !important;
        }

        /* Mobile overrides */
        @media (max-width: 991px) {
            /* Hide dropdown menu on mobile - Account redirects to profile page */
            #accountDropdownMenu {
                display: none !important;
            }
        }

        @media (max-width: 480px) {
            .header-actions .action-item.dropdown .custom-dropdown-menu {
                top: 60px !important;
                right: 8px !important;
                width: calc(100vw - 16px);
                max-width: calc(100vw - 16px);
            }

            .custom-dropdown-item {
                padding: 10px 18px;
                font-size: 14px;
            }

            .logout-btn {
                padding: 10px 18px;
                font-size: 14px;
            }
        }

        /* --- Prevent horizontal overflow on all pages --- */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* --- Fix action icons alignment in header on mobile --- */
        @media (max-width: 991px) {
            .header-actions {
                align-items: center;
            }
            .action-item {
                min-width: 32px;
                justify-content: center;
            }
        }

        /* --- Completely hide the desktop search container on mobile --- */
        @media (max-width: 991px) {
            .header-search-container,
            .desktop-search-only {
                display: none !important;
            }
        }

        /* ================= COMPREHENSIVE MOBILE RESPONSIVE UTILITIES ================= */
        /* Mobile-first responsive utilities for all pages */
        
        /* Container & Section Padding */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px !important;
            }
            section, .section {
                padding-left: 15px;
                padding-right: 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 12px !important;
            }
        }

        /* Mobile Search visibility is controlled in MOBILE RESPONSIVE STYLES section above */

        /* Global Mobile Typography */
        @media (max-width: 576px) {
            h1 { font-size: 24px !important; }
            h2 { font-size: 20px !important; }
            h3 { font-size: 18px !important; }
            p, body { font-size: 14px; }
        }

        /* Mobile Grid Utilities */
        @media (max-width: 768px) {
            .mobile-col-1 { grid-template-columns: 1fr !important; }
            .mobile-col-2 { grid-template-columns: repeat(2, 1fr) !important; }
            .mobile-stack { flex-direction: column !important; }
            .mobile-hide { display: none !important; }
            .mobile-show { display: block !important; }
            .mobile-full-width { width: 100% !important; max-width: 100% !important; }
        }

        /* Touch-Friendly Buttons */
        @media (max-width: 768px) {
            button, .btn, [type="submit"], [type="button"] {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* Fix Images */
        @media (max-width: 768px) {
            img {
                max-width: 100%;
                height: auto;
            }
        }

        /* Mobile Scrollable Tables */
        @media (max-width: 768px) {
            .table-responsive, .table-scroll {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            table {
                min-width: 600px;
            }
        }

        /* Fix Forms */
        @media (max-width: 576px) {
            input, select, textarea {
                font-size: 16px !important; /* Prevents iOS zoom */
            }
            .form-row, .form-group {
                margin-bottom: 12px;
            }
        }

        /* Safe area for notched phones handled in mobile-responsive.css */

    </style>
</head>

<body>
    <canvas id="particles-canvas"></canvas>

    <!-- ================= HEADER ================= -->
    <header>
        <!-- ✅ FIX: Marquee bar moved inside <header> so it sticks with the navbar on scroll -->
        <div class="top-branding">
                        <div class="announcement-bar">
                            <div class="announcement-text">
                                🛒 WELCOME TO WINKELKART — INDIA'S OWN SHOPPING DESTINATION &nbsp;&nbsp;&nbsp; 🔥 FREEDOM SALE UPTO 80% OFF &nbsp;&nbsp;&nbsp; 🚚 FREE DELIVERY ON ORDERS ABOVE ₹499
                            </div>
                        </div>
        </div>
        <div class="header-main">
            <div class="container header-wrapper">

                <!-- Logo -->
                <a href="{{ url('/') }}" class="header-logo">
                    <img src="{{ asset('assets/images/logo/Winkel_Shop__2_-removebg-preview.png') }}" alt="WinkelKart Logo">

                    <div class="logo-text-container">
                        <div class="logo-main-text">
                            <span style="font-size:26px; font-weight:800; color:white; font-family:'Poppins',sans-serif; letter-spacing:-0.5px;">Winkel</span>
                            <span style="font-size:26px; font-weight:800; color:#F97316; font-family:'Poppins',sans-serif; letter-spacing:-0.5px;">Kart</span>
                        </div>
                        <span class="logo-tagline">Shop Smart, Live Better</span>
                    </div>
                </a>

                <!-- Search Bar (Desktop Only - hidden on mobile via CSS) -->
                <form class="header-search-container desktop-search-only" action="{{ route('product.search') }}" method="GET" style="display:flex;align-items:center;max-width:580px;width:100%;">
                    <input type="text" name="q" class="search-field" placeholder="Search Electronics, Appliances, Hotels..." required>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- Right Actions -->
                <div class="header-actions">

                    <!-- Download App Button -->
                    <a href="{{ asset('WinkelKart.apk') }}" class="download-app-btn" download="WinkelKart.apk" title="Download WinkelKart App">
                        <ion-icon name="logo-android" class="app-icon"></ion-icon>
                        <span class="app-text">📲 Download App</span>
                    </a>

                    <!-- Sell Button: text on tablet, icon only on very small phones -->
                    <a href="{{ url('register-buisness') }}" class="sell-btn">
                        <span class="sell-text">Sell With Us</span>
                        <span class="sell-icon" style="display:none;">🏪</span>
                    </a>

                    @if(Auth::check())
                    <a href="{{ url('/cart_list') }}" class="action-item position-relative">
                        <ion-icon name="cart-outline"></ion-icon>
                        <span>Cart
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                            @endphp
                            @if($cartCount > 0)
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="font-size:10px;">{{ $cartCount }}</span>
                            @endif
                        </span>
                    </a>

                    <!-- Account Link - Direct to Profile -->
                    <a href="{{ url('/profile-buyer') }}" class="action-item" style="text-decoration: none;">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span>Hello, {{ strtok(Auth::user()->name, ' ') }}</span>
                    </a>
                    @else
                    <!-- Guest User -->
                    <a href="{{ url('login') }}" class="action-item">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span>Login</span>
                    </a>
                    <a href="{{ url('register-buyer') }}" class="action-item">
                        <ion-icon name="person-add-outline"></ion-icon>
                        <span>Register</span>
                    </a>
                    @endif

                    <!-- Mobile Menu Button -->
                    <button class="menu-toggle" id="menu-toggle" data-mobile-menu-open-btn>
                        <ion-icon name="menu-outline"></ion-icon>
                    </button>
                </div>
            </div>
        </div>

        <!-- SUB NAVBAR (Desktop Categories) -->
        <div class="sub-navbar">
            <div class="container">
                <ul class="nav-list">
                    <li class="nav-item"><a href="{{ url('hotels-and-resorts') }}">🏨 Hotels & Resorts</a></li>
                    <li class="nav-item"><a href="{{ url('cosmetics') }}">👗 Fashion</a></li>
                        <li class="nav-item"><a href="{{ url('mobile') }}">📱 Mobile</a></li>
                        <li class="nav-item"><a href="{{ url('electronics') }}">💻 Electronics</a></li>
                        <li class="nav-item"><a href="{{ url('appliances') }}">🔌 Appliances</a></li>
                    <li class="nav-item"><a href="{{ url('contact') }}">📞 Contact</a></li>
                </ul>
            </div>
        </div>

        <!-- MOBILE MENU DROPDOWN -->
        <div class="mobile-menu" id="mobile-menu" data-mobile-menu>
            <!-- Mobile Menu Close Button (for UX) -->
            <button class="menu-toggle mobile-menu-close" data-mobile-menu-close-btn style="position:absolute;top:10px;right:10px;z-index:10;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
            <!-- Mobile Search Bar (Primary Search on Mobile) -->
            <div class="mobile-search">
                <form action="{{ route('product.search') }}" method="GET">
                    <input type="text" name="q" placeholder="🔍 Search electronics, appliances, hotels..." required>
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="container">
                <ul style="padding:0; margin:0;">

                    @if(Auth::check())
                    <!-- ✅ User greeting at top of mobile menu -->
                    <li class="menu-category mobile-user-greeting">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span>👋 Hello, {{ Auth::user()->name }}</span>
                    </li>
                    @endif

                    <li class="menu-category"><a href="{{ url('/') }}">🔥 Hot Deals</a></li>
                    <li class="menu-category"><a href="{{ url('hotels-and-resorts') }}">🏨 Hotels & Resorts</a></li>
                    <li class="menu-category"><a href="{{ url('cosmetics') }}">👗 Fashion</a></li>
                        <li class="menu-category"><a href="{{ url('mobile') }}">📱 Mobile</a></li>
                    <li class="menu-category"><a href="{{ url('electronics') }}">💻 Electronics</a></li>
                        <li class="menu-category"><a href="{{ url('appliances') }}">🔌 Appliances</a></li>
                    <li class="menu-category"><a href="{{ url('contact') }}">📞 Contact</a></li>
                    <li class="menu-category"><a href="{{ url('register-buisness') }}">🏢 Sell on WinkelKart</a></li>
                    <li class="menu-category"><a href="{{ asset('WinkelKart.apk') }}" download="WinkelKart.apk">📲 Download App</a></li>

                    @if(Auth::check())
                    <li class="menu-category"><a href="{{ url('/profile-buyer') }}">👤 My Profile</a></li>
                    <li class="menu-category"><a href="{{ route('profile.edit') }}">✍ Edit Profile</a></li>
                    <li class="menu-category"><a href="{{ route('profile.addresses') }}">📍 Manage Address</a></li>
                    <li class="menu-category"><a href="{{ url('/my-orders') }}">📦 My Orders</a></li>
                    <li class="menu-category"><a href="{{ route('support.index') }}">🛟 Help & Support</a></li>
                    <li class="menu-category">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" style="background:none; border:none; color:#EF4444; padding:14px 20px; font-size:15px; font-weight:500; width:100%; text-align:left; min-height:48px;">
                                🚪 Logout
                            </button>
                        </form>
                    </li>
                    @else
                    <li class="menu-category"><a href="{{ url('login') }}">🔐 Login</a></li>
                    <li class="menu-category"><a href="{{ url('register-buyer') }}">📝 Register</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </header>

<!-- ================= DYNAMIC HERO SLIDER ================= -->
@if(
    request()->is('hotels-and-resorts') ||
    request()->is('cosmetics') ||
    request()->is('mobile') ||
    request()->is('electronics') ||
    request()->is('appliances')
)
    @include('front.components.hero-slider-dynamic')
@endif
    <!-- ================= PAGE CONTENT ================= -->
    @yield('content')

    <!-- ================= FOOTER ================= -->
    <footer class="footer">

        <div class="footer-wrapper">

            <!-- About Col -->
            <div class="footer-col">
                <h3>WinkelKart</h3>
                <p>India's most trusted online shopping destination. From high-end electronics to home appliances and luxury stays, we have it all.</p>
                <div class="social-icons">
                    <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                    <a href="#"><ion-icon name="logo-linkedin"></ion-icon></a>
                </div>
            </div>

            <!-- Categories Col -->
            <div class="footer-col">
                <h3>Categories</h3>
                <ul>
                    <li><a href="{{ url('/') }}">Hot Deals</a></li>
                    <li><a href="{{ url('hotels-and-resorts') }}">Hotels & Resorts</a></li>
                    <li><a href="{{ url('cosmetics') }}">Fashion</a></li>
                    <li><a href="{{ url('mobile') }}">Mobile Phones</a></li>
                    <li><a href="{{ url('electronics') }}">Electronics</a></li>
                    <li><a href="{{ url('appliances') }}">Appliances</a></li>
                </ul>
            </div>

            <!-- For Business Col -->
            <div class="footer-col">
                <h3>For Business</h3>
                <ul>
                    <li><a href="{{ url('register-buisness') }}">Sell on WinkelKart</a></li>
                    <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('return_refund') }}">Return & Refund</a></li>
                    <li><a href="{{ route('shipping_delivery') }}">Shipping & Delivery</a></li>
                    <li><a href="{{ route('cancellation_policy') }}">Cancellation Policy</a></li>
                    <li><a href="{{ route('support.index') }}">Help & Support</a></li>
                </ul>
            </div>

            <!-- Contact Col -->
            <div class="footer-col">
                <h3>Contact Us</h3>
                <ul>
                    <li>
                        <a href="#">
                            <ion-icon name="location-outline"></ion-icon>
                            SRD Technologies, Kulsum Complex, Bagdogra, West Bengal
                        </a>
                    </li>
                    <li>
                        <a href="tel:6294693931">
                            <ion-icon name="call-outline"></ion-icon>
                            +91 6294693931
                        </a>
                    </li>
                    <li>
                        <a href="mailto:info@winkelkart.com">
                            <ion-icon name="mail-outline"></ion-icon>
                            info@winkelkart.com
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <div class="container">
                <img src="{{ asset('assets/images/payment.png') }}" alt="payment method" class="payment-img">
                <p class="copyright">
                    Copyright &copy; 2026 <a href="#">WinkelKart</a>. A UNIT OF SRD TECHNOLOGIES INDIA. GSTIN: 19DFEPR9642F1ZY
                </p>
            </div>
        </div>

    </footer>

    <!-- ✅ FIX: Dynamically set body padding-top to always match actual fixed header height -->
    <script>
        (function () {
            function adjustBodyPadding() {
                var header = document.querySelector('header');
                if (header) {
                    document.body.style.paddingTop = header.offsetHeight + 'px';
                }
            }
            // Run on load
            window.addEventListener('load', adjustBodyPadding);
            // Run on resize (handles mobile/desktop switch)
            window.addEventListener('resize', adjustBodyPadding);
            // Also run immediately in case fonts/icons shift height
            document.addEventListener('DOMContentLoaded', adjustBodyPadding);
        })();
    </script>

    <!-- Custom JS -->
    <script src="./assets/js/script.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ FIXED: Mobile Menu + Account Dropdown (placed after Bootstrap JS) -->
    <script>
        (function() {
            var toggleBtn   = document.getElementById("menu-toggle");
            var mobileMenu  = document.getElementById("mobile-menu");
            var accountBtn  = document.getElementById("accountDropdown");
            var accountMenu = document.getElementById("accountDropdownMenu");
            var accountWrap = document.getElementById("accountDropdownWrap");

            /* ---------- Hamburger menu ---------- */
            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener("click", function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle("show");
                    // Close account dropdown if open
                    if (accountMenu) accountMenu.classList.remove("show");
                });
            }

            /* ---------- Account dropdown (desktop only, redirect on mobile) ---------- */
            if (accountBtn && accountMenu) {
                accountBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // On mobile: redirect to profile page
                    if (window.innerWidth <= 991) {
                        window.location.href = '{{ url("/profile-buyer") }}';
                        return;
                    }
                    
                    // On desktop: toggle dropdown
                    var isOpen = accountMenu.classList.contains("show");
                    // Close hamburger menu first on mobile
                    if (mobileMenu) mobileMenu.classList.remove("show");
                    if (isOpen) {
                        accountMenu.classList.remove("show");
                    } else {
                        accountMenu.classList.add("show");
                    }
                });
            }

            /* ---------- Close dropdown when clicking outside ---------- */
            document.addEventListener("click", function(e) {
                if (accountMenu && accountWrap && !accountWrap.contains(e.target)) {
                    accountMenu.classList.remove("show");
                }
                if (mobileMenu && toggleBtn && !mobileMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
                    mobileMenu.classList.remove("show");
                }
            });

            /* ---------- Close on Escape ---------- */
            document.addEventListener("keydown", function(e) {
                if (e.key === "Escape") {
                    if (accountMenu) accountMenu.classList.remove("show");
                    if (mobileMenu)  mobileMenu.classList.remove("show");
                }
            });
        })();
    </script>

    <!-- Particle Script -->
    <script>
        (function() {
            const canvas = document.getElementById('particles-canvas');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let width, height;
            let particles = [];

            const particleCount = 100;
            const connectionDistance = 150;
            const mouseDistance = 180;
            const particleColors = ['#4F46E5', '#a5b4fc', '#E5E7EB'];

            function resize() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width;
                canvas.height = height;
            }
            window.addEventListener('resize', resize);
            resize();

            let mouse = {
                x: null,
                y: null
            };
            window.addEventListener('mousemove', (e) => {
                mouse.x = e.x;
                mouse.y = e.y;
            });
            window.addEventListener('mouseout', () => {
                mouse.x = null;
                mouse.y = null;
            });

            class Particle {
                constructor() {
                    this.x = Math.random() * width;
                    this.y = Math.random() * height;
                    this.vx = (Math.random() - 0.5) * 1;
                    this.vy = (Math.random() - 0.5) * 1;
                    this.size = Math.random() * 3 + 1;
                    this.color = particleColors[Math.floor(Math.random() * particleColors.length)];
                }
                update() {
                    this.x += this.vx;
                    this.y += this.vy;
                    if (this.x < 0 || this.x > width) this.vx *= -1;
                    if (this.y < 0 || this.y > height) this.vy *= -1;

                    if (mouse.x != null) {
                        let dx = mouse.x - this.x;
                        let dy = mouse.y - this.y;
                        let distance = Math.sqrt(dx * dx + dy * dy);
                        if (distance < mouseDistance) {
                            const forceDirectionX = dx / distance;
                            const forceDirectionY = dy / distance;
                            const force = (mouseDistance - distance) / mouseDistance;
                            const directionX = forceDirectionX * force * 2;
                            const directionY = forceDirectionY * force * 2;
                            this.x -= directionX;
                            this.y -= directionY;
                        }
                    }
                }
                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            for (let i = 0; i < particleCount; i++) particles.push(new Particle());

            function animate() {
                ctx.clearRect(0, 0, width, height);
                for (let i = 0; i < particles.length; i++) {
                    particles[i].update();
                    particles[i].draw();
                    for (let j = i; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        if (distance < connectionDistance) {
                            ctx.beginPath();
                            const opacity = 1 - (distance / connectionDistance);
                            ctx.strokeStyle = 'rgba(79, 70, 229, ' + opacity * 0.2 + ')';
                            ctx.lineWidth = 1;
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
                requestAnimationFrame(animate);
            }
            animate();
        })();
    </script>

</body>

</html>