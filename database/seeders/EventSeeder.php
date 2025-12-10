<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories for events
        $categories = Category::where('type', Category::TYPE_EVENT)->get();
        
        if ($categories->isEmpty()) {
            $this->command->warn('Please run CategorySeeder first!');
            return;
        }

        // Create or get tags
        $tags = $this->createTags();

        // Calculate dates (events spanning across Ramadhan 2025)
        $ramadhanStart = now()->addMonths(3); // Adjust sesuai kalender Hijriyah
        
        // Events data
        $events = [
            // Kajian Ramadhan
            [
                'title' => 'Kajian Tafsir Al-Quran Juz 30',
                'category' => 'Kajian Ramadhan',
                'description' => 'Kajian mendalam tentang tafsir juz 30 (Juz Amma) oleh Ustadz Dr. Ahmad Satori, Lc., MA',
                'location' => 'Masjid Al-Ikhlas, Jakarta Selatan',
                'start_datetime' => $ramadhanStart->copy()->addDays(5)->setTime(20, 30),
                'end_datetime' => $ramadhanStart->copy()->addDays(5)->setTime(22, 0),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->addDays(4),
                'max_participants' => 200,
                'is_free' => true,
                'tags' => ['Kajian', 'Tafsir', 'Al-Quran', 'Ramadhan'],
                'is_featured' => true,
                'requirements' => 'Membawa Al-Quran dan alat tulis',
                'contact_person' => 'Panitia Kajian',
                'contact_phone' => '081234567890',
                'contact_email' => 'kajian@ramadhan1447.id',
            ],
            [
                'title' => 'Kajian Fiqih Puasa dan Zakat',
                'category' => 'Kajian Ramadhan',
                'description' => 'Pembahasan lengkap seputar fiqih puasa dan zakat fitrah bersama Ustadz Prof. Dr. Yusuf Mansur',
                'location' => 'Aula Islamic Center, Bekasi',
                'start_datetime' => $ramadhanStart->copy()->addDays(10)->setTime(19, 30),
                'end_datetime' => $ramadhanStart->copy()->addDays(10)->setTime(21, 30),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->addDays(9),
                'max_participants' => 300,
                'is_free' => true,
                'tags' => ['Kajian', 'Fiqih', 'Puasa', 'Zakat'],
                'is_featured' => true,
            ],
            [
                'title' => 'Tausiyah Ramadhan: Menggapai Malam Lailatul Qadr',
                'category' => 'Kajian Ramadhan',
                'description' => 'Kajian spesial tentang keutamaan dan cara mencari Lailatul Qadr',
                'location' => 'Masjid Agung At-Tin, Jakarta Timur',
                'start_datetime' => $ramadhanStart->copy()->addDays(20)->setTime(21, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(20)->setTime(23, 0),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->addDays(19),
                'max_participants' => 500,
                'is_free' => true,
                'tags' => ['Kajian', 'Lailatul Qadr', 'Ramadhan'],
                'is_featured' => true,
            ],

            // Tilawah Al-Quran
            [
                'title' => 'Pelatihan Tahsin Al-Quran untuk Pemula',
                'category' => 'Tilawah Al-Quran',
                'description' => 'Program pelatihan tahsin Al-Quran dari dasar untuk pemula. Materi meliputi makhorijul huruf dan tajwid dasar.',
                'location' => 'Pesantren Modern Al-Hikmah, Tangerang',
                'start_datetime' => $ramadhanStart->copy()->addDays(1)->setTime(14, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(1)->setTime(16, 0),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->subDays(1),
                'max_participants' => 50,
                'is_free' => false,
                'price' => 150000,
                'tags' => ['Tilawah', 'Tahsin', 'Al-Quran', 'Pelatihan'],
                'is_featured' => false,
                'requirements' => 'Membawa Al-Quran sendiri',
            ],
            [
                'title' => 'Khataman Al-Quran 30 Juz Berjamaah',
                'category' => 'Tilawah Al-Quran',
                'description' => 'Acara khataman Al-Quran 30 juz dibaca secara bergantian oleh jamaah',
                'location' => 'Masjid Istiqlal, Jakarta Pusat',
                'start_datetime' => $ramadhanStart->copy()->addDays(28)->setTime(8, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(28)->setTime(12, 0),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->addDays(27),
                'max_participants' => 300,
                'is_free' => true,
                'tags' => ['Tilawah', 'Khataman', 'Al-Quran', 'Berjamaah'],
                'is_featured' => true,
            ],
            [
                'title' => 'Musabaqah Tilawatil Quran (MTQ) Ramadhan',
                'category' => 'Tilawah Al-Quran',
                'description' => 'Lomba tilawah Al-Quran untuk umum dengan berbagai kategori',
                'location' => 'Gedung Dakwah Muhammadiyah, Jakarta',
                'start_datetime' => $ramadhanStart->copy()->addDays(15)->setTime(9, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(15)->setTime(17, 0),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->addDays(10),
                'max_participants' => 100,
                'is_free' => false,
                'price' => 50000,
                'tags' => ['Tilawah', 'Lomba', 'MTQ', 'Al-Quran'],
                'is_featured' => false,
            ],

            // Tarawih Berjamaah
            [
                'title' => 'Tarawih Berjamaah 8 Rakaat + Witir',
                'category' => 'Tarawih Berjamaah',
                'description' => 'Shalat tarawih berjamaah 8 rakaat dilanjutkan witir 3 rakaat',
                'location' => 'Masjid Al-Barkah, Depok',
                'start_datetime' => $ramadhanStart->copy()->setTime(20, 0),
                'end_datetime' => $ramadhanStart->copy()->setTime(21, 30),
                'registration_start' => now(),
                'registration_end' => null,
                'max_participants' => null, // Unlimited
                'is_free' => true,
                'tags' => ['Tarawih', 'Shalat', 'Berjamaah', 'Ramadhan'],
                'is_featured' => false,
            ],
            [
                'title' => 'Tarawih Berjamaah dengan Tadarus Juz 30',
                'category' => 'Tarawih Berjamaah',
                'description' => 'Tarawih spesial dengan tadarus Al-Quran juz 30 selama 10 malam terakhir',
                'location' => 'Masjid Raya Cikarang',
                'start_datetime' => $ramadhanStart->copy()->addDays(20)->setTime(20, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(29)->setTime(22, 0),
                'registration_start' => now(),
                'registration_end' => null,
                'max_participants' => null,
                'is_free' => true,
                'tags' => ['Tarawih', 'Tadarus', 'Al-Quran'],
                'is_featured' => true,
            ],

            // Dzikir & Doa
            [
                'title' => 'Majelis Dzikir dan Doa Bersama',
                'category' => 'Dzikir & Doa',
                'description' => 'Acara dzikir dan doa bersama setiap Jumat malam di bulan Ramadhan',
                'location' => 'Masjid Al-Furqon, Bogor',
                'start_datetime' => $ramadhanStart->copy()->next('Friday')->setTime(20, 0),
                'end_datetime' => $ramadhanStart->copy()->next('Friday')->setTime(22, 0),
                'registration_start' => now(),
                'registration_end' => null,
                'max_participants' => 500,
                'is_free' => true,
                'tags' => ['Dzikir', 'Doa', 'Majelis', 'Ramadhan'],
                'is_featured' => false,
            ],
            [
                'title' => 'Tahajjud dan Witir Berjamaah',
                'category' => 'Dzikir & Doa',
                'description' => 'Shalat tahajjud berjamaah di sepertiga malam terakhir',
                'location' => 'Pesantren Daarut Tauhid, Bandung',
                'start_datetime' => $ramadhanStart->copy()->addDays(20)->setTime(3, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(29)->setTime(4, 30),
                'registration_start' => now(),
                'registration_end' => null,
                'max_participants' => null,
                'is_free' => true,
                'tags' => ['Tahajjud', 'Witir', 'Shalat Malam'],
                'is_featured' => true,
            ],

            // Buka Puasa Bersama
            [
                'title' => 'Buka Puasa Bersama 1000 Anak Yatim',
                'category' => 'Buka Puasa Bersama',
                'description' => 'Program buka puasa bersama untuk 1000 anak yatim dan dhuafa. Donasi sangat diharapkan.',
                'location' => 'Lapangan Parkir Mall Cikarang, Bekasi',
                'start_datetime' => $ramadhanStart->copy()->addDays(14)->setTime(17, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(14)->setTime(19, 30),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->addDays(12),
                'max_participants' => 1200,
                'is_free' => true,
                'tags' => ['Buka Puasa', 'Anak Yatim', 'Sosial', 'Donasi'],
                'is_featured' => true,
                'requirements' => 'Membawa identitas diri',
                'contact_person' => 'Yayasan Peduli Yatim',
                'contact_phone' => '081234567899',
                'contact_email' => 'yatim@ramadhan1447.id',
            ],
            [
                'title' => 'Iftar On The Road - Berbagi Takjil Gratis',
                'category' => 'Buka Puasa Bersama',
                'description' => 'Program berbagi takjil gratis untuk pengendara dan masyarakat sekitar',
                'location' => 'Simpang Tiga Cikarang, Bekasi',
                'start_datetime' => $ramadhanStart->copy()->setTime(17, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(29)->setTime(18, 30),
                'registration_start' => now(),
                'registration_end' => null,
                'max_participants' => 50, // Relawan
                'is_free' => true,
                'tags' => ['Takjil', 'Buka Puasa', 'Berbagi', 'Relawan'],
                'is_featured' => false,
            ],

            // Sahur On The Road
            [
                'title' => 'Sahur On The Road - Konvoi Motor Subuh',
                'category' => 'Sahur On The Road',
                'description' => 'Konvoi motor keliling kota membangunkan sahur dengan takbir dan musik islami',
                'location' => 'Start: Alun-alun Cikarang',
                'start_datetime' => $ramadhanStart->copy()->setTime(3, 30),
                'end_datetime' => $ramadhanStart->copy()->addDays(29)->setTime(4, 30),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->subDays(1),
                'max_participants' => 100,
                'is_free' => true,
                'tags' => ['Sahur', 'Konvoi', 'Motor', 'Takbir'],
                'is_featured' => true,
                'requirements' => 'Membawa motor sendiri, SIM, dan STNK',
            ],
            [
                'title' => 'Sahur Bareng di Masjid dengan Menu Gratis',
                'category' => 'Sahur On The Road',
                'description' => 'Program sahur gratis setiap hari di masjid untuk jamaah dan masyarakat sekitar',
                'location' => 'Masjid Jami\' Al-Ikhlas, Karawang',
                'start_datetime' => $ramadhanStart->copy()->setTime(3, 30),
                'end_datetime' => $ramadhanStart->copy()->addDays(29)->setTime(5, 0),
                'registration_start' => now(),
                'registration_end' => null,
                'max_participants' => null,
                'is_free' => true,
                'tags' => ['Sahur', 'Gratis', 'Masjid', 'Berjamaah'],
                'is_featured' => false,
            ],

            // Kegiatan Sosial
            [
                'title' => 'Bazaar Ramadhan - Pasar Murah Sembako',
                'category' => 'Kegiatan Sosial',
                'description' => 'Bazaar sembako murah untuk masyarakat menjelang Idul Fitri',
                'location' => 'Lapangan Parkir Masjid Agung Cikarang',
                'start_datetime' => $ramadhanStart->copy()->addDays(25)->setTime(8, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(27)->setTime(17, 0),
                'registration_start' => now(),
                'registration_end' => null,
                'max_participants' => null,
                'is_free' => true,
                'tags' => ['Bazaar', 'Sembako', 'Murah', 'Sosial'],
                'is_featured' => true,
            ],
            [
                'title' => 'Santunan Anak Yatim dan Dhuafa',
                'category' => 'Kegiatan Sosial',
                'description' => 'Program santunan dan pembagian paket sembako untuk anak yatim dan keluarga dhuafa',
                'location' => 'Gedung Serbaguna Cikarang',
                'start_datetime' => $ramadhanStart->copy()->addDays(20)->setTime(9, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(20)->setTime(13, 0),
                'registration_start' => now(),
                'registration_end' => $ramadhanStart->copy()->addDays(18),
                'max_participants' => 500,
                'is_free' => true,
                'tags' => ['Santunan', 'Yatim', 'Dhuafa', 'Sosial'],
                'is_featured' => false,
            ],
        ];

        foreach ($events as $eventData) {
            // Get category
            $category = $categories->firstWhere('name', $eventData['category']);
            
            if (!$category) {
                $this->command->warn("Category '{$eventData['category']}' not found, skipping event: {$eventData['title']}");
                continue;
            }

            // Create event
            $event = Event::create([
                'title' => $eventData['title'],
                'slug' => Str::slug($eventData['title']),
                'description' => $eventData['description'],
                'full_description' => $this->generateFullDescription($eventData),
                'category_id' => $category->id,
                'location' => $eventData['location'],
                'start_datetime' => $eventData['start_datetime'],
                'end_datetime' => $eventData['end_datetime'],
                'timezone' => 'Asia/Jakarta',
                'is_registration_open' => true,
                'registration_start' => $eventData['registration_start'],
                'registration_end' => $eventData['registration_end'],
                'max_participants' => $eventData['max_participants'],
                'current_participants' => rand(0, min($eventData['max_participants'] ?? 50, 30)),
                'is_free' => $eventData['is_free'],
                'price' => $eventData['price'] ?? null,
                'requirements' => $eventData['requirements'] ?? null,
                'contact_person' => $eventData['contact_person'] ?? 'Panitia Acara',
                'contact_phone' => $eventData['contact_phone'] ?? '081234567890',
                'contact_email' => $eventData['contact_email'] ?? 'info@ramadhan1447.id',
                'status' => Event::STATUS_PUBLISHED,
                'is_featured' => $eventData['is_featured'],
            ]);

            // Attach tags
            $eventTags = [];
            foreach ($eventData['tags'] as $tagName) {
                $tag = $tags->firstWhere('name', $tagName);
                if ($tag) {
                    $eventTags[] = $tag->id;
                }
            }
            $event->tags()->attach($eventTags);

            $this->command->info("✓ Created event: {$event->title}");
        }

        $this->command->info('✅ Event seeding completed!');
    }

    /**
     * Create tags
     */
    private function createTags()
    {
        $tagNames = [
            'Ramadhan', 'Kajian', 'Tafsir', 'Al-Quran', 'Fiqih', 'Puasa', 'Zakat',
            'Tilawah', 'Tahsin', 'Khataman', 'Lomba', 'MTQ', 'Tarawih', 'Shalat',
            'Berjamaah', 'Tadarus', 'Dzikir', 'Doa', 'Majelis', 'Tahajjud', 'Witir',
            'Shalat Malam', 'Buka Puasa', 'Anak Yatim', 'Sosial', 'Donasi', 'Takjil',
            'Berbagi', 'Relawan', 'Sahur', 'Konvoi', 'Motor', 'Takbir', 'Gratis',
            'Masjid', 'Bazaar', 'Sembako', 'Murah', 'Santunan', 'Dhuafa', 'Pelatihan',
            'Lailatul Qadr', 'Ibadah'
        ];

        $tags = collect();
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
            $tags->push($tag);
        }

        return $tags;
    }

    /**
     * Generate full description for event
     */
    private function generateFullDescription($eventData)
    {
        $description = "<h2>Tentang Event</h2>\n";
        $description .= "<p>{$eventData['description']}</p>\n\n";
        
        $description .= "<h3>Detail Acara</h3>\n";
        $description .= "<ul>\n";
        $description .= "<li><strong>Lokasi:</strong> {$eventData['location']}</li>\n";
        $description .= "<li><strong>Waktu:</strong> " . $eventData['start_datetime']->format('d F Y, H:i') . " WIB</li>\n";
        $description .= "<li><strong>Biaya:</strong> " . ($eventData['is_free'] ? 'GRATIS' : 'Rp ' . number_format($eventData['price'] ?? 0, 0, ',', '.')) . "</li>\n";
        
        if (isset($eventData['max_participants']) && $eventData['max_participants']) {
            $description .= "<li><strong>Kuota Peserta:</strong> {$eventData['max_participants']} orang</li>\n";
        } else {
            $description .= "<li><strong>Kuota Peserta:</strong> Tidak Terbatas</li>\n";
        }
        
        $description .= "</ul>\n\n";

        if (isset($eventData['requirements'])) {
            $description .= "<h3>Persyaratan</h3>\n";
            $description .= "<p>{$eventData['requirements']}</p>\n\n";
        }

        $description .= "<h3>Fasilitas</h3>\n";
        $description .= "<ul>\n";
        $description .= "<li>Sertifikat (jika tersedia)</li>\n";
        $description .= "<li>Snack dan minuman</li>\n";
        $description .= "<li>Materi event</li>\n";
        $description .= "<li>Doorprize (jika ada)</li>\n";
        $description .= "</ul>\n\n";

        $description .= "<h3>Kontak Informasi</h3>\n";
        $description .= "<p>Untuk informasi lebih lanjut, silakan hubungi:</p>\n";
        $description .= "<ul>\n";
        $description .= "<li><strong>Contact Person:</strong> " . ($eventData['contact_person'] ?? 'Panitia Acara') . "</li>\n";
        $description .= "<li><strong>WhatsApp:</strong> " . ($eventData['contact_phone'] ?? '081234567890') . "</li>\n";
        $description .= "<li><strong>Email:</strong> " . ($eventData['contact_email'] ?? 'info@ramadhan1447.id') . "</li>\n";
        $description .= "</ul>\n";

        return $description;
    }
}