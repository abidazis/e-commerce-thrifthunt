<x-layout :$menu :$submenu :$categories :$appname>
    <div class="container py-5 mt-5">
        <h3 class="mb-4">Keranjang Saya</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($items->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-start">Produk</th>
                            <th scope="col" class="text-center">Harga Satuan</th>
                            <th scope="col" class="text-center">Kuantitas</th>
                            <th scope="col" class="text-center">Subtotal</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPrice = 0; @endphp
                        @foreach($items as $item)
                            @php
                                $productPrice = $item->product->saleprice > 0 && $item->product->saleprice < $item->product->price
                                                    ? $item->product->saleprice
                                                    : $item->product->price;
                                $subtotal = $productPrice * $item->quantity;
                                $totalPrice += $subtotal;
                            @endphp
                            <tr id="cart-item-{{ $item->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ url('storage/'.$item->product->image) }}" alt="{{ $item->product->title }}" class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}" alt="No Image" class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->title ?? 'Produk Tidak Ditemukan' }}</h6>
                                            <small class="text-muted">{{ $item->product->category->title ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">Rp {{ number_format($productPrice, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm quantity-input-group justify-content-center">
                                        <button class="btn btn-outline-secondary btn-decrease-quantity" type="button" data-item-id="{{ $item->id }}" data-product-id="{{ $item->product_id }}">-</button>
                                        <input type="number" class="form-control text-center quantity-input" value="{{ $item->quantity }}" min="1" readonly style="width: 60px;" id="qty-{{ $item->id }}">
                                        <button class="btn btn-outline-secondary btn-increase-quantity" type="button" data-item-id="{{ $item->id }}" data-product-id="{{ $item->product_id }}">+</button>
                                    </div>
                                </td>
                                <td class="text-center text-primary fw-bold" id="subtotal-{{ $item->id }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form id="remove-form-{{ $item->id }}" class="d-inline"> {{-- Hapus method="POST" dan action="{{ route('cart.remove', $item->id) }}" dari sini karena akan ditangani AJAX --}}
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-cart-item" data-item-id="{{ $item->id }}"> {{-- Ubah type="submit" menjadi type="button" --}}
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-5">Total Keranjang:</td>
                            <td class="text-center fw-bold fs-5 text-success" id="grand-total">Rp {{ number_format($totalPrice, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 animated-button">
                    Lanjut ke Checkout <i class="bi bi-arrow-right-circle ms-2"></i>
                </a>
            </div>
        @else
            <div class="alert alert-info text-center py-4">
                <p class="mb-0">Keranjang belanja Anda masih kosong.</p>
                <a href="/" class="btn btn-primary mt-3">Mulai Belanja</a>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log("jQuery is ready. Script started."); // Debugging: Pastikan script dijalankan
            function updateCartItem(itemId, productId, newQuantity) {
                console.log("updateCartItem called for itemId:", itemId, "newQuantity:", newQuantity); // Debugging: Pastikan fungsi terpanggil
                // Batasi kuantitas minimal 1, jika 0 atau kurang, itu artinya hapus
                if (newQuantity < 0) newQuantity = 0; // Ubah ke 0 untuk menandakan hapus jika tombol '-' ditekan saat qty 1

                $.ajax({
                    url: '{{ route("cart.update") }}', // Gunakan route() helper
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        item_id: itemId,
                        product_id: productId,
                        quantity: newQuantity
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.item_removed) { // Cek flag item_removed dari controller
                                $('#cart-item-' + itemId).remove(); // Hapus baris item dari DOM
                                if ($('.table tbody tr').length === 0) {
                                    // Jika tidak ada item tersisa, refresh halaman atau tampilkan pesan keranjang kosong
                                    location.reload();
                                }
                            } else {
                                $('#subtotal-' + itemId).text('Rp ' + response.subtotal);
                            }
                            $('#grand-total').text('Rp ' + response.grand_total);
                            $('.navbar .badge').text(response.cart_count); // Update badge di navbar

                            // Tampilkan pesan sukses sementara (opsional)
                            // $('.container').prepend('<div class="alert alert-success mt-3 animated-fade-out">' + response.message + '</div>');
                            // setTimeout(function() { $('.animated-fade-out').fadeOut('slow', function() { $(this).remove(); }); }, 3000);

                        } else {
                            alert('Gagal memperbarui keranjang: ' + response.message);
                            // Kembalikan kuantitas di UI jika gagal (opsional, perlu simpan currentQty sebelum AJAX)
                        }
                    },
                    error: function(xhr) {
                        console.error("Error updating cart:", xhr.responseText);
                        alert('Terjadi kesalahan saat memperbarui keranjang. Silakan coba lagi.');
                        // Kembalikan nilai input jika error
                        location.reload(); // Refresh halaman jika ada error serius
                    }
                });
            }

            // Tombol kurangi kuantitas
            $('.btn-decrease-quantity').on('click', function() {
                console.log("Decrease button clicked."); // Debugging: Pastikan click event terpanggil
                let itemId = $(this).data('item-id');
                let productId = $(this).data('product-id');
                let currentQty = parseInt($('#qty-' + itemId).val());
                let newQty = currentQty - 1;

                $('#qty-' + itemId).val(newQty); // Update input field (sementara)
                updateCartItem(itemId, productId, newQty);
            });

            // Tombol tambah kuantitas
            $('.btn-increase-quantity').on('click', function() {
                let itemId = $(this).data('item-id');
                let productId = $(this).data('product-id');
                let currentQty = parseInt($('#qty-' + itemId).val());
                let newQty = currentQty + 1;

                $('#qty-' + itemId).val(newQty); // Update input field (sementara)
                updateCartItem(itemId, productId, newQty);
            });

            // Tombol Hapus (sekarang menggunakan AJAX)
            $('.delete-cart-item').on('click', function(e) {
                e.preventDefault(); // Mencegah form submit biasa
                let itemId = $(this).data('item-id');
                let form = $(this).closest('form'); // Dapatkan formnya

                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                    $.ajax({
                        url: form.attr('action'), // Ambil URL dari form
                        method: 'POST', // Form method adalah POST
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE' // Spoofing DELETE method
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#cart-item-' + itemId).remove(); // Hapus baris item dari DOM
                                $('#grand-total').text('Rp ' + response.grand_total);
                                $('.navbar .badge').text(response.cart_count);

                                if ($('.table tbody tr').length === 0) {
                                    location.reload(); // Refresh jika keranjang kosong
                                }
                            } else {
                                alert('Gagal menghapus produk: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error("Error deleting cart item:", xhr.responseText);
                            alert('Terjadi kesalahan saat menghapus produk.');
                            location.reload(); // Refresh halaman jika ada error
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-layout>