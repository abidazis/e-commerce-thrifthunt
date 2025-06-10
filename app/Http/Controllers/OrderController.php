<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public function invoice(Order $order)
    {
        $order->load(['user', 'product']); // pastikan relasi tersedia
        return view('admin.invoice', compact('order'));
    }
    public function invoicePdf(Order $order)
    {
        $order->load(['user', 'product']);
        $pdf = Pdf::loadView('admin.invoice-pdf', compact('order'));
        return $pdf->download('invoice-'.$order->id.'.pdf');
    }
}
