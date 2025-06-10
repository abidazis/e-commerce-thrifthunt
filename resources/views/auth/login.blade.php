@extends('layouts.app')

@section('content')
<div class="container-fluid login-page-container"> {{-- Menggunakan container-fluid dan kelas kustom --}}
    <div class="row min-vh-100 align-items-center justify-content-center"> {{-- min-vh-100 untuk tinggi penuh, align-items-center untuk tengah vertikal --}}
        {{-- Kolom Kiri: Ilustrasi/Gambar (Seperti area "Belanja di Shopee") --}}
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center login-promo-section p-5">
            <img src="{{ asset('images/thrift-illustration.png') }}" alt="ThriftHunt Illustration" class="img-fluid mb-4" style="max-width: 400px;"> {{-- Ganti dengan path gambar ilustrasi kamu --}}
            <h1 class="text-white text-center display-5 fw-bold mb-3">Selamat Datang di ThriftHunt!</h1>
            <p class="lead text-white-50 text-center">Temukan harta karun fashion bekas berkualitas dengan harga terbaik.</p>
        </div>

        {{-- Kolom Kanan: Form Login --}}
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="card login-card shadow-lg border-0 rounded-4"> {{-- Kelas kustom untuk card login --}}
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4 fw-bold">{{ __('Login') }}</h3>
                    <p class="text-center text-muted mb-4">Masuk untuk melanjutkan belanja Anda.</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label visually-hidden">{{ __('Email Address') }}</label> {{-- Sembunyikan label, gunakan placeholder --}}
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror rounded-pill px-4" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email Address') }}">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label visually-hidden">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror rounded-pill px-4" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-primary text-decoration-none fw-bold" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>

                        <div class="d-grid gap-2 mb-3"> {{-- Menggunakan d-grid untuk tombol full width --}}
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill animated-button">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <p class="text-center text-muted mt-4">Belum punya akun?
                            <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold">Daftar Sekarang</a>
                        </p>

                        {{-- Divider atau Opsi Login Lain (Opsional, seperti "ATAU" di Shopee) --}}
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1 mx-3">
                            <span class="text-muted">ATAU</span>
                            <hr class="flex-grow-1 mx-3">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill animated-button-social">
                                <i class="bi bi-google me-2"></i> Login dengan Google
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill animated-button-social">
                                <i class="bi bi-facebook me-2"></i> Login dengan Facebook
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection