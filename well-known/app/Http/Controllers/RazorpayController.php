<?php

namespace App\Http\Controllers;

use App\Mail\AdminMembershipPaidNotification;
use App\Mail\SellerMembershipPaidNotification;
use App\Models\Booking;
use App\Models\Order;
use App\Models\RazorpayPayment;
use App\Models\SellerKycVerification;
use App\Models\User;
use App\Services\ShiprocketService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    private ?Api $razorpay = null;

    public function __construct()
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if ($key && $secret) {
            try {
                $this->razorpay = new Api($key, $secret);
            } catch (\Exception $e) {
                Log::error('Razorpay API init failed: ' . $e->getMessage());
            }
        } else {
            Log::error('Razorpay credentials missing. RAZORPAY_KEY or RAZORPAY_SECRET not set in .env');
        }
    }

    /**
     * Create a Razorpay order for any payment type.
     *
     * POST /razorpay/create-order
     * Body: { type: membership|order|booking, reference_id: int, amount: numeric }
     */
    public function createOrder(Request $request)
    {
        if (!$this->razorpay) {
            return response()->json(['error' => 'Payment gateway not configured. Please contact support.'], 503);
        }

        $request->validate([
            'type' => 'required|in:membership,order,booking',
            'reference_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $type = $request->type;
        $referenceId = $request->reference_id;
        $amount = (float) $request->amount;

        // Validate amount against server-side records (never trust frontend)
        $validatedAmount = $this->validateAmount($type, $referenceId, $user);

        if ($validatedAmount === null) {
            return response()->json(['error' => 'Invalid reference or unauthorized.'], 422);
        }

        // Use server-validated amount, not the one from frontend
        $amountInPaise = (int) round($validatedAmount * 100);

        try {
            $razorpayOrder = $this->razorpay->order->create([
                'receipt' => $type . '_' . $referenceId . '_' . time(),
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'notes' => [
                    'type' => $type,
                    'reference_id' => $referenceId,
                    'user_id' => $user->id,
                ],
            ]);

            // Store the pending payment record
            RazorpayPayment::create([
                'user_id' => $user->id,
                'type' => $type,
                'reference_id' => $referenceId,
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $validatedAmount,
                'currency' => 'INR',
                'status' => 'Pending',
            ]);

            // Also store the razorpay_order_id on the source record
            $this->storeRazorpayOrderId($type, $referenceId, $razorpayOrder->id);

            return response()->json([
                'order_id' => $razorpayOrder->id,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'key' => config('services.razorpay.key'),
                'name' => 'WinkelKart',
                'description' => $this->getDescription($type),
                'prefill' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact' => $user->mobile ?? '',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay create order failed: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create payment order. Please try again.'], 500);
        }
    }

    /**
     * Verify payment after Razorpay callback.
     *
     * POST /razorpay/verify-payment
     */
    public function verifyPayment(Request $request)
    {
        if (!$this->razorpay) {
            return response()->json(['error' => 'Payment gateway not configured.'], 503);
        }

        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'type' => 'required|in:membership,order,booking',
            'reference_id' => 'required|integer',
        ]);

        $user = Auth::user();

        // Verify signature
        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            config('services.razorpay.secret')
        );

        if (!hash_equals($expectedSignature, $request->razorpay_signature)) {
            Log::warning('Razorpay signature verification failed', [
                'user_id' => $user->id,
                'order_id' => $request->razorpay_order_id,
            ]);
            return response()->json(['error' => 'Payment verification failed. Invalid signature.'], 400);
        }

        try {
            DB::beginTransaction();

            // Update the razorpay_payments record
            $payment = RazorpayPayment::where('razorpay_order_id', $request->razorpay_order_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $payment->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'status' => 'Paid',
            ]);

            // Update the source record based on type
            $this->updateSourceRecord(
                $request->type,
                $request->reference_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature,
                $user
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $this->getSuccessMessage($request->type),
                'redirect' => $this->getRedirectUrl($request->type, $request->reference_id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Razorpay verify payment failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Payment verified but update failed. Contact support.'], 500);
        }
    }

    /**
     * Handle payment failure callback.
     *
     * POST /razorpay/payment-failed
     */
    public function paymentFailed(Request $request)
    {
        if (!$this->razorpay) {
            return response()->json(['error' => 'Payment gateway not configured.'], 503);
        }

        $request->validate([
            'razorpay_order_id' => 'required|string',
            'type' => 'required|in:membership,order,booking',
            'reference_id' => 'required|integer',
        ]);

        $user = Auth::user();

        RazorpayPayment::where('razorpay_order_id', $request->razorpay_order_id)
            ->where('user_id', $user->id)
            ->update(['status' => 'Failed']);

        return response()->json([
            'success' => false,
            'message' => 'Payment failed. Please try again.',
            'redirect' => route('payment.failed'),
        ]);
    }

    // ─── Private helpers ───────────────────────────────────────────────

    /**
     * Server-side amount validation – never trust the frontend amount.
     */
    private function validateAmount(string $type, int $referenceId, $user): ?float
    {
        switch ($type) {
            case 'membership':
                $kyc = SellerKycVerification::where('id', $referenceId)
                    ->where('user_id', $user->id)
                    ->first();
                return $kyc ? 999.00 : null; // Fixed membership fee

            case 'order':
                $order = Order::where('id', $referenceId)
                    ->where('user_id', $user->id)
                    ->first();
                return $order ? (float) ($order->grand_total ?? $order->total_amount) : null;

            case 'booking':
                $booking = Booking::where('id', $referenceId)
                    ->where('user_id', $user->id)
                    ->first();
                return $booking ? (float) $booking->total_price : null;

            default:
                return null;
        }
    }

    private function storeRazorpayOrderId(string $type, int $referenceId, string $razorpayOrderId): void
    {
        switch ($type) {
            case 'membership':
                SellerKycVerification::where('id', $referenceId)
                    ->update(['razorpay_order_id' => $razorpayOrderId]);
                break;
            case 'order':
                Order::where('id', $referenceId)
                    ->update(['razorpay_order_id' => $razorpayOrderId]);
                break;
            case 'booking':
                Booking::where('id', $referenceId)
                    ->update(['razorpay_order_id' => $razorpayOrderId]);
                break;
        }
    }

    private function updateSourceRecord(string $type, int $referenceId, string $paymentId, string $signature, $user): void
    {
        switch ($type) {
            case 'membership':
                SellerKycVerification::where('id', $referenceId)
                    ->where('user_id', $user->id)
                    ->update([
                        'razorpay_payment_id' => $paymentId,
                        'razorpay_signature' => $signature,
                        'payment_status' => 'Verified',
                        'membership_payment_verified' => true,
                        'membership_start' => Carbon::now(),
                        'membership_expiry' => Carbon::now()->addYear(),
                    ]);

                // Fire membership payment emails
                try {
                    $kyc = SellerKycVerification::where('id', $referenceId)->where('user_id', $user->id)->first();
                    if ($kyc) {
                        $seller = User::find($user->id);
                        if ($seller) {
                            Mail::to($seller->email)->send(new SellerMembershipPaidNotification($seller, $kyc));
                        }
                        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                        if ($adminEmail && $seller) {
                            Mail::to($adminEmail)->send(new AdminMembershipPaidNotification($seller, $kyc));
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send membership payment emails: ' . $e->getMessage());
                }
                break;

            case 'order':
                $order = Order::where('id', $referenceId)
                    ->where('user_id', $user->id)
                    ->first();

                if ($order) {
                    $order->update([
                        'razorpay_payment_id' => $paymentId,
                        'razorpay_signature' => $signature,
                        'payment_status' => 'Paid',
                        'payment_method' => 'Razorpay',
                        'order_status' => 'Paid',
                    ]);

                    // Process Shiprocket shipment after successful payment
                    $this->processShiprocketShipment($order);
                }
                break;

            case 'booking':
                $booking = Booking::where('id', $referenceId)
                    ->where('user_id', $user->id)
                    ->firstOrFail();

                $booking->update([
                    'razorpay_payment_id' => $paymentId,
                    'payment_status' => 'Paid',
                    'status' => 'confirmed',
                ]);

                // Also update the related payment record (old payments table)
                if ($booking->payment) {
                    $booking->payment->update([
                        'payment_status' => 'Paid',
                        'payment_method' => 'Razorpay',
                        'reference_id' => $paymentId,
                    ]);
                }
                break;
        }
    }

    private function getDescription(string $type): string
    {
        return match ($type) {
            'membership' => 'Seller Membership Fee (₹999/year)',
            'order' => 'Product Order Payment',
            'booking' => 'Hotel Booking Payment',
            default => 'WinkelKart Payment',
        };
    }

    private function getSuccessMessage(string $type): string
    {
        return match ($type) {
            'membership' => 'Membership payment successful! Your seller account is now active for 1 year.',
            'order' => 'Payment successful! Your order has been confirmed.',
            'booking' => 'Payment successful! Your hotel booking is confirmed.',
            default => 'Payment successful!',
        };
    }

    private function getRedirectUrl(string $type, int $referenceId): string
    {
        return match ($type) {
            'membership' => route('seller.kyc.edit'),
            'order' => route('order.success', [
                'orderNumber' => Order::find($referenceId)?->order_number ?? '',
            ]),
            'booking' => route('booking.confirmation', [
                'bookingCode' => Booking::find($referenceId)?->booking_code ?? '',
            ]),
            default => route('index'),
        };
    }

    /**
     * Process Shiprocket shipment for an order (same logic as ProductController)
     */
    private function processShiprocketShipment(Order $order)
    {
        try {
            $order->load(['items.product.seller', 'buyer.user_info']);

            $firstItem = $order->items->first();
            if (!$firstItem || !$firstItem->product || !$firstItem->product->seller) {
                return;
            }

            $seller = $firstItem->product->seller;
            $buyerInfo = $order->buyer->user_info;

            $deliveryPincode = $buyerInfo?->pincode ?? '110001';

            $shippingData = [
                'weight' => 0.5,
                'length' => 10,
                'breadth' => 10,
                'height' => 10,
                'hsn' => 0,
                'comment' => 'Order #' . $order->order_number,
            ];

            $shiprocket = new ShiprocketService();
            $result = $shiprocket->processShipment($order, $shippingData);

            if ($result['success']) {
                $order->update([
                    'shiprocket_order_id'   => $result['order_id'],
                    'shiprocket_shipment_id' => $result['shipment_id'],
                    'awb_code'              => $result['awb_code'],
                    'courier_name'          => 'Shiprocket',
                    'shipping_label_url'    => $result['label_url'],
                    'shipping_invoice_url'  => $result['invoice_url'],
                    'tracking_status'       => $result['awb_code'] ? 'Label Created' : 'Processing',
                    'pickup_requested'      => !empty($result['awb_code']),
                    'pickup_requested_at'   => !empty($result['awb_code']) ? now() : null,
                ]);

                \App\Models\OrderStatusTimeline::create([
                    'order_id' => $order->id,
                    'status' => 'Processing',
                    'note' => 'Shipment created with Shiprocket. AWB: ' . ($result['awb_code'] ?? 'N/A'),
                    'changed_by' => null,
                    'changed_by_role' => 'Shiprocket Integration',
                ]);

                Log::info('Shiprocket shipment processed after payment', [
                    'order_number' => $order->order_number,
                    'shipment_id' => $result['shipment_id'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket shipment exception: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
        }
    }
}
