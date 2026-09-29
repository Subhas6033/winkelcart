@extends('layouts.app')

@section('content')
<?php
$last_segment = request()->segment(count(request()->segments()));
?>
<div class="content-wrapper">
  <div class="container-fluid ">
    <div class="row">
      <div class="col-xl-12  mt-3 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12">
                <h5 class="info-box-text mb-0"><b>Products</b></h5>
              </div>
            </div>
          </div>
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          {{-- Subscription plan info for sellers --}}
          @if(!empty($isSeller))
          @php
            $viewIsPro = $isPro ?? false;
            $viewCount = $sellerProductCount ?? 0;
            $viewLimit = 50;
          @endphp
          @if(!$viewIsPro)
          <div class="alert d-flex align-items-center justify-content-between mb-0 mt-0" style="border-radius:0;border-bottom:1px solid #dee2e6;background:#fff8e1;border-left:4px solid #ffc107;">
              <div>
                  <i class="fas fa-info-circle text-warning mr-2"></i>
                  <strong>Free Plan:</strong> {{ $viewCount }} / {{ $viewLimit }} products used.
                  @if($viewCount >= $viewLimit)
                      <span class="text-danger font-weight-bold"> Limit reached — you cannot add more products.</span>
                  @else
                      {{ $viewLimit - $viewCount }} slots remaining.
                  @endif
              </div>
              <a href="{{ route('register-buisness.create') }}" class="btn btn-sm btn-warning ml-3" style="white-space:nowrap;font-weight:700;">⚡ Upgrade to Pro</a>
          </div>
          @else
          <div class="alert mb-0 mt-0" style="border-radius:0;border-bottom:1px solid #dee2e6;background:#f3f0ff;border-left:4px solid #6f42c1;">
              <i class="fas fa-star text-purple mr-2" style="color:#6f42c1;"></i>
              <strong style="color:#6f42c1;">Pro Plan:</strong> Unlimited product listings · 7% commission · Featured listing eligible.
          </div>
          @endif
          @endif
          {{-- End subscription plan info --}}
          <div class="card-body">
            <form action="{{ route('products.store') }}" for_mode="{{ $last_segment }}" id="product_form" name="product_form" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="pl-lg-4">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-name">Product Name <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_name"></span>
                      <input type="hidden" name="product_id" id="product_id" class="form-control" value="{{ isset($product->id) ? $product->id : '' }}">
                      <input type="text" name="name" class="form-control" value="{{ old('name', isset($product->name) ? $product->name : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-image">Product Images <span class="required-red">*</span></label>
                        <span class="validation-error" id="error_msg_image"></span>
                        <input type="file" name="images[]" id="images" class="form-control" multiple onchange="previewImages(event)">
                        <small class="form-text text-muted">Hold Ctrl (or Cmd) to select multiple images.</small>
                        <div id="preview-container" class="mt-2"></div>
                        @if(isset($product->productImages) && count($product->productImages))
                          <div class="mt-2" id="existing-images">
                            @foreach($product->productImages as $img)
                              <div class="d-inline-block position-relative mr-2 mb-2" id="img-{{ $img->id }}">
                                <img src="{{ asset('uploads/products/'.$img->image) }}" alt="Product Image" width="80" class="img-thumbnail">
                                <button type="button"
                                        onclick="deleteProductImage({{ $product->id }}, {{ $img->id }}, this)"
                                        class="btn btn-sm btn-danger"
                                        style="position:absolute;top:0;right:0;padding:2px 6px;line-height:1;">&times;</button>
                              </div>
                            @endforeach
                          </div>
                        @elseif(isset($product->image) && $product->image != '')
                          <div class="mt-2">
                            <img src="{{ asset('uploads/products/'.$product->image) }}" alt="Product Image" width="80" class="img-thumbnail">
                          </div>
                        @endif
                      </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-mrp">MRP <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_mrp"></span>
                      <input type="text" name="total_price" class="form-control" value="{{ old('total_price', isset($product->total_price) ? $product->total_price : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-base-price">Base Price <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_base_price"></span>
                      <input type="text" name="offer_price" id="base_price" class="form-control" value="{{ old('offer_price', isset($product->offer_price) ? $product->offer_price : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-platform-fee">Platform Fee (10% incl. GST)</label>
                      <input type="text" name="platform_fee" id="platform_fee" class="form-control" value="" readonly>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-offer-price">Offer Price</label>
                      <input type="text" name="calculated_offer_price" id="calculated_offer_price" class="form-control" value="" readonly>
                      <small class="form-text text-muted">Auto-calculated: Base Price + Platform Fee</small>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-tax">Tax (%) <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_tax"></span>
                      <input type="text" name="tax" class="form-control allow-only-numeric" 
                        value="{{ old('tax', isset($product->tax) ? $product->tax : '') }}" maxlength="2">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-tax-money">Tax Money</label>
                      <input type="text" name="tax_money" id="tax_money" class="form-control" value="" readonly>
                      <small class="form-text text-muted">Calculated on Offer Price</small>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-final-price">Final Price <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_final_price"></span>
                      <input type="text" name="final_price" class="form-control" value="{{ old('final_price', isset($product->final_price) ? $product->final_price : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-company-share">Company Earn</label>
                      <input type="text" name="company_share" id="company_share" class="form-control" value="" readonly>
                      <small class="form-text text-muted">Platform Fee + (Platform Fee × Tax%)</small>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-seller-profit">Seller Earn</label>
                      <input type="text" name="seller_profit" id="seller_profit" class="form-control" value="" readonly>
                      <small class="form-text text-muted">Final Price − Company Earn</small>
                    </div>
                  </div>

                  {{-- Package & Dimensions --}}
                  <div class="col-lg-12">
                    <hr style="margin: 8px 0 16px;">
                    <h6 style="font-weight:700; color:#2c3e50; margin-bottom:12px;">
                      📦 Package &amp; Dimensions
                      <small class="text-muted" style="font-weight:400; font-size:12px;">— used by Shiprocket for shipping calculation &amp; label</small>
                    </h6>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-weight">Weight (kg) <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_weight"></span>
                      <input type="number" name="weight" id="input-weight" class="form-control" step="0.001" min="0.001"
                             value="{{ old('weight', isset($product->weight) ? $product->weight : '0.500') }}"
                             placeholder="e.g. 0.500">
                      <small class="form-text text-muted">Dead weight in kg. Minimum 0.5 kg is applied by Shiprocket.</small>
                    </div>
                  </div>
                  <div class="col-lg-6"></div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label">Length (cm) <span class="required-red">*</span></label>
                      <div class="input-group">
                        <input type="number" name="length" class="form-control" step="0.01" min="0.5"
                               value="{{ old('length', isset($product->length) ? $product->length : '10.00') }}"
                               placeholder="10">
                        <div class="input-group-append"><span class="input-group-text">CM</span></div>
                      </div>
                      <small class="form-text text-muted">Min 0.50 cm</small>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label">Breadth / Width (cm) <span class="required-red">*</span></label>
                      <div class="input-group">
                        <input type="number" name="breadth" class="form-control" step="0.01" min="0.5"
                               value="{{ old('breadth', isset($product->breadth) ? $product->breadth : '10.00') }}"
                               placeholder="10">
                        <div class="input-group-append"><span class="input-group-text">CM</span></div>
                      </div>
                      <small class="form-text text-muted">Min 0.50 cm</small>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label">Height (cm) <span class="required-red">*</span></label>
                      <div class="input-group">
                        <input type="number" name="height" class="form-control" step="0.01" min="0.5"
                               value="{{ old('height', isset($product->height) ? $product->height : '10.00') }}"
                               placeholder="10">
                        <div class="input-group-append"><span class="input-group-text">CM</span></div>
                      </div>
                      <small class="form-text text-muted">Min 0.50 cm</small>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div id="volumetricWeightDisplay" style="background:#f0f4ff; border:1px solid #c7d2fe; border-radius:6px; padding:10px 14px; font-size:13px; color:#3730a3; margin-bottom:12px;">
                      Volumetric Weight: <strong id="volWeightVal">0.200 KG</strong>
                      &nbsp;&nbsp;|&nbsp;&nbsp;
                      Applicable Weight (max of dead &amp; volumetric): <strong id="appWeightVal">0.500 KG</strong>
                    </div>
                  </div>
                  <hr style="margin: 0 0 16px;">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-category">Category <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_category"></span>
                      @if(!empty($isSeller) && $categories->count() === 1)
                        {{-- Sellers: locked to their registered business category --}}
                        @php $sellerCatId = $categories->keys()->first(); $sellerCatName = $categories->first(); @endphp
                        <input type="text" class="form-control" value="{{ $sellerCatName }}" readonly style="background:#f8f9fa;">
                        <input type="hidden" name="category_id" id="category_id" value="{{ $sellerCatId }}">
                        <small class="form-text text-muted">Category is fixed to your registered business type.</small>
                      @else
                        <select name="category_id" id="category_id" class="form-control">
                          <option value="">-- Select Category --</option>
                          @foreach($categories as $id => $name)
                            <option value="{{ $id }}"
                              {{ old('category_id', isset($product->category_id) ? $product->category_id : '') == $id ? 'selected' : '' }}>
                              {{ $name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                  {{-- Sub-category (loaded via AJAX for admin; pre-loaded for seller) --}}
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="sub_category_id">Sub-category</label>
                      <select name="sub_category_id" id="sub_category_id" class="form-control">
                        <option value="">-- Select Sub-category --</option>
                        @if(!empty($subCategories))
                          @foreach($subCategories as $sub)
                            <option value="{{ $sub->id }}"
                              {{ old('sub_category_id', isset($product->sub_category_id) ? $product->sub_category_id : '') == $sub->id ? 'selected' : '' }}>
                              {{ $sub->name }}
                            </option>
                          @endforeach
                        @endif
                      </select>
                      <small class="form-text text-muted">Optional — select a category first to load sub-categories.</small>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-company-name">Brand/Company</label>
                      <span class="validation-error" id="error_msg_company"></span>
                      <input type="text" name="company" class="form-control" value="{{ old('company', isset($product->company) ? $product->company : '') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-quantity">Quantity / Stock <span class="required-red">*</span></label>
                      <span class="validation-error" id="error_msg_quantity"></span>
                      <input type="number" name="quantity" id="input-quantity" class="form-control" min="0" step="1"
                             value="{{ old('quantity', isset($product->quantity) ? $product->quantity : 1) }}"
                             placeholder="e.g. 10">
                      <small class="form-text text-muted">Set to 0 to mark the product as Not Available.</small>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-warranty">Warranty</label>
                      <input type="text" name="warranty" id="input-warranty" class="form-control"
                             value="{{ old('warranty', isset($product->warranty) ? $product->warranty : '') }}"
                             placeholder="e.g. 1 Year Manufacturer Warranty">
                      <small class="form-text text-muted">Leave blank if no warranty applies.</small>
                    </div>
                  </div>
                  @if(auth()->user() && auth()->user()->hasRole('Admin'))
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="is_top_deal">Top Deal (Admin)</label>
                      <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" class="custom-control-input" id="is_top_deal" name="is_top_deal" value="1"
                          {{ old('is_top_deal', isset($product->is_top_deal) ? $product->is_top_deal : 0) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_top_deal">Show in Top Deals section</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="top_deal_priority">Deal Priority</label>
                      <input type="number" min="0" max="9999" name="top_deal_priority" id="top_deal_priority" class="form-control"
                        value="{{ old('top_deal_priority', isset($product->top_deal_priority) ? $product->top_deal_priority : 0) }}">
                      <small class="form-text text-muted">Higher value appears first when discounts are similar.</small>
                    </div>
                  </div>
                  @endif
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="form-control-label" for="input-desc">Description</label>
                      <span class="validation-error" id="error_msg_desc"></span>
                      <textarea name="desc" id="desc" class="form-control">{{ old('desc', isset($product->desc) ? $product->desc : '') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">Save</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
              </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@push('js')
<script type="text/javascript">
  $(document).ready(function() {
    // Auto-calculate based on formulas:
    // Platform Fee = Base Price × 10%
    // Offer Price = Base Price + Platform Fee
    // Tax Money = Offer Price × Tax%
    // Final Price = Offer Price + Tax Money
    // Company (10%) = Final Price × 10%
    // Seller Profit = Final Price − Company
    function calculatePrices() {
      var basePrice = parseFloat($('input[name="offer_price"]').val()) || 0;  // Base Price
      var mrp = parseFloat($('input[name="total_price"]').val()) || 0;  // MRP (Cost Price)
      var taxPercent = parseFloat($('input[name="tax"]').val()) || 0;
      
      // Platform Fee = Base Price × 10%
      var platformFee = (basePrice * 10) / 100;
      
      // Offer Price = Base Price + Platform Fee
      var offerPrice = basePrice + platformFee;
      
      // Tax Money = Offer Price × Tax%
      var taxMoney = (offerPrice * taxPercent) / 100;
      
      // Final Price = Offer Price + Tax Money
      var finalPrice = offerPrice + taxMoney;
      
      // Company Earn = Platform Fee + (Platform Fee × Tax%)
      var companyShare = platformFee + (platformFee * taxPercent / 100);
      
      // Seller Profit = Final Price − Company Earn
      var sellerProfit = finalPrice - companyShare;
      
      // Update fields
      $('#platform_fee').val(platformFee ? platformFee.toFixed(2) : '');
      $('#calculated_offer_price').val(offerPrice ? offerPrice.toFixed(2) : '');
      $('#tax_money').val(taxMoney ? taxMoney.toFixed(2) : '');
      $('input[name="final_price"]').val(finalPrice ? finalPrice.toFixed(2) : '');
      $('#company_share').val(companyShare ? companyShare.toFixed(2) : '');
      $('#seller_profit').val(!isNaN(sellerProfit) ? sellerProfit.toFixed(2) : '');
    }
    $('input[name="offer_price"], input[name="tax"], input[name="total_price"]').on('input', calculatePrices);
    calculatePrices();

    // ── Volumetric weight calculator ────────────────────────────────────────
    function updateVolumetric() {
      var l = parseFloat($('input[name="length"]').val()) || 10;
      var b = parseFloat($('input[name="breadth"]').val()) || 10;
      var h = parseFloat($('input[name="height"]').val()) || 10;
      var deadWt = parseFloat($('#input-weight').val()) || 0.5;

      // Shiprocket volumetric formula: L × B × H / 5000
      var volKg = (l * b * h) / 5000;
      var applicable = Math.max(deadWt, volKg, 0.5);

      $('#volWeightVal').text(volKg.toFixed(3) + ' KG');
      $('#appWeightVal').text(applicable.toFixed(3) + ' KG');
    }
    $('input[name="length"], input[name="breadth"], input[name="height"], #input-weight').on('input', updateVolumetric);
    updateVolumetric();

    $("#product_form").submit(function() {
      //e.preventDefault();
      $('.validation-error').hide();
      var for_mode = $("#product_form").attr('for_mode');
      var name = $('input[name="name"]').val();
      //var offer_description = CKEDITOR.instances.offer_description.getData();
      if ($.trim(name) == '') {
        $("#error_msg_name").show().text("product Name is not set!");
        return false;
      } else {
        $('#roles').prop('disabled', false);
        $(this).find("button[type='submit']").prop('disabled', true);
        return true;
      }
    });
    $('.allow-only-numeric').keyup(function() {
      var node = $(this);
      node.val(node.val().replace(/[^0-9]/g,''));
    });

    // ---- Sub-category AJAX loader ----
    var APP_URL = "{{ url('') }}";
    var initialCatId = $('#category_id').val();
    // Preload sub-cats if a category is already selected (edit mode / seller mode)
    if (initialCatId) {
      loadSubCategories(initialCatId, {{ old('sub_category_id', isset($product->sub_category_id) ? $product->sub_category_id : 'null') }});
    }
    $('#category_id').on('change', function() {
      var catId = $(this).val();
      loadSubCategories(catId, null);
    });

    function loadSubCategories(catId, selectedId) {
      var $sel = $('#sub_category_id');
      $sel.html('<option value="">Loading...</option>');
      if (!catId) { $sel.html('<option value="">-- Select Sub-category --</option>'); return; }
      $.getJSON(APP_URL + '/admin/sub-categories-ajax/' + catId, function(data) {
        $sel.html('<option value="">-- Select Sub-category --</option>');
        $.each(data, function(i, sub) {
          var opt = $('<option></option>').val(sub.id).text(sub.name);
          if (selectedId && sub.id == selectedId) opt.prop('selected', true);
          $sel.append(opt);
        });
      }).fail(function() {
        $sel.html('<option value="">-- Select Sub-category --</option>');
      });
    }
  });
</script>
@endpush

@push('js')
<script>
function deleteProductImage(productId, imageId, btn) {
  if (!confirm('Delete this image?')) return;
  var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch('/admin/products/' + productId + '/delete-image/' + imageId, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': token,
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  }).then(function(r) {
    if (r.ok) {
      var wrapper = btn.closest('.d-inline-block');
      if (wrapper) wrapper.remove();
    } else {
      alert('Failed to delete image. Please try again.');
    }
  }).catch(function() {
    alert('Failed to delete image. Please try again.');
  });
}

function previewImages(event) {
  var files = event.target.files;
  var container = document.getElementById('preview-container');
  container.innerHTML = '';
  for (let i = 0; i < files.length; i++) {
    let reader = new FileReader();
    reader.onload = function(e) {
      let img = document.createElement('img');
      img.src = e.target.result;
      img.width = 80;
      img.className = 'img-thumbnail mr-2 mb-2';
      container.appendChild(img);
    }
    reader.readAsDataURL(files[i]);
  }
}
</script>
@endpush