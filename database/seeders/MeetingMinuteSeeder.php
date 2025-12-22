<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeetingMinute;
use App\Models\Event;
use App\Models\User;
use App\Models\CommitteeStructure;
use Carbon\Carbon;

class MeetingMinuteSeeder extends Seeder
{
    /**
     * Counter for minute code generation
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

        // If no users exist, we can't seed meeting minutes
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // Get sample users for different roles
        $creator = $users->first();
        $chairman = $users->count() > 1 ? $users->skip(1)->first() : $creator;
        $secretary = $users->count() > 2 ? $users->skip(2)->first() : $creator;

        // Initialize counter from existing minutes
        $latestMinute = MeetingMinute::whereYear('created_at', now()->year)
            ->latest('id')
            ->first();

        if ($latestMinute && $latestMinute->minute_code) {
            $this->codeCounter = ((int) substr($latestMinute->minute_code, -3)) + 1;
        }

        $this->command->info('Creating meeting minutes...');

        // 1. Draft Meeting Minutes (3)
        $this->createDraftMinutes($creator, $chairman, $secretary, $users, $events, $structures);

        // 2. Finalized Meeting Minutes (3)
        $this->createFinalizedMinutes($creator, $chairman, $secretary, $users, $events, $structures);

        // 3. Distributed Meeting Minutes (2)
        $this->createDistributedMinutes($creator, $chairman, $secretary, $users, $events, $structures);

        // 4. Past Meetings (4)
        $this->createPastMeetings($creator, $chairman, $secretary, $users, $events, $structures);

        // 5. Upcoming Meetings (3)
        $this->createUpcomingMeetings($creator, $chairman, $secretary, $users, $events, $structures);

        $this->command->info('✅ Meeting minutes seeded successfully!');
        $this->command->info('Total: ' . MeetingMinute::count() . ' meeting minutes created');
    }

    /**
     * Generate minute code manually
     */
    private function generateMinuteCode(): string
    {
        $year = now()->year;
        $code = 'MM-' . $year . '-' . str_pad($this->codeCounter, 3, '0', STR_PAD_LEFT);
        $this->codeCounter++;
        return $code;
    }

    /**
     * Get random participants
     */
    private function getRandomParticipants($users, $count = null)
    {
        $count = $count ?? rand(3, 8);
        return $users->random(min($count, $users->count()))->pluck('id')->toArray();
    }

    /**
     * Create draft meeting minutes
     */
    private function createDraftMinutes($creator, $chairman, $secretary, $users, $events, $structures)
    {
        $minutes = [
            [
                'meeting_title' => 'Rapat Koordinasi Persiapan Ramadhan 1447H',
                'meeting_type' => 'coordination',
                'meeting_date' => now()->addDays(3)->setHour(14)->setMinute(0),
                'location' => 'Ruang Rapat Masjid Al-Ikhlas',
                'duration_minutes' => 120,
                'agenda' => "1. Pembukaan\n2. Review timeline kegiatan Ramadhan\n3. Pembagian tugas panitia\n4. Diskusi anggaran\n5. Penutup",
                'notes' => 'Harap semua peserta membawa laptop untuk presentasi',
            ],
            [
                'meeting_title' => 'Rapat Evaluasi Program Kajian Bulanan',
                'meeting_type' => 'evaluation',
                'meeting_date' => now()->subDays(2)->setHour(19)->setMinute(30),
                'location' => 'Sekretariat Panitia',
                'meeting_link' => 'https://meet.google.com/abc-defg-hij',
                'duration_minutes' => 90,
                'agenda' => "1. Pembukaan\n2. Review kehadiran kajian bulan lalu\n3. Evaluasi materi dan pemateri\n4. Rencana kajian bulan depan\n5. Penutup",
            ],
            [
                'meeting_title' => 'Rapat Darurat: Perubahan Jadwal Acara',
                'meeting_type' => 'emergency',
                'meeting_date' => now()->addHours(6),
                'meeting_link' => 'https://zoom.us/j/123456789',
                'duration_minutes' => 60,
                'agenda' => "1. Informasi perubahan jadwal\n2. Diskusi alternatif solusi\n3. Keputusan dan tindak lanjut",
                'notes' => 'Meeting online via Zoom. Link akan dikirim 30 menit sebelum meeting.',
            ],
        ];

        foreach ($minutes as $data) {
            $participants = $this->getRandomParticipants($users, 5);
            $absent = $this->getRandomParticipants($users, 2);

            $data['minute_code'] = $this->generateMinuteCode();
            $data['created_by'] = $creator->id;
            $data['chairman'] = $chairman->id;
            $data['secretary'] = $secretary->id;
            $data['status'] = MeetingMinute::STATUS_DRAFT;
            $data['participants'] = $participants;
            $data['absent_members'] = $absent;

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            MeetingMinute::create($data);
        }

        $this->command->info('✓ Created 3 draft meeting minutes');
    }

