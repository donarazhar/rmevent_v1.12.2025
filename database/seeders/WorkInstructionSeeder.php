<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkInstruction;
use App\Models\SOP;
use App\Models\User;
use Carbon\Carbon;

class WorkInstructionSeeder extends Seeder
{
    public function run(): void
    {
        // Get users for created_by and approved_by
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $creator = $users->first();
        $approver = $users->count() > 1 ? $users->skip(1)->first() : $creator;

        // Get SOPs if available
        $sops = SOP::all();

        $this->command->info('Seeding Work Instructions...');

        $instructions = [
            // 1. Setup - Easy
            [
                'sop_id' => $sops->isNotEmpty() ? $sops->random()->id : null,
                'instruction_code' => 'WI-001',
                'title' => 'Setup Email untuk Panitia',
                'description' => 'Panduan lengkap untuk setup email panitia menggunakan Gmail',
                'category' => 'setup',
                'content' => "Panduan ini menjelaskan cara setup email panitia untuk memastikan komunikasi yang efektif.\n\nLangkah-langkah meliputi pembuatan akun, konfigurasi, dan testing.",
                'steps' => json_encode([
                    [
                        'title' => 'Buat Akun Gmail',
                        'description' => 'Kunjungi gmail.com dan klik Create Account. Isi form dengan data panitia.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Konfigurasi Security',
                        'description' => 'Aktifkan 2-Factor Authentication untuk keamanan tambahan.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Setup Signature',
                        'description' => 'Buat email signature dengan logo dan kontak panitia.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Test Email',
                        'description' => 'Kirim test email ke semua anggota panitia untuk memastikan semua berfungsi.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Computer/Laptop', 'Internet Connection', 'Logo Panitia']),
                'materials_required' => json_encode(['Email template', 'Contact list']),
                'safety_notes' => 'Jangan share password ke orang yang tidak authorized.',
                'precautions' => json_encode([
                    'Pastikan password kuat minimal 12 karakter',
                    'Backup recovery email dan phone number',
                    'Jangan gunakan public WiFi saat setup',
                ]),
                'estimated_time' => 30,
                'difficulty_level' => 'easy',
                'version' => '1.0',
                'effective_date' => Carbon::now()->subDays(30),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(25),
                'view_count' => 45,
                'download_count' => 12,
            ],

            // 2. Execution - Medium
            [
                'sop_id' => $sops->isNotEmpty() ? $sops->random()->id : null,
                'instruction_code' => 'WI-002',
                'title' => 'Proses Registrasi Peserta Event',
                'description' => 'Prosedur standar untuk melakukan registrasi peserta event secara online dan offline',
                'category' => 'execution',
                'content' => "Panduan lengkap untuk proses registrasi peserta event.\n\nMencakup verifikasi data, pembayaran, dan konfirmasi.",
                'steps' => json_encode([
                    [
                        'title' => 'Buka Form Registrasi',
                        'description' => 'Akses link registrasi dan pastikan form ter-load dengan sempurna.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Input Data Peserta',
                        'description' => 'Isi semua field yang required: nama, email, phone, institusi.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Upload Dokumen',
                        'description' => 'Upload KTP/KTM dan bukti pembayaran sesuai format yang ditentukan.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Verifikasi Data',
                        'description' => 'Cross-check semua data yang diinput sebelum submit.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Submit & Konfirmasi',
                        'description' => 'Submit form dan catat registration code untuk tracking.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Computer', 'Scanner/Camera', 'Internet']),
                'materials_required' => json_encode(['KTP/KTM', 'Bukti Pembayaran', 'Pas Foto']),
                'safety_notes' => 'Pastikan data pribadi peserta terlindungi sesuai aturan GDPR.',
                'precautions' => json_encode([
                    'Validasi semua dokumen sebelum approve',
                    'Jangan accept pembayaran di luar sistem',
                    'Backup data registrasi setiap hari',
                ]),
                'estimated_time' => 15,
                'difficulty_level' => 'medium',
                'version' => '2.1',
                'effective_date' => Carbon::now()->subDays(20),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(18),
                'view_count' => 128,
                'download_count' => 34,
            ],

            // 3. Troubleshooting - Medium
            [
                'sop_id' => null,
                'instruction_code' => 'WI-003',
                'title' => 'Troubleshooting Payment Gateway Error',
                'description' => 'Panduan untuk mengatasi masalah umum pada payment gateway',
                'category' => 'troubleshooting',
                'content' => "Dokumen ini berisi solusi untuk masalah payment gateway yang sering terjadi.\n\nTermasuk timeout, failed transaction, dan duplicate payment.",
                'steps' => json_encode([
                    [
                        'title' => 'Identifikasi Error Code',
                        'description' => 'Catat error code yang muncul dan timestamp-nya.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Check API Status',
                        'description' => 'Verifikasi status API payment gateway di status page mereka.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Verify Transaction Log',
                        'description' => 'Check database untuk melihat apakah transaksi tercatat.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Retry atau Refund',
                        'description' => 'Tentukan apakah perlu retry payment atau process refund.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Database Access', 'API Documentation', 'Admin Panel']),
                'materials_required' => json_encode(['Error Log', 'Transaction ID']),
                'safety_notes' => 'Jangan pernah share API key atau credentials.',
                'precautions' => json_encode([
                    'Backup database sebelum melakukan perubahan',
                    'Dokumentasikan semua troubleshooting steps',
                    'Inform user tentang status transaksi mereka',
                ]),
                'estimated_time' => 45,
                'difficulty_level' => 'medium',
                'version' => '1.3',
                'effective_date' => Carbon::now()->subDays(15),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(14),
                'view_count' => 67,
                'download_count' => 18,
            ],

            // 4. Maintenance - Easy
            [
                'sop_id' => null,
                'instruction_code' => 'WI-004',
                'title' => 'Daily Database Backup',
                'description' => 'Prosedur backup database harian untuk disaster recovery',
                'category' => 'maintenance',
                'content' => "Backup database adalah critical task yang harus dilakukan setiap hari.\n\nPanduan ini memastikan data selalu aman dan dapat di-recover.",
                'steps' => json_encode([
                    [
                        'title' => 'Login ke Server',
                        'description' => 'SSH ke server menggunakan credentials yang authorized.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Run Backup Script',
                        'description' => 'Execute backup script dengan command: ./backup.sh',
                        'image' => '',
                    ],
                    [
                        'title' => 'Verify Backup File',
                        'description' => 'Check apakah backup file ter-create dengan size yang sesuai.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Upload to Cloud',
                        'description' => 'Upload backup file ke Google Drive atau AWS S3.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['SSH Client', 'Backup Script', 'Cloud Storage']),
                'materials_required' => json_encode(['Server Credentials', 'Backup Checklist']),
                'safety_notes' => 'Encrypt backup file sebelum upload ke cloud.',
                'precautions' => json_encode([
                    'Jangan delete old backup sebelum verify new backup',
                    'Test restore procedure secara berkala',
                    'Keep backup di minimal 2 lokasi berbeda',
                ]),
                'estimated_time' => 20,
                'difficulty_level' => 'easy',
                'version' => '1.0',
                'effective_date' => Carbon::now()->subDays(60),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(58),
                'view_count' => 89,
                'download_count' => 23,
            ],

            // 5. Reporting - Medium
            [
                'sop_id' => $sops->isNotEmpty() ? $sops->random()->id : null,
                'instruction_code' => 'WI-005',
                'title' => 'Generate Monthly Financial Report',
                'description' => 'Cara membuat laporan keuangan bulanan yang comprehensive',
                'category' => 'reporting',
                'content' => "Laporan keuangan bulanan harus dibuat tepat waktu dan akurat.\n\nPanduan ini membantu ensure konsistensi format dan data.",
                'steps' => json_encode([
                    [
                        'title' => 'Export Data Transaksi',
                        'description' => 'Export semua transaksi income dan expense untuk periode yang ditentukan.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Kategorisasi Transaksi',
                        'description' => 'Group transaksi berdasarkan kategori dan sub-kategori.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Calculate Totals',
                        'description' => 'Hitung total income, expense, dan net balance.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Create Visualization',
                        'description' => 'Buat chart dan graph untuk memudahkan pemahaman.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Review & Submit',
                        'description' => 'Review dengan treasurer sebelum submit ke steering committee.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Excel/Google Sheets', 'Accounting Software', 'Chart Tool']),
                'materials_required' => json_encode(['Transaction Data', 'Budget Plan', 'Report Template']),
                'safety_notes' => 'Pastikan data keuangan tidak bocor ke pihak unauthorized.',
                'precautions' => json_encode([
                    'Double-check semua perhitungan',
                    'Pastikan semua transaksi tercatat',
                    'Save report dengan password protection',
                ]),
                'estimated_time' => 120,
                'difficulty_level' => 'medium',
                'version' => '1.2',
                'effective_date' => Carbon::now()->subDays(10),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(8),
                'view_count' => 52,
                'download_count' => 15,
            ],

            // 6. Execution - Hard
            [
                'sop_id' => null,
                'instruction_code' => 'WI-006',
                'title' => 'Setup Live Streaming untuk Event',
                'description' => 'Panduan teknis untuk setup live streaming event ke multiple platform',
                'category' => 'execution',
                'content' => "Live streaming membutuhkan persiapan teknis yang matang.\n\nDokumen ini cover setup hardware, software, dan troubleshooting.",
                'steps' => json_encode([
                    [
                        'title' => 'Persiapan Hardware',
                        'description' => 'Setup camera, microphone, dan lighting sesuai dengan venue.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Konfigurasi Encoder',
                        'description' => 'Setup OBS Studio dengan bitrate dan resolution yang optimal.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Connect to Platform',
                        'description' => 'Koneksikan ke YouTube, Instagram, dan Zoom secara simultan.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Audio Check',
                        'description' => 'Test audio dari multiple sources dan pastikan tidak ada feedback.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Test Stream',
                        'description' => 'Lakukan test stream 30 menit sebelum event dimulai.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Go Live',
                        'description' => 'Start streaming dan monitor quality sepanjang event.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Camera', 'Microphone', 'Laptop', 'OBS Studio', 'Internet']),
                'materials_required' => json_encode(['Stream Keys', 'Overlay Graphics', 'Backup Laptop']),
                'safety_notes' => 'Pastikan backup internet connection tersedia.',
                'precautions' => json_encode([
                    'Test semua equipment 1 hari sebelum event',
                    'Siapkan backup plan jika primary system fail',
                    'Monitor bandwidth usage sepanjang stream',
                    'Have technical support team on standby',
                ]),
                'estimated_time' => 180,
                'difficulty_level' => 'hard',
                'version' => '1.0',
                'effective_date' => Carbon::now()->subDays(5),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(3),
                'view_count' => 34,
                'download_count' => 11,
            ],

            // 7. Setup - Draft
            [
                'sop_id' => null,
                'instruction_code' => 'WI-007',
                'title' => 'Setup WhatsApp Business untuk Customer Service',
                'description' => 'Draft: Panduan setup WhatsApp Business API untuk automated responses',
                'category' => 'setup',
                'content' => "Work instruction ini masih dalam tahap draft.\n\nAkan di-update dengan detail teknis yang lebih lengkap.",
                'steps' => json_encode([
                    [
                        'title' => 'Register Business Account',
                        'description' => 'Daftar WhatsApp Business API melalui Facebook Business Manager.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Setup Auto-Reply',
                        'description' => 'Configure automated responses untuk pertanyaan umum.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Facebook Business Manager', 'Phone Number', 'Computer']),
                'materials_required' => json_encode(['Business Documents', 'Message Templates']),
                'safety_notes' => null,
                'precautions' => json_encode(['Verify business documents', 'Test all automated messages']),
                'estimated_time' => 60,
                'difficulty_level' => 'medium',
                'version' => '0.1',
                'effective_date' => Carbon::now(),
                'status' => 'draft',
                'created_by' => $creator->id,
                'approved_by' => null,
                'approved_at' => null,
                'view_count' => 5,
                'download_count' => 0,
            ],

            // 8. Troubleshooting - Hard
            [
                'sop_id' => null,
                'instruction_code' => 'WI-008',
                'title' => 'Server Recovery dari Crash',
                'description' => 'Emergency procedure untuk recover server yang crash',
                'category' => 'troubleshooting',
                'content' => "Critical guide untuk handle server crash dan minimize downtime.\n\nHarus dilakukan dengan cepat dan akurat.",
                'steps' => json_encode([
                    [
                        'title' => 'Assess Situation',
                        'description' => 'Cek status server dan identifikasi root cause dari crash.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Attempt Restart',
                        'description' => 'Try soft restart terlebih dahulu sebelum hard restart.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Check Error Logs',
                        'description' => 'Analyze error logs untuk understand what went wrong.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Restore from Backup',
                        'description' => 'Jika restart gagal, restore dari last good backup.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Verify System',
                        'description' => 'Test semua functionality sebelum declare system operational.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Server Access', 'Backup Files', 'Monitoring Tools']),
                'materials_required' => json_encode(['Recovery Checklist', 'Contact List']),
                'safety_notes' => 'CRITICAL: Jangan panic. Follow procedure dengan calm dan methodical.',
                'precautions' => json_encode([
                    'Document setiap step yang dilakukan',
                    'Inform stakeholders tentang situation',
                    'Jangan modify production system without backup',
                ]),
                'estimated_time' => 240,
                'difficulty_level' => 'hard',
                'version' => '1.1',
                'effective_date' => Carbon::now()->subDays(45),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(43),
                'view_count' => 23,
                'download_count' => 8,
            ],

            // 9. Maintenance - Medium  
            [
                'sop_id' => null,
                'instruction_code' => 'WI-009',
                'title' => 'Website Performance Optimization',
                'description' => 'Panduan untuk optimize website performance dan loading speed',
                'category' => 'maintenance',
                'content' => "Website performance directly impact user experience.\n\nOptimization rutin perlu dilakukan untuk maintain speed.",
                'steps' => json_encode([
                    [
                        'title' => 'Run Performance Audit',
                        'description' => 'Use Google PageSpeed Insights untuk analyze current performance.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Optimize Images',
                        'description' => 'Compress semua images dan convert ke WebP format.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Enable Caching',
                        'description' => 'Configure browser caching dan server-side caching.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Minify Assets',
                        'description' => 'Minify CSS, JavaScript, dan HTML files.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Test Results',
                        'description' => 'Run performance test lagi dan compare dengan baseline.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['PageSpeed Insights', 'Image Optimizer', 'Code Minifier']),
                'materials_required' => json_encode(['Website Access', 'Performance Baseline']),
                'safety_notes' => 'Backup website sebelum melakukan optimization.',
                'precautions' => json_encode([
                    'Test di staging environment dulu',
                    'Monitor website after deployment',
                    'Keep original files sebelum optimization',
                ]),
                'estimated_time' => 90,
                'difficulty_level' => 'medium',
                'version' => '1.0',
                'effective_date' => Carbon::now()->subDays(12),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(10),
                'view_count' => 41,
                'download_count' => 13,
            ],

            // 10. Reporting - Easy
            [
                'sop_id' => null,
                'instruction_code' => 'WI-010',
                'title' => 'Weekly Progress Report',
                'description' => 'Format dan cara membuat weekly progress report',
                'category' => 'reporting',
                'content' => "Weekly report membantu track progress dan identify bottlenecks.\n\nHarus submitted setiap Jumat pukul 17:00.",
                'steps' => json_encode([
                    [
                        'title' => 'List Completed Tasks',
                        'description' => 'List semua tasks yang completed dalam minggu ini.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Identify Blockers',
                        'description' => 'Note any blockers atau challenges yang dihadapi.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Plan Next Week',
                        'description' => 'Outline tasks untuk minggu depan dengan priorities.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Submit Report',
                        'description' => 'Submit report melalui email atau project management tool.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Email', 'Report Template', 'Task List']),
                'materials_required' => json_encode(['Completed Task List', 'Next Week Plan']),
                'safety_notes' => null,
                'precautions' => json_encode(['Be honest about challenges', 'Highlight achievements']),
                'estimated_time' => 30,
                'difficulty_level' => 'easy',
                'version' => '1.0',
                'effective_date' => Carbon::now()->subDays(40),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(38),
                'view_count' => 156,
                'download_count' => 42,
            ],

            // 11. Other - Archived
            [
                'sop_id' => null,
                'instruction_code' => 'WI-011',
                'title' => '[ARCHIVED] Old Registration System',
                'description' => 'Archived: Legacy registration system yang sudah diganti',
                'category' => 'other',
                'content' => "Dokumen ini sudah tidak aktif digunakan.\n\nDiganti dengan system baru yang lebih efficient.",
                'steps' => json_encode([]),
                'tools_required' => json_encode([]),
                'materials_required' => json_encode([]),
                'safety_notes' => null,
                'precautions' => json_encode([]),
                'estimated_time' => 0,
                'difficulty_level' => 'easy',
                'version' => '0.9',
                'effective_date' => Carbon::now()->subDays(180),
                'status' => 'archived',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(178),
                'view_count' => 234,
                'download_count' => 67,
            ],

            // 12. Setup - Easy - Recent
            [
                'sop_id' => null,
                'instruction_code' => 'WI-012',
                'title' => 'Setup Zoom Meeting untuk Workshop',
                'description' => 'Quick guide untuk setup Zoom meeting dengan proper configuration',
                'category' => 'setup',
                'content' => "Zoom meeting setup yang proper ensure professional workshop experience.\n\nPanduan ini cover basic sampai advanced settings.",
                'steps' => json_encode([
                    [
                        'title' => 'Schedule Meeting',
                        'description' => 'Buat new meeting di Zoom dengan tanggal dan waktu yang tepat.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Configure Settings',
                        'description' => 'Set waiting room, mute on entry, dan recording options.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Add Co-hosts',
                        'description' => 'Assign co-hosts untuk membantu manage participants.',
                        'image' => '',
                    ],
                    [
                        'title' => 'Share Meeting Link',
                        'description' => 'Send meeting link via email dengan clear instructions.',
                        'image' => '',
                    ],
                ]),
                'tools_required' => json_encode(['Zoom Account', 'Computer', 'Internet']),
                'materials_required' => json_encode(['Participant List', 'Meeting Agenda']),
                'safety_notes' => 'Jangan share meeting link publicly untuk avoid zoom-bombing.',
                'precautions' => json_encode([
                    'Enable waiting room untuk control entry',
                    'Test audio/video sebelum meeting',
                    'Prepare backup plan jika Zoom down',
                ]),
                'estimated_time' => 15,
                'difficulty_level' => 'easy',
                'version' => '1.0',
                'effective_date' => Carbon::now()->subDays(2),
                'status' => 'published',
                'created_by' => $creator->id,
                'approved_by' => $approver->id,
                'approved_at' => Carbon::now()->subDays(1),
                'view_count' => 78,
                'download_count' => 19,
            ],
        ];

        foreach ($instructions as $data) {
            WorkInstruction::create($data);
        }

        // Summary
        $total = count($instructions);
        $published = collect($instructions)->where('status', 'published')->count();
        $draft = collect($instructions)->where('status', 'draft')->count();
        $archived = collect($instructions)->where('status', 'archived')->count();

        $this->command->info("✅ Seeded {$total} Work Instructions:");
        $this->command->info("   📘 Published: {$published}");
        $this->command->info("   📝 Draft: {$draft}");
        $this->command->info("   📦 Archived: {$archived}");
        $this->command->info('');

        // By Category
        $this->command->info('By Category:');
        $categories = collect($instructions)->groupBy('category')->map->count();
        foreach ($categories as $category => $count) {
            $this->command->info("   {$category}: {$count}");
        }
        $this->command->info('');

        // By Difficulty
        $this->command->info('By Difficulty:');
        $difficulties = collect($instructions)->groupBy('difficulty_level')->map->count();
        foreach ($difficulties as $difficulty => $count) {
            $this->command->info("   {$difficulty}: {$count}");
        }
    }
}
