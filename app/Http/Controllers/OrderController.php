<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; // Import model OrderItem
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Metode store ini mungkin perlu dipertimbangkan ulang jika semua order dari keranjang.
    // Jika ini adalah tombol "Beli Sekarang" langsung dari halaman produk (tanpa keranjang),
    // maka perlu disesuaikan agar membuat satu Order dan satu OrderItem.
    public function store(Request $request)
    {
        logger('Form Pesan masuk (Direct Order)');
        logger($request->all());

        // Pastikan product_id dikirim
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1; // Default 1 jika tidak ada quantity di request

        $itemPrice = $product->saleprice > 0 && $product->saleprice < $product->price ? $product->saleprice : $product->price;
        $totalAmount = $itemPrice * $quantity;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'DIR-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8)) . '-' . date('YmdHis'),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_address' => 'Default Address for Direct Order', // Perlu form input jika ini bukan dari keranjang
                'shipping_method' => 'Standard',
                'payment_method' => 'COD',
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price_at_purchase' => $itemPrice,
            ]);

            DB::commit();
            return redirect()->route('orders.customer_show', $order->id)->with('success', 'Pesanan berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger('ERROR DIRECT ORDER: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pesanan langsung: ' . $e->getMessage());
        }
    }


    public function customerIndex()
    {
        $userId = Auth::id(); // Mengambil ID pengguna yang sedang login

        // Penting: Pastikan baris ini ada dan tidak diubah
        // Mengambil order utama beserta item-itemnya, HANYA UNTUK PENGGUNA YANG LOGIN
        $orders = Order::with('items.product')
                       ->where('user_id', $userId) // <--- PASTIKAN FILTER INI ADA DAN AKTIF
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        // Debugging: Anda bisa menambahkan dd() di sini untuk melihat $userId dan hasil $orders
        // dd('User ID: ' . $userId, $orders->toArray());


        $menu = Page::where(['is_group' => 0, 'is_active' => 1])->get();
        $submenu = Page::where(['is_group' => 1, 'is_active' => 1])->get();
        $categories = Category::all();
        $appname = config('app.name');

        return view('orders.customer_index', compact('orders', 'menu', 'submenu', 'categories', 'appname'));
    }

    public function customerShow(Order $order)
    {
        // Debugging: Lihat ID user pesanan dan ID user yang login
        // dd('Order User ID: ' . $order->user_id, 'Auth User ID: ' . Auth::id());

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'items.product']);

        $menu = Page::where(['is_group' => 0, 'is_active' => 1])->get();
        $submenu = Page::where(['is_group' => 1, 'is_active' => 1])->get();
        $categories = Category::all();
        $appname = config('app.name');

        return view('orders.customer_show', compact('order', 'menu', 'submenu', 'categories', 'appname'));
    }

    public function invoice(Order $order)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'penjual')) {
             abort(403, 'Unauthorized action.');
        }
        $order->load(['user', 'items.product']); // Load item-item pesanan
        return view('admin.invoice', compact('order'));
    }

    public function invoicePdf(Order $order)
    {
        if (!Auth::check() || ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'penjual')) {
            abort(403, 'Unauthorized action.');
        }
        $order->load(['user', 'items.product']); // Load item-item pesanan
        $pdf = Pdf::loadView('admin.invoice-pdf', compact('order'));
        return $pdf->download('invoice-'.$order->order_number.'.pdf'); // Gunakan order_number untuk nama file
    }
}