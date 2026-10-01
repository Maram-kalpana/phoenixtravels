<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ApiPageController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\RazorpayController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ---------------- Start Admin Controller ----------------
Route::post('admin/login', [AdminController::class, 'login']);
Route::post('admin/store-list', [AdminController::class, 'store_list']);
Route::post('admin/search-store', [AdminController::class, 'search_store_list']);
Route::post('admin/orders-report-list', [AdminController::class, 'orders_report_list']);
// ---------------- End Admin Controller ----------------
// ---------------- UsersController ----------------
Route::post('user-signup', [UserController::class, 'signup']);
Route::post('login', [UserController::class, 'login']);
Route::post('otp-verify', [UserController::class, 'OtpVerification']);
Route::post('get_profile', [UserController::class, 'get_profile']);
Route::post('update_profile', [UserController::class, 'update_profile']);

Route::post('dashboard', [UserController::class, 'dashboard']);
Route::get('terms-and-conditions/{id}', [UserController::class, 'terms_and_conditions']);
Route::get('about-us/{id}', [UserController::class, 'about_us']);
Route::get('privacy-policy/{id}', [UserController::class, 'privacy_policy']);
Route::get('refund-policy/{id}', [UserController::class, 'refund_policy']);
Route::get('subscribe-plans', [UserController::class, 'subscribe_plans']);
Route::post('category-by-products', [UserController::class, 'category_by_products']);
Route::get('product-detail/{id}', [UserController::class, 'product_detail']);
Route::post('store-list', [UserController::class, 'store_list']);
Route::post('history-subscriptions', [UserController::class, 'history_subscriptions']);
Route::post('submit-subscriptions', [UserController::class, 'submit_history_subscriptions']);
Route::post('account-delete', [UserController::class, 'account_delete']);
Route::get('contact-us', [UserController::class, 'contact_us']);
Route::post('faq-list', [FaqController::class, 'list']);

Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::get('/payment-status', [PaymentController::class, 'getPaymentStatus']);
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook']);


Route::get('privacy-policy', [ApiPageController::class, 'index']);
Route::get('terms-and-conditions', [ApiPageController::class, 'terms_and_conditions']);
Route::get('about-us', [ApiPageController::class, 'about']);


Route::get('country-list', [UserController::class, 'country']);
Route::get('state-list/{id}', [UserController::class, 'state']);
Route::get('city-list/{country_id}/{state_id}', [UserController::class, 'city']);


Route::get('home-list', [HomeController::class, 'index']);

Route::post('/tours-category-by-tour', [HomeController::class, 'tour_category_by_tour']);



Route::post('/plan-by-price', [HomeController::class, 'plan_by_price']);


Route::post('package-list', [PackageController::class, 'index']);

Route::post('/tours-details', [PackageController::class, 'tours_details']);

Route::post('/submit-package', [PackageController::class, 'submit_pay']);

Route::post('/order-list', [PaymentController::class, 'order']);

Route::get('/coupon-list', [CouponController::class, 'index']);
Route::post('coupon/check', [CouponController::class, 'check_coupon']);
Route::post('coupon/apply', [CouponController::class, 'apply_coupon']);
Route::post('coupon/history', [CouponController::class, 'user_coupon_history']);
Route::get('coupons/active', [CouponController::class, 'active_coupons']);

Route::post('create-order', [RazorpayController::class, 'createOrder']);
Route::post('verify-payment', [RazorpayController::class, 'verifyPayment']);
Route::get('payment-status/{paymentId}', [RazorpayController::class, 'paymentStatus']);
Route::post('refund/{paymentId}', [RazorpayController::class, 'refundPayment']);

Route::get('notification-list', [UserController::class, 'notification']);

Route::get('/package-list', [PackageController::class, 'package_list']);

Route::get('/table-package-list', [PackageController::class, 'table_package_list']);
