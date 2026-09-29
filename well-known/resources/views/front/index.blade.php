@extends('front.layouts.app')

@section('content')

<style>
    /* ================= GOOGLE FONTS ================= */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700&display=swap');

    /* ================= RESET & VARIABLES ================= */
    :root {
        --wk-green: #2e9e4f;
        --wk-dark-green: #1e6e35;
        --wk-light-green: #edfaf2;
        --wk-yellow: #fbbf24;
        --wk-pink: #f43f5e;
        --wk-blue: #2563eb;
        --wk-orange: #f97316;

        --text-main: #0f172a;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --bg-body: #f1f5f9;
        --bg-card: #ffffff;
        --border: #e2e8f0;
        --white: #ffffff;

        --radius-sm: 8px;
        --radius-md: 14px;
        --radius-lg: 20px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.04);
        --shadow-lg: 0 20px 40px rgba(0,0,0,0.12), 0 8px 16px rgba(0,0,0,0.06);
        --shadow-hover: 0 24px 50px rgba(0,0,0,0.14), 0 10px 20px rgba(0,0,0,0.06);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: var(--bg-body);
        color: var(--text-main);
        -webkit-font-smoothing: antialiased;
    }

    a { text-decoration: none; color: inherit; transition: all 0.25s ease; }
    ul { list-style: none; padding: 0; margin: 0; }

    .container {
        max-width: 1380px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ================= HERO SLIDER ================= */
    .hero-slider {
        position: relative;
        height: 520px;
        width: 100%;
        overflow: hidden;
        margin-bottom: 48px;
        border-radius: 0 0 32px 32px;
    }

    .slide {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.9s;
        padding: 0 5%;
    }

    .slide.active { 
        opacity: 1; 
        visibility: visible;
        z-index: 1; 
    }

    /* Slide background overlay pattern */
    .slide::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }

    /* Animated glow accent for slides */
    .slide::after {
        content: '';
        position: absolute;
        bottom: -50%;
        right: -10%;
        width: 60%;
        height: 100%;
        background: radial-gradient(ellipse at center, rgba(255,255,255,0.12) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    .slide-content {
        max-width: 1280px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 50px;
        color: white;
        position: relative;
        z-index: 1;
    }

    .slide-text { 
        flex: 1; 
        max-width: 580px;
        animation: slideTextIn 0.8s ease-out;
    }

    @keyframes slideTextIn {
        from { 
            opacity: 0; 
            transform: translateY(30px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .slide-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        margin-bottom: 22px;
        border: 1px solid rgba(255,255,255,0.25);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .slide-tag.pulse {
        animation: tagPulse 2s infinite;
    }

    @keyframes tagPulse {
        0%, 100% { box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
        50% { box-shadow: 0 4px 30px rgba(255,255,255,0.3); }
    }

    .slide-text h2 {
        font-size: 56px;
        font-weight: 900;
        margin-bottom: 18px;
        line-height: 1.02;
        letter-spacing: -2px;
        text-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .slide-text h2 span {
        background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .slide-text p {
        font-size: 18px;
        margin-bottom: 28px;
        opacity: 0.9;
        line-height: 1.65;
        font-weight: 400;
        max-width: 480px;
    }

    .slide-btns {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .slide-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 16px 38px;
        background: white;
        color: #1a1a1a;
        border-radius: 50px;
        font-weight: 700;
        font-size: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }

    .slide-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.5) 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .slide-btn:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 16px 40px rgba(0,0,0,0.3);
        color: #1a1a1a;
    }

    .slide-btn:hover::before { opacity: 1; }

    .slide-btn.outline {
        background: transparent;
        border: 2px solid rgba(255,255,255,0.6);
        color: white;
        box-shadow: none;
    }

    .slide-btn.outline:hover {
        background: rgba(255,255,255,0.15);
        border-color: white;
        color: white;
    }

    .slide-img {
        flex: 0 0 auto;
        width: 420px;
        height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        animation: slideImgIn 0.8s ease-out 0.2s both;
    }

    @keyframes slideImgIn {
        from { 
            opacity: 0; 
            transform: translateX(40px) scale(0.9); 
        }
        to { 
            opacity: 1; 
            transform: translateX(0) scale(1); 
        }
    }

    .slide-img::before {
        content: '';
        position: absolute;
        inset: -10px;
        background: rgba(255,255,255,0.06);
        border-radius: 24px;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    /* Decorative ring around product image */
    .slide-img::after {
        content: '';
        position: absolute;
        inset: 20px;
        border: 2px dashed rgba(255,255,255,0.15);
        border-radius: 20px;
        animation: ringRotate 20s linear infinite;
    }

    @keyframes ringRotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .slide-img img {
        max-height: 320px;
        max-width: 370px;
        object-fit: contain;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 25px 40px rgba(0,0,0,0.3));
        transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .slide.active .slide-img img { 
        animation: imgFloat 4s ease-in-out infinite;
    }

    @keyframes imgFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }

    /* Deal slide specific styles */
    .slide.deal-slide .deal-badge {
        position: absolute;
        top: -10px;
        right: 30px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 12px 22px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 22px;
        box-shadow: 0 8px 25px rgba(239,68,68,0.4);
        z-index: 10;
        transform: rotate(5deg);
        animation: badgePop 0.5s ease-out 0.5s both;
    }

    @keyframes badgePop {
        from { transform: rotate(5deg) scale(0); }
        to { transform: rotate(5deg) scale(1); }
    }

    .slide.deal-slide .deal-price {
        display: flex;
        align-items: baseline;
        gap: 12px;
        margin-bottom: 8px;
    }

    .slide.deal-slide .deal-price .current {
        font-size: 42px;
        font-weight: 900;
        color: #fbbf24;
    }

    .slide.deal-slide .deal-price .original {
        font-size: 20px;
        text-decoration: line-through;
        opacity: 0.5;
    }

    .slide.deal-slide .deal-savings {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(34,197,94,0.25);
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        color: #86efac;
        margin-bottom: 20px;
    }

    /* Slider Navigation */
    .slider-dots {
        position: absolute; 
        bottom: 28px; 
        left: 50%; 
        transform: translateX(-50%);
        display: flex; 
        gap: 10px; 
        z-index: 5;
        align-items: center;
        background: rgba(0,0,0,0.25);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 10px 18px;
        border-radius: 50px;
    }

    .dot {
        width: 10px; 
        height: 10px;
        background: rgba(255,255,255,0.4);
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .dot:hover {
        background: rgba(255,255,255,0.7);
        transform: scale(1.15);
    }

    .dot.active {
        background: white;
        width: 32px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(255,255,255,0.4);
    }

    .slider-arrow {
        position: absolute;
        top: 50%; 
        transform: translateY(-50%);
        width: 52px; 
        height: 52px;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; 
        align-items: center; 
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        color: white;
        font-size: 20px;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .slider-arrow:hover { 
        background: rgba(255,255,255,0.25); 
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .slider-arrow.prev { left: 24px; }
    .slider-arrow.next { right: 24px; }

    /* ================= PAGE SECTIONS ================= */
    .section-container {
        max-width: 1380px;
        margin: 0 auto 56px auto;
        padding: 0 20px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--border);
        position: relative;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--wk-green);
        border-radius: 2px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.5px;
    }

    .section-title ion-icon {
        font-size: 22px;
    }

    .view-all {
        color: var(--wk-blue);
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 7px 16px;
        border-radius: 50px;
        background: #eff6ff;
        transition: all 0.25s;
    }
    .view-all:hover {
        background: var(--wk-blue);
        color: white;
    }

    /* ================= PRODUCT GRID ================= */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
    }

    /* ================= PRODUCT CARD ================= */
    .product-card {
        background: var(--bg-card);
        border-radius: var(--radius-md);
        padding: 16px;
        border: 1.5px solid var(--border);
        position: relative;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .product-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(46,158,79,0.03) 0%, transparent 60%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(46,158,79,0.2);
    }
    .product-card:hover::before { opacity: 1; }

    /* Badges */
    .discount {
        position: absolute;
        top: 12px; right: 12px;
        background: var(--wk-pink);
        color: white;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        z-index: 2;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(244,63,94,0.35);
    }

    .badge {
        position: absolute;
        top: 12px; left: 12px;
        background: var(--wk-yellow);
        color: #78350f;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        z-index: 2;
        letter-spacing: 0.5px;
    }

    /* Image Container */
    .product-img {
        height: 175px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        background: linear-gradient(145deg, #f8fafc, #f1f5f9);
        border-radius: var(--radius-sm);
        overflow: hidden;
        position: relative;
    }

    /* Broken image / loading state */
    .product-img img {
        max-height: 145px;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        /* Show placeholder when broken */
        min-height: 60px;
        min-width: 60px;
    }

    /* When image fails to load — hide broken icon, show gradient bg */
    .product-img img[alt]:not([src]),
    .product-img img[src=""],
    .product-img img:not([src]) {
        visibility: hidden;
    }

    .product-card:hover .product-img img { transform: scale(1.07); }

    /* Product Info */
    .product-info {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .product-info h3 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
        color: var(--text-main);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4;
    }

    .store-name {
        font-size: 11px;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .price-box {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 14px;
        margin-top: auto;
        flex-wrap: wrap;
    }

    .price-curr {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .price-old {
        font-size: 13px;
        text-decoration: line-through;
        color: var(--text-muted);
        font-weight: 400;
    }

    /* Buttons */
    .card-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 11px 16px;
        background: var(--wk-green);
        color: white;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 13px;
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
        letter-spacing: 0.2px;
    }

    .card-btn:hover {
        background: var(--wk-dark-green);
        color: white;
        box-shadow: 0 6px 18px rgba(46,158,79,0.35);
        transform: translateY(-1px);
    }

    .card-btn.book-btn {
        background: var(--wk-blue);
    }
    .card-btn.book-btn:hover {
        background: #1d4ed8;
        box-shadow: 0 6px 18px rgba(37,99,235,0.35);
    }

    /* ================= SECTION-SPECIFIC ACCENTS ================= */
    /* Hotels section */
    .hotels-section .section-header::after { background: var(--wk-blue); }

    /* Hot deals section */
    .deals-section .section-header::after { background: var(--wk-pink); }

    /* ================= EMPTY / LOADING STATES ================= */
    .product-img::after {
        content: '🛍️';
        position: absolute;
        font-size: 36px;
        opacity: 0.2;
        pointer-events: none;
    }

    /* Hide the emoji when real image is loaded */
    .product-img:has(img[src]:not([src=""])) ::after {
        display: none;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1100px) {
        .product-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
    }

    @media (max-width: 900px) {
        .slide-content { flex-direction: column; text-align: center; }
        .slide-img { display: none; }
        .slide-text h2 { font-size: 34px; letter-spacing: -0.5px; }
        .hero-slider { height: 360px; border-radius: 0 0 24px 24px; }
        .slide-btns { flex-direction: column; gap: 10px; }
        .slide-btn.outline { padding: 12px 24px; }
        .slide.deal-slide .deal-price .current { font-size: 32px; }
        .slide.deal-slide .deal-price .original { font-size: 16px; }
        .slide.deal-slide .deal-badge { display: none; }
    }

    @media (max-width: 768px) {
        .container { padding: 0 15px; }
        .section-container { padding: 0 15px; margin-bottom: 40px; }
        .product-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .section-title { font-size: 18px; }
        .section-header { margin-bottom: 18px; padding-bottom: 12px; }
        .hero-slider { height: 340px; margin-bottom: 36px; border-radius: 0 0 20px 20px; }
        .slide-text h2 { font-size: 30px; }
        .slide-text p { font-size: 15px; margin-bottom: 24px; }
        .slide-btn { padding: 12px 28px; font-size: 14px; }
        .slide-btns { gap: 10px; }
        .slider-arrow { width: 40px; height: 40px; font-size: 16px; }
        .slider-arrow.prev { left: 12px; }
        .slider-arrow.next { right: 12px; }
        .slider-dots { padding: 8px 14px; }
        .product-card { padding: 12px; }
        .product-img { height: 150px; }
        .product-info h3 { font-size: 13px; }
        .price-curr { font-size: 17px; }
        .card-btn { padding: 10px 14px; font-size: 12px; min-height: 44px; }
        .view-all { padding: 6px 12px; font-size: 12px; }
        .slide.deal-slide .deal-price .current { font-size: 28px; }
        .slide.deal-slide .deal-savings { font-size: 11px; padding: 5px 10px; }
    }

    @media (max-width: 600px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .section-title { font-size: 18px; }
        .hero-slider { height: 320px; border-radius: 0 0 16px 16px; }
        .slide-text h2 { font-size: 26px; }
        .slide-btns { flex-direction: column; width: 100%; }
        .slide-btn { width: 100%; justify-content: center; padding: 12px 20px; }
        .slide.deal-slide .deal-price .current { font-size: 26px; }
    }

    @media (max-width: 480px) {
        .container { padding: 0 12px; }
        .section-container { padding: 0 12px; margin-bottom: 32px; }
        .hero-slider { height: 300px; margin-bottom: 28px; }
        .slide { padding: 0 15px; }
        .slide-text h2 { font-size: 22px; }
        .slide-text p { font-size: 13px; margin-bottom: 18px; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .slide-tag { font-size: 10px; padding: 5px 12px; margin-bottom: 15px; }
        .slide-btn { padding: 10px 18px; font-size: 13px; }
        .slider-arrow { width: 36px; height: 36px; font-size: 14px; }
        .slider-arrow.prev { left: 8px; }
        .slider-arrow.next { right: 8px; }
        .slider-dots { padding: 6px 12px; bottom: 16px; }
        .dot { width: 8px; height: 8px; }
        .dot.active { width: 24px; }
        .section-title { font-size: 16px; }
        .section-title ion-icon { font-size: 18px; }
        .product-grid { gap: 10px; }
        .product-card { padding: 10px; border-radius: 10px; }
        .product-img { height: 130px; margin-bottom: 10px; }
        .product-img img { max-height: 115px; }
        .product-info h3 { font-size: 12px; white-space: normal; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .store-name { font-size: 10px; margin-bottom: 8px; }
        .price-box { margin-bottom: 10px; gap: 6px; }
        .price-curr { font-size: 15px; }
        .price-old { font-size: 11px; }
        .discount { top: 8px; right: 8px; font-size: 9px; padding: 3px 8px; }
        .badge { top: 8px; left: 8px; font-size: 9px; padding: 3px 8px; }
        .card-btn { padding: 9px 12px; font-size: 11px; border-radius: 6px; }
        .view-all { padding: 5px 10px; font-size: 11px; }
        .slide.deal-slide .deal-price { flex-direction: column; gap: 2px; }
        .slide.deal-slide .deal-price .current { font-size: 24px; }
        .slide.deal-slide .deal-price .original { font-size: 14px; }
        .slide.deal-slide .deal-savings { font-size: 10px; padding: 4px 8px; }
    }

    @media (max-width: 380px) {
        .container { padding: 0 10px; }
        .section-container { padding: 0 10px; }
        .hero-slider { height: 250px; }
        .slide-text h2 { font-size: 20px; }
        .slide-text p { font-size: 12px; margin-bottom: 16px; }
        .slide-btn { padding: 9px 18px; font-size: 12px; }
        .section-header { flex-direction: column; gap: 10px; align-items: flex-start; }
        .section-title { font-size: 15px; }
        .product-card { padding: 8px; }
        .product-img { height: 110px; margin-bottom: 8px; }
        .product-img img { max-height: 95px; }
        .product-info h3 { font-size: 11px; }
        .price-curr { font-size: 14px; }
        .card-btn { padding: 8px 10px; font-size: 10px; }
    }
    
    /* Extra small screens (Galaxy Fold, small phones under 360px) */
    @media (max-width: 359px) {
        .container { padding: 0 8px; }
        .section-container { padding: 0 8px; margin-bottom: 24px; }
        .hero-slider { height: 220px; }
        .slide { padding: 0 10px; }
        .slide-text h2 { font-size: 18px; line-height: 1.2; }
        .slide-text p { font-size: 11px; margin-bottom: 12px; -webkit-line-clamp: 2; line-clamp: 2; }
        .slide-tag { font-size: 9px; padding: 4px 10px; margin-bottom: 10px; }
        .slide-btn { padding: 8px 16px; font-size: 11px; }
        .slider-arrow { display: none; }
        .slider-dots { bottom: 10px; }
        .dot { width: 6px; height: 6px; }
        .dot.active { width: 18px; }
        .section-title { font-size: 14px; }
        .section-title ion-icon { font-size: 16px; }
        .product-grid { gap: 8px; }
        .product-card { padding: 6px; border-radius: 8px; }
        .product-img { height: 95px; margin-bottom: 6px; border-radius: 6px; }
        .product-img img { max-height: 85px; }
        .product-info h3 { font-size: 10px; -webkit-line-clamp: 2; }
        .store-name { font-size: 9px; margin-bottom: 6px; }
        .price-box { margin-bottom: 8px; gap: 4px; }
        .price-curr { font-size: 13px; }
        .price-old { font-size: 10px; }
        .discount { font-size: 8px; padding: 2px 6px; top: 6px; right: 6px; }
        .badge { font-size: 8px; padding: 2px 6px; top: 6px; left: 6px; }
        .card-btn { padding: 7px 8px; font-size: 10px; border-radius: 5px; min-height: 36px; }
        .view-all { padding: 4px 8px; font-size: 10px; }
    }
    
    /* Ultra small (Galaxy Fold closed, <280px) */
    @media (max-width: 280px) {
        .container { padding: 0 6px; }
        .hero-slider { height: 180px; }
        .slide-text h2 { font-size: 16px; }
        .slide-text p { display: none; }
        .slide-btn { padding: 6px 12px; font-size: 10px; }
        .product-grid { grid-template-columns: 1fr; gap: 6px; }
        .product-card { flex-direction: row; padding: 8px; }
        .product-img { width: 80px; height: 80px; flex-shrink: 0; margin-bottom: 0; margin-right: 10px; }
        .product-info { text-align: left; }
        .card-btn { margin-top: auto; }
    }
</style>

<!-- ================= HERO SLIDER ================= -->
<section class="hero-slider" id="hero-slider">
    @php $slideIndex = 0; @endphp

    <!-- Slide 1: WinkelKart Welcome (Premium Gradient) -->
    <div class="slide active" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 30%, #334155 60%, #1e293b 100%);">
        <div class="container slide-content">
            <div class="slide-text">
                <div class="slide-tag pulse">
                    <ion-icon name="sparkles-outline"></ion-icon>
                    Welcome to WinkelKart
                </div>
                <h2>Shop <span>Smarter</span>,<br>Live Better</h2>
                <p>Discover thousands of products from trusted sellers. From electronics to fashion, hotels to daily essentials — all in one place.</p>
                <div class="slide-btns">
                    <a href="{{ url('electronics') }}" class="slide-btn">
                        Start Shopping <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="slide-img">
                <img src="{{ asset('assets/images/products/mobile1.jpg') }}" alt="WinkelKart">
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp

    <!-- Slide 2: Computers & Tech (Emerald Theme) -->
    <div class="slide" style="background: linear-gradient(135deg, #064e3b 0%, #047857 40%, #059669 70%, #10b981 100%);">
        <div class="container slide-content">
            <div class="slide-text">
                <div class="slide-tag">
                    <ion-icon name="desktop-outline"></ion-icon>
                    Tech Zone
                </div>
                <h2>Power Up Your<br><span>Workspace</span></h2>
                <p>Premium laptops, desktops, and accessories. Get up to 40% off on top brands this season!</p>
                <div class="slide-btns">
                    <a href="{{ url('computers') }}" class="slide-btn" style="color:#047857;">
                        Shop Computers <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="slide-img">
                <img src="{{ asset('assets/images/products/desktop2.jpg') }}" alt="Computers">
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp

    <!-- Slide 3: Electronics (Electric Blue Theme) -->
    <div class="slide" style="background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 40%, #4f46e5 70%, #6366f1 100%);">
        <div class="container slide-content">
            <div class="slide-text">
                <div class="slide-tag">
                    <ion-icon name="flash-outline"></ion-icon>
                    Electronics Sale
                </div>
                <h2>Latest <span>Gadgets</span><br>& Accessories</h2>
                <p>Headphones, smartwatches, power banks and more. Premium quality at unbeatable prices.</p>
                <div class="slide-btns">
                    <a href="{{ url('electronics') }}" class="slide-btn" style="color:#4f46e5;">
                        Browse Electronics <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="slide-img">
                <img src="{{ asset('assets/images/products/headphone1.jpg') }}" alt="Electronics">
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp

    <!-- Slide 4: Hotels & Resorts (Sunset Blue Theme) -->
    <div class="slide" style="background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 40%, #0284c7 70%, #0ea5e9 100%);">
        <div class="container slide-content">
            <div class="slide-text">
                <div class="slide-tag">
                    <ion-icon name="airplane-outline"></ion-icon>
                    Vacation Mode
                </div>
                <h2>Luxury <span>Stays</span><br>Await You</h2>
                <p>Book top-rated hotels, resorts, and villas at exclusive prices. Your dream getaway is just a click away.</p>
                <div class="slide-btns">
                    <a href="{{ url('hotels-and-resorts') }}" class="slide-btn" style="color:#0284c7;">
                        Book Your Stay <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="slide-img">
                <img src="{{ asset('assets/images/products/hotels11.jpg') }}" alt="Hotels">
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp

    <!-- Slide 5: Fashion (Rose/Pink Theme) -->
    <div class="slide" style="background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 40%, #8b5cf6 70%, #a78bfa 100%);">
        <div class="container slide-content">
            <div class="slide-text">
                <div class="slide-tag">
                    <ion-icon name="shirt-outline"></ion-icon>
                    Fashion Forward
                </div>
                <h2>Style That<br><span>Speaks</span></h2>
                <p>Trending fashion for men and women. Clothes, shoes, accessories — refresh your wardrobe today!</p>
                <div class="slide-btns">
                    <a href="{{ url('fashion') }}" class="slide-btn" style="color:#7c3aed;">
                        Shop Fashion <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="slide-img">
                <img src="{{ asset('assets/images/products/jacket-1.jpg') }}" alt="Fashion">
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp

    <!-- Dynamic Top Deal Slides -->
    @isset($heroTopDeals)
    @foreach($heroTopDeals as $index => $deal)
    @php
        $discountPct = $deal->total_price > 0 ? round((($deal->total_price - $deal->final_price) / $deal->total_price) * 100) : 0;
        $savings = $deal->total_price - $deal->final_price;
        // Cycle through vibrant gradient themes for deals
        $dealThemes = [
            'linear-gradient(135deg, #7f1d1d 0%, #b91c1c 40%, #dc2626 70%, #ef4444 100%)',
            'linear-gradient(135deg, #78350f 0%, #b45309 40%, #d97706 70%, #f59e0b 100%)',
            'linear-gradient(135deg, #14532d 0%, #15803d 40%, #22c55e 70%, #4ade80 100%)',
            'linear-gradient(135deg, #831843 0%, #be185d 40%, #ec4899 70%, #f472b6 100%)',
        ];
        $theme = $dealThemes[$index % count($dealThemes)];
    @endphp
    <div class="slide deal-slide" style="background: {{ $theme }};">
        <div class="container slide-content">
            <div class="slide-text">
                <div class="slide-tag pulse">
                    <ion-icon name="flame-outline"></ion-icon>
                    Top Deal #{{ $index + 1 }}
                </div>
                <h2 style="font-size:40px; line-height:1.15;">{{ Str::limit($deal->name, 50) }}</h2>
                <div class="deal-price">
                    <span class="current">₹{{ number_format($deal->final_price, 0) }}</span>
                    @if($deal->total_price > $deal->final_price)
                        <span class="original">₹{{ number_format($deal->total_price, 0) }}</span>
                    @endif
                </div>
                @if($savings > 0)
                <div class="deal-savings">
                    <ion-icon name="trending-down-outline"></ion-icon>
                    Save ₹{{ number_format($savings, 0) }}
                </div>
                @endif
                <p style="margin-bottom:20px; font-size:15px; opacity:0.85;">{{ Str::limit($deal->detail ?? 'Amazing deal from ' . $deal->company . '. Limited stock available!', 100) }}</p>
                <div class="slide-btns">
                    <a href="{{ url('product/'.$deal->id) }}" class="slide-btn">
                        Grab This Deal <ion-icon name="cart-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="slide-img">
                @if($discountPct > 0)
                <div class="deal-badge">-{{ $discountPct }}%</div>
                @endif
                <img src="{{ asset('uploads/products/'.$deal->image) }}" alt="{{ $deal->name }}" onerror="this.src='{{ asset('assets/images/products/mobile1.jpg') }}'">
            </div>
        </div>
    </div>
    @php $slideIndex++; @endphp
    @endforeach
    @endisset

    <!-- Navigation Arrows -->
    <div class="slider-arrow prev" onclick="changeSlide(-1)">
        <ion-icon name="chevron-back-outline"></ion-icon>
    </div>
    <div class="slider-arrow next" onclick="changeSlide(1)">
        <ion-icon name="chevron-forward-outline"></ion-icon>
    </div>

    <!-- Dots (dynamically generated) -->
    <div class="slider-dots" id="slider-dots">
        <!-- Dots will be generated by JavaScript based on slide count -->
    </div>
</section>

<!-- ================= HOT DEALS ================= -->
<section class="section-container deals-section">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="flame" style="color:var(--wk-pink)"></ion-icon>
            Top Deals of the Week
        </h3>
        <a href="{{ url('/') }}" class="view-all">View All &rarr;</a>
    </div>

    <div class="product-grid">
        @forelse($top_deals->products as $product)
            <div class="product-card">
                @if($product->total_price > $product->final_price)
                    <span class="discount">-{{ round((($product->total_price - $product->final_price) / $product->total_price) * 100) }}%</span>
                @endif
                <div class="product-img">
                    <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}" onerror="this.style.opacity='0'">
                </div>
                <div class="product-info">
                    <h3 title="{{ $product->name }}">{{ $product->name }}</h3>
                    <p class="store-name">
                        <ion-icon name="storefront-outline"></ion-icon>
                        {{ $product->company }}
                    </p>
                    <div class="price-box">
                        <span class="price-curr">₹{{ number_format($product->final_price, 2) }}</span>
                        @if($product->total_price > $product->final_price)
                            <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                        @endif
                    </div>
                    <a href="{{ url('product/'.$product->id) }}" class="card-btn">
                        <ion-icon name="cart-outline"></ion-icon> Add to Cart
                    </a>
                </div>
            </div>
        @empty
            <div class="product-card" style="grid-column: 1/-1; text-align:center;">
                <div class="product-info" style="padding: 24px;">
                    <h3 style="margin-bottom: 8px;">Top Deals Are Coming Soon</h3>
                    <p class="store-name" style="justify-content:center;">Admin is curating the best offers for you.</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

<!-- ================= ELECTRONICS ================= -->
<section class="section-container">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="flash-outline" style="color:var(--wk-yellow)"></ion-icon>
            Electronics &amp; Accessories
        </h3>
        <a href="{{ url('electronics') }}" class="view-all">View All &rarr;</a>
    </div>

    <div class="product-grid" id="slider">
        @foreach($electronics->products as $product)
            <div class="product-card">
                @if($product->total_price > $product->offer_price)
                    <span class="discount">-{{ round((($product->total_price - $product->offer_price) / $product->total_price) * 100) }}%</span>
                @endif
                <a href="{{ url('product/'.$product->id) }}" style="display:contents;">
                    <div class="product-img">
                        <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}" onerror="this.style.opacity='0'">
                    </div>
                    <div class="product-info">
                        <h3 title="{{ $product->name }}">{{ $product->name }}</h3>
                        <p class="store-name">
                            <ion-icon name="storefront-outline"></ion-icon>
                            {{ $product->company }}
                        </p>
                        <div class="price-box">
                            <span class="price-curr">₹{{ number_format($product->final_price, 2) }}</span>
                            @if($product->total_price > $product->final_price)
                                <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                            @endif
                        </div>
                        <div class="card-btn">
                            <ion-icon name="flash-outline"></ion-icon> Buy Now
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- ================= COMPUTERS & LAPTOPS ================= -->
<section class="section-container">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="laptop-outline"></ion-icon>
            Computers &amp; Laptops
        </h3>
        <a href="{{ url('computers') }}" class="view-all">View All &rarr;</a>
    </div>

    <div class="product-grid">
        @foreach($computers->products as $product)
            <div class="product-card">
                @if($product->total_price > $product->offer_price)
                    <span class="discount">-{{ round((($product->total_price - $product->offer_price) / $product->total_price) * 100) }}%</span>
                @endif
                <div class="product-img">
                    <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}" onerror="this.style.opacity='0'">
                </div>
                <div class="product-info">
                    <h3 title="{{ $product->name }}">{{ $product->name }}</h3>
                    <p class="store-name">
                        <ion-icon name="storefront-outline"></ion-icon>
                        {{ $product->company }}
                    </p>
                    <div class="price-box">
                            <span class="price-curr">₹{{ number_format($product->final_price, 2) }}</span>
                            @if($product->total_price > $product->final_price)
                                <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                            @endif
                    </div>
                    <a href="{{ url('product/'.$product->id) }}" class="card-btn">
                        <ion-icon name="flash-outline"></ion-icon> Buy Now
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- ================= MONITORS, KEYBOARD & MOUSE ================= -->
<section class="section-container">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="desktop-outline"></ion-icon>
            Monitors, Keyboard &amp; Mouse
        </h3>
        <a href="{{ url('computers') }}" class="view-all">View All &rarr;</a>
    </div>

    <div class="product-grid">
        @foreach($monitor_mouse as $data)
            @foreach($data->products as $product)
                <div class="product-card">
                    @if(isset($product->total_price) && $product->total_price > $product->offer_price)
                        <span class="discount">-{{ round((($product->total_price - $product->offer_price) / $product->total_price) * 100) }}%</span>
                    @endif
                    <a href="{{ url('product/'.$product->id) }}" style="display:contents;">
                        <div class="product-img">
                            <img src="{{ asset('uploads/products/'.$product->image) }}" alt="{{ $product->name }}" onerror="this.style.opacity='0'">
                        </div>
                        <div class="product-info">
                            <h3 title="{{ $product->name }}">{{ $product->name }}</h3>
                            <p class="store-name">
                                <ion-icon name="storefront-outline"></ion-icon>
                                {{ $product->company }}
                            </p>
                            <div class="price-box">
                                <span class="price-curr">₹{{ number_format($product->final_price, 2) }}</span>
                                @if(isset($product->total_price) && $product->total_price > $product->final_price)
                                    <span class="price-old">₹{{ number_format($product->total_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="card-btn">
                                <ion-icon name="flash-outline"></ion-icon> Buy Now
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endforeach
    </div>
</section>

<!-- ================= HOTELS & RESORTS ================= -->
<section class="section-container hotels-section">
    <div class="section-header">
        <h3 class="section-title">
            <ion-icon name="bed-outline" style="color:var(--wk-blue)"></ion-icon>
            Discover Your Next Favourite Stay
        </h3>
        <a href="{{ url('hotels-and-resorts') }}" class="view-all">Explore Stays &rarr;</a>
    </div>

    <div class="product-grid">
        @foreach($hotels as $hotel)
            <div class="product-card">
                <a href="{{ url('hotel-details/'.$hotel->id) }}" style="display:contents;">
                    <div class="product-img">
                        @php
                            $imgName = trim($hotel->image ?? '');
                            $imgPath = public_path('uploads/products/' . $imgName);
                            $imgAsset = file_exists($imgPath) && $imgName ? asset('uploads/products/' . $imgName) : asset('uploads/not_found.jpg');
                        @endphp
                        <img src="{{ $imgAsset }}" alt="{{ $hotel->name }}" onerror="this.style.opacity='0'">
                    </div>
                    <div class="product-info">
                        <h3 title="{{ $hotel->name }}">{{ $hotel->name }}</h3>
                        <p class="store-name">
                            <ion-icon name="location-outline"></ion-icon>
                            {{ $hotel->location ?? 'Location' }}
                        </p>
                        <div class="price-box">
                            <span class="price-curr">{{ $hotel->rating ?? '4.5' }} ⭐</span>
                            <span class="price-old" style="font-size:11px; text-decoration:none; color:var(--text-muted);">Rating</span>
                        </div>
                        <div class="card-btn book-btn">
                            <ion-icon name="calendar-outline"></ion-icon> Book Now
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- ================= JAVASCRIPT ================= -->
<script>
    // --- HERO SLIDER LOGIC (Dynamic) ---
    const sliderContainer = document.getElementById('hero-slider');
    const slides = sliderContainer.querySelectorAll('.slide');
    const dotsContainer = document.getElementById('slider-dots');
    let currentSlide = 0;
    let slideInterval;

    // Dynamically generate navigation dots
    function generateDots() {
        dotsContainer.innerHTML = '';
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = 'dot' + (index === 0 ? ' active' : '');
            dot.onclick = () => setSlide(index);
            dotsContainer.appendChild(dot);
        });
    }

    generateDots();
    const dots = dotsContainer.querySelectorAll('.dot');

    function showSlide(index) {
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;

        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function setSlide(index) {
        showSlide(index);
        resetInterval();
    }

    function changeSlide(direction) {
        setSlide(currentSlide + direction);
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 6000); // 6 seconds per slide
    }

    // Start auto-slide
    resetInterval();

    // Pause on hover
    sliderContainer.addEventListener('mouseenter', () => clearInterval(slideInterval));
    sliderContainer.addEventListener('mouseleave', resetInterval);

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') changeSlide(-1);
        if (e.key === 'ArrowRight') changeSlide(1);
    });

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    
    sliderContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    sliderContainer.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        if (touchStartX - touchEndX > swipeThreshold) {
            changeSlide(1); // Swipe left -> next
        } else if (touchEndX - touchStartX > swipeThreshold) {
            changeSlide(-1); // Swipe right -> prev
        }
    }

    // --- IMAGE ERROR HANDLING ---
    // Add a subtle placeholder for images that fail to load
    document.querySelectorAll('.product-img img').forEach(img => {
        img.addEventListener('error', function() {
            this.style.opacity = '0';
        });
        // Also handle already-errored images (cached errors)
        if (img.complete && img.naturalWidth === 0) {
            img.style.opacity = '0';
        }
    });
</script>

@endsection