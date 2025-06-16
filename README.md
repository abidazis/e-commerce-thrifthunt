# ThriftHunt E-commerce Website

Aplikasi toko online berbasis web yang dibuat dengan Laravel, dengan peran penjual dan pembeli, serta fitur keranjang, checkout, dan pelacakan pesanan.

## Daftar Isi
- [Tentang Proyek](#tentang-proyek)
- [Fitur](#fitur)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Langkah-langkah Instalasi Lokal](#langkah-langkah-instalasi-lokal)
- [Akses Admin Panel](#akses-admin-panel)
- [Deployment (Untuk Tim Pengembang/DevOps)](#deployment-untuk-tim-pengembangdevops)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

## Tentang Proyek
Proyek ini adalah implementasi toko online dengan fokus pada penjualan barang 'thrift' (bekas). Aplikasi ini menyediakan pengalaman berbelanja yang intuitif bagi pembeli dan sistem manajemen pesanan yang efisien bagi penjual/admin.

## Fitur
### Untuk Pembeli:
- Halaman utama (homepage) dengan carousel dan daftar produk.
- Fungsi pencarian produk.
- Halaman detail produk.
- Keranjang belanja interaktif (tambah/kurang kuantitas, hapus item).
- Proses checkout dengan informasi pengiriman dan pembayaran (integrasi Midtrans **(masih dalam pengembangan/belum diaktifkan)**).
- Riwayat pesanan dengan status pelacakan.
- Download invoice pesanan.

### Untuk Penjual/Admin:
- Dashboard admin dengan statistik (total kategori, produk, pesanan) dan grafik pesanan bulanan.
- Manajemen kategori produk.
- Manajemen produk (tambah, edit, hapus).
- Manajemen slider di homepage.
- Manajemen pesanan dengan kemampuan update status langsung dari tabel.
- Export packing slip untuk pengiriman.

## Persyaratan Sistem
Pastikan sistem Anda memenuhi persyaratan berikut:
- PHP >= 8.2 (Direkomendasikan PHP 8.2.12+)
- Composer
- Node.js & npm (untuk aset frontend, jika diperlukan)
- MySQL Database
- Web Server (Apache/Nginx, atau gunakan PHP built-in server `php artisan serve`)

## Langkah-langkah Instalasi Lokal

Ikuti langkah-langkah ini untuk menjalankan proyek secara lokal:

1.  **Clone Repositori:**
    ```bash
    git clone [https://github.com/USERNAME/thrifthunt-ecommerce.git](https://github.com/USERNAME/thrifthunt-ecommerce.git) # Ganti USERNAME
    cd thrifthunt-ecommerce
    ```

2.  **Instal Dependensi Composer:**
    ```bash
    composer install --optimize-autoloader --no-dev
    ```

3.  **Konfigurasi Environment:**
    - Buat salinan file `.env.example` menjadi `.env`:
        ```bash
        cp .env.example .env
        # Atau di Windows: copy .env.example .env
        ```
    - Edit file `.env` dan atur konfigurasi database Anda:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=ecommerce-thrifthunt # Sesuaikan dengan nama database Anda
        DB_USERNAME=root # Sesuaikan username database Anda
        DB_PASSWORD= # Sesuaikan password database Anda (kosong jika tidak ada)
        ```
    - Generate kunci aplikasi:
        ```bash
        php artisan key:generate
        ```

4.  **Migrasi Database dan Seed Data (Opsional):**
    - Buat database `ecommerce-thrifthunt` di MySQL (via phpMyAdmin atau client lain).
    - Jalankan migrasi database:
        ```bash
        php artisan migrate
        ```
    - (Opsional) Jika Anda memiliki seeder untuk data dummy, jalankan:
        ```bash
        php artisan db:seed
        ```
    - Atau import `ecommerce-thrifthunt (6).sql` yang disediakan.

5.  **Instalasi Aset Frontend (jika diperlukan):**
    Jika ada aset frontend (CSS/JS kustom, bukan hanya Bootstrap CDN), Anda mungkin perlu:
    ```bash
    npm install
    npm run dev # Atau npm run build untuk produksi
    ```

6.  **Buat Akun Admin Filament (Jika Belum Ada):**
    ```bash
    php artisan make:filament-user
    ```
    Ikuti instruksi di terminal untuk membuat akun admin pertama.

7.  **Jalankan Server:**
    ```bash
    php artisan serve
    ```
    Aplikasi akan berjalan di `http://127.0.0.1:8000`.

## Akses Admin Panel
- URL: `http://127.0.0.1:8000/admin`
- Login dengan akun yang dibuat melalui `php artisan make:filament-user` atau akun `admin@gmail.com` yang ada di database dump.

## Deployment (Untuk Tim Pengembang/DevOps)
*(Bagian ini akan diisi oleh teman Anda yang akan melakukan deployment)*
Proyek ini dapat di-deploy ke berbagai platform hosting (Shared Hosting, VPS, PaaS). Perhatikan konfigurasi `APP_ENV`, `APP_DEBUG`, `APP_URL`, dan detail database di file `.env` server. Pastikan PHP versi 8.2+ tersedia.

## Kontribusi
Silakan buka `issue` atau `pull request` jika Anda menemukan bug atau ingin menambahkan fitur.

## Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE.md) (Jika ada file LICENSE.md di root proyek Anda).
