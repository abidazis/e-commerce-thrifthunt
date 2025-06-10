@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="display-6 fw-bold">Hasil Pencarian untuk "<span class="text-primary">{{ $query }}</span>"</h2>
            <p class="lead text-muted">{{ $products->total() }} produk ditemukan.</p>
            {{-- Bisa tambahkan filter kategori lagi di sini jika mau --}}
            {{-- <x-category :categories="$categories" /> --}}
        </div>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            Maaf, tidak ada produk yang ditemukan dengan kata kunci "{{ $query }}".
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 product-card">
                        <img src="{{ url('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate">{{ $product->title }}</h5> {{-- text-truncate untuk judul panjang --}}
                            <p class="card-text text-muted mb-2"><small>{{ $product->category->title }}</small></p>
                            <p class="card-text description-preview">{{ Str::limit($product->description, 70) }}</p> {{-- Batasi deskripsi --}}
                            <div class="mt-auto">
                                <p class="card-text fs-5 fw-bold text-primary">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                                @if($product->saleprice > 0 && $product->saleprice < $product->price)
                                    <p class="card-text text-danger text-decoration-line-through">Rp. {{ number_format($product->saleprice, 0, ',', '.') }}</p>
                                @endif
                                <a href="/product/{{ $product->id }}" class="btn btn-outline-primary w-100 mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(['query' => request('query')])->links() }}
        </div>
    @endif
</div>

{{-- Tambahkan sedikit CSS untuk card produk jika belum ada di app.blade.php --}}
<style>
    .product-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.05);
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,.1);
    }
    .product-card .card-img-top {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        height: 200px; /* Tinggi gambar produk */
        object-fit: cover;
    }
    .product-card .card-body {
        padding: 1.25rem;
    }
    .product-card .card-title {
        font-weight: 600;
        color: #212529;
        white-space: nowrap; /* Mencegah judul pindah baris */
        overflow: hidden; /* Sembunyikan jika melebihi lebar */
        text-overflow: ellipsis; /* Tambahkan elipsis jika terpotong */
    }
    .product-card .description-preview {
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.5;
        height: 4.5em; /* Ketinggian tetap untuk 3 baris teks */
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection