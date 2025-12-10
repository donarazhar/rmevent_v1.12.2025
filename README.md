# 🕌 Ramadhan Mubarak 1447 H - Event Management System

> Sistem manajemen event dan konten untuk kegiatan Ramadhan berbasis Laravel 12.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 📋 Deskripsi

**Ramadhan Mubarak 1447 H** adalah aplikasi web yang dirancang khusus untuk mengelola berbagai kegiatan selama bulan Ramadhan. Aplikasi ini memudahkan panitia dalam mengelola event, pendaftaran jamaah, publikasi konten, hingga pengumpulan feedback dalam satu platform terintegrasi.

## ✨ Fitur Utama

Aplikasi ini dilengkapi dengan berbagai fitur untuk mendukung kegiatan Ramadhan:

### 🕋 Manajemen Event & Jamaah
- **Kelola Event**: Membuat dan mengelola berbagai jenis acara seperti kajian, buka puasa bersama, dan i'tikaf.
- **Registrasi Online**: Sistem pendaftaran online yang mudah digunakan oleh jamaah.
- **Manajemen Peserta**: Mengelola kuota, melihat daftar peserta, dan memantau status kehadiran.
- **Tiket Digital**: Pembuatan tiket dengan QR code untuk verifikasi peserta.

### 📝 Manajemen Konten
- **Publikasi Artikel**: Mempublikasikan artikel, jadwal, dan pengumuman penting.
- **Kategori & Tag**: Mengelompokkan konten berdasarkan kategori (misal: "Tips Ramadhan", "Kajian") dan tag untuk memudahkan pencarian.
- **Editor Konten**: Rich text editor untuk membuat konten yang informatif dan menarik.

### 💬 Interaksi & Feedback
- **Sistem Feedback**: Mengumpulkan masukan, saran, dan keluhan dari jamaah untuk perbaikan.
- **Notifikasi Email**: Pengiriman notifikasi otomatis untuk konfirmasi pendaftaran dan pengingat acara.
- **Halaman Statis**: Mengelola halaman informatif seperti "Tentang Kami", "FAQ", dan "Kontak".

## 🚀 Teknologi

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Blade, Vite, Tailwind CSS (diasumsikan)
- **Database**: MySQL (atau database lain yang didukung Laravel)

## ⚙️ Instalasi & Setup

1.  **Clone Repositori**
    ```bash
    git clone https://github.com/donarazhar/rmevent_v1.12.2025.git
    cd rmevent_v1.12.2025
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    - Salin file `.env.example` menjadi `.env`.
    - Konfigurasi koneksi database Anda.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Migrasi & Seeding Database**
    ```bash
    php artisan migrate --seed
    ```

5.  **Jalankan Aplikasi**
    ```bash
    npm run dev
    php artisan serve
    ```

** Developer: Donar Azhar
📞 Contact & Support
-- Email: donarazhar@gmail.com

** All contributors and supporters
** Ramadhan Mubarak 1447 H - Built with ❤️ for the Ummah
