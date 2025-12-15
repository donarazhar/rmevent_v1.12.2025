<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\BudgetAllocation;
use App\Models\CommitteeStructure;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BudgetAllocationSeeder extends Seeder
{
    public function run(): void
    {
        $budgets = Budget::all();
        $structures = CommitteeStructure::all();
        $events = Event::all();
        $users = User::all();

        if ($budgets->isEmpty()) {
            $this->command->warn('Please run BudgetSeeder first!');
            return;
        }

        if ($structures->isEmpty()) {
            $this->command->warn('Please run CommitteeStructureSeeder first!');
            return;
        }

        // Get the active budget
        $activeBudget = $budgets->where('status', 'active')->first();

        if (!$activeBudget) {
            $this->command->warn('No active budget found!');
            return;
        }

        $admin = $users->where('role', 'admin')->first() ?? $users->first();
        $currentYear = date('Y');

        $allocations = [];

        // Division-based Allocations
        $divisionAllocations = [
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => $structures->where('position_name', 'Ketua Umum')->first()?->id,
                'event_id' => null,
                'allocation_code' => 'ALLOC-DIV-001',
                'title' => 'Alokasi Budget Ketua Umum',
                'description' => 'Alokasi budget untuk kegiatan dan operasional Ketua Umum',
                'allocation_type' => 'division',
                'allocated_amount' => 25000000.00,
                'spent_amount' => 8500000.00,
                'remaining_amount' => 16500000.00,
                'committed_amount' => 5000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 15),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'active',
                'allocated_to' => $structures->where('position_name', 'Ketua Umum')->first()?->user_id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 12, 10, 0),
                'notes' => 'Untuk kebutuhan representasi dan koordinasi tingkat pimpinan',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => $structures->where('position_name', 'LIKE', '%Sekretaris%')->first()?->id,
                'event_id' => null,
                'allocation_code' => 'ALLOC-DIV-002',
                'title' => 'Alokasi Budget Sekretariat',
                'description' => 'Alokasi budget untuk operasional sekretariat',
                'allocation_type' => 'division',
                'allocated_amount' => 30000000.00,
                'spent_amount' => 15000000.00,
                'remaining_amount' => 15000000.00,
                'committed_amount' => 8000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 15),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'active',
                'allocated_to' => $structures->where('position_name', 'LIKE', '%Sekretaris%')->first()?->user_id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 12, 10, 30),
                'notes' => 'Untuk ATK, surat-menyurat, dan administrasi harian',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => $structures->where('position_name', 'LIKE', '%Bendahara%')->first()?->id,
                'event_id' => null,
                'allocation_code' => 'ALLOC-DIV-003',
                'title' => 'Alokasi Budget Bendahara',
                'description' => 'Alokasi budget untuk operasional keuangan',
                'allocation_type' => 'division',
                'allocated_amount' => 20000000.00,
                'spent_amount' => 7000000.00,
                'remaining_amount' => 13000000.00,
                'committed_amount' => 3000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 15),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'active',
                'allocated_to' => $structures->where('position_name', 'LIKE', '%Bendahara%')->first()?->user_id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 12, 11, 0),
                'notes' => 'Untuk biaya administrasi bank, pajak, dan audit',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => $structures->where('division_name', 'LIKE', '%Acara%')->first()?->id ?? $structures->skip(3)->first()?->id,
                'event_id' => null,
                'allocation_code' => 'ALLOC-DIV-004',
                'title' => 'Alokasi Budget Divisi Acara',
                'description' => 'Alokasi budget untuk divisi penyelenggara acara',
                'allocation_type' => 'division',
                'allocated_amount' => 45000000.00,
                'spent_amount' => 18000000.00,
                'remaining_amount' => 27000000.00,
                'committed_amount' => 12000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 15),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'active',
                'allocated_to' => $structures->where('division_name', 'LIKE', '%Acara%')->first()?->user_id ?? $structures->skip(3)->first()?->user_id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 12, 11, 30),
                'notes' => 'Untuk peralatan, dekorasi, dan kebutuhan event',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => $structures->where('division_name', 'LIKE', '%PSDM%')->first()?->id ?? $structures->skip(4)->first()?->id,
                'event_id' => null,
                'allocation_code' => 'ALLOC-DIV-005',
                'title' => 'Alokasi Budget PSDM',
                'description' => 'Alokasi budget untuk Pengembangan SDM',
                'allocation_type' => 'division',
                'allocated_amount' => 35000000.00,
                'spent_amount' => 12000000.00,
                'remaining_amount' => 23000000.00,
                'committed_amount' => 8000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 15),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'active',
                'allocated_to' => $structures->where('division_name', 'LIKE', '%PSDM%')->first()?->user_id ?? $structures->skip(4)->first()?->user_id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 12, 12, 0),
                'notes' => 'Untuk training, workshop, dan pengembangan anggota',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => $structures->where('division_name', 'LIKE', '%Humas%')->first()?->id ?? $structures->skip(5)->first()?->id,
                'event_id' => null,
                'allocation_code' => 'ALLOC-DIV-006',
                'title' => 'Alokasi Budget Humas & Media',
                'description' => 'Alokasi budget untuk publikasi dan hubungan masyarakat',
                'allocation_type' => 'division',
                'allocated_amount' => 25000000.00,
                'spent_amount' => 9000000.00,
                'remaining_amount' => 16000000.00,
                'committed_amount' => 6000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 15),
                'valid_until' => Carbon::create($currentYear, 12, 31),
                'status' => 'active',
                'allocated_to' => $structures->where('division_name', 'LIKE', '%Humas%')->first()?->user_id ?? $structures->skip(5)->first()?->user_id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 12, 12, 30),
                'notes' => 'Untuk konten sosial media, merchandise, dan publikasi',
                'created_by' => $admin->id,
            ],
        ];

        // Filter out allocations with null structure_id
        $divisionAllocations = array_filter($divisionAllocations, function ($allocation) {
            return $allocation['structure_id'] !== null;
        });

        $allocations = array_merge($allocations, $divisionAllocations);

        // Event-based Allocations
        if ($events->count() > 0) {
            $eventAllocations = [
                [
                    'budget_id' => $activeBudget->id,
                    'structure_id' => null,
                    'event_id' => $events->first()->id,
                    'allocation_code' => 'ALLOC-EVT-001',
                    'title' => 'Alokasi Budget ' . $events->first()->name,
                    'description' => 'Alokasi budget khusus untuk event ' . $events->first()->name,
                    'allocation_type' => 'event',
                    'allocated_amount' => 50000000.00,
                    'spent_amount' => 30000000.00,
                    'remaining_amount' => 20000000.00,
                    'committed_amount' => 15000000.00,
                    'valid_from' => Carbon::parse($events->first()->start_date)->subDays(30),
                    'valid_until' => Carbon::parse($events->first()->end_date)->addDays(15),
                    'status' => 'active',
                    'allocated_to' => $admin->id,
                    'approved_by' => $admin->id,
                    'approved_at' => Carbon::create($currentYear, 1, 18, 14, 0),
                    'notes' => 'Budget untuk penyelenggaraan event utama',
                    'created_by' => $admin->id,
                ],
            ];

            if ($events->count() > 1) {
                $eventAllocations[] = [
                    'budget_id' => $activeBudget->id,
                    'structure_id' => null,
                    'event_id' => $events->skip(1)->first()->id,
                    'allocation_code' => 'ALLOC-EVT-002',
                    'title' => 'Alokasi Budget ' . $events->skip(1)->first()->name,
                    'description' => 'Alokasi budget khusus untuk event ' . $events->skip(1)->first()->name,
                    'allocation_type' => 'event',
                    'allocated_amount' => 30000000.00,
                    'spent_amount' => 12000000.00,
                    'remaining_amount' => 18000000.00,
                    'committed_amount' => 8000000.00,
                    'valid_from' => Carbon::parse($events->skip(1)->first()->start_date)->subDays(20),
                    'valid_until' => Carbon::parse($events->skip(1)->first()->end_date)->addDays(10),
                    'status' => 'active',
                    'allocated_to' => $users->skip(1)->first()->id,
                    'approved_by' => $admin->id,
                    'approved_at' => Carbon::create($currentYear, 1, 20, 10, 0),
                    'notes' => 'Budget untuk event pendukung',
                    'created_by' => $admin->id,
                ];
            }

            $allocations = array_merge($allocations, $eventAllocations);
        }

        // Project-based Allocations
        $projectAllocations = [
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => null,
                'event_id' => null,
                'allocation_code' => 'ALLOC-PRJ-001',
                'title' => 'Alokasi Budget Proyek Website Baru',
                'description' => 'Budget untuk pengembangan website organisasi yang baru',
                'allocation_type' => 'project',
                'allocated_amount' => 40000000.00,
                'spent_amount' => 22000000.00,
                'remaining_amount' => 18000000.00,
                'committed_amount' => 10000000.00,
                'valid_from' => Carbon::create($currentYear, 2, 1),
                'valid_until' => Carbon::create($currentYear, 5, 31),
                'status' => 'active',
                'allocated_to' => $users->skip(2)->first()?->id ?? $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 25, 15, 0),
                'notes' => 'Termasuk design, development, dan maintenance 6 bulan',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => null,
                'event_id' => null,
                'allocation_code' => 'ALLOC-PRJ-002',
                'title' => 'Alokasi Budget Proyek Sistem Manajemen',
                'description' => 'Budget untuk implementasi sistem manajemen internal',
                'allocation_type' => 'project',
                'allocated_amount' => 35000000.00,
                'spent_amount' => 15000000.00,
                'remaining_amount' => 20000000.00,
                'committed_amount' => 12000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 1),
                'valid_until' => Carbon::create($currentYear, 6, 30),
                'status' => 'active',
                'allocated_to' => $users->skip(3)->first()?->id ?? $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 15, 9, 30),
                'notes' => 'Untuk digitalisasi proses administrasi',
                'created_by' => $admin->id,
            ],
        ];

        $allocations = array_merge($allocations, $projectAllocations);

        // Activity-based Allocations
        $activityAllocations = [
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => null,
                'event_id' => null,
                'allocation_code' => 'ALLOC-ACT-001',
                'title' => 'Alokasi Budget Workshop Series Q1',
                'description' => 'Budget untuk rangkaian workshop di Q1',
                'allocation_type' => 'activity',
                'allocated_amount' => 15000000.00,
                'spent_amount' => 10000000.00,
                'remaining_amount' => 5000000.00,
                'committed_amount' => 4000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 1),
                'valid_until' => Carbon::create($currentYear, 3, 31),
                'status' => 'active',
                'allocated_to' => $users->skip(4)->first()?->id ?? $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 8, 11, 0),
                'notes' => 'Untuk 3 workshop pengembangan skill',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => null,
                'event_id' => null,
                'allocation_code' => 'ALLOC-ACT-002',
                'title' => 'Alokasi Budget Social Media Campaign',
                'description' => 'Budget untuk kampanye media sosial semester 1',
                'allocation_type' => 'activity',
                'allocated_amount' => 10000000.00,
                'spent_amount' => 3000000.00,
                'remaining_amount' => 7000000.00,
                'committed_amount' => 5000000.00,
                'valid_from' => Carbon::create($currentYear, 1, 1),
                'valid_until' => Carbon::create($currentYear, 6, 30),
                'status' => 'active',
                'allocated_to' => $users->skip(5)->first()?->id ?? $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 10, 14, 30),
                'notes' => 'Untuk konten dan ads di berbagai platform',
                'created_by' => $admin->id,
            ],
        ];

        $allocations = array_merge($allocations, $activityAllocations);

        // Example of depleted and cancelled allocations
        $specialAllocations = [
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => null,
                'event_id' => null,
                'allocation_code' => 'ALLOC-ACT-003',
                'title' => 'Alokasi Budget Meeting Bulanan Jan-Feb',
                'description' => 'Budget untuk konsumsi meeting rutin',
                'allocation_type' => 'activity',
                'allocated_amount' => 4000000.00,
                'spent_amount' => 4000000.00,
                'remaining_amount' => 0.00,
                'committed_amount' => 0.00,
                'valid_from' => Carbon::create($currentYear, 1, 1),
                'valid_until' => Carbon::create($currentYear, 2, 28),
                'status' => 'depleted',
                'allocated_to' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 1, 5, 9, 0),
                'notes' => 'Budget sudah habis terpakai',
                'created_by' => $admin->id,
            ],
            [
                'budget_id' => $activeBudget->id,
                'structure_id' => null,
                'event_id' => null,
                'allocation_code' => 'ALLOC-PRJ-003',
                'title' => 'Alokasi Budget Proyek Mobile App',
                'description' => 'Budget untuk pengembangan aplikasi mobile (CANCELLED)',
                'allocation_type' => 'project',
                'allocated_amount' => 60000000.00,
                'spent_amount' => 0.00,
                'remaining_amount' => 60000000.00,
                'committed_amount' => 0.00,
                'valid_from' => Carbon::create($currentYear, 3, 1),
                'valid_until' => Carbon::create($currentYear, 8, 31),
                'status' => 'cancelled',
                'allocated_to' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::create($currentYear, 2, 1, 10, 0),
                'notes' => 'Proyek dibatalkan karena perubahan prioritas strategis',
                'created_by' => $admin->id,
            ],
        ];

        $allocations = array_merge($allocations, $specialAllocations);

        // Create all allocations
        foreach ($allocations as $allocation) {
            BudgetAllocation::create($allocation);
        }

        $this->command->info('✅ Budget Allocation seeder completed! Created ' . count($allocations) . ' allocations.');
        $this->command->info('   - Division allocations: ' . count($divisionAllocations));
        $this->command->info('   - Event allocations: ' . (isset($eventAllocations) ? count($eventAllocations) : 0));
        $this->command->info('   - Project allocations: ' . count($projectAllocations));
        $this->command->info('   - Activity allocations: ' . (count($activityAllocations) + count($specialAllocations)));
    }
}
