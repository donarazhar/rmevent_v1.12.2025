<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proposal;
use App\Models\Event;
use App\Models\User;
use App\Models\CommitteeStructure;
use Carbon\Carbon;

class ProposalSeeder extends Seeder
{
    /**
     * Counter for proposal code generation
     */
    private $codeCounter = 1;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample data
        $events = Event::all();
        $structures = CommitteeStructure::all();
        $users = User::all();

        // If no users exist, we can't seed proposals
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $creator = $users->first();
        $approver = $users->count() > 1 ? $users->skip(1)->first() : $creator;

        // Initialize counter from existing proposals
        $latestProposal = Proposal::whereYear('created_at', now()->year)
            ->latest('id')
            ->first();

        if ($latestProposal && $latestProposal->proposal_code) {
            $this->codeCounter = ((int) substr($latestProposal->proposal_code, -3)) + 1;
        }

        $this->command->info('Creating proposals...');

        // 1. Draft Proposals (5)
        $this->createDraftProposals($creator, $events, $structures);

        // 2. Submitted Proposals (3)
        $this->createSubmittedProposals($creator, $events, $structures);

        // 3. Under Review Proposals (2)
        $this->createUnderReviewProposals($creator, $approver, $events, $structures);

        // 4. Approved Proposals (5)
        $this->createApprovedProposals($creator, $approver, $events, $structures);

        // 5. Rejected Proposals (2)
        $this->createRejectedProposals($creator, $approver, $events, $structures);

        // 6. Revision Needed Proposals (2)
        $this->createRevisionNeededProposals($creator, $approver, $events, $structures);

        // 7. Overdue Proposal (1)
        $this->createOverdueProposal($creator, $events, $structures);

