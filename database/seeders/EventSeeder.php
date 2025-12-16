<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', Category::TYPE_EVENT)->get();

        if ($categories->isEmpty()) {
            $this->command->warn('Please run CategorySeeder first!');
            return;
        }

        $tags = $this->createTags();

        // Base date Ramadhan 1436 H (disesuaikan dengan tahun berjalan)
        $ramadhanStart = Carbon::parse('2026-06-18'); // 1 Ramadhan 1436 H

        $events = [
            // Kajian Ramadhan
            [
                'title' => 'Kuliah Subuh: Ramadhan dan Persaudaraan',
                'category' => 'Kajian Ramadhan',
                'description' => 'Kajian ba\'da Subuh dengan tema Ramadhan dan Persaudaraan oleh Drs. H. Amliwazir Saidi',
                'location' => 'Ruang Utama Masjid Agung Al Azhar',
                'start_datetime' => $ramadhanStart->copy()->addDays(0)->setTime(5, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(0)->setTime(6, 0),
                'is_free' => true,
                'tags' => ['Kuliah Subuh', 'Kajian', 'Ramadhan'],
                'is_featured' => true,
            ],
            [
                'title' => 'Tarawih dan Taushiyah: Mengapa Harus Berpuasa',
                'category' => 'Tarawih Berjamaah',
                'description' => 'Shalat tarawih 8 rakaat + witir dilanjutkan taushiyah tentang kewajiban puasa',
                'location' => 'Ruang Utama Masjid Agung Al Azhar',
                'start_datetime' => $ramadhanStart->copy()->addDays(0)->setTime(20, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(0)->setTime(21, 30),
                'is_free' => true,
                'tags' => ['Tarawih', 'Ceramah', 'Ramadhan'],
                'is_featured' => true,
            ],

            // Tilawah & Tadarus
            [
                'title' => 'Tadarus Al-Quran Sebelum Dzuhur',
                'category' => 'Tilawah Al-Quran',
                'description' => 'Tadarus Al-Quran berjamaah setiap hari sebelum shalat Dzuhur',
                'location' => 'Ruang Utama Masjid Agung Al Azhar',
                'start_datetime' => $ramadhanStart->copy()->setTime(11, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(28)->setTime(12, 0),
                'is_free' => true,
                'tags' => ['Tadarus', 'Al-Quran', 'Ramadhan'],
                'is_featured' => false,
            ],
            [
                'title' => 'Nuzulul Quran 17 Ramadhan 1436 H',
                'category' => 'Tilawah Al-Quran',
                'description' => 'Peringatan Nuzulul Quran dengan khataman dan kajian spesial',
                'location' => 'Ruang Utama Masjid Agung Al Azhar',
                'start_datetime' => $ramadhanStart->copy()->addDays(16)->setTime(6, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(16)->setTime(17, 0),
                'is_free' => true,
                'tags' => ['Nuzulul Quran', 'Al-Quran', 'Khataman'],
                'is_featured' => true,
            ],
            [
                'title' => 'Musabaqah Tilawatil Quran (MTQ) Ramadhan',
                'category' => 'Tilawah Al-Quran',
                'description' => 'Lomba tilawah Al-Quran untuk umum dengan berbagai kategori',
                'location' => 'Masjid Agung Al Azhar',
                'start_datetime' => Carbon::parse('2026-06-27')->setTime(8, 0),
                'end_datetime' => Carbon::parse('2026-06-27')->setTime(17, 0),
                'registration_end' => Carbon::parse('2026-06-25'),
                'max_participants' => 100,
                'is_free' => false,
                'price' => 50000,
                'tags' => ['MTQ', 'Lomba', 'Al-Quran'],
                'is_featured' => true,
            ],
            [
                'title' => 'Musabaqah Hifzhil Quran (MHQ) Ramadhan',
                'category' => 'Tilawah Al-Quran',
                'description' => 'Lomba hafalan Al-Quran untuk umum',
                'location' => 'Masjid Agung Al Azhar',
                'start_datetime' => Carbon::parse('2026-06-28')->setTime(8, 0),
                'end_datetime' => Carbon::parse('2026-06-28')->setTime(17, 0),
                'registration_end' => Carbon::parse('2026-06-26'),
                'max_participants' => 50,
                'is_free' => false,
                'price' => 50000,
                'tags' => ['MHQ', 'Lomba', 'Hafalan'],
                'is_featured' => true,
            ],

            // Buka Puasa Bersama
            [
                'title' => 'Buka Puasa Bersama 2000 Anak Yatim & Dhuafa',
                'category' => 'Buka Puasa Bersama',
                'description' => 'Program buka puasa bersama untuk 2000 anak yatim dan dhuafa. Donasi sangat diharapkan.',
                'location' => 'Aula Buya HAMKA, Masjid Agung Al Azhar',
                'start_datetime' => Carbon::parse('2026-07-11')->setTime(13, 0),
                'end_datetime' => Carbon::parse('2026-07-11')->setTime(19, 30),
                'registration_end' => Carbon::parse('2026-07-09'),
                'max_participants' => 2000,
                'is_free' => true,
                'tags' => ['Buka Puasa', 'Anak Yatim', 'Sosial'],
                'is_featured' => true,
                'requirements' => 'Membawa identitas diri',
                'contact_person' => 'Panitia Ramadhan',
                'contact_phone' => '021-7278-3683',
                'contact_email' => 'info@masjidagungalazhar.com',
            ],
            [
                'title' => 'Buka Puasa Harian Gratis',
                'category' => 'Buka Puasa Bersama',
                'description' => 'Buka puasa gratis untuk jamaah setiap hari di bulan Ramadhan',
                'location' => 'Aula Buya HAMKA',
                'start_datetime' => $ramadhanStart->copy()->setTime(18, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(28)->setTime(18, 30),
                'is_free' => true,
                'tags' => ['Buka Puasa', 'Gratis', 'Harian'],
                'is_featured' => false,
            ],

            // Buka Puasa di LAPAS
            [
                'title' => 'Buka Puasa Bersama di Lapas Dewasa Tangerang',
                'category' => 'Kegiatan Sosial',
                'description' => 'Program berbagi kebahagiaan dengan warga binaan Lapas Dewasa Tangerang',
                'location' => 'Lapas Dewasa Tangerang',
                'start_datetime' => Carbon::parse('2026-06-27')->setTime(15, 0),
                'end_datetime' => Carbon::parse('2026-06-27')->setTime(20, 30),
                'is_free' => true,
                'tags' => ['Buka Puasa', 'Sosial', 'LAPAS'],
                'is_featured' => false,
            ],

            // I'tikaf
            [
                'title' => 'I\'tikaf 10 Hari Terakhir Ramadhan',
                'category' => 'Dzikir & Doa',
                'description' => 'Program i\'tikaf khusus di 10 hari terakhir Ramadhan dengan kajian dari para ulama',
                'location' => 'Ruang Utama Masjid Agung Al Azhar',
                'start_datetime' => Carbon::parse('2026-07-07')->setTime(0, 0),
                'end_datetime' => Carbon::parse('2026-07-15')->setTime(23, 59),
                'is_free' => true,
                'tags' => ['Itikaf', 'Ramadhan', 'Lailatul Qadr'],
                'is_featured' => true,
            ],

            // Kegiatan Sosial
            [
                'title' => 'Bazaar Ramadhan',
                'category' => 'Kegiatan Sosial',
                'description' => 'Bazaar Ramadhan dengan berbagai menu buka puasa dan kebutuhan Ramadhan',
                'location' => 'Lingkungan Masjid Agung Al Azhar',
                'start_datetime' => $ramadhanStart->copy()->setTime(15, 0),
                'end_datetime' => $ramadhanStart->copy()->addDays(27)->setTime(22, 0),
                'is_free' => true,
                'tags' => ['Bazaar', 'Ramadhan', 'Kuliner'],
                'is_featured' => false,
            ],

            // Kajian Khusus (Jumat)
            [
                'title' => 'Dialog: Zakat Membuat Ummat Berdaya dan Berkarya',
                'category' => 'Kajian Ramadhan',
                'description' => 'Dialog interaktif tentang pemberdayaan umat melalui zakat bersama Harry Rachmad, S.Pd',
                'location' => 'Ruang Utama Masjid Agung Al Azhar',
                'start_datetime' => Carbon::parse('2026-06-19')->setTime(13, 0),
                'end_datetime' => Carbon::parse('2026-06-19')->setTime(14, 30),
                'is_free' => true,
                'tags' => ['Zakat', 'Dialog', 'Kajian'],
                'is_featured' => true,
            ],
            [
                'title' => 'Dialog: Makro Ekonomi Syariah',
                'category' => 'Kajian Ramadhan',
                'description' => 'Kajian tentang ekonomi syariah yang sukses memberdayakan umat bersama Ir. H. Adiwarman A. Karim',
                'location' => 'Ruang Utama Masjid Agung Al Azhar',
                'start_datetime' => Carbon::parse('2026-06-26')->setTime(13, 0),
                'end_datetime' => Carbon::parse('2026-06-26')->setTime(14, 30),
                'is_free' => true,
                'tags' => ['Ekonomi Syariah', 'Dialog', 'Kajian'],
                'is_featured' => true,
            ],
        ];

        $createdCount = 0;
        foreach ($events as $eventData) {
            $category = $categories->firstWhere('name', $eventData['category']);

            if (!$category) {
                $this->command->warn("Category '{$eventData['category']}' not found");
                continue;
            }

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
                'registration_start' => now(),
                'registration_end' => $eventData['registration_end'] ?? null,
                'max_participants' => $eventData['max_participants'] ?? null,
                'current_participants' => 0,
                'is_free' => $eventData['is_free'],
                'price' => $eventData['price'] ?? null,
                'requirements' => $eventData['requirements'] ?? null,
                'contact_person' => $eventData['contact_person'] ?? 'Panitia Masjid',
                'contact_phone' => $eventData['contact_phone'] ?? '021-7278-3683',
                'contact_email' => $eventData['contact_email'] ?? 'info@masjidagungalazhar.com',
                'status' => Event::STATUS_PUBLISHED,
                'is_featured' => $eventData['is_featured'],
            ]);

            $eventTags = [];
            foreach ($eventData['tags'] as $tagName) {
                $tag = $tags->firstWhere('name', $tagName);
                if ($tag) $eventTags[] = $tag->id;
            }
            $event->tags()->attach($eventTags);

            $this->command->info("✓ Created: {$event->title}");
            $createdCount++;
        }

        $this->command->info("✅ Event seeding completed! Created {$createdCount} events");
    }

    private function createTags()
    {
        $tagNames = [
            'Ramadhan',
            'Kajian',
            'Al-Quran',
            'Tarawih',
            'Ceramah',
            'Tadarus',
            'Tilawah',
            'MTQ',
            'MHQ',
            'Lomba',
            'Hafalan',
            'Nuzulul Quran',
            'Khataman',
            'Buka Puasa',
            'Anak Yatim',
            'Sosial',
            'Gratis',
            'Harian',
            'LAPAS',
            'Konvoi',
            'Sahur',
            'Takbir',
            'Itikaf',
            'Lailatul Qadr',
            'Dzikir',
            'Doa',
            'Bazaar',
            'Kuliner',
            'Zakat',
            'Dialog',
            'Ekonomi Syariah',
            'Kuliah Subuh',
            'Taushiyah'
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
            $description .= "<li><strong>Kuota:</strong> {$eventData['max_participants']} orang</li>\n";
        }

        $description .= "</ul>\n";

        if (isset($eventData['requirements'])) {
            $description .= "<h3>Persyaratan</h3>\n";
            $description .= "<p>{$eventData['requirements']}</p>\n";
        }

        return $description;
    }
}
