<!DOCTYPE html>
<html>
<head>
    <title>Packing Slip Pesanan #{{ $order->order_number ?? $order->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif; font-size: 10px; line-height: 1.4; color: #333; }
        .container { width: 90%; margin: auto; padding: 15px; border: 1px dashed #ccc; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; padding: 0; font-size: 20px; color: #0d6efd; }
        .info-section { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .info-section div { margin-bottom: 5px; }
        .info-section strong { display: inline-block; width: 100px; }
        .product-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .product-table th, .product-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .product-table th { background-color: #f8f8f8; }
        .total-section { margin-top: 20px; text-align: right; }
        .total-section div { margin-bottom: 5px; }
        .total-section strong { display: inline-block; width: 120px; }
        .notes { margin-top: 20px; font-size: 9px; color: #666; }
        .disclaimer { font-size: 8px; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PACKING SLIP</h1>
            <p><strong>ThriftHunt - Detail Pesanan untuk Pengiriman</strong></p>
        </div>

        <div class="info-section">
            <div><strong>No. Pesanan:</strong> #{{ $order->order_number ?? $order->id }}</div>
            <div><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d F Y H:i') }}</div>
            <div><strong>Status Order:</strong> {{ ucfirst($order->status) }}</div>
        </div>

        <div class="info-section">
            <div><strong>Pembeli:</strong> {{ $order->user->name ?? 'N/A' }}</div>
            <div><strong>Alamat Pengiriman:</strong> {{ $order->shipping_address ?? 'N/A' }}</div>
            <div><strong>Metode Pengiriman:</strong> {{ $order->shipping_method ?? 'N/A' }}</div>
            <div><strong>Metode Pembayaran:</strong> {{ $order->payment_method ?? 'N/A' }}</div>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Produk</th>
                    <th>Kuantitas</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal Item</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->title ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Tidak ada item dalam pesanan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-section">
            <div><strong>Total Harga Barang:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
            <div><strong>Ongkos Kirim:</strong> Rp 0</div> {{-- Tambahkan logika jika ada kolom ongkir --}}
            <div><strong>Total Dibayar:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
        </div>

        <div class="notes">
            <p>Catatan untuk Kurir:</p>
            <p>Harap serahkan pesanan ini kepada pembeli yang tertera. Pastikan produk diterima dalam kondisi baik.</p>
        </div>

        <div class="disclaimer">
            Ini adalah packing slip, bukan faktur pajak. Untuk informasi lebih lanjut, kunjungi website kami.
        </div>
    </div>
</body>
</html>