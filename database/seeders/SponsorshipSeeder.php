<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SponsorshipSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $sponsorships = [
            [
                'event_id' => 1,
                'sponsor_code' => 'SPO-001',
                'company_name' => 'PT Tech Inovasi Indonesia',
                'contact_person' => 'Budi Santoso',
                'email' => 'budi.santoso@techinnovasi.co.id',
                'phone' => '021-5551234',
                'address' => 'Jl. Sudirman Kav 52-53, Jakarta Selatan 12190',
                'website' => 'https://techinnovasi.co.id',
                'tier' => 'platinum',
                'committed_amount' => 100000000.00,
                'received_amount' => 100000000.00,
                'outstanding_amount' => 0.00,
                'type' => 'cash',
                'in_kind_description' => null,
                'in_kind_value' => null,
                'benefits_package' => json_encode([
                    'Logo di semua materi promosi',
                    'Booth premium 6x6 meter',
                    'Speaking slot 30 menit',
                    'Publikasi di media sosial 10x',
                    '20 tiket VIP',
                    'Banner backdrop utama'
                ]),
                'logo_placements' => json_encode([
                    'Website event',
                    'Backdrop panggung utama',
                    'Semua materi cetak',
                    'Banner entrance',
                    'T-shirt panitia'
                ]),
                'deliverables' => json_encode([
                    'Laporan post-event',
                    'Database peserta',
                    'Foto dan video dokumentasi',
                    'Sertifikat apresiasi'
                ]),
                'status' => 'completed',
                'proposal_sent_date' => $now->copy()->subMonths(5),
                'commitment_date' => $now->copy()->subMonths(4),
                'contract_date' => $now->copy()->subMonths(4),
                'fulfillment_date' => $now->copy()->subMonth(1),
                'payment_schedule' => json_encode([
                    ['milestone' => 'Down Payment', 'amount' => 50000000, 'due_date' => $now->copy()->subMonths(3)->format('Y-m-d'), 'status' => 'paid'],
                    ['milestone' => 'Final Payment', 'amount' => 50000000, 'due_date' => $now->copy()->subMonths(2)->format('Y-m-d'), 'status' => 'paid']
                ]),
                'contract_document' => 'contracts/SPO-001-contract.pdf',
                'proposal_document' => 'proposals/SPO-001-proposal.pdf',
                'attachments' => json_encode(['reports/SPO-001-postevent.pdf']),
                'notes' => 'Sponsor platinum tahun ini. Sangat kooperatif dan puas dengan hasilnya.',
                'internal_notes' => 'Prioritaskan untuk tahun depan. Kemungkinan besar akan lanjut.',
                'pic_internal' => 1,
                'created_by' => 1,
                'created_at' => $now->copy()->subMonths(5),
                'updated_at' => $now->copy()->subMonth(1),
                'deleted_at' => null,
            ],
            [
                'event_id' => 1,
                'sponsor_code' => 'SPO-002',
                'company_name' => 'PT Digital Marketing Solution',
                'contact_person' => 'Siti Rahmawati',
                'email' => 'siti.r@digimarsol.com',
                'phone' => '021-5559876',
                'address' => 'Jl. Gatot Subroto Kav 27, Jakarta Selatan 12950',
                'website' => 'https://digimarsol.com',
                'tier' => 'gold',
                'committed_amount' => 50000000.00,
                'received_amount' => 50000000.00,
                'outstanding_amount' => 0.00,
                'type' => 'cash',
                'in_kind_description' => null,
                'in_kind_value' => null,
                'benefits_package' => json_encode([
                    'Logo di materi utama',
                    'Booth 4x4 meter',
                    'Publikasi di media sosial 5x',
                    '10 tiket VIP',
                    'Banner di area strategis'
                ]),
                'logo_placements' => json_encode([
                    'Website event',
                    'Backdrop panggung',
                    'Banner entrance',
                    'Katalog event'
                ]),
                'deliverables' => json_encode([
                    'Laporan post-event',
                    'Foto dokumentasi',
                    'Sertifikat apresiasi'
                ]),
                'status' => 'completed',
                'proposal_sent_date' => $now->copy()->subMonths(4),
                'commitment_date' => $now->copy()->subMonths(3),
                'contract_date' => $now->copy()->subMonths(3),
                'fulfillment_date' => $now->copy()->subMonth(1),
                'payment_schedule' => json_encode([
                    ['milestone' => 'Full Payment', 'amount' => 50000000, 'due_date' => $now->copy()->subMonths(2)->format('Y-m-d'), 'status' => 'paid']
                ]),
                'contract_document' => 'contracts/SPO-002-contract.pdf',
                'proposal_document' => 'proposals/SPO-002-proposal.pdf',
                'attachments' => null,
                'notes' => 'Pembayaran tepat waktu. Sponsor yang baik.',
                'internal_notes' => 'Hubungan baik dengan contact person.',
                'pic_internal' => 2,
                'created_by' => 1,
                'created_at' => $now->copy()->subMonths(4),
                'updated_at' => $now->copy()->subMonth(1),
                'deleted_at' => null,
            ],
            [
                'event_id' => 1,
                'sponsor_code' => 'SPO-003',
                'company_name' => 'CV Kreatif Media Nusantara',
                'contact_person' => 'Ahmad Fauzi',
                'email' => 'ahmad@kreatifmedia.id',
                'phone' => '021-5557654',
                'address' => 'Jl. Kuningan Barat No. 8, Jakarta Selatan 12710',
                'website' => 'https://kreatifmedia.id',
                'tier' => 'silver',
                'committed_amount' => 15000000.00,
                'received_amount' => 15000000.00,
                'outstanding_amount' => 0.00,
                'type' => 'mixed',
                'in_kind_description' => 'Jasa desain grafis untuk semua materi promosi event',
                'in_kind_value' => 10000000.00,
                'benefits_package' => json_encode([
                    'Logo di materi cetak',
                    'Booth 3x3 meter',
                    'Publikasi media sosial 3x',
                    '5 tiket reguler'
                ]),
                'logo_placements' => json_encode([
                    'Website event',
                    'Katalog event',
                    'Poster digital'
                ]),
                'deliverables' => json_encode([
                    'Foto dokumentasi',
                    'Sertifikat apresiasi'
                ]),
                'status' => 'completed',
                'proposal_sent_date' => $now->copy()->subMonths(4),
                'commitment_date' => $now->copy()->subMonths(3),
                'contract_date' => $now->copy()->subMonths(3),
                'fulfillment_date' => $now->copy()->subMonth(1),
                'payment_schedule' => json_encode([
                    ['milestone' => 'Cash Payment', 'amount' => 15000000, 'due_date' => $now->copy()->subMonths(2)->format('Y-m-d'), 'status' => 'paid']
                ]),
                'contract_document' => 'contracts/SPO-003-contract.pdf',
                'proposal_document' => 'proposals/SPO-003-proposal.pdf',
                'attachments' => null,
                'notes' => 'Sponsor dengan paket mixed. Hasil desain sangat memuaskan.',
                'internal_notes' => 'Partnership yang saling menguntungkan.',
                'pic_internal' => 2,
                'created_by' => 1,
                'created_at' => $now->copy()->subMonths(4),
                'updated_at' => $now->copy()->subMonth(1),
                'deleted_at' => null,
            ],
            [
                'event_id' => 2,
                'sponsor_code' => 'SPO-004',
                'company_name' => 'PT Telekomunikasi Global',
                'contact_person' => 'Rina Marlina',
                'email' => 'rina.marlina@telglobal.co.id',
                'phone' => '021-5558888',
                'address' => 'Jl. Jend. Sudirman Kav 10, Jakarta Pusat 10220',
                'website' => 'https://telglobal.co.id',
                'tier' => 'platinum',
                'committed_amount' => 150000000.00,
                'received_amount' => 75000000.00,
                'outstanding_amount' => 75000000.00,
                'type' => 'cash',
                'in_kind_description' => null,
                'in_kind_value' => null,
                'benefits_package' => json_encode([
                    'Logo di semua materi promosi',
                    'Booth premium 8x8 meter',
                    'Keynote speech slot',
                    'Publikasi di media sosial 15x',
                    '30 tiket VIP',
                    'Co-branding material'
                ]),
                'logo_placements' => json_encode([
                    'Semua platform digital',
                    'Semua backdrop',
                    'Semua materi cetak',
                    'Merchandise event'
                ]),
                'deliverables' => json_encode([
                    'Laporan lengkap post-event',
                    'Full database peserta',
                    'Video highlight profesional',
                    'Plakat apresiasi khusus'
                ]),
                'status' => 'confirmed',
                'proposal_sent_date' => $now->copy()->subMonths(2),
                'commitment_date' => $now->copy()->subMonth(1),
                'contract_date' => $now->copy()->subMonth(1),
                'fulfillment_date' => null,
                'payment_schedule' => json_encode([
                    ['milestone' => 'Down Payment 50%', 'amount' => 75000000, 'due_date' => $now->copy()->subMonth(1)->format('Y-m-d'), 'status' => 'paid'],
                    ['milestone' => 'Final Payment 50%', 'amount' => 75000000, 'due_date' => $now->copy()->addMonth(1)->format('Y-m-d'), 'status' => 'pending']
                ]),
                'contract_document' => 'contracts/SPO-004-contract.pdf',
                'proposal_document' => 'proposals/SPO-004-proposal.pdf',
                'attachments' => null,
                'notes' => 'Sponsor platinum untuk event tahun depan. Negosiasi berjalan lancar.',
                'internal_notes' => 'Perlu follow-up pembayaran kedua bulan depan.',
                'pic_internal' => 1,
                'created_by' => 1,
                'created_at' => $now->copy()->subMonths(2),
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'event_id' => 2,
                'sponsor_code' => 'SPO-005',
                'company_name' => 'Yayasan Pendidikan Teknologi',
                'contact_person' => 'Dr. Bambang Sutrisno',
                'email' => 'bambang@ypteknologi.org',
                'phone' => '021-5553456',
                'address' => 'Jl. Raya Cikarang No. 45, Bekasi 17550',
                'website' => 'https://ypteknologi.org',
                'tier' => 'partner',
                'committed_amount' => 0.00,
                'received_amount' => 0.00,
                'outstanding_amount' => 0.00,
                'type' => 'in_kind',
                'in_kind_description' => 'Menyediakan venue dan fasilitasnya, sound system profesional, dan tim teknis',
                'in_kind_value' => 50000000.00,
                'benefits_package' => json_encode([
                    'Logo sebagai venue partner',
                    'Mention di semua publikasi',
                    '10 tiket untuk mahasiswa',
                    'Booth informasi kampus'
                ]),
                'logo_placements' => json_encode([
                    'Website event',
                    'Sebagai venue partner',
                    'Materi cetak'
                ]),
                'deliverables' => json_encode([
                    'Foto dokumentasi',
                    'Sertifikat apresiasi'
                ]),
                'status' => 'confirmed',
                'proposal_sent_date' => $now->copy()->subMonths(3),
                'commitment_date' => $now->copy()->subMonths(2),
                'contract_date' => $now->copy()->subMonths(2),
                'fulfillment_date' => null,
                'payment_schedule' => null,
                'contract_document' => 'contracts/SPO-005-mou.pdf',
                'proposal_document' => 'proposals/SPO-005-proposal.pdf',
                'attachments' => null,
                'notes' => 'Partnership strategis dengan institusi pendidikan.',
                'internal_notes' => 'Venue partner yang sangat membantu. Koordinasi berjalan baik.',
                'pic_internal' => 2,
                'created_by' => 1,
                'created_at' => $now->copy()->subMonths(3),
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'event_id' => 2,
                'sponsor_code' => 'SPO-006',
                'company_name' => 'PT Startup Hub Indonesia',
                'contact_person' => 'Dian Kusuma',
                'email' => 'dian@startuphub.id',
                'phone' => '021-5552345',
                'address' => 'Jl. HR Rasuna Said Blok X-5, Jakarta Selatan 12950',
                'website' => 'https://startuphub.id',
                'tier' => 'gold',
                'committed_amount' => 60000000.00,
                'received_amount' => 0.00,
                'outstanding_amount' => 60000000.00,
                'type' => 'cash',
                'in_kind_description' => null,
                'in_kind_value' => null,
                'benefits_package' => json_encode([
                    'Logo di materi utama',
                    'Booth 5x5 meter',
                    'Workshop slot 1 jam',
                    'Publikasi di media sosial 7x',
                    '15 tiket VIP'
                ]),
                'logo_placements' => json_encode([
                    'Website event',
                    'Backdrop area workshop',
                    'Banner strategis'
                ]),
                'deliverables' => json_encode([
                    'Laporan post-event',
                    'Lead generation report',
                    'Foto dokumentasi'
                ]),
                'status' => 'negotiating',
                'proposal_sent_date' => $now->copy()->subWeeks(3),
                'commitment_date' => null,
                'contract_date' => null,
                'fulfillment_date' => null,
                'payment_schedule' => json_encode([
                    ['milestone' => 'Down Payment', 'amount' => 30000000, 'due_date' => null, 'status' => 'pending'],
                    ['milestone' => 'Final Payment', 'amount' => 30000000, 'due_date' => null, 'status' => 'pending']
                ]),
                'contract_document' => null,
                'proposal_document' => 'proposals/SPO-006-proposal.pdf',
                'attachments' => null,
                'notes' => 'Masih dalam tahap negosiasi paket benefits.',
                'internal_notes' => 'Follow-up meeting dijadwalkan minggu depan. Kemungkinan deal 80%.',
                'pic_internal' => 1,
                'created_by' => 1,
                'created_at' => $now->copy()->subWeeks(3),
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ];

        DB::table('sponsorships')->insert($sponsorships);
    }
}
