<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        // Get required data
        $events = Event::all();
        $users = User::all();
        
        if ($events->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please run EventSeeder and UserSeeder first!');
            return;
        }

        $admin = $users->where('role', 'admin')->first() ?? $users->first();
        $reviewer = $users->where('role', 'admin')->skip(1)->first() ?? $users->first();
        $currentYear = date('Y');

        $budgets = [
            // Budget 1: Approved Budget for Annual Event
            [
                'event_id' => $events->first()->id,
                'budget_code' => 'RAB-2024-001',
                'title' => 'Budget RAB Kegiatan Tahunan 2024',
                'description' => 'Rencana Anggaran Biaya untuk kegiatan tahunan organisasi tahun 2024',
                'fiscal_year' => '2024',
                'version' => 1,
                'parent_budget_id' => null,
                'total_planned' => 250000000.00,
                'total_approved' => 240000000.00,
                'total_allocated' => 180000000.00,
                'total_spent' => 85000000.00,
                'total_remaining' => 95000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 1),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'active',
                'submitted_by' => $admin->id,
                'submitted_at' => Carbon::create($currentYear, 1, 5, 10, 0),
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => Carbon::create($currentYear, 1, 8, 14, 30),
                'review_notes' => 'Budget sudah sesuai dengan kebutuhan. Disetujui untuk dilanjutkan.',
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 10, 9, 15),
                'approval_notes' => 'Disetujui dengan penyesuaian anggaran dari Rp 250jt menjadi Rp 240jt.',
                'revision_reason' => null,
                'rejection_reason' => null,
                'attachments' => json_encode([
                    ['name' => 'RAB_Detail_2024.pdf', 'url' => '/storage/budgets/rab_2024.pdf'],
                    ['name' => 'Justifikasi_Anggaran.xlsx', 'url' => '/storage/budgets/justification.xlsx']
                ]),
                'notes' => 'Budget ini mencakup seluruh kegiatan operasional dan event organisasi untuk tahun 2024',
                'created_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 1, 2, 8, 0),
                'updated_at' => Carbon::create($currentYear, 1, 10, 9, 15),
            ],

            // Budget 2: Under Review Budget
            [
                'event_id' => $events->skip(1)->first()->id ?? $events->first()->id,
                'budget_code' => 'RAB-2024-002',
                'title' => 'Budget Seminar Nasional IT 2024',
                'description' => 'Anggaran untuk penyelenggaraan seminar nasional teknologi informasi',
                'fiscal_year' => '2024',
                'version' => 1,
                'parent_budget_id' => null,
                'total_planned' => 150000000.00,
                'total_approved' => 0.00,
                'total_allocated' => 0.00,
                'total_spent' => 0.00,
                'total_remaining' => 0.00,
                'valid_from' => Carbon::create($currentYear, 3, 1),
                'valid_until' => Carbon::create($currentYear, 3, 31),
                'status' => 'under_review',
                'submitted_by' => $admin->id,
                'submitted_at' => Carbon::create($currentYear, 2, 1, 10, 0),
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => null,
                'review_notes' => null,
                'approved_by' => null,
                'approved_at' => null,
                'approval_notes' => null,
                'revision_reason' => null,
                'rejection_reason' => null,
                'attachments' => json_encode([
                    ['name' => 'Proposal_Seminar.pdf', 'url' => '/storage/budgets/proposal_seminar.pdf']
                ]),
                'notes' => 'Menunggu review dari tim finance',
                'created_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 1, 28, 14, 30),
                'updated_at' => Carbon::create($currentYear, 2, 1, 10, 0),
            ],

            // Budget 3: Draft Budget
            [
                'event_id' => $events->skip(2)->first()->id ?? $events->first()->id,
                'budget_code' => 'RAB-2024-003',
                'title' => 'Budget Workshop Internal Q2 2024',
                'description' => 'Anggaran untuk workshop peningkatan kapasitas internal di Q2',
                'fiscal_year' => '2024',
                'version' => 1,
                'parent_budget_id' => null,
                'total_planned' => 75000000.00,
                'total_approved' => 0.00,
                'total_allocated' => 0.00,
                'total_spent' => 0.00,
                'total_remaining' => 0.00,
                'valid_from' => Carbon::create($currentYear, 4, 1),
                'valid_until' => Carbon::create($currentYear, 6, 30),
                'status' => 'draft',
                'submitted_by' => null,
                'submitted_at' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_notes' => null,
                'approved_by' => null,
                'approved_at' => null,
                'approval_notes' => null,
                'revision_reason' => null,
                'rejection_reason' => null,
                'attachments' => null,
                'notes' => 'Masih dalam tahap penyusunan draft awal',
                'created_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 15, 9, 0),
                'updated_at' => Carbon::create($currentYear, 2, 15, 9, 0),
            ],

            // Budget 4: Revised Budget (Version 2)
            [
                'event_id' => $events->first()->id,
                'budget_code' => 'RAB-2024-004',
                'title' => 'Budget RAB Kegiatan Tahunan 2024 (Revisi 1)',
                'description' => 'Revisi budget kegiatan tahunan karena ada penambahan program baru',
                'fiscal_year' => '2024',
                'version' => 2,
                'parent_budget_id' => 1, // Refers to RAB-2024-001
                'total_planned' => 280000000.00,
                'total_approved' => 0.00,
                'total_allocated' => 0.00,
                'total_spent' => 0.00,
                'total_remaining' => 0.00,
                'valid_from' => Carbon::create($currentYear, 1, 1),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'submitted',
                'submitted_by' => $admin->id,
                'submitted_at' => Carbon::create($currentYear, 2, 20, 11, 0),
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_notes' => null,
                'approved_by' => null,
                'approved_at' => null,
                'approval_notes' => null,
                'revision_reason' => 'Penambahan program baru: Kegiatan CSR dan Partnership dengan industri',
                'rejection_reason' => null,
                'attachments' => json_encode([
                    ['name' => 'RAB_Revisi_1.pdf', 'url' => '/storage/budgets/rab_2024_rev1.pdf'],
                    ['name' => 'Justifikasi_Revisi.docx', 'url' => '/storage/budgets/revision_notes.docx']
                ]),
                'notes' => 'Revisi karena ada penambahan anggaran untuk program baru',
                'created_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 18, 13, 30),
                'updated_at' => Carbon::create($currentYear, 2, 20, 11, 0),
            ],

            // Budget 5: Rejected Budget
            [
                'event_id' => $events->skip(3)->first()->id ?? $events->first()->id,
                'budget_code' => 'RAB-2024-005',
                'title' => 'Budget Studi Banding Luar Negeri',
                'description' => 'Anggaran untuk studi banding ke universitas di Singapura',
                'fiscal_year' => '2024',
                'version' => 1,
                'parent_budget_id' => null,
                'total_planned' => 500000000.00,
                'total_approved' => 0.00,
                'total_allocated' => 0.00,
                'total_spent' => 0.00,
                'total_remaining' => 0.00,
                'valid_from' => Carbon::create($currentYear, 6, 1),
                'valid_until' => Carbon::create($currentYear, 6, 15),
                'status' => 'rejected',
                'submitted_by' => $admin->id,
                'submitted_at' => Carbon::create($currentYear, 3, 1, 10, 0),
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => Carbon::create($currentYear, 3, 5, 14, 0),
                'review_notes' => 'Anggaran terlalu besar dan tidak sesuai dengan prioritas organisasi tahun ini.',
                'approved_by' => null,
                'approved_at' => null,
                'approval_notes' => null,
                'revision_reason' => null,
                'rejection_reason' => 'Anggaran tidak sesuai dengan skala prioritas dan keterbatasan dana organisasi. Disarankan untuk ditunda ke tahun depan atau diganti dengan alternatif yang lebih ekonomis.',
                'attachments' => json_encode([
                    ['name' => 'Proposal_Studi_Banding.pdf', 'url' => '/storage/budgets/proposal_sg.pdf']
                ]),
                'notes' => 'Ditolak oleh reviewer',
                'created_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 25, 15, 0),
                'updated_at' => Carbon::create($currentYear, 3, 5, 14, 0),
            ],

            // Budget 6: Closed Budget (Previous Year)
            [
                'event_id' => $events->first()->id,
                'budget_code' => 'RAB-2023-001',
                'title' => 'Budget Kegiatan Tahunan 2023',
                'description' => 'Rencana Anggaran Biaya untuk kegiatan tahunan organisasi tahun 2023',
                'fiscal_year' => '2023',
                'version' => 1,
                'parent_budget_id' => null,
                'total_planned' => 200000000.00,
                'total_approved' => 195000000.00,
                'total_allocated' => 195000000.00,
                'total_spent' => 188000000.00,
                'total_remaining' => 7000000.00,
                'valid_from' => Carbon::create($currentYear - 1, 1, 1),
                'valid_until' => Carbon::create($currentYear - 1, 12, 31),
                'status' => 'closed',
                'submitted_by' => $admin->id,
                'submitted_at' => Carbon::create($currentYear - 1, 1, 5, 10, 0),
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => Carbon::create($currentYear - 1, 1, 8, 14, 30),
                'review_notes' => 'Disetujui dengan minor adjustment',
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear - 1, 1, 10, 9, 15),
                'approval_notes' => 'Approved',
                'revision_reason' => null,
                'rejection_reason' => null,
                'attachments' => json_encode([
                    ['name' => 'RAB_2023.pdf', 'url' => '/storage/budgets/rab_2023.pdf']
                ]),
                'notes' => 'Budget tahun 2023 yang sudah closed',
                'created_by' => $admin->id,
                'created_at' => Carbon::create($currentYear - 1, 1, 2, 8, 0),
                'updated_at' => Carbon::create($currentYear, 1, 5, 10, 0),
            ],
        ];

        foreach ($budgets as $budget) {
            Budget::create($budget);
        }

        $this->command->info('✅ Budget seeder completed! Created ' . count($budgets) . ' budgets.');
    }
}