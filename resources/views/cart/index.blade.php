<x-layout :$menu :$submenu :$categories :$appname>
    <div class="container py-5">
        <h3>Keranjang Saya</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($items->count())
            <form action="{{ route('checkout.index') }}" method="GET">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product->title }}</td>
                                <td>Rp {{ number_format($item->product->saleprice) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Lanjut ke Checkout</button>
            </form>
        @else
            <p>Keranjang masih kosong.</p>
        @endif
    </div>
</x-layout>
