<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\ShopController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;


// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.details');

// Cart
Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart.index');

// Language Switcher
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'bn'])) {
        Session::put('locale', $locale);
    }
    return back();
})->name('language.switch');

// SSLCommerz Callback Routes (CSRF Excluded in bootstrap/app.php)
Route::post('/pay/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/pay/fail', [PaymentController::class, 'fail'])->name('payment.fail');
Route::post('/pay/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Order Success Page
    Route::get('/order-success/{invoice_no}', [CheckoutController::class, 'success'])->name('order.success');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // User Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders'); //user.orders
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');

    // Profile & Password Update
    Route::post('/dashboard/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update.info');
    Route::post('/dashboard/password/update', [DashboardController::class, 'updatePassword'])->name('profile.update.password');

    // Default Laravel Breeze/Jetstream Profile Routes (Optional)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/orders/{invoice_no}', [DashboardController::class, 'orderDetails'])->name('dashboard.order.details');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/invoice/{id}', [InvoiceController::class, 'download'])->name('invoice.download');
    Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon/remove', [CartController::class, 'removeCoupon'])->name('coupon.remove');

});

// API Search Route
Route::get('/api/search', [HomeController::class, 'search'])->name('api.search');

// Track Order Page
Route::match(['get', 'post'], '/track-order', [HomeController::class, 'trackOrder'])->name('track.order');

// Offers Page Route
Route::get('/offers', [HomeController::class, 'offers'])->name('offers.index');

// Contact Page View
Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact.index');

// Form Submission
Route::post('/contact-send', [ContactController::class, 'send'])->name('contact.send');

// Vendor Registration Routes
Route::get('/become-a-vendor', function () {
    return Inertia::render('VendorRegister');
})->name('vendor.register');

Route::post('/vendor-store', [VendorController::class, 'store'])->name('vendor.store');

Route::get('/flash-sale/{id}', [FlashSaleController::class, 'show'])->name('flash-sale.show');

// Shop Routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

require __DIR__.'/auth.php';
