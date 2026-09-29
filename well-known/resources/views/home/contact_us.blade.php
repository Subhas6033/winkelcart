@extends('layouts.app')

@section('content')

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

    .info-item h4 { font-size: 16px; margin-bottom: 5px; }
    .info-item p { font-size: 13px; opacity: 0.9; line-height: 1.6; margin: 0; }

    .social-icons { display: flex; gap: 12px; margin-top: 20px; }

    .social-icons a {
        width: 38px; height: 38px;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 18px;
        transition: 0.3s;
        text-decoration: none;
    }

    .social-icons a:hover { background: white; color: var(--wk-blue); }

    /* ===== RIGHT: TABLE PANEL ===== */
    .contact-form {
        flex: 1.5;
        padding: 50px 40px;
        overflow-x: auto;
    }

    .contact-form h2 {
        color: var(--wk-green);
        margin-bottom: 25px;
        font-size: 22px;
        font-weight: 700;
    }

    /* Company intro card */
    .company-intro {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #f9f9f9;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #eee;
    }

    .company-intro img { width: 120px; }

    .company-intro p {
        font-size: 14px;
        color: #444;
        line-height: 1.6;
        margin: 0;
    }

    /* Contacts Table */
    .contact-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .contact-table thead tr {
        background: var(--wk-green);
        color: white;
    }

    .contact-table th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
    }

    .contact-table td { padding: 12px 15px; border-bottom: 1px solid #eee; color: #444; }
    .contact-table tr:nth-child(even) { background: #f9f9f9; }
    .contact-table tr:hover { background: var(--wk-light-green); }

    /* Video Link Card */
    .video-card {
        background: #f0f9ff;
        border: 1px solid #bee3f8;
        border-radius: 10px;
        padding: 16px 20px;
        font-size: 14px;
        color: #444;
    }

    .video-card a { color: var(--wk-blue); font-weight: 600; text-decoration: underline; }
    .video-card a:hover { color: var(--wk-dark-green); }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .contact-wrapper { flex-direction: column; margin: 20px; }
        .contact-info, .contact-form { padding: 30px 20px; }
        .company-intro { flex-direction: column; text-align: center; }
    }
</style>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<!-- ===== HERO ===== -->
<div class="contact-hero">
    <h1>Get in Touch</h1>
    <p>Have questions? We'd love to hear from you.</p>
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
            <p style="margin-bottom:12px; font-size:14px;">Follow us:</p>
            <div class="social-icons">
                <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                <a href="#"><ion-icon name="logo-linkedin"></ion-icon></a>
            </div>
        </div>
    </div>

    <!-- Right: Content (backend untouched) -->
    <div class="contact-form">
        <h2>Our Team</h2>

        <!-- Company Intro Card -->
        <div class="company-intro">
            <a href=""><img src="{{ asset('assets_admin/images/yashujee_logo.png') }}" alt=""></a>
            <p><b>Winkel India Automation</b><br>
                42, Metcalfe Street Kolkata 700013<br>
                Near Bank of India Mission Row Branch<br>
                Printer Support
            </p>
        </div>

        <!-- Contacts Table (backend untouched) -->
        <table class="contact-table">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Email ID</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Karan Kumar Pandey</td>
                    <td>+9147321883</td>
                    <td>printer.support@utkarsh.bank</td>
                    <td>Printer Support</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Kush Singh</td>
                    <td>+9147321884</td>
                    <td></td>
                    <td>Printer Support</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Neeraj Updhay</td>
                    <td>+9147321885</td>
                    <td></td>
                    <td>Printer Support</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Anand Sharma</td>
                    <td>+9147321881</td>
                    <td>servicing.icon@gmail.com</td>
                    <td>Consumable (Toner, Drum, Ink & Maintenance Box)</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Surajit Sanpui</td>
                    <td>+9147321882</td>
                    <td>winkel.ecomerce@gmail.com</td>
                    <td>Service Support (Printer & Printer Spare Parts)</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Manu Prasad</td>
                    <td>+9883727249</td>
                    <td></td>
                    <td>Manager</td>
                </tr>
            </tbody>
        </table>

        <!-- Video Link Card (backend untouched) -->
        <div class="video-card">
            <p class="mb-0">Please visit the link below: 
                <a href="https://youtu.be/SF9D7rX1C4g">Our Company Profile Video</a>
            </p>
        </div>

    </div>
</div>

@endsection