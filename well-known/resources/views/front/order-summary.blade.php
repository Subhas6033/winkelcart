@extends('front.layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f9fbe7 0%, #e8f5e9 100%) !important;
        min-height: 100vh;
    }
    .order-summary-bg {
        min-height: 100vh;
        padding: 40px 0;
    }
    .order-summary-container {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        max-width: 700px;
        margin: 0 auto;
        padding: 36px 32px 32px 32px;
    }
    .order-summary-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 24px;
        color: var(--wk-blue);
        text-align: center;
    }
    .order-summary-section {
        margin-bottom: 28px;
    }
    .order-summary-label {
        font-weight: 600;
        color: #333;
    }
    .order-summary-value {
        color: #444;
    }
    .order-summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }
    .order-summary-table th, .order-summary-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .order-summary-table th {
        background: #f1f8e9;
        font-weight: 700;
    }
    .order-summary-total {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--wk-green);
        text-align: right;
    }
    .order-summary-btn {
        width: 100%;
        padding: 14px 0;
        background: var(--wk-blue);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        margin-top: 18px;
        transition: .2s;
        cursor: pointer;
    }
    .order-summary-btn:hover {
        background: #1565c0;
    }
    .order-summary-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* SweetAlert custom styles */
    .swal2-popup-custom {
        border-radius: 24px !important;
        padding: 40px 32px !important;
        font-family: inherit !important;
    }
    .swal2-title-custom {
        font-size: 1.7rem !important;
        font-weight: 800 !important;
        color: #2d6a4f !important;
    }
    .swal2-confirm-custom {
        border-radius: 50px !important;
        padding: 10px 32px !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
    }
</style>

