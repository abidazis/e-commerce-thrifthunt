@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="display-6 fw-bold text-dark">Detail Pesanan #{{ $order->order_number ?? $order->id }}</h2>
            <p class="lead text-muted">Informasi lengkap pesanan Anda.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded-3 mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    Informasi Pesanan
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>No. Pesanan:</strong></div>
                        <div class="col-md-8">#{{ $order->order_number ?? $order->id }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Tanggal Pesan:</strong></div>
                        <div class="col-md-8">{{ $order->created_at->format('d F Y H:i') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Status:</strong></div>
                        <div class="col-md-8">
                            @php
                                $statusClass = '';
                                switch($order->status) {
                                    case 'pending': $statusClass = 'text-warning'; break;
                                    case 'diproses': $statusClass = 'text-info'; break;
                                    case 'dikirim': $statusClass = 'text-primary'; break;
                                    case 'selesai': $statusClass = 'text-success'; break;
                                    default: $statusClass = 'text-muted'; break;
                                }
                            @endphp
                            <span class="badge bg-light {{ $statusClass }} p-2">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Pembeli:</strong></div>
                        <div class="col-md-8">{{ $order->user->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm rounded-3 mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    Detail Produk
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ url('storage/'.$item->product->image) }}" alt="{{ $item->product->title }}" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}" alt="No Image" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <span>{{ $item->product->title ?? 'Produk Tidak Ditemukan' }}</span>
                                    </div>
                                </td>
                                <td>Rp. {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp. {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada item dalam pesanan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Harga Barang:</td>
                                <td class="fw-bold">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm rounded-3 mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    Informasi Pengiriman & Pembayaran
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Alamat Pengiriman:</strong></div>
                        <div class="col-md-8">{{ $order->shipping_address ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Metode Pengiriman:</strong></div>
                        <div class="col-md-8">{{ $order->shipping_method ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Metode Pembayaran:</strong></div>
                        <div class="col-md-8">{{ $order->payment_method ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('orders.customer_index') }}" class="btn btn-secondary me-2">Kembali ke Daftar Pesanan</a>
                <a href="{{ route('orders.invoice_pdf', $order->id) }}" class="btn btn-success">Download Invoice PDF</a>
            </div>
        </div>
    </div>
</div>
@endsection