@extends('layouts.app')

@section('content')
    <x-carousel :sliders="$sliders" />

    <section class="my-5 py-3" id="our-products-section"> {{-- Tambahkan id di sini --}}
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="display-5 fw-bold text-dark">Produk Kami</h2>
                    <p class="lead text-muted">Jelajahi koleksi terbaru kami dengan gaya yang unik dan harga terjangkau.</p>
                    <x-category :categories="$categories" />
                </div>
            </div>
            <x-products :$products />
        </div>
    </section>

    <section class="bg-light py-5">
        <div class="container text-center">
            <h3 class="fw-bold mb-4">Mengapa Memilih ThriftHunt?</h3>
            <div class="row">
                <div class="col-md-4">
                    <i class="bi bi-truck display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Pengiriman Cepat & Aman</h5>
                    <p class="text-muted">Kami memastikan barang sampai tujuan dengan cepat dan dalam kondisi terbaik.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-wallet-fill display-4 text-success mb-3"></i>
                    <h5 class="fw-bold">Harga Terbaik</h5>
                    <p class="text-muted">Dapatkan produk berkualitas dengan harga yang sangat kompetitif.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-shield-lock-fill display-4 text-info mb-3"></i>
                    <h5 class="fw-bold">Transaksi Aman</h5>
                    <p class="text-muted">Sistem pembayaran yang aman dan terpercaya untuk ketenangan pikiran Anda.</p>
                </div>
            </div>
        </div>
    </section>
@endsection