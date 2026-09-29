<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Services\ShiprocketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShippingCalculationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Calculate shipping for the authenticated user's cart.
     *
     * POST /shipping/calculate-cart
     * Body JSON: { delivery_pincode: "110001", payment_method: "razorpay"|"cod" }
     *
     * @return JsonResponse
     */
    public function calculateCart(Request $request): JsonResponse
    {
        $request->validate([
            'delivery_pincode' => ['required', 'string', 'regex:/^\d{6}$/'],
            'payment_method'   => ['required', 'in:cod,razorpay'],
        ]);

        $user      = Auth::user();
        $cartItems = Cart::with('products.seller.kycVerification')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }

        try {
            $service = new ShiprocketService();
            $result  = $service->calculateCartShipping(
                $cartItems,
                $request->input('delivery_pincode'),
                $request->input('payment_method', 'razorpay')
            );

            return response()->json([
                'success'        => true,
                'total_shipping' => $result['total_shipping'],
                'cart_subtotal'  => $result['cart_subtotal'],
                'grand_total'    => $result['grand_total'],
                'free_shipping'  => $result['free_shipping'],
                'no_service'     => $result['no_service'],
                'sellers'        => $result['sellers'],
                'error'          => $result['error'],
                // Formatted strings for the UI
                'formatted' => [
                    'shipping'    => '₹' . number_format($result['total_shipping'], 2),
                    'subtotal'    => '₹' . number_format($result['cart_subtotal'], 2),
                    'grand_total' => '₹' . number_format($result['grand_total'], 2),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingCalculationController::calculateCart failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Shipping calculation failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Calculate shipping for a single product (Buy Now flow).
     *
     * POST /shipping/calculate-product
     * Body JSON: { product_id: 5, quantity: 1, delivery_pincode: "110001", payment_method: "razorpay" }
     *
     * @return JsonResponse
     */
    public function calculateProduct(Request $request): JsonResponse
    {
        $request->validate([
            'product_id'       => ['required', 'integer', 'exists:products,id'],
            'quantity'         => ['required', 'integer', 'min:1'],
            'delivery_pincode' => ['required', 'string', 'regex:/^\d{6}$/'],
            'payment_method'   => ['required', 'in:cod,razorpay'],
        ]);

        $product = Product::with('seller.kycVerification')
            ->where('deleted', 0)
            ->findOrFail((int) $request->input('product_id'));

        try {
            $service = new ShiprocketService();
            $result  = $service->calculateSingleProductShipping(
                $product,
                (int) $request->input('quantity', 1),
                $request->input('delivery_pincode'),
                $request->input('payment_method', 'razorpay')
            );

            return response()->json([
                'success'        => true,
                'total_shipping' => $result['total_shipping'],
                'cart_subtotal'  => $result['cart_subtotal'],
                'grand_total'    => $result['grand_total'],
                'free_shipping'  => $result['free_shipping'],
                'no_service'     => $result['no_service'],
                'sellers'        => $result['sellers'],
                'error'          => $result['error'],
                'formatted' => [
                    'shipping'    => '₹' . number_format($result['total_shipping'], 2),
                    'subtotal'    => '₹' . number_format($result['cart_subtotal'], 2),
                    'grand_total' => '₹' . number_format($result['grand_total'], 2),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingCalculationController::calculateProduct failed: ' . $e->getMessage(), [
                'product_id' => $request->product_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Shipping calculation failed. Please try again.',
            ], 500);
        }
    }
}
