<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Page;
use App\Models\Order; // Model Order baru
use App\Models\OrderItem; // Model OrderItem baru
use App\Models\Product; // Pastikan Product diimport
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Untuk Order Number

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

            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($carts as $cart) {
                $product = $cart->product;

                if (!$product) {
                    throw new \Exception('Produk dengan ID ' . $cart->product_id . ' tidak ditemukan.'); // Lebih baik lempar exception
                }

                $itemPrice = $product->saleprice > 0 && $product->saleprice < $product->price ? $product->saleprice : $product->price;
                $totalAmount += $itemPrice * $cart->quantity;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $cart->quantity,
                    'price_at_purchase' => $itemPrice,
                ];
            }

            // Buat entri Order utama
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-' . Str::upper(Str::random(8)) . '-' . date('YmdHis'), // Contoh generate order number
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
            ]);

            // Simpan item-item pesanan ke tabel order_items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            Cart::where('user_id', $user->id)->delete(); // Kosongkan keranjang
            DB::commit();

            return redirect()->route('orders.customer_show', $order->id)->with('success', 'Pesanan kamu berhasil dikirim!'); // Redirect ke detail pesanan baru
        } catch (\Exception $e) {
            DB::rollBack();
            logger('ERROR CHECKOUT: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage()); // Tampilkan pesan error lebih detail
        }
    }

    // Metode index() tetap sama jika hanya menampilkan keranjang
    public function index()
    {
        $user = auth()->user();
        $items = Cart::with('product')->where('user_id', $user->id)->get();

        $menu = Page::where(['is_group'=>0,'is_active'=>1])->get();
        $submenu = Page::where(['is_group'=>1,'is_active'=>1])->get();
        $categories = Category::all();
        $appname = config('app.name');

        return view('checkout.index', compact('items', 'menu', 'submenu', 'categories', 'appname'));
    }
}