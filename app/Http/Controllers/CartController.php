<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function add(Request $request)
    {
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'quantity' => 1,
        ]);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
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

    public function remove($id)
    {
        Cart::where('id', $id)->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Produk dihapus dari keranjang');
    }
}

