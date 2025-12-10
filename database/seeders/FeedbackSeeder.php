<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎯 Starting Feedback Seeder...');

        $faker = Faker::create('id_ID');

        // Get events and registrations
        $events = Event::published()->get();
        $registrations = EventRegistration::whereIn('status', ['confirmed', 'attended'])->get();
        $users = User::all();

        if ($events->isEmpty()) {
            $this->command->warn('No events found. Please run EventSeeder first.');
            return;
        }

        $createdCount = 0;

        // =========================================================================
        // CREATE TESTIMONIALS FROM REGISTRATIONS (50 testimonials)
        // =========================================================================
        
        $testimonialCount = min(50, $registrations->count());
        
        $this->command->info("Creating {$testimonialCount} testimonials from registrations...");

        foreach ($registrations->random($testimonialCount) as $registration) {
            $event = $registration->event;
            $rating = $this->generateRating(); // Weighted rating (more 4-5 stars)
            
            Feedback::create([
                'user_id' => $registration->user_id,
                'event_id' => $registration->event_id,
                'registration_id' => $registration->id,
                'type' => Feedback::TYPE_TESTIMONIAL,
                'name' => $registration->name,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'subject' => null,
                'message' => $this->getTestimonialMessage($rating, $event),
                'overall_rating' => $rating,
                'content_rating' => $this->generateDetailRating($rating),
                'speaker_rating' => $this->generateDetailRating($rating),
                'venue_rating' => $this->generateDetailRating($rating),
                'organization_rating' => $this->generateDetailRating($rating),
                'recommendation_score' => $this->generateRecommendationScore($rating),
                'suggestions' => rand(0, 100) < 40 ? $this->getSuggestion($rating) : null,
                'is_published' => $rating >= 4 ? true : (rand(0, 100) < 50), // Publish 4-5 stars, 50% for 3 stars
                'is_featured' => $rating === 5 && rand(0, 100) < 25, // 25% of 5-star featured
                'display_order' => rand(1, 100),
                'status' => Feedback::STATUS_RESOLVED,
                'created_at' => now()->subDays(rand(1, 60)),
            ]);

            $createdCount++;
        }

        // =========================================================================
        // CREATE GENERAL FEEDBACKS (15 feedbacks)
        // =========================================================================
        
        $this->command->info('Creating 15 general feedbacks...');

        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $type = $faker->randomElement([
                Feedback::TYPE_GENERAL,
                Feedback::TYPE_SUGGESTION,
                Feedback::TYPE_COMPLAINT,
            ]);
            
            Feedback::create([
                'user_id' => rand(0, 100) < 70 ? $user->id : null, // 70% with user
                'event_id' => null,
                'registration_id' => null,
                'type' => $type,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $faker->numerify('08##########'),
                'subject' => $this->getGeneralSubject($type),
                'message' => $this->getGeneralMessage($type),
                'overall_rating' => rand(3, 5),
                'content_rating' => null,
                'speaker_rating' => null,
                'venue_rating' => null,
                'organization_rating' => null,
                'recommendation_score' => rand(6, 10),
                'suggestions' => rand(0, 100) < 60 ? $this->getGeneralSuggestion() : null,
                'is_published' => false,
                'is_featured' => false,
                'status' => $faker->randomElement([
                    Feedback::STATUS_NEW,
                    Feedback::STATUS_IN_REVIEW,
                    Feedback::STATUS_RESPONDED,
                ]),
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            $createdCount++;
        }

        $this->command->info("✅ Created {$createdCount} feedbacks successfully!");
        $this->command->info("   📊 Breakdown:");
        $this->command->info("      - Testimonials: {$testimonialCount}");
        $this->command->info("      - General Feedbacks: 15");
    }

    /**
     * Generate weighted rating (more positive ratings)
     */
    private function generateRating(): int
    {
        $random = rand(1, 100);
        
        if ($random <= 45) return 5; // 45% chance
        if ($random <= 75) return 4; // 30% chance
        if ($random <= 90) return 3; // 15% chance
        if ($random <= 97) return 2; // 7% chance
        return 1; // 3% chance
    }

    /**
     * Generate detail rating based on overall rating
     */
    private function generateDetailRating(int $overallRating): int
    {
        $min = max(1, $overallRating - 1);
        $max = min(5, $overallRating + 1);
        return rand($min, $max);
    }

    /**
     * Generate recommendation score based on rating
     */
    private function generateRecommendationScore(int $rating): int
    {
        $baseScore = [
            1 => [1, 3],
            2 => [3, 5],
            3 => [5, 7],
            4 => [7, 9],
            5 => [9, 10],
        ];

        return rand($baseScore[$rating][0], $baseScore[$rating][1]);
    }

    /**
     * Get testimonial message based on rating and event
     */
    private function getTestimonialMessage(int $rating, Event $event): string
    {
        $eventTitle = $event->title;
        $categoryName = $event->category->name;

        $messages = [
            5 => [
                "Alhamdulillah, {$eventTitle} benar-benar luar biasa! Materi yang disampaikan sangat mendalam dan mudah dipahami. Ustadz menyampaikan dengan sangat baik dan aplikatif untuk kehidupan sehari-hari. MashaAllah, semoga panitia terus mengadakan event berkualitas seperti ini. Jazakumullahu khairan!",
                "Subhanallah, pengalaman spiritual yang sangat berkesan di {$eventTitle}. Tempat nyaman, materi berkualitas tinggi, dan panitia sangat ramah serta profesional. Saya benar-benar merasakan keberkahan di setiap sesi. Highly recommended untuk jamaah yang ingin menambah ilmu dan meningkatkan kualitas ibadah!",
                "MashaAllah tabarakallah! Event {$eventTitle} ini adalah yang terbaik yang pernah saya ikuti. Tidak hanya materinya yang sangat bermanfaat, tapi suasananya juga sangat kondusif untuk belajar dan merenungkan. Terima kasih banyak kepada panitia yang sudah bekerja keras. Semoga menjadi amal jariyah untuk kita semua.",
                "Alhamdulillah berkah sekali bisa mengikuti {$eventTitle}. Materi sangat relevan dengan kondisi saat ini dan langsung bisa dipraktikkan. Ustadz sangat kompeten dan cara penyampaiannya menyentuh hati. Panitia juga sangat profesional dan ramah. Ditunggu event berikutnya!",
                "Event {$eventTitle} benar-benar membuka wawasan saya tentang Islam yang rahmatan lil alamin. Setiap sesi dipenuhi dengan ilmu yang bermanfaat dan inspiratif. Saya pulang dengan hati yang penuh semangat untuk menjadi muslim yang lebih baik. Terima kasih panitia, jazakumullahu khairan katsiran!",
                "Subhanallah, tidak ada kata-kata yang bisa menggambarkan betapa bermanfaatnya {$eventTitle} ini. Dari awal hingga akhir, semuanya diatur dengan sempurna. Materi mendalam, penyampaian menyentuh hati, dan fasilitas sangat memadai. Ini adalah investasi terbaik untuk akhirat. Barakallahu fikum!",
            ],
            4 => [
                "Alhamdulillah, {$eventTitle} sangat bermanfaat. Materi disampaikan dengan jelas dan mudah dipahami. Ustadz sangat kompeten dan ramah. Mungkin untuk ke depannya bisa ditambahkan sesi tanya jawab yang lebih panjang karena materinya sangat menarik. Overall, sangat memuaskan!",
                "Event yang sangat baik! Saya mendapat banyak ilmu baru dari {$eventTitle}. Tempat nyaman dan panitia ramah. Hanya saja mungkin waktunya bisa diperpanjang karena materinya sangat bagus dan sayang kalau terlalu singkat. Terima kasih panitia!",
                "Alhamdulillah bisa ikut {$eventTitle}. Materi sangat relevan dengan kehidupan sehari-hari dan aplikatif. Penyampaiannya juga bagus. Saran saya, untuk event selanjutnya bisa disediakan modul atau handout digital. Jazakumullahu khairan!",
                "Pengalaman yang sangat menyenangkan di {$eventTitle}. Ustadz sangat inspiratif dan materinya dalam. Fasilitas juga memadai. Semoga ada event serupa lagi dengan topik yang berbeda. Terima kasih panitia!",
                "Event {$eventTitle} terorganisir dengan baik. Materi bermanfaat dan penyampaiannya menarik. Mungkin untuk event selanjutnya bisa ditambahkan workshop praktik langsung. Overall sangat recommended!",
            ],
            3 => [
                "Alhamdulillah bisa ikut {$eventTitle}. Materi cukup bermanfaat meski ada beberapa hal yang bisa ditingkatkan dari sisi teknis seperti sound system. Overall masih worth it untuk diikuti dan saya dapat insight baru tentang {$categoryName}.",
                "Event cukup baik. Materi yang disampaikan menarik, namun mungkin perlu ada perbaikan di beberapa aspek seperti manajemen waktu dan koordinasi panitia. Semoga event berikutnya bisa lebih baik lagi. Terima kasih.",
                "Cukup memuaskan. Materi {$eventTitle} bagus tapi ada beberapa kendala teknis yang sedikit mengganggu. Panitia sudah berusaha keras dan saya apresiasi itu. Semoga terus berkembang dan lebih baik ke depannya.",
            ],
            2 => [
                "Terima kasih sudah mengadakan {$eventTitle}. Materinya cukup menarik namun ada beberapa hal yang perlu diperbaiki seperti koordinasi panitia dan fasilitas. Semoga kritik ini bisa membantu untuk perbaikan event selanjutnya.",
                "Event {$eventTitle} memiliki potensi yang baik, namun pelaksanaannya masih perlu banyak perbaikan. Saya berharap panitia bisa lebih mempersiapkan segala sesuatu dengan matang untuk event mendatang.",
            ],
            1 => [
                "Saya rasa {$eventTitle} masih jauh dari ekspektasi. Banyak hal yang perlu diperbaiki mulai dari koordinasi, fasilitas, hingga penyampaian materi. Semoga ada perbaikan signifikan untuk event selanjutnya.",
            ],
        ];

        return $messages[$rating][array_rand($messages[$rating])];
    }

    /**
     * Get suggestions based on rating
     */
    private function getSuggestion(int $rating): string
    {
        if ($rating >= 4) {
            $suggestions = [
                "Mungkin untuk event selanjutnya bisa ditambahkan sesi tanya jawab yang lebih interaktif dan panjang.",
                "Alangkah baiknya jika ada live streaming untuk jamaah yang tidak bisa hadir langsung.",
                "Saran saya, mungkin bisa disediakan modul atau handout digital yang bisa didownload setelah event.",
                "Untuk ke depannya, mungkin bisa ditambahkan program khusus untuk remaja dan anak-anak.",
                "Semoga bisa ada event rutin setiap bulan, tidak hanya saat Ramadhan saja.",
                "Mungkin bisa ditambahkan sesi workshop praktik langsung untuk memperdalam pemahaman.",
                "Alangkah baiknya jika ada grup WhatsApp atau Telegram untuk follow-up diskusi setelah event.",
                "Saran saya waktu event bisa lebih fleksibel untuk jamaah yang bekerja, mungkin bisa ada sesi weekend.",
            ];
        } else {
            $suggestions = [
                "Mohon persiapan teknis seperti sound system dan proyektor dicek lebih teliti sebelum event dimulai.",
                "Koordinasi antar panitia perlu ditingkatkan agar event berjalan lebih lancar.",
                "Fasilitas toilet dan tempat wudhu perlu diperbanyak untuk menghindari antrian panjang.",
                "Manajemen waktu perlu diperbaiki, beberapa sesi terlalu mepet dan terburu-buru.",
                "Area parkir perlu diatur lebih baik untuk menghindari kemacetan saat event selesai.",
                "Informasi teknis tentang event sebaiknya dikomunikasikan lebih jelas sejak awal pendaftaran.",
            ];
        }

        return $suggestions[array_rand($suggestions)];
    }

    /**
     * Get general feedback subject based on type
     */
    private function getGeneralSubject(string $type): string
    {
        $subjects = [
            Feedback::TYPE_GENERAL => [
                'Apresiasi untuk Platform Digital Ramadhan',
                'Saran untuk Pengembangan Website',
                'Pertanyaan tentang Jadwal Event Mendatang',
                'Testimoni Penggunaan Platform',
                'Feedback Positif untuk Tim Panitia',
            ],
            Feedback::TYPE_SUGGESTION => [
                'Usulan Fitur Baru untuk Website',
                'Saran Program Ramadhan untuk Tahun Depan',
                'Ide Event untuk Anak dan Remaja',
                'Rekomendasi Perbaikan Sistem Registrasi',
                'Saran Kolaborasi dengan Komunitas',
            ],
            Feedback::TYPE_COMPLAINT => [
                'Kendala Saat Registrasi Online',
                'Masalah Teknis pada Website',
                'Keluhan tentang Proses Pembayaran',
                'Kendala Akses Mobile Version',
                'Masalah Notifikasi Email',
            ],
        ];

        return $subjects[$type][array_rand($subjects[$type])];
    }

    /**
     * Get general feedback message based on type
     */
    private function getGeneralMessage(string $type): string
    {
        $messages = [
            Feedback::TYPE_GENERAL => [
                "Assalamualaikum. Alhamdulillah saya sangat terbantu dengan adanya platform digital ini. Semua informasi event Ramadhan tersedia lengkap dan mudah diakses. Website-nya juga user-friendly. Jazakumullahu khairan untuk tim yang sudah mengembangkan platform ini. Semoga terus berkembang dan bermanfaat untuk ummat.",
                "Terima kasih untuk semua tim yang sudah bekerja keras mengorganisir event-event Ramadhan melalui platform ini. Semua berjalan lancar dan profesional. Sistem registrasi online sangat memudahkan dan informasinya lengkap. Ditunggu inovasi-inovasi selanjutnya!",
                "Alhamdulillah, platform ini benar-benar membantu saya menemukan berbagai kegiatan spiritual selama Ramadhan. Sangat informatif dan mudah digunakan. Semoga platform ini bisa menjadi wadah berkumpulnya ummat muslim untuk berbagi kebaikan. Barakallahu fikum!",
            ],
            Feedback::TYPE_SUGGESTION => [
                "Assalamualaikum. Saya ingin memberikan saran untuk pengembangan platform ini. Mungkin bisa ditambahkan fitur notifikasi push untuk event mendatang dan reminder sebelum event dimulai. Juga alangkah baiknya jika ada fitur bookmark untuk menyimpan event favorit. Jazakumullahu khairan.",
                "Saran saya, untuk Ramadhan tahun depan bisa ditambahkan program khusus untuk anak-anak dan remaja. Ini akan sangat bermanfaat untuk keluarga yang ingin mengajak anak-anak belajar tentang nilai-nilai Ramadhan. Mungkin bisa ada workshop kreatif atau lomba untuk anak.",
                "Usulan saya, platform ini bisa dikembangkan menjadi aplikasi mobile yang bisa didownload. Akan lebih praktis untuk jamaah yang sering mengakses dari smartphone. Fitur-fitur seperti jadwal shalat, kalender Ramadhan, dan notifikasi event akan sangat membantu.",
                "Alangkah baiknya jika ada fitur komunitas atau forum diskusi di platform ini. Jamaah bisa saling berbagi pengalaman dan saling mengingatkan tentang kebaikan. Bisa juga untuk koordinasi carpooling atau berbagi informasi tentang masjid-masjid terdekat.",
            ],
            Feedback::TYPE_COMPLAINT => [
                "Assalamualaikum. Saya mengalami sedikit kendala saat melakukan registrasi online untuk event kemarin. Proses pembayaran sempat error dan saya harus mengulang beberapa kali. Mungkin sistem bisa ditingkatkan agar lebih stabil. Namun Alhamdulillah akhirnya berhasil. Terima kasih.",
                "Saya ingin menyampaikan bahwa website agak lambat saat diakses dari mobile. Mungkin perlu optimasi untuk versi mobile agar lebih responsif. Secara keseluruhan kontennya sudah bagus, hanya perlu perbaikan performa saja. Jazakumullahu khairan.",
                "Mohon diperbaiki sistem notifikasi email-nya. Saya sudah registrasi untuk beberapa event tapi tidak menerima email konfirmasi. Setelah saya cek ternyata masuk ke folder spam. Mungkin bisa disesuaikan setting email server-nya. Terima kasih.",
            ],
        ];

        return $messages[$type][array_rand($messages[$type])];
    }

    /**
     * Get general suggestions
     */
    private function getGeneralSuggestion(): string
    {
        $suggestions = [
            "Tambahkan fitur dark mode untuk kenyamanan membaca di malam hari.",
            "Sediakan API untuk integrasi dengan aplikasi pihak ketiga.",
            "Buat dashboard personal untuk tracking event yang sudah diikuti.",
            "Tambahkan fitur rating dan review untuk setiap event.",
            "Sediakan versi Bahasa Arab untuk jamaah internasional.",
            "Integrasi dengan kalender Google untuk reminder otomatis.",
            "Tambahkan fitur share ke social media yang lebih lengkap.",
            "Buat program referral atau poin untuk peserta aktif.",
            "Sediakan konten artikel dan video tutorial tentang ibadah Ramadhan.",
            "Tambahkan fitur donasi online untuk kegiatan sosial.",
        ];

        return $suggestions[array_rand($suggestions)];
    }
}