<div class="order-summary-bg">
    <div class="order-summary-container">
        <div class="order-summary-title">Order Summary</div>
        <div class="order-summary-section">
            <span class="order-summary-label">Product:</span>
            <span class="order-summary-value">{{ $product->name ?? 'Product Name' }}</span>
        </div>
        <div class="order-summary-section">
            <span class="order-summary-label">Price:</span>
            <span class="order-summary-value">₹{{ number_format($product->final_price ?? 0, 2) }}</span>
        </div>
        <div class="order-summary-section">
            <span class="order-summary-label">Quantity:</span>
            <span class="order-summary-value">1</span>
        </div>

        <form id="placeOrderForm" action="{{ url('place_order', $product->id ?? 1) }}" method="POST">
            @csrf
            <div class="order-summary-section">
                <span class="order-summary-label">Delivery Address:</span>
                @if(isset($hasCompleteAddress) && !$hasCompleteAddress)
                    <div class="alert alert-warning" style="margin-top: 8px; padding: 10px; font-size: 13px;">
                        <i class="fa fa-exclamation-triangle"></i> Please update your complete delivery address to place an order.
                        @if(optional($user->user_info)->address)
                            <br><strong>Your saved address:</strong> {{ $user->user_info->address }}
                        @endif
                    </div>
                    <a href="{{ route('profile.addresses') }}" class="btn btn-primary btn-sm" style="margin-top: 8px;">Update Delivery Address</a>
                @else
                    <input type="text" name="shipping_address" class="form-control" style="margin-top: 4px; margin-bottom: 4px; background: #f8f9fa;" value="{{ optional($user->user_info)->full_address ?? optional($user->user_info)->address ?? '' }}" placeholder="Enter your delivery address" required readonly>
                    <a href="{{ route('profile.addresses') }}" style="font-size: 12px; color: #1d4ed8;">Change Address</a>
                @endif
            </div>
            <div class="order-summary-section">
                <table class="order-summary-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $product->name ?? 'Product Name' }}</td>
                            <td>₹{{ number_format($product->final_price ?? 0, 2) }}</td>
                            <td>1</td>
                            <td>₹{{ number_format($product->final_price ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="order-summary-total">
                    Total: ₹{{ number_format($product->final_price ?? 0, 2) }}
                </div>
            </div>

            <!-- Shipping Cost Section (dynamic) -->
            <div id="shippingSection" class="order-summary-section" style="border-top:1px solid #eee; padding-top:12px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span class="order-summary-label">Shipping:</span>
                    <span id="shippingCostDisplay" style="color:#2d6a4f; font-weight:600;">⏳ Calculating...</span>
                </div>
                <div id="shippingEtaDisplay" style="font-size:12px; color:#888; margin-top:4px;"></div>
                <div id="freeShippingBadge" style="display:none; font-size:12px; color:#2d6a4f; margin-top:4px; font-weight:600;">🎉 Free Shipping Applied!</div>
            </div>

            <!-- No-service alert -->
            <div id="noServiceAlert" style="display:none; background:#fff3cd; border:1px solid #ffc107; border-radius:8px; padding:10px 14px; margin-bottom:12px; font-size:13px; color:#664d03;">
                ⚠️ <strong>Delivery Not Available</strong> — No courier service found for your pincode. Please <a href="{{ route('profile.addresses') }}">update your address</a>.
            </div>

            <!-- Grand Total -->
            <div id="grandTotalSection" class="order-summary-section" style="display:none; border-top:2px solid #2d6a4f; padding-top:12px;">
                <div class="order-summary-total" id="grandTotalDisplay"></div>
            </div>
            <input type="hidden" name="shipping_cost" id="hiddenShippingCostBuyNow" value="0">

            <!-- Payment Methods Section -->
            <div class="order-summary-section">
                <span class="order-summary-label">Payment Method:</span>
                <div style="margin-top: 10px;">
                    <label style="margin-right: 18px;">
                        <input type="radio" name="payment_method" value="razorpay" checked> Pay Online (Razorpay)
                    </label>
                    <label style="margin-right: 18px;">
                        <input type="radio" name="payment_method" value="cod"> Cash on Delivery
                    </label>
                </div>
            </div>
            @if(isset($hasCompleteAddress) && !$hasCompleteAddress)
                <a href="{{ route('profile.addresses') }}" class="order-summary-btn" style="display: inline-block; text-align: center; text-decoration: none; background: #ffc107; color: #000;">Add Address First</a>
            @else
                <button type="submit" class="order-summary-btn" id="placeOrderBtn">Place Order</button>
            @endif
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{ asset('js/razorpay-payment.js') }}"></script>
<script>
document.getElementById('placeOrderForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var form     = this;
    var btn      = document.getElementById('placeOrderBtn');
    var formData = new FormData(form);
    var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

    btn.disabled    = true;
    btn.textContent = 'Placing Order...';

    Swal.fire({
        title: 'Processing...',
        html: 'Please wait while we place your order ⏳',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: function () { Swal.showLoading(); }
    });

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success && data.payment_required && paymentMethod === 'razorpay') {
            Swal.close();
            WinkelKartPay.initiate({
                type: 'order',
                reference_id: data.order_id,
                amount: data.amount,
                onSuccess: function() { btn.disabled = false; btn.textContent = 'Place Order'; },
                onFailure: function() { btn.disabled = false; btn.textContent = 'Place Order'; }
            });
        } else if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '🎉 Order Placed!',
                html: '<b>' + (data.message || 'Your order has been placed successfully!') + '</b>',
                confirmButtonText: '👍 Awesome!',
                confirmButtonColor: '#2d6a4f',
                allowOutsideClick: false
            }).then(function(result) {
                if (result.isConfirmed && data.redirect) window.location.href = data.redirect;
            });
            form.reset();
        } else {
            Swal.fire({ icon: 'error', title: 'Oops!', text: data.message || 'Something went wrong.' });
        }
        btn.disabled = false;
        btn.textContent = 'Place Order';
    })
    .catch(function() {
        Swal.fire({ icon: 'error', title: 'Connection Error', text: 'Could not reach the server.' });
        btn.disabled = false;
        btn.textContent = 'Place Order';
    });
});
</script>
<script>
(function () {
    'use strict';
    var deliveryPincode = '{{ optional($user->user_info)->pincode ?? '' }}';
    var hasAddress      = {{ isset($hasCompleteAddress) && $hasCompleteAddress ? 'true' : 'false' }};
    var calcUrl         = '{{ route('shipping.calculate_product') }}';
    var csrfToken       = '{{ csrf_token() }}';
    var productId       = {{ $product->id ?? 'null' }};

    function getPaymentMethod() {
        var el = document.querySelector('input[name="payment_method"]:checked');
        return el ? el.value : 'razorpay';
    }

    function calculateShipping() {
        if (!hasAddress || !deliveryPincode || deliveryPincode.length !== 6 || !productId) {
            document.getElementById('shippingCostDisplay').textContent = 'Add complete address first.';
            return;
        }

        document.getElementById('shippingCostDisplay').textContent = '⏳ Calculating...';
        document.getElementById('grandTotalSection').style.display = 'none';
        document.getElementById('noServiceAlert').style.display = 'none';

        fetch(calcUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
                delivery_pincode: deliveryPincode,
                payment_method: getPaymentMethod()
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) {
                document.getElementById('shippingCostDisplay').textContent = data.message || 'Unavailable';
                return;
            }

            var placeBtn = document.getElementById('placeOrderBtn');

            if (data.free_shipping) {
                document.getElementById('shippingCostDisplay').textContent = 'FREE';
                document.getElementById('freeShippingBadge').style.display = 'block';
            } else {
                document.getElementById('shippingCostDisplay').textContent = data.formatted.shipping;
                document.getElementById('freeShippingBadge').style.display = 'none';
            }

            // ETA from first courier
            var eta = '';
            if (data.sellers && data.sellers.length > 0 && data.sellers[0].available_couriers && data.sellers[0].available_couriers.length > 0) {
                var c = data.sellers[0].available_couriers[0];
                if (c.estimated_delivery_days) eta = '📅 Est. delivery: ' + c.estimated_delivery_days + ' day(s)';
            }
            document.getElementById('shippingEtaDisplay').textContent = eta;

            // Grand total
            document.getElementById('grandTotalDisplay').textContent = 'Grand Total: ' + data.formatted.grand_total;
            document.getElementById('grandTotalSection').style.display = 'block';

            document.getElementById('hiddenShippingCostBuyNow').value = data.total_shipping;

            if (data.no_service) {
                document.getElementById('noServiceAlert').style.display = 'block';
                if (placeBtn) { placeBtn.disabled = true; placeBtn.style.opacity = '.5'; }
            } else {
                document.getElementById('noServiceAlert').style.display = 'none';
                if (placeBtn) { placeBtn.disabled = false; placeBtn.style.opacity = '1'; }
            }
        })
        .catch(function () {
            document.getElementById('shippingCostDisplay').textContent = 'Unavailable';
        });
    }

    calculateShipping();

    document.querySelectorAll('input[name="payment_method"]').forEach(function (radio) {
        radio.addEventListener('change', calculateShipping);
    });
}());
</script>
@endsection