        $this->command->info('✅ Proposals seeded successfully!');
        $this->command->info('Total: ' . Proposal::count() . ' proposals created');
    }

    /**
     * Generate proposal code manually
     */
    private function generateProposalCode(): string
    {
        $year = now()->year;
        $code = 'PROP-' . $year . '-' . str_pad($this->codeCounter, 3, '0', STR_PAD_LEFT);
        $this->codeCounter++;
        return $code;
    }

    /**
     * Create draft proposals
     */
    private function createDraftProposals($creator, $events, $structures)
    {
        $proposals = [
            [
                'title' => 'Proposal Sponsorship Paket Ramadhan 1447H',
                'type' => Proposal::TYPE_SPONSORSHIP,
                'description' => 'Proposal sponsorship untuk mendapatkan dukungan paket ramadhan dari mitra strategis',
                'submitted_to' => 'PT. Berkah Ramadhan Indonesia',
                'recipient_contact' => 'Budi Santoso - 081234567890',
                'recipient_email' => 'budi.santoso@berkahramadhan.co.id',
                'requested_amount' => 50000000,
                'executive_summary' => 'Program paket ramadhan untuk 500 keluarga prasejahtera di wilayah Jakarta Timur dengan target distribusi pada minggu kedua Ramadhan.',
                'objectives' => "1. Menyalurkan paket ramadhan kepada 500 keluarga prasejahtera\n2. Menjalin kerjasama strategis dengan mitra corporate\n3. Meningkatkan kepedulian sosial di bulan Ramadhan",
            ],
            [
                'title' => 'Proposal Kerjasama Acara Buka Puasa Bersama',
                'type' => Proposal::TYPE_PARTNERSHIP,
                'description' => 'Proposal kerjasama penyelenggaraan buka puasa bersama dengan masjid Al-Ikhlas',
                'submitted_to' => 'Pengurus Masjid Al-Ikhlas',
                'recipient_email' => 'masjid.alikhlas@gmail.com',
                'requested_amount' => 15000000,
                'executive_summary' => 'Penyelenggaraan buka puasa bersama untuk 200 jamaah dengan konsep sederhana namun berkah.',
            ],
            [
                'title' => 'Proposal Pendanaan Santunan Yatim Piatu',
                'type' => Proposal::TYPE_FUNDING,
                'description' => 'Proposal pendanaan untuk program santunan yatim piatu bulanan',
                'submitted_to' => 'Yayasan Peduli Anak Negeri',
                'recipient_contact' => 'Ibu Siti Aminah',
                'recipient_email' => 'info@pedulianakenegeri.org',
                'requested_amount' => 30000000,
                'background' => 'Terdapat 100 anak yatim piatu di wilayah Jakarta Selatan yang membutuhkan bantuan rutin untuk kebutuhan pendidikan dan kesehatan.',
                'objectives' => "1. Memberikan santunan bulanan kepada 100 anak yatim\n2. Memastikan kebutuhan pendidikan terpenuhi\n3. Monitoring kesehatan anak yatim secara berkala",
            ],
            [
                'title' => 'Proposal Kegiatan Tadarus Keliling',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Proposal untuk mengadakan tadarus Al-Quran keliling di 10 masjid',
                'requested_amount' => 8000000,
                'budget_overview' => "- Konsumsi: Rp 4.000.000\n- Transport Ustadz: Rp 2.000.000\n- Publikasi: Rp 1.000.000\n- Operasional: Rp 1.000.000",
            ],
            [
                'title' => 'Proposal Renovasi Mushola Kampus',
                'type' => Proposal::TYPE_PROJECT,
                'description' => 'Proposal renovasi dan perluasan mushola kampus untuk menampung jamaah yang semakin banyak',
                'submitted_to' => 'Rektor Universitas Islam Jakarta',
                'requested_amount' => 100000000,
                'timeline' => "Minggu 1-2: Persiapan dan perizinan\nMinggu 3-6: Pelaksanaan renovasi\nMinggu 7-8: Finishing dan peresmian",
            ],
        ];

        foreach ($proposals as $data) {
            // MANUAL GENERATE proposal_code
            $data['proposal_code'] = $this->generateProposalCode();
            $data['created_by'] = $creator->id;
            $data['status'] = Proposal::STATUS_DRAFT;

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            Proposal::create($data);
        }

        $this->command->info('✓ Created 5 draft proposals');
    }

    /**
     * Create submitted proposals
     */
    private function createSubmittedProposals($creator, $events, $structures)
    {
        $proposals = [
            [
                'title' => 'Proposal Kajian Ramadhan Mingguan',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Proposal penyelenggaraan kajian ramadhan setiap minggu dengan tema berbeda',
                'submitted_to' => 'Pengurus DKM Masjid Baitul Jannah',
                'requested_amount' => 12000000,
                'executive_summary' => 'Kajian ramadhan mingguan dengan 4 kali pertemuan, menghadirkan ustadz kompeten dengan tema seputar ibadah ramadhan.',
                'objectives' => "1. Meningkatkan pemahaman ibadah ramadhan\n2. Membangun komunitas yang aktif\n3. Menyediakan kajian berkualitas",
            ],
            [
                'title' => 'Proposal Media Partnership Portal Ramadhan',
                'type' => Proposal::TYPE_PARTNERSHIP,
                'description' => 'Kerjasama dengan portal media untuk publikasi kegiatan ramadhan',
                'submitted_to' => 'Redaksi Portal Ramadhan.id',
                'recipient_email' => 'redaksi@ramadhan.id',
                'requested_amount' => 5000000,
            ],
            [
                'title' => 'Proposal Bantuan Modal Usaha Ibu-ibu',
                'type' => Proposal::TYPE_FUNDING,
                'description' => 'Program pemberian modal usaha untuk ibu-ibu di lingkungan masjid',
                'submitted_to' => 'Dinas Pemberdayaan Perempuan Jakarta',
                'requested_amount' => 25000000,
                'expected_outcomes' => 'Terbentuknya 20 usaha mikro yang mandiri dan berkelanjutan di kalangan ibu-ibu jamaah masjid.',
            ],
        ];

        foreach ($proposals as $data) {
            $submittedAt = now()->subDays(rand(1, 7));

            $data['proposal_code'] = $this->generateProposalCode();
            $data['created_by'] = $creator->id;
            $data['status'] = Proposal::STATUS_SUBMITTED;
            $data['submitted_by'] = $creator->id;
            $data['submitted_at'] = $submittedAt;
            $data['submission_date'] = $submittedAt->toDateString();
            $data['response_deadline'] = now()->addDays(rand(7, 21));

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            Proposal::create($data);
        }

        $this->command->info('✓ Created 3 submitted proposals');
    }

    /**
     * Create under review proposals
     */
    private function createUnderReviewProposals($creator, $approver, $events, $structures)
    {
        $proposals = [
            [
                'title' => 'Proposal Pelatihan Qiroah untuk Remaja',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Program pelatihan qiroah (baca Al-Quran) untuk remaja masjid',
                'submitted_to' => 'Takmir Masjid An-Nur',
                'requested_amount' => 7500000,
                'methodology' => 'Pelatihan dilakukan 2x seminggu selama 1 bulan dengan metode praktik langsung dan evaluasi berkala.',
            ],
            [
                'title' => 'Proposal Zakat Fitrah Management System',
                'type' => Proposal::TYPE_PROJECT,
                'description' => 'Pengembangan sistem digital untuk pengelolaan zakat fitrah',
                'requested_amount' => 18000000,
                'budget_overview' => "- Development: Rp 10.000.000\n- Server & Domain: Rp 3.000.000\n- Training: Rp 2.000.000\n- Maintenance: Rp 3.000.000",
            ],
        ];

        foreach ($proposals as $data) {
            $submittedAt = now()->subDays(rand(3, 10));
            $reviewedAt = now()->subDays(rand(1, 3));

            $data['proposal_code'] = $this->generateProposalCode();
            $data['created_by'] = $creator->id;
            $data['status'] = Proposal::STATUS_UNDER_REVIEW;
            $data['submitted_by'] = $creator->id;
            $data['submitted_at'] = $submittedAt;
            $data['submission_date'] = $submittedAt->toDateString();
            $data['reviewed_by'] = $approver->id;
            $data['reviewed_at'] = $reviewedAt;
            $data['response_deadline'] = now()->addDays(rand(10, 20));

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            Proposal::create($data);
        }

        $this->command->info('✓ Created 2 under review proposals');
    }

    /**
     * Create approved proposals
     */
    private function createApprovedProposals($creator, $approver, $events, $structures)
    {
        $proposals = [
            [
                'title' => 'Proposal Santunan Anak Yatim Ramadhan 1447H',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Program santunan untuk 150 anak yatim di wilayah Jakarta Barat',
                'submitted_to' => 'Baznas Jakarta Barat',
                'requested_amount' => 45000000,
                'approved_amount' => 45000000,
                'approval_notes' => 'Proposal disetujui sepenuhnya. Dana akan dicairkan dalam 2 tahap.',
            ],
            [
                'title' => 'Proposal Buka Puasa Bersama 1000 Anak Yatim',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Acara buka puasa bersama skala besar untuk anak yatim se-Jabodetabek',
                'submitted_to' => 'Kementerian Sosial RI',
                'requested_amount' => 75000000,
                'approved_amount' => 60000000,
                'approval_notes' => 'Disetujui dengan penyesuaian budget. Mohon koordinasi untuk technical meeting.',
            ],
            [
                'title' => 'Proposal Pembangunan Perpustakaan Mini Masjid',
                'type' => Proposal::TYPE_PROJECT,
                'description' => 'Pembangunan perpustakaan mini dengan koleksi buku-buku islami',
                'requested_amount' => 35000000,
                'approved_amount' => 35000000,
                'approval_notes' => 'Approved. Diharapkan selesai sebelum Ramadhan.',
            ],
            [
                'title' => 'Proposal Pelatihan Dai Muda',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Program pelatihan untuk dai muda dengan materi public speaking dan ilmu agama',
                'requested_amount' => 20000000,
                'approved_amount' => 18000000,
                'approval_notes' => 'Disetujui dengan pengurangan biaya operasional. Silakan mulai pelaksanaan.',
            ],
            [
                'title' => 'Proposal Sponsorship Program Tahfidz Intensif',
                'type' => Proposal::TYPE_SPONSORSHIP,
                'description' => 'Mencari sponsor untuk program tahfidz intensif 30 hari',
                'submitted_to' => 'Yayasan Tahfidz Al-Qur\'an Indonesia',
                'requested_amount' => 40000000,
                'approved_amount' => 40000000,
                'approval_notes' => 'Full approval. Perjanjian kerjasama akan ditandatangani minggu depan.',
            ],
        ];

        foreach ($proposals as $data) {
            $submittedAt = now()->subDays(rand(15, 30));
            $reviewedAt = now()->subDays(rand(7, 14));
            $approvedAt = now()->subDays(rand(1, 7));

            $data['proposal_code'] = $this->generateProposalCode();
            $data['created_by'] = $creator->id;
            $data['status'] = Proposal::STATUS_APPROVED;
            $data['submitted_by'] = $creator->id;
            $data['submitted_at'] = $submittedAt;
            $data['submission_date'] = $submittedAt->toDateString();
            $data['reviewed_by'] = $approver->id;
            $data['reviewed_at'] = $reviewedAt;
            $data['approved_by'] = $approver->id;
            $data['approved_at'] = $approvedAt;
            $data['approved_date'] = $approvedAt->toDateString();

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            Proposal::create($data);
        }

        $this->command->info('✓ Created 5 approved proposals');
    }

    /**
     * Create rejected proposals
     */
    private function createRejectedProposals($creator, $approver, $events, $structures)
    {
        $proposals = [
            [
                'title' => 'Proposal Event Musik Religi Outdoor',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Event musik religi outdoor dengan kapasitas 5000 orang',
                'submitted_to' => 'Dinas Pariwisata Jakarta',
                'requested_amount' => 150000000,
                'rejection_reason' => 'Budget terlalu besar dan kurang detail rincian penggunaan dana. Lokasi yang diajukan juga belum mendapat izin keramaian. Silakan revisi dan ajukan kembali dengan perbaikan.',
            ],
            [
                'title' => 'Proposal Ziarah Religi ke Luar Negeri',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Program ziarah religi ke Mesir dan Turki untuk 50 peserta',
                'requested_amount' => 500000000,
                'rejection_reason' => 'Proposal ditolak karena tidak sesuai dengan fokus program ramadhan tahun ini yang lebih mengutamakan kegiatan lokal dan pemberdayaan masyarakat sekitar.',
            ],
        ];

        foreach ($proposals as $data) {
            $submittedAt = now()->subDays(rand(10, 20));
            $reviewedAt = now()->subDays(rand(3, 10));

            $data['proposal_code'] = $this->generateProposalCode();
            $data['created_by'] = $creator->id;
            $data['status'] = Proposal::STATUS_REJECTED;
            $data['submitted_by'] = $creator->id;
            $data['submitted_at'] = $submittedAt;
            $data['submission_date'] = $submittedAt->toDateString();
            $data['reviewed_by'] = $approver->id;
            $data['reviewed_at'] = $reviewedAt;

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            Proposal::create($data);
        }

        $this->command->info('✓ Created 2 rejected proposals');
    }

    /**
     * Create revision needed proposals
     */
    private function createRevisionNeededProposals($creator, $approver, $events, $structures)
    {
        $proposals = [
            [
                'title' => 'Proposal Lomba Adzan dan Tahfidz Tingkat Nasional',
                'type' => Proposal::TYPE_EVENT,
                'description' => 'Penyelenggaraan lomba adzan dan tahfidz tingkat nasional',
                'requested_amount' => 80000000,
                'review_feedback' => "Proposal menarik, namun perlu perbaikan:\n1. Detail breakdown budget kurang lengkap\n2. Timeline pelaksanaan perlu diperjelas\n3. Tambahkan informasi juri dan narasumber\n4. Sertakan rencana publikasi dan promosi\n\nSilakan revisi dan submit ulang.",
            ],
            [
                'title' => 'Proposal Aplikasi Mobile Jadwal Sholat & Kajian',
                'type' => Proposal::TYPE_PROJECT,
                'description' => 'Pengembangan aplikasi mobile untuk jadwal sholat dan kajian masjid',
                'requested_amount' => 45000000,
                'review_feedback' => "Perlu revisi:\n1. Spesifikasi teknis aplikasi kurang detail\n2. Belum ada user requirement analysis\n3. Maintenance plan belum jelas\n4. Budget development perlu dirinci lebih detail\n\nSilakan lengkapi dokumen pendukung.",
            ],
        ];

        foreach ($proposals as $data) {
            $submittedAt = now()->subDays(rand(5, 15));
            $reviewedAt = now()->subDays(rand(1, 5));

            $data['proposal_code'] = $this->generateProposalCode();
            $data['created_by'] = $creator->id;
            $data['status'] = Proposal::STATUS_REVISION_NEEDED;
            $data['submitted_by'] = $creator->id;
            $data['submitted_at'] = $submittedAt;
            $data['submission_date'] = $submittedAt->toDateString();
            $data['reviewed_by'] = $approver->id;
            $data['reviewed_at'] = $reviewedAt;
            $data['response_deadline'] = now()->addDays(rand(7, 14));

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            Proposal::create($data);
        }

        $this->command->info('✓ Created 2 revision needed proposals');
    }

    /**
     * Create overdue proposal
     */
    private function createOverdueProposal($creator, $events, $structures)
    {
        $submittedAt = now()->subDays(25);

        $data = [
            'proposal_code' => $this->generateProposalCode(),
            'title' => 'Proposal Kerjasama CSR Bank Syariah',
            'type' => Proposal::TYPE_PARTNERSHIP,
            'description' => 'Proposal kerjasama CSR dengan bank syariah untuk program ramadhan',
            'submitted_to' => 'PT. Bank Syariah Mandiri',
            'recipient_contact' => 'Divisi CSR - 021-5551234',
            'recipient_email' => 'csr@banksyariahmandiri.co.id',
            'requested_amount' => 100000000,
            'executive_summary' => 'Program kerjasama CSR untuk pemberdayaan UMKM di kalangan jamaah masjid dengan fokus produk ramadhan.',
            'created_by' => $creator->id,
            'status' => Proposal::STATUS_SUBMITTED,
            'submitted_by' => $creator->id,
            'submitted_at' => $submittedAt,
            'submission_date' => $submittedAt->toDateString(),
            'response_deadline' => now()->subDays(5),
        ];

        if ($events->isNotEmpty()) {
            $data['event_id'] = $events->random()->id;
        }
        if ($structures->isNotEmpty()) {
            $data['structure_id'] = $structures->random()->id;
        }

        Proposal::create($data);

        $this->command->info('✓ Created 1 overdue proposal');
    }
}
