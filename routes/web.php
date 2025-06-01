<?php

use App\Models\category;
use App\Models\page;
use App\Models\product;
use App\Models\slider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;

Route::middleware('auth')->group(function () {
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

Route::get('/register/penjual', [RegisterController::class, 'showPenjualForm']);
Route::post('/register/penjual', [RegisterController::class, 'registerPenjual']);

Route::get('/register/pembeli', [RegisterController::class, 'showPembeliForm']);
Route::post('/register/pembeli', [RegisterController::class, 'registerPembeli']);

Route::get('/', function () {
    $appname = Config::get('app.name');
    $sliders = slider::all();
    $menu = page::where(['is_group'=>0,'is_active'=>1])->get();
    $submenu = page::where(['is_group'=>1,'is_active'=>1])->get();
    $categories = category::all();
    $products = product::all();
    return view('home',compact('appname','sliders','menu','submenu','categories','products'));
});

Route::get('/page/{page:id}', function (page $page) {
    $appname = Config::get('app.name');
    $menu = page::where(['is_group'=>0,'is_active'=>1])->get();
    $submenu = page::where(['is_group'=>1,'is_active'=>1])->get();
    $categories = category::all();
    return view('page',compact('appname','menu','submenu','categories','page'));
});

Route::get('/product/{product:id}', function (product $product) {
    $appname = Config::get('app.name');
    $menu = page::where(['is_group'=>0,'is_active'=>1])->get();
    $submenu = page::where(['is_group'=>1,'is_active'=>1])->get();
    $categories = category::all();
    return view('detail_product',compact('appname','menu','submenu','categories','product'));
});