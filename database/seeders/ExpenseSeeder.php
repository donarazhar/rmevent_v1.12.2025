<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use App\Models\Event;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\BudgetAllocation;
use App\Models\CommitteeStructure;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get required data
        $users = User::limit(5)->get();
        $events = Event::limit(3)->get();
        $budgets = Budget::limit(2)->get();
        $budgetItems = BudgetItem::limit(3)->get();
        $allocations = BudgetAllocation::limit(3)->get();
        $structures = CommitteeStructure::limit(3)->get();

        if ($users->count() < 1) {
            $this->command->warn('⚠️  No users found. Please run UserSeeder first.');
            return;
        }

        if ($events->count() < 1) {
            $this->command->warn('⚠️  No events found. Please run EventSeeder first.');
            return;
        }

        $requester = $users->first();
        $reviewer = $users->count() > 1 ? $users[1] : $requester;
        $approver = $users->count() > 2 ? $users[2] : $requester;
        $payer = $users->count() > 3 ? $users[3] : $requester;
        $event = $events->first();
        $budget = $budgets->first();
        $budgetItem = $budgetItems->first();
        $allocation = $allocations->first();
        $structure = $structures->first();

        $expenses = [
            // 1. PAID - Sewa Venue (event_execution)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => $budgetItem->id ?? null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0001',
                'title' => 'Sewa Venue Seminar Nasional 2024',
                'description' => 'Sewa auditorium untuk seminar nasional 3 hari termasuk sound system dan proyektor',
                'category' => 'event_execution',
                'vendor_name' => 'PT Venue Eksekutif Indonesia',
                'vendor_contact' => '021-12345678',
                'vendor_address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'vendor_tax_id' => '01.234.567.8-901.000',
                'requested_amount' => 15000000.00,
                'approved_amount' => 15000000.00,
                'paid_amount' => 15000000.00,
                'tax_amount' => 1650000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => true,
                'request_date' => now()->subDays(30),
                'needed_by_date' => now()->subDays(20),
                'approved_date' => now()->subDays(28),
                'payment_date' => now()->subDays(27),
                'payment_method' => 'bank_transfer',
                'bank_account' => 'BCA - 1234567890 a.n. PT Venue Eksekutif',
                'payment_reference' => 'TRF20241215001',
                'status' => 'paid',
                'invoice_file' => 'expenses/invoices/INV-VEN-2024-001.pdf',
                'receipt_file' => 'expenses/receipts/RCP-VEN-2024-001.pdf',
                'supporting_documents' => ['contract.pdf', 'quotation.pdf'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(29),
                'review_notes' => 'Quotation sudah diverifikasi, vendor terpercaya',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(28),
                'approved_date' => now()->subDays(28),
                'approval_notes' => 'Sesuai dengan proposal acara yang disetujui',
                'paid_by' => $payer->id,
                'paid_at' => now()->subDays(27),
                'notes' => 'Venue sudah dikonfirmasi dan siap digunakan untuk tanggal 15-17 Februari 2024. Termasuk fasilitas AC, wifi, dan cleaning service.',
            ],

            // 2. PAID - Katering (meals)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => $budgetItem->id ?? null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0002',
                'title' => 'Katering Konsumsi 300 Peserta',
                'description' => 'Paket makan siang dan snack untuk 300 peserta selama 3 hari (900 pax total)',
                'category' => 'meals',
                'vendor_name' => 'CV Rasa Nusantara Catering',
                'vendor_contact' => '021-98765432',
                'vendor_address' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan',
                'vendor_tax_id' => '02.345.678.9-012.000',
                'requested_amount' => 27000000.00,
                'approved_amount' => 27000000.00,
                'paid_amount' => 27000000.00,
                'tax_amount' => 2970000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => true,
                'request_date' => now()->subDays(25),
                'needed_by_date' => now()->subDays(15),
                'approved_date' => now()->subDays(23),
                'payment_date' => now()->subDays(22),
                'payment_method' => 'bank_transfer',
                'bank_account' => 'Mandiri - 9876543210 a.n. CV Rasa Nusantara',
                'payment_reference' => 'TRF20241218002',
                'status' => 'paid',
                'invoice_file' => 'expenses/invoices/INV-CAT-2024-001.pdf',
                'receipt_file' => 'expenses/receipts/RCP-CAT-2024-001.pdf',
                'supporting_documents' => ['menu_list.pdf', 'tasting_photos.jpg'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(24),
                'review_notes' => 'Katering sudah diverifikasi kualitasnya dengan tasting session',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(23),
                'approved_date' => now()->subDays(23),
                'approval_notes' => 'Disetujui, menu sudah disesuaikan dengan budget',
                'paid_by' => $payer->id,
                'paid_at' => now()->subDays(22),
                'notes' => 'Menu: Nasi box dengan lauk ayam/ikan, sayur, buah, snack, dan minuman. DP 50% sudah dibayar, pelunasan H-3 sebelum acara.',
            ],

            // 3. APPROVED - Honorarium Narasumber (honorarium)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => $budgetItem->id ?? null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0003',
                'title' => 'Honorarium 5 Narasumber Seminar',
                'description' => 'Honorarium untuk 5 narasumber ahli @ Rp 5.000.000',
                'category' => 'honorarium',
                'vendor_name' => 'Multiple Speakers',
                'vendor_contact' => 'Various',
                'vendor_address' => null,
                'vendor_tax_id' => 'Various NPWP',
                'requested_amount' => 25000000.00,
                'approved_amount' => 25000000.00,
                'paid_amount' => null,
                'tax_amount' => 1250000.00,
                'tax_type' => 'PPh 21 (5%)',
                'has_tax_invoice' => false,
                'request_date' => now()->subDays(20),
                'needed_by_date' => now()->addDays(5),
                'approved_date' => now()->subDays(18),
                'payment_date' => null,
                'payment_method' => 'bank_transfer',
                'bank_account' => 'Multiple accounts (will be specified)',
                'payment_reference' => null,
                'status' => 'approved',
                'invoice_file' => null,
                'receipt_file' => null,
                'supporting_documents' => ['speaker_list.xlsx', 'cv_speakers.pdf'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(19),
                'review_notes' => 'CV dan track record narasumber sudah diverifikasi',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(18),
                'approved_date' => now()->subDays(18),
                'approval_notes' => 'Honorarium sesuai standar untuk narasumber tingkat nasional',
                'paid_by' => null,
                'paid_at' => null,
                'notes' => 'Pembayaran akan dilakukan setelah acara selesai dengan bukti kehadiran (attendance sheet dan foto). Potongan PPh 21 sudah dihitung.',
            ],

            // 4. SUBMITTED - Transport dan Akomodasi (transportation + accommodation)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0004',
                'title' => 'Transport dan Akomodasi Panitia Luar Kota',
                'description' => 'Biaya transportasi (flight/train) dan penginapan 20 panitia dari luar kota untuk persiapan dan pelaksanaan event',
                'category' => 'transportation',
                'vendor_name' => 'PT Nusantara Travel & Hotel',
                'vendor_contact' => '021-55566677',
                'vendor_address' => 'Jl. Thamrin No. 88, Jakarta Pusat',
                'vendor_tax_id' => '03.456.789.0-123.000',
                'requested_amount' => 12000000.00,
                'approved_amount' => null,
                'paid_amount' => null,
                'tax_amount' => 0.00,
                'tax_type' => null,
                'has_tax_invoice' => false,
                'request_date' => now()->subDays(15),
                'needed_by_date' => now()->addDays(10),
                'approved_date' => null,
                'payment_date' => null,
                'payment_method' => 'bank_transfer',
                'bank_account' => 'BCA - 5566778899 a.n. PT Nusantara Travel',
                'payment_reference' => null,
                'status' => 'submitted',
                'invoice_file' => null,
                'receipt_file' => null,
                'supporting_documents' => ['quotation.pdf', 'panitia_list.xlsx'],
                'requested_by' => $requester->id,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_notes' => null,
                'approved_by' => null,
                'approved_at' => null,
                'approved_date' => null,
                'approval_notes' => null,
                'paid_by' => null,
                'paid_at' => null,
                'notes' => 'Estimasi biaya: Transport Rp 400.000/orang, Hotel Rp 200.000/malam x 2 malam. Total 20 orang dari Bandung, Surabaya, dan Yogyakarta.',
            ],

            // 5. PAID - Printing (logistics)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => $budgetItem->id ?? null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0005',
                'title' => 'Cetak Sertifikat dan Materi Peserta',
                'description' => 'Cetak 300 sertifikat art carton + 300 buku materi seminar hardcover full color 200 halaman',
                'category' => 'logistics',
                'vendor_name' => 'CV Mitra Grafika Printing',
                'vendor_contact' => '021-77788899',
                'vendor_address' => 'Jl. Gatot Subroto No. 100, Jakarta Selatan',
                'vendor_tax_id' => '04.567.890.1-234.000',
                'requested_amount' => 9000000.00,
                'approved_amount' => 9000000.00,
                'paid_amount' => 9000000.00,
                'tax_amount' => 990000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => true,
                'request_date' => now()->subDays(22),
                'needed_by_date' => now()->subDays(10),
                'approved_date' => now()->subDays(20),
                'payment_date' => now()->subDays(19),
                'payment_method' => 'bank_transfer',
                'bank_account' => 'BNI - 5432167890 a.n. CV Mitra Grafika',
                'payment_reference' => 'TRF20241220003',
                'status' => 'paid',
                'invoice_file' => 'expenses/invoices/INV-PRT-2024-001.pdf',
                'receipt_file' => 'expenses/receipts/RCP-PRT-2024-001.pdf',
                'supporting_documents' => ['design_approval.pdf', 'sample_photos.jpg'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(21),
                'review_notes' => 'Sample dan desain sudah disetujui tim kreatif',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(20),
                'approved_date' => now()->subDays(20),
                'approval_notes' => 'Disetujui, pastikan kualitas cetak sesuai sample',
                'paid_by' => $payer->id,
                'paid_at' => now()->subDays(19),
                'notes' => 'Sertifikat menggunakan art carton 310gsm dengan foil gold. Buku materi hardcover full color 200 halaman. Barang sudah diterima dan sesuai pesanan.',
            ],

            // 6. REJECTED - Promosi Digital (marketing)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => null,
                'budget_allocation_id' => null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0006',
                'title' => 'Iklan Facebook & Instagram Ads',
                'description' => 'Budget iklan digital untuk promosi acara selama 2 minggu dengan target reach 50.000 orang',
                'category' => 'marketing',
                'vendor_name' => 'Meta Platforms Ireland Limited',
                'vendor_contact' => 'ads@facebook.com',
                'vendor_address' => 'Online Platform',
                'vendor_tax_id' => null,
                'requested_amount' => 5000000.00,
                'approved_amount' => null,
                'paid_amount' => null,
                'tax_amount' => 550000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => false,
                'request_date' => now()->subDays(12),
                'needed_by_date' => now()->addDays(3),
                'approved_date' => null,
                'payment_date' => null,
                'payment_method' => 'other',
                'bank_account' => null,
                'payment_reference' => null,
                'status' => 'rejected',
                'invoice_file' => null,
                'receipt_file' => null,
                'supporting_documents' => ['ad_proposal.pdf', 'target_audience.xlsx'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(10),
                'review_notes' => 'Budget marketing sudah melebihi alokasi',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(10),
                'approved_date' => null,
                'approval_notes' => null,
                'paid_by' => null,
                'paid_at' => null,
                'rejection_reason' => 'Budget marketing untuk bulan ini sudah habis. Gunakan promosi organik melalui komunitas dan influencer terlebih dahulu. Bisa diajukan ulang bulan depan jika masih diperlukan.',
            ],

            // 7. PAID - Merchandise (logistics)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => $budgetItem->id ?? null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0007',
                'title' => 'Merchandise Peserta (300 set)',
                'description' => 'Goodie bag berisi: totebag canvas, pulpen premium, notes A5, tumbler stainless, dan sticker',
                'category' => 'logistics',
                'vendor_name' => 'PT Kreatif Merchandise Indonesia',
                'vendor_contact' => '021-22334455',
                'vendor_address' => 'Jl. Sudirman No. 200, Jakarta Pusat',
                'vendor_tax_id' => '05.678.901.2-345.000',
                'requested_amount' => 18000000.00,
                'approved_amount' => 18000000.00,
                'paid_amount' => 18000000.00,
                'tax_amount' => 1980000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => true,
                'request_date' => now()->subDays(18),
                'needed_by_date' => now()->subDays(5),
                'approved_date' => now()->subDays(16),
                'payment_date' => now()->subDays(15),
                'payment_method' => 'bank_transfer',
                'bank_account' => 'BRI - 7654321098 a.n. PT Kreatif Merchandise',
                'payment_reference' => 'TRF20241225004',
                'status' => 'paid',
                'invoice_file' => 'expenses/invoices/INV-MER-2024-001.pdf',
                'receipt_file' => 'expenses/receipts/RCP-MER-2024-001.pdf',
                'supporting_documents' => ['mockup.pdf', 'sample_photos.jpg'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(17),
                'review_notes' => 'Sample merchandise sudah dicek dan approved',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(16),
                'approved_date' => now()->subDays(16),
                'approval_notes' => 'Desain merchandise sudah sesuai branding event',
                'paid_by' => $payer->id,
                'paid_at' => now()->subDays(15),
                'notes' => 'Totebag canvas tebal dengan sablon 2 sisi, tumbler stainless 500ml, notes hardcover A5, pulpen metal premium. DP 50% di awal, pelunasan saat barang diterima.',
            ],

            // 8. UNDER_REVIEW - Dokumentasi (equipment)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0008',
                'title' => 'Jasa Dokumentasi Profesional',
                'description' => 'Tim dokumentasi foto dan video selama 3 hari acara + editing video highlight 10 menit',
                'category' => 'equipment',
                'vendor_name' => 'CV Visual Media Production',
                'vendor_contact' => '0812-3456-7890',
                'vendor_address' => 'Jl. Kemang Raya No. 50, Jakarta Selatan',
                'vendor_tax_id' => '06.789.012.3-456.000',
                'requested_amount' => 8000000.00,
                'approved_amount' => null,
                'paid_amount' => null,
                'tax_amount' => 160000.00,
                'tax_type' => 'PPh 23 (2%)',
                'has_tax_invoice' => false,
                'request_date' => now()->subDays(10),
                'needed_by_date' => now()->addDays(15),
                'approved_date' => null,
                'payment_date' => null,
                'payment_method' => 'bank_transfer',
                'bank_account' => 'BCA - 1122334455 a.n. CV Visual Media',
                'payment_reference' => null,
                'status' => 'under_review',
                'invoice_file' => null,
                'receipt_file' => null,
                'supporting_documents' => ['portfolio.pdf', 'contract_draft.docx'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(8),
                'review_notes' => 'Portfolio bagus, sedang diverifikasi ketersediaan jadwal vendor',
                'approved_by' => null,
                'approved_at' => null,
                'approved_date' => null,
                'approval_notes' => null,
                'paid_by' => null,
                'paid_at' => null,
                'notes' => 'Deliverables: 500 foto edited high-res, 1 video highlight 10 menit, raw files (foto dan video). Team 2 fotografer dan 2 videografer.',
            ],

            // 9. DRAFT - Dekorasi (equipment)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => null,
                'budget_allocation_id' => null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0009',
                'title' => 'Dekorasi Venue dan Backdrop',
                'description' => 'Setup dekorasi panggung, backdrop utama 6x3m, 10 standing banner, dan rangkaian bunga',
                'category' => 'equipment',
                'vendor_name' => 'CV Kreasi Dekorasi',
                'vendor_contact' => '0813-4567-8901',
                'vendor_address' => 'Jl. Senopati No. 75, Jakarta Selatan',
                'vendor_tax_id' => '07.890.123.4-567.000',
                'requested_amount' => 6500000.00,
                'approved_amount' => null,
                'paid_amount' => null,
                'tax_amount' => 715000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => true,
                'request_date' => now()->subDays(8),
                'needed_by_date' => now()->addDays(7),
                'approved_date' => null,
                'payment_date' => null,
                'payment_method' => 'bank_transfer',
                'bank_account' => 'Mandiri - 3344556677 a.n. CV Kreasi Dekorasi',
                'payment_reference' => null,
                'status' => 'draft',
                'invoice_file' => null,
                'receipt_file' => null,
                'supporting_documents' => ['design_mockup.jpg'],
                'requested_by' => $requester->id,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_notes' => null,
                'approved_by' => null,
                'approved_at' => null,
                'approved_date' => null,
                'approval_notes' => null,
                'paid_by' => null,
                'paid_at' => null,
                'notes' => 'Masih draft, menunggu finalisasi desain dari tim kreatif. Backdrop 6x3m dengan cetak digital, 10 standing banner 60x160cm, rangkaian bunga segar untuk panggung.',
            ],

            // 10. PAID - Sound System (equipment)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => $budgetItem->id ?? null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0010',
                'title' => 'Sewa Sound System dan Lighting Professional',
                'description' => 'Rental audio visual equipment lengkap termasuk operator selama 3 hari event',
                'category' => 'equipment',
                'vendor_name' => 'PT Audio Visual Nusantara',
                'vendor_contact' => '021-88899900',
                'vendor_address' => 'Jl. Rasuna Said No. 150, Jakarta Selatan',
                'vendor_tax_id' => '08.901.234.5-678.000',
                'requested_amount' => 7500000.00,
                'approved_amount' => 7500000.00,
                'paid_amount' => 7500000.00,
                'tax_amount' => 825000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => true,
                'request_date' => now()->subDays(14),
                'needed_by_date' => now()->subDays(1),
                'approved_date' => now()->subDays(12),
                'payment_date' => now()->subDays(11),
                'payment_method' => 'bank_transfer',
                'bank_account' => 'Mandiri - 2233445566 a.n. PT Audio Visual Nusantara',
                'payment_reference' => 'TRF20241228005',
                'status' => 'paid',
                'invoice_file' => 'expenses/invoices/INV-AV-2024-001.pdf',
                'receipt_file' => 'expenses/receipts/RCP-AV-2024-001.pdf',
                'supporting_documents' => ['equipment_list.pdf', 'contract.pdf'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(13),
                'review_notes' => 'Vendor sudah berpengalaman untuk event sejenis, equipment sesuai kebutuhan',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(12),
                'approved_date' => now()->subDays(12),
                'approval_notes' => 'Approved, pastikan technical meeting dilakukan H-2',
                'paid_by' => $payer->id,
                'paid_at' => now()->subDays(11),
                'notes' => 'Include: 4 mic wireless + 2 standing mic, mixer 16 channel, speaker line array, LED screen 3x2m, proyektor 10.000 lumens, lighting paket, 2 operator. Pembayaran 100% di muka sesuai terms vendor.',
            ],

            // 11. APPROVED - ATK (operational)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => null,
                'budget_allocation_id' => $allocation->id ?? null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0011',
                'title' => 'ATK dan Perlengkapan Panitia',
                'description' => 'Alat tulis kantor, name tag, clipboard, map folder, dan keperluan operasional event',
                'category' => 'operational',
                'vendor_name' => 'Toko Sinar Jaya Stationery',
                'vendor_contact' => '021-33445566',
                'vendor_address' => 'Jl. Mangga Dua Raya No. 20, Jakarta Utara',
                'vendor_tax_id' => '09.012.345.6-789.000',
                'requested_amount' => 2500000.00,
                'approved_amount' => 2500000.00,
                'paid_amount' => null,
                'tax_amount' => 275000.00,
                'tax_type' => 'PPN 11%',
                'has_tax_invoice' => true,
                'request_date' => now()->subDays(7),
                'needed_by_date' => now()->addDays(3),
                'approved_date' => now()->subDays(5),
                'payment_date' => null,
                'payment_method' => 'cash',
                'bank_account' => null,
                'payment_reference' => null,
                'status' => 'approved',
                'invoice_file' => null,
                'receipt_file' => null,
                'supporting_documents' => ['shopping_list.xlsx'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(6),
                'review_notes' => 'Daftar kebutuhan sudah sesuai dengan jumlah panitia',
                'approved_by' => $approver->id,
                'approved_at' => now()->subDays(5),
                'approved_date' => now()->subDays(5),
                'approval_notes' => 'Approved, beli di toko yang memberikan invoice resmi',
                'paid_by' => null,
                'paid_at' => null,
                'notes' => 'Item: 50 clipboard, 100 name tag + holder, 500 pulpen, 100 notes A5, 50 map folder, 10 box stapler + isi, 20 cutter, 50 spidol, selotip, double tape, dll. Pembelian di toko ATK lokal dengan nota resmi.',
            ],

            // 12. CANCELLED - Website Development (other)
            [
                'event_id' => $event->id ?? null,
                'budget_id' => $budget->id ?? null,
                'budget_item_id' => null,
                'budget_allocation_id' => null,
                'structure_id' => $structure->id ?? null,
                'expense_code' => 'EXP-202412-0012',
                'title' => 'Development Website Event Khusus',
                'description' => 'Pembuatan website event dengan sistem registrasi online, payment gateway, dan dashboard admin',
                'category' => 'other',
                'vendor_name' => 'PT Teknologi Digital Indonesia',
                'vendor_contact' => '021-44556677',
                'vendor_address' => 'Jl. Kuningan No. 88, Jakarta Selatan',
                'vendor_tax_id' => '10.123.456.7-890.000',
                'requested_amount' => 10000000.00,
                'approved_amount' => null,
                'paid_amount' => null,
                'tax_amount' => 200000.00,
                'tax_type' => 'PPh 23 (2%)',
                'has_tax_invoice' => false,
                'request_date' => now()->subDays(35),
                'needed_by_date' => now()->subDays(20),
                'approved_date' => null,
                'payment_date' => null,
                'payment_method' => 'bank_transfer',
                'bank_account' => null,
                'payment_reference' => null,
                'status' => 'cancelled',
                'invoice_file' => null,
                'receipt_file' => null,
                'supporting_documents' => ['proposal.pdf', 'wireframe.pdf'],
                'requested_by' => $requester->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(33),
                'review_notes' => null,
                'approved_by' => null,
                'approved_at' => null,
                'approved_date' => null,
                'approval_notes' => null,
                'paid_by' => null,
                'paid_at' => null,
                'notes' => 'CANCELLED - Keputusan panitia untuk menggunakan platform existing (Eventbrite) karena lebih cepat dan cost-effective. Development website khusus memakan waktu terlalu lama.',
            ],
        ];

        $createdCount = 0;
        foreach ($expenses as $expenseData) {
            try {
                Expense::create($expenseData);
                $createdCount++;
            } catch (\Exception $e) {
                $this->command->warn("⚠️  Failed to create expense: {$expenseData['expense_code']} - {$e->getMessage()}");
            }
        }

        // Summary
        $this->command->info("✅ Successfully created {$createdCount} expense records");
        $this->command->newLine();

        // Statistics
        $totalRequested = Expense::sum('requested_amount');
        $totalApproved = Expense::whereNotNull('approved_amount')->sum('approved_amount');
        $totalPaid = Expense::whereNotNull('paid_amount')->sum('paid_amount');

        $this->command->info('📊 Expense Statistics:');
        $this->command->info('   Total Requested: Rp ' . number_format($totalRequested, 0, ',', '.'));
        $this->command->info('   Total Approved: Rp ' . number_format($totalApproved, 0, ',', '.'));
        $this->command->info('   Total Paid: Rp ' . number_format($totalPaid, 0, ',', '.'));
        $this->command->newLine();

        $this->command->info('📋 Status Distribution:');
        $statuses = ['paid', 'approved', 'under_review', 'submitted', 'draft', 'rejected', 'cancelled'];
        foreach ($statuses as $status) {
            $count = Expense::where('status', $status)->count();
            if ($count > 0) {
                $amount = Expense::where('status', $status)->sum('requested_amount');
                $this->command->info("   " . ucfirst(str_replace('_', ' ', $status)) . ": {$count} expenses (Rp " . number_format($amount, 0, ',', '.') . ")");
            }
        }
        $this->command->newLine();

        $this->command->info('🏷️  Category Distribution:');
        $categories = Expense::select('category')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(requested_amount) as total')
            ->groupBy('category')
            ->get();

        foreach ($categories as $cat) {
            $this->command->info('   ' . ucfirst(str_replace('_', ' ', $cat->category)) . ': ' .
                $cat->count . ' expenses (Rp ' . number_format($cat->total, 0, ',', '.') . ')');
        }
    }
}
