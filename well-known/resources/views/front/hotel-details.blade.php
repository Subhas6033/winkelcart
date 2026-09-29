@extends('front.layouts.app')

@section('content')
<style>
    .hotel-details-bg {
        min-height: 100vh;
        padding: 40px 0;
        /* No background here, let app.blade.php handle it */
    }
    .hotel-details-container {
        background: rgba(255,255,255,0.97);
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        max-width: 900px;
        margin: 0 auto;
        padding: 36px 32px 32px 32px;
    }
    .hotel-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--wk-blue);
        margin-bottom: 10px;
    }
    .hotel-location {
        color: #388e3c;
        font-size: 1.1rem;
        margin-bottom: 18px;
    }
    .hotel-gallery {
        display: flex;
        gap: 18px;
        margin-bottom: 24px;
        overflow-x: auto;
    }
    .hotel-gallery img {
        border-radius: 12px;
        width: 180px;
        height: 120px;
        object-fit: cover;
        box-shadow: 0 2px 10px rgba(30,136,229,.10);
    }
    .hotel-info-table {
        width: 100%;
        margin-bottom: 18px;
        border-collapse: collapse;
    }
    .hotel-info-table th, .hotel-info-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .hotel-info-table th {
        background: #e0f2f1;
        font-weight: 700;
    }
    .hotel-desc {
        margin-bottom: 18px;
        color: #444;
    }
    .hotel-amenities {
        margin-bottom: 18px;
    }
    .hotel-amenities span {
        display: inline-block;
        background: #e8f5e9;
        color: #388e3c;
        border-radius: 8px;
        padding: 6px 14px;
        margin: 4px 6px 4px 0;
        font-size: 0.98rem;
        font-weight: 500;
    }
    .hotel-book-form {
        background: #f1f8e9;
        border-radius: 12px;
        padding: 24px 18px;
        margin-top: 18px;
    }
    .hotel-book-form label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }
    .hotel-book-form input, .hotel-book-form select {
        width: 100%;
        padding: 8px 10px;
        border-radius: 6px;
        border: 1px solid #bdbdbd;
        margin-bottom: 14px;
    }
    .hotel-book-btn {
        width: 100%;
        padding: 14px 0;
        background: var(--wk-blue);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        margin-top: 8px;
        transition: .2s;
        cursor: pointer;
    }
    .hotel-book-btn:hover {
        background: #1565c0;
    }

    /* ================= MOBILE RESPONSIVE ================= */
    @media (max-width: 768px) {
        .hotel-details-bg {
            padding: 20px 0;
        }
        .hotel-details-container {
            padding: 20px 16px;
            margin: 0 10px;
            border-radius: 12px;
        }
        .hotel-title {
            font-size: 1.5rem;
        }
        .hotel-location {
            font-size: 0.95rem;
        }
        .hotel-gallery {
            gap: 10px;
            padding-bottom: 8px;
        }
        .hotel-gallery img {
            width: 140px;
            height: 100px;
            flex-shrink: 0;
        }
        .hotel-info-table th, .hotel-info-table td {
            padding: 8px 6px;
            font-size: 0.9rem;
        }
        .hotel-amenities span {
            padding: 5px 10px;
            margin: 3px 4px 3px 0;
            font-size: 0.85rem;
        }
        .hotel-book-form {
            padding: 16px 14px;
        }
    }

    @media (max-width: 480px) {
        .hotel-details-container {
            padding: 16px 12px;
            margin: 0 8px;
        }
        .hotel-title {
            font-size: 1.3rem;
        }
        .hotel-gallery img {
            width: 120px;
            height: 85px;
        }
        .hotel-book-btn {
            font-size: 1rem;
            padding: 12px 0;
        }
    }
    
    /* Samsung Galaxy (360px) and similar devices */
    @media (max-width: 400px) {
        .hotel-details-bg {
            padding: 12px 0;
        }
        .hotel-details-container {
            padding: 14px 10px;
            margin: 0 6px;
            border-radius: 10px;
        }
        .hotel-title {
            font-size: 1.15rem;
            line-height: 1.3;
        }
        .hotel-location {
            font-size: 0.85rem;
            margin-bottom: 12px;
        }
        .hotel-gallery {
            gap: 8px;
            margin-bottom: 16px;
        }
        .hotel-gallery img {
            width: 100px;
            height: 70px;
            border-radius: 8px;
        }
        .hotel-info-table th, .hotel-info-table td {
            padding: 6px 4px;
            font-size: 0.82rem;
        }
        .hotel-amenities span {
            padding: 4px 8px;
            margin: 2px 3px 2px 0;
            font-size: 0.78rem;
        }
        .hotel-book-form {
            padding: 12px 10px;
            border-radius: 10px;
        }
        .hotel-book-form label {
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        .hotel-book-form input, .hotel-book-form select {
            padding: 8px;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .hotel-book-btn {
            font-size: 0.95rem;
            padding: 10px 0;
            border-radius: 8px;
        }
        .hotel-desc {
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 14px;
        }
    }
    
    /* Very small phones (359px and below) */
    @media (max-width: 359px) {
        .hotel-details-bg {
            padding: 8px 0;
        }
        .hotel-details-container {
            padding: 12px 8px;
            margin: 0 4px;
            border-radius: 8px;
        }
        .hotel-title {
            font-size: 1rem;
        }
        .hotel-location {
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        .hotel-gallery {
            gap: 6px;
            margin-bottom: 12px;
        }
        .hotel-gallery img {
            width: 85px;
            height: 60px;
            border-radius: 6px;
        }
        .hotel-info-table th, .hotel-info-table td {
            padding: 5px 3px;
            font-size: 0.75rem;
        }
        .hotel-amenities span {
            padding: 3px 6px;
            font-size: 0.72rem;
        }
        .hotel-book-form {
            padding: 10px 8px;
        }
        .hotel-book-form label {
            font-size: 0.85rem;
        }
        .hotel-book-form input, .hotel-book-form select {
            padding: 7px;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }
        .hotel-book-btn {
            font-size: 0.9rem;
            padding: 8px 0;
        }
        .hotel-desc {
            font-size: 0.85rem;
        }
    }
    
    /* Galaxy Fold and ultra small screens */
    @media (max-width: 280px) {
        .hotel-details-container {
            padding: 10px 6px;
            margin: 0 2px;
        }
        .hotel-title {
            font-size: 0.9rem;
        }
        .hotel-gallery {
            flex-wrap: wrap;
            justify-content: center;
        }
        .hotel-gallery img {
            width: 75px;
            height: 55px;
        }
        .hotel-info-table th, .hotel-info-table td {
            font-size: 0.7rem;
        }
        .hotel-amenities span {
            font-size: 0.68rem;
        }
    }
</style>
<div class="hotel-details-bg">
    <div class="hotel-details-container">
        <div class="hotel-title">{{ $hotel->name ?? 'Hotel Name' }}</div>
        <div class="hotel-location"><i class="fa fa-map-marker-alt"></i> {{ $hotel->location ?? 'Location not specified' }}</div>
        <div class="hotel-gallery">
            @if(!empty($hotel->gallery) && is_array($hotel->gallery) && count($hotel->gallery))
                @foreach($hotel->gallery as $img)
                    @php $img = trim($img); @endphp
                    @php $imgPath = public_path('uploads/products/' . $img); @endphp
                    <img src="{{ file_exists($imgPath) && $img ? asset('uploads/products/' . $img) : asset('uploads/not_found.jpg') }}" alt="Hotel View">
                @endforeach
            @else
                @php
                    $mainImg = trim($hotel->image ?? '');
                    $mainImgPath = public_path('uploads/products/' . $mainImg);
                    $mainAsset = file_exists($mainImgPath) && $mainImg ? asset('uploads/products/' . $mainImg) : asset('uploads/not_found.jpg');
                @endphp
                <img src="{{ $mainAsset }}" alt="Hotel View">
            @endif
        </div>
        <div class="hotel-desc">
            {{ $hotel->desc ?? 'No description available.' }}
        </div>
        <table class="hotel-info-table">
            <tr><th>Check-in</th><td>{{ $hotel->checkin_time ?? '2:00 PM' }}</td></tr>
            <tr><th>Check-out</th><td>{{ $hotel->checkout_time ?? '12:00 PM' }}</td></tr>
            <tr><th>Rating</th><td>{{ $hotel->rating ?? '4.8/5' }}</td></tr>
            <tr><th>Contact</th><td>{{ $hotel->contact ?? '+91 22 6665 3366' }}</td></tr>
        </table>
        <div class="hotel-amenities">
            @if(!empty($hotel->amenities))
                @foreach($hotel->amenities as $amenity)
                    <span>{{ $amenity }}</span>
                @endforeach
            @else
                <span>Free WiFi</span>
                <span>Swimming Pool</span>
                <span>Spa & Wellness</span>
                <span>Airport Shuttle</span>
                <span>Restaurant</span>
                <span>Bar</span>
                <span>Fitness Center</span>
                <span>Room Service</span>
                <span>Parking</span>
                <span>Family Rooms</span>
            @endif
        </div>
        @if(session('success'))
            <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 10px; margin-bottom: 15px; font-weight:600;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                <i class="fa fa-exclamation-circle"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @php
            $rooms = collect($hotel->rooms ?? []);
        @endphp
        <form class="hotel-book-form" id="booking-form" action="{{ $rooms->count() > 0 ? route('booking.store', $rooms->first()->id) : '#' }}" method="POST">
            @csrf
            <label for="checkin">Check-in Date</label>
            <input type="date" id="checkin" name="check_in" required min="{{ date('Y-m-d') }}" value="{{ old('check_in') }}">
            <label for="checkout">Check-out Date</label>
            <input type="date" id="checkout" name="check_out" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('check_out') }}">
            <label for="guests">Guests</label>
            <select id="guests" name="guests" required>
                @for($i=1;$i<=10;$i++)
                    <option value="{{ $i }}" {{ old('guests') == $i ? 'selected' : '' }}>{{ $i }} Guest{{ $i>1?'s':'' }}</option>
                @endfor
            </select>
            <label for="room">Room Type</label>
            <select id="room" name="room_id" required onchange="updateFormAction(this)">
                <option value="" selected disabled>Select a room type</option>
                @php
                    $standardTypes = ['Deluxe', 'Luxury', 'Normal', 'Couple', 'Dormitory', 'Suite', 'Family', 'Sea View'];
                @endphp
                @foreach($standardTypes as $type)
                    @php $typeRooms = $rooms->filter(fn($r) => strtolower($r->room_type) === strtolower($type)); @endphp
                    @if($typeRooms->count())
                        <optgroup label="{{ $type }}">
                            @foreach($typeRooms as $r)
                                <option value="{{ $r->id }}" 
                                    data-action="{{ route('booking.store', $r->id) }}"
                                    data-price="{{ $r->price }}"
                                    data-max-guests="{{ $r->max_guests }}"
                                    {{ old('room_id') == $r->id ? 'selected' : '' }}>
                                    {{ $type }} (Max: {{ $r->max_guests }}) - ₹{{ number_format($r->price, 2) }}/night
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
                @if($rooms->count() == 0)
                    <option disabled>No rooms available</option>
                @endif
            </select>

            <!-- Price Summary -->
            <div id="price-summary" style="display:none; margin: 15px 0; padding: 15px; background: #fff3e0; border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Price per Night:</span>
                    <span id="price-per-night">₹0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Number of Nights:</span>
                    <span id="num-nights">0</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem; border-top: 1px solid #ffcc80; padding-top: 8px;">
                    <span>Total:</span>
                    <span id="total-price" style="color: #e65100;">₹0.00</span>
                </div>
            </div>

            <div style="margin: 22px 0 18px 0; padding: 18px 16px 10px 16px; background: #e3f0fa; border-radius: 12px; box-shadow: 0 2px 8px rgba(30,136,229,0.07);">
                <span class="order-summary-label" style="display:block; margin-bottom:12px; font-weight:700; color:#1d4ed8; letter-spacing:0.2px;">Payment Method:</span>
                <div style="display: flex; flex-wrap: wrap; gap: 18px 28px; align-items: center;">
                    <label style="font-weight:500; display:flex; align-items:center; gap:7px; font-size:15px;">
                        <input type="radio" name="payment_method" value="razorpay" checked style="margin-right:4px; accent-color:#1d4ed8;"> Pay Online (Razorpay)
                    </label>
                    <label style="font-weight:500; display:flex; align-items:center; gap:7px; font-size:15px;">
                        <input type="radio" name="payment_method" value="cod" {{ old('payment_method') == 'cod' ? 'checked' : '' }} style="margin-right:4px; accent-color:#1d4ed8;"> Cash on Arrival
                    </label>
                </div>
            </div>
            @auth
                <button type="submit" class="hotel-book-btn" id="book-btn" {{ $rooms->count() == 0 ? 'disabled' : '' }}>Book Now</button>
            @else
                <a href="{{ route('login') }}" class="hotel-book-btn" style="display:block; text-align:center; text-decoration:none;">Login to Book</a>
                <p style="text-align: center; margin-top: 10px; color: #666; font-size: 0.9rem;">
                    Don't have an account? <a href="{{ route('register_buyer') }}" style="color: #1565c0;">Register here</a>
                </p>
            @endauth
        </form>

        <script>
            function updateFormAction(select) {
                const form = document.getElementById('booking-form');
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.action) {
                    form.action = selectedOption.dataset.action;
                }
                calculateTotal();
            }

            function calculateTotal() {
                const checkin = document.getElementById('checkin').value;
                const checkout = document.getElementById('checkout').value;
                const roomSelect = document.getElementById('room');
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                const priceSummary = document.getElementById('price-summary');
                
                if (checkin && checkout && selectedOption && selectedOption.value) {
                    const price = parseFloat(selectedOption.dataset.price) || 0;
                    const checkinDate = new Date(checkin);
                    const checkoutDate = new Date(checkout);
                    const nights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
                    
                    if (nights > 0) {
                        const total = price * nights;
                        document.getElementById('price-per-night').textContent = '₹' + price.toLocaleString('en-IN', {minimumFractionDigits: 2});
                        document.getElementById('num-nights').textContent = nights + ' night' + (nights > 1 ? 's' : '');
                        document.getElementById('total-price').textContent = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
                        priceSummary.style.display = 'block';
                    } else {
                        priceSummary.style.display = 'none';
                    }
                } else {
                    priceSummary.style.display = 'none';
                }
            }

            // Auto-update checkout min date when checkin changes
            document.getElementById('checkin').addEventListener('change', function() {
                const checkinDate = new Date(this.value);
                checkinDate.setDate(checkinDate.getDate() + 1);
                const minCheckout = checkinDate.toISOString().split('T')[0];
                document.getElementById('checkout').min = minCheckout;
                if (document.getElementById('checkout').value && document.getElementById('checkout').value <= this.value) {
                    document.getElementById('checkout').value = minCheckout;
                }
                calculateTotal();
            });

            document.getElementById('checkout').addEventListener('change', calculateTotal);
            document.getElementById('room').addEventListener('change', calculateTotal);

            // Validate guest count and handle Razorpay payment
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const roomSelect = document.getElementById('room');
                const guestsSelect = document.getElementById('guests');
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                
                if (selectedOption && selectedOption.dataset.maxGuests) {
                    const maxGuests = parseInt(selectedOption.dataset.maxGuests);
                    const selectedGuests = parseInt(guestsSelect.value);
                    
                    if (selectedGuests > maxGuests) {
                        alert('This room only accommodates ' + maxGuests + ' guest(s). Please select fewer guests or choose a different room.');
                        return false;
                    }
                }

                var btn = document.getElementById('book-btn');
                var formData = new FormData(form);
                var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

                btn.disabled = true;
                btn.textContent = 'Processing...';

                Swal.fire({
                    title: 'Processing...',
                    html: 'Please wait while we create your booking ⏳',
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
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success && data.payment_required && paymentMethod === 'razorpay') {
                        Swal.close();
                        WinkelKartPay.initiate({
                            type: 'booking',
                            reference_id: data.booking_id,
                            amount: data.amount,
                            onSuccess: function() { btn.disabled = false; btn.textContent = 'Book Now'; },
                            onFailure: function() { btn.disabled = false; btn.textContent = 'Book Now'; }
                        });
                    } else if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '🎉 Booking Confirmed!',
                            html: '<b>Your booking has been confirmed!</b>',
                            confirmButtonText: '👍 Awesome!',
                            confirmButtonColor: '#2d6a4f',
                            allowOutsideClick: false
                        }).then(function(result) {
                            if (result.isConfirmed && data.redirect) window.location.href = data.redirect;
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops!', text: data.message || 'Something went wrong.' });
                    }
                    btn.disabled = false;
                    btn.textContent = 'Book Now';
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Connection Error', text: 'Could not reach the server.' });
                    btn.disabled = false;
                    btn.textContent = 'Book Now';
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script src="{{ asset('js/razorpay-payment.js') }}"></script>

        <!-- Background Particles Canvas -->
        <canvas id="particles-canvas" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:-1;"></canvas>
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
            const particleColors = ['#43a047', '#a5d6a7', '#bdbdbd'];
            function resize() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width;
                canvas.height = height;
            }
            window.addEventListener('resize', resize);
            resize();
            function Particle() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.radius = 2 + Math.random() * 2;
                this.color = particleColors[Math.floor(Math.random() * particleColors.length)];
                this.vx = (Math.random() - 0.5) * 0.7;
                this.vy = (Math.random() - 0.5) * 0.7;
            }
            Particle.prototype.update = function() {
                this.x += this.vx;
                this.y += this.vy;
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;
            };
            Particle.prototype.draw = function() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, 2 * Math.PI);
                ctx.fillStyle = this.color;
                ctx.fill();
            };
            for (let i = 0; i < particleCount; i++) particles.push(new Particle());
            function animate() {
                ctx.clearRect(0, 0, width, height);
                for (let i = 0; i < particles.length; i++) {
                    particles[i].update();
                    particles[i].draw();
                    for (let j = i; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < connectionDistance) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = 'rgba(67,160,71,0.12)';
                            ctx.lineWidth = 1;
                            ctx.stroke();
                        }
                    }
                }
                requestAnimationFrame(animate);
            }
            animate();
        })();
        </script>
    </div>
</div>
@endsection