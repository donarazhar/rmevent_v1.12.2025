<?php

namespace Database\Seeders;

use App\Models\ProgressReport;
use App\Models\Event;
use App\Models\CommitteeStructure;
use App\Models\ProjectTimeline;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgressReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get required data
        $events = Event::all();
        $structures = CommitteeStructure::all();
        $timelines = ProjectTimeline::all();
        $users = User::all();

        if ($events->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please run EventSeeder and UserSeeder first!');
            return;
        }

        $this->command->info('Seeding Progress Reports...');

        $reportTypes = ['daily', 'weekly', 'monthly', 'milestone', 'ad_hoc'];
        $reportCounter = 1;

        foreach ($events as $event) {
            // Create multiple reports per event
            $numberOfReports = rand(5, 10);

            for ($i = 0; $i < $numberOfReports; $i++) {
                $reportType = $reportTypes[array_rand($reportTypes)];
                $creator = $users->random();

                // Determine period based on report type
                $periodStart = now()->subDays(rand(1, 60));
                $periodEnd = match ($reportType) {
                    'daily' => $periodStart->copy()->addDay(),
                    'weekly' => $periodStart->copy()->addWeek(),
                    'monthly' => $periodStart->copy()->addMonth(),
                    'milestone' => $periodStart->copy()->addDays(rand(7, 30)),
                    'ad_hoc' => $periodStart->copy()->addDays(rand(1, 14)),
                };

                $reportDate = $periodEnd->copy()->addDays(rand(0, 3));

                // Random metrics
                $tasksPlanned = rand(5, 30);
                $tasksCompleted = rand(0, $tasksPlanned);
                $tasksDelayed = rand(0, $tasksPlanned - $tasksCompleted);
                $overallProgress = rand(0, 100);

                $budgetAllocated = rand(5000000, 50000000);
                $budgetUsed = rand(0, $budgetAllocated);
                $budgetVariance = $budgetAllocated - $budgetUsed;

                // Generate report code
                $reportCode = $this->generateReportCode($reportDate, $reportCounter++);

                // Determine status and workflow
                $statusRand = rand(1, 100);
                if ($statusRand <= 20) {
                    $status = 'draft';
                    $submittedTo = null;
                    $submittedAt = null;
                    $approvedBy = null;
                    $approvedAt = null;
                    $approvalNotes = null;
                    $reviewerFeedback = null;
                } elseif ($statusRand <= 35) {
                    $status = 'submitted';
                    $reviewer = $users->where('id', '!=', $creator->id)->random();
                    $submittedTo = $reviewer->id;
                    $submittedAt = $reportDate->copy()->addHours(rand(1, 48));
                    $approvedBy = null;
                    $approvedAt = null;
                    $approvalNotes = null;
                    $reviewerFeedback = null;
                } elseif ($statusRand <= 50) {
                    $status = 'rejected';
                    $reviewer = $users->where('id', '!=', $creator->id)->random();
                    $submittedTo = $reviewer->id;
                    $submittedAt = $reportDate->copy()->addHours(rand(1, 48));
                    $approvedBy = null;
                    $approvedAt = null;
                    $approvalNotes = null;
                    $reviewerFeedback = $this->generateRejectionFeedback();
                } else {
                    $status = 'approved';
                    $reviewer = $users->where('id', '!=', $creator->id)->random();
                    $submittedTo = $reviewer->id;
                    $submittedAt = $reportDate->copy()->addHours(rand(1, 48));
                    $approvedBy = $reviewer->id;
                    $approvedAt = $submittedAt->copy()->addHours(rand(1, 72));
                    $approvalNotes = rand(1, 100) > 50 ? $this->generateApprovalNotes() : null;
                    $reviewerFeedback = null;
                }

                // Create dummy attachments for some reports
                $attachments = rand(1, 100) > 60 ? $this->generateDummyAttachments() : null;

                $report = ProgressReport::create([
                    'event_id' => $event->id,
                    'structure_id' => $structures->isNotEmpty() && rand(1, 100) > 30 ? $structures->random()->id : null,
                    'timeline_id' => $timelines->isNotEmpty() && rand(1, 100) > 40 ? $timelines->random()->id : null,
                    'report_code' => $reportCode,
                    'title' => $this->generateReportTitle($reportType, $event->title, $i + 1),
                    'report_type' => $reportType,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                    'report_date' => $reportDate,
                    'executive_summary' => $this->generateExecutiveSummary($overallProgress),
                    'activities_completed' => $this->generateActivitiesCompleted($reportType),
                    'ongoing_activities' => $this->generateOngoingActivities($reportType),
                    'planned_activities' => $this->generatePlannedActivities($reportType),
                    'issues_challenges' => rand(1, 100) > 40 ? $this->generateIssuesChallenges() : null,
                    'solutions_recommendations' => rand(1, 100) > 40 ? $this->generateSolutionsRecommendations() : null,
                    'overall_progress' => $overallProgress,
                    'tasks_planned' => $tasksPlanned,
                    'tasks_completed' => $tasksCompleted,
                    'tasks_delayed' => $tasksDelayed,
                    'budget_allocated' => $budgetAllocated,
                    'budget_used' => $budgetUsed,
                    'budget_variance' => $budgetVariance,
                    'team_members_involved' => rand(5, 25),
                    'hours_spent' => rand(20, 200),
                    'attachments' => $attachments,
                    'status' => $status,
                    'created_by' => $creator->id,
                    'submitted_to' => $submittedTo,
                    'approved_by' => $approvedBy,
                    'submitted_at' => $submittedAt,
                    'approved_at' => $approvedAt,
                    'reviewer_feedback' => $reviewerFeedback,
                    'approval_notes' => $approvalNotes,
                    'created_at' => $reportDate,
                    'updated_at' => $approvedAt ?? $submittedAt ?? $reportDate,
                ]);

                $this->command->info("Created report: {$report->report_code} - {$report->title} ({$status})");
            }
        }

        $totalReports = ProgressReport::count();
        $this->command->info("✓ Successfully created {$totalReports} progress reports");
        $this->command->info("  - Draft: " . ProgressReport::where('status', 'draft')->count());
        $this->command->info("  - Submitted: " . ProgressReport::where('status', 'submitted')->count());
        $this->command->info("  - Approved: " . ProgressReport::where('status', 'approved')->count());
        $this->command->info("  - Rejected: " . ProgressReport::where('status', 'rejected')->count());
    }

    /**
     * Generate report code
     */
    private function generateReportCode($date, $counter): string
    {
        return 'PR-' . $date->format('Ym') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate report title
     */
    private function generateReportTitle(string $type, string $eventName, int $number): string
    {
        $titles = [
            'daily' => "Daily Progress Report - Day {$number}",
            'weekly' => "Weekly Progress Report - Week {$number}",
            'monthly' => "Monthly Progress Report - Month {$number}",
            'milestone' => "Milestone Progress Report #{$number}",
            'ad_hoc' => "Ad Hoc Progress Report #{$number}",
        ];

        return $titles[$type] . " - " . substr($eventName, 0, 30);
    }

    /**
     * Generate executive summary
     */
    private function generateExecutiveSummary(int $progress): string
    {
        $summaries = [
            "Progress keseluruhan proyek mencapai {$progress}% dari target. Tim telah menyelesaikan beberapa milestone penting dan sedang fokus pada aktivitas prioritas tinggi. Koordinasi antar divisi berjalan lancar dan semua anggota tim menunjukkan komitmen yang baik.",

            "Periode pelaporan ini menunjukkan kemajuan signifikan dengan pencapaian {$progress}%. Beberapa tantangan telah berhasil diatasi melalui koordinasi yang baik antar tim. Kami optimis dapat mencapai target sesuai timeline yang ditentukan.",

            "Tim telah mencapai progress {$progress}% dengan menyelesaikan berbagai aktivitas kunci. Meskipun ada beberapa kendala minor, secara keseluruhan proyek berjalan sesuai rencana. Kolaborasi tim dan dukungan stakeholder sangat membantu kelancaran pelaksanaan.",

            "Laporan progress menunjukkan pencapaian {$progress}% dari keseluruhan target. Beberapa inovasi dan improvement process telah diterapkan untuk meningkatkan efisiensi kerja. Tim terus berkomitmen untuk memberikan hasil terbaik.",
        ];

        return $summaries[array_rand($summaries)];
    }

    /**
     * Generate activities completed
     */
    private function generateActivitiesCompleted(string $type): string
    {
        $activities = [
            "✓ Menyelesaikan koordinasi dengan vendor dan supplier utama\n✓ Finalisasi desain dan layout venue acara\n✓ Pengadaan peralatan dan perlengkapan acara\n✓ Rapat koordinasi tim dan pembagian tugas detail\n✓ Pembuatan rundown acara dan technical meeting",

            "✓ Konfirmasi pembicara dan narasumber acara\n✓ Persiapan materi publikasi dan promosi\n✓ Survey lokasi dan pengecekan teknis venue\n✓ Penyusunan budget detail per divisi\n✓ Koordinasi dengan pihak keamanan dan protokol",

            "✓ Pembuatan proposal sponsorship dan pendanaan\n✓ Desain merchandise dan souvenir acara\n✓ Rapat evaluasi progress dengan steering committee\n✓ Finalisasi konsep dekorasi dan tata panggung\n✓ Pendataan peserta dan registrasi online",

            "✓ Penyusunan SOP dan prosedur teknis acara\n✓ Training dan briefing untuk volunteer dan panitia\n✓ Pengadaan konsumsi dan catering\n✓ Pembuatan media promosi digital dan cetak\n✓ Koordinasi transportasi dan akomodasi",
        ];

        return $activities[array_rand($activities)];
    }

    /**
     * Generate ongoing activities
     */
    private function generateOngoingActivities(string $type): string
    {
        $activities = [
            "⚡ Proses negosiasi dengan beberapa vendor pilihan\n⚡ Pengembangan website dan sistem registrasi online\n⚡ Koordinasi dengan media partner untuk publikasi\n⚡ Persiapan konten untuk social media campaign\n⚡ Review dan approval budget dengan finance team",

            "⚡ Finalisasi kontrak kerjasama dengan sponsor utama\n⚡ Produksi materi promosi dan marketing collateral\n⚡ Pengembangan aplikasi mobile untuk peserta\n⚡ Koordinasi teknis dengan tim audio visual\n⚡ Persiapan dokumentasi dan coverage acara",

            "⚡ Monitoring progress tiap divisi secara berkala\n⚡ Koordinasi dengan stakeholder eksternal\n⚡ Pengadaan dan persiapan door prize\n⚡ Setup sistem ticketing dan RSVP\n⚡ Persiapan protokol kesehatan dan keamanan",

            "⚡ Review dan revisi rundown acara\n⚡ Koordinasi dengan tim kreatif untuk content creation\n⚡ Pengaturan logistik dan distribusi merchandise\n⚡ Testing sistem registrasi dan check-in\n⚡ Persiapan backup plan dan contingency",
        ];

        return $activities[array_rand($activities)];
    }

    /**
     * Generate planned activities
     */
    private function generatePlannedActivities(string $type): string
    {
        $activities = [
            "📋 Technical meeting dengan seluruh vendor dan supplier\n📋 Gladi bersih dan simulasi acara lengkap\n📋 Final check semua peralatan dan perlengkapan\n📋 Briefing akhir untuk seluruh panitia dan volunteer\n📋 Launching campaign promosi fase final",

            "📋 Koordinasi final dengan pembicara dan narasumber\n📋 Persiapan goodie bag dan welcome kit peserta\n📋 Testing sistem teknologi dan infrastruktur\n📋 Rapat koordinasi dengan tim keamanan\n📋 Finalisasi dekorasi dan setup venue",

            "📋 Pelaksanaan dry run acara secara menyeluruh\n📋 Distribusi tugas shift dan jadwal panitia\n📋 Persiapan media center dan press conference\n📋 Setup registration desk dan information counter\n📋 Final briefing dengan seluruh tim",

            "📋 Quality control semua deliverables\n📋 Koordinasi dengan pihak berwenang terkait perizinan\n📋 Persiapan evacuation plan dan emergency response\n📋 Setup live streaming dan virtual participation\n📋 Final meeting dengan steering committee",
        ];

        return $activities[array_rand($activities)];
    }

    /**
     * Generate issues and challenges
     */
    private function generateIssuesChallenges(): string
    {
        $issues = [
            "⚠️ Keterlambatan konfirmasi dari beberapa vendor karena jadwal yang padat\n⚠️ Budget terbatas untuk beberapa item yang tidak terprediksi sebelumnya\n⚠️ Kendala koordinasi karena perbedaan jadwal antar divisi\n⚠️ Perubahan regulasi yang memerlukan penyesuaian konsep acara",

            "⚠️ Kesulitan mencari venue dengan spesifikasi yang sesuai dan harga terjangkau\n⚠️ Respons peserta untuk registrasi awal masih di bawah ekspektasi\n⚠️ Keterbatasan SDM untuk beberapa posisi krusial\n⚠️ Kendala teknis dalam pengembangan sistem online",

            "⚠️ Perubahan mendadak dari salah satu sponsor utama\n⚠️ Cuaca tidak menentu yang dapat mempengaruhi outdoor setup\n⚠️ Kompleksitas perizinan yang memerlukan waktu lebih lama\n⚠️ Komunikasi yang belum optimal antar sub-divisi",

            "⚠️ Keterbatasan waktu persiapan untuk beberapa deliverables\n⚠️ Perbedaan ekspektasi dengan beberapa stakeholder\n⚠️ Kendala logistik pengiriman merchandise\n⚠️ Keterbatasan ruang untuk penyimpanan peralatan",
        ];

        return $issues[array_rand($issues)];
    }

    /**
     * Generate solutions and recommendations
     */
    private function generateSolutionsRecommendations(): string
    {
        $solutions = [
            "✅ Mempercepat proses negosiasi dengan memberikan deadline yang jelas kepada vendor\n✅ Realokasi budget dari pos yang under-utilized\n✅ Implementasi project management tool untuk koordinasi yang lebih baik\n✅ Mengadakan rapat rutin mingguan untuk monitoring progress",

            "✅ Memperluas pencarian venue dengan kriteria yang lebih fleksibel\n✅ Intensifikasi promosi melalui berbagai channel digital dan offline\n✅ Rekrutmen volunteer tambahan melalui kampus dan organisasi\n✅ Konsultasi dengan IT consultant untuk troubleshooting sistem",

            "✅ Mencari alternatif sponsor dan memperkuat proposal value\n✅ Mempersiapkan backup plan indoor untuk antisipasi cuaca\n✅ Mendelegasikan tim khusus untuk follow-up perizinan\n✅ Mengoptimalkan penggunaan grup komunikasi dan regular sync-up",

            "✅ Membuat timeline yang lebih detail dengan buffer time\n✅ Melakukan stakeholder meeting untuk alignment ekspektasi\n✅ Koordinasi langsung dengan ekspedisi untuk tracking pengiriman\n✅ Mencari temporary storage atau mengoptimalkan existing space",
        ];

        return $solutions[array_rand($solutions)];
    }

    /**
     * Generate rejection feedback
     */
    private function generateRejectionFeedback(): string
    {
        $feedbacks = [
            "Report perlu dilengkapi dengan data metrics yang lebih detail. Mohon tambahkan breakdown budget per kategori dan timeline aktivitas yang lebih spesifik.",

            "Beberapa bagian report masih kurang jelas dan perlu elaborasi lebih lanjut. Silakan perbaiki executive summary dan tambahkan evidence/dokumentasi untuk aktivitas yang sudah completed.",

            "Data budget variance perlu dijelaskan lebih detail. Mohon sertakan justifikasi untuk selisih budget dan action plan untuk optimization.",

            "Report ini belum mencantumkan risk assessment dan mitigation plan. Mohon tambahkan analisis risiko dan langkah-langkah antisipasi yang akan diambil.",

            "Perlu ada update terkait coordination dengan stakeholder eksternal. Silakan lengkapi report dengan status komunikasi dan agreement yang sudah dicapai.",
        ];

        return $feedbacks[array_rand($feedbacks)];
    }

    /**
     * Generate approval notes
     */
    private function generateApprovalNotes(): string
    {
        $notes = [
            "Report sudah sangat baik dan komprehensif. Keep up the good work! 👍",
            "Excellent progress! Tim menunjukkan performa yang luar biasa. Lanjutkan momentum ini.",
            "Report approved. Harap terus monitor budget utilization dan laporkan jika ada perubahan signifikan.",
            "Very detailed and informative report. Terima kasih atas kerja keras tim!",
            "Approved. Perhatikan beberapa catatan minor yang sudah didiskusikan dan implementasikan untuk report berikutnya.",
        ];

        return $notes[array_rand($notes)];
    }

    /**
     * Generate dummy attachments
     */
    private function generateDummyAttachments(): array
    {
        $attachmentTypes = [
            ['name' => 'Budget_Report.xlsx', 'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'size' => rand(50000, 500000)],
            ['name' => 'Progress_Documentation.pdf', 'type' => 'application/pdf', 'size' => rand(100000, 1000000)],
            ['name' => 'Meeting_Minutes.docx', 'type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'size' => rand(30000, 300000)],
            ['name' => 'Timeline_Chart.png', 'type' => 'image/png', 'size' => rand(200000, 2000000)],
            ['name' => 'Vendor_Quotation.pdf', 'type' => 'application/pdf', 'size' => rand(80000, 800000)],
            ['name' => 'Activity_Photos.zip', 'type' => 'application/zip', 'size' => rand(5000000, 15000000)],
        ];

        $numberOfAttachments = rand(1, 3);
        $selectedAttachments = [];

        for ($i = 0; $i < $numberOfAttachments; $i++) {
            $attachment = $attachmentTypes[array_rand($attachmentTypes)];
            $attachment['path'] = 'progress-reports/dummy-' . uniqid() . '-' . $attachment['name'];
            $selectedAttachments[] = $attachment;
        }

        return $selectedAttachments;
    }
}
