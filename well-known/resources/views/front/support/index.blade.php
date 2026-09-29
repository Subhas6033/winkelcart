@extends('front.layouts.app')

@section('content')
<div class="container py-4" style="max-width: 1100px;">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-3">Help & Support</h4>
                    <p class="text-muted">Create a support ticket and our team will get back to you.</p>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @foreach (['name', 'email', 'order_number', 'subject', 'message'] as $field)
                        @error($field)
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    @endforeach

                    <form action="{{ route('support.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order Number</label>
                            <select name="order_number" class="form-control" required>
                                <option value="">Select your order number</option>
                                @if(auth()->check() && isset($userOrders))
                                    @foreach($userOrders as $orderNumber)
                                        <option value="{{ $orderNumber }}" {{ old('order_number') === $orderNumber ? 'selected' : '' }}>
                                            {{ $orderNumber }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @if(!auth()->check())
                                <small class="text-muted">Please login to choose your order number.</small>
                            @elseif(isset($userOrders) && $userOrders->isEmpty())
                                <small class="text-muted">No orders found in your account yet.</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success" {{ (auth()->check() && isset($userOrders) && $userOrders->isEmpty()) ? 'disabled' : '' }}>
                            Submit Ticket
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">My Support Tickets</h5>

                    @if(!auth()->check())
                        <p class="text-muted mb-0">Login to view your submitted tickets.</p>
                    @elseif($tickets && $tickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order Number</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Opening Date</th>
                                        <th>Closing Date</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->id }}</td>
                                            <td>{{ $ticket->order_number }}</td>
                                            <td>{{ $ticket->subject }}</td>
                                            <td><span class="badge bg-secondary">{{ $ticket->status }}</span></td>
                                            <td>{{ $ticket->created_at ? (\Carbon\Carbon::parse($ticket->created_at)->format('d M Y')) : '-' }}</td>
                                            <td>
                                                @if ($ticket->resolved_at)
                                                    {{ (\Carbon\Carbon::parse($ticket->resolved_at)->format('d M Y H:i')) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $ticket->admin_note ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $tickets->links() }}
                    @else
                        <p class="text-muted mb-0">You have not submitted any tickets yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
