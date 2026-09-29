<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\front\HomeController as FrontHomeController;
use App\Http\Controllers\front\RegisterController;
use App\Http\Controllers\front\ProductController as FrontProductController;
use App\Http\Controllers\SellReportController;

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;

// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/', [FrontHomeController::class, 'index'])->name("index");
Route::get('/computers', [FrontHomeController::class, 'computers'])->name("computers");
Route::get('/electronics', [FrontHomeController::class, 'electronics'])->name("electronics");
Route::get('/groceries', [FrontHomeController::class, 'groceries'])->name("groceries");
Route::get('/cosmetics', [FrontHomeController::class, 'cosmetics'])->name("cosmetics");
Route::get('/hotels-and-resorts', [FrontHomeController::class, 'hotels_resorts'])->name("hotels_resorts");
// Route::get('/register-buisness', [FrontHomeController::class, 'register_buisness'])->name("register_buisness");
Route::resource("register-buisness", RegisterController::class);
Route::get("register-buyer", [RegisterController::class, 'register_buyer'])->name("register_buyer");
Route::post("buyer-store", [RegisterController::class, 'buyer_store'])->name("buyer_store");

Route::get('/product/{id}', [FrontProductController::class, 'show'])->name('product.show');

Route::get('/cart_add/{id}', [FrontProductController::class, 'cart_add'])->name('cart_add');
Route::get('/cart_list', [FrontProductController::class, 'cart_list'])->name('cart_list');
Route::post('/cart_update/{id}', [FrontProductController::class, 'cart_update'])->name('cart_update');
Route::get('/cart_remove/{id}', [FrontProductController::class, 'cart_remove'])->name('cart_remove');
Route::post('/place-order', [FrontProductController::class, 'place_order'])->name('place_order');

Route::post('/checkout/buy-now/{id}', [FrontProductController::class, 'buyNow'])->name('checkout.buy-now');

Route::get("/login", function () {
    return view("auth.login");
});
Route::get("/register", function () {
    return view("auth.register");
});
Route::post('register', [AuthController::class, 'register'])->name("register");
Route::post('login', [AuthController::class, 'login'])->name("login");
Route::post('logout', [AuthController::class, 'logout'])->name("logout");

Route::group(['middleware' => ['auth', 'preventBackHistory']], function () {
    Route::get('admin', [HomeController::class, 'index'])->name("dashboard");
    Route::get('admin/dashboard', [HomeController::class, 'index'])->name("dashboard");
    Route::get('admin/contact-us', [HomeController::class, 'contact_us'])->name("contact-us");
    Route::resource("admin/menus", MenuController::class);
    Route::post('menu_status_change', [MenuController::class, 'menu_status_change'])->name("menu_status_change");
    Route::resource("admin/roles", App\Http\Controllers\RoleController::class);
    Route::resource("admin/users", App\Http\Controllers\UserController::class);
    Route::post('user_status_change', [App\Http\Controllers\UserController::class, 'user_status_change'])->name("user_status_change");
    Route::post('user_seller_approval', [App\Http\Controllers\UserController::class, 'user_seller_approval'])->name("user_seller_approval");
    Route::post('user_unique_email_check', [App\Http\Controllers\UserController::class, 'user_unique_email_check'])->name("user_unique_email_check");
    Route::post('product_status_change', [App\Http\Controllers\ProductController::class, 'product_status_change'])->name("product_status_change");
    Route::resource("admin/products", App\Http\Controllers\ProductController::class);
    Route::resource("admin/loans", App\Http\Controllers\LoanController::class);
    Route::post('loan_status_change', [App\Http\Controllers\LoanController::class, 'loan_status_change'])->name("loan_status_change");
    // Route::resource("admin/reports", App\Http\Controllers\ReportController::class);

    Route::post('category_status_change', [CategoryController::class, 'category_status_change'])->name("category_status_change");
    Route::resource("admin/categories", CategoryController::class);
    Route::post('order_status_change', [OrderController::class, 'order_status_change'])->name("order_status_change");
    Route::post('payment_status_change', [OrderController::class, 'payment_status_change'])->name("payment_status_change");
    Route::resource("admin/orders", OrderController::class);
    Route::resource("admin/sell-report", SellReportController::class);
    Route::post('/update-delivery-date', [OrderController::class, 'updateDeliveryDate'])->name('updateDeliveryDate');
    Route::post('/update-seller-payment', [OrderController::class, 'updateSellerPayment'])->name('updateSellerPayment');
});


