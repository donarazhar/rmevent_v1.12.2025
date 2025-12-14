<?php
// database/seeders/CommitteeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\CommitteeStructure;
use App\Models\CommitteeMember;
use Illuminate\Support\Facades\DB;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        
        try {
            // Get first event or create one
            $event = Event::first();
            
            if (!$event) {
                $event = Event::create([
                    'title' => 'Ramadhan 1447 H',
                    'slug' => 'ramadhan-1447-h',
                    'description' => 'Event Ramadhan Mubarak 1447 H',
                    'start_date' => now()->addMonth(),
                    'end_date' => now()->addMonth()->addDays(30),
                    'location' => 'Jakarta',
                    'status' => 'upcoming',
                    'is_featured' => true,
                ]);
            }

            // Use existing users from UserSeeder instead of creating new ones
            $users = $this->getExistingUsers();

            if (count($users) < 10) {
                $this->command->warn('⚠️  Not enough users found. Please run UserSeeder first.');
                $this->command->info('Run: php artisan db:seed --class=UserSeeder');
                DB::rollBack();
                return;
            }

            // Create committee structures
            $this->createCommitteeStructure($event, $users);

            DB::commit();
            
            $this->command->info('✓ Committee structure seeded successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error seeding committee: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get existing users from UserSeeder
     */
    private function getExistingUsers(): array
    {
        // Get admins and panitia only (not jamaah)
        $admins = User::where('role', 'admin')->get();
        $panitia = User::where('role', 'panitia')->get();
        
        // Merge and convert to array
        return $admins->merge($panitia)->all();
    }

    /**
     * Create committee structure
     */
    private function createCommitteeStructure(Event $event, array $users): void
    {
        // Pastikan ada cukup user
        if (count($users) < 10) {
            throw new \Exception('Minimal 10 users diperlukan untuk membuat struktur committee');
        }

        // 1. STEERING COMMITTEE (Level 1)
        $steering = CommitteeStructure::create([
            'event_id' => $event->id,
            'parent_id' => null,
            'name' => 'Steering Committee',
            'code' => 'SC',
            'description' => 'Komite pengarah yang bertanggung jawab atas arah strategis dan kebijakan event',
            'level' => 1,
            'order' => 1,
            'leader_id' => $users[0]->id,
            'vice_leader_id' => $users[1]->id,
            'email' => 'steering@ramadhanmubarak.org',
            'phone' => '+62 812-3456-7890',
            'status' => 'active',
            'responsibilities' => [
                'Menetapkan visi, misi, dan strategi event',
                'Mengawasi pelaksanaan event secara keseluruhan',
                'Membuat keputusan strategis dan kebijakan',
                'Mengevaluasi kinerja panitia',
                'Bertanggung jawab kepada stakeholder'
            ],
            'authorities' => [
                'Menyetujui anggaran dan alokasi dana',
                'Menunjuk dan memberhentikan ketua divisi',
                'Mengambil keputusan final dalam situasi kritis',
                'Mewakili panitia dalam forum resmi'
            ]
        ]);

        $this->createMembers($steering, [$users[0], $users[1]], 'steering');

        // 2. ORGANIZING COMMITTEE (Level 1)
        $organizing = CommitteeStructure::create([
            'event_id' => $event->id,
            'parent_id' => null,
            'name' => 'Organizing Committee',
            'code' => 'OC',
            'description' => 'Panitia pelaksana yang mengkoordinasikan seluruh divisi operasional',
            'level' => 1,
            'order' => 2,
            'leader_id' => $users[2]->id,
            'vice_leader_id' => $users[3]->id,
            'email' => 'organizing@ramadhanmubarak.org',
            'phone' => '+62 812-3456-7891',
            'status' => 'active',
            'responsibilities' => [
                'Mengkoordinasikan seluruh divisi operasional',
                'Memastikan timeline event berjalan sesuai rencana',
                'Mengelola komunikasi antar divisi',
                'Melaporkan progress kepada Steering Committee',
                'Menangani issue operasional'
            ],
            'authorities' => [
                'Mengalokasikan resources antar divisi',
                'Menyetujui proposal dari divisi',
                'Membuat keputusan operasional',
                'Mengatur jadwal rapat koordinasi'
            ]
        ]);

        $this->createMembers($organizing, [$users[2], $users[3]], 'organizing');

        // === DIVISI-DIVISI OPERASIONAL (Level 2) ===

        // 3. Divisi Acara
        $eventDiv = CommitteeStructure::create([
            'event_id' => $event->id,
            'parent_id' => $organizing->id,
            'name' => 'Divisi Acara',
            'code' => 'DIV-ACARA',
            'description' => 'Bertanggung jawab atas konsep, perencanaan, dan pelaksanaan seluruh rangkaian acara',
            'level' => 2,
            'order' => 1,
            'leader_id' => $users[4]->id ?? $users[2]->id,
            'vice_leader_id' => $users[5]->id ?? $users[3]->id,
            'email' => 'acara@ramadhanmubarak.org',
            'phone' => '+62 812-3456-7892',
            'status' => 'active',
            'responsibilities' => [
                'Merancang konsep dan rundown acara',
                'Mengkoordinasikan pelaksanaan acara',
                'Mengelola tim MC dan moderator',
                'Mengatur timing dan flow acara',
                'Evaluasi pelaksanaan acara'
            ],
            'authorities' => [
                'Menentukan konsep dan tema acara',
                'Memilih MC dan moderator',
                'Mengubah rundown jika diperlukan',
                'Koordinasi dengan talent/pembicara'
            ]
        ]);

        $this->createMembers($eventDiv, [
            $users[4] ?? $users[2], 
            $users[5] ?? $users[3], 
            $users[6] ?? $users[4]
        ], 'division');

        // 4. Divisi Publikasi & Dokumentasi
        $pubdok = CommitteeStructure::create([
            'event_id' => $event->id,
            'parent_id' => $organizing->id,
            'name' => 'Divisi Publikasi & Dokumentasi',
            'code' => 'DIV-PUBDOK',
            'description' => 'Mengelola publikasi event dan dokumentasi visual',
            'level' => 2,
            'order' => 2,
            'leader_id' => $users[6]->id ?? $users[3]->id,
            'vice_leader_id' => $users[7]->id ?? $users[4]->id,
            'email' => 'pubdok@ramadhanmubarak.org',
            'phone' => '+62 812-3456-7893',
            'status' => 'active',
            'responsibilities' => [
                'Membuat strategi publikasi dan promosi',
                'Mengelola media sosial event',
                'Dokumentasi foto dan video',
                'Membuat konten kreatif',
                'Press release dan media relations'
            ],
            'authorities' => [
                'Menyetujui konten publikasi',
                'Memilih vendor dokumentasi',
                'Menentukan strategi media sosial',
                'Koordinasi dengan media partner'
            ]
        ]);

        $this->createMembers($pubdok, [
            $users[6] ?? $users[3],
            $users[7] ?? $users[4],
            $users[8] ?? $users[5]
        ], 'division');

        // 5. Divisi Keuangan
        $keuangan = CommitteeStructure::create([
            'event_id' => $event->id,
            'parent_id' => $organizing->id,
            'name' => 'Divisi Keuangan',
            'code' => 'DIV-KEUANGAN',
            'description' => 'Mengelola keuangan, budgeting, dan pertanggungjawaban dana event',
            'level' => 2,
            'order' => 3,
            'leader_id' => $users[8]->id ?? $users[4]->id,
            'vice_leader_id' => $users[9]->id ?? $users[5]->id,
            'email' => 'keuangan@ramadhanmubarak.org',
            'phone' => '+62 812-3456-7894',
            'status' => 'active',
            'responsibilities' => [
                'Membuat dan mengelola budget event',
                'Mencatat semua transaksi keuangan',
                'Melakukan verifikasi dan pembayaran',
                'Membuat laporan keuangan berkala',
                'Audit dan pertanggungjawaban dana'
            ],
            'authorities' => [
                'Menyetujui pencairan dana',
                'Verifikasi invoice dan kwitansi',
                'Menolak pengajuan yang tidak sesuai budget',
                'Membuat rekomendasi finansial'
            ]
        ]);

        $this->createMembers($keuangan, [
            $users[8] ?? $users[4],
            $users[9] ?? $users[5]
        ], 'division');

        $this->command->info('✓ Created committee structures');
        $this->command->info('  - Using ' . count($users) . ' existing users');
        $this->command->info('  - Level 1: Steering & Organizing Committee');
        $this->command->info('  - Level 2: 3 Operational Divisions');
    }

    /**
     * Create committee members
     */
    private function createMembers(CommitteeStructure $structure, array $users, string $type): void
    {
        foreach ($users as $index => $user) {
            // Skip if user is null
            if (!$user) continue;

            // Determine position
            $position = 'member';
            $specificRole = 'Anggota';

            if ($index === 0) {
                $position = $type === 'steering' ? 'chairman' : ($type === 'organizing' ? 'coordinator' : 'leader');
                $specificRole = $type === 'steering' ? 'Chairman' : ($type === 'organizing' ? 'Coordinator' : 'Ketua');
            } elseif ($index === 1) {
                $position = 'vice_leader';
                $specificRole = 'Wakil Ketua';
            }

            CommitteeMember::create([
                'structure_id' => $structure->id,
                'user_id' => $user->id,
                'position' => $position,
                'specific_role' => $specificRole,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => 'active',
                'tasks_completed' => rand(0, 10),
                'tasks_assigned' => rand(10, 20),
                'performance_score' => rand(70, 100) / 10,
                'notes' => 'Member aktif sejak awal pembentukan panitia',
                'assigned_by' => 1,
            ]);
        }
    }
}