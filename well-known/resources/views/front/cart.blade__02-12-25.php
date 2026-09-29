<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: hsl(57 79.3% 60.5%);
      font-family: 'Poppins', sans-serif;
    }

    .cart-container {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      margin: 3rem auto;
      max-width: 1100px;
    }

    .table img {
      width: 70px;
      border-radius: 10px;
    }

    .navbar-brand img {
      height: 60px;
    }

    /* ✅ Responsive adjustments */
    @media (max-width: 768px) {
      .cart-container {
        padding: 1rem;
        margin: 1rem;
      }

      h2 {
        font-size: 1.5rem;
        text-align: center;
      }

      .d-flex.justify-content-between {
        flex-direction: column;
        align-items: center !important;
        gap: 1rem;
      }

      /* ✅ Make cart & order tables mobile-friendly */
      .table thead {
        display: none;
      }

      .table,
      .table tbody,
      .table tr,
      .table td {
        display: block;
        width: 100%;
      }

      .table tr {
        margin-bottom: 1.5rem;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 1rem;
      }

      .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
      }

      .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 1rem;
        width: 45%;
        padding-right: 10px;
        font-weight: 600;
        text-align: left;
      }

      .table img {
        width: 60px;
      }

      form.d-flex {
        flex-direction: row;
        justify-content: flex-end;
      }

      .btn {
        font-size: 0.9rem;
        padding: 0.4rem 0.6rem;
      }

      .text-center img {
        width: 150px;
      }
    }
  </style>
