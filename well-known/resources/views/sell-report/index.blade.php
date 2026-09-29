@extends('layouts.app')

@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row  mt-5">
      <div class="col-xl-12  mt-3">
        @if(Session::has('success'))
        <div>
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
        </div>
        @endif
        <div class="card">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Sell Report</b></h5>
              </div>
              <div class="col text-right">                
                <button id="exportExcel" class="btn btn-success btn-sm float-end me-3">
                  <i class="fas fa-file-excel"></i> Download Excel
                </button>
              </div>
            </div>
          </div>

          <!-- Grand Totals Summary -->
          <div class="card-body pt-0">
            <div class="row">
              <div class="col-md-3">
                <div class="card bg-primary text-white mb-3">
                  <div class="card-body py-2">
                    <h6 class="mb-0">Grand Total</h6>
                    <h4 class="mb-0">₹{{ number_format($grandTotals['total_amount'], 2) }}</h4>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-warning text-dark mb-3">
                  <div class="card-body py-2">
                    <h6 class="mb-0">Total Tax</h6>
                    <h4 class="mb-0">₹{{ number_format($grandTotals['total_tax'], 2) }}</h4>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-info text-white mb-3">
                  <div class="card-body py-2">
                    <h6 class="mb-0">Company Earn (10%)</h6>
                    <h4 class="mb-0">₹{{ number_format($grandTotals['company_profit'], 2) }}</h4>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card bg-success text-white mb-3">
                  <div class="card-body py-2">
                    <h6 class="mb-0">Seller Profit</h6>
                    <h4 class="mb-0">₹{{ number_format($grandTotals['seller_profit'], 2) }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="px-3 pb-3">
            <form method="GET" action="{{ route('sell-report.index') }}" class="row align-items-end">
              @if(!$isSellerView)
              <div class="col-md-3 mb-2">
                <label class="mb-1">Seller</label>
                <select name="seller_id" class="form-control">
                  <option value="">All Sellers</option>
                  @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" {{ (string)$selectedSellerId === (string)$seller->id ? 'selected' : '' }}>
                      {{ $seller->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              @endif

              <div class="{{ $isSellerView ? 'col-md-4' : 'col-md-3' }} mb-2">
                <label class="mb-1">Product</label>
                <select name="product_id" class="form-control">
                  <option value="">All Products</option>
                  @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ (string)$selectedProductId === (string)$product->id ? 'selected' : '' }}>
                      {{ $product->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="{{ $isSellerView ? 'col-md-4' : 'col-md-3' }} mb-2">
                <label class="mb-1">Buyer</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search buyer name">
              </div>
              <div class="{{ $isSellerView ? 'col-md-4' : 'col-md-3' }} mb-2">
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                <a href="{{ route('sell-report.index') }}" class="btn btn-secondary">Reset</a>
              </div>
            </form>
          </div>

          <div class="table-responsive">
            <table class="table align-items-center table-flush" id="sellReportTable">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Sl No</th>
                  <th scope="col">Order No</th>
                  <th scope="col">Buyer</th>
                  <th scope="col">Address</th>
                  <th scope="col">Product</th>
                  <th scope="col">Seller</th>
                  <th scope="col">Qty</th>
                  <th scope="col">MRP</th>
                  <th scope="col">Base Price</th>
                  <th scope="col">Platform Fee</th>
                  <th scope="col">Offer Price</th>
                  <th scope="col">Tax %</th>
                  <th scope="col">Tax Amount</th>
                  <th scope="col">Final Price</th>
                  <th scope="col">Company (10%)</th>
                  <th scope="col">Seller Profit</th>
                  <th scope="col">Total</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty(@$data))
                @foreach ($data as $key => $order)
                  @php $firstItem = true; $itemCount = 0; @endphp
                  @foreach ($order->items as $item)
                    @if (
                      $item->product &&
                      (Auth::user()->hasRole('Admin') || $item->product->created_by == Auth::id()) &&
                      (empty($selectedSellerId) || (int)$item->product->created_by === (int)$selectedSellerId) &&
                      (empty($selectedProductId) || (int)$item->product_id === (int)$selectedProductId)
                    )
                      @php $itemCount++; @endphp
                    @endif
                  @endforeach

                  @if($itemCount > 0)
                  @foreach ($order->items as $itemKey => $item)
                    @if (
                      $item->product &&
                      (Auth::user()->hasRole('Admin') || $item->product->created_by == Auth::id()) &&
                      (empty($selectedSellerId) || (int)$item->product->created_by === (int)$selectedSellerId) &&
                      (empty($selectedProductId) || (int)$item->product_id === (int)$selectedProductId)
                    )
                    <tr>
                      @if($firstItem)
                        <td rowspan="{{ $itemCount }}">{{ ++$i }}</td>
                        <td rowspan="{{ $itemCount }}">{{ $order->order_number }}</td>
                        <td rowspan="{{ $itemCount }}">{{ $order->buyer->name }}</td>
                        <td rowspan="{{ $itemCount }}">{{ $order->buyer->user_info->address ?? 'N/A' }}</td>
                        @php $firstItem = false; @endphp
                      @endif
                      <td>
                        <strong>{{ $item->product->name }}</strong><br>
                        @if($item->product->seller)
                          <small class="text-muted">
                            Seller: {{ $item->product->seller->name }}
                          </small>
                        @endif
                      </td>
                      <td>{{ $item->product->seller->name ?? '-' }}</td>
                      <td>{{ $item->quantity }}</td>
                      <td>₹{{ number_format($item->calc_mrp, 2) }}</td>
                      <td>₹{{ number_format($item->calc_base_price, 2) }}</td>
                      <td>₹{{ number_format($item->calc_platform_fee, 2) }}</td>
                      <td>₹{{ number_format($item->calc_offer_price, 2) }}</td>
                      <td>{{ $item->calc_tax_percent }}%</td>
                      <td>₹{{ number_format($item->total_tax, 2) }}</td>
                      <td>₹{{ number_format($item->calc_final_price, 2) }}</td>
                      <td>₹{{ number_format($item->total_company_profit, 2) }}</td>
                      <td>₹{{ number_format($item->total_seller_profit, 2) }}</td>
                      <td><strong>₹{{ number_format($item->total_amount, 2) }}</strong></td>
                    </tr>
                    @endif
                  @endforeach
                  <!-- Order subtotal row -->
                  <tr class="table-secondary">
                    <td colspan="12" class="text-right"><strong>Order Subtotal:</strong></td>
                    <td><strong>₹{{ number_format($order->calc_totals['total_tax'], 2) }}</strong></td>
                    <td></td>
                    <td><strong>₹{{ number_format($order->calc_totals['company_profit'], 2) }}</strong></td>
                    <td><strong>₹{{ number_format($order->calc_totals['seller_profit'], 2) }}</strong></td>
                    <td><strong>₹{{ number_format($order->calc_totals['total_amount'], 2) }}</strong></td>
                  </tr>
                  @endif
                @endforeach
                @endif
              </tbody>
              <tfoot class="table-dark">
                <tr>
                  <td colspan="12" class="text-right"><strong>GRAND TOTAL:</strong></td>
                  <td><strong>₹{{ number_format($grandTotals['total_tax'], 2) }}</strong></td>
                  <td></td>
                  <td><strong>₹{{ number_format($grandTotals['company_profit'], 2) }}</strong></td>
                  <td><strong>₹{{ number_format($grandTotals['seller_profit'], 2) }}</strong></td>
                  <td><strong>₹{{ number_format($grandTotals['total_amount'], 2) }}</strong></td>
                </tr>
              </tfoot>
            </table>
            {{ $data->appends(Request::all())->links("pagination::bootstrap-4") }}
          </div>
           
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.getElementById("exportExcel").addEventListener("click", function() {
    var wb = XLSX.utils.book_new();
    var ws_data = [];

    // Define headers
    ws_data.push([
        "Sl No", "Order No", "Buyer", "Address", "Product", "Seller", "Qty",
        "MRP", "Base Price", "Platform Fee", "Offer Price", "Tax %", "Tax Amount",
        "Final Price", "Company (10%)", "Seller Profit", "Total"
    ]);

    let grandTotal = 0;
    let grandTax = 0;
    let grandCompanyProfit = 0;
    let grandSellerProfit = 0;

    // Get data from PHP
    @if(!empty($data))
    @foreach ($data as $key => $order)
        @foreach ($order->items as $item)
          @if (
            $item->product &&
            (Auth::user()->hasRole('Admin') || $item->product->created_by == Auth::id()) &&
            (empty($selectedSellerId) || (int)$item->product->created_by === (int)$selectedSellerId) &&
            (empty($selectedProductId) || (int)$item->product_id === (int)$selectedProductId)
          )
            ws_data.push([
                "{{ $loop->parent->iteration }}",
                "{{ $order->order_number }}",
                "{{ $order->buyer->name }}",
                "{{ $order->buyer->user_info->address ?? 'N/A' }}",
                "{{ $item->product->name }}",
                "{{ $item->product->seller->name ?? 'N/A' }}",
                {{ $item->quantity }},
                {{ $item->calc_mrp }},
                {{ $item->calc_base_price }},
                {{ $item->calc_platform_fee }},
                {{ $item->calc_offer_price }},
                {{ $item->calc_tax_percent }},
                {{ $item->total_tax }},
                {{ $item->calc_final_price }},
                {{ $item->total_company_profit }},
                {{ $item->total_seller_profit }},
                {{ $item->total_amount }}
            ]);
            @endif
        @endforeach
        // Order subtotal
        ws_data.push([
            "", "", "", "", "", "", "", "", "", "", "", "", "Order Subtotal:",
            {{ $order->calc_totals['total_tax'] }},
            "",
            {{ $order->calc_totals['company_profit'] }},
            {{ $order->calc_totals['seller_profit'] }},
            {{ $order->calc_totals['total_amount'] }}
        ]);
        ws_data.push([]); // Empty row between orders
    @endforeach
    @endif

    // Grand totals from PHP
    grandTotal = {{ $grandTotals['total_amount'] }};
    grandTax = {{ $grandTotals['total_tax'] }};
    grandCompanyProfit = {{ $grandTotals['company_profit'] }};
    grandSellerProfit = {{ $grandTotals['seller_profit'] }};

    // Add Grand Total row
    ws_data.push([
        "", "", "", "", "", "", "", "", "", "", "", "", "GRAND TOTAL:",
        grandTax.toFixed(2),
        "",
        grandCompanyProfit.toFixed(2),
        grandSellerProfit.toFixed(2),
        grandTotal.toFixed(2)
    ]);

    // Create worksheet
    var ws = XLSX.utils.aoa_to_sheet(ws_data);

    // Adjust column widths
    ws['!cols'] = [
        { wch: 6 },   // Sl No
        { wch: 15 },  // Order No
        { wch: 15 },  // Buyer
        { wch: 30 },  // Address
        { wch: 25 },  // Product
        { wch: 15 },  // Seller
        { wch: 5 },   // Qty
        { wch: 10 },  // MRP
        { wch: 12 },  // Base Price
        { wch: 12 },  // Platform Fee
        { wch: 12 },  // Offer Price
        { wch: 8 },   // Tax %
        { wch: 12 },  // Tax Amount
        { wch: 12 },  // Final Price
        { wch: 12 },  // Company (10%)
        { wch: 12 },  // Seller Profit
        { wch: 12 }   // Total
    ];

    // Add worksheet to workbook
    XLSX.utils.book_append_sheet(wb, ws, "Sell Report");

    // Save Excel file with date
    var today = new Date();
    var dateStr = today.toISOString().slice(0,10);
    XLSX.writeFile(wb, "sell_report_" + dateStr + ".xlsx");
});
</script>
@endpush