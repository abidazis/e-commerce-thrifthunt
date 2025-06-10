<nav class="navbar fixed-top shadow navbar-expand-lg navbar-light bg-white rounded-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">{{ config('app.name', 'ThriftHunt') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- Form Pencarian Produk --}}
            <form class="d-flex my-2 my-lg-0 mx-auto w-50" action="{{ route('products.search') }}" method="GET"> {{-- Tambahkan w-50 untuk lebar 50% --}}
                <div class="input-group">
                    <input class="form-control border-end-0 border rounded-pill" type="search" placeholder="Cari produk..." aria-label="Search" name="query" value="{{ request('query') }}">
                    <span class="input-group-append">
                        <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </span>
                </div>
            </form>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item">
                    {{-- Arahkan ke id carousel --}}
                    <a class="nav-link" aria-current="page" href="/#carouselExampleCaptions">Home</a>
                </li>

                {{-- Menu Pages sebelumnya, sekarang jadi Produk --}}
                <li class="nav-item">
                    {{-- Arahkan ke id bagian produk --}}
                    <a class="nav-link" href="/#our-products-section">Produk</a>
                </li>

                {{-- Hapus loop @foreach($menu as $mn) dan dropdown pages yang lama jika tidak lagi diperlukan --}}
                {{-- Jika kamu masih ingin menampilkan menu dari database, kamu perlu menyesuaikan logikanya --}}
                {{-- Misalnya, memindahkan "Pages" ke bagian "Produk" jika itu maksudnya, atau menambahkannya lagi secara terpisah --}}

                @auth
                <li class="nav-item me-3">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart-fill fs-5"></i>
                        {{-- <span class="badge bg-danger rounded-pill">3</span> --}}
                    </a>
                </li>
                @endauth

                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }} ({{ auth()->user()->role }})
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item ms-lg-3">
                    <a class="nav-link btn btn-primary rounded-pill px-4" href="{{ route('login') }}">Login</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>