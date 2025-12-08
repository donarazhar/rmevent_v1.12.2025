<?php
// database/seeders/PageSeeder.php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Homepage
            [
                'title' => 'Beranda',
                'slug' => 'beranda',
                'content' => $this->getHomepageContent(),
                'template' => 'landing',
                'sections' => json_encode($this->getHomepageSections()),
                'meta_title' => 'Ramadhan Mubarak 1447 H - Beranda',
                'meta_description' => 'Selamat datang di portal digital Ramadhan Mubarak 1447 H. Temukan berbagai kegiatan spiritual dan informasi lengkap seputar event Ramadhan.',
                'meta_keywords' => 'ramadhan 1447, kegiatan ramadhan, portal ramadhan',
                'parent_id' => null,
                'order' => 1,
                'status' => 'published',
                'show_in_menu' => true,
                'is_homepage' => true,
            ],

            // Tentang Kami
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'content' => $this->getAboutContent(),
                'template' => 'default',
                'sections' => null,
                'meta_title' => 'Tentang Kami - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Mengenal lebih dekat tentang program Ramadhan Mubarak 1447 H, visi, misi, dan tim panitia kami.',
                'meta_keywords' => 'tentang ramadhan mubarak, visi misi, panitia',
                'parent_id' => null,
                'order' => 2,
                'status' => 'published',
                'show_in_menu' => true,
                'is_homepage' => false,
            ],

            // Program & Kegiatan
            [
                'title' => 'Program & Kegiatan',
                'slug' => 'program-kegiatan',
                'content' => $this->getProgramContent(),
                'template' => 'default',
                'sections' => json_encode($this->getProgramSections()),
                'meta_title' => 'Program & Kegiatan - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Jelajahi berbagai program dan kegiatan Ramadhan yang kami sediakan: kajian, tarawih, tadarus, dan kegiatan sosial.',
                'meta_keywords' => 'program ramadhan, kegiatan ramadhan, kajian islam',
                'parent_id' => null,
                'order' => 3,
                'status' => 'published',
                'show_in_menu' => true,
                'is_homepage' => false,
            ],

            // Cara Daftar
            [
                'title' => 'Cara Pendaftaran',
                'slug' => 'cara-pendaftaran',
                'content' => $this->getRegistrationGuideContent(),
                'template' => 'default',
                'sections' => null,
                'meta_title' => 'Cara Pendaftaran - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Panduan lengkap cara mendaftar dan berpartisipasi dalam kegiatan Ramadhan Mubarak 1447 H.',
                'meta_keywords' => 'cara daftar, panduan pendaftaran, registrasi event',
                'parent_id' => null,
                'order' => 4,
                'status' => 'published',
                'show_in_menu' => true,
                'is_homepage' => false,
            ],

            // Kontak
            [
                'title' => 'Hubungi Kami',
                'slug' => 'hubungi-kami',
                'content' => $this->getContactContent(),
                'template' => 'contact',
                'sections' => json_encode($this->getContactSections()),
                'meta_title' => 'Hubungi Kami - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Ada pertanyaan? Hubungi kami melalui berbagai channel komunikasi yang tersedia.',
                'meta_keywords' => 'kontak, hubungi kami, customer service',
                'parent_id' => null,
                'order' => 5,
                'status' => 'published',
                'show_in_menu' => true,
                'is_homepage' => false,
            ],

            // FAQ
            [
                'title' => 'FAQ (Pertanyaan Umum)',
                'slug' => 'faq',
                'content' => '<p>Temukan jawaban atas pertanyaan yang sering diajukan seputar kegiatan Ramadhan Mubarak 1447 H.</p>',
                'template' => 'faq',
                'sections' => null,
                'meta_title' => 'FAQ - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Pertanyaan yang sering diajukan seputar kegiatan dan pendaftaran Ramadhan Mubarak 1447 H.',
                'meta_keywords' => 'faq, pertanyaan umum, tanya jawab',
                'parent_id' => null,
                'order' => 6,
                'status' => 'published',
                'show_in_menu' => true,
                'is_homepage' => false,
            ],

            // Galeri
            [
                'title' => 'Galeri',
                'slug' => 'galeri',
                'content' => '<p>Dokumentasi visual dari berbagai kegiatan Ramadhan Mubarak tahun-tahun sebelumnya.</p>',
                'template' => 'gallery',
                'sections' => null,
                'meta_title' => 'Galeri - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Lihat dokumentasi foto dan video kegiatan Ramadhan Mubarak.',
                'meta_keywords' => 'galeri, foto kegiatan, dokumentasi',
                'parent_id' => null,
                'order' => 7,
                'status' => 'published',
                'show_in_menu' => true,
                'is_homepage' => false,
            ],

            // Privacy Policy
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => $this->getPrivacyPolicyContent(),
                'template' => 'default',
                'sections' => null,
                'meta_title' => 'Kebijakan Privasi - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Kebijakan privasi dan perlindungan data pribadi Ramadhan Mubarak 1447 H.',
                'meta_keywords' => 'privacy policy, kebijakan privasi, perlindungan data',
                'parent_id' => null,
                'order' => 8,
                'status' => 'published',
                'show_in_menu' => false,
                'is_homepage' => false,
            ],

            // Terms & Conditions
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-ketentuan',
                'content' => $this->getTermsContent(),
                'template' => 'default',
                'sections' => null,
                'meta_title' => 'Syarat & Ketentuan - Ramadhan Mubarak 1447 H',
                'meta_description' => 'Syarat dan ketentuan penggunaan platform Ramadhan Mubarak 1447 H.',
                'meta_keywords' => 'terms conditions, syarat ketentuan',
                'parent_id' => null,
                'order' => 9,
                'status' => 'published',
                'show_in_menu' => false,
                'is_homepage' => false,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }

        $this->command->info('✅ Pages seeded successfully! Total: ' . Page::count());
    }

    private function getHomepageContent(): string
    {
        return <<<HTML
<div class="hero-section">
    <h1>Ramadhan Mubarak 1447 H</h1>
    <p class="lead">Menyambut Bulan Penuh Berkah dengan Kebersamaan</p>
    <p>Mari bersama-sama memaksimalkan ibadah di bulan suci Ramadhan melalui berbagai kegiatan spiritual yang telah kami persiapkan.</p>
</div>
HTML;
    }

    private function getHomepageSections(): array
    {
        return [
            [
                'type' => 'hero',
                'title' => 'Ramadhan Mubarak 1447 H',
                'subtitle' => 'Menyambut Bulan Penuh Berkah dengan Kebersamaan',
                'background_image' => '/images/hero-ramadhan.jpg',
                'cta_text' => 'Lihat Kegiatan',
                'cta_url' => '/events',
            ],
            [
                'type' => 'features',
                'title' => 'Kegiatan Kami',
                'items' => [
                    [
                        'icon' => 'fas fa-mosque',
                        'title' => 'Kajian & Ceramah',
                        'description' => 'Kajian rutin dengan ustadz terbaik',
                    ],
                    [
                        'icon' => 'fas fa-praying-hands',
                        'title' => 'Shalat Tarawih',
                        'description' => 'Tarawih berjamaah setiap malam',
                    ],
                    [
                        'icon' => 'fas fa-quran',
                        'title' => 'Tadarus Al-Quran',
                        'description' => 'Membaca dan mengkaji Al-Quran bersama',
                    ],
                    [
                        'icon' => 'fas fa-utensils',
                        'title' => 'Buka Puasa Bersama',
                        'description' => 'Berbuka puasa dengan jamaah',
                    ],
                ],
            ],
            [
                'type' => 'stats',
                'items' => [
                    ['number' => '1000+', 'label' => 'Jamaah Aktif'],
                    ['number' => '50+', 'label' => 'Kegiatan'],
                    ['number' => '30', 'label' => 'Hari Penuh Berkah'],
                    ['number' => '20+', 'label' => 'Ustadz & Mentor'],
                ],
            ],
        ];
    }

    private function getAboutContent(): string
    {
        return <<<HTML
<h1>Tentang Ramadhan Mubarak 1447 H</h1>

<h2>Visi</h2>
<p>Menjadi program Ramadhan yang memberikan dampak positif dan berkelanjutan bagi umat Muslim dalam meningkatkan kualitas ibadah dan ukhuwah Islamiyah.</p>

<h2>Misi</h2>
<ul>
    <li>Menyelenggarakan kegiatan spiritual yang berkualitas dan mudah diakses oleh seluruh umat Muslim</li>
    <li>Membangun ekosistem digital yang transparan, akuntabel, dan efisien dalam pengelolaan kegiatan</li>
    <li>Memfasilitasi pertumbuhan spiritual dan sosial melalui program-program yang terstruktur</li>
    <li>Menciptakan dokumentasi dan knowledge base untuk continuous improvement</li>
</ul>

<h2>Sejarah</h2>
<p>Program Ramadhan Mubarak telah diselenggarakan sejak tahun 1440 H dengan komitmen untuk terus berkembang dan memberikan pelayanan terbaik bagi jamaah. Setiap tahun, kami berupaya meningkatkan kualitas program dan memperluas jangkauan manfaat.</p>

<h2>Tim Panitia</h2>
<p>Didukung oleh tim panitia yang berdedikasi tinggi dan berpengalaman dalam mengorganisir berbagai kegiatan keagamaan. Tim kami terdiri dari berbagai divisi yang bekerja secara profesional dan penuh amanah.</p>
HTML;
    }

    private function getProgramContent(): string
    {
        return <<<HTML
<h1>Program & Kegiatan Ramadhan 1447 H</h1>

<p class="lead">Berikut adalah rangkaian program yang telah kami persiapkan untuk menyambut bulan suci Ramadhan 1447 H.</p>

<h2>Kegiatan Spiritual</h2>
<ul>
    <li><strong>Shalat Tarawih Berjamaah</strong> - Setiap malam di Masjid Agung</li>
    <li><strong>Tadarus Al-Quran</strong> - Khatam 30 Juz bersama-sama</li>
    <li><strong>Kajian Rutin</strong> - Setiap hari setelah Maghrib</li>
    <li><strong>Qiyamul Lail</strong> - Di malam-malam terakhir Ramadhan</li>
</ul>

<h2>Kegiatan Sosial</h2>
<ul>
    <li><strong>Buka Puasa Bersama</strong> - Setiap Jumat dan Sabtu</li>
    <li><strong>Santunan Yatim & Dhuafa</strong> - Program berbagi kebahagiaan</li>
    <li><strong>Bagi-Bagi Takjil</strong> - Di berbagai titik strategis</li>
    <li><strong>Zakat Fitrah</strong> - Penyaluran zakat yang amanah</li>
</ul>

<h2>Kegiatan Edukatif</h2>
<ul>
    <li><strong>Workshop Tahsin</strong> - Memperbaiki bacaan Al-Quran</li>
    <li><strong>Pelatihan Kultum</strong> - Meningkatkan kemampuan dakwah</li>
    <li><strong>Kelas Fiqih Ramadhan</strong> - Memahami hukum-hukum puasa</li>
</ul>
HTML;
    }

    private function getProgramSections(): array
    {
        return [
            [
                'type' => 'timeline',
                'title' => 'Jadwal Kegiatan',
                'items' => [
                    ['time' => '04:00 - 05:00', 'activity' => 'Shalat Subuh Berjamaah'],
                    ['time' => '05:00 - 06:00', 'activity' => 'Tadarus Al-Quran'],
                    ['time' => '18:00 - 18:30', 'activity' => 'Buka Puasa Bersama'],
                    ['time' => '19:00 - 20:00', 'activity' => 'Shalat Tarawih'],
                    ['time' => '20:00 - 21:00', 'activity' => 'Kajian Rutin'],
                ],
            ],
        ];
    }

    private function getRegistrationGuideContent(): string
    {
        return <<<HTML
<h1>Cara Pendaftaran</h1>

<h2>Langkah-Langkah Pendaftaran</h2>
<ol>
    <li><strong>Buat Akun</strong> - Daftar melalui menu "Registrasi" dengan mengisi data diri</li>
    <li><strong>Verifikasi Email</strong> - Cek email untuk verifikasi akun Anda</li>
    <li><strong>Login</strong> - Masuk ke akun yang telah dibuat</li>
    <li><strong>Pilih Kegiatan</strong> - Browse kegiatan yang tersedia dan pilih yang Anda minati</li>
    <li><strong>Isi Form Pendaftaran</strong> - Lengkapi form pendaftaran dengan benar</li>
    <li><strong>Submit</strong> - Kirim pendaftaran dan tunggu konfirmasi</li>
    <li><strong>Konfirmasi</strong> - Anda akan menerima email konfirmasi beserta detail kegiatan</li>
</ol>

<h2>Persyaratan</h2>
<ul>
    <li>Muslim/Muslimah</li>
    <li>Memiliki email aktif</li>
    <li>Nomor telepon/WhatsApp yang dapat dihubungi</li>
    <li>Mengisi form pendaftaran dengan lengkap dan benar</li>
</ul>

<h2>Biaya Pendaftaran</h2>
<p>Sebagian besar kegiatan kami adalah <strong>GRATIS</strong>. Untuk beberapa kegiatan khusus yang memerlukan biaya, akan dicantumkan pada detail event masing-masing.</p>

<h2>Butuh Bantuan?</h2>
<p>Jika mengalami kesulitan dalam proses pendaftaran, silakan hubungi kami melalui:</p>
<ul>
    <li>WhatsApp: +62 812-3456-7890</li>
    <li>Email: support@ramadhanmubarak.org</li>
    <li>Form kontak di website</li>
</ul>
HTML;
    }

    private function getContactContent(): string
    {
        return <<<HTML
<h1>Hubungi Kami</h1>

<p class="lead">Kami siap membantu Anda! Jangan ragu untuk menghubungi kami melalui channel komunikasi yang tersedia.</p>

<h2>Informasi Kontak</h2>
<p><strong>Alamat:</strong><br>
Sekretariat Ramadhan Mubarak 1447 H<br>
Jl. Masjid Agung No. 123, Menteng<br>
Jakarta Pusat 10310</p>

<p><strong>Email:</strong><br>
Info Umum: info@ramadhanmubarak.org<br>
Support: support@ramadhanmubarak.org</p>

<p><strong>Telepon/WhatsApp:</strong><br>
+62 812-3456-7890<br>
+62 813-9876-5432</p>

<h2>Jam Operasional</h2>
<p>Senin - Jumat: 08:00 - 17:00 WIB<br>
Sabtu: 08:00 - 14:00 WIB<br>
Minggu: Libur (kecuali saat bulan Ramadhan)</p>

<h2>Media Sosial</h2>
<p>Follow kami untuk update terbaru:<br>
Instagram: @ramadhanmubarak1447<br>
Facebook: Ramadhan Mubarak 1447 H<br>
YouTube: Ramadhan Mubarak Official</p>
HTML;
    }

    private function getContactSections(): array
    {
        return [
            [
                'type' => 'contact_info',
                'items' => [
                    ['icon' => 'fas fa-map-marker-alt', 'title' => 'Alamat', 'value' => 'Jl. Masjid Agung No. 123, Jakarta'],
                    ['icon' => 'fas fa-phone', 'title' => 'Telepon', 'value' => '+62 812-3456-7890'],
                    ['icon' => 'fas fa-envelope', 'title' => 'Email', 'value' => 'info@ramadhanmubarak.org'],
                ],
            ],
        ];
    }

    private function getPrivacyPolicyContent(): string
    {
        return <<<HTML
<h1>Kebijakan Privasi</h1>

<p><em>Terakhir diperbarui: Januari 2025</em></p>

<h2>1. Informasi yang Kami Kumpulkan</h2>
<p>Kami mengumpulkan informasi yang Anda berikan saat:</p>
<ul>
    <li>Mendaftar akun di platform kami</li>
    <li>Mendaftar untuk mengikuti kegiatan/event</li>
    <li>Mengisi formulir kontak atau feedback</li>
    <li>Berinteraksi dengan layanan kami</li>
</ul>

<h2>2. Penggunaan Informasi</h2>
<p>Informasi yang kami kumpulkan digunakan untuk:</p>
<ul>
    <li>Memproses pendaftaran kegiatan Anda</li>
    <li>Mengirimkan notifikasi terkait kegiatan</li>
    <li>Meningkatkan layanan kami</li>
    <li>Komunikasi dengan Anda terkait program</li>
</ul>

<h2>3. Keamanan Data</h2>
<p>Kami berkomitmen untuk melindungi data pribadi Anda dengan menerapkan langkah-langkah keamanan yang sesuai.</p>

<h2>4. Hak Anda</h2>
<p>Anda memiliki hak untuk:</p>
<ul>
    <li>Mengakses data pribadi Anda</li>
    <li>Memperbarui informasi Anda</li>
    <li>Menghapus akun Anda</li>
    <li>Mengajukan keberatan atas pemrosesan data</li>
</ul>

<h2>5. Kontak</h2>
<p>Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami di privacy@ramadhanmubarak.org</p>
HTML;
    }

    private function getTermsContent(): string
    {
        return <<<HTML
<h1>Syarat & Ketentuan</h1>

<p><em>Terakhir diperbarui: Januari 2025</em></p>

<h2>1. Penerimaan Syarat</h2>
<p>Dengan mengakses dan menggunakan platform Ramadhan Mubarak 1447 H, Anda menyetujui untuk terikat dengan syarat dan ketentuan ini.</p>

<h2>2. Penggunaan Layanan</h2>
<p>Anda setuju untuk:</p>
<ul>
    <li>Memberikan informasi yang akurat dan lengkap</li>
    <li>Menjaga kerahasiaan akun Anda</li>
    <li>Menggunakan platform dengan cara yang sah dan etis</li>
    <li>Tidak menyalahgunakan layanan kami</li>
</ul>

<h2>3. Pendaftaran Kegiatan</h2>
<ul>
    <li>Pendaftaran bersifat mengikat setelah konfirmasi diterima</li>
    <li>Pembatalan harus dilakukan minimal 3 hari sebelum acara</li>
    <li>Panitia berhak membatalkan kegiatan karena force majeure</li>
</ul>

<h2>4. Tanggung Jawab</h2>
<p>Kami tidak bertanggung jawab atas:</p>
<ul>
    <li>Kerugian yang timbul dari penggunaan platform</li>
    <li>Gangguan teknis atau downtime</li>
    <li>Kehilangan data karena faktor eksternal</li>
</ul>

<h2>5. Perubahan Ketentuan</h2>
<p>Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diumumkan melalui platform.</p>

<h2>6. Kontak</h2>
<p>Pertanyaan terkait syarat dan ketentuan dapat dikirim ke legal@ramadhanmubarak.org</p>
HTML;
    }
}
