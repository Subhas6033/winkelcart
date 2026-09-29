<style>
    .hero-slider-dynamic {
        position: relative;
        height: 520px;
        width: 100%;
        overflow: hidden;
        margin-bottom: 48px;
        border-radius: 0 0 32px 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .slide-dynamic {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.9s;
        padding: 0 5%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .slide-dynamic::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.15) 0%, transparent 40%), 
                    linear-gradient(90deg, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0) 100%);
        z-index: 0;
    }

    .slide-dynamic::after {
        content: '';
        position: absolute;
        top: 0; right: 0; width: 60%; height: 100%;
        background: linear-gradient(90deg, transparent 0%, rgba(0,0,0,0.15) 100%);
        z-index: 0;
    }

    .slide-dynamic.active {
        opacity: 1;
        visibility: visible;
        z-index: 1;
    }

    .slide-content-dynamic {
        max-width: 1280px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 50px;
        color: white;
        position: relative;
        z-index: 2;
    }

    .slide-text-dynamic { 
        flex: 1; 
        max-width: 580px; 
        animation: slideInLeft 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        opacity: 0;
    }

    .slide-dynamic.active .slide-text-dynamic { animation: slideInLeft 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    @keyframes slideInLeft { from { opacity: 0; transform: translateX(-60px); } to { opacity: 1; transform: translateX(0); } }

    .slide-tag-dynamic { 
        background: rgba(255,255,255,0.2); 
        backdrop-filter: blur(10px);
        border-radius: 50px; 
        padding: 10px 24px; 
        color: white; 
        font-size: 12px; 
        font-weight: 700; 
        margin-bottom: 16px; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    
    .slide-tag-dynamic.pulse { animation: tagPulse 2s infinite; }
    @keyframes tagPulse { 0%,100% { box-shadow: 0 8px 32px rgba(0,0,0,0.1);} 50% { box-shadow: 0 8px 40px rgba(255,255,255,0.2); } }

    .slide-text-dynamic h2 { 
        font-size: 64px; 
        font-weight: 900; 
        margin-bottom: 18px; 
        line-height: 1.1; 
        letter-spacing: -1px; 
        text-shadow: 0 8px 30px rgba(0,0,0,0.3);
        word-break: break-word;
    }
    
    .slide-text-dynamic h2 span { 
        background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%); 
        -webkit-background-clip: text; 
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .slide-text-dynamic p { 
        font-size: 18px; 
        margin-bottom: 28px; 
        opacity: 0.95; 
        line-height: 1.7;
        font-weight: 300;
    }

    .slide-btns-dynamic { display: flex; gap: 14px; flex-wrap: wrap; }
    
    .slide-btn-dynamic { 
        padding: 14px 36px; 
        background: white; 
        color: #1a1a1a; 
        border-radius: 50px; 
        font-weight: 700; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.25); 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
        border: none;
        font-size: 15px;
    }
    
    .slide-btn-dynamic:hover { 
        transform: translateY(-6px) scale(1.05);
        box-shadow: 0 15px 40px rgba(0,0,0,0.35);
        color: #1a1a1a; 
    }
    
    .slide-btn-dynamic.outline { 
        background: rgba(255,255,255,0.15); 
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.6); 
        color: white; 
    }
    
    .slide-btn-dynamic.outline:hover {
        background: rgba(255,255,255,0.25);
        border-color: white;
    }

    .slide-img-dynamic { 
        width: 420px; 
        height: 380px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        position: relative;
        animation: slideInRight 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        opacity: 0;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
        overflow: hidden;
        padding: 20px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.2);
    }

    .slide-dynamic.active .slide-img-dynamic { animation: slideInRight 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(60px) scale(0.9); } to { opacity: 1; transform: translateX(0) scale(1); } }

    .slide-img-dynamic img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover;
        object-position: center;
        animation: float 4s ease-in-out infinite;
        filter: drop-shadow(0 10px 30px rgba(0,0,0,0.2));
        border-radius: 15px;
    }

    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }

    .deal-badge-dynamic { 
        position: absolute; 
        top: 12px; 
        right: 12px; 
        background: rgba(255,59,48,0.85); 
        backdrop-filter: blur(10px);
        color: #ffffff; 
        border-radius: 50px; 
        padding: 10px 16px; 
        font-weight: 800; 
        font-size: 13px;
        box-shadow: 0 8px 24px rgba(255,59,48,0.3);
    }
    
    .deal-price-dynamic { 
        display: flex; 
        align-items: baseline; 
        gap: 12px; 
        margin: 16px 0; 
    }
    
    .deal-price-dynamic .current { 
        font-size: 48px; 
        font-weight: 900;
        text-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .deal-price-dynamic .original { 
        font-size: 22px; 
        color: rgba(255,255,255,0.8); 
        text-decoration: line-through;
    }
    
    .deal-savings-dynamic { 
        background: rgba(76, 175, 80, 0.8); 
        backdrop-filter: blur(10px);
        padding: 10px 16px; 
        border-radius: 10px; 
        color: #fff; 
        font-weight: 700; 
        font-size: 14px;
        box-shadow: 0 8px 24px rgba(76, 175, 80, 0.3);
    }

    .slider-arrow-dynamic { 
        position: absolute; 
        top: 50%; 
        transform: translateY(-50%); 
        width: 52px; 
        height: 52px; 
        border-radius: 50%; 
        background: rgba(255,255,255,0.2); 
        backdrop-filter: blur(10px);
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: white; 
        cursor: pointer; 
        z-index: 5; 
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 2px solid rgba(255,255,255,0.3);
        font-size: 24px;
        font-weight: bold;
    }
    
    .slider-arrow-dynamic:hover { 
        background: rgba(255,255,255,0.35); 
        transform: translateY(-50%) scale(1.15);
        border-color: white;
        box-shadow: 0 12px 40px rgba(0,0,0,0.2);
    }
    
    .slider-arrow-dynamic.prev { left: 20px; }
    .slider-arrow-dynamic.next { right: 20px; }

    .slider-dots-dynamic { 
        position: absolute; 
        left: 50%; 
        bottom: 20px; 
        transform: translateX(-50%); 
        display: flex; 
        gap: 12px; 
        z-index: 3;
    }
    
    .dot-dynamic { 
        width: 12px; 
        height: 12px; 
        border-radius: 999px; 
        background: rgba(255,255,255,0.4); 
        cursor: pointer; 
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 2px solid rgba(255,255,255,0.2);
    }
    
    .dot-dynamic:hover { background: rgba(255,255,255,0.6); }
    .dot-dynamic.active { 
        width: 32px; 
        background: white;
        border-color: white;
        box-shadow: 0 4px 12px rgba(255,255,255,0.3);
    }

    .skeleton-dynamic { 
        background: linear-gradient(-90deg, #e8e8e8, #f5f5f5, #e8e8e8); 
        background-size: 200% 100%; 
        animation: loading 2s infinite; 
    }
    @keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    @media (max-width: 900px) { 
        .slide-content-dynamic { flex-direction: column; text-align: center; } 
        .slide-img-dynamic { display: none; } 
        .slide-text-dynamic h2 { font-size: 42px; } 
        .hero-slider-dynamic { height: 380px; border-radius: 0 0 24px 24px; } 
    }
    @media (max-width: 768px) { 
        .hero-slider-dynamic { height: 340px; margin-bottom: 36px; } 
        .slider-arrow-dynamic { width: 44px; height: 44px; font-size: 20px; }
    }
    @media (max-width: 600px) { 
        .hero-slider-dynamic { height: 320px; border-radius: 0 0 16px 16px; } 
        .slide-btn-dynamic { width: 100%; } 
        .slide-text-dynamic h2 { font-size: 28px; }
    }
    @media (max-width: 480px) { 
        .hero-slider-dynamic { height: 300px; margin-bottom: 20px; } 
        .slide-dynamic { padding: 0 15px; } 
        .slide-text-dynamic h2 { font-size: 22px; } 
        .slide-text-dynamic p { font-size: 14px; }
        .slide-text-dynamic { max-width: 100%; }
        .dot-dynamic { width: 10px; height: 10px; } 
        .dot-dynamic.active { width: 24px; } 
        .slider-arrow-dynamic { width: 40px; height: 40px; font-size: 18px; }
        .slide-tag-dynamic { font-size: 10px; padding: 8px 16px; }
    }

    .slide-text-dynamic h2 { animation: textReveal 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s forwards; opacity: 0; }
    @keyframes textReveal { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .slide-text-dynamic p { animation: textReveal 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s forwards; opacity: 0; }
    .slide-btns-dynamic { animation: textReveal 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.6s forwards; opacity: 0; }
</style>

<section class="hero-slider-dynamic skeleton-dynamic" id="hero-slider-dynamic">
    <div class="slider-dots-dynamic" id="hero-dots-dynamic"></div>
    <div class="slider-arrow-dynamic prev" id="prev-dynamic">❮</div>
    <div class="slider-arrow-dynamic next" id="next-dynamic">❯</div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const category = getCategoryFromRoute();
        const apiUrl = `/api/hero-products${category ? '/' + category : ''}`;
        
        fetch(apiUrl)
            .then(response => response.json())
            .then(products => {
                if (!products || products.length === 0) {
                    renderFallbackSlides();
                } else {
                    renderSlides(products);
                }
                initializeSlider();
            })
            .catch(error => {
                renderFallbackSlides();
                initializeSlider();
            });

        function getCategoryFromRoute() {
            const path = window.location.pathname.split('/').filter(x => x);
            const categoryMap = {
                'electronics': 'Electronics',
                'mobile': 'Mobile',
                'appliances': 'Appliances',
                'fashion': 'Fashion',
                'hotels-and-resorts': 'Hotels & Resorts',
                'cosmetics': 'Cosmetics',
                'computers': 'Computers'
            };
            return categoryMap[path[0]] || null;
        }

        function getGradientForCategory(category) {
            const categoryLower = (category || '').toLowerCase();
            const gradients = {
                'fashion': 'linear-gradient(135deg, #ec4899 0%, #db2777 40%, #be185d 70%, #9d174d 100%)',
                'electronics': 'linear-gradient(135deg, #0369a1 0%, #0284c7 40%, #0ea5e9 70%, #38bdf8 100%)',
                'mobile': 'linear-gradient(135deg, #6d28d9 0%, #7c3aed 40%, #a78bfa 70%, #c4b5fd 100%)',
                'appliances': 'linear-gradient(135deg, #c2410c 0%, #d97706 40%, #f59e0b 70%, #fbbf24 100%)',
                'hotels & resorts': 'linear-gradient(135deg, #78350f 0%, #92400e 40%, #b45309 70%, #d97706 100%)',
                'cosmetics': 'linear-gradient(135deg, #db2777 0%, #ec4899 40%, #f472b6 70%, #fbcfe8 100%)',
                'computers': 'linear-gradient(135deg, #0f766e 0%, #14919b 40%, #06b6d4 70%, #22d3ee 100%)'
            };
            return gradients[categoryLower] || 'linear-gradient(135deg, #667eea 0%, #764ba2 40%, #f093fb 100%)';
        }

        function renderFallbackSlides() {
            const category = getCategoryFromRoute();
            const categoryImages = {
                'Electronics': [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1600',
                    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=1600',
                    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1600'
                ],
                'Mobile': [
                    'https://images.unsplash.com/photo-1556656793-08538906a9f8?q=80&w=1600',
                    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1600',
                    'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?q=80&w=1600' // Added 1
                ],
                'Appliances': [
                    'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?q=80&w=1600',
                    'https://images.unsplash.com/photo-1585771724684-38269d6639fd?q=80&w=1600',
                    'https://images.unsplash.com/photo-1556228578-8c89e6adf883?q=80&w=1600'
                ],
                'Hotels & Resorts': [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600',
                    'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?q=80&w=1600',
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1600' // Added 1
                ],
                'Cosmetics': [
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=1600',
                    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=1600',
                    'https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=1600',
                    
                ],
                'Computers': [
                    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=1600',
                    'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1600',
                    'https://images.unsplash.com/photo-1588872657840-4e06bad49c8d?q=80&w=1600'
                ]
            };

            const images = categoryImages[category] || [
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1600',
                'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=1600',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1600'
            ];

            const fallbackSlides = [
                {
                    title: category ? `Discover ${category}` : 'Shop Smart, Live Better',
                    description: 'Explore our premium collection of curated products from trusted sellers.',
                    category: category || 'all',
                    image: images[0],
                    price: 'Browse Deals',
                    discount: 0,
                    cta_link: '#',
                    is_trending: false
                },
                {
                    title: 'Exclusive Offers',
                    description: 'Get amazing discounts on your favorite products.',
                    category: category || 'all',
                    image: images[1] || images[0],
                    price: 'Shop Now',
                    discount: 0,
                    cta_link: '#',
                    is_trending: true
                },
                {
                    title: 'Premium Quality',
                    description: 'Handpicked products from verified sellers. Quality assured.',
                    category: category || 'all',
                    image: images[2] || images[0],
                    price: 'Explore',
                    discount: 0,
                    cta_link: '#',
                    is_trending: false
                }
            ];
            renderSlides(fallbackSlides);
        }

        function renderSlides(products) {
            const sliderContainer = document.getElementById('hero-slider-dynamic');
            sliderContainer.classList.remove('skeleton-dynamic');
            sliderContainer.innerHTML = '';
            
            const dotsContainer = document.createElement('div');
            dotsContainer.className = 'slider-dots-dynamic';
            dotsContainer.id = 'hero-dots-dynamic';

            products.forEach((product, index) => {
                const gradient = getGradientForCategory(product.category);
                const slide = document.createElement('div');
                slide.className = `slide-dynamic ${index === 0 ? 'active' : ''}`;
                
                const imageUrl = (product.image && product.image !== '') 
                    ? product.image 
                    : 'https://via.placeholder.com/1600x900?text=Product+Image';
                
                // Fixed background string
                slide.style.background = `url("${imageUrl}"), ${gradient}`;
                slide.style.backgroundBlendMode = 'overlay';
                slide.style.backgroundSize = 'cover';
                
                const priceDisplay = typeof product.price === 'number' 
                    ? `₹${parseInt(product.price).toLocaleString('en-IN')}`
                    : product.price;

                const buttonText = product.price && product.price !== 'Browse Deals' ? 'Shop Now' : 'Explore';
                
                slide.innerHTML = `
                    <div class="container slide-content-dynamic">
                        <div class="slide-text-dynamic">
                            <div class="slide-tag-dynamic pulse">
                                <ion-icon name="sparkles-outline"></ion-icon>
                                ${product.is_trending ? 'Trending Now' : 'Featured Deal'}
                            </div>
                            <h2>${product.title}</h2>
                            <p>${product.description || 'Premium quality products curated just for you.'}</p>
                            ${product.price && typeof product.price === 'number' ? `
                                <div class="deal-price-dynamic">
                                    <span class="current">${priceDisplay}</span>
                                    ${product.offer_price ? `<span class="original">₹${parseInt(product.offer_price).toLocaleString('en-IN')}</span>` : ''}
                                    ${product.discount > 0 ? `<span class="deal-savings-dynamic">${product.discount}% OFF</span>` : ''}
                                </div>
                            ` : ''}
                            <div class="slide-btns-dynamic">
                                <a href="${product.cta_link || '#'}" class="slide-btn-dynamic">
                                    ${buttonText} <ion-icon name="arrow-forward-outline"></ion-icon>
                                </a>
                                ${product.is_trending ? '<a href="#" class="slide-btn-dynamic outline"><ion-icon name="trending-up-outline"></ion-icon> Trending</a>' : ''}
                            </div>
                        </div>
                        <div class="slide-img-dynamic">
                            <img src="${imageUrl}" alt="${product.title}" loading="lazy">
                        </div>
                    </div>
                `;
                sliderContainer.appendChild(slide);

                const dot = document.createElement('div');
                dot.className = `dot-dynamic ${index === 0 ? 'active' : ''}`;
                dot.onclick = () => goToSlide(index);
                dotsContainer.appendChild(dot);
            });

            sliderContainer.appendChild(dotsContainer);
            sliderContainer.appendChild(createArrow('prev'));
            sliderContainer.appendChild(createArrow('next'));
        }

        function createArrow(direction) {
            const arrow = document.createElement('div');
            arrow.className = `slider-arrow-dynamic ${direction}`;
            arrow.id = `${direction}-dynamic`;
            arrow.textContent = direction === 'prev' ? '❮' : '❯';
            arrow.onclick = direction === 'prev' ? () => prevSlide() : () => nextSlide();
            return arrow;
        }

        let currentSlide = 0;
        let autoplayInterval;

        function initializeSlider() {
            startAutoplay();
        }

        function updateSlides() {
            const slides = document.querySelectorAll('.slide-dynamic');
            const dots = document.querySelectorAll('.dot-dynamic');
            if(!slides.length) return;
            slides.forEach((slide, idx) => slide.classList.toggle('active', idx === currentSlide));
            dots.forEach((dot, idx) => dot.classList.toggle('active', idx === currentSlide));
        }

        function goToSlide(index) {
            const slides = document.querySelectorAll('.slide-dynamic');
            currentSlide = (index + slides.length) % slides.length;
            updateSlides();
            resetAutoplay();
        }

        window.nextSlide = function() { goToSlide(currentSlide + 1); }
        window.prevSlide = function() { goToSlide(currentSlide - 1); }

        function startAutoplay() {
            autoplayInterval = setInterval(window.nextSlide, 5000);
        }

        function resetAutoplay() {
            clearInterval(autoplayInterval);
            startAutoplay();
        }
    });
</script>