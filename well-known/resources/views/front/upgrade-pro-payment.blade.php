<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade to Pro Plan — WinkelKart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }

        .header {
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .plan-card {
            background: #f8f9ff;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .plan-name {
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .plan-price-per {
            color: #666;
            font-size: 14px;
        }

        .benefits {
            text-align: left;
            margin: 20px 0;
            padding: 20px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        .benefit {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
            color: #555;
        }

        .benefit:before {
            content: "✓";
            display: inline-block;
            width: 24px;
            height: 24px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: bold;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .pay-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 20px;
        }

        .pay-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .pay-btn:active {
            transform: translateY(0);
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            font-size: 12px;
            color: #999;
        }

        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .error.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚀 WinkelKart</div>
            <h1>Upgrade to Pro Plan</h1>
            <p class="subtitle">Unlock unlimited products & premium features</p>
        </div>

        <div class="plan-card">
            <div class="plan-name">Pro Seller Plan</div>
            <div class="plan-price">₹999<span class="plan-price-per">/year</span></div>
        </div>

        <div class="benefits">
            <div class="benefit">Unlimited product listings</div>
            <div class="benefit">Priority customer support</div>
            <div class="benefit">Advanced analytics & reports</div>
            <div class="benefit">Featured shop badge</div>
            <div class="benefit">Bulk order management</div>
        </div>

        <div class="error" id="errorMsg"></div>

        <button class="pay-btn" onclick="initiatePayment()">
            <span id="btnText">💳 Pay ₹999 & Upgrade</span>
            <span id="btnLoader" class="loading"><span class="spinner"></span></span>
        </button>

        <div class="security-badge">
            🔒 Secure payment powered by Razorpay
        </div>
    </div>

    <!-- Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        let paymentInProgress = false;

        function initiatePayment() {
            if (paymentInProgress) return;

            paymentInProgress = true;
            const btn = document.querySelector('.pay-btn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const errorMsg = document.getElementById('errorMsg');

            // Hide error and show loader
            errorMsg.classList.remove('show');
            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.classList.add('show');

            const options = {
                key: '{{ $razorpayKey }}',
                amount: {{ $amount }},
                currency: '{{ $currency }}',
                order_id: '{{ $orderId }}',
                name: 'WinkelKart',
                description: 'Pro Seller Subscription — ₹999/year',
                prefill: {
                    name: '{{ $userName }}',
                    email: '{{ $userEmail }}',
                    contact: '{{ $userPhone }}'
                },
                handler: function(response) {
                    handlePaymentSuccess(response);
                },
                modal: {
                    ondismiss: function() {
                        paymentInProgress = false;
                        btn.disabled = false;
                        btnText.style.display = 'inline';
                        btnLoader.classList.remove('show');
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }

        function handlePaymentSuccess(response) {
            const btn = document.querySelector('.pay-btn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const errorMsg = document.getElementById('errorMsg');

            // Send payment details to backend
            fetch('{{ route("seller.complete-upgrade") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Show success message and redirect
                    btnText.innerHTML = '✅ Upgrade Complete!';
                    btnLoader.classList.remove('show');
                    btn.style.background = '#4caf50';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    throw new Error(data.error || 'Upgrade failed');
                }
            })
            .catch(err => {
                paymentInProgress = false;
                errorMsg.textContent = err.message || 'Payment verification failed. Please contact support.';
                errorMsg.classList.add('show');
                btn.disabled = false;
                btnText.style.display = 'inline';
                btnLoader.classList.remove('show');
            });
        }
    </script>
</body>
</html>
