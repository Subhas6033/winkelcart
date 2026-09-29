<?php

namespace App\Http\Controllers;

use App\Mail\OrderShippedNotification;
use App\Mail\SellerOrderShippedNotification;
use App\Models\Order;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ShiprocketShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:order-list|order-edit', ['only' => ['index', 'requestPickup', 'refreshTracking']]);
    }

    /**
     * Show Shiprocket shipping management page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Order::with(['buyer', 'items.product'])
            ->where('deleted', 0)
            ->whereNotNull('shiprocket_shipment_id');

        // If seller, only show their orders
        if ($user->hasRole('Seller')) {
            $query->whereHas('items.product', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('tracking_status', $request->status);
        }

        // Search by order number or AWB
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('awb_code', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'DESC')->paginate(25);

        return view('orders.shiprocket_shipping', compact('orders'));
    }

    /**
     * Seller-facing Shiprocket page (limited to their orders)
     */
    public function sellerIndex(Request $request)
    {
        $user = Auth::user();

        $query = \App\Models\Order::with(['buyer', 'items.product'])
            ->where('deleted', 0)
            ->whereNotNull('shiprocket_shipment_id')
            ->whereHas('items.product', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });

        if ($request->filled('status')) {
            $query->where('tracking_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('awb_code', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'DESC')->get();

        // Return the seller-specific view; the blade expects $shiprocketOrders
        return view('seller.shiprocket-shipping', ['shiprocketOrders' => $orders]);
    }

    /**
     * Request pickup for a shipment
     */
    public function requestPickup(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|integer',
            'order_number' => 'required|string',
        ]);

        $order = Order::where('shiprocket_shipment_id', $request->shipment_id)
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Check permissions
        if (Auth::user()->hasRole('Seller') && $order->items->first()?->product?->created_by != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $shiprocket = new ShiprocketService();
        $result = $shiprocket->requestPickup($order->shiprocket_shipment_id);

        if ($result) {
            $order->update([
                'pickup_requested' => true,
                'pickup_requested_at' => now(),
            ]);

            Log::info('Pickup requested for order', [
                'order_number' => $order->order_number,
                'shipment_id' => $order->shiprocket_shipment_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pickup requested successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to request pickup. Please try again.',
        ]);
    }

    /**
     * Refresh tracking status for an order
     */
    public function refreshTracking(Request $request)
    {
        $request->validate([
            'awb_code' => 'required|string',
            'order_number' => 'required|string',
        ]);

        $order = Order::where('awb_code', $request->awb_code)
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Check permissions
        if (Auth::user()->hasRole('Seller') && $order->items->first()?->product?->created_by != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $shiprocket = new ShiprocketService();
        $trackingData = $shiprocket->trackShipment($order->awb_code);

        if ($trackingData) {
            $trackingStatus = $trackingData['tracking_status'] ?? $order->tracking_status;
            $trackingHistory = $trackingData['tracking_history'] ?? [];

            $order->update([
                'tracking_status' => $trackingStatus,
                'tracking_details' => json_encode($trackingHistory, JSON_PRETTY_PRINT),
            ]);

            // Update order status based on tracking
            $orderStatusMap = [
                'Delivered' => 'Delivered',
                'In Transit' => 'Shipped',
                'Out for Delivery' => 'Shipped',
                'RTO' => 'RTO',
            ];

            $newOrderStatus = $orderStatusMap[$trackingStatus] ?? null;
            if ($newOrderStatus && $newOrderStatus !== $order->order_status) {
                $order->order_status = $newOrderStatus;
                $order->save();

                \App\Models\OrderStatusTimeline::create([
                    'order_id' => $order->id,
                    'status' => $newOrderStatus,
                    'note' => 'Status updated via Shiprocket tracking refresh',
                    'changed_by' => Auth::id(),
                    'changed_by_role' => Auth::user()->roles->pluck('name')->first(),
                ]);
            }

            Log::info('Tracking refreshed', [
                'order_number' => $order->order_number,
                'awb_code' => $order->awb_code,
                'status' => $trackingStatus,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tracking refreshed',
                'data' => [
                    'tracking_status' => $trackingStatus,
                    'tracking_history' => $trackingHistory,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to refresh tracking. Please try again.',
        ]);
    }

    /**
     * Manually create Shiprocket shipment for an existing order
     */
    public function createShipment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::with(['items.product.seller', 'buyer.user_info'])
            ->findOrFail($request->order_id);

        // Check permissions
        if (Auth::user()->hasRole('Seller') && $order->items->first()?->product?->created_by != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if already has shipment
        if ($order->shiprocket_shipment_id) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment already exists for this order',
            ]);
        }

        $shippingData = [
            'weight' => $request->weight ?? 0.5,
            'length' => $request->length ?? 10,
            'breadth' => $request->breadth ?? 10,
            'height' => $request->height ?? 10,
            'hsn' => $request->hsn ?? 0,
            'comment' => 'Order #' . $order->order_number,
        ];

        $shiprocket = new ShiprocketService();
        $result = $shiprocket->processShipment($order, $shippingData);

        if ($result['success']) {
            $order->update([
                'shiprocket_order_id' => $result['order_id'],
                'shiprocket_shipment_id' => $result['shipment_id'],
                'awb_code' => $result['awb_code'],
                'courier_name' => 'Shiprocket',
                'shipping_label_url' => $result['label_url'],
                'shipping_invoice_url' => $result['invoice_url'],
                'tracking_status' => 'Label Created',
                'pickup_requested' => true,
                'pickup_requested_at' => now(),
            ]);

            OrderStatusTimeline::create([
                'order_id' => $order->id,
                'status' => 'Processing',
                'note' => 'Shipment created with Shiprocket. AWB: ' . ($result['awb_code'] ?? 'N/A'),
                'changed_by' => Auth::id(),
                'changed_by_role' => Auth::user()->roles->pluck('name')->first(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shipment created successfully',
                'data' => $result,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create shipment: ' . implode(', ', $result['errors']),
        ]);
    }

    /**
     * Manually assign AWB / courier to an existing Shiprocket shipment
     */
    public function assignAwb(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|integer',
            'order_number' => 'required|string',
        ]);

        $order = Order::where('shiprocket_shipment_id', $request->shipment_id)
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        if (Auth::user()->hasRole('Seller') && $order->items->first()?->product?->created_by != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $shiprocket = new ShiprocketService();

        // Try to get a courier ID via serviceability first
        $courierId = null;
        try {
            $userInfo        = $order->buyer?->user_info;
            $deliveryPincode = preg_replace('/\D/', '', (string) ($userInfo?->pincode ?? ''));
            $firstItem       = $order->items->first();
            $sellerKyc       = $firstItem?->product?->seller?->kycVerification;
            $pickupPincode   = preg_replace('/\D/', '', (string) ($sellerKyc?->pickup_pincode
                ?? $firstItem?->product?->seller?->user_info?->pincode ?? ''));
            $paymentMethod   = strtolower($order->payment_method ?? '') === 'cod' ? 'COD' : 'Pre-paid';
            $weight          = max(round($order->items->sum(fn($i) => floatval($i->product?->weight ?? 0.5) * $i->quantity), 2), 0.5);

            if (strlen($pickupPincode) === 6 && strlen($deliveryPincode) === 6) {
                $courierId = $shiprocket->getRecommendedCourierId($pickupPincode, $deliveryPincode, $weight, $paymentMethod);
            }
        } catch (\Exception $e) {
            Log::warning('Courier lookup failed during manual assign: ' . $e->getMessage());
        }

        $result = $shiprocket->assignAwb($order->shiprocket_shipment_id, $courierId);

        if ($result) {
            // Check for explicit AWB assignment failure with Shiprocket error message
            if (isset($result['awb_assign_status']) && $result['awb_assign_status'] == 0) {
                $srError = $result['message']
                    ?? ($result['response']['data']['awb_assign_error'] ?? 'AWB assignment rejected by Shiprocket.');
                Log::error('AWB assignment rejected by Shiprocket', [
                    'order_number' => $order->order_number,
                    'shipment_id'  => $order->shiprocket_shipment_id,
                    'sr_error'     => $srError,
                    'full_response' => $result,
                ]);
                return response()->json(['success' => false, 'message' => $srError]);
            }

            // Try immediate response first
            $awbCode = $result['awb_code']
                ?? ($result['response']['data']['awb_code'] ?? null);

            // Shiprocket sometimes assigns AWB async — poll once after 5 seconds
            if (!$awbCode && $order->shiprocket_order_id) {
                sleep(5);
                $orderData = $shiprocket->getOrder((int) $order->shiprocket_order_id);
                $awbCode   = $orderData['awb_code']
                    ?? ($orderData['shipments']['awb_code'] ?? null);
            }

            if ($awbCode) {
                $order->update(['awb_code' => $awbCode]);

                Log::info('AWB manually assigned', [
                    'order_number' => $order->order_number,
                    'awb_code'     => $awbCode,
                ]);

                // Notify buyer and seller that order has been shipped
                try {
                    $order->load(['buyer', 'items.product.seller']);
                    if ($order->buyer && $order->buyer->email) {
                        Mail::to($order->buyer->email)->send(new OrderShippedNotification($order->buyer, $order));
                    }
                    $seller = $order->items->first()?->product?->seller;
                    if ($seller && $seller->email) {
                        Mail::to($seller->email)->send(new SellerOrderShippedNotification($seller, $order));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send AWB shipped emails: ' . $e->getMessage());
                }

                return response()->json([
                    'success'  => true,
                    'message'  => 'AWB assigned successfully',
                    'awb_code' => $awbCode,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'AWB request accepted by Shiprocket but AWB not yet generated. Wait 1-2 minutes and refresh the page.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to assign AWB. Check Shiprocket dashboard.',
        ]);
    }

    /**
     * Generate shipping label for a shipment
     */
    public function generateLabel(Request $request)
    {
        $request->validate([
            'shipment_id'  => 'required|integer',
            'order_number' => 'required|string',
        ]);

        $order = Order::where('shiprocket_shipment_id', $request->shipment_id)
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $shiprocket = new ShiprocketService();
        $labelUrl   = $shiprocket->generateLabel([$order->shiprocket_shipment_id]);

        if ($labelUrl) {
            $order->update(['shipping_label_url' => $labelUrl]);

            return response()->json([
                'success'   => true,
                'message'   => 'Label generated',
                'label_url' => $labelUrl,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate label. Ensure AWB is assigned first.',
        ]);
    }

    /**
     * Generate invoice for a Shiprocket order
     */
    public function generateInvoice(Request $request)
    {
        $request->validate([
            'shiprocket_order_id' => 'required|integer',
            'order_number'        => 'required|string',
        ]);

        $order = Order::where('shiprocket_order_id', $request->shiprocket_order_id)
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $shiprocket  = new ShiprocketService();
        $invoiceUrl  = $shiprocket->generateInvoice([$order->shiprocket_order_id]);

        if ($invoiceUrl) {
            $order->update(['shipping_invoice_url' => $invoiceUrl]);

            return response()->json([
                'success'     => true,
                'message'     => 'Invoice generated',
                'invoice_url' => $invoiceUrl,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate invoice.',
        ]);
    }
}

