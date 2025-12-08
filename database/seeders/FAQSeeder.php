<?php
// database/seeders/FAQSeeder.php

namespace Database\Seeders;

use App\Models\FAQ;
use App\Models\Category;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil atau buat kategori FAQ
        $generalCategory = Category::firstOrCreate(
            ['slug' => 'faq-umum', 'type' => 'general'],
            [
                'name' => 'FAQ Umum',
                'description' => 'Pertanyaan umum seputar Ramadhan Mubarak',
                'icon' => 'fas fa-question-circle',
                'color' => '#6B7280',
                'is_active' => true,
                'order' => 1,
            ]
        );

        $registrationCategory = Category::firstOrCreate(
            ['slug' => 'faq-pendaftaran', 'type' => 'general'],
            [
                'name' => 'FAQ Pendaftaran',
                'description' => 'Pertanyaan seputar pendaftaran kegiatan',
                'icon' => 'fas fa-user-plus',
                'color' => '#3B82F6',
                'is_active' => true,
                'order' => 2,
            ]
        );

        $eventCategory = Category::firstOrCreate(
            ['slug' => 'faq-kegiatan', 'type' => 'general'],
            [
                'name' => 'FAQ Kegiatan',
                'description' => 'Pertanyaan seputar kegiatan dan program',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#10B981',
                'is_active' => true,
                'order' => 3,
            ]
        );

        $faqs = [
            // ============================================
            // FAQ UMUM
            // ============================================
            [
                'category_id' => $generalCategory->id,
                'question' => 'Apa itu Ramadhan Mubarak 1447 H?',
                'answer' => 'Ramadhan Mubarak 1447 H adalah program tahunan yang diselenggarakan untuk memfasilitasi umat Muslim dalam menjalankan ibadah di bulan suci Ramadhan. Kami menyediakan berbagai kegiatan spiritual, sosial, dan edukatif yang dirancang untuk meningkatkan ketakwaan dan mempererat ukhuwah Islamiyah.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category_id' => $generalCategory->id,
                'question' => 'Siapa yang boleh mengikuti kegiatan ini?',
                'answer' => 'Semua umat Muslim dari berbagai kalangan dipersilakan untuk mengikuti kegiatan kami. Baik laki-laki maupun perempuan, dari anak-anak hingga dewasa, semua memiliki kesempatan yang sama untuk berpartisipasi dalam program yang sesuai dengan kategori usia dan minat masing-masing.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category_id' => $generalCategory->id,
                'question' => 'Apakah semua kegiatan gratis?',
                'answer' => 'Sebagian besar kegiatan kami bersifat gratis dan terbuka untuk umum. Namun, untuk beberapa kegiatan khusus seperti workshop tertentu atau buka puasa bersama dengan kapasitas terbatas, mungkin ada kontribusi biaya yang akan dicantumkan pada detail masing-masing event. Informasi lengkap tentang biaya (jika ada) akan selalu transparan pada halaman pendaftaran.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'category_id' => $generalCategory->id,
                'question' => 'Dimana lokasi kegiatan dilaksanakan?',
                'answer' => 'Sebagian besar kegiatan dilaksanakan di Masjid Agung Al-Mubarak, Jakarta Pusat. Untuk kegiatan tertentu yang dilaksanakan di lokasi berbeda, akan dicantumkan dengan jelas pada detail event masing-masing. Kami juga menyediakan peta lokasi dan petunjuk arah untuk memudahkan jamaah.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'category_id' => $generalCategory->id,
                'question' => 'Bagaimana cara menghubungi panitia?',
                'answer' => 'Anda dapat menghubungi kami melalui berbagai channel: (1) WhatsApp: +62 812-3456-7890, (2) Email: info@ramadhanmubarak.org atau support@ramadhanmubarak.org, (3) Formulir kontak di website, (4) Direct Message di Instagram @ramadhanmubarak1447. Tim kami siap membantu Anda di jam operasional Senin-Jumat pukul 08:00-17:00 WIB.',
                'order' => 5,
                'is_active' => true,
            ],

            // ============================================
            // FAQ PENDAFTARAN
            // ============================================
            [
                'category_id' => $registrationCategory->id,
                'question' => 'Bagaimana cara mendaftar kegiatan?',
                'answer' => 'Cara mendaftar sangat mudah: (1) Buat akun terlebih dahulu melalui menu "Registrasi", (2) Verifikasi email Anda, (3) Login ke akun, (4) Pilih kegiatan yang ingin diikuti dari halaman "Events", (5) Klik tombol "Daftar" pada event yang dipilih, (6) Isi formulir pendaftaran dengan lengkap dan benar, (7) Submit pendaftaran, (8) Tunggu email konfirmasi dari kami. Seluruh proses dilakukan secara online melalui website.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category_id' => $registrationCategory->id,
                'question' => 'Apakah harus membuat akun untuk mendaftar?',
                'answer' => 'Ya, untuk kegiatan yang memerlukan pendaftaran, Anda perlu membuat akun terlebih dahulu. Hal ini bertujuan untuk memudahkan Anda dalam mengelola pendaftaran, mendapatkan notifikasi terkait kegiatan, dan mengakses berbagai fitur lainnya. Proses pembuatan akun sangat cepat dan mudah, hanya memerlukan email dan beberapa informasi dasar.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category_id' => $registrationCategory->id,
                'question' => 'Berapa lama proses konfirmasi pendaftaran?',
                'answer' => 'Setelah Anda mengirimkan pendaftaran, sistem akan secara otomatis memproses dan mengirimkan email konfirmasi dalam waktu maksimal 1x24 jam. Untuk kegiatan dengan kuota terbatas, konfirmasi akan dikirim berdasarkan first-come-first-served. Anda juga dapat mengecek status pendaftaran melalui dashboard akun Anda.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'category_id' => $registrationCategory->id,
                'question' => 'Bagaimana jika saya ingin membatalkan pendaftaran?',
                'answer' => 'Anda dapat membatalkan pendaftaran dengan login ke akun, masuk ke menu "Pendaftaran Saya", dan pilih opsi "Batalkan Pendaftaran" pada event yang ingin dibatalkan. Untuk event dengan biaya, pembatalan minimal 3 hari sebelum acara akan mendapat refund penuh. Pembatalan kurang dari 3 hari tidak dapat direfund. Kami sangat menghargai jika Anda memberitahu kami sesegera mungkin jika berhalangan hadir.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'category_id' => $registrationCategory->id,
                'question' => 'Apakah bisa mendaftar untuk orang lain?',
                'answer' => 'Ya, Anda dapat mendaftar untuk keluarga atau teman. Namun, pastikan Anda memiliki informasi lengkap dan akurat dari orang yang akan didaftarkan. Untuk kegiatan tertentu yang memerlukan verifikasi identitas, peserta yang terdaftar wajib hadir sesuai dengan data yang didaftarkan. Satu akun dapat mendaftarkan beberapa peserta untuk event yang sama.',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'category_id' => $registrationCategory->id,
                'question' => 'Bagaimana jika lupa password akun?',
                'answer' => 'Jika lupa password, klik link "Lupa Password" di halaman login. Masukkan email yang terdaftar, dan kami akan mengirimkan link reset password ke email Anda. Ikuti instruksi di email tersebut untuk membuat password baru. Jika masih mengalami kesulitan, hubungi tim support kami di support@ramadhanmubarak.org.',
                'order' => 6,
                'is_active' => true,
            ],

            // ============================================
            // FAQ KEGIATAN
            // ============================================
            [
                'category_id' => $eventCategory->id,
                'question' => 'Apa saja kegiatan yang tersedia?',
                'answer' => 'Kami menyediakan berbagai kegiatan meliputi: (1) Kegiatan Spiritual: Shalat Tarawih, Tadarus Al-Quran, Kajian Rutin, Qiyamul Lail, (2) Kegiatan Sosial: Buka Puasa Bersama, Santunan Yatim & Dhuafa, Bagi-bagi Takjil, (3) Kegiatan Edukatif: Workshop Tahsin, Pelatihan Kultum, Kelas Fiqih Ramadhan, (4) Kegiatan Khusus Anak: Mengaji, Mewarnai, Games Islami. Detail lengkap dapat dilihat di halaman "Program & Kegiatan".',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category_id' => $eventCategory->id,
                'question' => 'Apakah ada dress code untuk mengikuti kegiatan?',
                'answer' => 'Kami mengharapkan seluruh peserta mengenakan pakaian yang sopan dan menutup aurat sesuai dengan tuntunan Islam. Untuk kegiatan di masjid, jamaah perempuan diharapkan mengenakan jilbab/kerudung dan pakaian yang longgar. Jamaah laki-laki diharapkan mengenakan pakaian yang rapi dan sopan. Untuk kegiatan outdoor tertentu, mungkin ada dress code khusus yang akan diinformasikan pada detail event.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category_id' => $eventCategory->id,
                'question' => 'Apakah boleh membawa anak-anak?',
                'answer' => 'Ya, anak-anak diperbolehkan mengikuti kegiatan. Namun, untuk kegiatan tertentu yang memerlukan fokus dan ketenangan (seperti kajian atau tahsin), kami menyarankan anak di bawah 5 tahun dititipkan terlebih dahulu atau didampingi dengan baik. Kami juga menyediakan program khusus untuk anak-anak yang lebih fun dan edukatif. Orang tua bertanggung jawab penuh atas keamanan dan kenyamanan anak-anak mereka selama kegiatan.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'category_id' => $eventCategory->id,
                'question' => 'Apakah disediakan konsumsi?',
                'answer' => 'Untuk kegiatan Buka Puasa Bersama, konsumsi sudah disediakan oleh panitia. Untuk kegiatan lainnya seperti Kajian atau Tarawih, biasanya disediakan snack ringan dan minuman. Detail tentang konsumsi akan dicantumkan pada informasi masing-masing event. Jika Anda memiliki alergi makanan atau dietary restriction tertentu, mohon informasikan saat pendaftaran.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'category_id' => $eventCategory->id,
                'question' => 'Bagaimana jika saya terlambat datang?',
                'answer' => 'Kami sangat menganjurkan jamaah untuk datang tepat waktu agar tidak mengganggu kekhusyukan ibadah. Namun, jika terpaksa terlambat, Anda tetap dapat bergabung dengan kegiatan yang sedang berlangsung. Masuk dengan tenang dan mengikuti arahan panitia. Untuk kegiatan dengan durasi terbatas seperti workshop, keterlambatan lebih dari 15 menit mungkin tidak diperkenankan masuk demi kenyamanan peserta lain.',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'category_id' => $eventCategory->id,
                'question' => 'Apakah ada sertifikat untuk kegiatan tertentu?',
                'answer' => 'Ya, untuk kegiatan edukatif seperti Workshop Tahsin, Pelatihan Kultum, dan Kelas Fiqih yang bersifat berjenjang, kami menyediakan sertifikat bagi peserta yang menyelesaikan seluruh rangkaian program dengan baik. Sertifikat akan diberikan pada sesi terakhir atau dapat diambil di sekretariat setelah kegiatan selesai. Sertifikat ini dapat menjadi portofolio Anda dalam pengembangan ilmu keislaman.',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'category_id' => $eventCategory->id,
                'question' => 'Bagaimana jika kegiatan dibatalkan?',
                'answer' => 'Jika terjadi pembatalan kegiatan karena force majeure atau kondisi darurat, kami akan segera menginformasikan kepada seluruh peserta yang telah terdaftar melalui email, WhatsApp, dan notifikasi di website. Untuk event berbayar, biaya akan di-refund 100% atau dapat digunakan untuk event lain yang setara. Kami akan memberitahu informasi pembatalan minimal 24 jam sebelum acara (kecuali kondisi darurat).',
                'order' => 7,
                'is_active' => true,
            ],

            // ============================================
            // FAQ TAMBAHAN
            // ============================================
            [
                'category_id' => $generalCategory->id,
                'question' => 'Apakah ada parkir untuk kendaraan?',
                'answer' => 'Ya, kami menyediakan area parkir yang cukup luas untuk mobil dan motor di sekitar Masjid Agung. Namun, karena kapasitas terbatas terutama pada malam tarawih, kami sangat menganjurkan jamaah untuk: (1) Datang lebih awal, (2) Menggunakan transportasi umum jika memungkinkan, (3) Carpool dengan jamaah lain. Petugas parkir akan membantu mengarahkan kendaraan Anda. Parkir gratis untuk semua jamaah.',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'category_id' => $generalCategory->id,
                'question' => 'Bagaimana cara menjadi volunteer/panitia?',
                'answer' => 'Kami sangat senang jika Anda ingin berkontribusi sebagai volunteer! Cara mendaftarnya: (1) Isi formulir volunteer di halaman "Hubungi Kami", (2) Atau kirim email ke volunteer@ramadhanmubarak.org dengan subject "Volunteer Ramadhan 1447", (3) Cantumkan: nama, kontak, keahlian/minat, dan waktu yang tersedia. Tim kami akan menghubungi Anda untuk proses selanjutnya. Sebagai volunteer, Anda akan mendapat pengalaman berharga, sertifikat, dan pahala berlipat!',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'category_id' => $generalCategory->id,
                'question' => 'Apakah menerima donasi atau sponsorship?',
                'answer' => 'Ya, kami menerima donasi dan sponsorship dari individu maupun perusahaan yang ingin berkontribusi dalam kesuksesan program Ramadhan Mubarak. Donasi dapat disalurkan melalui: (1) Transfer bank (informasi rekening ada di halaman Donasi), (2) QRIS, (3) Langsung ke sekretariat. Semua donasi akan dikelola dengan amanah dan transparan. Laporan keuangan dapat diakses oleh donatur. Untuk sponsorship perusahaan, hubungi partnership@ramadhanmubarak.org.',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            FAQ::create($faq);
        }

        $this->command->info('✅ FAQs seeded successfully! Total: ' . FAQ::count());
        $this->command->info('📂 Categories: General (' . $generalCategory->name . '), Registration (' . $registrationCategory->name . '), Events (' . $eventCategory->name . ')');
    }
}