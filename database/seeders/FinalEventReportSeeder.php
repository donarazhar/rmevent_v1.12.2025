<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\FinalEventReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinalEventReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get events that have ended (past events)
        $pastEvents = Event::where('status', Event::STATUS_COMPLETED)
            ->orWhere('end_datetime', '<', now())
            ->get();

        if ($pastEvents->isEmpty()) {
            $this->command->warn('No completed events found. Creating sample events first...');
            // Create sample completed events if none exist
            $pastEvents = $this->createSampleCompletedEvents();
        }

        // Get users for created_by, reviewed_by, approved_by
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $admin = $users->where('email', 'admin@ramadhan.id')->first() ?? $users->first();
        $reviewer = $users->where('email', 'reviewer@ramadhan.id')->first() ?? $users->skip(1)->first() ?? $admin;
        $approver = $users->where('email', 'approver@ramadhan.id')->first() ?? $users->skip(2)->first() ?? $admin;

        $this->command->info('Creating Final Event Reports...');

        $reportData = [
            // Report 1: Seminar Motivasi - Published
            [
                'status' => FinalEventReport::STATUS_PUBLISHED,
                'title' => 'Laporan Akhir Seminar Motivasi Ramadhan 2024',
                'executive_summary' => 'Seminar Motivasi Ramadhan 2024 telah sukses dilaksanakan dengan tingkat partisipasi yang sangat baik. Acara ini menghadirkan narasumber berkualitas dan mendapat respon positif dari peserta. Secara keseluruhan, acara berjalan lancar dengan pencapaian target yang memuaskan.',
                'event_overview' => 'Seminar Motivasi Ramadhan 2024 merupakan acara tahunan yang bertujuan untuk memberikan pemahaman mendalam tentang nilai-nilai spiritual dan motivasi dalam menjalani ibadah Ramadhan. Acara dilaksanakan secara offline di Aula Masjid Agung dengan durasi 4 jam.',
                'objectives_achievement' => 'Tujuan utama acara adalah meningkatkan pemahaman peserta tentang nilai-nilai Ramadhan, memotivasi untuk beribadah lebih baik, dan membangun networking antar jamaah. Semua tujuan tercapai dengan baik, dibuktikan dengan tingkat kepuasan peserta sebesar 4.7/5.0.',
                'implementation_process' => 'Pelaksanaan acara dimulai pukul 08.00 WIB dengan registrasi peserta, dilanjutkan dengan pembukaan pada pukul 09.00 WIB. Sesi pertama membahas tentang keutamaan Ramadhan, sesi kedua tentang tips produktif di bulan Ramadhan, dan ditutup dengan sesi tanya jawab interaktif.',
                'participant_analysis' => 'Total peserta yang hadir mencapai 250 orang dari 300 peserta terdaftar, dengan komposisi 60% perempuan dan 40% laki-laki. Mayoritas peserta berusia 25-40 tahun dengan latar belakang pendidikan sarjana. Tingkat kehadiran mencapai 83.3%.',
                'financial_report' => 'Total anggaran yang dialokasikan sebesar Rp 50.000.000 dengan realisasi pengeluaran Rp 45.500.000 (91% dari anggaran). Terdapat surplus sebesar Rp 4.500.000 yang akan digunakan untuk kegiatan berikutnya.',
                'challenges_solutions' => 'Tantangan: Kendala teknis sound system di awal acara. Solusi: Tim teknis berhasil memperbaiki dalam waktu 15 menit. Tantangan: Parkir yang terbatas. Solusi: Koordinasi dengan petugas untuk mengatur area parkir tambahan.',
                'lessons_learned' => 'Penting untuk melakukan technical check lebih awal (H-1) untuk menghindari kendala teknis. Perlu antisipasi lebih baik untuk kapasitas parkir. Sesi tanya jawab sebaiknya diberi waktu lebih banyak karena antusiasme peserta tinggi.',
                'recommendations' => 'Untuk acara selanjutnya, disarankan untuk: 1) Menambah durasi sesi tanya jawab, 2) Menyediakan materi dalam bentuk digital untuk peserta, 3) Menggunakan venue dengan kapasitas parkir lebih besar, 4) Mengadakan pre-event survey untuk mengetahui topik yang diminati.',
                'conclusion' => 'Seminar Motivasi Ramadhan 2024 dapat dikategorikan sebagai acara yang sangat sukses dengan pencapaian target yang melampaui ekspektasi. Tingkat kepuasan peserta yang tinggi dan feedback positif menjadi indikator keberhasilan acara ini.',
                'total_participants' => 250,
                'registered_participants' => 300,
                'attended_participants' => 250,
                'attendance_rate' => 83.33,
                'total_budget' => 50000000,
                'total_income' => 50000000,
                'total_expenses' => 45500000,
                'surplus_deficit' => 4500000,
                'overall_satisfaction' => 4.7,
                'content_rating' => 4.8,
                'organization_rating' => 4.6,
                'venue_rating' => 4.7,
                'committee_members' => 15,
                'team_performance_score' => 4.5,
            ],

            // Report 2: Buka Bersama - Approved
            [
                'status' => FinalEventReport::STATUS_APPROVED,
                'title' => 'Laporan Akhir Buka Puasa Bersama Ramadhan 1445 H',
                'executive_summary' => 'Acara Buka Puasa Bersama tahun ini dihadiri oleh 500 jamaah dengan suasana yang hangat dan penuh kebersamaan. Program ini berhasil mempererat tali silaturahmi antar jamaah masjid.',
                'event_overview' => 'Buka Puasa Bersama merupakan kegiatan rutin yang diadakan setiap tahun untuk mempererat ukhuwah islamiyah. Tahun ini acara dilaksanakan di halaman masjid dengan konsep outdoor yang lebih luas dan nyaman.',
                'objectives_achievement' => 'Target peserta 400 orang terlampaui dengan kehadiran 500 jamaah. Tujuan mempererat silaturahmi tercapai dengan baik, terbukti dari interaksi positif antar peserta dan antusiasme yang tinggi.',
                'implementation_process' => 'Persiapan dimulai sejak pukul 14.00 dengan penataan tempat dan persiapan makanan. Acara dibuka dengan kultum singkat pukul 17.30, dilanjutkan berbuka bersama pukul 18.00, dan ditutup dengan shalat Maghrib berjamaah.',
                'participant_analysis' => 'Peserta yang hadir sangat beragam dari berbagai kalangan: keluarga, pemuda, lansia, dan anak-anak. Komposisi peserta: 30% keluarga, 25% pemuda, 20% lansia, dan 25% anak-anak.',
                'financial_report' => 'Dana yang digunakan untuk acara ini sebesar Rp 35.000.000 dengan rincian: konsumsi Rp 25.000.000, dekorasi Rp 3.000.000, sound system Rp 2.000.000, dan lain-lain Rp 5.000.000.',
                'challenges_solutions' => 'Tantangan: Hujan turun menjelang waktu berbuka. Solusi: Segera memindahkan sebagian acara ke dalam masjid dan memasang tenda tambahan. Peserta tetap dapat berbuka dengan nyaman.',
                'lessons_learned' => 'Perlu selalu menyiapkan plan B untuk acara outdoor. Koordinasi dengan tim cuaca/prakiraan sangat penting. Penyediaan tenda cadangan sangat membantu dalam situasi darurat.',
                'recommendations' => 'Untuk tahun depan: 1) Siapkan lebih banyak tenda cadangan, 2) Pertimbangkan venue indoor yang lebih luas, 3) Tambah personil untuk koordinasi yang lebih baik, 4) Buat sistem registrasi online untuk memudahkan pendataan.',
                'conclusion' => 'Meski sempat terkendala hujan, acara Buka Puasa Bersama tetap berjalan dengan sukses dan peserta merasa puas. Spirit kebersamaan dan gotong royong panitia patut diapresiasi.',
                'total_participants' => 500,
                'registered_participants' => 400,
                'attended_participants' => 500,
                'attendance_rate' => 125.00,
                'total_budget' => 35000000,
                'total_income' => 35000000,
                'total_expenses' => 35000000,
                'surplus_deficit' => 0,
                'overall_satisfaction' => 4.5,
                'content_rating' => 4.4,
                'organization_rating' => 4.5,
                'venue_rating' => 4.6,
                'committee_members' => 20,
                'team_performance_score' => 4.6,
            ],

            // Report 3: Kajian Tafsir - Under Review
            [
                'status' => FinalEventReport::STATUS_UNDER_REVIEW,
                'title' => 'Laporan Akhir Kajian Tafsir Al-Quran',
                'executive_summary' => 'Kajian Tafsir Al-Quran seri Ramadhan telah dilaksanakan selama 10 kali pertemuan dengan total 150 peserta tetap. Program ini memberikan pemahaman mendalam tentang tafsir surat-surat pendek dalam Al-Quran.',
                'event_overview' => 'Kajian dilaksanakan setiap Senin dan Kamis malam selama bulan Ramadhan, dengan durasi 2 jam per sesi. Materi disampaikan oleh ustadz yang kompeten dalam bidang tafsir.',
                'objectives_achievement' => 'Target 10 pertemuan tercapai dengan tingkat kehadiran konsisten. Peserta menunjukkan peningkatan pemahaman signifikan tentang makna ayat-ayat Al-Quran yang dikaji.',
                'implementation_process' => 'Setiap sesi dimulai dengan tilawah Al-Quran, dilanjutkan penyampaian materi tafsir selama 60 menit, dan diskusi interaktif 60 menit. Materi dirancang berjenjang dari surat pendek hingga menengah.',
                'participant_analysis' => 'Peserta mayoritas berusia 30-50 tahun dengan minat tinggi terhadap kajian agama. Tingkat retensi peserta sangat baik dengan 90% peserta mengikuti minimal 8 dari 10 sesi.',
                'financial_report' => 'Program ini dibiayai dari dana kas masjid sebesar Rp 15.000.000 untuk honorarium ustadz, konsumsi ringan, dan materi pendukung. Pengeluaran efisien sesuai dengan anggaran yang direncanakan.',
                'challenges_solutions' => 'Tantangan: Beberapa peserta kesulitan memahami istilah-istilah tafsir yang kompleks. Solusi: Dibuat glossary istilah dan sesi tanya jawab yang lebih interaktif.',
                'lessons_learned' => 'Penting untuk menyesuaikan tingkat kesulitan materi dengan kapasitas peserta. Materi pendukung visual sangat membantu pemahaman. Durasi 2 jam sudah ideal untuk kajian mendalam.',
                'recommendations' => 'Lanjutkan program dengan seri yang berbeda. Pertimbangkan untuk membuat kelompok kajian berdasarkan level pemahaman. Dokumentasikan materi untuk keperluan review peserta.',
                'conclusion' => 'Kajian Tafsir Al-Quran terbukti efektif dalam meningkatkan pemahaman jamaah tentang Al-Quran. Program ini layak untuk dilanjutkan dan dikembangkan lebih lanjut.',
                'total_participants' => 150,
                'registered_participants' => 160,
                'attended_participants' => 150,
                'attendance_rate' => 93.75,
                'total_budget' => 15000000,
                'total_income' => 15000000,
                'total_expenses' => 14500000,
                'surplus_deficit' => 500000,
                'overall_satisfaction' => 4.8,
                'content_rating' => 4.9,
                'organization_rating' => 4.7,
                'venue_rating' => 4.7,
                'committee_members' => 8,
                'team_performance_score' => 4.7,
            ],

            // Report 4: Santunan Anak Yatim - Draft
            [
                'status' => FinalEventReport::STATUS_DRAFT,
                'title' => 'Laporan Akhir Santunan Anak Yatim',
                'executive_summary' => 'Program santunan anak yatim berhasil menyalurkan bantuan kepada 100 anak yatim di sekitar wilayah masjid. Dana yang terkumpul melebihi target awal.',
                'event_overview' => 'Program santunan rutin yang diadakan setiap Ramadhan sebagai bentuk kepedulian sosial masjid terhadap anak-anak yatim dan dhuafa di sekitar wilayah masjid.',
                'objectives_achievement' => 'Target penyaluran bantuan untuk 80 anak tercapai dan terlampaui menjadi 100 anak. Dana yang terkumpul Rp 75.000.000 melebihi target Rp 60.000.000.',
                'implementation_process' => 'Program dimulai dengan penggalangan dana selama 2 minggu, dilanjutkan dengan verifikasi data penerima, persiapan paket santunan, dan penyerahan santunan pada minggu ketiga Ramadhan.',
                'participant_analysis' => 'Penerima manfaat adalah anak yatim usia 5-18 tahun dari keluarga tidak mampu. Setiap anak menerima paket berisi uang tunai Rp 500.000, paket sembako, dan perlengkapan sekolah.',
                'financial_report' => 'Total dana terkumpul Rp 75.000.000 dari donasi jamaah dan donatur tetap. Dana tersalurkan Rp 70.000.000 dengan rincian: santunan tunai Rp 50.000.000, sembako Rp 15.000.000, perlengkapan sekolah Rp 5.000.000.',
                'challenges_solutions' => 'Tantangan: Verifikasi data penerima memakan waktu cukup lama. Solusi: Bekerja sama dengan RT/RW setempat untuk validasi data yang lebih cepat dan akurat.',
                'lessons_learned' => 'Database penerima manfaat perlu diperbarui secara berkala. Koordinasi dengan pihak kelurahan sangat membantu validasi data. Transparansi pengelolaan dana meningkatkan kepercayaan donatur.',
                'recommendations' => 'Buat sistem database digital untuk penerima manfaat. Publikasikan laporan keuangan secara berkala. Pertimbangkan program pendampingan jangka panjang untuk anak yatim.',
                'conclusion' => 'Program santunan anak yatim tahun ini berjalan dengan baik dan mendapat apresiasi positif dari masyarakat. Surplus dana akan disimpan untuk program lanjutan.',
                'total_participants' => 100,
                'registered_participants' => 100,
                'attended_participants' => 100,
                'attendance_rate' => 100.00,
                'total_budget' => 60000000,
                'total_income' => 75000000,
                'total_expenses' => 70000000,
                'surplus_deficit' => 5000000,
                'overall_satisfaction' => 4.9,
                'content_rating' => 4.9,
                'organization_rating' => 4.8,
                'venue_rating' => 4.7,
                'committee_members' => 12,
                'team_performance_score' => 4.8,
            ],
        ];

        foreach ($pastEvents->take(4) as $index => $event) {
            if (!isset($reportData[$index])) {
                break;
            }

            $data = $reportData[$index];
            $status = $data['status'];

            // Generate unique report code
            $reportCode = $this->generateReportCode();

            $report = FinalEventReport::create([
                'event_id' => $event->id,
                'report_code' => $reportCode,
                'title' => $data['title'],
                'report_date' => $event->end_datetime->addDays(7)->format('Y-m-d'),

                // Sections
                'executive_summary' => $data['executive_summary'],
                'event_overview' => $data['event_overview'],
                'objectives_achievement' => $data['objectives_achievement'],
                'implementation_process' => $data['implementation_process'],
                'participant_analysis' => $data['participant_analysis'],
                'financial_report' => $data['financial_report'],
                'challenges_solutions' => $data['challenges_solutions'],
                'lessons_learned' => $data['lessons_learned'],
                'recommendations' => $data['recommendations'],
                'conclusion' => $data['conclusion'],

                // Statistics
                'total_participants' => $data['total_participants'],
                'registered_participants' => $data['registered_participants'],
                'attended_participants' => $data['attended_participants'],
                'attendance_rate' => $data['attendance_rate'],

                // Financial
                'total_budget' => $data['total_budget'],
                'total_income' => $data['total_income'],
                'total_expenses' => $data['total_expenses'],
                'surplus_deficit' => $data['surplus_deficit'],

                // Ratings
                'overall_satisfaction' => $data['overall_satisfaction'],
                'content_rating' => $data['content_rating'],
                'organization_rating' => $data['organization_rating'],
                'venue_rating' => $data['venue_rating'],

                // Team
                'committee_members' => $data['committee_members'],
                'team_performance_score' => $data['team_performance_score'],

                // Status & Workflow
                'status' => $status,
                'created_by' => $admin->id,
                'notes' => 'Laporan ini dibuat sebagai dokumentasi lengkap kegiatan.',
            ]);

            // Update workflow fields based on status
            if ($status === FinalEventReport::STATUS_UNDER_REVIEW) {
                $report->update([
                    'reviewed_by' => $reviewer->id,
                    'reviewed_at' => now()->subDays(2),
                ]);
            } elseif ($status === FinalEventReport::STATUS_APPROVED) {
                $report->update([
                    'reviewed_by' => $reviewer->id,
                    'reviewed_at' => now()->subDays(5),
                    'approved_by' => $approver->id,
                    'approved_at' => now()->subDays(3),
                ]);
            } elseif ($status === FinalEventReport::STATUS_PUBLISHED) {
                $report->update([
                    'reviewed_by' => $reviewer->id,
                    'reviewed_at' => now()->subDays(10),
                    'approved_by' => $approver->id,
                    'approved_at' => now()->subDays(8),
                    'published_at' => now()->subDays(7),
                ]);
            }

            $this->command->info("✓ Created: {$report->title} ({$status})");
        }

        $this->command->info('Final Event Reports seeded successfully!');
    }

    /**
     * Generate unique report code
     */
    private function generateReportCode(): string
    {
        $year = now()->year;
        $latestReport = FinalEventReport::whereYear('created_at', $year)
            ->latest('id')
            ->first();

        $number = $latestReport ?
            ((int) substr($latestReport->report_code, -3) + 1) : 1;

        return 'FER-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Create sample completed events for testing
     */
    private function createSampleCompletedEvents()
    {
        $user = User::first();

        $events = [
            [
                'title' => 'Seminar Motivasi Ramadhan 2024',
                'slug' => 'seminar-motivasi-ramadhan-2024',
                'description' => 'Seminar motivasi untuk meningkatkan semangat ibadah di bulan Ramadhan',
                'start_datetime' => now()->subDays(30),
                'end_datetime' => now()->subDays(30)->addHours(4),
                'status' => Event::STATUS_COMPLETED,
            ],
            [
                'title' => 'Buka Puasa Bersama Ramadhan 1445 H',
                'slug' => 'buka-puasa-bersama-1445',
                'description' => 'Acara buka puasa bersama untuk mempererat silaturahmi',
                'start_datetime' => now()->subDays(20),
                'end_datetime' => now()->subDays(20)->addHours(3),
                'status' => Event::STATUS_COMPLETED,
            ],
            [
                'title' => 'Kajian Tafsir Al-Quran',
                'slug' => 'kajian-tafsir-alquran',
                'description' => 'Kajian rutin tafsir Al-Quran setiap minggu',
                'start_datetime' => now()->subDays(15),
                'end_datetime' => now()->subDays(15)->addHours(2),
                'status' => Event::STATUS_COMPLETED,
            ],
            [
                'title' => 'Santunan Anak Yatim',
                'slug' => 'santunan-anak-yatim',
                'description' => 'Program santunan untuk anak yatim dan dhuafa',
                'start_datetime' => now()->subDays(10),
                'end_datetime' => now()->subDays(10)->addHours(3),
                'status' => Event::STATUS_COMPLETED,
            ],
        ];

        $createdEvents = collect();
        foreach ($events as $eventData) {
            $event = Event::create(array_merge($eventData, [
                'location' => 'Masjid Agung',
                'is_registration_open' => false,
                'is_free' => true,
                'max_participants' => 300,
                'current_participants' => 0,
            ]));
            $createdEvents->push($event);
        }

        return $createdEvents;
    }
}
