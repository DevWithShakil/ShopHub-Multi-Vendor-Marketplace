    <?php

    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\CheckoutController;
    use App\Http\Controllers\PaymentController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\WishlistController;
    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\App;

    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    });

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.details');

    Route::get('/cart', function () {
        return inertia('Cart');
    })->name('cart.index');

    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    });

    Route::get('/order-success/{invoice_no}', [CheckoutController::class, 'success'])->name('order.success');



    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

        // Dashboard Home
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // My Orders Page
        Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');

        // Profile Page
        Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');

        Route::post('/dashboard/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update.info');
        Route::post('/dashboard/password/update', [DashboardController::class, 'updatePassword'])->name('profile.update.password');
    });

    // language switcher routes
    Route::get('/language/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'bn'])) {
            Session::put('locale', $locale);
        }
        return back();
    })->name('language.switch');


    Route::post('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::post('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');
    Route::post('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

    require __DIR__.'/auth.php';
