<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Page; // Diperlukan untuk layout
use App\Models\Category; // Diperlukan untuk layout
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $userId = auth()->id();
        $productId = $request->product_id;
        $quantityToAdd = $request->quantity ?? 1; // Pastikan Anda mengirim 'quantity' dari form/request, default 1

        // 1. Cek apakah produk sudah ada di keranjang pengguna
        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            // 2. Jika produk sudah ada, tambahkan kuantitasnya
            $cartItem->quantity += $quantityToAdd;
            $cartItem->save();
            $message = 'Kuantitas produk di keranjang berhasil diperbarui!';
        } else {
            // 3. Jika produk belum ada, buat entri keranjang baru
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantityToAdd,
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang!';
        }

        return redirect()->route('cart.index')->with('success', $message);
    }

    public function index()
    {
        $items = Cart::with('product')->where('user_id', auth()->id())->get();

        // ambil variabel tambahan untuk layout
        $menu = \App\Models\Page::where(['is_group'=>0,'is_active'=>1])->get();
        $submenu = \App\Models\Page::where(['is_group'=>1,'is_active'=>1])->get();
        $categories = \App\Models\Category::all();
        $appname = config('app.name');

        return view('cart.index', compact('items', 'menu', 'submenu', 'categories', 'appname'));
    }

public function update(Request $request)
    {
        $itemId = $request->item_id;
        $newQuantity = $request->quantity;
        // $productId = $request->product_id; // Tidak perlu productId lagi di sini jika sudah ada itemId

        $cartItem = Cart::where('id', $itemId)
                        ->where('user_id', Auth::id())
                        ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Item keranjang tidak ditemukan.'], 404);
        }

        if ($newQuantity <= 0) {
            // Jika kuantitas <= 0, hapus item dari keranjang
            $cartItem->delete();
            $message = 'Produk dihapus dari keranjang.';
            // Karena item dihapus, kita tidak bisa lagi mengakses $cartItem->product
            $subtotalForDeletedItem = 0; // Subtotal untuk item yang dihapus adalah 0
        } else {
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            $message = 'Kuantitas berhasil diperbarui.';
            // Hitung subtotal untuk item yang baru diupdate
            $productPrice = $cartItem->product->saleprice > 0 && $cartItem->product->saleprice < $cartItem->product->price
                                ? $cartItem->product->saleprice
                                : $cartItem->product->price;
            $subtotalForUpdatedItem = $productPrice * $cartItem->quantity;
        }

        // Hitung ulang total keranjang dan subtotal untuk respons AJAX
        $updatedCartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $grandTotal = 0;
        foreach ($updatedCartItems as $item) {
            $productPrice = $item->product->saleprice > 0 && $item->product->saleprice < $item->product->price
                                ? $item->product->saleprice
                                : $item->product->price;
            $grandTotal += $productPrice * $item->quantity;
        }

        $cartCount = $updatedCartItems->sum('quantity'); // Jumlah total item di keranjang

        $response = [
            'success' => true,
            'message' => $message,
            // Perhatikan bahwa $subtotalForUpdatedItem hanya relevan jika item tidak dihapus
            'subtotal' => isset($subtotalForUpdatedItem) ? number_format($subtotalForUpdatedItem, 0, ',', '.') : '0',
            'grand_total' => number_format($grandTotal, 0, ',', '.'),
            'cart_count' => $cartCount, // Untuk update badge di navbar
            'item_removed' => ($newQuantity <= 0), // Tambahkan flag jika item dihapus
        ];

        return response()->json($response);
    }

    public function remove($id) // <--- UBAH NAMA METODE INI MENJADI 'remove'
    {
        Cart::where('id', $id)->where('user_id', auth()->id())->delete();

        // Setelah menghapus, hitung ulang total keranjang untuk response
        $updatedCartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $grandTotal = 0;
        foreach ($updatedCartItems as $item) {
            $productPrice = $item->product->saleprice > 0 && $item->product->saleprice < $item->product->price
                                ? $item->product->saleprice
                                : $item->product->price;
            $grandTotal += $productPrice * $item->quantity;
        }
        $cartCount = $updatedCartItems->sum('quantity');

        // Ini akan selalu menjadi respons AJAX karena ini dipanggil dari form hapus yang sudah kita buat AJAX-nya di Blade
        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang',
            'grand_total' => number_format($grandTotal, 0, ',', '.'),
            'cart_count' => $cartCount,
        ]);
    }
}