<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Page;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        logger('--- MULAI CHECKOUT ---');
        logger($request->all());

        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();

            $carts = Cart::with('product')->where('user_id', $user->id)->get();

            if ($carts->isEmpty()) {
                return back()->with('error', 'Keranjang kamu kosong.');
            }

            foreach ($carts as $cart) {
                $product = $cart->product;

                if (!$product) {
                    continue; // skip jika produk sudah tidak tersedia
                }

                Order::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $cart->quantity,
                    'status' => 'pending',
                    'shipping_address' => $request->shipping_address,
                    'shipping_method' => $request->shipping_method,
                    'payment_method' => $request->payment_method,
                ]);

                logger('Sukses menyimpan order untuk product ID: ' . $product->id);
            }

            Cart::where('user_id', $user->id)->delete(); // kosongkan keranjang
            DB::commit();

            return redirect('/')->with('success', 'Pesanan kamu berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger('ERROR CHECKOUT: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pesanan. Silakan coba lagi.');
        }
    }

    public function index()
    {
        $user = auth()->user();
        $items = Cart::with('product')->where('user_id', $user->id)->get();

        // ambil data untuk layout
        $menu = Page::where(['is_group'=>0,'is_active'=>1])->get();
        $submenu = Page::where(['is_group'=>1,'is_active'=>1])->get();
        $categories = Category::all();
        $appname = config('app.name');

        return view('checkout.index', compact('items', 'menu', 'submenu', 'categories', 'appname'));
    }
}
