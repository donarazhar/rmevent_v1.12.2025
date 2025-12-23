<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExecutiveSummary;
use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExecutiveSummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found. Please create a user first.');
            return;
        }

        // Disable auto-generation temporarily and generate manually
        $samples = [
            // Sample 1: Monthly Summary
            [
                'summary_code' => $this->generateCode(1),
                'event_id' => null,
                'title' => 'Monthly Report - Ramadhan 1447 H',
                'summary_type' => 'monthly',
                'period_start' => Carbon::now()->startOfMonth(),
                'period_end' => Carbon::now()->endOfMonth(),
                'report_date' => Carbon::now(),
                'executive_overview' => "Bulan Ramadhan 1447 H telah berjalan dengan baik dengan berbagai program yang dilaksanakan. Total pendapatan mencapai Rp 150 juta dengan pengeluaran Rp 120 juta, menghasilkan surplus Rp 30 juta.",
                'key_highlights' => "- Pelaksanaan 15 kegiatan Ramadhan\n- Partisipasi 500+ jamaah\n- Kepuasan peserta mencapai 4.5/5\n- Budget efficiency 80%",
                'achievements' => "- Berhasil melaksanakan seluruh program sesuai jadwal\n- Meningkatkan partisipasi jamaah 20% dari tahun lalu\n- Menghemat budget 20%",
                'challenges' => "- Keterbatasan ruang untuk beberapa acara\n- Koordinasi dengan vendor yang perlu diperbaiki",
                'recommendations' => "- Perlu mencari venue yang lebih besar untuk tahun depan\n- Membuat SOP kerjasama dengan vendor\n- Meningkatkan komunikasi dengan jamaah",
                'total_income' => 150000000,
                'total_expenses' => 120000000,
                'net_result' => 30000000,
                'budget_utilization_percentage' => 80.00,
                'events_conducted' => 15,
                'total_participants' => 500,
                'satisfaction_score' => 4.5,
                'status' => 'published',
                'created_by' => $user->id,
                'approved_by' => $user->id,
                'approved_at' => Carbon::now(),
            ],

            // Sample 2: Quarterly Summary
            [
                'summary_code' => $this->generateCode(2),
                'event_id' => null,
                'title' => 'Q1 2025 - Spiritual Program Summary',
                'summary_type' => 'quarterly',
                'period_start' => Carbon::create(2025, 1, 1),
                'period_end' => Carbon::create(2025, 3, 31),
                'report_date' => Carbon::create(2025, 4, 1),
                'executive_overview' => "Kuartal pertama tahun 2025 mencatat pencapaian signifikan dalam program spiritual dengan total 45 kegiatan yang terlaksana.",
                'key_highlights' => "- 45 kegiatan spiritual terlaksana\n- 1,200+ peserta total\n- Satisfaction score 4.6/5\n- ROI positif 25%",
                'achievements' => "- Program tahfidz mencapai target 80%\n- Kajian rutin dihadiri rata-rata 100 orang\n- Dana sosial terdistribusi tepat sasaran",
                'challenges' => "- Fluktuasi kehadiran di beberapa program\n- Kendala teknis di acara online",
                'recommendations' => "- Variasi format acara untuk meningkatkan engagement\n- Upgrade infrastruktur IT\n- Survey berkala untuk feedback peserta",
                'total_income' => 450000000,
                'total_expenses' => 360000000,
                'net_result' => 90000000,
                'budget_utilization_percentage' => 80.00,
                'events_conducted' => 45,
                'total_participants' => 1200,
                'satisfaction_score' => 4.6,
                'status' => 'approved',
                'created_by' => $user->id,
                'reviewed_by' => $user->id,
                'reviewed_at' => Carbon::now()->subDays(2),
                'approved_by' => $user->id,
                'approved_at' => Carbon::now()->subDay(),
            ],

            // Sample 3: Event Summary (Draft)
            [
                'summary_code' => $this->generateCode(3),
                'event_id' => null, // Will be set later if Event exists
                'title' => 'Tarawih Berjamaah - Night 27 Report',
                'summary_type' => 'event',
                'period_start' => Carbon::now()->subDays(3),
                'period_end' => Carbon::now()->subDays(3),
                'report_date' => Carbon::now(),
                'executive_overview' => "Tarawih berjamaah malam ke-27 Ramadhan dilaksanakan dengan khusyuk dan dihadiri jamaah dalam jumlah besar.",
                'key_highlights' => "- Kehadiran 350 jamaah\n- Khataman Al-Quran tercapai\n- Distribusi zakat fitrah lancar\n- Budget sesuai rencana",
                'achievements' => "- Jamaah sangat antusias\n- Tidak ada kendala teknis\n- Konsumsi tercukupi dengan baik",
                'challenges' => "- Parkir kurang memadai\n- Beberapa jamaah datang terlambat",
                'recommendations' => "- Koordinasi dengan security untuk parkir\n- Reminder lebih awal kepada jamaah",
                'total_income' => 25000000,
                'total_expenses' => 20000000,
                'net_result' => 5000000,
                'budget_utilization_percentage' => 80.00,
                'events_conducted' => 1,
                'total_participants' => 350,
                'satisfaction_score' => 4.7,
                'status' => 'draft',
                'created_by' => $user->id,
            ],

            // Sample 4: Annual Summary (Under Review)
            [
                'summary_code' => $this->generateCode(4),
                'event_id' => null,
                'title' => 'Annual Report 2024 - Complete Overview',
                'summary_type' => 'annual',
                'period_start' => Carbon::create(2024, 1, 1),
                'period_end' => Carbon::create(2024, 12, 31),
                'report_date' => Carbon::create(2025, 1, 15),
                'executive_overview' => "Tahun 2024 merupakan tahun yang penuh berkah dengan berbagai pencapaian luar biasa dalam program-program spiritual dan sosial.",
                'key_highlights' => "- 180+ kegiatan sepanjang tahun\n- 5,000+ partisipan total\n- Pendapatan naik 30% dari 2023\n- Profit margin 22%\n- Average satisfaction 4.5/5",
                'achievements' => "- Launching program tahfidz online\n- Partnership dengan 5 masjid baru\n- Renovasi musholla selesai\n- Program beasiswa untuk 50 santri\n- Distribusi bantuan ke 200 keluarga",
                'challenges' => "- Pandemi masih mempengaruhi beberapa program\n- Keterbatasan SDM di beberapa divisi\n- Budget marketing perlu ditingkatkan",
                'recommendations' => "- Rekrutmen volunteer untuk 2025\n- Digitalisasi semua program\n- Peningkatan marketing budget 15%\n- Pembentukan tim IT internal\n- Pelatihan rutin untuk pengurus",
                'total_income' => 1800000000,
                'total_expenses' => 1400000000,
                'net_result' => 400000000,
                'budget_utilization_percentage' => 77.78,
                'events_conducted' => 180,
                'total_participants' => 5000,
                'satisfaction_score' => 4.5,
                'status' => 'under_review',
                'created_by' => $user->id,
            ],

            // Sample 5: Event Summary (Published)
            [
                'summary_code' => $this->generateCode(5),
                'event_id' => null, // Will be set later if Event exists
                'title' => 'Buka Puasa Bersama Komunitas',
                'summary_type' => 'event',
                'period_start' => Carbon::now()->subDays(5),
                'period_end' => Carbon::now()->subDays(5),
                'report_date' => Carbon::now()->subDays(4),
                'executive_overview' => "Acara buka puasa bersama komunitas berlangsung sukses dengan kehadiran yang melebihi ekspektasi.",
                'key_highlights' => "- 450 peserta hadir (target 300)\n- 100% peserta puas\n- Donasi terkumpul Rp 15 juta\n- Media coverage di 3 outlet",
                'achievements' => "- Acara berjalan tepat waktu\n- Konsumsi berkualitas dan halal\n- Dokumentasi lengkap\n- Networking yang baik",
                'challenges' => "- Venue hampir tidak cukup\n- Sound system sempat bermasalah",
                'recommendations' => "- Booking venue lebih besar untuk tahun depan\n- Backup sound system\n- Pendaftaran online untuk kontrol jumlah",
                'total_income' => 35000000,
                'total_expenses' => 25000000,
                'net_result' => 10000000,
                'budget_utilization_percentage' => 71.43,
                'events_conducted' => 1,
                'total_participants' => 450,
                'satisfaction_score' => 4.9,
                'status' => 'published',
                'created_by' => $user->id,
                'reviewed_by' => $user->id,
                'reviewed_at' => Carbon::now()->subDays(3),
                'approved_by' => $user->id,
                'approved_at' => Carbon::now()->subDays(2),
            ],
        ];

        // Insert samples
        foreach ($samples as $sample) {
            // Try to assign event_id if exists
            if ($sample['summary_type'] === 'event' && Event::count() > 0) {
                $event = Event::inRandomOrder()->first();
                if ($event) {
                    $sample['event_id'] = $event->id;
                }
            }

            DB::table('executive_summaries')->insert(array_merge($sample, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        $this->command->info('✅ Executive Summaries seeded successfully!');
        $this->command->info('   - 5 sample summaries created');
        $this->command->info('   - Statuses: 2 Published, 1 Approved, 1 Under Review, 1 Draft');
    }

    /**
     * Generate summary code
     */
    private function generateCode($number): string
    {
        $year = now()->year;
        return 'ES-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
