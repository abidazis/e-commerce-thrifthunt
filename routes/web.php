<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

// Import semua Models yang diperlukan
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Slider;

// Import semua Controllers yang diperlukan
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the Application's RouteServiceProvider within
| a group which contains the "web" middleware group. Now create something great!
|
*/

// Middleware untuk data umum di view (menu, submenu, categories, appname)
// Ini adalah cara untuk melewatkan data ke semua view jika diperlukan
// Namun, pendekatan yang lebih baik adalah melewatkannya dari controller masing-masing
// Karena Anda sudah melakukannya di banyak controller, ini mungkin tidak terlalu diperlukan
// Tapi bisa jadi fallback untuk rute dengan closure.
/*
View::composer('*', function ($view) {
    $view->with([
        'appname' => Config::get('app.name', 'ThriftHunt'),
        'menu' => Page::where(['is_group' => 0, 'is_active' => 1])->get(),
        'submenu' => Page::where(['is_group' => 1, 'is_active' => 1])->get(),
        'categories' => Category::all(),
    ]);
});
*/

// --- Rute Halaman Utama (Homepage) ---
Route::get('/', function () {
    $sliders = Slider::all();
    $categories = Category::all();
    $products = Product::all();
    $menu = Page::where(['is_group' => 0, 'is_active' => 1])->get();
    $submenu = Page::where(['is_group' => 1, 'is_active' => 1])->get();
    $appname = config('app.name'); // Menggunakan config() langsung

    return view('home', compact('sliders', 'categories', 'products', 'menu', 'submenu', 'appname'));
});

// --- Rute Autentikasi Kustom (Login & Register) ---
// Hapus Auth::routes(); karena Anda mengimplementasikan kustom
// Auth::routes();

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register/penjual', 'showPenjualForm')->name('register.penjual.form');
    Route::post('/register/penjual', 'registerPenjual')->name('register.penjual');
    Route::get('/register/pembeli', 'showPembeliForm')->name('register.pembeli.form');
    Route::post('/register/pembeli', 'registerPembeli')->name('register.pembeli');
});


// --- Rute Publik (Tidak Membutuhkan Autentikasi) ---

// Rute Produk
Route::controller(ProductController::class)->group(function () {
    Route::get('/products/search', 'search')->name('products.search'); // Ubah ke /products/search agar lebih spesifik
    Route::get('/product/{product}', 'show')->name('products.show'); // Menggunakan Route Model Binding {product}
});

// Rute Halaman Statis (Pages)
Route::get('/page/{page}', function (Page $page) { // Menggunakan Route Model Binding {page}
    $menu = Page::where(['is_group' => 0, 'is_active' => 1])->get();
    $submenu = Page::where(['is_group' => 1, 'is_active' => 1])->get();
    $categories = Category::all();
    $appname = config('app.name');

    // Asumsi view 'page.show' atau 'page'
    return view('page.show', compact('page', 'menu', 'submenu', 'categories', 'appname'));
})->name('page.show');


// --- Rute yang Membutuhkan Autentikasi (Middleware 'auth') ---
Route::middleware('auth')->group(function () {

    // Dashboard setelah login (jika /home bukan homepage utama)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Rute Cart
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add', 'add')->name('cart.add');
        Route::delete('/cart/remove/{id}', 'remove')->name('cart.remove'); // <--- PASTIKAN INI DELETE
        Route::put('/cart/update', 'update')->name('cart.update');
    });

    // Rute Checkout
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
    });

    // Rute Pesanan (Orders)
    Route::controller(OrderController::class)->group(function () {
        Route::post('/order/store', 'store')->name('order.store'); // Rute untuk order langsung (misal dari detail produk)

        // Rute untuk Pembeli
        Route::get('/my-orders', 'customerIndex')->name('orders.customer_index');
        Route::get('/my-orders/{order}', 'customerShow')->name('orders.customer_show'); // Menggunakan {order} untuk Route Model Binding
        Route::get('/my-orders/{order}/invoice-pdf', 'invoicePdf')->name('orders.invoice_pdf'); // Untuk pembeli download invoice

        // Rute untuk Admin/Penjual
        Route::get('/admin/orders/{order}/invoice', 'invoice')->name('admin.invoice'); // Invoice admin (HTML)
        Route::get('/admin/orders/{order}/invoice-pdf-admin', 'invoicePdf')->name('admin.invoice.pdf_admin'); // Invoice admin (PDF)

        // Rute BARU untuk Packing Slip PDF
        Route::get('/admin/orders/{order}/packing-slip-pdf', 'packingSlipPdf')->name('admin.packing_slip.pdf'); // <--- ROUTE BARU
        // });
    });

});