<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        {{-- Loop untuk indicators --}}
        @foreach($sliders as $key => $slider)
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        <?php
            $i = 0;
            foreach ($sliders as $slider) {
        ?>
            <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>">
                <img src="{{ url('storage/'.$slider->image) }}" class="d-block w-100 carousel-img" alt="{{ $slider->title }}">
                {{-- Hanya satu carousel-caption, kita atur responsivitas di dalamnya --}}
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                    <div class="carousel-content-wrapper"> {{-- Ini akan jadi kotak blur --}}
                        <h1 class="display-3 fw-bold text-white mb-3 text-center"> {{-- Ubah warna teks ke putih, ukuran, dan tebal --}}
                            {{ $slider->title }}
                        </h1>
                        <p class="lead text-white-50 text-center mb-4 d-none d-md-block"> {{-- Ubah warna teks subtitle, tambahkan d-md-block --}}
                            {{ $slider->subtitle }}
                        </p>
                        <div class="mt-3"> {{-- Tambahkan margin-top untuk tombol --}}
                            <a href="#" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm animated-button">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php $i++;} ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>