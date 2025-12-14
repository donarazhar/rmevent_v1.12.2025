<?php

namespace Database\Seeders;

use App\Models\JobDescription;
use App\Models\JobAssignment;
use App\Models\User;
use App\Models\CommitteeStructure;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JobDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang diperlukan
        $users = User::all();
        $structures = CommitteeStructure::all();
        $events = Event::all();

        if ($users->isEmpty() || $structures->isEmpty()) {
            $this->command->warn('Please seed Users and CommitteeStructures first!');
            return;
        }

        $creator = $users->first();
        $approver = $users->skip(1)->first() ?? $creator;

        // Data Job Descriptions
        $jobDescriptions = [
            [
                'structure_id' => $structures->first()->id,
                'event_id' => $events->first()->id ?? null,
                'title' => 'Koordinator Acara Utama',
                'code' => 'JOB-001',
                'description' => 'Bertanggung jawab mengkoordinasikan seluruh rangkaian acara utama, memastikan semua berjalan sesuai timeline dan rencana yang telah ditetapkan.',
                'responsibilities' => [
                    'Mengkoordinasikan tim pelaksana acara',
                    'Memastikan timeline acara berjalan sesuai rencana',
                    'Melakukan koordinasi dengan vendor dan pihak eksternal',
                    'Membuat laporan progres acara secara berkala',
                    'Menangani masalah atau kendala yang muncul saat acara'
                ],
                'requirements' => [
                    'Pengalaman minimal 2 tahun dalam event management',
                    'Memiliki kemampuan leadership yang baik',
                    'Dapat bekerja di bawah tekanan',
                    'Mampu berkomunikasi dengan efektif'
                ],
                'skills_required' => [
                    'Project Management',
                    'Communication',
                    'Problem Solving',
                    'Time Management',
                    'Leadership'
                ],
                'estimated_hours' => 120,
                'workload_level' => 'heavy',
                'priority' => 'urgent',
                'required_members' => 2,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(35),
                'status' => 'active',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(2),
            ],
            [
                'structure_id' => $structures->skip(1)->first()->id ?? $structures->first()->id,
                'event_id' => $events->first()->id ?? null,
                'title' => 'Desainer Grafis',
                'code' => 'JOB-002',
                'description' => 'Membuat desain visual untuk keperluan promosi dan dokumentasi acara, termasuk poster, banner, dan konten media sosial.',
                'responsibilities' => [
                    'Membuat desain poster dan banner acara',
                    'Mendesain konten untuk media sosial',
                    'Membuat template presentasi',
                    'Revisi desain berdasarkan feedback',
                    'Memastikan konsistensi brand identity'
                ],
                'requirements' => [
                    'Mahir menggunakan Adobe Photoshop dan Illustrator',
                    'Memiliki portfolio desain',
                    'Kreatif dan up-to-date dengan tren desain',
                    'Dapat menyelesaikan desain sesuai deadline'
                ],
                'skills_required' => [
                    'Adobe Photoshop',
                    'Adobe Illustrator',
                    'Graphic Design',
                    'Visual Communication',
                    'Creativity'
                ],
                'estimated_hours' => 60,
                'workload_level' => 'medium',
                'priority' => 'high',
                'required_members' => 3,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(3),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'active',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(1),
            ],
            [
                'structure_id' => $structures->first()->id,
                'event_id' => $events->skip(1)->first()->id ?? $events->first()->id ?? null,
                'title' => 'Content Writer',
                'code' => 'JOB-003',
                'description' => 'Membuat konten tulisan untuk keperluan publikasi acara di berbagai platform media.',
                'responsibilities' => [
                    'Menulis artikel promosi acara',
                    'Membuat caption media sosial',
                    'Menulis press release',
                    'Membuat content calendar',
                    'Melakukan proofreading konten'
                ],
                'requirements' => [
                    'Memiliki kemampuan menulis yang baik',
                    'Kreatif dalam mengemas informasi',
                    'Memahami SEO dan copywriting',
                    'Dapat bekerja dengan deadline ketat'
                ],
                'skills_required' => [
                    'Content Writing',
                    'Copywriting',
                    'SEO',
                    'Social Media',
                    'Proofreading'
                ],
                'estimated_hours' => 40,
                'workload_level' => 'light',
                'priority' => 'medium',
                'required_members' => 2,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(40),
                'status' => 'active',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subHours(12),
            ],
            [
                'structure_id' => $structures->first()->id,
                'event_id' => $events->first()->id ?? null,
                'title' => 'Fotografer & Videografer',
                'code' => 'JOB-004',
                'description' => 'Mendokumentasikan seluruh kegiatan acara dalam bentuk foto dan video berkualitas tinggi.',
                'responsibilities' => [
                    'Mengambil foto dan video selama acara',
                    'Melakukan editing foto dan video',
                    'Membuat highlight video acara',
                    'Menyimpan dan mengarsipkan dokumentasi',
                    'Koordinasi dengan tim kreatif'
                ],
                'requirements' => [
                    'Memiliki kamera profesional',
                    'Pengalaman fotografi/videografi minimal 1 tahun',
                    'Mahir editing menggunakan software profesional',
                    'Portfolio yang relevan'
                ],
                'skills_required' => [
                    'Photography',
                    'Videography',
                    'Adobe Premiere Pro',
                    'Adobe Lightroom',
                    'Video Editing'
                ],
                'estimated_hours' => 80,
                'workload_level' => 'heavy',
                'priority' => 'high',
                'required_members' => 2,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(25),
                'status' => 'active',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(3),
            ],
            [
                'structure_id' => $structures->skip(1)->first()->id ?? $structures->first()->id,
                'event_id' => null,
                'title' => 'Web Developer',
                'code' => 'JOB-005',
                'description' => 'Mengembangkan dan memaintain website untuk keperluan registrasi dan informasi acara.',
                'responsibilities' => [
                    'Membuat sistem registrasi online',
                    'Mengembangkan fitur website',
                    'Melakukan maintenance dan bug fixing',
                    'Optimasi performa website',
                    'Integrasi dengan payment gateway'
                ],
                'requirements' => [
                    'Menguasai HTML, CSS, JavaScript',
                    'Pengalaman dengan Laravel atau framework sejenis',
                    'Memahami database MySQL',
                    'Familiar dengan Git'
                ],
                'skills_required' => [
                    'Laravel',
                    'PHP',
                    'MySQL',
                    'JavaScript',
                    'HTML/CSS',
                    'Git'
                ],
                'estimated_hours' => 100,
                'workload_level' => 'heavy',
                'priority' => 'urgent',
                'required_members' => 1,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(45),
                'status' => 'active',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(5),
            ],
            [
                'structure_id' => $structures->first()->id,
                'event_id' => $events->first()->id ?? null,
                'title' => 'Liaison Officer',
                'code' => 'JOB-006',
                'description' => 'Menangani komunikasi dan koordinasi dengan pihak eksternal, sponsor, dan mitra acara.',
                'responsibilities' => [
                    'Berkomunikasi dengan sponsor dan partner',
                    'Mengatur meeting dan koordinasi',
                    'Membuat proposal kerjasama',
                    'Follow up kesepakatan dan kontrak',
                    'Membuat laporan kerjasama'
                ],
                'requirements' => [
                    'Kemampuan komunikasi yang excellent',
                    'Pengalaman dalam public relations',
                    'Dapat berbahasa Inggris dengan baik',
                    'Networking yang luas'
                ],
                'skills_required' => [
                    'Communication',
                    'Public Relations',
                    'Negotiation',
                    'English',
                    'Networking'
                ],
                'estimated_hours' => 70,
                'workload_level' => 'medium',
                'priority' => 'high',
                'required_members' => 2,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(50),
                'status' => 'active',
                'created_by' => $creator->id,
                'approved_by' => null,
                'approved_at' => null,
            ],
            [
                'structure_id' => $structures->first()->id,
                'event_id' => null,
                'title' => 'Finance Officer',
                'code' => 'JOB-007',
                'description' => 'Mengelola keuangan acara, membuat laporan keuangan, dan mengontrol budget.',
                'responsibilities' => [
                    'Membuat perencanaan budget',
                    'Mencatat semua transaksi keuangan',
                    'Membuat laporan keuangan berkala',
                    'Mengontrol pengeluaran',
                    'Koordinasi dengan treasurer'
                ],
                'requirements' => [
                    'Teliti dan detail-oriented',
                    'Memahami akuntansi dasar',
                    'Mahir menggunakan Excel',
                    'Jujur dan dapat dipercaya'
                ],
                'skills_required' => [
                    'Accounting',
                    'Microsoft Excel',
                    'Financial Planning',
                    'Reporting',
                    'Budgeting'
                ],
                'estimated_hours' => 90,
                'workload_level' => 'medium',
                'priority' => 'urgent',
                'required_members' => 1,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'draft',
                'created_by' => $creator->id,
                'approved_by' => null,
                'approved_at' => null,
            ],
            [
                'structure_id' => $structures->skip(1)->first()->id ?? $structures->first()->id,
                'event_id' => $events->first()->id ?? null,
                'title' => 'Social Media Specialist',
                'code' => 'JOB-008',
                'description' => 'Mengelola akun media sosial acara dan membuat strategi digital marketing.',
                'responsibilities' => [
                    'Mengelola akun Instagram, Twitter, TikTok',
                    'Membuat content calendar',
                    'Posting konten secara teratur',
                    'Monitoring dan engagement',
                    'Analisis performa media sosial'
                ],
                'requirements' => [
                    'Aktif di media sosial',
                    'Memahami tren digital marketing',
                    'Kreatif dalam membuat konten',
                    'Dapat menganalisis metrics'
                ],
                'skills_required' => [
                    'Social Media Management',
                    'Content Creation',
                    'Digital Marketing',
                    'Analytics',
                    'Engagement Strategy'
                ],
                'estimated_hours' => 50,
                'workload_level' => 'medium',
                'priority' => 'medium',
                'required_members' => 2,
                'assigned_members' => 0,
                'start_date' => Carbon::now()->addDays(4),
                'end_date' => Carbon::now()->addDays(35),
                'status' => 'active',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(1),
            ],
        ];

        // Insert Job Descriptions dan simpan untuk assignments
        $createdJobs = [];
        foreach ($jobDescriptions as $jobData) {
            $createdJobs[] = JobDescription::create($jobData);
            $this->command->info("Created: {$jobData['title']} ({$jobData['code']})");
        }

        // Buat Job Assignments untuk beberapa job yang sudah aktif
        if ($users->count() >= 3) {
            $assignableUsers = $users->skip(2)->take(5); // Ambil user untuk di-assign
            $assigner = $users->skip(1)->first() ?? $creator;

            $assignments = [
                [
                    'job_description_id' => $createdJobs[0]->id, // Koordinator Acara
                    'user_id' => $assignableUsers->first()->id,
                    'assigned_date' => Carbon::now()->subDays(3),
                    'expected_completion_date' => Carbon::now()->addDays(32),
                    'status' => 'in_progress',
                    'progress_percentage' => 25,
                    'notes' => 'Sudah mulai koordinasi dengan vendor',
                    'assigned_by' => $assigner->id,
                    'started_at' => Carbon::now()->subDays(2),
                ],
                [
                    'job_description_id' => $createdJobs[1]->id, // Desainer Grafis
                    'user_id' => $assignableUsers->skip(1)->first()->id,
                    'assigned_date' => Carbon::now()->subDays(2),
                    'expected_completion_date' => Carbon::now()->addDays(28),
                    'status' => 'in_progress',
                    'progress_percentage' => 40,
                    'notes' => 'Desain poster utama sudah selesai',
                    'assigned_by' => $assigner->id,
                    'started_at' => Carbon::now()->subDays(1),
                ],
                [
                    'job_description_id' => $createdJobs[1]->id, // Desainer Grafis (user ke-2)
                    'user_id' => $assignableUsers->skip(2)->first()->id,
                    'assigned_date' => Carbon::now()->subDays(2),
                    'expected_completion_date' => Carbon::now()->addDays(28),
                    'status' => 'assigned',
                    'progress_percentage' => 0,
                    'notes' => 'Akan fokus pada konten media sosial',
                    'assigned_by' => $assigner->id,
                ],
                [
                    'job_description_id' => $createdJobs[4]->id, // Web Developer
                    'user_id' => $assignableUsers->skip(3)->first()->id,
                    'assigned_date' => Carbon::now()->subDays(5),
                    'expected_completion_date' => Carbon::now()->addDays(40),
                    'status' => 'in_progress',
                    'progress_percentage' => 60,
                    'notes' => 'Sistem registrasi hampir selesai',
                    'assigned_by' => $assigner->id,
                    'started_at' => Carbon::now()->subDays(4),
                ],
                [
                    'job_description_id' => $createdJobs[7]->id, // Social Media Specialist
                    'user_id' => $assignableUsers->skip(4)->first()->id ?? $assignableUsers->first()->id,
                    'assigned_date' => Carbon::now()->subDays(1),
                    'expected_completion_date' => Carbon::now()->addDays(34),
                    'status' => 'assigned',
                    'progress_percentage' => 0,
                    'notes' => 'Sedang menyusun content calendar',
                    'assigned_by' => $assigner->id,
                ],
            ];

            foreach ($assignments as $assignmentData) {
                JobAssignment::create($assignmentData);

                // Update assigned_members count
                $job = JobDescription::find($assignmentData['job_description_id']);
                $job->increment('assigned_members');

                // Update status jika sudah filled
                if ($job->assigned_members >= $job->required_members) {
                    $job->update(['status' => 'filled']);
                }

                $this->command->info("Assigned user to: {$job->title}");
            }
        }

        $this->command->info('Job Descriptions and Assignments seeded successfully!');
    }
}
