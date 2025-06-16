@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="display-5 fw-bold text-dark">Pesanan Saya</h2>
            <p class="lead text-muted">Lihat status dan riwayat pesanan Anda di sini.</p>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            Anda belum memiliki pesanan apapun. Mulai belanja sekarang!
            <br><a href="/" class="btn btn-primary mt-3">Belanja Sekarang</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">No. Pesanan</th>
                        <th scope="col">Total Barang</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Pesan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <th scope="row">{{ $order->order_number ?? $order->id }}</th>
                        <td>{{ $order->items->sum('quantity') }} item</td>
                        <td>Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
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
                        </td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('orders.customer_show', $order->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection