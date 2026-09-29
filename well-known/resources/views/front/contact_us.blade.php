@extends('front.layouts.app')

@section('content')

<!-- SweetAlert2 CSS (for styling the popup) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    :root {
        --wk-green: #43a047;
        --wk-dark-green: #2e7d32;
        --wk-light-green: #e8f5e9;
        --wk-yellow: #fdd835;
        --wk-blue: #1e88e5;
        --text-main: #212121;
        --bg-body: #f9f9f9;
    }

    body { background-color: var(--bg-body) !important; font-family: 'Poppins', sans-serif; }

    /* ===== CONTACT HERO ===== */
    .contact-hero {
        background: linear-gradient(135deg, var(--wk-dark-green) 0%, var(--wk-green) 100%);
        padding: 60px 0 100px;
        text-align: center;
        color: white;
    }

    .contact-hero h1 { font-size: 36px; font-weight: 800; margin-bottom: 10px; }
    .contact-hero p { font-size: 16px; opacity: 0.9; }

    /* ===== CONTACT WRAPPER ===== */
    .contact-wrapper {
        max-width: 1100px;
        margin: -60px auto 50px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        display: flex;
        overflow: hidden;
        position: relative;
        z-index: 10;
    }

    /* ===== LEFT: INFO PANEL ===== */
    .contact-info {
        flex: 1;
        background: var(--wk-blue);
        color: white;
        padding: 50px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .contact-info h3 { font-size: 24px; margin-bottom: 30px; }

    .info-item { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 30px; }

    .info-item ion-icon {
        font-size: 24px;
        background: rgba(255,255,255,0.2);
        padding: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .info-item h4 { font-size: 18px; margin-bottom: 5px; }
    .info-item p { font-size: 14px; opacity: 0.9; line-height: 1.6; margin: 0; }

    .social-icons { display: flex; gap: 15px; margin-top: 20px; }

    .social-icons a {
        width: 40px; height: 40px;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 18px;
        transition: 0.3s;
        text-decoration: none;
    }

    .social-icons a:hover { background: white; color: var(--wk-blue); }

    /* ===== RIGHT: FORM PANEL ===== */
    .contact-form {
        flex: 1.5;
        padding: 50px 40px;
        overflow-x: auto;
    }

    .contact-form h2 {
        color: var(--wk-green);
        margin-bottom: 30px;
        font-size: 24px;
        font-weight: 700;
    }

    .form-group { margin-bottom: 20px; }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        transition: 0.3s;
        color: var(--text-main);
    }

    .form-control:focus {
        border-color: var(--wk-green);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 160, 71, 0.1);
    }

    .submit-btn {
        background: var(--wk-green);
        color: white;
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: 0.3s;
        font-family: 'Poppins', sans-serif;
    }

    .submit-btn:hover { background: var(--wk-dark-green); transform: translateY(-2px); }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .contact-wrapper { flex-direction: column; margin: 20px; }
        .contact-info, .contact-form { padding: 30px 20px; }
        .contact-hero { padding: 40px 15px 80px; }
        .contact-hero h1 { font-size: 28px; }
        .contact-form h2 { font-size: 20px; }
        .contact-info h3 { font-size: 20px; }
    }

    @media (max-width: 576px) {
        .contact-wrapper { margin: 15px; border-radius: 12px; }
        .contact-hero { padding: 30px 10px 60px; }
        .contact-hero h1 { font-size: 24px; }
        .contact-hero p { font-size: 14px; }
        .contact-info, .contact-form { padding: 25px 15px; }
        .contact-info h3 { font-size: 18px; margin-bottom: 20px; }
        .contact-form h2 { font-size: 18px; margin-bottom: 20px; }
        .info-item { gap: 12px; margin-bottom: 20px; }
        .info-item ion-icon { font-size: 20px; padding: 8px; }
        .info-item h4 { font-size: 16px; }
        .info-item p { font-size: 13px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-size: 13px; }
        .form-control { padding: 10px 12px; font-size: 14px; }
        .submit-btn { padding: 12px 20px; font-size: 15px; min-height: 48px; }
        .social-icons a { width: 44px; height: 44px; }
    }

    @media (max-width: 400px) {
        .contact-wrapper { margin: 10px; margin-top: -40px; }
        .contact-hero { padding: 25px 10px 50px; }
        .contact-hero h1 { font-size: 20px; }
        .contact-hero p { font-size: 13px; }
        .contact-info, .contact-form { padding: 20px 12px; }
        .contact-info h3 { font-size: 16px; }
        .contact-form h2 { font-size: 16px; }
        .info-item h4 { font-size: 14px; }
        .info-item p { font-size: 12px; }
        .form-group label { font-size: 12px; }
        .form-control { padding: 10px; font-size: 13px; border-radius: 6px; }
        .submit-btn { padding: 10px 15px; font-size: 14px; border-radius: 6px; }
        .social-icons { gap: 10px; }
    }
    
    @media (max-width: 359px) {
        .contact-wrapper { margin: 8px; margin-top: -35px; border-radius: 10px; }
        .contact-hero { padding: 20px 8px 40px; }
        .contact-hero h1 { font-size: 18px; }
        .contact-hero p { font-size: 12px; }
        .contact-info, .contact-form { padding: 16px 10px; }
        .contact-info h3 { font-size: 15px; margin-bottom: 16px; }
        .contact-form h2 { font-size: 15px; margin-bottom: 16px; }
        .info-item { gap: 10px; margin-bottom: 16px; }
        .info-item ion-icon { font-size: 18px; padding: 6px; }
        .info-item h4 { font-size: 13px; }
        .info-item p { font-size: 11px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { font-size: 11px; }
        .form-control { padding: 8px 10px; font-size: 12px; }
        .submit-btn { padding: 10px 12px; font-size: 13px; min-height: 44px; }
        .social-icons a { width: 38px; height: 38px; font-size: 16px; }
    }
    
    @media (max-width: 280px) {
        .contact-wrapper { margin: 6px; }
        .contact-hero h1 { font-size: 16px; }
        .contact-hero p { font-size: 11px; }
        .contact-info, .contact-form { padding: 14px 8px; }
        .contact-info h3, .contact-form h2 { font-size: 14px; }
        .info-item h4 { font-size: 12px; }
        .info-item p { font-size: 10px; }
        .form-control { padding: 8px; font-size: 12px; }
        .submit-btn { padding: 8px 10px; font-size: 12px; }
    }
</style>

<!-- ===== HERO ===== -->
<div class="contact-hero">
    <div>
        <h1>Get in Touch</h1>
        <p>Have questions? We'd love to hear from you.</p>
    </div>
</div>

<!-- ===== CONTACT WRAPPER ===== -->
<div class="contact-wrapper">

    <!-- Left: Contact Info -->
    <div class="contact-info">
        <div>
            <h3>Contact Information</h3>

            <div class="info-item">
                <ion-icon name="location"></ion-icon>
                <div>
                    <h4>Headquarters</h4>
                    <p>SRD Technologies India<br>Kulsum Complex, Station More,<br>Bagdogra, West Bengal - 734014</p>
                </div>
            </div>

            <div class="info-item">
                <ion-icon name="call"></ion-icon>
                <div>
                    <h4>Phone Number</h4>
                    <p>+91 6294693931</p>
                    <p>1800-WINKEL-HELP</p>
                </div>
            </div>

            <div class="info-item">
                <ion-icon name="mail"></ion-icon>
                <div>
                    <h4>Email Address</h4>
                    <p>info@winkelkart.com</p>
                    <p>support@winkelkart.com</p>
                </div>
            </div>
        </div>

        <div>
            <p style="margin-bottom:15px; font-size:14px;">Follow us:</p>
            <div class="social-icons">
                <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                <a href="#"><ion-icon name="logo-linkedin"></ion-icon></a>
            </div>
        </div>
    </div>

    <!-- Right: Form -->
    <div class="contact-form">
        <h2>Send us a Message</h2>

        <!-- ID added to form for JS targeting -->
        <form id="contactForm" action="{{ route('contact.send') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="business_name" class="form-control" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="contact_person" class="form-control" placeholder="+91 98765 43210" required>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control" placeholder="Street, City, Zip" required>
            </div>

            <div class="form-group">
                <label>State</label>
                <select name="state_type" class="form-control">
                    <option value="West Bengal" selected>West Bengal</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Karnataka">Karnataka</option>
                    <option value="Tamil Nadu">Tamil Nadu</option>
                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                    <option value="Bihar">Bihar</option>
                    <option value="Gujarat">Gujarat</option>
                </select>
            </div>

            <div class="form-group">
                <label>Country</label>
                <select name="country_type" class="form-control">
                    <option value="India" selected>India</option>
                    <option value="USA">United States</option>
                    <option value="UK">United Kingdom</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="UAE">UAE</option>
                </select>
            </div>

            <input type="submit" value="Send Message" class="submit-btn">

        </form>
    </div>

</div>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default submission first

        Swal.fire({
            title: 'Thank You!',
            text: 'Our team will contact you soon.',
            icon: 'success',
            confirmButtonColor: '#43a047', // Matching your green theme
            confirmButtonText: 'Great!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Now submit the form backend
                this.submit();
            }
        });
    });
</script>

@endsection