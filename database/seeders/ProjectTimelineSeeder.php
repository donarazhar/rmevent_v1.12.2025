<?php
// database/seeders/ProjectTimelineSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectTimeline;
use App\Models\Event;
use App\Models\User;
use App\Models\CommitteeStructure;
use Carbon\Carbon;

class ProjectTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample data
        $events = Event::all();
        $users = User::all();
        $structures = CommitteeStructure::all();

        if ($events->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please run EventSeeder and UserSeeder first!');
            return;
        }

        // Get first event for timeline
        $event = $events->first();
        $eventDate = Carbon::parse($event->start_date ?? now()->addMonths(2));

        // Timeline Structure for Ramadhan Event
        $timelines = [
            // FASE 1: PERSIAPAN AWAL (3 bulan sebelum event)
            [
                'name' => 'Fase Persiapan Awal',
                'description' => 'Tahap persiapan dan perencanaan awal event Ramadhan',
                'code' => 'TL-PREP-001',
                'level' => 0,
                'order' => 1,
                'start_date' => $eventDate->copy()->subMonths(3),
                'end_date' => $eventDate->copy()->subMonths(2)->subDays(1),
                'status' => 'completed',
                'priority' => 'high',
                'progress_percentage' => 100,
                'estimated_budget' => 15000000,
                'actual_budget' => 14500000,
                'estimated_hours' => 160,
                'actual_hours' => 155,
                'children' => [
                    [
                        'name' => 'Pembentukan Panitia',
                        'description' => 'Membentuk struktur kepanitiaan dan pembagian tugas',
                        'code' => 'TL-PREP-001-A',
                        'start_date' => $eventDate->copy()->subMonths(3),
                        'end_date' => $eventDate->copy()->subMonths(3)->addDays(7),
                        'status' => 'completed',
                        'priority' => 'urgent',
                        'progress_percentage' => 100,
                        'estimated_budget' => 2000000,
                        'actual_budget' => 2000000,
                        'estimated_hours' => 40,
                        'actual_hours' => 38,
                    ],
                    [
                        'name' => 'Survei Lokasi & Venue',
                        'description' => 'Survei dan pemilihan lokasi untuk berbagai kegiatan',
                        'code' => 'TL-PREP-001-B',
                        'start_date' => $eventDate->copy()->subMonths(3)->addDays(8),
                        'end_date' => $eventDate->copy()->subMonths(3)->addDays(21),
                        'status' => 'completed',
                        'priority' => 'high',
                        'progress_percentage' => 100,
                        'estimated_budget' => 3000000,
                        'actual_budget' => 2800000,
                        'estimated_hours' => 60,
                        'actual_hours' => 55,
                    ],
                    [
                        'name' => 'Penyusunan Proposal & RAB',
                        'description' => 'Membuat proposal kegiatan dan rencana anggaran biaya',
                        'code' => 'TL-PREP-001-C',
                        'start_date' => $eventDate->copy()->subMonths(3)->addDays(10),
                        'end_date' => $eventDate->copy()->subMonths(2)->subDays(10),
                        'status' => 'completed',
                        'priority' => 'high',
                        'progress_percentage' => 100,
                        'estimated_budget' => 5000000,
                        'actual_budget' => 4700000,
                        'estimated_hours' => 80,
                        'actual_hours' => 75,
                    ],
                    [
                        'name' => 'Pencarian Sponsor & Donatur',
                        'description' => 'Menghubungi dan negosiasi dengan calon sponsor',
                        'code' => 'TL-PREP-001-D',
                        'start_date' => $eventDate->copy()->subMonths(2)->subDays(15),
                        'end_date' => $eventDate->copy()->subMonths(2)->subDays(1),
                        'status' => 'completed',
                        'priority' => 'urgent',
                        'progress_percentage' => 100,
                        'estimated_budget' => 5000000,
                        'actual_budget' => 5000000,
                        'estimated_hours' => 100,
                        'actual_hours' => 92,
                    ],
                ],
            ],

            // FASE 2: PERSIAPAN TEKNIS (2 bulan sebelum event)
            [
                'name' => 'Fase Persiapan Teknis',
                'description' => 'Persiapan teknis dan logistik untuk pelaksanaan event',
                'code' => 'TL-TECH-002',
                'level' => 0,
                'order' => 2,
                'start_date' => $eventDate->copy()->subMonths(2),
                'end_date' => $eventDate->copy()->subMonth()->subDays(1),
                'status' => 'in_progress',
                'priority' => 'high',
                'progress_percentage' => 75,
                'estimated_budget' => 35000000,
                'actual_budget' => 28000000,
                'estimated_hours' => 320,
                'actual_hours' => 245,
                'children' => [
                    [
                        'name' => 'Booking Venue & Perizinan',
                        'description' => 'Booking tempat dan mengurus perizinan',
                        'code' => 'TL-TECH-002-A',
                        'start_date' => $eventDate->copy()->subMonths(2),
                        'end_date' => $eventDate->copy()->subMonths(2)->addDays(14),
                        'status' => 'completed',
                        'priority' => 'urgent',
                        'progress_percentage' => 100,
                        'estimated_budget' => 10000000,
                        'actual_budget' => 10000000,
                        'estimated_hours' => 60,
                        'actual_hours' => 58,
                    ],
                    [
                        'name' => 'Pengadaan Perlengkapan',
                        'description' => 'Pengadaan sound system, tenda, kursi, dll',
                        'code' => 'TL-TECH-002-B',
                        'start_date' => $eventDate->copy()->subMonths(2)->addDays(7),
                        'end_date' => $eventDate->copy()->subMonth()->addDays(7),
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'progress_percentage' => 80,
                        'estimated_budget' => 15000000,
                        'actual_budget' => 12000000,
                        'estimated_hours' => 120,
                        'actual_hours' => 95,
                    ],
                    [
                        'name' => 'Desain & Produksi Materi Promosi',
                        'description' => 'Desain poster, banner, spanduk, merchandise',
                        'code' => 'TL-TECH-002-C',
                        'start_date' => $eventDate->copy()->subMonths(2)->addDays(10),
                        'end_date' => $eventDate->copy()->subMonth()->addDays(14),
                        'status' => 'in_progress',
                        'priority' => 'medium',
                        'progress_percentage' => 70,
                        'estimated_budget' => 8000000,
                        'actual_budget' => 5000000,
                        'estimated_hours' => 100,
                        'actual_hours' => 72,
                    ],
                    [
                        'name' => 'Rekrutmen Relawan',
                        'description' => 'Mencari dan melatih relawan untuk event',
                        'code' => 'TL-TECH-002-D',
                        'start_date' => $eventDate->copy()->subMonth()->subDays(20),
                        'end_date' => $eventDate->copy()->subMonth()->subDays(1),
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'progress_percentage' => 60,
                        'estimated_budget' => 2000000,
                        'actual_budget' => 1000000,
                        'estimated_hours' => 80,
                        'actual_hours' => 50,
                    ],
                ],
            ],

            // FASE 3: PROMOSI & PUBLIKASI (1 bulan sebelum event)
            [
                'name' => 'Fase Promosi & Publikasi',
                'description' => 'Campaign dan promosi event ke masyarakat',
                'code' => 'TL-PROMO-003',
                'level' => 0,
                'order' => 3,
                'start_date' => $eventDate->copy()->subMonth(),
                'end_date' => $eventDate->copy()->subDays(3),
                'status' => 'in_progress',
                'priority' => 'high',
                'progress_percentage' => 50,
                'estimated_budget' => 20000000,
                'actual_budget' => 8000000,
                'estimated_hours' => 200,
                'actual_hours' => 85,
                'children' => [
                    [
                        'name' => 'Peluncuran Website & Sosial Media',
                        'description' => 'Launch website dan kampanye di social media',
                        'code' => 'TL-PROMO-003-A',
                        'start_date' => $eventDate->copy()->subMonth(),
                        'end_date' => $eventDate->copy()->subMonth()->addDays(7),
                        'status' => 'completed',
                        'priority' => 'high',
                        'progress_percentage' => 100,
                        'estimated_budget' => 5000000,
                        'actual_budget' => 4500000,
                        'estimated_hours' => 60,
                        'actual_hours' => 55,
                    ],
                    [
                        'name' => 'Kampanye Media Sosial',
                        'description' => 'Posting konten promosi di berbagai platform',
                        'code' => 'TL-PROMO-003-B',
                        'start_date' => $eventDate->copy()->subMonth()->addDays(5),
                        'end_date' => $eventDate->copy()->subDays(3),
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'progress_percentage' => 65,
                        'estimated_budget' => 8000000,
                        'actual_budget' => 5000000,
                        'estimated_hours' => 100,
                        'actual_hours' => 60,
                    ],
                    [
                        'name' => 'Kerjasama Media Partner',
                        'description' => 'Publikasi di radio, koran, dan media online',
                        'code' => 'TL-PROMO-003-C',
                        'start_date' => $eventDate->copy()->subDays(25),
                        'end_date' => $eventDate->copy()->subDays(5),
                        'status' => 'in_progress',
                        'priority' => 'medium',
                        'progress_percentage' => 40,
                        'estimated_budget' => 7000000,
                        'actual_budget' => 3000000,
                        'estimated_hours' => 80,
                        'actual_hours' => 30,
                    ],
                ],
            ],

            // FASE 4: PERSIAPAN FINAL (1 minggu sebelum event)
            [
                'name' => 'Fase Persiapan Final',
                'description' => 'Persiapan akhir menjelang pelaksanaan event',
                'code' => 'TL-FINAL-004',
                'level' => 0,
                'order' => 4,
                'start_date' => $eventDate->copy()->subDays(7),
                'end_date' => $eventDate->copy()->subDay(),
                'status' => 'not_started',
                'priority' => 'urgent',
                'progress_percentage' => 0,
                'estimated_budget' => 10000000,
                'estimated_hours' => 150,
                'children' => [
                    [
                        'name' => 'Gladi Bersih & Technical Meeting',
                        'description' => 'Gladi resik dan koordinasi final dengan semua pihak',
                        'code' => 'TL-FINAL-004-A',
                        'start_date' => $eventDate->copy()->subDays(7),
                        'end_date' => $eventDate->copy()->subDays(5),
                        'status' => 'not_started',
                        'priority' => 'urgent',
                        'progress_percentage' => 0,
                        'estimated_budget' => 3000000,
                        'estimated_hours' => 40,
                    ],
                    [
                        'name' => 'Setup Venue & Dekorasi',
                        'description' => 'Pemasangan tenda, sound system, dan dekorasi',
                        'code' => 'TL-FINAL-004-B',
                        'start_date' => $eventDate->copy()->subDays(3),
                        'end_date' => $eventDate->copy()->subDay(),
                        'status' => 'not_started',
                        'priority' => 'urgent',
                        'progress_percentage' => 0,
                        'estimated_budget' => 5000000,
                        'estimated_hours' => 80,
                    ],
                    [
                        'name' => 'Briefing Panitia & Relawan',
                        'description' => 'Briefing final untuk semua panitia dan relawan',
                        'code' => 'TL-FINAL-004-C',
                        'start_date' => $eventDate->copy()->subDays(2),
                        'end_date' => $eventDate->copy()->subDay(),
                        'status' => 'not_started',
                        'priority' => 'high',
                        'progress_percentage' => 0,
                        'estimated_budget' => 2000000,
                        'estimated_hours' => 30,
                    ],
                ],
            ],

            // FASE 5: PELAKSANAAN EVENT
            [
                'name' => 'Fase Pelaksanaan Event',
                'description' => 'Pelaksanaan event Ramadhan Mubarak',
                'code' => 'TL-EVENT-005',
                'level' => 0,
                'order' => 5,
                'start_date' => $eventDate->copy(),
                'end_date' => $eventDate->copy()->addDays(2),
                'status' => 'not_started',
                'priority' => 'urgent',
                'progress_percentage' => 0,
                'estimated_budget' => 50000000,
                'estimated_hours' => 300,
                'children' => [
                    [
                        'name' => 'Hari Pertama - Pembukaan',
                        'description' => 'Acara pembukaan dan talkshow',
                        'code' => 'TL-EVENT-005-A',
                        'start_date' => $eventDate->copy(),
                        'end_date' => $eventDate->copy(),
                        'status' => 'not_started',
                        'priority' => 'urgent',
                        'progress_percentage' => 0,
                        'estimated_budget' => 20000000,
                        'estimated_hours' => 100,
                    ],
                    [
                        'name' => 'Hari Kedua - Kegiatan Inti',
                        'description' => 'Bazar, lomba, dan berbagai kegiatan',
                        'code' => 'TL-EVENT-005-B',
                        'start_date' => $eventDate->copy()->addDay(),
                        'end_date' => $eventDate->copy()->addDay(),
                        'status' => 'not_started',
                        'priority' => 'urgent',
                        'progress_percentage' => 0,
                        'estimated_budget' => 20000000,
                        'estimated_hours' => 120,
                    ],
                    [
                        'name' => 'Hari Ketiga - Penutupan',
                        'description' => 'Acara penutupan dan pengumuman pemenang',
                        'code' => 'TL-EVENT-005-C',
                        'start_date' => $eventDate->copy()->addDays(2),
                        'end_date' => $eventDate->copy()->addDays(2),
                        'status' => 'not_started',
                        'priority' => 'urgent',
                        'progress_percentage' => 0,
                        'estimated_budget' => 10000000,
                        'estimated_hours' => 80,
                    ],
                ],
            ],

            // FASE 6: POST EVENT
            [
                'name' => 'Fase Post Event',
                'description' => 'Evaluasi dan pelaporan pasca event',
                'code' => 'TL-POST-006',
                'level' => 0,
                'order' => 6,
                'start_date' => $eventDate->copy()->addDays(3),
                'end_date' => $eventDate->copy()->addDays(14),
                'status' => 'not_started',
                'priority' => 'medium',
                'progress_percentage' => 0,
                'estimated_budget' => 5000000,
                'estimated_hours' => 100,
                'children' => [
                    [
                        'name' => 'Bongkar Venue & Pengembalian',
                        'description' => 'Bongkar dan kembalikan semua perlengkapan',
                        'code' => 'TL-POST-006-A',
                        'start_date' => $eventDate->copy()->addDays(3),
                        'end_date' => $eventDate->copy()->addDays(5),
                        'status' => 'not_started',
                        'priority' => 'high',
                        'progress_percentage' => 0,
                        'estimated_budget' => 2000000,
                        'estimated_hours' => 40,
                    ],
                    [
                        'name' => 'Evaluasi & Rapat Panitia',
                        'description' => 'Evaluasi pelaksanaan event dengan panitia',
                        'code' => 'TL-POST-006-B',
                        'start_date' => $eventDate->copy()->addDays(6),
                        'end_date' => $eventDate->copy()->addDays(8),
                        'status' => 'not_started',
                        'priority' => 'medium',
                        'progress_percentage' => 0,
                        'estimated_budget' => 1000000,
                        'estimated_hours' => 30,
                    ],
                    [
                        'name' => 'Laporan Pertanggungjawaban',
                        'description' => 'Penyusunan LPJ dan laporan keuangan',
                        'code' => 'TL-POST-006-C',
                        'start_date' => $eventDate->copy()->addDays(9),
                        'end_date' => $eventDate->copy()->addDays(14),
                        'status' => 'not_started',
                        'priority' => 'high',
                        'progress_percentage' => 0,
                        'estimated_budget' => 2000000,
                        'estimated_hours' => 60,
                    ],
                ],
            ],
        ];

        // Insert timelines with children
        foreach ($timelines as $timelineData) {
            $children = $timelineData['children'] ?? [];
            unset($timelineData['children']);

            // Create parent timeline
            $parent = $this->createTimeline($timelineData, $event, $users, $structures);

            // Create children timelines
            if (!empty($children)) {
                foreach ($children as $childData) {
                    $childData['parent_id'] = $parent->id;
                    $childData['level'] = $parent->level + 1;
                    $this->createTimeline($childData, $event, $users, $structures);
                }
            }
        }

        $this->command->info('Project Timeline seeded successfully!');
    }

    /**
     * Create a timeline record
     */
    private function createTimeline(array $data, $event, $users, $structures)
    {
        // Get random users for assignment
        $assignedUser = $users->random();
        $teamMembers = $users->random(rand(2, 5))->pluck('id')->toArray();
        $structure = $structures->isNotEmpty() ? $structures->random() : null;

        $timeline = ProjectTimeline::create([
            'event_id' => $event->id,
            'parent_id' => $data['parent_id'] ?? null,
            'structure_id' => $structure?->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'code' => $data['code'],
            'level' => $data['level'] ?? 0,
            'order' => $data['order'] ?? 1,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'actual_start_date' => isset($data['actual_start_date']) ? $data['actual_start_date'] : 
                                   ($data['status'] !== 'not_started' ? $data['start_date'] : null),
            'actual_end_date' => isset($data['actual_end_date']) ? $data['actual_end_date'] : 
                                ($data['status'] === 'completed' ? $data['end_date'] : null),
            'duration_days' => Carbon::parse($data['start_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1,
            'assigned_to' => $assignedUser->id,
            'team_members' => $teamMembers,
            'progress_percentage' => $data['progress_percentage'] ?? 0,
            'status' => $data['status'],
            'priority' => $data['priority'],
            'dependencies' => [],
            'estimated_budget' => $data['estimated_budget'] ?? null,
            'actual_budget' => $data['actual_budget'] ?? null,
            'estimated_hours' => $data['estimated_hours'] ?? null,
            'actual_hours' => $data['actual_hours'] ?? null,
            'notes' => $data['notes'] ?? null,
            'completion_notes' => $data['completion_notes'] ?? null,
            'attachments' => [],
            'created_by' => $users->first()->id,
        ]);

        return $timeline;
    }
}