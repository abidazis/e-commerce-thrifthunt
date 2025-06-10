<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <h2>INVOICE #{{ $order->id }}</h2>
    <p><strong>Pembeli:</strong> {{ $order->user->name }}</p>
    <p><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
    <p><strong>Metode Pengiriman:</strong> {{ $order->shipping_method }}</p>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->product->title }}</td>
                <td>Rp {{ number_format($order->product->saleprice) }}</td>
                <td>{{ $order->quantity }}</td>
                <td>Rp {{ number_format($order->product->saleprice * $order->quantity) }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>Total:</strong> Rp {{ number_format($order->product->saleprice * $order->quantity) }}</p>
</body>
</html>