    /**
     * Create finalized meeting minutes
     */
    private function createFinalizedMinutes($creator, $chairman, $secretary, $users, $events, $structures)
    {
        $minutes = [
            [
                'meeting_title' => 'Rapat Pleno Pembentukan Panitia Ramadhan',
                'meeting_type' => 'planning',
                'meeting_date' => now()->subDays(7)->setHour(15)->setMinute(0),
                'location' => 'Aula Masjid Raya',
                'duration_minutes' => 180,
                'agenda' => "1. Pembukaan dan tilawah\n2. Pembacaan SK Panitia\n3. Susunan kepanitiaan\n4. Job description tiap divisi\n5. Timeline kegiatan\n6. Penutup",
                'discussion_summary' => 'Rapat berjalan lancar dengan seluruh peserta hadir. Telah disepakati struktur kepanitiaan dan timeline kegiatan Ramadhan 1447H. Setiap divisi diminta untuk menyusun proposal kegiatan masing-masing.',
                'decisions' => "1. Menyetujui struktur kepanitiaan yang diajukan\n2. Timeline pelaksanaan dimulai 2 minggu sebelum Ramadhan\n3. Deadline pengajuan proposal divisi: 2 minggu dari sekarang\n4. Rapat koordinasi rutin setiap Jumat pukul 19:00",
                'action_items' => 'Setiap ketua divisi wajib menyusun proposal kegiatan dan mengajukan anggaran',
            ],
            [
                'meeting_title' => 'Rapat Koordinasi dengan Sponsor',
                'meeting_type' => 'coordination',
                'meeting_date' => now()->subDays(5)->setHour(10)->setMinute(0),
                'location' => 'Hotel Grand Indonesia',
                'duration_minutes' => 120,
                'agenda' => "1. Perkenalan kedua belah pihak\n2. Presentasi program Ramadhan\n3. Diskusi bentuk kerjasama\n4. Negosiasi sponsorship\n5. Kesepakatan dan MoU",
                'discussion_summary' => 'Pertemuan dengan PT. Berkah Ramadhan Indonesia berjalan produktif. Pihak sponsor tertarik untuk mendukung program buka puasa bersama dan santunan anak yatim.',
                'decisions' => "1. Sponsor bersedia memberikan dukungan sebesar Rp 100.000.000\n2. Alokasi: 60% buka puasa, 40% santunan yatim\n3. MoU akan ditandatangani minggu depan\n4. Logo sponsor akan dipasang di semua materi promosi",
                'external_participants' => [
                    [
                        'name' => 'Budi Santoso',
                        'organization' => 'PT. Berkah Ramadhan Indonesia',
                        'email' => 'budi@berkahramadhan.co.id',
                        'phone' => '08123456789'
                    ],
                    [
                        'name' => 'Siti Aminah',
                        'organization' => 'PT. Berkah Ramadhan Indonesia',
                        'email' => 'siti@berkahramadhan.co.id',
                        'phone' => '08198765432'
                    ],
                ],
            ],
            [
                'meeting_title' => 'Rapat Umum Anggota Tahunan',
                'meeting_type' => 'general',
                'meeting_date' => now()->subDays(10)->setHour(13)->setMinute(0),
                'location' => 'Masjid Al-Furqon',
                'duration_minutes' => 240,
                'agenda' => "1. Pembukaan\n2. Laporan pertanggungjawaban pengurus\n3. Laporan keuangan\n4. Pemilihan pengurus baru\n5. Program kerja tahun depan\n6. Penutup",
                'discussion_summary' => 'Rapat umum anggota dihadiri oleh 150 jamaah. Laporan pertanggungjawaban diterima dengan baik. Pemilihan pengurus baru berlangsung demokratis.',
                'decisions' => "1. Laporan pertanggungjawaban diterima dengan suara bulat\n2. Pengurus baru periode 2024-2027 telah terpilih\n3. Program kerja tahun depan fokus pada digitalisasi dan pemberdayaan ekonomi\n4. Iuran anggota tetap Rp 50.000/bulan",
            ],
        ];

        foreach ($minutes as $data) {
            $participants = $this->getRandomParticipants($users, 8);
            $absent = $this->getRandomParticipants($users, 1);

            $meetingDate = $data['meeting_date'];
            $finalizedAt = $meetingDate->copy()->addDays(1);

            $data['minute_code'] = $this->generateMinuteCode();
            $data['created_by'] = $creator->id;
            $data['chairman'] = $chairman->id;
            $data['secretary'] = $secretary->id;
            $data['status'] = MeetingMinute::STATUS_FINALIZED;
            $data['participants'] = $participants;
            $data['absent_members'] = $absent;
            $data['finalized_by'] = $chairman->id;
            $data['finalized_at'] = $finalizedAt;

            // Add action items
            $data['action_items_list'] = [
                [
                    'task' => 'Menyusun proposal kegiatan divisi',
                    'assignee' => $users->random()->id,
                    'deadline' => now()->addDays(14)->toDateString(),
                    'status' => 'pending',
                    'notes' => 'Konsultasi dengan ketua panitia'
                ],
                [
                    'task' => 'Koordinasi dengan sponsor',
                    'assignee' => $users->random()->id,
                    'deadline' => now()->addDays(7)->toDateString(),
                    'status' => 'in_progress',
                    'notes' => 'Follow up meeting minggu depan'
                ],
            ];

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }
            if ($structures->isNotEmpty() && rand(0, 1)) {
                $data['structure_id'] = $structures->random()->id;
            }

            MeetingMinute::create($data);
        }

