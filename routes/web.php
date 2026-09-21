<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;



/*
|--------------------------------------------------------------------------
| Web Routes — Frennz.Stuff
|--------------------------------------------------------------------------
| Semua halaman masih murni tampilan statis hasil migrasi dari HTML asli.
| Data produk/keranjang/wishlist/pesanan masih dikelola di sisi client
| (localStorage lewat public/js/data.js & public/js/store.js), jadi route
| di sini cukup mengembalikan view — belum ada Controller/Model.
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['GET', 'POST'], '/switch-account', [AuthController::class, 'switchAccount'])->name('switch.account');

Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});

Route::get('/', function () {
    return view('home.index');
})->name('home');

Route::get('/shop', [ProductController::class, 'index'])->name('products.index');

// Produk memakai query string ?id=... (bukan route segment), sama seperti
// product.html?id=... di versi asli — biar js/data.js & js/store.js tidak perlu diubah.
Route::get('/product', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', function () {
    return view('cart.index');
})->name('cart.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');

Route::get('/wishlist', function () {
    return view('wishlist.index');
})->name('wishlist.index');

Route::get('/order-success', function () {
    return view('order-success');
})->name('order.success');

Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
| Sama seperti halaman toko, data masih dikelola sepenuhnya lewat
| public/js/data.js & public/js/store.js (localStorage) — belum ada
| Controller/Model/auth admin. Tinggal sambungkan ke database saat
| backend beneran dibuat.
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');

    Route::get('/promos', [AdminVoucherController::class, 'index'])->name('promos');
    Route::post('/promos', [AdminVoucherController::class, 'store'])->name('promos.store');
    Route::patch('/promos/{voucher}/toggle', [AdminVoucherController::class, 'toggle'])->name('promos.toggle');
    Route::delete('/promos/{voucher}', [AdminVoucherController::class, 'destroy'])->name('promos.destroy');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
});