<?php

namespace App\Http\Controllers;

use App\Models\Product; // Pastikan model Product di-import
use App\Models\Category; // Jika kamu ingin filter berdasarkan kategori juga
use App\Models\Page; // Jika kamu menggunakan pages untuk menu/submenu
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ... (metode-metode lain yang mungkin sudah ada)

    public function search(Request $request)
    {
        $query = $request->input('query'); // Ambil kata kunci dari input 'query'

        // Lakukan pencarian produk
        // Menggunakan "LIKE" dan wildcard (%) untuk mencari sebagian kata
        // Pencarian di kolom 'title' atau 'description'
        $products = Product::where('title', 'like', '%' . $query . '%')
                           ->orWhere('description', 'like', '%' . $query . '%')
                           ->paginate(12); // Paginasi hasil pencarian, 12 produk per halaman

        // Jika kamu memerlukan data menu, submenu, dan kategori untuk layout
        $menu = Page::where('is_group', 0)->where('is_active', 1)->get(); // Contoh: Ambil pages yang bukan grup dan aktif
        $submenu = Page::where('is_group', 1)->where('is_active', 1)->get(); // Contoh: Ambil pages yang grup dan aktif
        $categories = Category::all(); // Ambil semua kategori
        $appname = config('app.name', 'ThriftHunt'); // Ambil nama aplikasi

        return view('products.search_results', compact('products', 'query', 'menu', 'submenu', 'categories', 'appname'));
    }

    // ... (metode-metode lain)
}