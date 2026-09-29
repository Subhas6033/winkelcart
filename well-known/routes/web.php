<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\front\HomeController as FrontHomeController;
use App\Http\Controllers\front\RegisterController;
use App\Http\Controllers\front\ProductController as FrontProductController;
use App\Http\Controllers\front\PageController as FrontPageController;
use App\Http\Controllers\front\ProfileController as FrontProfileController;
use App\Http\Controllers\front\SupportController as FrontSupportController;
use App\Http\Controllers\SellReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ContactQueryController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\AdminReturnRefundController;
use App\Http\Controllers\AdminSellerKycController;
use App\Http\Controllers\AdminSellerSettlementController;
use App\Http\Controllers\AdminOrderTimelineController;
use App\Http\Controllers\SellerOrderTimelineController;
use App\Http\Controllers\AdminAuditLogController;
use App\Http\Controllers\AdminCmsPageController;
use App\Http\Controllers\AdminNotificationController;

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;

use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\ContactController;

use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\ShiprocketWebhookController;
use App\Http\Controllers\ShiprocketShippingController;
use App\Http\Controllers\ShippingCalculationController;

// Product search route
Route::get('/search', [FrontProductController::class, 'search'])->name('product.search');

// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/', [FrontHomeController::class, 'index'])->name("index");
Route::get('/computers', [FrontHomeController::class, 'computers'])->name("computers");
Route::get('/electronics', [FrontHomeController::class, 'electronics_combined'])->name("electronics");
Route::get('/appliances', [FrontHomeController::class, 'appliances'])->name("appliances");
Route::get('/mobile', [FrontHomeController::class, 'mobile'])->name("mobile");
Route::get('/groceries', [FrontHomeController::class, 'groceries'])->name("groceries");
Route::get('/cosmetics', [FrontHomeController::class, 'cosmetics'])->name("cosmetics");
Route::get('/hotels-and-resorts', [FrontHomeController::class, 'hotels_resorts'])->name("hotels_resorts");
use App\Http\Controllers\HotelController;
Route::get('/hotel-details/{id}', [HotelController::class, 'show'])->name('hotel.details');
Route::get('/hotels', [HotelController::class, 'frontendList'])->name('hotels.index');
// Room booking routes (handled by BookingController)
Route::get('room/{room}/book', [App\Http\Controllers\BookingController::class, 'create'])->name('booking.create');
Route::post('room/{room}/book', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
Route::get('booking/confirmation/{bookingCode}', [App\Http\Controllers\BookingController::class, 'confirmation'])->name('booking.confirmation')->middleware('auth');
Route::post('booking/check-availability', [App\Http\Controllers\BookingController::class, 'checkAvailability'])->name('booking.check-availability');
// Route::get('/register-buisness', [FrontHomeController::class, 'register_buisness'])->name("register_buisness");
Route::get('/contact', [FrontHomeController::class, 'contact'])->name("contact");
Route::get('/terms-and-conditions', [FrontPageController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [FrontPageController::class, 'privacy'])->name('privacy');
Route::get('/return-refund-policy', [FrontPageController::class, 'returnRefund'])->name('return_refund');
Route::get('/shipping-delivery-policy', [FrontPageController::class, 'shippingDelivery'])->name('shipping_delivery');
Route::get('/cancellation-policy', [FrontPageController::class, 'cancellation'])->name('cancellation_policy');
Route::get('/payment-failed', [FrontPageController::class, 'paymentFailed'])->name('payment.failed');
// Contact form submission route
Route::post('/send-email', [ContactController::class, 'send'])->name('contact.send');
Route::get('/support', [FrontSupportController::class, 'index'])->name('support.index');
Route::post('/support', [FrontSupportController::class, 'store'])->name('support.store');
Route::resource("register-buisness", RegisterController::class);
Route::post('register-buisness/create-payment', [RegisterController::class, 'createRegistrationPayment'])->name('register.create_payment');

// Seller upgrade to pro plan routes (must be after resource route)
Route::middleware('auth')->get('/seller/upgrade-to-pro', [RegisterController::class, 'upgradeToProPlan'])->name('seller.upgrade-to-pro');
Route::middleware('auth')->post('/seller/complete-upgrade', [RegisterController::class, 'completeProUpgrade'])->name('seller.complete-upgrade');
Route::get("register-buyer", [RegisterController::class, 'register_buyer'])->name("register_buyer");
Route::post("buyer-store", [RegisterController::class, 'buyer_store'])->name("buyer_store");


// Product review submission (frontend)
Route::post('/product/{id}/review', [\App\Http\Controllers\front\ReviewController::class, 'store'])->middleware('auth')->name('product.review.store');
Route::get('/product/{id}', [FrontProductController::class, 'show'])->name('product.show');

Route::get('/cart_add/{id}', [FrontProductController::class, 'cart_add'])->name('cart_add');
Route::get('/cart_list', [FrontProductController::class, 'cart_list'])->name('cart_list');
Route::post('/cart_update/{id}', [FrontProductController::class, 'cart_update'])->name('cart_update');
Route::get('/cart_remove/{id}', [FrontProductController::class, 'cart_remove'])->name('cart_remove');
Route::post('/place_order/{id?}', [FrontProductController::class, 'place_order'])->name('place_order');

// My Orders (requires login)
Route::get('/my-orders', [FrontProductController::class, 'my_orders'])->middleware('auth')->name('my_orders');
Route::get('/order/{orderNumber}', [FrontProductController::class, 'order_detail'])->middleware('auth')->name('order_detail');
Route::post('/order/{orderNumber}/cancel', [FrontProductController::class, 'cancel_order'])->middleware('auth')->name('cancel_order');
Route::get('/order/{orderNumber}/invoice', [FrontProductController::class, 'download_invoice'])->middleware('auth')->name('download_invoice');
Route::get('/order-success/{orderNumber?}', [FrontPageController::class, 'orderSuccess'])->middleware('auth')->name('order.success');

// Track Order (public - no login required)
Route::get('/track-order', [FrontProductController::class, 'track_order'])->name('track_order');
Route::post('/track-order', [FrontProductController::class, 'track_order_search'])->name('track_order_search');
Route::get('/track-order/{orderNumber}', [FrontProductController::class, 'track_order_direct'])->name('track_order_direct');

// Buy Now (GET) - Show Order Summary (requires login)
Route::get('/buy_now/{id}', [FrontProductController::class, 'buyNowOrderSummary'])->middleware('auth')->name('buy_now');

Route::get("/login", function () {
    return view("auth.login");
});
Route::get("/register", function () {
    return view("auth.register");
});
Route::get('/mail-test', function () {
    Mail::raw('Test email', function ($message) {
        $message->from('info@winkelkart.com', 'Winkelkart')
                ->to('dasbapi394@gmail.com')
                ->subject('Test Mail');
    });

    return 'Mail attempted';
});
Route::post('register', [AuthController::class, 'register'])->name("register");
Route::post('login', [AuthController::class, 'login'])->name("login");
Route::post('logout', [AuthController::class, 'logout'])->name("logout");

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::group(['middleware' => ['auth', 'preventBackHistory']], function () {
            // ── Razorpay Payment Routes ──────────────────────────────────────
            Route::post('razorpay/create-order', [RazorpayController::class, 'createOrder'])->name('razorpay.create_order');
            Route::post('razorpay/verify-payment', [RazorpayController::class, 'verifyPayment'])->name('razorpay.verify_payment');
            Route::post('razorpay/payment-failed', [RazorpayController::class, 'paymentFailed'])->name('razorpay.payment_failed');

            // ── Dynamic Shipping Calculation ─────────────────────────────────
            Route::post('shipping/calculate-cart', [ShippingCalculationController::class, 'calculateCart'])->name('shipping.calculate_cart');
            Route::post('shipping/calculate-product', [ShippingCalculationController::class, 'calculateProduct'])->name('shipping.calculate_product');

            // Shiprocket Webhook (for tracking updates)
            Route::post('shiprocket/webhook', [ShiprocketWebhookController::class, 'handleWebhook'])->name('shiprocket.webhook');

            // Shiprocket Shipping Management
            Route::get('admin/shiprocket-shipping', [ShiprocketShippingController::class, 'index'])->middleware('admin')->name('admin.shiprocket_shipping.index');
            Route::post('admin/shiprocket/request-pickup', [ShiprocketShippingController::class, 'requestPickup'])->middleware('admin')->name('admin.shiprocket.request_pickup');
            Route::post('admin/shiprocket/refresh-tracking', [ShiprocketShippingController::class, 'refreshTracking'])->middleware('admin')->name('admin.shiprocket.refresh_tracking');
            Route::post('admin/shiprocket/create-shipment', [ShiprocketShippingController::class, 'createShipment'])->middleware('admin')->name('admin.shiprocket.create_shipment');
            // Seller-facing Shiprocket page
            Route::get('seller/shiprocket-shipping', [ShiprocketShippingController::class, 'sellerIndex'])->name('seller.shiprocket_shipping.index');
            Route::post('admin/shiprocket/assign-awb', [ShiprocketShippingController::class, 'assignAwb'])->middleware('admin')->name('admin.shiprocket.assign_awb');
            Route::post('admin/shiprocket/generate-label', [ShiprocketShippingController::class, 'generateLabel'])->middleware('admin')->name('admin.shiprocket.generate_label');
            Route::post('admin/shiprocket/generate-invoice', [ShiprocketShippingController::class, 'generateInvoice'])->middleware('admin')->name('admin.shiprocket.generate_invoice');

            // Room Management (Admin)
            Route::resource('admin/hotels.rooms', App\Http\Controllers\RoomController::class, [
                'names' => [
                    'index' => 'admin.rooms.index',
                    'create' => 'admin.rooms.create',
                    'store' => 'admin.rooms.store',
                    'edit' => 'admin.rooms.edit',
                    'update' => 'admin.rooms.update',
                    'destroy' => 'admin.rooms.destroy',
                ]
            ])->except(['show']);

            // Amenity Management (Admin)
            Route::resource('admin/amenities', App\Http\Controllers\AmenityController::class, [
                'names' => [
                    'index' => 'admin.amenities.index',
                    'create' => 'admin.amenities.create',
                    'store' => 'admin.amenities.store',
                    'edit' => 'admin.amenities.edit',
                    'update' => 'admin.amenities.update',
                    'destroy' => 'admin.amenities.destroy',
                ]
            ])->except(['show']);

            // Booking Management (Admin)
            Route::get('admin/bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('admin.bookings.index');
            Route::post('admin/bookings/{id}/confirm', [App\Http\Controllers\BookingController::class, 'confirm'])->name('admin.bookings.confirm');
        // Booking routes for users
        Route::middleware(['auth'])->group(function () {
            Route::get('room/{room}/book', [App\Http\Controllers\BookingController::class, 'create'])->name('booking.create');
            Route::post('room/{room}/book', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
            Route::get('user/bookings', [App\Http\Controllers\BookingController::class, 'userBookings'])->name('user.bookings');
            Route::post('user/bookings/{id}/cancel', [App\Http\Controllers\BookingController::class, 'cancel'])->name('user.bookings.cancel');
        });
        // Hotel Management (Admin)
        Route::resource('admin/hotels', App\Http\Controllers\HotelController::class, [
            'names' => [
                'index' => 'admin.hotels.index',
                'create' => 'admin.hotels.create',
                'store' => 'admin.hotels.store',
                'edit' => 'admin.hotels.edit',
                'update' => 'admin.hotels.update',
                'destroy' => 'admin.hotels.destroy',
            ]
        ])->except(['show'])->middleware('admin');
            // Room Management (Admin)
            Route::resource('admin/hotels.rooms', App\Http\Controllers\RoomController::class, [
                'names' => [
                    'index' => 'admin.rooms.index',
                    'create' => 'admin.rooms.create',
                    'store' => 'admin.rooms.store',
                    'edit' => 'admin.rooms.edit',
                    'update' => 'admin.rooms.update',
                    'destroy' => 'admin.rooms.destroy',
                ]
            ])->except(['show'])->middleware('admin');

            // Amenity Management (Admin)
            Route::resource('admin/amenities', App\Http\Controllers\AmenityController::class, [
                'names' => [
                    'index' => 'admin.amenities.index',
                    'create' => 'admin.amenities.create',
                    'store' => 'admin.amenities.store',
                    'edit' => 'admin.amenities.edit',
                    'update' => 'admin.amenities.update',
                    'destroy' => 'admin.amenities.destroy',
                ]
            ])->except(['show'])->middleware('admin');

            // Booking Management (Admin)
            Route::get('admin/bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('admin.bookings.index')->middleware('admin');
            Route::get('admin/bookings/{id}', [App\Http\Controllers\BookingController::class, 'show'])->name('admin.bookings.show')->middleware('admin');
            Route::post('admin/bookings/{id}/confirm', [App\Http\Controllers\BookingController::class, 'confirm'])->name('admin.bookings.confirm')->middleware('admin');
            Route::post('admin/bookings/{id}/complete', [App\Http\Controllers\BookingController::class, 'complete'])->name('admin.bookings.complete')->middleware('admin');
            Route::post('admin/bookings/{id}/cancel', [App\Http\Controllers\BookingController::class, 'adminCancel'])->name('admin.bookings.cancel')->middleware('admin');

            // Razorpay Payments Management (Admin)
            Route::get('admin/razorpay-payments', [App\Http\Controllers\AdminRazorpayPaymentController::class, 'index'])->name('admin.razorpay_payments.index')->middleware('admin');
    Route::get('admin', [App\Http\Controllers\AdminDashboardController::class, 'index'])->middleware('admin')->name("dashboard");
    Route::get('admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->middleware('admin')->name("dashboard");
    Route::get('admin/contact-us', [HomeController::class, 'contact_us'])->name("contact-us");
    Route::resource("admin/menus", MenuController::class);
    Route::post('menu_status_change', [MenuController::class, 'menu_status_change'])->name("menu_status_change");
    Route::resource("admin/roles", App\Http\Controllers\RoleController::class);
    Route::resource("admin/users", App\Http\Controllers\UserController::class);
    Route::post('user_status_change', [App\Http\Controllers\UserController::class, 'user_status_change'])->name("user_status_change");
    Route::post('user_seller_approval', [App\Http\Controllers\UserController::class, 'user_seller_approval'])->name("user_seller_approval");
    Route::post('user_unique_email_check', [App\Http\Controllers\UserController::class, 'user_unique_email_check'])->name("user_unique_email_check");
    Route::post('product_status_change', [App\Http\Controllers\ProductController::class, 'product_status_change'])->name("product_status_change");
    Route::delete('admin/products/{product}/delete-image/{image}', [App\Http\Controllers\ProductController::class, 'deleteImage'])->name('products.delete_image');
    Route::post('admin/products/{product}/top-deal', [App\Http\Controllers\ProductController::class, 'updateTopDeal'])->name('products.top_deal.update');
    Route::resource("admin/products", App\Http\Controllers\ProductController::class);
    Route::resource("admin/reviews", App\Http\Controllers\ReviewController::class)->only(['index', 'destroy']);
    Route::resource("admin/loans", App\Http\Controllers\LoanController::class);
    Route::post('loan_status_change', [App\Http\Controllers\LoanController::class, 'loan_status_change'])->name("loan_status_change");
    // Route::resource("admin/reports", App\Http\Controllers\ReportController::class);

    Route::post('category_status_change', [CategoryController::class, 'category_status_change'])->name("category_status_change");
    Route::resource("admin/categories", CategoryController::class);

    // Sub-categories
    Route::resource("admin/sub-categories", App\Http\Controllers\SubCategoryController::class)->names('sub_categories');
    Route::get("admin/sub-categories-ajax/{id}", [App\Http\Controllers\SubCategoryController::class, 'getByCategoryId'])->name('sub_categories.by_category');
    Route::post("sub_category_status_change", [App\Http\Controllers\SubCategoryController::class, 'statusChange'])->name("sub_category_status_change");
    Route::post('order_status_change', [OrderController::class, 'order_status_change'])->name("order_status_change");
    Route::post('payment_status_change', [OrderController::class, 'payment_status_change'])->name("payment_status_change");
    Route::resource("admin/orders", OrderController::class);
    Route::resource("admin/sell-report", SellReportController::class);
    Route::post('/update-delivery-date', [OrderController::class, 'updateDeliveryDate'])->name('updateDeliveryDate');
    Route::post('/update-seller-payment', [OrderController::class, 'updateSellerPayment'])->name('updateSellerPayment');

    Route::get('admin/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::get('admin/settings/{id}/edit', [SettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('admin/settings/{id}', [SettingController::class, 'update'])->name('admin.settings.update');

    Route::get('profile-buyer', [FrontProfileController::class, 'show'])->name('profile_buyer');
    Route::get('edit-profile', [FrontProfileController::class, 'edit'])->name('profile.edit');
    Route::post('edit-profile', [FrontProfileController::class, 'update'])->name('profile.update');
    Route::get('seller/edit-profile', [FrontProfileController::class, 'sellerEdit'])->name('seller.profile.edit');
    Route::post('seller/edit-profile', [FrontProfileController::class, 'sellerUpdate'])->name('seller.profile.update');
    Route::get('seller/kyc', [FrontProfileController::class, 'sellerKycEdit'])->name('seller.kyc.edit');
    Route::post('seller/kyc', [FrontProfileController::class, 'sellerKycUpdate'])->name('seller.kyc.update');
    Route::post('seller/kyc/create-draft', [FrontProfileController::class, 'sellerKycCreateDraft'])->name('seller.kyc.create_draft');
    Route::get('seller/guide-download', [FrontProfileController::class, 'downloadSellerGuide'])->name('seller.guide.download');
    Route::get('manage-addresses', [FrontProfileController::class, 'addresses'])->name('profile.addresses');
    Route::post('manage-addresses', [FrontProfileController::class, 'updateAddress'])->name('profile.addresses.update');

    Route::get('admin/contact-queries', [ContactQueryController::class, 'index'])->middleware('admin')->name('admin.contact_queries.index');
    Route::post('admin/contact-queries/{contactSubmission}/status', [ContactQueryController::class, 'updateStatus'])->middleware('admin')->name('admin.contact_queries.update_status');
    Route::get('admin/support-tickets', [SupportTicketController::class, 'index'])->middleware('admin')->name('admin.support_tickets.index');
    Route::post('admin/support-tickets/{ticket}/status', [SupportTicketController::class, 'updateStatus'])->middleware('admin')->name('admin.support_tickets.update_status');

    Route::get('admin/return-refund-requests', [AdminReturnRefundController::class, 'index'])->middleware('admin')->name('admin.return_refunds.index');
    Route::post('admin/return-refund-requests', [AdminReturnRefundController::class, 'store'])->middleware('admin')->name('admin.return_refunds.store');
    Route::post('admin/return-refund-requests/{returnRefund}/status', [AdminReturnRefundController::class, 'updateStatus'])->middleware('admin')->name('admin.return_refunds.update_status');

    Route::get('admin/seller-kyc', [AdminSellerKycController::class, 'index'])->middleware('admin')->name('admin.seller_kyc.index');
    Route::post('admin/seller-kyc/{seller}', [AdminSellerKycController::class, 'upsert'])->middleware('admin')->name('admin.seller_kyc.upsert');

    Route::get('admin/seller-settlements', [AdminSellerSettlementController::class, 'index'])->middleware('admin')->name('admin.seller_settlements.index');
    Route::post('admin/seller-settlements', [AdminSellerSettlementController::class, 'store'])->middleware('admin')->name('admin.seller_settlements.store');
    Route::post('admin/seller-settlements/{settlement}/status', [AdminSellerSettlementController::class, 'updateStatus'])->middleware('admin')->name('admin.seller_settlements.update_status');

    Route::get('admin/order-status-timelines', [AdminOrderTimelineController::class, 'index'])->middleware('admin')->name('admin.order_timelines.index');
    Route::post('admin/order-status-timelines/{order}', [AdminOrderTimelineController::class, 'store'])->middleware('admin')->name('admin.order_timelines.store');

    // Seller Order Timelines
    Route::get('seller/order-status-timelines', [SellerOrderTimelineController::class, 'index'])->name('seller.order_timelines.index');
    Route::post('seller/order-status-timelines/{order}', [SellerOrderTimelineController::class, 'store'])->name('seller.order_timelines.store');
    Route::get('seller/seller-settlements', [AdminSellerSettlementController::class, 'sellerIndex'])->name('seller.seller_settlements.index');

    Route::get('admin/audit-logs', [AdminAuditLogController::class, 'index'])->middleware('admin')->name('admin.audit_logs.index');

    Route::get('admin/cms-pages', [AdminCmsPageController::class, 'index'])->middleware('admin')->name('admin.cms_pages.index');
    Route::get('admin/cms-pages/create', [AdminCmsPageController::class, 'create'])->middleware('admin')->name('admin.cms_pages.create');
    Route::post('admin/cms-pages', [AdminCmsPageController::class, 'store'])->middleware('admin')->name('admin.cms_pages.store');
    Route::get('admin/cms-pages/{cmsPage}/edit', [AdminCmsPageController::class, 'edit'])->middleware('admin')->name('admin.cms_pages.edit');
    Route::put('admin/cms-pages/{cmsPage}', [AdminCmsPageController::class, 'update'])->middleware('admin')->name('admin.cms_pages.update');
    Route::delete('admin/cms-pages/{cmsPage}', [AdminCmsPageController::class, 'destroy'])->middleware('admin')->name('admin.cms_pages.destroy');

    // Admin Notifications
    Route::get('admin/notifications', [AdminNotificationController::class, 'index'])->middleware('admin')->name('admin.notifications.index');
    Route::get('admin/notifications/recent', [AdminNotificationController::class, 'getRecent'])->middleware('admin')->name('admin.notifications.recent');
    Route::get('admin/notifications/unread-count', [AdminNotificationController::class, 'getUnreadCount'])->middleware('admin')->name('admin.notifications.unread_count');
    Route::get('admin/notifications/{id}/mark-read', [AdminNotificationController::class, 'markAsRead'])->middleware('admin')->name('admin.notifications.mark_read');
    Route::post('admin/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->middleware('admin')->name('admin.notifications.mark_all_read');
    Route::delete('admin/notifications/{id}', [AdminNotificationController::class, 'destroy'])->middleware('admin')->name('admin.notifications.destroy');
    Route::post('admin/notifications/clear-read', [AdminNotificationController::class, 'clearRead'])->middleware('admin')->name('admin.notifications.clear_read');

    Route::get('admin/seller-settlements/auto-calc', [AdminSellerSettlementController::class, 'getSettlementAmounts'])->middleware('admin')->name('admin.seller_settlements.auto_calc');
});
