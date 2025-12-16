<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\User;
use Carbon\Carbon;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Get users for created_by
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $creator = $users->first();

        $this->command->info('Seeding Templates...');

        $templates = [
            // 1. Document - Surat Resmi
            [
                'template_code' => 'TPL-001',
                'name' => 'Template Surat Resmi Organisasi',
                'description' => 'Template surat resmi untuk keperluan organisasi dengan format standar dan kop surat yang profesional',
                'category' => 'document',
                'file_type' => 'docx',
                'file_path' => 'templates/files/surat_resmi.docx',
                'file_size' => 45620,
                'usage_instructions' => "1. Ganti {nama_organisasi} dengan nama organisasi Anda\n2. Isi {tanggal} dengan tanggal surat\n3. Ganti {tujuan_surat} dengan nama penerima\n4. Isi {isi_surat} dengan konten surat\n5. Tanda tangan di bagian bawah",
                'variables' => json_encode(['{nama_organisasi}', '{tanggal}', '{tujuan_surat}', '{isi_surat}', '{pengirim}']),
                'tags' => json_encode(['surat', 'resmi', 'organisasi', 'formal']),
                'preview_description' => 'Template surat dengan kop organisasi dan format profesional',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 234,
                'usage_count' => 456,
            ],

            // 2. Form - Formulir Pendaftaran
            [
                'template_code' => 'TPL-002',
                'name' => 'Formulir Pendaftaran Event',
                'description' => 'Form pendaftaran peserta event dengan fields lengkap untuk data diri dan kontak darurat',
                'category' => 'form',
                'file_type' => 'docx',
                'file_path' => 'templates/files/form_pendaftaran.docx',
                'file_size' => 38450,
                'usage_instructions' => "1. Sesuaikan {nama_event} dengan event Anda\n2. Edit field sesuai kebutuhan\n3. Print atau distribusikan online\n4. Kumpulkan dan arsipkan formulir yang sudah diisi",
                'variables' => json_encode(['{nama_event}', '{tanggal_event}', '{lokasi}', '{biaya}']),
                'tags' => json_encode(['form', 'pendaftaran', 'event', 'peserta']),
                'preview_description' => 'Formulir dengan fields data diri, kontak, dan persetujuan',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 567,
                'usage_count' => 892,
            ],

            // 3. Presentation - Proposal Event
            [
                'template_code' => 'TPL-003',
                'name' => 'Template Proposal Event Ramadhan',
                'description' => 'Presentation template untuk proposal event Ramadhan dengan design Islami yang menarik',
                'category' => 'presentation',
                'file_type' => 'pptx',
                'file_path' => 'templates/files/proposal_event.pptx',
                'file_size' => 2145600,
                'usage_instructions' => "1. Edit cover dengan judul event\n2. Isi latar belakang dan tujuan\n3. Detail timeline dan anggaran\n4. Tambahkan struktur panitia\n5. Sesuaikan design dengan brand event",
                'variables' => json_encode(['{judul_event}', '{tanggal}', '{lokasi}', '{budget}']),
                'tags' => json_encode(['proposal', 'event', 'ramadhan', 'presentation']),
                'preview_description' => 'Slide profesional dengan design Islami modern',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 178,
                'usage_count' => 245,
            ],

            // 4. Spreadsheet - Budget Planning
            [
                'template_code' => 'TPL-004',
                'name' => 'Template Budget Planning Event',
                'description' => 'Spreadsheet lengkap untuk perencanaan budget event dengan formula otomatis',
                'category' => 'spreadsheet',
                'file_type' => 'xlsx',
                'file_path' => 'templates/files/budget_planning.xlsx',
                'file_size' => 87340,
                'usage_instructions' => "1. Isi nama event di sheet pertama\n2. Input semua kategori pengeluaran\n3. Masukkan nominal di kolom Amount\n4. Formula akan menghitung total otomatis\n5. Review dan adjust sesuai kebutuhan",
                'variables' => json_encode(['{nama_event}', '{periode}', '{pic_finance}']),
                'tags' => json_encode(['budget', 'finance', 'planning', 'event']),
                'preview_description' => 'Spreadsheet dengan kategori lengkap dan auto-calculation',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 423,
                'usage_count' => 678,
            ],

            // 5. Certificate - Sertifikat Peserta
            [
                'template_code' => 'TPL-005',
                'name' => 'Sertifikat Peserta Workshop',
                'description' => 'Template sertifikat elegant untuk peserta workshop atau pelatihan',
                'category' => 'certificate',
                'file_type' => 'docx',
                'file_path' => 'templates/files/sertifikat_peserta.docx',
                'file_size' => 1245800,
                'usage_instructions' => "1. Ganti {nama_peserta} dengan nama penerima\n2. Isi {nama_workshop} dengan judul workshop\n3. Update {tanggal_pelaksanaan}\n4. Tanda tangan ketua panitia\n5. Print di kertas khusus sertifikat",
                'variables' => json_encode(['{nama_peserta}', '{nama_workshop}', '{tanggal_pelaksanaan}', '{ketua_panitia}']),
                'tags' => json_encode(['sertifikat', 'certificate', 'workshop', 'peserta']),
                'preview_description' => 'Design elegant dengan border dan ornamen profesional',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 891,
                'usage_count' => 1234,
            ],

            // 6. Email - Undangan Event
            [
                'template_code' => 'TPL-006',
                'name' => 'Template Email Undangan Event',
                'description' => 'Email template untuk undangan event dengan format HTML yang menarik',
                'category' => 'email',
                'file_type' => 'pdf',
                'file_path' => 'templates/files/email_undangan.pdf',
                'file_size' => 156890,
                'usage_instructions' => "1. Copy isi template ke email client\n2. Ganti semua variabel dengan data aktual\n3. Pastikan link registrasi berfungsi\n4. Test kirim ke email sendiri dulu\n5. Kirim massal dengan BCC",
                'variables' => json_encode(['{nama_penerima}', '{nama_event}', '{tanggal}', '{lokasi}', '{link_registrasi}']),
                'tags' => json_encode(['email', 'undangan', 'invitation', 'event']),
                'preview_description' => 'Email HTML responsive dengan call-to-action button',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 345,
                'usage_count' => 567,
            ],

            // 7. Report - Laporan Kegiatan
            [
                'template_code' => 'TPL-007',
                'name' => 'Template Laporan Pertanggungjawaban',
                'description' => 'Format lengkap untuk laporan pertanggungjawaban kegiatan event',
                'category' => 'report',
                'file_type' => 'docx',
                'file_path' => 'templates/files/lpj.docx',
                'file_size' => 67890,
                'usage_instructions' => "1. Isi cover dengan data event\n2. Tulis latar belakang dan tujuan\n3. Detail pelaksanaan kegiatan\n4. Lampirkan dokumentasi\n5. Tambahkan laporan keuangan\n6. Kesimpulan dan rekomendasi",
                'variables' => json_encode(['{nama_event}', '{tanggal_pelaksanaan}', '{lokasi}', '{jumlah_peserta}']),
                'tags' => json_encode(['laporan', 'lpj', 'report', 'pertanggungjawaban']),
                'preview_description' => 'Format laporan formal dengan struktur lengkap',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 289,
                'usage_count' => 412,
            ],

            // 8. Letter - Surat Izin Kegiatan
            [
                'template_code' => 'TPL-008',
                'name' => 'Surat Permohonan Izin Kegiatan',
                'description' => 'Template surat permohonan izin untuk mengadakan kegiatan di lokasi tertentu',
                'category' => 'letter',
                'file_type' => 'docx',
                'file_path' => 'templates/files/surat_izin.docx',
                'file_size' => 42100,
                'usage_instructions' => "1. Isi kop surat organisasi\n2. Tulis tujuan surat (instansi yang dituju)\n3. Jelaskan detail kegiatan\n4. Lampirkan proposal jika diminta\n5. Tanda tangan ketua panitia",
                'variables' => json_encode(['{nama_organisasi}', '{tujuan_surat}', '{nama_kegiatan}', '{tanggal_kegiatan}', '{lokasi}']),
                'tags' => json_encode(['surat', 'izin', 'permohonan', 'kegiatan']),
                'preview_description' => 'Surat formal dengan struktur permohonan yang jelas',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 156,
                'usage_count' => 234,
            ],

            // 9. Spreadsheet - Daftar Hadir
            [
                'template_code' => 'TPL-009',
                'name' => 'Template Daftar Hadir Peserta',
                'description' => 'Spreadsheet untuk mencatat kehadiran peserta event atau meeting',
                'category' => 'spreadsheet',
                'file_type' => 'xlsx',
                'file_path' => 'templates/files/daftar_hadir.xlsx',
                'file_size' => 34560,
                'usage_instructions' => "1. Print atau gunakan digital\n2. Isi header dengan nama kegiatan\n3. Peserta tanda tangan di kolom\n4. Scan atau foto setelah selesai\n5. Arsipkan untuk dokumentasi",
                'variables' => json_encode(['{nama_kegiatan}', '{tanggal}', '{lokasi}']),
                'tags' => json_encode(['daftar hadir', 'attendance', 'absensi', 'peserta']),
                'preview_description' => 'Table sederhana dengan kolom nama, institusi, TTD',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 678,
                'usage_count' => 945,
            ],

            // 10. Document - Surat Tugas
            [
                'template_code' => 'TPL-010',
                'name' => 'Template Surat Tugas Panitia',
                'description' => 'Surat penugasan resmi untuk anggota panitia event',
                'category' => 'document',
                'file_type' => 'docx',
                'file_path' => 'templates/files/surat_tugas.docx',
                'file_size' => 38900,
                'usage_instructions' => "1. Isi data lengkap anggota panitia\n2. Sebutkan posisi dan tanggung jawab\n3. Tulis periode penugasan\n4. Tanda tangan ketua dan sekretaris\n5. Berikan ke yang bersangkutan",
                'variables' => json_encode(['{nama_panitia}', '{posisi}', '{tanggung_jawab}', '{periode}']),
                'tags' => json_encode(['surat tugas', 'penugasan', 'panitia', 'assignment']),
                'preview_description' => 'Surat resmi dengan kop dan stempel organisasi',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 234,
                'usage_count' => 345,
            ],

            // 11. Presentation - Sponsorship Deck
            [
                'template_code' => 'TPL-011',
                'name' => 'Template Proposal Sponsorship',
                'description' => 'Presentation deck untuk menarik sponsor dengan package menarik',
                'category' => 'presentation',
                'file_type' => 'pptx',
                'file_path' => 'templates/files/sponsorship_deck.pptx',
                'file_size' => 3456700,
                'usage_instructions' => "1. Sesuaikan cover dengan brand event\n2. Highlight benefit untuk sponsor\n3. Detail package dan harga\n4. Tambahkan testimoni jika ada\n5. Clear call-to-action di akhir",
                'variables' => json_encode(['{nama_event}', '{target_peserta}', '{benefit_sponsor}']),
                'tags' => json_encode(['sponsorship', 'proposal', 'sponsor', 'deck']),
                'preview_description' => 'Slide profesional dengan package pricing table',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 145,
                'usage_count' => 189,
            ],

            // 12. Form - Feedback Event
            [
                'template_code' => 'TPL-012',
                'name' => 'Formulir Feedback Peserta Event',
                'description' => 'Form evaluasi untuk mengumpulkan feedback dari peserta event',
                'category' => 'form',
                'file_type' => 'docx',
                'file_path' => 'templates/files/feedback_form.docx',
                'file_size' => 45670,
                'usage_instructions' => "1. Distribusikan di akhir event\n2. Bisa dalam bentuk print atau Google Form\n3. Kumpulkan dan analisis feedback\n4. Gunakan untuk improvement event berikutnya",
                'variables' => json_encode(['{nama_event}', '{tanggal}', '{sesi}']),
                'tags' => json_encode(['feedback', 'evaluasi', 'survey', 'form']),
                'preview_description' => 'Form dengan rating scale dan open-ended questions',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 312,
                'usage_count' => 489,
            ],

            // 13. Certificate - Sertifikat Panitia
            [
                'template_code' => 'TPL-013',
                'name' => 'Sertifikat Apresiasi Panitia',
                'description' => 'Sertifikat penghargaan untuk anggota panitia yang telah berkontribusi',
                'category' => 'certificate',
                'file_type' => 'docx',
                'file_path' => 'templates/files/sertifikat_panitia.docx',
                'file_size' => 1567800,
                'usage_instructions' => "1. Input nama panitia\n2. Sebutkan posisi/divisi\n3. Tulis nama event\n4. TTD ketua dan sekretaris\n5. Print di kertas sertifikat",
                'variables' => json_encode(['{nama_panitia}', '{posisi}', '{nama_event}', '{periode}']),
                'tags' => json_encode(['sertifikat', 'panitia', 'apresiasi', 'penghargaan']),
                'preview_description' => 'Design modern dengan warna gold dan blue',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 523,
                'usage_count' => 678,
            ],

            // 14. Report - Financial Report
            [
                'template_code' => 'TPL-014',
                'name' => 'Template Laporan Keuangan Event',
                'description' => 'Format laporan keuangan lengkap dengan income statement',
                'category' => 'report',
                'file_type' => 'xlsx',
                'file_path' => 'templates/files/financial_report.xlsx',
                'file_size' => 123450,
                'usage_instructions' => "1. Input semua pemasukan di sheet Income\n2. Catat pengeluaran di sheet Expense\n3. Formula akan auto-calculate balance\n4. Review dan export ke PDF\n5. Lampirkan bukti transaksi",
                'variables' => json_encode(['{nama_event}', '{periode}', '{treasurer}']),
                'tags' => json_encode(['keuangan', 'finance', 'laporan', 'report']),
                'preview_description' => 'Spreadsheet dengan multiple sheets dan dashboard',
                'status' => 'active',
                'created_by' => $creator->id,
                'download_count' => 267,
                'usage_count' => 389,
            ],

            // 15. Document - DRAFT
            [
                'template_code' => 'TPL-015',
                'name' => '[DRAFT] Template Surat Kerjasama',
                'description' => 'Draft template untuk surat perjanjian kerjasama antar organisasi',
                'category' => 'document',
                'file_type' => 'docx',
                'file_path' => 'templates/files/surat_kerjasama_draft.docx',
                'file_size' => 56780,
                'usage_instructions' => "Template masih dalam tahap draft. Mohon tunggu versi final.",
                'variables' => json_encode(['{pihak_pertama}', '{pihak_kedua}', '{ruang_lingkup}']),
                'tags' => json_encode(['kerjasama', 'mou', 'partnership', 'draft']),
                'preview_description' => 'Draft template kerjasama dengan klausul standar',
                'status' => 'inactive',
                'created_by' => $creator->id,
                'download_count' => 12,
                'usage_count' => 23,
            ],
        ];

        foreach ($templates as $data) {
            Template::create($data);
        }

        // Summary
        $total = count($templates);
        $active = collect($templates)->where('status', 'active')->count();
        $inactive = collect($templates)->where('status', 'inactive')->count();

        $this->command->info("✅ Seeded {$total} Templates:");
        $this->command->info("   ✓ Active: {$active}");
        $this->command->info("   ✗ Inactive: {$inactive}");
        $this->command->info('');

        // By Category
        $this->command->info('By Category:');
        $categories = collect($templates)->groupBy('category')->map->count();
        foreach ($categories as $category => $count) {
            $this->command->info("   {$category}: {$count}");
        }
        $this->command->info('');

        // By File Type
        $this->command->info('By File Type:');
        $fileTypes = collect($templates)->groupBy('file_type')->map->count();
        foreach ($fileTypes as $type => $count) {
            $this->command->info("   {$type}: {$count}");
        }
        $this->command->info('');

        // Total Stats
        $totalDownloads = collect($templates)->sum('download_count');
        $totalUsage = collect($templates)->sum('usage_count');
        $this->command->info("Total Downloads: " . number_format($totalDownloads));
        $this->command->info("Total Usage: " . number_format($totalUsage));
    }
}
