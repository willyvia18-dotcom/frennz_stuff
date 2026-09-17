# Migrasi Frennz.Stuff (HTML statis → Laravel Blade)

## Cara pasang
1. Buat/pastikan sudah ada project Laravel kosong (`laravel new frennz-stuff` atau `composer create-project laravel/laravel frennz-stuff`).
2. Salin isi folder `resources/views/` di sini ke `resources/views/` project Laravel kamu (timpa kalau ada bentrok).
3. Salin isi folder `public/css/` dan `public/js/` di sini ke `public/css/` dan `public/js/` project Laravel kamu.
4. Salin isi `routes/web.php` di sini ke `routes/web.php` project Laravel kamu (atau gabungkan kalau sudah ada isi lain).
5. Jalankan `php artisan serve` lalu buka `/`.

## Apa yang berubah dari versi HTML asli
Tampilan **tidak diubah sama sekali** — semua HTML, class CSS, dan logic JavaScript disalin apa adanya. Yang berubah hanya cara file-file itu disusun & dihubungkan supaya cocok dengan Laravel:

- Setiap halaman `.html` dipecah jadi Blade view yang `@extends('layouts.app')`, dengan `<head>`/aset umum ditaruh di `resources/views/layouts/app.blade.php`.
- Semua link antar halaman (`index.html`, `catalog.html`, `product.html?id=`, dst.) diganti jadi `{{ route(...) }}` sesuai daftar di `routes/web.php`.
- Semua `<link>`/`<script src>` ke `css/style.css`, `js/data.js`, `js/store.js` diganti jadi `{{ asset(...) }}`.
- `css/style.css`, `js/data.js`, `js/store.js` dipindah apa adanya ke `public/css` dan `public/js` — isinya tidak diubah sedikit pun, jadi seluruh logic (cart, wishlist, voucher, checkout, dsb via localStorage) tetap jalan persis seperti sebelumnya.
- `login.html` (yang tadinya satu halaman dengan toggle JS antara form login/daftar) dipecah jadi dua halaman terpisah — `auth/login.blade.php` dan `auth/register.blade.php` — sesuai struktur folder yang diminta. Link "Daftar" / "Masuk" di masing-masing form sekarang berpindah halaman (bukan toggle di tempat), tapi tampilan formnya identik.
- Panel Admin (`admin/*.html`) **belum** dimasukkan karena tidak ada di struktur folder yang diminta. Link "Panel Admin" di footer sengaja diarahkan ke `#` — kalau nanti mau dimigrasikan juga, tinggal bilang.
- `resources/views/components/product-card.blade.php` dibuat sesuai struktur yang diminta, meniru persis markup kartu produk dari JS. Saat ini belum dipakai di halaman manapun karena semua kartu produk masih di-render lewat JavaScript (data dari localStorage) — komponen ini siap dipakai nanti begitu produk sudah diambil dari database via Eloquent.

## Struktur file
```
resources/views/
├── layouts/app.blade.php
├── components/product-card.blade.php
├── home/index.blade.php          (dari index.html)
├── products/index.blade.php      (dari catalog.html)
├── products/show.blade.php       (dari product.html)
├── cart/index.blade.php          (dari cart.html)
├── checkout/index.blade.php      (dari checkout.html)
├── auth/login.blade.php          (dari login.html)
├── auth/register.blade.php       (dari login.html)
├── wishlist/index.blade.php      (dari wishlist.html)
└── order-success.blade.php       (dari order-success.html)

public/css/style.css   (disalin apa adanya)
public/js/data.js      (disalin apa adanya)
public/js/store.js     (disalin apa adanya)

routes/web.php
```
