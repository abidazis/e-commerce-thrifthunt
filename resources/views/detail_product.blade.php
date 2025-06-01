<x-layout :$menu :$submenu :$categories :$appname>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3 mx-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <div class="container mt-lg-5 py-5">
        <div class="row pt-lg-5">
            <!-- Product Images -->
            <div class="col-md-6 mb-4">
                <img src="{{ url('storage/'.$product->image) }}" alt="Product" class="img-fluid rounded mb-3 product-image border" id="mainImage">
            </div>
    
            <!-- Product Details -->
            <div class="col-md-6">
                <h2 class="mb-3">{{ $product->title }}</h2>
                <p class="text-muted mb-4">Category: {{ $product->category->title }}</p>
                <div class="mb-3">
                    <span class="h4 me-2">Rp {{ number_format($product->saleprice,0,',','.') }}</span>
                    <span class="text-muted"><s>Rp {{ number_format($product->price,0,',','.') }}</s></span>
                </div>
                <button class="btn btn-success btn-lg mb-3 me-2">
                        <i class="bi bi-telephone"></i> Order Via WhatsApp
                </button>

                @auth
                    @if (auth()->user()->role === 'pembeli')
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button class="btn btn-success mt-3" type="submit">+ Tambah ke Keranjang</button>
                        </form>
                    @endif
                @else
                    <a href="{{ url('/login') }}" class="btn btn-primary mt-3">Login untuk memesan</a>
                @endauth


                <div class="p-4 border"><?= $product->description ?></div>
            </div>
        </div>
    </div>

</x-layout>