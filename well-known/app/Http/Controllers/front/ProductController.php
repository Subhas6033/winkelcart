<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedNotification;
use App\Mail\SellerOrderNotification;
use App\Mail\AdminNewOrderNotification;
use App\Services\ShiprocketService;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusTimeline;
use App\Models\AdminNotification;

class ProductController extends Controller
{
    // ...existing code...

    // Show Order Summary for Buy Now
    public function buyNowOrderSummary($id)
    {
        $product = Product::with('seller.kycVerification')->findOrFail($id);

        if (!$this->isProductSellableByVerifiedSeller($product)) {
            return redirect()->route('index')->with('error', 'This product is currently unavailable for purchase.');
        }

        $user = Auth::user()->load('user_info'); // Eager load user_info
        
        // Check if user has complete address
        $userInfo = $user->user_info;
        $hasCompleteAddress = $userInfo && $userInfo->pincode && $userInfo->city && $userInfo->state && $userInfo->address_line_1;
        
        return view('front.order-summary', compact('product', 'user', 'hasCompleteAddress'));
    }
    // Product search backend for navbar
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $queryLower = strtolower($query);
        
        // Detect category keywords and redirect to appropriate category page
        $categoryRedirects = [
            'computer' => 'computers',
            'desktop' => 'computers',
            'laptop' => 'computers',
            'monitor' => 'computers',
            'mouse' => 'computers',
            'keyboard' => 'computers',
            'pc' => 'computers',
            'electronic' => 'electronics',
            'mobile' => 'electronics',
            'phone' => 'electronics',
            'headphone' => 'electronics',
            'speaker' => 'electronics',
            'tv' => 'electronics',
            'television' => 'electronics',
            'grocery' => 'groceries',
            'groceries' => 'groceries',
            'food' => 'groceries',
            'rice' => 'groceries',
            'oil' => 'groceries',
            'spice' => 'groceries',
            'snack' => 'groceries',
            'cosmetic' => 'cosmetics',
            'makeup' => 'cosmetics',
            'beauty' => 'cosmetics',
            'skincare' => 'cosmetics',
            'fashion' => 'cosmetics',
            'hotel' => 'hotels-and-resorts',
            'resort' => 'hotels-and-resorts',
            'stay' => 'hotels-and-resorts',
            'room' => 'hotels-and-resorts',
            'booking' => 'hotels-and-resorts',
        ];

        // Check if query matches any category keyword
        foreach ($categoryRedirects as $keyword => $route) {
            if (strpos($queryLower, $keyword) !== false) {
                return redirect()->to(url($route) . '?q=' . urlencode($query));
            }
        }