</head>
<body>

  <div class="container cart-container">
    <!-- 🛒 Cart Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('assets/images/logo/Winkel_Shop__2_-removebg-preview.png') }}" alt="Winkel Logo">
      </a>
      <h2 class="mb-0">🛒 Your Shopping Cart</h2>
    </div>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($cartItems->isEmpty())
      <!-- <p>Your cart is empty.</p> -->
      <div class="text-center my-5">
        <p class="fs-5 mb-3">🛍️ Your cart is empty.</p>
        <a href="{{ url('/') }}" class="btn btn-outline-primary fw-bold px-4">
          ← Continue Shopping
        </a>
      </div>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Image</th>
              <th>Product</th>
              <th>Price (₹)</th>
              <th>Quantity</th>
              <th>Subtotal (₹)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cartItems as $item)
            <tr>
              <td data-label="Image"><img src="{{ asset('uploads/products/'.$item->products->image) }}"></td>
              <td data-label="Product">{{ $item->products->name }}</td>
              <td data-label="Price">₹{{ number_format($item->price, 2) }}</td>
              <td data-label="Quantity">
                <form action="{{ url('cart_update', $item->id) }}" method="POST" class="d-flex">
                  @csrf
                  <input type="text" name="quantity" min="1" value="{{ $item->quantity }}" 
                    class="form-control w-50 me-2 allow-only-numeric" maxlength="2">
                  <button class="btn btn-sm btn-outline-success">Update</button>
                </form>
              </td>
              <td data-label="Subtotal">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
              <td data-label="Remove">
                <form action="{{ url('cart_remove', $item->id) }}" method="get">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Remove</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
        <h4>Total: ₹{{ number_format($total, 2) }}</h4>
      </div>

      <div class="text-center mt-5">
        <h4>Scan QR to Pay</h4>
        <img src="{{ asset('assets/images/qr/upi_qr.png') }}" alt="QR Code" width="200">

        <form action="{{ route('place_order') }}" method="POST" enctype="multipart/form-data" class="mt-4">
          @csrf
          <input type="hidden" name="total_amount" value="{{ number_format($total, 2) }}">
          <div class="mb-3">
            <label for="payment_screenshot" class="form-label">Upload Payment Screenshot</label>
            <input type="file" class="form-control @error('payment_screenshot') is-invalid @enderror" 
                  name="payment_screenshot" accept="image/*" required>
            @error('payment_screenshot')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('/') }}" class="btn btn-outline-primary px-4 fw-bold">
              ← Continue Shopping
            </a>
            <button type="submit" class="btn btn-success px-4 fw-bold">
              Place Order
            </button>
          </div>
          <!-- <button type="submit" class="btn btn-success">Place Order</button> -->
        </form>
      </div>
    @endif


    <!-- 📦 Orders Section -->
    @if(!$orders->isEmpty())
    <hr class="my-5">
    <div class="mt-5">
      <h3 class="mb-4">📦 Your Orders</h3>

      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Order No</th>
              <th>Date</th>
              <th>Items</th>
              <th>Total (₹)</th>
              <th>Payment</th>
              <th>Payment Received</th>            
              <th></th>            
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
            <tr>
              <td data-label="Order No">{{ $order->order_number }}</td>
              <td data-label="Date">{{ $order->created_at->format('d M Y, h:i A') }}</td>
              <td data-label="Items">
                @foreach($order->items as $item)
                  <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('uploads/products/'.$item->product->image) }}" 
                        alt="{{ $item->product->name }}" 
                        width="50" class="me-2 rounded">
                    <div>
                      <strong>{{ $item->product->name }}</strong><br>
                      Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}<br>
                      Delivery Date: {{ $item->delivery_date ? date('d M, Y', strtotime($item->delivery_date)) : 'Not set' }}
                    </div>
                  </div>
                @endforeach
              </td>
              <td data-label="Total">₹{{ number_format($order->total_amount, 2) }}</td>
              <td data-label="Payment">
                @if($order->payment_image)
                  <img src="{{ asset('uploads/payments/'.$order->payment_image) }}" 
                      alt="Payment Proof" 
                      width="50" 
                      height="50"
                      data-bs-toggle="modal" 
                      data-bs-target="#paymentModal{{ $order->id }}" 
                      style="cursor:pointer;">
                  <!-- Modal -->
                  <div class="modal fade" id="paymentModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Payment Receipt</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                          <img src="{{ asset('uploads/payments/'.$order->payment_image) }}" 
                              alt="Payment Image" 
                              class="img-fluid rounded">
                        </div>
                      </div>
                    </div>
                  </div>
                @else
                  <span class="text-muted">Not uploaded</span>
                @endif
              </td>
              <td data-label="Status">
                <span class="badge bg-{{ $order->order_status == 'Pending' ? 'warning' : ($order->order_status == 'Done' ? 'success' : 'secondary') }}">
                  {{ $order->order_status == 'Pending' ? 'No' : 'Yes' }}
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-primary" onclick="downloadInvoice({{ $order->id }})">
                  Download Invoice
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- @foreach($orders as $order)
      <div id="invoice-{{ $order->id }}" class="invoice-container" style="display:none; padding:20px; background:#fff; color:#000;">
        <h2 style="text-align:center;">Tax Invoice</h2>
        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
        <hr>
        <table width="100%" border="1" cellspacing="0" cellpadding="5">
          <thead>
            <tr>
              <th>Product</th><th>Qty</th><th>Price</th><th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
            <tr>
              <td>{{ $item->product->name }}</td>
              <td>{{ $item->quantity }}</td>
              <td>₹{{ number_format($item->price,2) }}</td>
              <td>₹{{ number_format($item->quantity * $item->price,2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <h3 style="text-align:right;">Grand Total: ₹{{ number_format($order->total_amount, 2) }}</h3>
      </div>
      @endforeach -->

      @foreach($orders as $order)
      <div id="invoice-{{ $order->id }}" 
          class="invoice-container" 
          style="display:none; background:#fff; padding:25px; color:#000; font-family:Arial, sans-serif; font-size:12px;">
        
        <div style="text-align:center; margin-bottom:10px;">
          <h3 style="margin:0;">Tax Invoice</h3>
        </div>

        <!-- Seller + Buyer Info -->
        <table width="100%" style="margin-bottom:10px;">
          <tr>
            <td width="60%" style="vertical-align:top;">
              <strong>Sold By:</strong><br>
              @php
                $seller = $order->items->first()->product->seller ?? null;
              @endphp
              {{ $seller->name ?? 'Seller Name' }}<br>
              {{ $seller->user_info->address ?? 'Seller Address' }}<br>
              <small>Email: {{ $seller->email ?? '-' }}</small><br>
              <small>Phone: {{ $seller->mobile ?? '-' }}</small>
            </td>
            <td width="40%" style="vertical-align:top;">
              <strong>Billing Address:</strong><br>
              {{ $order->buyer->name ?? 'Buyer Name' }}<br>
              {{ $order->buyer->user_info->address ?? 'Buyer Address' }}<br>
              <small>Email: {{ $order->buyer->email ?? '-' }}</small><br>
              <small>Phone: {{ $order->buyer->mobile ?? '-' }}</small>
            </td>
          </tr>
        </table>

        <!-- Order Details -->
        <table width="100%" style="margin-bottom:10px;">
          <tr>
            <td><strong>Order No:</strong> {{ $order->order_number }}</td>
            <td><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</td>
          </tr>
        </table>

        <!-- Items -->
        <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; text-align:center;">
          <thead style="background:#f2f2f2;">
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Price (₹)</th>
              <th>Total (₹)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
            <tr>
              <td style="text-align:left;">{{ $item->product->name }}</td>
              <td>{{ $item->quantity }}</td>
              <td>{{ number_format($item->price,2) }}</td>
              <td>{{ number_format($item->quantity * $item->price,2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Grand Total -->
        <div style="text-align:right; margin-top:15px;">
          <h4>Grand Total: ₹{{ number_format($order->total_amount, 2) }}</h4>
        </div>

        <div style="margin-top:20px; text-align:center;">
          <small>Thank you for shopping with us!</small>
        </div>
      </div>
      @endforeach

    </div>
    @endif
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>
    $('.allow-only-numeric').keyup(function() {
      var node = $(this);
      node.val(node.val().replace(/[^0-9]/g,''));
    });
  </script>  

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  async function downloadInvoice(orderId) {
    const { jsPDF } = window.jspdf;
    const invoice = document.getElementById(`invoice-${orderId}`);

    // Make visible temporarily for rendering
    invoice.style.display = 'block';

    const canvas = await html2canvas(invoice, { scale: 2 });
    const imgData = canvas.toDataURL('image/png');

    const pdf = new jsPDF('p', 'mm', 'a4');
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

    pdf.save(`Invoice_${orderId}.pdf`);
    
    // Hide again
    invoice.style.display = 'none';
  }
  </script>
</body>
</html>
