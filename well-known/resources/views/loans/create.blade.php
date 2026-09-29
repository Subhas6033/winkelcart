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
                <h5 class="info-box-text mb-0"><b>Loans</b></h5>
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
          @if(Session::has('success'))
          <div>
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
          </div>
          @endif
          @if(Session::has('error'))
          <div>
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('error') }}</p>
          </div>
          @endif
          <div class="card-body">
            <form action="{{ route('loans.store') }}" for_mode="{{ $last_segment }}" id="loan_form" name="loan_form" method="POST">
              @csrf
              <div class="pl-lg-4">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-bank_id">Bank</label>
                      <span class="validation-error" id="error_msg_bank_id"></span>
                      <input type="hidden" name="loan_id" id="loan_id" class="form-control" value="{{ isset($loan->id)  ? $loan->id : ''; }}">
                      <select name="bank_id" id="bank_id" class="form-control">
                        <option value="">Select Bank</option>
                        @if(!$bank->isEmpty())
                        @foreach($bank as $k=>$v)
                        <option value="{{ $v->id }}" {{isset($loan) ? $loan->bank_id == $v->id ? 'selected' : '' : ''}}>{{ $v->name }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-sanction_date">Sanction Date</label>
                      <span class="validation-error" id="error_msg_sanction_date"></span>
                      <input type="text" name="sanction_date" id="sanction_date" class="form-control calender" data-provide="datepicker" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ isset($loan) ? date('d-M-Y', strtotime($loan->sanction_date)) : old('sanction_date') }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-borrower_name">Borrower Name</label>
                      <span class="validation-error" id="error_msg_borrower_name"></span>
                      <input type="text" name="borrower_name" id="borrower_name" class="form-control" value="{{ isset($loan) ? $loan->borrower_name : old('borrower_name') }}">
                      <!-- <input type="text" name="borrower_name" id="borrower_name" class="form-control" value="{{ old('borrower_name') }}"> -->
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-coborrower_name">Co Borrower Name</label>
                      <span class="validation-error" id="error_msg_coborrower_name"></span>
                      <input type="text" name="coborrower_name" id="coborrower_name" class="form-control" value="{{ isset($loan) ? $loan->co_borrower_name : old('coborrower_name') }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-product_id">Product</label>
                      <span class="validation-error" id="error_msg_product_id"></span>
                      <select name="product_id" id="product_id" class="form-control">
                        <option value="">Select product</option>
                        @if(!$product->isEmpty())
                        @foreach($product as $k=>$v)
                        <option value="{{ $v->id }}" {{isset($loan) ? $loan->product_id == $v->id ? 'selected' : '' : ''}}>{{ $v->name }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-ammount_sanction">Amount Sanctioned</label>
                      <span class="validation-error" id="error_msg_ammount_sanction"></span>
                      <input type="text" name="ammount_sanction" id="ammount_sanction" class="form-control" value="{{ isset($loan) ? $loan->amount_sactioin : old('ammount_sanction') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="form-group">
                      <label class="form-control-label" for="input-tenour">Tenure (Months)</label>
                      <span class="validation-error" id="error_msg_tenour"></span>
                      <input type="text" name="tenour" id="tenour" class="form-control" value="{{ isset($loan) ? $loan->tenour : old('tenour') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="form-group">
                      <label class="form-control-label" for="input-repo">Repo (%)</label>
                      <span class="validation-error" id="error_msg_repo"></span>
                      <input type="text" name="repo" id="repo" class="form-control" value="{{ isset($loan) ? $loan->repo : old('repo') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="form-group">
                      <label class="form-control-label" for="input-mclr">MCLR (%)</label>
                      <span class="validation-error" id="error_msg_mclr"></span>
                      <input type="text" name="mclr" id="mclr" class="form-control" value="{{ isset($loan) ? $loan->mclr : old('mclr') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="form-group">
                      <label class="form-control-label" for="input-base">Base (%)</label>
                      <span class="validation-error" id="error_msg_base"></span>
                      <input type="text" name="base" id="base" class="form-control" value="{{ isset($loan) ? $loan->base : old('base') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="form-group">
                      <label class="form-control-label" for="input-plr">PLR (%)</label>
                      <span class="validation-error" id="error_msg_plr"></span>
                      <input type="text" name="plr" id="plr" class="form-control" value="{{ isset($loan) ? $loan->plr : old('plr') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="form-group">
                      <label class="form-control-label" for="input-spread_rate">Spread Rate (%)</label>
                      <span class="validation-error" id="error_msg_spread_rate"></span>
                      <input type="text" name="spread_rate" id="spread_rate" class="form-control" value="{{ isset($loan) ? $loan->spread_rate : old('spread_rate') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="input-calculate_amount">Calculate Amount</label>
                      <span class="validation-error" id="error_msg_calculate_amount"></span>
                      <input type="text" name="calculate_amount" id="calculate_amount" class="form-control" value="{{ isset($loan) ? $loan->calculate_rate : old('calculate_amount') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="input-amount_emi">Amount of EMI</label>
                      <span class="validation-error" id="error_msg_amount_emi"></span>
                      <input type="text" name="amount_emi" id="amount_emi" class="form-control" value="{{ isset($loan) ? $loan->amount_emi : old('amount_emi') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');this.value = this.value.replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-security">Security</label>
                      <span class="validation-error" id="error_msg_security"></span>
                      <textarea name="security" id="security" class="form-control">{{ isset($loan) ? $loan->security : old('any_specialsecurity_condition') }}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-closure_charge">Closure Charge</label>
                      <span class="validation-error" id="error_msg_closure_charge"></span>
                      <textarea name="closure_charge" id="closure_charge" class="form-control">{{ isset($loan) ? $loan->closure_charge : old('closure_charge') }}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label" for="input-any_special_condition">Any Special Condition</label>
                      <span class="validation-error" id="error_msg_any_special_condition"></span>
                      <textarea name="any_special_condition" id="any_special_condition" class="form-control">{{ isset($loan) ? $loan->any_special_condition : old('any_special_condition') }}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="input-emi_date">EMI Date</label>
                      <span class="validation-error" id="error_msg_emi_date"></span>
                      <input type="text" name="emi_date" id="emi_date" class="form-control calender" data-provide="datepicker" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ isset($loan) ? date('d-M-Y', strtotime($loan->emi_date)) : old('emi_date') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="input-emi_bank_id">EMI Bank</label>
                      <span class="validation-error" id="error_msg_emi_bank_id"></span>
                      <select name="emi_bank_id" id="emi_bank_id" class="form-control">
                        <option value="">Select Bank</option>
                        @if(!$bank->isEmpty())
                        @foreach($bank as $k=>$v)
                        <option value="{{ $v->id }}" {{isset($loan) ? $loan->emi_bank == $v->id ? 'selected' : '' : ''}}>{{ $v->name }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="input-emi_bank_acc">Account No</label>
                      <span class="validation-error" id="error_msg_emi_bank_acc"></span>
                      <input type="text" name="emi_bank_acc" id="emi_bank_acc" class="form-control"       autocomplete="off" value="{{ isset($loan) ? $loan->emi_acc_no : old('emi_bank_acc') }}">
                      
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label" for="input-distribution_date">Distribution Date</label>
                      <span class="validation-error" id="error_msg_distribution_date"></span>
                      <input type="text" name="distribution_date" id="distribution_date" class="form-control calender" data-provide="datepicker" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ isset($loan) ? date('d-M-Y', strtotime($loan->distribution_date)) : old('distribution_date') }}">
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">Save</button>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-danger my-4">Cancel</a>
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
    $("#loan_form").submit(function() {
      //e.preventDefault();
      $('.validation-error').hide();
      var for_mode = $("#loan_form").attr('for_mode');
      var bank_id = $("#bank_id").val();
      var sanction_date = $('input[name="sanction_date"]').val();
      var borrower_name = $('input[name="borrower_name"]').val();
      var coborrower_name = $('input[name="coborrower_name"]').val();
      var product_id = $("#product_id").val();
      var ammount_sanction = $('input[name="ammount_sanction"]').val();
      var tenour = $('input[name="tenour"]').val();
      var repo = $('input[name="repo"]').val();
      var mclr = $('input[name="mclr"]').val();
      var base = $('input[name="base"]').val();
      var plr = $('input[name="plr"]').val();
      var spread_rate = $('input[name="spread_rate"]').val();

      var calculate_amount = $('input[name="calculate_amount"]').val();
      var amount_emi = $('input[name="amount_emi"]').val();
      var emi_date = $('input[name="emi_date"]').val();
      var emi_bank_id = $("#emi_bank_id").val();
      var emi_bank_acc = $('input[name="emi_bank_acc"]').val();
      var distribution_date = $('input[name="distribution_date"]').val();
      //var offer_description = CKEDITOR.instances.offer_description.getData();
      //alert(product_id);
      if ($.trim(bank_id) == '') {
        $("#error_msg_bank_id").show().text("Bank is not set!");
        return false;
      } else if ($.trim(sanction_date) == '') {
        $("#error_msg_sanction_date").show().text("Date is not set!");
        return false;
      } else if ($.trim(borrower_name) == '') {
        $("#error_msg_borrower_name").show().text("Borrower name is not set!");
        return false;
      } else if ($.trim(coborrower_name) == '') {
        $("#error_msg_coborrower_name").show().text("Co borrower name is not set!");
        return false;
      } else if ($.trim(product_id) == '') {
        $("#error_msg_product_id").show().text("Product is not set!");
        return false;
      } else if ($.trim(ammount_sanction) == '') {
        $("#error_msg_ammount_sanction").show().text("Ammount sanction is not set!");
        return false;
      } else if ($.trim(tenour) == '') {
        $("#error_msg_tenour").show().text("is not set!");
        return false;
      } else if ($.trim(repo) == '') {
        $("#error_msg_repo").show().text("is not set!");
        return false;
      } else if ($.trim(mclr) == '') {
        $("#error_msg_mclr").show().text("is not set!");
        return false;
      } else if ($.trim(base) == '') {
        $("#error_msg_base").show().text("is not set!");
        return false;
      } else if ($.trim(plr) == '') {
        $("#error_msg_plr").show().text("is not set!");
        return false;
      } else if ($.trim(spread_rate) == '') {
        $("#error_msg_spread_rate").show().text("is not set!");
        return false;
      } else {
        $(this).find("button[type='submit']").prop('disabled', true);
        return true;
      }

    });
  });
</script>
@endpush