        // If no category match, show general search results
        $keywords = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);
        
        // Search Products
        $productsQuery = Product::query()
            ->where('status', 0)
            ->where('deleted', 0)
            ->fromVerifiedSellers();
        $productsQuery->where(function($q) use ($keywords, $query) {
            foreach ($keywords as $word) {
                $q->orWhere('name', 'like', "%{$word}%")
                  ->orWhere('detail', 'like', "%{$word}%")
                  ->orWhere('company', 'like', "%{$word}%");
            }
            // Also try exact phrase match
            $q->orWhere('name', 'like', "%{$query}%")
              ->orWhere('detail', 'like', "%{$query}%");
        });

        // Category filter
        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->input('category_id'));
        }
        // Price range filter
        if ($request->filled('min_price')) {
            $productsQuery->where(function($q) use ($request) {
                $q->where('final_price', '>=', $request->input('min_price'))
                  ->orWhere('total_price', '>=', $request->input('min_price'));
            });
        }
        if ($request->filled('max_price')) {
            $productsQuery->where(function($q) use ($request) {
                $q->where('final_price', '<=', $request->input('max_price'))
                  ->orWhere('total_price', '<=', $request->input('max_price'));
            });
        }
        // Brand/Company filter
        if ($request->filled('company')) {
            $productsQuery->where('company', 'like', "%{$request->input('company')}%");
        }
        // Discounted products only
        if ($request->boolean('discounted')) {
            $productsQuery->whereColumn('final_price', '<', 'total_price');
        }
        // In-stock filter (assuming status 0 = in stock)
        if ($request->filled('in_stock')) {
            $productsQuery->where('status', 0);
        }

        $products = $productsQuery->with('category')->paginate(12, ['*'], 'products_page')->withQueryString();

        // Search Hotels
        $hotelsQuery = \App\Models\Hotel::query()->fromVerifiedSellers();
        $hotelsQuery->where(function($q) use ($keywords, $query) {
            foreach ($keywords as $word) {
                $q->orWhere('name', 'like', "%{$word}%")
                  ->orWhere('location', 'like', "%{$word}%")
                  ->orWhere('desc', 'like', "%{$word}%")
                  ->orWhereJsonContains('amenities', $word);
            }
            // Exact phrase match
            $q->orWhere('name', 'like', "%{$query}%")
              ->orWhere('location', 'like', "%{$query}%");
        });

        // Hotel-specific filters
        if ($request->filled('hotel_rating')) {
            $hotelsQuery->where('rating', '>=', $request->input('hotel_rating'));
        }
        if ($request->filled('property_type')) {
            $hotelsQuery->where('property_type', $request->input('property_type'));
        }

        $hotels = $hotelsQuery->paginate(12, ['*'], 'hotels_page')->withQueryString();

        // Get categories for filter dropdown
        $categories = \App\Models\Category::where('status', '0')->get();

        // Determine active tab
        $activeTab = $request->input('tab', 'all');
        
        // Count totals
        $totalProducts = $products->total();
        $totalHotels = $hotels->total();
        $totalResults = $totalProducts + $totalHotels;

        return view('front.search-results', compact(
            'products', 'hotels', 'query', 'categories', 
            'activeTab', 'totalProducts', 'totalHotels', 'totalResults'
        ));
    }
    public function show($id)
    {
        $product = Product::with(['seller.kycVerification', 'reviews.user'])
            ->where('status', 0)
            ->where('deleted', 0)
            ->findOrFail($id);

        abort_unless($this->isProductSellableByVerifiedSeller($product), 404);

        return view('front.product-details', compact('product'));
    }
    public function cart_add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error_login', 'Please login to add items to cart.');
        }

        $product = Product::with('seller.kycVerification')->findOrFail($id);

        if (!$this->isProductSellableByVerifiedSeller($product)) {
            return redirect()->back()->with('error', 'This product cannot be purchased right now because seller KYC is not fully verified.');
        }

        $user_id = Auth::id();

        // check if already in cart
        $existing = Cart::where('user_id', $user_id)
                        ->where('product_id', $id)
                        ->first();

        if ($existing) {
            $existing->quantity += 1;
            $existing->price += $existing->price;
            $existing->save();
        } else {
            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->final_price ?? $product->total_price,
            ]);
        }
        
        return redirect()->route('cart_list')->with('success', 'Product added to cart successfully!');
    }

    // 🧾 Show Cart
    public function cart_list()
    {
        $user = Auth::user()->load('user_info'); // Eager load user_info
        $cartItems = Cart::with('products')->where('user_id', $user->id)->get();        
        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        $orders = Order::with(['items.product.seller.user_info'])
        ->where('user_id', $user->id)
        ->orderBy('id', 'DESC')
        ->get();

        // Check if user has complete address
        $userInfo = $user->user_info;
        $hasCompleteAddress = $userInfo && $userInfo->pincode && $userInfo->city && $userInfo->state && $userInfo->address_line_1;

        return view('front.cart', compact('cartItems', 'total', 'orders', 'hasCompleteAddress', 'user'));
    }

    // 🔄 Update Quantity
    public function cart_update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        return redirect()->route('cart_list')->with('success', 'Product updated to cart successfully!');
    }

    // ❌ Remove Item
    public function cart_remove($id)
    {
        Cart::findOrFail($id)->delete();        
        return redirect()->route('cart_list')->with('success', 'Item removed from cart!');
    }

    public function place_order(Request $request, $productId = null)
    {
        try {
            $user = Auth::user();
            
            // Check if user has complete address
            $userInfo = $user->user_info;
            if (!$userInfo || !$userInfo->pincode || !$userInfo->city || !$userInfo->state || !$userInfo->address_line_1) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please add your complete delivery address before placing an order.',
                        'redirect' => route('profile.addresses')
                    ]);
                }
                return redirect()->route('profile.addresses')
                    ->with('error', 'Please add your complete delivery address (including pincode, city, and state) before placing an order.');
            }
            
            $validationRules = [
                'shipping_address' => 'required|string|max:500',
                'payment_method'   => 'required|in:cod,razorpay',
            ];
            
            $request->validate($validationRules);

            // Update user address if changed
            if ($request->shipping_address) {
                $userInfo->address = $request->shipping_address;
                $userInfo->save();
            }

            // ── Shipping cost helper ──────────────────────────────────────────
            $shiprocket      = new ShiprocketService();
            $deliveryPincode = preg_replace('/\D/', '', (string) ($userInfo->pincode ?? ''));
            $paymentMethod   = $request->input('payment_method', 'razorpay');

            // For Buy Now, $productId is set, else use cart
            if ($productId) {
                $product = Product::with('seller.kycVerification')->findOrFail($productId);

                if (!$this->isProductSellableByVerifiedSeller($product)) {
                    $errorMsg = 'This product cannot be purchased right now because seller KYC is not fully verified.';
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => $errorMsg], 403);
                    }
                    return back()->with('error', $errorMsg);
                }

                $cartSubtotal = (float) ($product->final_price ?? $product->offer_price ?? $product->total_price ?? 0);

                // Server-side shipping calculation
                $shippingResult = $shiprocket->calculateSingleProductShipping(
                    $product, 1, $deliveryPincode, $paymentMethod
                );

                if ($shippingResult['no_service']) {
                    $msg = 'No courier service available for your delivery address. Please try a different address or contact support.';
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    return back()->with('error', $msg);
                }

                $shippingCost = $shippingResult['total_shipping'];
                $grandTotal   = $cartSubtotal + $shippingCost;

                $order = Order::create([
                    'user_id'           => $user->id,
                    'order_number'      => Helper::create_order_number(10),
                    'total_amount'      => $grandTotal,   // grand total is what Razorpay charges
                    'cart_total'        => $cartSubtotal,
                    'shipping_cost'     => $shippingCost,
                    'grand_total'       => $grandTotal,
                    'shipping_breakdown'=> $shippingResult['sellers'],
                    'order_status'      => 'Pending',
                    'status'            => 0,
                    'deleted'           => 0,
                    'payment_method'    => $paymentMethod,
                    'payment_status'    => 'Pending',
                    'shipping_address'  => $request->shipping_address,
                ]);

                OrderStatusTimeline::create([
                    'order_id'        => $order->id,
                    'status'          => 'Placed',
                    'note'            => 'Order placed by customer.',
                    'changed_by'      => $user->id,
                    'changed_by_role' => 'Customer',
                ]);
                
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => 1,
                    'price'      => $cartSubtotal,
                    'status'     => 0,
                ]);
                
                // Send order confirmation email to buyer
                try {
                    $order->load('items.product.seller');
                    Mail::to($user->email)->send(new OrderPlacedNotification($user, $order));
                } catch (\Exception $e) {
                    \Log::error('Order confirmation email failed: ' . $e->getMessage());
                }
                
                $this->notifySellers($order, $user);

                try {
                    $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                    if ($adminEmail) {
                        Mail::to($adminEmail)->send(new AdminNewOrderNotification($order));
                    }
                } catch (\Exception $e) {
                    \Log::error('Admin new order email failed: ' . $e->getMessage());
                }
                
                AdminNotification::notify(
                    AdminNotification::TYPE_ORDER_PLACED,
                    'New Order Placed',
                    'Order #' . $order->order_number . ' placed by ' . $user->name . ' for ₹' . number_format($grandTotal, 2),
                    ['link' => route('orders.index'), 'related_id' => $order->id, 'related_type' => Order::class]
                );

                if ($paymentMethod === 'cod') {
                    $this->processShiprocketShipment($order);
                }

                if ($paymentMethod === 'razorpay') {
                    return response()->json([
                        'success'          => true,
                        'payment_required' => true,
                        'order_id'         => $order->id,
                        'amount'           => $grandTotal,
                        'order_number'     => $order->order_number,
                    ]);
                }

                if ($request->ajax()) {
                    return response()->json([
                        'success'  => true,
                        'message'  => 'Order placed successfully! Redirecting to order confirmation...',
                        'redirect' => route('order.success', ['orderNumber' => $order->order_number])
                    ]);
                }
                return redirect()->route('order.success', ['orderNumber' => $order->order_number])
                    ->with('success', 'Order placed successfully!');

            } else {
                // ── Cart checkout ─────────────────────────────────────────────
                $cartItems = Cart::with('products.seller.kycVerification')
                    ->where('user_id', $user->id)
                    ->get();
                
                if ($cartItems->isEmpty()) {
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Your cart is empty!']);
                    }
                    return back()->with('error', 'Your cart is empty!');
                }

                $hasUnverifiedSellerProducts = $cartItems->contains(function ($item) {
                    return !$item->products || !$this->isProductSellableByVerifiedSeller($item->products);
                });

                if ($hasUnverifiedSellerProducts) {
                    $errorMsg = 'One or more cart items cannot be purchased because seller KYC is not fully verified.';
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => $errorMsg], 403);
                    }
                    return back()->with('error', $errorMsg);
                }
                
                // Server-side shipping calculation
                $shippingResult = $shiprocket->calculateCartShipping(
                    $cartItems, $deliveryPincode, $paymentMethod
                );

                if ($shippingResult['no_service']) {
                    $msg = 'No courier service available for one or more seller locations. Please contact support.';
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    return back()->with('error', $msg);
                }

                $cartSubtotal = $shippingResult['cart_subtotal'];
                $shippingCost = $shippingResult['total_shipping'];
                $grandTotal   = $shippingResult['grand_total'];
                
                $order = Order::create([
                    'user_id'           => $user->id,
                    'order_number'      => Helper::create_order_number(10),
                    'total_amount'      => $grandTotal,
                    'cart_total'        => $cartSubtotal,
                    'shipping_cost'     => $shippingCost,
                    'grand_total'       => $grandTotal,
                    'shipping_breakdown'=> $shippingResult['sellers'],
                    'order_status'      => 'Pending',
                    'status'            => 0,
                    'deleted'           => 0,
                    'payment_method'    => $paymentMethod,
                    'payment_status'    => 'Pending',
                    'shipping_address'  => $request->shipping_address ?? ($user->user_info->address ?? ''),
                ]);

                OrderStatusTimeline::create([
                    'order_id'        => $order->id,
                    'status'          => 'Placed',
                    'note'            => 'Order placed by customer.',
                    'changed_by'      => $user->id,
                    'changed_by_role' => 'Customer',
                ]);
                
                foreach ($cartItems as $cartItem) {
                    $itemPrice = (float) ($cartItem->products->final_price ?? $cartItem->products->offer_price ?? $cartItem->price);
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity'   => $cartItem->quantity,
                        'price'      => $itemPrice,
                        'status'     => 0,
                    ]);
                }
                
                Cart::where('user_id', $user->id)->delete();
                
                try {
                    $order->load('items.product.seller');
                    Mail::to($user->email)->send(new OrderPlacedNotification($user, $order));
                } catch (\Exception $e) {
                    \Log::error('Order confirmation email failed: ' . $e->getMessage());
                }
                
                $this->notifySellers($order, $user);

                try {
                    $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                    if ($adminEmail) {
                        Mail::to($adminEmail)->send(new AdminNewOrderNotification($order));
                    }
                } catch (\Exception $e) {
                    \Log::error('Admin new order email failed: ' . $e->getMessage());
                }
                
                AdminNotification::notify(
                    AdminNotification::TYPE_ORDER_PLACED,
                    'New Order Placed',
                    'Order #' . $order->order_number . ' placed by ' . $user->name . ' for ₹' . number_format($grandTotal, 2),
                    ['link' => route('orders.index'), 'related_id' => $order->id, 'related_type' => Order::class]
                );

                if ($paymentMethod === 'cod') {
                    $this->processShiprocketShipment($order);
                }

                if ($paymentMethod === 'razorpay') {
                    return response()->json([
                        'success'          => true,
                        'payment_required' => true,
                        'order_id'         => $order->id,
                        'amount'           => $grandTotal,
                        'order_number'     => $order->order_number,
                    ]);
                }

                if ($request->ajax()) {
                    return response()->json([
                        'success'  => true,
                        'message'  => 'Order placed successfully! Redirecting to order confirmation...',
                        'redirect' => route('order.success', ['orderNumber' => $order->order_number])
                    ]);
                }
                return redirect()->route('order.success', ['orderNumber' => $order->order_number])
                    ->with('success', 'Order placed successfully!');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Order could not be placed. Please try again.'
                ]);
            }
            return back()->with('error', $e->getMessage() ?: 'Order could not be placed. Please try again.');
        }
    }

    // Show My Orders page for logged-in users
    public function my_orders()
    {
        $orders = Order::with(['items.product.seller.user_info'])
            ->where('user_id', Auth::id())
            ->where('deleted', 0)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('front.my-orders', compact('orders'));
    }

    // Show single order details
    public function order_detail($orderNumber)
    {
        $order = Order::with(['items.product.seller.user_info', 'buyer.user_info'])
            ->where('user_id', Auth::id())
            ->where('order_number', $orderNumber)
            ->where('deleted', 0)
            ->firstOrFail();

        return view('front.order-detail', compact('order'));
    }

    // Cancel order (only if pending)
    public function cancel_order($orderNumber)
    {
        $order = Order::with('buyer')
            ->where('user_id', Auth::id())
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check if order has reached Processing or later in timeline
        $processingOrLater = OrderStatusTimeline::where('order_id', $order->id)
            ->whereIn('status', ['Processing', 'Shipped', 'Delivered'])
            ->exists();

        if ($order->order_status !== 'Pending' || $processingOrLater) {
            return redirect()->back()->with('error', 'Order cannot be cancelled after processing has started.');
        }

        $order->order_status = 'Cancelled';
        $order->save();

        OrderStatusTimeline::create([
            'order_id' => $order->id,
            'status' => 'Cancelled',
            'note' => 'Order cancelled by customer.',
            'changed_by' => Auth::id(),
            'changed_by_role' => 'Customer',
        ]);

        // Send cancellation email to customer
        try {
            $buyer = $order->buyer;
            if ($buyer && $buyer->email) {
                \Mail::to($buyer->email)->send(
                    new \App\Mail\OrderStatusUpdateNotification($buyer, $order, 'Cancelled', 'Order cancelled by customer.')
                );
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send order cancellation email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }

    // Download Invoice PDF
    public function download_invoice($orderNumber)
    {
        $order = Order::with(['items.product.seller', 'buyer.user_info'])
            ->where('user_id', Auth::id())
            ->where('order_number', $orderNumber)
            ->where('deleted', 0)
            ->firstOrFail();

        $invoiceService = new \App\Services\InvoiceService();
        $pdf = $invoiceService->generateInvoicePdf($order);
        
        return $pdf->download($invoiceService->getInvoiceFilename($order));
    }

    // Track Order Page
    public function track_order()
    {
        return view('front.track-order');
    }

    // Track Order Search
    public function track_order_search(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        $orderNumber = trim($request->order_number);
        
        // Find order by order number
        $order = Order::with(['items.product.seller.user_info', 'buyer.user_info'])
            ->with(['timelines'])
            ->where('order_number', $orderNumber)
            ->where('deleted', 0)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found. Please check the order number and try again.');
        }

        return view('front.track-order-result', compact('order'));
    }

    // Direct Track Order by Order Number (GET)
    public function track_order_direct($orderNumber)
    {
        $order = Order::with(['items.product.seller.user_info', 'buyer.user_info'])
            ->with(['timelines'])
            ->where('order_number', $orderNumber)
            ->where('deleted', 0)
            ->first();

        if (!$order) {
            return redirect()->route('track_order')->with('error', 'Order not found. Please check the order number and try again.');
        }

        return view('front.track-order-result', compact('order'));
    }

    private function isProductSellableByVerifiedSeller(Product $product): bool
    {
        // Allow admin-created products (user_id = 1) without KYC verification
        if ($product->created_by == 1) {
            return true;
        }

        $product->loadMissing('seller.kycVerification');

        $seller = $product->seller;

        return $seller && $seller->isFullyKycVerified();
    }

    /**
     * Send notification emails to all sellers whose products are in the order
     *
     * @param Order $order
     * @param \App\Models\User $buyer
     */
    private function notifySellers(Order $order, $buyer)
    {
        try {
            // Load order items with product and seller info
            $order->load('items.product.seller');

            // Group items by seller
            $sellerItems = collect();
            foreach ($order->items as $item) {
                if ($item->product && $item->product->seller) {
                    $sellerId = $item->product->created_by;
                    if (!$sellerItems->has($sellerId)) {
                        $sellerItems[$sellerId] = collect();
                    }
                    $sellerItems[$sellerId]->push($item);
                }
            }

            // Send email to each seller
            foreach ($sellerItems as $sellerId => $items) {
                $seller = $items->first()->product->seller;
                if ($seller && $seller->email) {
                    try {
                        Mail::to($seller->email)->send(new SellerOrderNotification($seller, $buyer, $order, $items));
                        \Log::info('Seller order notification sent to: ' . $seller->email . ' for order: ' . $order->order_number);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send seller notification to ' . $seller->email . ': ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error in notifySellers: ' . $e->getMessage());
        }
    }

    /**
     * Process Shiprocket shipment for an order
     * Creates shipment, assigns AWB, requests pickup, and stores tracking info
     *
     * @param Order $order
     * @return void
     */
    private function processShiprocketShipment(Order $order)
    {
        try {
            // Load order relationships
            $order->load(['items.product.seller', 'buyer.user_info']);

            // Get seller's pincode from first product's seller
            $firstItem = $order->items->first();
            if (!$firstItem || !$firstItem->product || !$firstItem->product->seller) {
                \Log::warning('Shiprocket: No seller found for order', ['order_id' => $order->id]);
                return;
            }

            $seller = $firstItem->product->seller;
            $sellerKyc = $seller->kycVerification;
            $buyerInfo = $order->buyer->user_info;

            // Get pickup pincode from seller's KYC or default
            $pickupPincode = '110001'; // Default to Delhi if not available
            $deliveryPincode = $buyerInfo?->pincode ?? '110001';

            // Prepare shipping data — pull dimensions from the first product in the order
            $firstProduct = $firstItem->product;
            $weight = $order->items->sum(function ($item) {
                return (float) ($item->product->weight ?? 0.5) * $item->quantity;
            });
            $weight = max(round($weight, 3), 0.5);

            $shippingData = [
                'weight'  => $weight,
                'length'  => (float) ($firstProduct->length  ?? 10),
                'breadth' => (float) ($firstProduct->breadth ?? 10),
                'height'  => (float) ($firstProduct->height  ?? 10),
                'hsn'     => 0,
                'comment' => 'Order #' . $order->order_number,
            ];

            // Initialize Shiprocket service
            $shiprocket = new ShiprocketService();

            // Process shipment
            $result = $shiprocket->processShipment($order, $shippingData);

            if ($result['success']) {
                // Update order with Shiprocket details
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

                // Add timeline entry
                OrderStatusTimeline::create([
                    'order_id' => $order->id,
                    'status' => 'Processing',
                    'note' => 'Shipment created with Shiprocket. AWB: ' . ($result['awb_code'] ?? 'N/A'),
                    'changed_by' => null,
                    'changed_by_role' => 'Shiprocket Integration',
                ]);

                \Log::info('Shiprocket shipment processed successfully', [
                    'order_number' => $order->order_number,
                    'shipment_id' => $result['shipment_id'],
                    'awb_code' => $result['awb_code'],
                ]);
            } else {
                \Log::error('Shiprocket shipment failed', [
                    'order_number' => $order->order_number,
                    'errors' => $result['errors'],
                ]);

                // Add admin notification for failed shipment
                AdminNotification::notify(
                    AdminNotification::TYPE_ORDER_PLACED,
                    'Shiprocket Shipment Failed',
                    'Order #' . $order->order_number . ' - Failed to create shipment: ' . implode(', ', $result['errors']),
                    [
                        'link' => route('orders.index'),
                        'related_id' => $order->id,
                        'related_type' => Order::class,
                    ]
                );
            }
        } catch (\Exception $e) {
            \Log::error('Shiprocket shipment exception: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
