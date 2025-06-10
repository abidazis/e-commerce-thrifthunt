<!DOCTYPE html>
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
</html>
