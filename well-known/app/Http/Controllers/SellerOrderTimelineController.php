<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\OrderStatusTimeline;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderTimelineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:order-list|order-edit');
    }

    private function ensureSellerAccess()
    {
        $user = Auth::user();
        abort_unless($user && $user->hasRole('Seller'), 403, 'Unauthorized - Seller access required');
    }

    public function index(Request $request)
    {
        $this->ensureSellerAccess();
        $user = Auth::user();

        $query = Order::with(['buyer', 'timelines.changedBy', 'items.product'])
            ->where('deleted', 0)
            ->whereHas('items.product', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhereHas('buyer', function ($buyerQuery) use ($search) {
                        $buyerQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $orders = $query->paginate(20)->appends($request->query());
        $statusOptions = ['Placed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        $sellerId = $user->id;

        return view('seller.order_timelines.index', compact('orders', 'statusOptions', 'sellerId'));
    }

    public function store(Request $request, Order $order)
    {
        $this->ensureSellerAccess();
        $user = Auth::user();

        // Verify this order contains at least one product from this seller
        $hasSellerProduct = $order->items()->whereHas('product', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })->exists();

        abort_unless($hasSellerProduct, 403, 'You can only update timelines for orders containing your products');
        abort_if((int) $order->deleted === 1, 404);

        $validated = $request->validate([
            'status' => 'required|in:Placed,Processing,Shipped,Delivered,Cancelled',
            'note' => 'nullable|string|max:2000',
        ]);

        $previousTimeline = $order->timelines()->latest('id')->first();

        $timeline = OrderStatusTimeline::create([
            'order_id' => $order->id,
            'status' => $validated['status'],
            'note' => $validated['note'] ?? null,
            'changed_by' => Auth::id(),
            'changed_by_role' => 'Seller',
        ]);

        AuditLog::record(
            'order.timeline_status_added_by_seller',
            'order',
            $order->id,
            [
                'previous_timeline_status' => $previousTimeline->status ?? null,
            ],
            [
                'timeline_status' => $timeline->status,
                'timeline_note' => $timeline->note,
                'seller_id' => $user->id,
            ]
        );

        // Create notification based on status type
        $notificationType = match($validated['status']) {
            'Shipped' => AdminNotification::TYPE_ORDER_SHIPPED,
            'Delivered' => AdminNotification::TYPE_ORDER_DELIVERED,
            'Cancelled' => AdminNotification::TYPE_ORDER_CANCELLED,
            default => null,
        };
        
        if ($notificationType) {
            AdminNotification::notify(
                $notificationType,
                'Order ' . $validated['status'] . ' by Seller',
                'Order #' . $order->order_number . ' has been marked as ' . $validated['status'] . ' by Seller ' . $user->name,
                [
                    'link' => route('admin.order_timelines.index'),
                    'related_id' => $order->id,
                    'related_type' => Order::class,
                ]
            );
        }

        return back()->with('success', 'Order timeline updated successfully.');
    }
}
