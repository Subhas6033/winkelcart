@extends('layouts.app')

@section('content')
@php($isSellerPanel = $isSellerPanel ?? false)
<div class="content-wrapper">
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!$isSellerPanel)
        <div class="card mb-4">
            <div class="card-header"><strong>Create Seller Settlement</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.seller_settlements.store') }}" class="row">
                    @csrf
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Seller</label>
                        <select name="seller_id" class="form-control" required>
                            <option value="">Select Seller</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->name }} ({{ $seller->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Period Start</label>
                        <input type="date" name="period_start" class="form-control" value="{{ old('period_start') }}" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Period End</label>
                        <input type="date" name="period_end" class="form-control" value="{{ old('period_end') }}" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Gross Amount</label>
                        <input type="number" name="gross_amount" class="form-control" step="0.01" min="0" value="{{ old('gross_amount') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Commission Amount</label>
                        <input type="number" name="commission_amount" class="form-control" step="0.01" min="0" value="{{ old('commission_amount', 0) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Net Amount (optional)</label>
                        <input type="number" name="net_amount" class="form-control" step="0.01" min="0" value="{{ old('net_amount') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Reference No (optional)</label>
                        <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Note (optional)</label>
                        <input type="text" name="note" class="form-control" value="{{ old('note') }}">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Create</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <strong>{{ $isSellerPanel ? 'My Settlements' : 'Seller Payout & Settlement' }}</strong>
                    <form method="GET" action="{{ $isSellerPanel ? route('seller.seller_settlements.index') : route('admin.seller_settlements.index') }}" class="form-inline mt-2 mt-md-0">
                        @if(!$isSellerPanel)
                        <select name="seller_id" class="form-control mr-2">
                            <option value="">All Sellers</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ (string)request('seller_id') === (string)$seller->id ? 'selected' : '' }}>
                                    {{ $seller->name }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                        <select name="status" class="form-control mr-2">
                            <option value="">All Status</option>
                            @foreach(['Pending', 'Processing', 'Paid', 'Hold', 'Failed'] as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Seller</th>
                                <th>Period</th>
                                <th>Amounts</th>
                                <th>Status</th>
                                <th>Payment</th>
                                @if(!$isSellerPanel)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settlements as $settlement)
                                <tr>
                                    <td>{{ $settlement->id }}</td>
                                    <td>
                                        {{ $settlement->seller->name ?? 'N/A' }}<br>
                                        <small>{{ $settlement->seller->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        {{ $settlement->period_start ? $settlement->period_start->format('d M Y') : '-' }}<br>
                                        to {{ $settlement->period_end ? $settlement->period_end->format('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        Gross: Rs. {{ number_format((float)$settlement->gross_amount, 2) }}<br>
                                        Commission: Rs. {{ number_format((float)$settlement->commission_amount, 2) }}<br>
                                        <strong>Net: Rs. {{ number_format((float)$settlement->net_amount, 2) }}</strong>
                                    </td>
                                    <td><span class="badge badge-info">{{ $settlement->status }}</span></td>
                                    <td>
                                        Ref: {{ $settlement->reference_no ?: '-' }}<br>
                                        Paid At: {{ $settlement->paid_at ? $settlement->paid_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    @if(!$isSellerPanel)
                                    <td style="min-width: 270px;">
                                        <form method="POST" action="{{ route('admin.seller_settlements.update_status', $settlement->id) }}">
                                            @csrf
                                            <div class="mb-2">
                                                <select name="status" class="form-control form-control-sm" required>
                                                    @foreach(['Pending', 'Processing', 'Paid', 'Hold', 'Failed'] as $status)
                                                        <option value="{{ $status }}" {{ $settlement->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="reference_no" class="form-control form-control-sm" value="{{ $settlement->reference_no }}" placeholder="Reference no">
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="note" class="form-control form-control-sm" value="{{ $settlement->note }}" placeholder="Note">
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isSellerPanel ? 6 : 7 }}" class="text-center py-4">No settlements found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $settlements->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@if(!$isSellerPanel)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sellerField = document.querySelector('select[name="seller_id"]');
    const startField = document.querySelector('input[name="period_start"]');
    const endField = document.querySelector('input[name="period_end"]');
    const grossField = document.querySelector('input[name="gross_amount"]');
    const commissionField = document.querySelector('input[name="commission_amount"]');
    const netField = document.querySelector('input[name="net_amount"]');

    if (!sellerField || !startField || !endField || !grossField || !commissionField || !netField) {
        return;
    }

    function autoFillAmounts() {
        const seller = sellerField.value;
        const start = startField.value;
        const end = endField.value;
        if (!seller || !start || !end) return;
        grossField.value = '';
        commissionField.value = '';
        netField.value = '';
        fetch(`{{ route('admin.seller_settlements.auto_calc') }}?seller_id=${seller}&period_start=${start}&period_end=${end}`)
            .then(r => r.json())
            .then(data => {
                grossField.value = data.gross_amount;
                commissionField.value = data.commission_amount;
                netField.value = data.net_amount;
            });
    }
    sellerField.addEventListener('change', autoFillAmounts);
    startField.addEventListener('change', autoFillAmounts);
    endField.addEventListener('change', autoFillAmounts);
});
</script>
@endpush
@endif
