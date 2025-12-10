# 🕌 Ramadhan Mubarak 1447 H - Event Management System

> Sistem manajemen event dan konten untuk kegiatan Ramadhan berbasis Laravel 12

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 📋 Deskripsi

**Ramadhan Mubarak 1447 H** adalah aplikasi web manajemen event dan konten yang dirancang khusus untuk mengelola berbagai kegiatan ibadah selama bulan Ramadhan. Aplikasi ini memungkinkan panitia untuk mengelola event, registrasi peserta, publikasi artikel, dan berbagai fitur lainnya dalam satu platform terintegrasi.

### ✨ Fitur Utama

#### 🎯 **Event Management**
- ✅ Kelola berbagai jenis event (Kajian, Tarawih, Buka Puasa, dll)
- ✅ Sistem registrasi online dengan form dinamis
- ✅ Manajemen kuota peserta dan status event
- ✅ Tiket digital dengan QR Code
- ✅ Notifikasi email otomatis

#### 📝 **Content Management**
- ✅ Publikasi artikel dan berita Ramadhan
- ✅ Sistem kategori dan tag
- ✅ Editor rich text untuk konten
- ✅ Featured posts dan sticky content

#### 👥 **Registration System**
- ✅ Pendaftaran peserta (user & guest)
- ✅ Pembayaran online (paid events)
- ✅ Upload bukti transfer
- ✅ Status tracking (pending, confirmed, attended)
- ✅ Download tiket PDF dengan QR Code

#### 🎨 **User Interface**
- ✅ Responsive design (mobile-first)
- ✅ Modern UI dengan Tailwind CSS
- ✅ Interactive components dengan Alpine.js
- ✅ Search & filter functionality

## 🛠️ Tech Stack

- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: MySQL 8.0+
- **Frontend**: 
  - Tailwind CSS 3.x
  - Alpine.js
  - Blade Templates
- **Package Manager**: Composer, NPM

## 📦 Instalasi

### Prerequisites

Pastikan sistem Anda sudah terinstall:
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 8.0+
- Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**
```bash
   git clone <repository-url>
   cd ramadhan-mubarak-1447h
```

2. **Install Dependencies**
```bash
   composer install
   npm install
```

3. **Environment Setup**
```bash
   cp .env.example .env
   php artisan key:generate
```

4. **Database Configuration**
   
   Edit `.env` file:
```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ramadhan_db
   DB_USERNAME=root
   DB_PASSWORD=
```

5. **Run Migration & Seeder**
```bash
   php artisan migrate:fresh --seed
```

6. **Storage Link**
```bash
   php artisan storage:link
```

7. **Build Assets**
```bash
   npm run build
```

8. **Run Development Server**
```bash
   php artisan serve
```

   Buka browser: `http://localhost:8000`

## 📁 Struktur Database

### Tables
```
├── users                    # User accounts
├── categories              # Event & post categories
├── posts                   # Articles & news
├── events                  # Events data
├── event_registrations     # Event registrations
├── tags                    # Tags for posts & events
├── taggables              # Polymorphic pivot
├── feedbacks              # User feedbacks
└── media                  # Media attachments
```

## 🎨 Fitur Detail

### 1. **Event Categories**
- Kajian & Ceramah
- Tadarus Al-Quran
- Shalat Tarawih
- Buka Puasa Bersama
- Kegiatan Sosial
- Qiyamul Lail
- Pelatihan & Workshop
- Kegiatan Anak

### 2. **Post Categories**
- Berita
- Artikel Islami
- Tips & Panduan
- Kisah Inspiratif
- Pengumuman

### 3. **Registration Features**
- Personal data collection
- Custom fields per event
- Payment proof upload
- Email confirmation
- QR Code ticket generation
- Registration status tracking

### 4. **User Roles** (Optional)
- Admin: Full access
- Panitia: Event management
- User: Registration & viewing

## 🔐 Default Credentials

Setelah seeding, gunakan credentials berikut:

**Admin Account:**
```
Email: admin@ramadhan.id
Password: password
```

## 📝 Seeders

Aplikasi dilengkapi dengan data dummy untuk testing:
```bash
php artisan db:seed --class=CategorySeeder    # Categories
php artisan db:seed --class=PostSeeder        # 15 articles
php artisan db:seed --class=EventSeeder       # 15 events
php artisan db:seed --class=EventRegistrationSeeder  # Sample registrations
```

## 🚀 Deployment

### Production Setup

1. **Set Environment to Production**
```env
   APP_ENV=production
   APP_DEBUG=false
```

2. **Optimize Application**
```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
```

3. **Set Permissions**
```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
```

## 🧪 Testing
```bash
# Run tests
php artisan test

# With coverage
php artisan test --coverage
```

## 📖 API Documentation

*(Coming soon)*

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Team

- **Developer**: Donar Azhar

## 📞 Contact & Support

- **Email**: donarazhar@gmail.com
- **Issues**: (https://github.com/donarazhar)

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- All contributors and supporters

---

**Ramadhan Mubarak 1447 H** - Built with ❤️ for the Ummah