        $this->command->info('✓ Created 3 finalized meeting minutes');
    }

    /**
     * Create distributed meeting minutes
     */
    private function createDistributedMinutes($creator, $chairman, $secretary, $users, $events, $structures)
    {
        $minutes = [
            [
                'meeting_title' => 'Rapat Evaluasi Kegiatan Bulan Lalu',
                'meeting_type' => 'evaluation',
                'meeting_date' => now()->subDays(15)->setHour(19)->setMinute(0),
                'location' => 'Sekretariat',
                'duration_minutes' => 120,
                'agenda' => "1. Review pelaksanaan kegiatan\n2. Analisis kendala dan solusi\n3. Poin-poin perbaikan\n4. Apresiasi tim sukses",
                'discussion_summary' => 'Evaluasi menyeluruh terhadap kegiatan bulan lalu. Beberapa kendala teknis teridentifikasi dan telah ditemukan solusinya. Tim apresiasi atas kinerja yang baik.',
                'decisions' => "1. Meningkatkan koordinasi antar divisi\n2. Memperbaiki sistem komunikasi internal\n3. Menambah volunteer untuk kegiatan besar\n4. Membuat checklist SOP untuk setiap kegiatan",
                'action_items' => 'Follow up perbaikan sistem dan rekrutmen volunteer baru',
            ],
            [
                'meeting_title' => 'Rapat Perencanaan Anggaran Tahunan',
                'meeting_type' => 'planning',
                'meeting_date' => now()->subDays(20)->setHour(14)->setMinute(0),
                'location' => 'Ruang Rapat Utama',
                'duration_minutes' => 180,
                'agenda' => "1. Pembukaan\n2. Review anggaran tahun lalu\n3. Usulan anggaran tahun depan\n4. Prioritas program\n5. Sumber pendanaan\n6. Kesimpulan",
                'discussion_summary' => 'Rapat membahas detail anggaran untuk program tahun depan. Total anggaran yang dibutuhkan sekitar Rp 500 juta dengan berbagai sumber pendanaan.',
                'decisions' => "1. Total anggaran disetujui Rp 500.000.000\n2. Alokasi: 40% operasional, 60% program\n3. Sumber: donasi, sponsorship, dan iuran anggota\n4. Pembentukan tim fundraising",
                'next_meeting_date' => now()->addDays(30),
                'next_meeting_location' => 'Sekretariat',
                'next_meeting_agenda' => 'Follow up realisasi anggaran dan laporan fundraising',
            ],
        ];

        foreach ($minutes as $data) {
            $participants = $this->getRandomParticipants($users, 10);
            $distributeTo = $this->getRandomParticipants($users, 12);

            $meetingDate = $data['meeting_date'];
            $finalizedAt = $meetingDate->copy()->addDays(1);
            $distributedAt = $finalizedAt->copy()->addHours(6);

            $data['minute_code'] = $this->generateMinuteCode();
            $data['created_by'] = $creator->id;
            $data['chairman'] = $chairman->id;
            $data['secretary'] = $secretary->id;
            $data['status'] = MeetingMinute::STATUS_DISTRIBUTED;
            $data['participants'] = $participants;
            $data['finalized_by'] = $chairman->id;
            $data['finalized_at'] = $finalizedAt;
            $data['distributed_at'] = $distributedAt;
            $data['distributed_to'] = $distributeTo;

            // Add action items
            $data['action_items_list'] = [
                [
                    'task' => 'Implementasi sistem komunikasi baru',
                    'assignee' => $users->random()->id,
                    'deadline' => now()->addDays(30)->toDateString(),
                    'status' => 'in_progress',
                    'notes' => 'Koordinasi dengan tim IT'
                ],
                [
                    'task' => 'Rekrutmen volunteer',
                    'assignee' => $users->random()->id,
                    'deadline' => now()->addDays(21)->toDateString(),
                    'status' => 'pending',
                ],
                [
                    'task' => 'Penyusunan SOP kegiatan',
                    'assignee' => $users->random()->id,
                    'deadline' => now()->addDays(14)->toDateString(),
                    'status' => 'completed',
                    'notes' => 'Sudah selesai dan didistribusikan'
                ],
            ];

            if ($events->isNotEmpty()) {
                $data['event_id'] = $events->random()->id;
            }

            MeetingMinute::create($data);
        }

        $this->command->info('✓ Created 2 distributed meeting minutes');
    }

    /**
     * Create past meetings
     */
    private function createPastMeetings($creator, $chairman, $secretary, $users, $events, $structures)
    {
        $meetings = [
            ['days_ago' => 25, 'title' => 'Rapat Koordinasi Mingguan #1', 'type' => 'coordination'],
            ['days_ago' => 18, 'title' => 'Rapat Koordinasi Mingguan #2', 'type' => 'coordination'],
            ['days_ago' => 30, 'title' => 'Rapat Perencanaan Jadwal Kajian', 'type' => 'planning'],
            ['days_ago' => 35, 'title' => 'Rapat Kick-off Program Ramadhan', 'type' => 'general'],
        ];

        foreach ($meetings as $meeting) {
            $meetingDate = now()->subDays($meeting['days_ago'])->setHour(19)->setMinute(0);
            $finalizedAt = $meetingDate->copy()->addDays(1);

            $data = [
                'minute_code' => $this->generateMinuteCode(),
                'meeting_title' => $meeting['title'],
                'meeting_type' => $meeting['type'],
                'meeting_date' => $meetingDate,
                'location' => 'Masjid Al-Ikhlas',
                'duration_minutes' => 90,
                'agenda' => "1. Pembukaan\n2. Update progress\n3. Diskusi kendala\n4. Planning minggu depan\n5. Penutup",
                'discussion_summary' => 'Rapat berjalan lancar dengan pembahasan progress kegiatan dan planning untuk periode selanjutnya.',
                'decisions' => "1. Melanjutkan program sesuai timeline\n2. Mengatasi kendala yang ada\n3. Meningkatkan koordinasi",
                'created_by' => $creator->id,
                'chairman' => $chairman->id,
                'secretary' => $secretary->id,
                'status' => MeetingMinute::STATUS_FINALIZED,
                'participants' => $this->getRandomParticipants($users, 6),
                'finalized_by' => $chairman->id,
                'finalized_at' => $finalizedAt,
            ];

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }

            MeetingMinute::create($data);
        }

        $this->command->info('✓ Created 4 past meeting minutes');
    }

    /**
     * Create upcoming meetings
     */
    private function createUpcomingMeetings($creator, $chairman, $secretary, $users, $events, $structures)
    {
        $meetings = [
            ['days_ahead' => 5, 'title' => 'Rapat Koordinasi Mingguan Mendatang', 'type' => 'coordination'],
            ['days_ahead' => 10, 'title' => 'Rapat Review Progress Program', 'type' => 'evaluation'],
            ['days_ahead' => 15, 'title' => 'Rapat Persiapan Acara Besar', 'type' => 'planning'],
        ];

        foreach ($meetings as $meeting) {
            $meetingDate = now()->addDays($meeting['days_ahead'])->setHour(19)->setMinute(0);

            $data = [
                'minute_code' => $this->generateMinuteCode(),
                'meeting_title' => $meeting['title'],
                'meeting_type' => $meeting['type'],
                'meeting_date' => $meetingDate,
                'location' => 'Sekretariat Panitia',
                'meeting_link' => rand(0, 1) ? 'https://meet.google.com/xyz-abcd-123' : null,
                'duration_minutes' => 120,
                'agenda' => "1. Pembukaan\n2. Agenda utama (TBD)\n3. Diskusi\n4. Kesimpulan\n5. Penutup",
                'created_by' => $creator->id,
                'chairman' => $chairman->id,
                'secretary' => $secretary->id,
                'status' => MeetingMinute::STATUS_DRAFT,
                'participants' => $this->getRandomParticipants($users, 8),
                'notes' => 'Harap konfirmasi kehadiran H-1',
            ];

            if ($events->isNotEmpty() && rand(0, 1)) {
                $data['event_id'] = $events->random()->id;
            }

            MeetingMinute::create($data);
        }

        $this->command->info('✓ Created 3 upcoming meeting minutes');
    }
}
