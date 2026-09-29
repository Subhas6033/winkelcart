@extends('layouts.app')

@section('content')
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

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <strong>Order Status Timeline - My Products</strong>
                <form method="GET" action="{{ route('seller.order_timelines.index') }}" class="form-inline mt-2 mt-md-0">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search order/buyer">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Buyer</th>
                                <th>My Products in Order</th>
                                <th>My Amount</th>
                                <th>Timeline History</th>
                                <th>Add Status Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                @php
                                    $latestTimeline = $order->timelines->last();
                                    $recentTimeline = $order->timelines->sortByDesc('id')->take(5);
                                    // Filter items to show only this seller's products
                                    $myItems = $order->items->filter(fn($item) => $item->product && $item->product->created_by == $sellerId);
                                    $myTotal = $myItems->sum(fn($item) => $item->price * $item->quantity);
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong><br>
                                        <small>{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}</small>
                                    </td>
                                    <td>
                                        {{ $order->buyer->name ?? 'N/A' }}<br>
                                        <small>{{ $order->buyer->email ?? '-' }}</small>
                                    </td>
                                    <td style="min-width: 200px;">
                                        @foreach($myItems as $item)
                                            <div class="mb-1">
                                                <strong>{{ $item->product->name ?? 'N/A' }}</strong><br>
                                                <small>{{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</small>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>₹{{ number_format($myTotal, 2) }}</td>
                                    <td style="min-width: 260px;">
                                        @if($recentTimeline->count() > 0)
                                            @foreach($recentTimeline as $timeline)
                                                <div class="mb-1">
                                                    <span class="badge badge-info">{{ $timeline->status }}</span>
                                                    <small>{{ $timeline->created_at ? $timeline->created_at->format('d M Y H:i') : '' }}</small>
                                                    @if($timeline->note)
                                                        <div><small>{{ $timeline->note }}</small></div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="badge badge-secondary">Placed</span>
                                            <small>{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '' }}</small>
                                        @endif
                                    </td>
                                    <td style="min-width: 300px;">
                                        <form method="POST" action="{{ route('seller.order_timelines.store', $order->id) }}">
                                            @csrf
                                            <div class="mb-2">
                                                <select name="status" class="form-control form-control-sm" required>
                                                    @foreach($statusOptions as $status)
                                                        <option value="{{ $status }}" {{ ($latestTimeline && $latestTimeline->status === $status) ? 'selected' : '' }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="note" class="form-control form-control-sm" placeholder="Optional note">
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary">Add Timeline Entry</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No orders found with your products.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
