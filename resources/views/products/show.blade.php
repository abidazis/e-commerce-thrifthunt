@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg rounded-4">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="{{ url('storage/'.$product->image) }}" class="img-fluid rounded-start-4" alt="{{ $product->title }}" style="object-fit: cover; height: 100%; min-height: 350px;">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body p-4">
                            <h1 class="card-title display-6 fw-bold mb-3">{{ $product->title }}</h1>
                            <p class="text-muted mb-2"><span class="badge bg-secondary">{{ $product->category->title ?? 'Tanpa Kategori' }}</span></p>
                            <p class="fs-4 fw-bold text-primary mb-3">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                            @if($product->saleprice > 0 && $product->saleprice < $product->price)
                                <p class="text-danger text-decoration-line-through fs-5">Rp. {{ number_format($product->saleprice, 0, ',', '.') }}</p>
                            @endif
                            <hr>
                            <h5 class="fw-bold">Deskripsi Produk:</h5>
                            <div class="mb-4">{!! $product->description !!}</div>

                            {{-- Form Tambah ke Keranjang --}}
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                {{-- Tambahkan input kuantitas di sini --}}
                                <div class="mb-3 d-flex align-items-center">
                                    <label for="quantity" class="form-label me-3 mb-0">Jumlah:</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" style="width: 80px;">
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill animated-button">
                                    <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                                </button>
                            </form>
                            {{-- Jika ada tombol Beli Sekarang langsung --}}
                            {{-- <form action="{{ route('orders.store') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill">Beli Sekarang</button>
                            </form> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection