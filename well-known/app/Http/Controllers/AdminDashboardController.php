<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\ContactSubmission;
use App\Models\SupportTicket;
use App\Models\ReturnRefundRequest;
use App\Models\SellerKycVerification;
use App\Models\SellerSettlement;
use App\Models\OrderStatusTimeline;
use App\Models\AuditLog;
use App\Models\CmsPage;
use App\Models\RazorpayPayment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->getRoleNames()->first();

        // If Seller, show seller-specific dashboard
        if ($role === 'Seller') {
            return $this->sellerDashboard($user);
        }

        // Admin Dashboard
        // Hotel & Booking Stats
        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('payment_status', 'Paid')->sum(DB::raw('COALESCE((SELECT total_price FROM bookings WHERE bookings.id = payments.booking_id),0)'));
        $pendingBookings = Booking::where('status', 'Pending')->count();
        $confirmedBookings = Booking::where('status', 'Confirmed')->count();
        $cancelledBookings = Booking::where('status', 'Cancelled')->count();
        $totalHotels = Hotel::count();
        $totalRooms = Room::count();
        $totalUsers = User::where('deleted', 0)->count();

        // E-commerce Stats
        $totalOrders = Order::where('deleted', 0)->count();
        $totalProducts = Product::where('deleted', 0)->count();
        $totalCategories = Category::where('deleted', 0)->count();
        $totalSales = Order::where('deleted', 0)->sum('total_amount');
        
        // Count sellers and buyers
        $users = User::where('deleted', 0)->get();
        $totalSellers = 0;
        $totalBuyers = 0;
        foreach ($users as $user) {
            $roleName = $user->getRoleNames()->first();
            if ($roleName == 'Seller') {
                $totalSellers++;
            } elseif ($roleName == 'User') {
                $totalBuyers++;
            }
        }

        // Order Status Stats
        $pendingOrders = Order::where('deleted', 0)->where('order_status', 'pending')->count();
        $completedOrders = Order::where('deleted', 0)->where('order_status', 'delivered')->count();
        $cancelledOrders = Order::where('deleted', 0)->where('order_status', 'cancelled')->count();

        // Extended Admin Module Stats
        $totalContactQueries = 0;
        $pendingContactQueries = 0;
        $solvedContactQueries = 0;
        $recentContactQueries = collect();

        if (Schema::hasTable('contact_submissions')) {
            $totalContactQueries = ContactSubmission::count();
            $pendingContactQueries = ContactSubmission::where(function ($q) {
                $q->where('status', 'Pending')->orWhereNull('status');
            })->count();
            $solvedContactQueries = ContactSubmission::where('status', 'Solved')->count();
            $recentContactQueries = ContactSubmission::orderByDesc('id')
                ->limit(5)
                ->get(['id', 'business_name', 'email', 'status', 'created_at']);
        }

        $totalSupportTickets = 0;
        $openSupportTickets = 0;
        $resolvedSupportTickets = 0;
        $recentSupportTickets = collect();

        if (Schema::hasTable('support_tickets')) {
            $totalSupportTickets = SupportTicket::count();
            $openSupportTickets = SupportTicket::whereIn('status', ['Open', 'In Progress'])->count();
            $resolvedSupportTickets = SupportTicket::where('status', 'Resolved')->count();
            $recentSupportTickets = SupportTicket::orderByDesc('id')
                ->limit(5)
                ->get(['id', 'subject', 'status', 'order_number', 'created_at']);
        }

        $totalReturnRefundRequests = 0;
        $pendingReturnRefundRequests = 0;
        $completedReturnRefundRequests = 0;
        $recentReturnRefundRequests = collect();

        if (Schema::hasTable('return_refund_requests')) {
            $totalReturnRefundRequests = ReturnRefundRequest::count();
            $pendingReturnRefundRequests = ReturnRefundRequest::whereIn('status', ['Pending', 'Under Review'])->count();
            $completedReturnRefundRequests = ReturnRefundRequest::whereIn('status', ['Approved', 'Completed'])->count();
            $recentReturnRefundRequests = ReturnRefundRequest::with('user:id,name')
                ->orderByDesc('id')
                ->limit(5)
                ->get(['id', 'user_id', 'order_number', 'request_type', 'status', 'created_at']);
        }

        $totalSellerKycProfiles = 0;
        $pendingSellerKyc = 0;
        $verifiedSellerKyc = 0;
        $notSubmittedSellerKyc = 0;

        if (Schema::hasTable('seller_kyc_verifications')) {
            $totalSellerKycProfiles = SellerKycVerification::count();
            $pendingSellerKyc = SellerKycVerification::where('status', 'Pending')->count();
            $verifiedSellerKyc = SellerKycVerification::where('status', 'Verified')->count();

            $submittedSellerKyc = SellerKycVerification::query()
                ->whereNotNull('user_id')
                ->distinct()
                ->count('user_id');
            $notSubmittedSellerKyc = max($totalSellers - $submittedSellerKyc, 0);
        }

        $totalSellerSettlements = 0;
        $pendingSellerSettlements = 0;
        $processingSellerSettlements = 0;
        $paidSellerSettlements = 0;

        if (Schema::hasTable('seller_settlements')) {
            $totalSellerSettlements = SellerSettlement::count();
            $pendingSellerSettlements = SellerSettlement::where('status', 'Pending')->count();
            $processingSellerSettlements = SellerSettlement::where('status', 'Processing')->count();
            $paidSellerSettlements = SellerSettlement::where('status', 'Paid')->count();
        }

        $totalOrderTimelineEvents = 0;
        $todayOrderTimelineEvents = 0;

        if (Schema::hasTable('order_status_timelines')) {
            $totalOrderTimelineEvents = OrderStatusTimeline::count();
            $todayOrderTimelineEvents = OrderStatusTimeline::whereDate('created_at', now()->toDateString())->count();
        }

        $totalAuditLogEntries = 0;
        $todayAuditLogEntries = 0;
        $recentAuditLogs = collect();

        if (Schema::hasTable('audit_logs')) {
            $totalAuditLogEntries = AuditLog::count();
            $todayAuditLogEntries = AuditLog::whereDate('created_at', now()->toDateString())->count();
            $recentAuditLogs = AuditLog::with('user:id,name')
                ->orderByDesc('id')
                ->limit(6)
                ->get(['id', 'user_id', 'action', 'entity_type', 'created_at']);
        }

        $totalCmsPages = 0;
        $activeCmsPages = 0;
        $activePolicyPages = 0;

        if (Schema::hasTable('cms_pages')) {
            $policySlugs = [
                'terms-and-conditions',
                'privacy-policy',
                'return-refund-policy',
                'shipping-delivery-policy',
                'cancellation-policy',
            ];

            $totalCmsPages = CmsPage::count();
            $activeCmsPages = CmsPage::where('is_active', 1)->count();
            $activePolicyPages = CmsPage::where('is_active', 1)
                ->whereIn('slug', $policySlugs)
                ->count();
        }

        // Razorpay Payment Stats
        $totalRazorpayPayments = 0;
        $razorpayPaidAmount = 0;
        $razorpayPendingPayments = 0;
        $razorpayPaidPayments = 0;
        $recentRazorpayPayments = collect();

        if (Schema::hasTable('razorpay_payments')) {
            $totalRazorpayPayments = RazorpayPayment::count();
            $razorpayPaidAmount = RazorpayPayment::where('status', 'paid')->sum('amount');
            $razorpayPendingPayments = RazorpayPayment::where('status', 'created')->count();
            $razorpayPaidPayments = RazorpayPayment::where('status', 'paid')->count();
            $recentRazorpayPayments = RazorpayPayment::with('user:id,name,email')
                ->orderByDesc('id')
                ->limit(5)
                ->get();
        }

        // Online order payment stats
        $onlinePaidOrders = Order::where('deleted', 0)->where('payment_status', 'Paid')->count();
        $onlineOrderRevenue = Order::where('deleted', 0)->where('payment_status', 'Paid')->sum('total_amount');

        return view('admin.dashboard', compact(
            'totalBookings', 'totalRevenue', 'pendingBookings', 'confirmedBookings', 'cancelledBookings', 
            'totalHotels', 'totalRooms', 'totalUsers',
            'totalOrders', 'totalProducts', 'totalCategories', 'totalSales',
            'totalSellers', 'totalBuyers', 'pendingOrders', 'completedOrders', 'cancelledOrders',
            'totalContactQueries', 'pendingContactQueries', 'solvedContactQueries',
            'totalSupportTickets', 'openSupportTickets', 'resolvedSupportTickets',
            'totalReturnRefundRequests', 'pendingReturnRefundRequests', 'completedReturnRefundRequests',
            'totalSellerKycProfiles', 'pendingSellerKyc', 'verifiedSellerKyc', 'notSubmittedSellerKyc',
            'totalSellerSettlements', 'pendingSellerSettlements', 'processingSellerSettlements', 'paidSellerSettlements',
            'totalOrderTimelineEvents', 'todayOrderTimelineEvents',
            'totalAuditLogEntries', 'todayAuditLogEntries',
            'totalCmsPages', 'activeCmsPages', 'activePolicyPages',
            'recentContactQueries', 'recentSupportTickets', 'recentReturnRefundRequests', 'recentAuditLogs',
            'totalRazorpayPayments', 'razorpayPaidAmount', 'razorpayPendingPayments', 'razorpayPaidPayments',
            'recentRazorpayPayments', 'onlinePaidOrders', 'onlineOrderRevenue'
        ));
    }

    private function sellerDashboard($user)
    {
        $sellerId = $user->id;
        $hasHotelOwnerColumn = Schema::hasColumn('hotels', 'created_by');
        $hasProductOwnerColumn = Schema::hasColumn('products', 'created_by');
        
        // Get seller's business category
        $userInfo = $user->user_info;
        $businessCategoryId = $userInfo ? $userInfo->business_category_id : null;
        $isHotelSeller = ($businessCategoryId == 4); // 4 = Hotel and Resort

        if ($isHotelSeller) {
            // Hotel Seller Dashboard
            if ($hasHotelOwnerColumn) {
                $hotelCount = Hotel::where('created_by', $sellerId)->count();
                $bookings = Booking::whereHas('hotel', function($q) use ($sellerId) {
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
            // Product Seller Dashboard
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

                $orderCount = $orders->count();

                // Count products by this seller
                $productCount = Product::where('created_by', $sellerId)
                    ->where('deleted', 0)
                    ->count();

                // Calculate total sales only for seller's own items
                $totalSales = 0;
                $totalReceivedAmount = 0;

                foreach ($orders as $order) {
                    foreach ($order->items as $item) {
                        if ($item->product && $item->product->created_by == $sellerId) {
                            $totalSales += $item->price * $item->quantity;
                            $totalReceivedAmount += $item->admin_paid_amount ?? 0;
                        }
                    }
                }

                // Shiprocket shipment data — orders that have been pushed to Shiprocket
                $shiprocketOrders = Order::whereHas('items.product', function($q) use ($sellerId) {
                        $q->where('created_by', $sellerId);
                    })
                    ->whereNotNull('shiprocket_order_id')
                    ->where('deleted', 0)
                    ->orderByDesc('created_at')
                    ->limit(10)
                    ->get();
            } else {
                $orderCount       = 0;
                $productCount     = 0;
                $totalSales       = 0;
                $totalReceivedAmount = 0;
                $shiprocketOrders = collect();
            }

            // Seller KYC / pickup address info
            $sellerKyc = $user->kycVerification;

            return view("home.seller_dashboard", compact(
                'isHotelSeller', 'orderCount', 'productCount',
                'totalSales', 'totalReceivedAmount',
                'shiprocketOrders', 'sellerKyc'
            ));
        }
    }
}
