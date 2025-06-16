<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.6; }
        h2 { margin-bottom: 0; }
        p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>INVOICE #{{ $order->id }}</h2>
    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

    <hr>

    <p><strong>Pembeli:</strong> {{ $order->user->name }}</p>
    <p><strong>Alamat:</strong> {{ $order->shipping_address ?? '-' }}</p>
    <p><strong>Pengiriman:</strong> {{ $order->shipping_method ?? '-' }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->product->title ?? '-' }}</td>
                <td>Rp {{ number_format($order->product->saleprice ?? 0, 0, ',', '.') }}</td>
                <td>{{ $order->quantity }}</td>
                <td>Rp {{ number_format(($order->product->saleprice ?? 0) * $order->quantity, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 30px;"><strong>Terima kasih telah berbelanja di ThriftHunt!</strong></p>
</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
    <title>Invoice Pesanan #{{ $order->order_number ?? $order->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .container { width: 90%; margin: auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; padding: 0; color: #0d6efd; }
        .invoice-details, .customer-details { margin-bottom: 20px; }
        .invoice-details table, .customer-details table { width: 100%; border-collapse: collapse; }
        .invoice-details th, .invoice-details td, .customer-details th, .customer-details td { padding: 8px; text-align: left; vertical-align: top; }
        .product-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .product-table th, .product-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .product-table th { background-color: #f2f2f2; }
        .total-section { margin-top: 30px; text-align: right; }
        .total-section table { width: 40%; float: right; border-collapse: collapse; }
        .total-section td { padding: 8px; text-align: right; border: 1px solid #ddd; }
        .total-section .grand-total { font-size: 16px; font-weight: bold; background-color: #e9ecef; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <p><strong>ThriftHunt</strong></p>
            <p>Jln. Contoh No. 123, Kota Bekasi, Jawa Barat</p>
            <p>Email: info@thrifthunt.com | Telp: +62 812 345 6789</p>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <strong>Invoice No:</strong> #{{ $order->order_number ?? $order->id }}<br>
                        <strong>Tanggal:</strong> {{ $order->created_at->format('d F Y') }}<br>
                        <strong>Status:</strong> {{ ucfirst($order->status) }}
                    </td>
                    <td style="width: 50%;">
                        <strong>Pembeli:</strong> {{ $order->user->name ?? 'N/A' }}<br>
                        <strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}<br>
                        <strong>Alamat Pengiriman:</strong> {{ $order->shipping_address ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Deskripsi Item</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->title ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp. {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table>
                <tr>
                    <td>Subtotal Barang:</td>
                    <td>Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim ({{ $order->shipping_method ?? 'N/A' }}):</td>
                    <td>Rp. 0</td> {{-- Jika ada kolom ongkir di order, gunakan itu --}}
                </tr>
                <tr class="grand-total">
                    <td>Grand Total:</td>
                    <td>Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <div class="footer">
            <p>Terima kasih telah berbelanja di ThriftHunt!</p>
            <p>&copy; {{ date('Y') }} ThriftHunt. All rights reserved.</p>
        </div>
    </div>
</body>
</html>