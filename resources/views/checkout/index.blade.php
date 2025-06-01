<x-layout :menu="$menu ?? []" :submenu="$submenu ?? []" :categories="$categories ?? []" :appname="$appname ?? 'ThriftHunt'">
<div class="container py-5">
    <h3>Checkout</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}">
        @csrf

        <h5 class="mt-4">Alamat Pengiriman</h5>
        <div class="mb-3">
            <textarea name="shipping_address" class="form-control" rows="3" required></textarea>
        </div>

        <h5>Jasa Pengiriman</h5>
        <div class="mb-3">
            <select name="shipping_method" class="form-control" required>
                <option value="JNE">JNE</option>
                <option value="SiCepat">SiCepat</option>
                <option value="POS Indonesia">POS Indonesia</option>
            </select>
        </div>

        <h5>Metode Pembayaran</h5>
        <div class="mb-3">
            <select name="payment_method" class="form-control" required>
                <option value="COD">COD (Bayar di Tempat)</option>
                <option value="Transfer">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>
        </div>

        <h5>Ringkasan Produk</h5>
        <ul class="list-group mb-3">
            @foreach ($items as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item->product->title }}
                    <span>Rp {{ number_format($item->product->saleprice) }}</span>
                </li>
            @endforeach
        </ul>

        <button type="submit" class="btn btn-primary">Konfirmasi Pesanan</button>
    </form>
</div>
</x-layout>
