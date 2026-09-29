<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\OrderStatusTimeline;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrderTimelineController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    private function ensureAdminAccess()
    {
        abort_unless(Auth::user() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin')), 403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $query = Order::with(['buyer', 'timelines.changedBy'])
            ->where('deleted', 0)
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

        return view('admin.order_timelines.index', compact('orders', 'statusOptions'));
    }

    public function store(Request $request, Order $order)
    {
        $this->ensureAdminAccess();

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
            'changed_by_role' => Auth::user()->roles->pluck('name')->first(),
        ]);

        // Send email to customer for status updates
        if (in_array($validated['status'], ['Processing', 'Shipped', 'Delivered'])) {
            try {
                $buyer = $order->buyer;
                if ($buyer && $buyer->email) {
                    \Mail::to($buyer->email)->send(
                        new \App\Mail\OrderStatusUpdateNotification($buyer, $order, $validated['status'], $validated['note'] ?? null)
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send order status update email: ' . $e->getMessage());
            }

            // Also notify seller when order is Shipped
            if ($validated['status'] === 'Shipped') {
                try {
                    $seller = $order->items()->with('product.seller')->get()->first()?->product?->seller;
                    if ($seller && $seller->email) {
                        \Mail::to($seller->email)->send(new \App\Mail\SellerOrderShippedNotification($seller, $order));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send shipped email to seller: ' . $e->getMessage());
                }
            }
        }

        AuditLog::record(
            'order.timeline_status_added',
            'order',
            $order->id,
            [
                'previous_timeline_status' => $previousTimeline->status ?? null,
            ],
            [
                'timeline_status' => $timeline->status,
                'timeline_note' => $timeline->note,
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
                'Order ' . $validated['status'],
                'Order #' . $order->order_number . ' has been marked as ' . $validated['status'] . ' by Admin',
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
