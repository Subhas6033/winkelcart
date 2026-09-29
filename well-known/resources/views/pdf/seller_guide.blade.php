<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WinkelKart Seller Guide</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #222; font-size: 13px; line-height: 1.6; }

        .header { background: #1a237e; color: #fff; padding: 28px 40px; text-align: center; }
        .header h1 { font-size: 26px; margin-bottom: 4px; }
        .header p { font-size: 12px; opacity: .85; }

        .content { padding: 30px 40px; }

        h2 { color: #1a237e; font-size: 17px; margin: 22px 0 8px; border-bottom: 2px solid #1a237e; padding-bottom: 4px; }
        h3 { color: #333; font-size: 14px; margin: 14px 0 6px; }

        p, li { margin-bottom: 6px; }
        ul, ol { padding-left: 22px; }
        ol li { margin-bottom: 8px; }

        .highlight-box {
            background: #e8eaf6; border-left: 4px solid #1a237e;
            padding: 12px 16px; margin: 12px 0; border-radius: 3px;
        }
        .warning-box {
            background: #fff3e0; border-left: 4px solid #e65100;
            padding: 12px 16px; margin: 12px 0; border-radius: 3px;
        }

        table { width: 100%; border-collapse: collapse; margin: 10px 0 16px; }
        th, td { border: 1px solid #bbb; padding: 7px 10px; text-align: left; font-size: 12px; }
        th { background: #1a237e; color: #fff; }

        .footer { text-align: center; font-size: 10px; color: #888; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 12px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ===== COVER / HEADER ===== --}}
<div class="header">
    <h1>WinkelKart &mdash; Seller Guide</h1>
    <p>Everything you need to know about selling on WinkelKart</p>
    <p style="margin-top:6px;font-size:11px;">Generated on {{ now()->format('d M Y') }}</p>
</div>

<div class="content">

{{-- ===== TABLE OF CONTENTS ===== --}}
<h2>Table of Contents</h2>
<ol>
    <li>Getting Started as a Seller</li>
    <li>Seller Registration &amp; KYC Verification</li>
    <li>Listing Products / Hotels</li>
    <li>Order Management &amp; Fulfilment</li>
    <li>Pricing, Commission &amp; Settlements</li>
    <li>Tax Information (GST Compliance)</li>
    <li>Shipping &amp; Delivery Guidelines</li>
    <li>Return, Refund &amp; Cancellation Policy</li>
    <li>Seller Code of Conduct</li>
    <li>Legal Disclaimer</li>
</ol>

{{-- ===== 1. GETTING STARTED ===== --}}
<h2>1. Getting Started as a Seller</h2>
<p>Welcome to WinkelKart! As a seller, you can list products across categories such as Electronics, Fashion, Groceries, Computers, and more &mdash; or manage Hotel &amp; Resort listings.</p>
<ol>
    <li><strong>Register</strong> on WinkelKart and select the <em>Seller</em> role during sign-up.</li>
    <li><strong>Complete your KYC</strong> (Know Your Customer) verification from the Seller Dashboard.</li>
    <li><strong>Set up your profile</strong> &mdash; business name, address, contact details.</li>
    <li><strong>Start listing</strong> products or hotels once KYC is approved.</li>
</ol>

{{-- ===== 2. KYC VERIFICATION ===== --}}
<h2>2. Seller Registration &amp; KYC Verification</h2>
<p>KYC verification is <strong>mandatory</strong> before you can receive settlements. You must provide:</p>

<table>
    <thead>
        <tr><th>Document / Field</th><th>Description</th></tr>
    </thead>
    <tbody>
        <tr><td>Legal Name</td><td>Your full legal name or registered business name</td></tr>
        <tr><td>PAN Number</td><td>Permanent Account Number issued by the Income Tax Department</td></tr>
        <tr><td>Aadhaar Number</td><td>12-digit Aadhaar identification number</td></tr>
        <tr><td>GST Number</td><td>Goods &amp; Services Tax Identification Number (GSTIN)</td></tr>
        <tr><td>Bank Details</td><td>Account holder name, account number, IFSC code, bank name</td></tr>
        <tr><td>Bank Passbook</td><td>Scanned copy of bank passbook or cancelled cheque</td></tr>
        <tr><td>ID Card</td><td>Government-issued photo ID (Aadhaar / Voter ID / Passport)</td></tr>
        <tr><td>PAN Card</td><td>Scanned copy of your PAN card</td></tr>
        <tr><td>GST Certificate</td><td>Scanned copy of GST registration certificate</td></tr>
        <tr><td>Membership Payment</td><td>Screenshot of membership payment ₹999 + 18% GST</td></tr>
    </tbody>
</table>

<div class="highlight-box">
    <strong>KYC Status Flow:</strong> Not Submitted &rarr; Pending (Under Review) &rarr; Verified / Rejected.<br>
    If rejected, review the admin feedback and re-submit with corrected documents.
</div>

{{-- ===== 3. LISTING PRODUCTS / HOTELS ===== --}}
<h2>3. Listing Products / Hotels</h2>

<h3>For Product Sellers</h3>
<ul>
    <li>Navigate to <strong>Products &rarr; Add New Product</strong> from your dashboard.</li>
    <li>Fill in product name, description, price, stock quantity, and upload clear images.</li>
    <li>Select the correct category and sub-category for your product.</li>
    <li>Products go live after admin review (if moderation is enabled).</li>
    <li>Keep product information accurate &mdash; misleading listings will be removed.</li>
</ul>

<h3>Product Pricing Calculation (Add Product)</h3>
<p>When you add a product, the final selling price is calculated automatically based on the following formula. WinkelKart charges a <strong>10% company profit</strong> on the Final Price.</p>

<table>
    <thead>
        <tr><th>Field</th><th>How It&rsquo;s Calculated</th></tr>
    </thead>
    <tbody>
        <tr><td><strong>MRP</strong></td><td>Maximum Retail Price &mdash; entered by the seller</td></tr>
        <tr><td><strong>Base Price</strong></td><td>Your selling price (before fees &amp; tax) &mdash; entered by the seller</td></tr>
        <tr><td><strong>Platform Fee</strong></td><td>Base Price &times; 10%</td></tr>
        <tr><td><strong>Offer Price</strong></td><td>Base Price + Platform Fee</td></tr>
        <tr><td><strong>Tax (%)</strong></td><td>Applicable GST rate &mdash; entered by the seller</td></tr>
        <tr><td><strong>Tax Money</strong></td><td>Offer Price &times; Tax %</td></tr>
        <tr><td><strong>Final Price</strong></td><td>Offer Price + Tax Money</td></tr>
        <tr><td><strong>Company Earn (10%)</strong></td><td>Final Price &times; 10% &mdash; WinkelKart&rsquo;s profit (inclusive of GST)</td></tr>
        <tr><td><strong>Seller Earn (90%)</strong></td><td>Final Price &minus; Company Earn</td></tr>
    </tbody>
</table>

<div class="highlight-box">
    <strong>Example:</strong> If your Base Price is &#8377;1,000 and Tax is 18%:<br>
    Platform Fee = &#8377;1,000 &times; 10% = <strong>&#8377;100</strong><br>
    Offer Price = &#8377;1,000 + &#8377;100 = <strong>&#8377;1,100</strong><br>
    Tax Money = &#8377;1,100 &times; 18% = <strong>&#8377;198</strong><br>
    Final Price = &#8377;1,100 + &#8377;198 = <strong>&#8377;1,298</strong><br>
    Company Earn (10%) = &#8377;1,298 &times; 10% = <strong>&#8377;129.80</strong><br>
    Seller Earn (90%) = &#8377;1,298 &minus; &#8377;129.80 = <strong>&#8377;1,168.20</strong>
</div>

<div class="warning-box">
    <strong>Note:</strong> The Platform Fee and Company Earn fields are auto-calculated and read-only. You only need to enter the MRP, Base Price, and Tax (%).
</div>

<h3>For Hotel &amp; Resort Sellers</h3>
<ul>
    <li>Navigate to <strong>Hotels &rarr; Add New Hotel</strong> from your dashboard.</li>
    <li>Provide hotel name, description, address, room types, pricing, and images.</li>
    <li>Manage room availability and booking calendar from the Hotel Management section.</li>
</ul>

{{-- ===== 4. ORDER MANAGEMENT ===== --}}
<h2>4. Order Management &amp; Fulfilment</h2>
<ul>
    <li>View incoming orders from the <strong>Orders</strong> section.</li>
    <li>Update order status promptly: Confirmed &rarr; Shipped &rarr; Delivered.</li>
    <li>Use the <strong>Order Timeline</strong> feature to add tracking updates for buyers.</li>
    <li>Ship orders within the promised timeline to maintain good seller ratings.</li>
    <li>For hotel bookings, confirm or reject bookings within 24 hours.</li>
</ul>

<div class="warning-box">
    <strong>Important:</strong> Failure to fulfil confirmed orders or repeated cancellations may lead to account suspension.
</div>

{{-- ===== 5. PRICING & COMMISSION ===== --}}
<h2>5. Pricing, Commission &amp; Settlements</h2>

<table>
    <thead>
        <tr><th>Item</th><th>Details</th></tr>
    </thead>
    <tbody>
        <tr><td>Product Pricing</td><td>You set the Base Price and Tax %. The Final Price is auto-calculated (see Section 3 for the full formula).</td></tr>
        <tr><td>Platform Fee</td><td>10% of Base Price &mdash; added to arrive at the Offer Price.</td></tr>
        <tr><td>Company Profit</td><td><strong>10% of Final Price</strong> (inclusive of GST) is retained by WinkelKart on every sale.</td></tr>
        <tr><td>Seller Earnings</td><td>90% of Final Price &mdash; credited to the seller after deducting company profit.</td></tr>
        <tr><td>Settlement Cycle</td><td>Settlements are processed periodically. Check your <strong>Seller Settlements</strong> section for details.</td></tr>
        <tr><td>Payment Method</td><td>Settlements are credited directly to the bank account registered in your KYC.</td></tr>
    </tbody>
</table>

<div class="highlight-box">
    <strong>Tip:</strong> Keep your bank details updated in KYC to avoid settlement delays.
</div>

<div class="page-break"></div>

{{-- ===== 6. TAX INFORMATION ===== --}}
<h2>6. Tax Information (GST Compliance)</h2>
<p>As a seller on WinkelKart, you are responsible for tax compliance under Indian tax law.</p>

<h3>GST Registration</h3>
<ul>
    <li>If your annual turnover exceeds &#8377;40 lakhs (&#8377;20 lakhs for special category states), GST registration is <strong>mandatory</strong>.</li>
    <li>For inter-state sales, GST registration is required regardless of turnover.</li>
    <li>Your GSTIN must be provided during KYC verification.</li>
</ul>

<h3>GST Rates (Common Categories)</h3>
<table>
    <thead>
        <tr><th>Category</th><th>Typical GST Rate</th></tr>
    </thead>
    <tbody>
        <tr><td>Groceries &amp; Essentials</td><td>0% &ndash; 5%</td></tr>
        <tr><td>Fashion &amp; Apparel</td><td>5% &ndash; 12%</td></tr>
        <tr><td>Electronics &amp; Computers</td><td>12% &ndash; 18%</td></tr>
        <tr><td>Luxury Items</td><td>18% &ndash; 28%</td></tr>
        <tr><td>Hotel Room Tariff (&le; &#8377;1,000)</td><td>12%</td></tr>
        <tr><td>Hotel Room Tariff (&gt; &#8377;1,000 &amp; &le; &#8377;7,500)</td><td>12%</td></tr>
        <tr><td>Hotel Room Tariff (&gt; &#8377;7,500)</td><td>18%</td></tr>
    </tbody>
</table>

<h3>Tax Invoicing</h3>
<ul>
    <li>You are responsible for issuing proper GST-compliant invoices for each sale.</li>
    <li>Invoices must include: seller GSTIN, buyer details, HSN/SAC code, taxable value, CGST, SGST/IGST amount.</li>
    <li>Maintain proper records for at least 6 years as required by GST law.</li>
</ul>

<h3>TDS (Tax Deducted at Source)</h3>
<ul>
    <li>WinkelKart may deduct TDS at 1% on net taxable supplies as per Section 194-O of the Income Tax Act.</li>
    <li>TDS certificates will be available for download from your Seller Settlements section.</li>
    <li>Ensure your PAN is linked with your Aadhaar to avoid higher TDS rates.</li>
</ul>

<h3>TCS (Tax Collected at Source)</h3>
<ul>
    <li>As an e-commerce operator, WinkelKart collects TCS at 1% (0.5% CGST + 0.5% SGST) on net taxable supplies.</li>
    <li>TCS collected will be reflected in your GST returns (GSTR-2A).</li>
    <li>You can claim credit for TCS in your own GST returns.</li>
</ul>

<div class="warning-box">
    <strong>Disclaimer:</strong> The above tax rates and information are provided as general guidance only. Tax rates are subject to change by the Government of India. Consult a qualified Chartered Accountant or tax advisor for advice specific to your business.
</div>

{{-- ===== 7. SHIPPING ===== --}}
<h2>7. Shipping &amp; Delivery Guidelines</h2>
<ul>
    <li>Pack products securely using appropriate packaging material.</li>
    <li>Include an invoice/packing slip inside each shipment.</li>
    <li>Ship within the committed timeframe (usually 2&ndash;3 business days after order confirmation).</li>
    <li>Provide valid tracking details via the Order Timeline feature.</li>
    <li>Use reputable courier partners to minimize transit damage.</li>
</ul>

{{-- ===== 8. RETURN / REFUND ===== --}}
<h2>8. Return, Refund &amp; Cancellation Policy</h2>
<ul>
    <li>Buyers can request returns/refunds as per WinkelKart&rsquo;s return policy. Check the admin panel for applicable return windows.</li>
    <li>Accept returns for defective, damaged, or incorrect items without dispute.</li>
    <li>Refunds for returned items are deducted from your next settlement cycle.</li>
    <li>Repeated quality complaints may result in product de-listing or account action.</li>
    <li>For hotel bookings, cancellation charges may apply as defined in your listing terms.</li>
</ul>

{{-- ===== 9. CODE OF CONDUCT ===== --}}
<h2>9. Seller Code of Conduct</h2>
<ol>
    <li><strong>Honest Listings:</strong> Do not misrepresent product/service details, images, or availability.</li>
    <li><strong>Fair Pricing:</strong> Do not engage in price gouging or misleading discounts.</li>
    <li><strong>Timely Fulfilment:</strong> Ship orders and confirm bookings within the committed timeframe.</li>
    <li><strong>Quality Standards:</strong> Sell only genuine, undamaged products. Do not list counterfeit or prohibited items.</li>
    <li><strong>Customer Communication:</strong> Respond to buyer queries and complaints professionally.</li>
    <li><strong>Data Privacy:</strong> Do not misuse buyer information obtained through the platform.</li>
    <li><strong>Compliance:</strong> Follow all applicable Indian laws including Consumer Protection Act, GST Act, and IT Act.</li>
</ol>

<div class="warning-box">
    <strong>Violation of the Code of Conduct may result in warnings, listing removal, settlement hold, or permanent account suspension.</strong>
</div>

{{-- ===== 10. LEGAL DISCLAIMER ===== --}}
<h2>10. Legal Disclaimer</h2>
<div style="background: #f5f5f5; padding: 16px; border: 1px solid #ccc; border-radius: 4px; margin-top: 10px;">
    <p><strong>1. Platform Role:</strong> WinkelKart operates as an online marketplace that connects buyers and sellers. WinkelKart is not a party to any transaction between buyers and sellers and does not own, sell, or resell any products or services listed on the platform.</p>

    <p><strong>2. Seller Responsibility:</strong> As a seller, you are solely responsible for the accuracy of your listings, the quality of products/services, timely fulfilment, and compliance with all applicable laws and regulations. You are also responsible for obtaining all necessary licenses, permits, and registrations.</p>

    <p><strong>3. Tax Liability:</strong> WinkelKart does not provide tax advice. All sellers are responsible for their own tax compliance, including GST registration, filing, and payment. The tax rates mentioned in this guide are indicative and subject to change. Sellers must consult qualified tax professionals for advice.</p>

    <p><strong>4. Intellectual Property:</strong> You warrant that all content (images, descriptions, branding) uploaded to WinkelKart is owned by you or used with proper authorization. Infringement of third-party intellectual property rights will result in immediate listing removal and potential legal action.</p>

    <p><strong>5. Limitation of Liability:</strong> WinkelKart shall not be liable for any direct, indirect, incidental, or consequential damages arising from your use of the platform, including but not limited to loss of revenue, profits, or data.</p>

    <p><strong>6. Indemnification:</strong> You agree to indemnify and hold WinkelKart harmless from any claims, losses, or damages arising from your listings, sales, tax non-compliance, or violation of these terms.</p>

    <p><strong>7. Modification of Terms:</strong> WinkelKart reserves the right to modify these guidelines and terms at any time. Continued use of the platform after modifications constitutes acceptance of the updated terms.</p>

    <p><strong>8. Governing Law:</strong> These terms are governed by and construed in accordance with the laws of India. Any disputes shall be subject to the exclusive jurisdiction of courts in the registered office location of WinkelKart.</p>

    <p style="margin-top: 14px; font-size: 11px; color: #666;">
        By selling on WinkelKart, you acknowledge that you have read, understood, and agreed to the above terms, guidelines, and disclaimer.
    </p>
</div>

{{-- ===== FOOTER ===== --}}
<div class="footer">
    <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
    <p>This document is auto-generated and intended for seller reference only.</p>
    <p>For support, contact us through the Help &amp; Support section on your dashboard.</p>
</div>

</div>
</body>
</html>
