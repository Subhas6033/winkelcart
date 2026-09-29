<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Helper;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('front.product-details', compact('product'));
    }
    public function cart_add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error_login', 'Please login to add items to cart.');
        }

        $product = Product::findOrFail($id);
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
                'price' => $product->offer_price ?? $product->total_price,
            ]);
        }
        
        return redirect()->route('cart_list')->with('success', 'Product added to cart successfully!');
    }

    // 🧾 Show Cart
    public function cart_list()
    {
        $cartItems = Cart::with('products')->where('user_id', Auth::id())->get();        
        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        $orders = Order::with(['items.product'])
        ->where('user_id', Auth::id())
        ->orderBy('id', 'DESC')
        ->get();

        // print_r($orders->toArray());die;
        return view('front.cart', compact('cartItems', 'total', 'orders'));
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

    public function place_order(Request $request)
    {
        // $request->validate([
        //     'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        $request->validate([
            'payment_screenshot' => 'required|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ], [
            'payment_screenshot.required' => 'Please upload a payment screenshot.',
            'payment_screenshot.mimes' => 'Only image files (JPG, PNG, GIF) are allowed.',
            'payment_screenshot.max' => 'Image size must be less than 2 MB.',
        ]);
        // print_r($_REQUEST);die;
        // Upload screenshot
        $fileName = time() . '.' . $request->payment_screenshot->extension();
        $request->payment_screenshot->move(public_path('uploads/payments'), $fileName);

        // Fetch cart items
        $cartItems = Cart::with('products')->where('user_id', Auth::id())->get();
        // print_r($cartItems->toarray());die;

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart_list', Auth::id())->with('error', 'Your cart is empty.');
        }

        // Create order record
        $totalAmount = $cartItems->sum(function ($item) {
            $price = $item->products->offer_price ?? $item->products->total_price;
            return $price * $item->quantity;
        });

        $order = Order::create([
            'user_id'       => Auth::id(),
            'order_number'  => Helper::create_order_number(10),
            'total_amount'  => $totalAmount,
            'payment_image' => $fileName,
            'order_status'  => 'Pending',
        ]);

        // Save order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->products->offer_price ?? $item->products->total_price,
            ]);
        }

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('cart_list', Auth::id())
            ->with('success', 'Order placed successfully! Awaiting admin confirmation.');
    }

}
