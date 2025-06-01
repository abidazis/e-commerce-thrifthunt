<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        logger('Form Pesan masuk');
        logger($request->all());
        
        Order::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'quantity' => 1,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pesanan berhasil dikirim!');
    }
}
