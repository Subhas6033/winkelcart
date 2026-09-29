<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    //
    function __construct()
    {

        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        // default values
        $sellerCount = 0;
        $userCount = 0;
        $orderCount = 0;
        $totalSales = 0;
        $totalReceivedAmount = 0;

        if ($role === 'Admin') {
            // --- Admin Dashboard ---
            $users = User::where('status', 0)->where('deleted', 0)->get();
            $orders = Order::where('status', 0)->where('deleted', 0)->get();

            $orderCount = $orders->count();
            $totalSales = $orders->sum('total_amount');
            $totalReceivedAmount = $totalSales;

            foreach ($users as $user) {
                $role_name_chk = $user->getRoleNames()->first();
                if ($role_name_chk == 'Seller') {
                    $sellerCount++;
                } elseif ($role_name_chk == 'User') {
                    $userCount++;
                }
            }
        } 
        elseif ($role === 'Seller') {
            // --- Seller Dashboard ---
            $sellerId = $user->id;
            $hasHotelOwnerColumn = Schema::hasColumn('hotels', 'created_by');
            $hasProductOwnerColumn = Schema::hasColumn('products', 'created_by');
            
            // Get seller's business category
            $userInfo = $user->user_info;
            $businessCategoryId = $userInfo ? $userInfo->business_category_id : null;
            $isHotelSeller = ($businessCategoryId == 4); // 4 = Hotel and Resort

            if ($isHotelSeller) {
                // Hotel Seller Dashboard - show hotel and booking stats
                if ($hasHotelOwnerColumn) {
                    $hotelCount = \App\Models\Hotel::where('created_by', $sellerId)->count();
                    $bookings = \App\Models\Booking::whereHas('hotel', function($q) use ($sellerId) {
                        $q->where('created_by', $sellerId);
                    })->get();

                    $bookingCount = $bookings->count();
                    $totalRevenue = $bookings->sum('total_price');
                    $pendingBookings = $bookings->where('status', 'pending')->count();
                    $confirmedBookings = $bookings->where('status', 'confirmed')->count();
                } else {
                    $hotelCount = 0;
                    $bookingCount = 0;
                    $totalRevenue = 0;
                    $pendingBookings = 0;
                    $confirmedBookings = 0;
                }

                return view("home.seller_dashboard", compact(
                    'isHotelSeller', 'hotelCount', 'bookingCount', 
                    'totalRevenue', 'pendingBookings', 'confirmedBookings'
                ));
            } else {
                // Product Seller Dashboard - show product and order stats
                $isHotelSeller = false;

                if ($hasProductOwnerColumn) {
                    // Fetch only orders that have products by this seller
                    $orders = Order::whereHas('items.product', function($q) use ($sellerId) {
                            $q->where('created_by', $sellerId);
                        })
                        ->where('status', 0)
                        ->where('deleted', 0)
                        ->with(['items.product'])
                        ->get();

                    // Count unique orders for this seller
                    $orderCount = $orders->count();

                    // Count products by this seller
                    $productCount = \App\Models\Product::where('created_by', $sellerId)
                        ->where('deleted', 0)
                        ->count();

                    // Calculate total sales only for seller's own items
                    $totalSales = 0;
                    $totalReceivedAmount = 0;

                    foreach ($orders as $order) {
                        foreach ($order->items as $item) {
                            if ($item->product && $item->product->created_by == $sellerId) {
                                $totalSales += $item->price * $item->quantity;
                                $totalReceivedAmount += $item->admin_paid_amount ?? 0; // safe null check
                            }
                        }
                    }
                } else {
                    $orderCount = 0;
                    $productCount = 0;
                    $totalSales = 0;
                    $totalReceivedAmount = 0;
                }

                // Shiprocket shipment data
                $shiprocketOrders = \App\Models\Order::whereHas('items.product', function($q) use ($sellerId) {
                        $q->where('created_by', $sellerId);
                    })
                    ->whereNotNull('shiprocket_order_id')
                    ->where('deleted', 0)
                    ->orderByDesc('created_at')
                    ->limit(10)
                    ->get();

                $sellerKyc = $user->kycVerification;

                $subscriptionPlan = optional($userInfo)->subscription_plan ?? 'free';
                $isPro = $subscriptionPlan === 'paid';
                $productLimit = $isPro ? null : 50; // null = unlimited

                return view("home.seller_dashboard", compact(
                    'isHotelSeller', 'orderCount', 'productCount',
                    'totalSales', 'totalReceivedAmount',
                    'shiprocketOrders', 'sellerKyc',
                    'isPro', 'subscriptionPlan', 'productLimit'
                ));
            }
        }
        return view("home.index", compact('sellerCount', 'userCount', 'orderCount', 'totalSales', 'totalReceivedAmount'));
    }

    public function contact_us()
    {
        return view("home.contact_us");
    }

    public function groceries()
{
    return view('front.groceries'); 
}


    // public function login_by_share_link($share_id)
    // {
    //     if (!empty($share_id)) {
    //         $get_customer_id = Customer::where("share_id", $share_id)->get();
    //         if (!empty($get_customer_id[0]->id)) {

    //         }
    //     }
    // }
}
