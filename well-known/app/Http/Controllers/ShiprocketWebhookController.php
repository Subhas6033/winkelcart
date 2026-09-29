<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusTimeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShiprocketWebhookController extends Controller
{
    /**
     * Handle Shiprocket webhook notifications
     *
     * Shiprocket sends webhook events for:
     * - Order updates
     * - Shipment status changes (In Transit, Delivered, RTO, etc.)
     * - Pickup scheduled
     * - Delivery failed
     */
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        Log::info('Shiprocket webhook received', [
            'data' => $data,
        ]);

        // Extract order/shipment data from webhook
        $awbCode = $data['awb_code'] ?? null;
        $shipmentId = $data['shipment_id'] ?? null;
        $orderId = $data['order_id'] ?? null;
        $status = $data['status'] ?? null;
        $trackingStatus = $data['tracking_status'] ?? null;

        // Find order by AWB code or shipment ID
        $order = null;

        if ($awbCode) {
            $order = Order::where('awb_code', $awbCode)->first();
        } elseif ($shipmentId) {
            $order = Order::where('shiprocket_shipment_id', $shipmentId)->first();
        }

        if (!$order) {
            Log::warning('Shiprocket webhook: Order not found', [
                'awb_code' => $awbCode,
                'shipment_id' => $shipmentId,
            ]);
            return response()->json(['status' => 'Order not found'], 404);
        }

        // Update order tracking status
        $this->updateOrderTracking($order, $data);

        return response()->json(['status' => 'Webhook processed successfully']);
    }

    /**
     * Update order tracking information from webhook data
     */
    private function updateOrderTracking(Order $order, array $data): void
    {
        $oldTrackingStatus = $order->tracking_status;
        $newTrackingStatus = $data['tracking_status'] ?? $order->tracking_status;

        // Update order fields
        $order->update([
            'tracking_status' => $newTrackingStatus,
            'tracking_details' => json_encode($data['tracking_history'] ?? [], JSON_PRETTY_PRINT),
        ]);

        // Map Shiprocket status to order status
        $orderStatusMap = [
            'Delivered' => 'Delivered',
            'In Transit' => 'Shipped',
            'Out for Delivery' => 'Shipped',
            'RTO' => 'RTO',
            'Lost' => 'Lost',
            'Damaged' => 'Damaged',
            'Delivery Failed' => 'Delivery Failed',
        ];

        $newOrderStatus = $orderStatusMap[$newTrackingStatus] ?? null;

        if ($newOrderStatus && $newOrderStatus !== $order->order_status) {
            $order->order_status = $newOrderStatus;
            $order->save();

            // Add timeline entry
            OrderStatusTimeline::create([
                'order_id' => $order->id,
                'status' => $newOrderStatus,
                'note' => 'Status updated via Shiprocket: ' . ($data['remarks'] ?? 'Tracking updated'),
                'changed_by' => null, // System update
                'changed_by_role' => 'Shiprocket Webhook',
            ]);
        }

        Log::info('Order tracking updated', [
            'order_number' => $order->order_number,
            'old_status' => $oldTrackingStatus,
            'new_status' => $newTrackingStatus,
        ]);
    }
}
