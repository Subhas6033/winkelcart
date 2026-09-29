<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to WinkelKart!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .logo-text {
            font-size: 36px;
            margin-bottom: 15px;
        }
        .logo-text .yellow {
            color: #fdd835;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .features {
            margin: 25px 0;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .feature-text h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 16px;
        }
        .feature-text p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .cta-section {
            text-align: center;
            padding: 20px;
            background: #e8f5e9;
            border-radius: 10px;
            margin: 25px 0;
        }
        .cta-section h3 {
            margin: 0 0 15px 0;
            color: #2e7d32;
        }
        .btn {
            display: inline-block;
            padding: 14px 35px;
            background: #43a047;
            color: white !important;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
        }
        .btn:hover {
            background: #2e7d32;
        }
        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .category-tag {
            background: #fff;
            border: 2px solid #43a047;
            color: #43a047;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
        .footer a {
            color: #43a047;
            text-decoration: none;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo-text">
                🛒 Winkel<span class="yellow">Kart</span>
            </div>
            <h1>Welcome Aboard! 🎉</h1>
            <p>India's Own Shopping Destination</p>
        </div>
        
        <div class="content">
            <p class="greeting">Hello, {{ $user->name }}! 👋</p>
            
            <p>Welcome to WinkelKart! We're thrilled to have you join our community of smart shoppers. Your account has been created successfully and you're all set to start shopping!</p>
            
            <div class="features">
                <div class="feature-item">
                    <span class="feature-icon">🛍️</span>
                    <div class="feature-text">
                        <h4>Shop Everything</h4>
                        <p>From electronics to groceries, fashion to hotels - find it all in one place!</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <span class="feature-icon">🚚</span>
                    <div class="feature-text">
                        <h4>Free Delivery</h4>
                        <p>Enjoy free delivery on orders above ₹499. Fast & reliable shipping!</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <span class="feature-icon">💯</span>
                    <div class="feature-text">
                        <h4>Genuine Products</h4>
                        <p>100% authentic products with easy returns & secure payments.</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <span class="feature-icon">🏨</span>
                    <div class="feature-text">
                        <h4>Book Hotels</h4>
                        <p>Plan your perfect getaway with our curated hotel collection!</p>
                    </div>
                </div>
            </div>
            
            <div class="categories">
                <span class="category-tag">💻 Computers</span>
                <span class="category-tag">📱 Electronics</span>
                <span class="category-tag">🛒 Groceries</span>
                <span class="category-tag">👕 Fashion</span>
                <span class="category-tag">🏨 Hotels</span>
            </div>
            
            <div class="cta-section">
                <h3>Ready to Start Shopping?</h3>
                <p style="color: #666; margin-bottom: 15px;">Discover amazing deals and exclusive offers!</p>
                <a href="{{ url('/') }}" class="btn">Start Shopping Now →</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Need help?</strong> Our support team is here for you 24/7!</p>
            <p>
                <a href="{{ url('/contact') }}">Contact Us</a> | 
                <a href="{{ url('/') }}">Visit Store</a>
            </p>
            <p style="margin-top: 15px;">© {{ date('Y') }} WinkelKart - Shop Smart, Live Better</p>
        </div>
    </div>
</body>
</html>
