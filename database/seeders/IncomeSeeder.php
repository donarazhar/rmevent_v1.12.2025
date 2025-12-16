<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\Event;
use App\Models\Budget;
use App\Models\EventRegistration;
use App\Models\Sponsorship;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();
        $budgets = Budget::where('status', 'active')->get();
        $registrations = EventRegistration::where('status', 'confirmed')->get();
        $sponsorships = Sponsorship::whereIn('status', ['confirmed', 'delivered'])->get();
        $users = User::all();

        if ($events->isEmpty()) {
            $this->command->warn('Please run EventSeeder first!');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('Please run UserSeeder first!');
            return;
        }

        $admin = $users->where('role', 'admin')->first() ?? $users->first();
        $verifier = $users->where('role', 'admin')->skip(1)->first() ?? $users->first();
        $currentYear = date('Y');

        $incomes = [];

        // ==========================================
        // REGISTRATION FEE INCOMES
        // ==========================================

        if ($registrations->count() > 0) {
            $registrationIncomes = [
                [
                    'event_id' => $events->first()->id,
                    'budget_id' => $budgets->first()->id ?? null,
                    'income_code' => 'IN-2024-001',
                    'title' => 'Pembayaran Registrasi - Ahmad Fadli',
                    'description' => 'Pembayaran biaya registrasi event seminar nasional',
                    'category' => 'registration_fee',
                    'source_name' => 'Ahmad Fadli',
                    'source_contact' => '081234567890',
                    'source_email' => 'ahmad.fadli@email.com',
                    'registration_id' => $registrations->first()->id,
                    'sponsorship_id' => null,
                    'amount' => 500000.00,
                    'payment_method' => 'bank_transfer',
                    'payment_reference' => 'TRF20240115001',
                    'bank_account' => 'BCA 1234567890',
                    'payment_date' => Carbon::create($currentYear, 1, 15),
                    'received_date' => Carbon::create($currentYear, 1, 15),
                    'receipt_number' => 'RCP-202401-0001',
                    'receipt_file' => null,
                    'status' => 'verified',
                    'verified_by' => $verifier->id,
                    'verified_at' => Carbon::create($currentYear, 1, 16, 10, 0),
                    'verification_notes' => 'Pembayaran verified, sesuai dengan bukti transfer',
                    'attachments' => null,
                    'notes' => 'Pembayaran tepat waktu',
                    'recorded_by' => $admin->id,
                    'created_at' => Carbon::create($currentYear, 1, 15, 14, 30),
                    'updated_at' => Carbon::create($currentYear, 1, 16, 10, 0),
                ],
                [
                    'event_id' => $events->first()->id,
                    'budget_id' => $budgets->first()->id ?? null,
                    'income_code' => 'IN-2024-002',
                    'title' => 'Pembayaran Registrasi - Siti Nurhaliza',
                    'description' => 'Pembayaran biaya registrasi workshop',
                    'category' => 'registration_fee',
                    'source_name' => 'Siti Nurhaliza',
                    'source_contact' => '082345678901',
                    'source_email' => 'siti.nur@email.com',
                    'registration_id' => $registrations->skip(1)->first()->id ?? $registrations->first()->id,
                    'sponsorship_id' => null,
                    'amount' => 350000.00,
                    'payment_method' => 'e_wallet',
                    'payment_reference' => 'GOPAY-20240116-ABC123',
                    'bank_account' => null,
                    'payment_date' => Carbon::create($currentYear, 1, 16),
                    'received_date' => Carbon::create($currentYear, 1, 16),
                    'receipt_number' => 'RCP-202401-0002',
                    'receipt_file' => null,
                    'status' => 'verified',
                    'verified_by' => $verifier->id,
                    'verified_at' => Carbon::create($currentYear, 1, 17, 9, 15),
                    'verification_notes' => 'Verified via e-wallet',
                    'attachments' => null,
                    'notes' => null,
                    'recorded_by' => $admin->id,
                    'created_at' => Carbon::create($currentYear, 1, 16, 16, 0),
                    'updated_at' => Carbon::create($currentYear, 1, 17, 9, 15),
                ],
                [
                    'event_id' => $events->first()->id,
                    'budget_id' => $budgets->first()->id ?? null,
                    'income_code' => 'IN-2024-003',
                    'title' => 'Pembayaran Registrasi - Budi Santoso',
                    'description' => 'Pembayaran registrasi pending verification',
                    'category' => 'registration_fee',
                    'source_name' => 'Budi Santoso',
                    'source_contact' => '083456789012',
                    'source_email' => 'budi.s@email.com',
                    'registration_id' => $registrations->skip(2)->first()->id ?? $registrations->first()->id,
                    'sponsorship_id' => null,
                    'amount' => 500000.00,
                    'payment_method' => 'bank_transfer',
                    'payment_reference' => 'TRF20240120005',
                    'bank_account' => 'Mandiri 9876543210',
                    'payment_date' => Carbon::create($currentYear, 1, 20),
                    'received_date' => Carbon::create($currentYear, 1, 20),
                    'receipt_number' => null,
                    'receipt_file' => null,
                    'status' => 'pending',
                    'verified_by' => null,
                    'verified_at' => null,
                    'verification_notes' => null,
                    'attachments' => null,
                    'notes' => 'Menunggu verifikasi dari finance',
                    'recorded_by' => $admin->id,
                    'created_at' => Carbon::create($currentYear, 1, 20, 11, 30),
                    'updated_at' => Carbon::create($currentYear, 1, 20, 11, 30),
                ],
            ];

            $incomes = array_merge($incomes, $registrationIncomes);
        }

        // ==========================================
        // SPONSORSHIP INCOMES
        // ==========================================

        if ($sponsorships->count() > 0) {
            $sponsorshipIncomes = [
                [
                    'event_id' => $events->first()->id,
                    'budget_id' => $budgets->first()->id ?? null,
                    'income_code' => 'IN-2024-004',
                    'title' => 'Sponsorship PT Teknologi Maju - Termin 1',
                    'description' => 'Pembayaran sponsorship termin pertama dari PT Teknologi Maju',
                    'category' => 'sponsorship',
                    'source_name' => 'PT Teknologi Maju',
                    'source_contact' => '021-12345678',
                    'source_email' => 'finance@teknologi.com',
                    'registration_id' => null,
                    'sponsorship_id' => $sponsorships->first()->id,
                    'amount' => 25000000.00,
                    'payment_method' => 'bank_transfer',
                    'payment_reference' => 'TRF-SPONSOR-001',
                    'bank_account' => 'BCA 1234567890',
                    'payment_date' => Carbon::create($currentYear, 1, 25),
                    'received_date' => Carbon::create($currentYear, 1, 25),
                    'receipt_number' => 'RCP-202401-0003',
                    'receipt_file' => null,
                    'status' => 'verified',
                    'verified_by' => $verifier->id,
                    'verified_at' => Carbon::create($currentYear, 1, 26, 13, 0),
                    'verification_notes' => 'Verified - Termin 1 dari 2',
                    'attachments' => null,
                    'notes' => 'Masih ada termin 2 sebesar Rp 25 juta',
                    'recorded_by' => $admin->id,
                    'created_at' => Carbon::create($currentYear, 1, 25, 15, 20),
                    'updated_at' => Carbon::create($currentYear, 1, 26, 13, 0),
                ],
                [
                    'event_id' => $events->first()->id,
                    'budget_id' => $budgets->first()->id ?? null,
                    'income_code' => 'IN-2024-005',
                    'title' => 'Sponsorship CV Kreatif Digital',
                    'description' => 'Sponsorship penuh dari CV Kreatif Digital',
                    'category' => 'sponsorship',
                    'source_name' => 'CV Kreatif Digital',
                    'source_contact' => '021-98765432',
                    'source_email' => 'info@kreatif.com',
                    'registration_id' => null,
                    'sponsorship_id' => $sponsorships->skip(1)->first()->id ?? $sponsorships->first()->id,
                    'amount' => 15000000.00,
                    'payment_method' => 'bank_transfer',
                    'payment_reference' => 'TRF202401280010',
                    'bank_account' => 'BNI 5555666677',
                    'payment_date' => Carbon::create($currentYear, 1, 28),
                    'received_date' => Carbon::create($currentYear, 1, 28),
                    'receipt_number' => 'RCP-202401-0004',
                    'receipt_file' => null,
                    'status' => 'verified',
                    'verified_by' => $verifier->id,
                    'verified_at' => Carbon::create($currentYear, 1, 29, 10, 30),
                    'verification_notes' => 'Sponsorship tier silver, verified',
                    'attachments' => null,
                    'notes' => null,
                    'recorded_by' => $admin->id,
                    'created_at' => Carbon::create($currentYear, 1, 28, 14, 45),
                    'updated_at' => Carbon::create($currentYear, 1, 29, 10, 30),
                ],
            ];

            $incomes = array_merge($incomes, $sponsorshipIncomes);
        }

        // ==========================================
        // DONATION INCOMES
        // ==========================================

        $donationIncomes = [
            [
                'event_id' => $events->first()->id,
                'budget_id' => $budgets->first()->id ?? null,
                'income_code' => 'IN-2024-006',
                'title' => 'Donasi dari Alumni 2015',
                'description' => 'Donasi dari kelompok alumni angkatan 2015',
                'category' => 'donation',
                'source_name' => 'Alumni 2015 Group',
                'source_contact' => '081234567899',
                'source_email' => 'alumni2015@email.com',
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 10000000.00,
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'DONASI-ALM2015-001',
                'bank_account' => 'BCA 1234567890',
                'payment_date' => Carbon::create($currentYear, 2, 1),
                'received_date' => Carbon::create($currentYear, 2, 1),
                'receipt_number' => 'RCP-202402-0001',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 2, 9, 0),
                'verification_notes' => 'Donasi verified dengan terima kasih',
                'attachments' => null,
                'notes' => 'Donasi untuk mendukung kegiatan ramadhan',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 1, 16, 0),
                'updated_at' => Carbon::create($currentYear, 2, 2, 9, 0),
            ],
            [
                'event_id' => $events->first()->id,
                'budget_id' => $budgets->first()->id ?? null,
                'income_code' => 'IN-2024-007',
                'title' => 'Donasi Anonim',
                'description' => 'Donasi dari donatur yang tidak ingin disebutkan namanya',
                'category' => 'donation',
                'source_name' => 'Hamba Allah',
                'source_contact' => null,
                'source_email' => null,
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 5000000.00,
                'payment_method' => 'cash',
                'payment_reference' => null,
                'bank_account' => null,
                'payment_date' => Carbon::create($currentYear, 2, 5),
                'received_date' => Carbon::create($currentYear, 2, 5),
                'receipt_number' => 'RCP-202402-0002',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 5, 11, 15),
                'verification_notes' => 'Cash donation, verified langsung',
                'attachments' => null,
                'notes' => 'Donasi tunai diserahkan langsung ke bendahara',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 5, 10, 30),
                'updated_at' => Carbon::create($currentYear, 2, 5, 11, 15),
            ],
        ];

        $incomes = array_merge($incomes, $donationIncomes);

        // ==========================================
        // INFAQ INCOMES
        // ==========================================

        $infaqIncomes = [
            [
                'event_id' => $events->first()->id,
                'budget_id' => $budgets->first()->id ?? null,
                'income_code' => 'IN-2024-008',
                'title' => 'Infaq Jum\'at Berkah',
                'description' => 'Kumpulan infaq dari kegiatan Jum\'at Berkah',
                'category' => 'infaq',
                'source_name' => 'Jamaah Jum\'at Berkah',
                'source_contact' => null,
                'source_email' => null,
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 3500000.00,
                'payment_method' => 'cash',
                'payment_reference' => null,
                'bank_account' => null,
                'payment_date' => Carbon::create($currentYear, 2, 9),
                'received_date' => Carbon::create($currentYear, 2, 9),
                'receipt_number' => 'RCP-202402-0003',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 10, 8, 30),
                'verification_notes' => 'Infaq terkumpul dari kotak amal',
                'attachments' => null,
                'notes' => 'Total dari 3 kotak amal di masjid',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 9, 15, 0),
                'updated_at' => Carbon::create($currentYear, 2, 10, 8, 30),
            ],
            [
                'event_id' => $events->first()->id,
                'budget_id' => null,
                'income_code' => 'IN-2024-009',
                'title' => 'Infaq Online Platform',
                'description' => 'Infaq dari platform donasi online',
                'category' => 'infaq',
                'source_name' => 'Donatur Online (Berbagai)',
                'source_contact' => null,
                'source_email' => 'donation@platform.com',
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 7500000.00,
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'PLATFORM-FEB2024',
                'bank_account' => 'BCA 1234567890',
                'payment_date' => Carbon::create($currentYear, 2, 15),
                'received_date' => Carbon::create($currentYear, 2, 15),
                'receipt_number' => 'RCP-202402-0004',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 16, 14, 0),
                'verification_notes' => 'Verified dari laporan platform',
                'attachments' => null,
                'notes' => 'Akumulasi infaq online selama Februari minggu 1-2',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 15, 16, 30),
                'updated_at' => Carbon::create($currentYear, 2, 16, 14, 0),
            ],
        ];

        $incomes = array_merge($incomes, $infaqIncomes);

        // ==========================================
        // MERCHANDISE INCOMES
        // ==========================================

        $merchandiseIncomes = [
            [
                'event_id' => $events->first()->id,
                'budget_id' => null,
                'income_code' => 'IN-2024-010',
                'title' => 'Penjualan Kaos Event',
                'description' => 'Penjualan merchandise kaos event ramadhan',
                'category' => 'merchandise',
                'source_name' => 'Penjualan Kolektif',
                'source_contact' => null,
                'source_email' => null,
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 4500000.00,
                'payment_method' => 'cash',
                'payment_reference' => null,
                'bank_account' => null,
                'payment_date' => Carbon::create($currentYear, 2, 18),
                'received_date' => Carbon::create($currentYear, 2, 18),
                'receipt_number' => 'RCP-202402-0005',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 19, 10, 0),
                'verification_notes' => 'Verified - 90 kaos terjual @ Rp 50.000',
                'attachments' => null,
                'notes' => 'Penjualan kaos event selama minggu ke-3 Februari',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 18, 17, 0),
                'updated_at' => Carbon::create($currentYear, 2, 19, 10, 0),
            ],
            [
                'event_id' => $events->first()->id,
                'budget_id' => null,
                'income_code' => 'IN-2024-011',
                'title' => 'Penjualan Mug & Tumbler',
                'description' => 'Penjualan merchandise mug dan tumbler',
                'category' => 'merchandise',
                'source_name' => 'Penjualan Kolektif',
                'source_contact' => null,
                'source_email' => null,
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 2750000.00,
                'payment_method' => 'e_wallet',
                'payment_reference' => 'QRIS-MERCH-FEB24',
                'bank_account' => null,
                'payment_date' => Carbon::create($currentYear, 2, 20),
                'received_date' => Carbon::create($currentYear, 2, 20),
                'receipt_number' => null,
                'receipt_file' => null,
                'status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'verification_notes' => null,
                'attachments' => null,
                'notes' => 'Menunggu rekap final penjualan',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 20, 15, 30),
                'updated_at' => Carbon::create($currentYear, 2, 20, 15, 30),
            ],
        ];

        $incomes = array_merge($incomes, $merchandiseIncomes);

        // ==========================================
        // GRANT INCOMES
        // ==========================================

        $grantIncomes = [
            [
                'event_id' => $events->first()->id,
                'budget_id' => $budgets->first()->id ?? null,
                'income_code' => 'IN-2024-012',
                'title' => 'Hibah dari Yayasan Pendidikan',
                'description' => 'Hibah untuk kegiatan dakwah ramadhan',
                'category' => 'grant',
                'source_name' => 'Yayasan Pendidikan Islam',
                'source_contact' => '021-87654321',
                'source_email' => 'hibah@yayasan.org',
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 20000000.00,
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'GRANT-YPI-2024-001',
                'bank_account' => 'BCA 1234567890',
                'payment_date' => Carbon::create($currentYear, 2, 22),
                'received_date' => Carbon::create($currentYear, 2, 22),
                'receipt_number' => 'RCP-202402-0006',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 23, 9, 0),
                'verification_notes' => 'Hibah verified sesuai MOU',
                'attachments' => null,
                'notes' => 'Grant untuk mendukung program kajian dan santunan',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 22, 14, 0),
                'updated_at' => Carbon::create($currentYear, 2, 23, 9, 0),
            ],
        ];

        $incomes = array_merge($incomes, $grantIncomes);

        // ==========================================
        // OTHER INCOMES
        // ==========================================

        $otherIncomes = [
            [
                'event_id' => $events->first()->id,
                'budget_id' => null,
                'income_code' => 'IN-2024-013',
                'title' => 'Penjualan Konsumsi',
                'description' => 'Penjualan paket konsumsi saat event',
                'category' => 'other',
                'source_name' => 'Penjualan Snack & Minuman',
                'source_contact' => null,
                'source_email' => null,
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 1500000.00,
                'payment_method' => 'cash',
                'payment_reference' => null,
                'bank_account' => null,
                'payment_date' => Carbon::create($currentYear, 2, 25),
                'received_date' => Carbon::create($currentYear, 2, 25),
                'receipt_number' => 'RCP-202402-0007',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 26, 8, 30),
                'verification_notes' => 'Verified dari kas harian',
                'attachments' => null,
                'notes' => null,
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 25, 18, 0),
                'updated_at' => Carbon::create($currentYear, 2, 26, 8, 30),
            ],
            [
                'event_id' => null,
                'budget_id' => null,
                'income_code' => 'IN-2024-014',
                'title' => 'Bunga Bank',
                'description' => 'Bunga dari rekening tabungan organisasi',
                'category' => 'other',
                'source_name' => 'Bank BCA',
                'source_contact' => null,
                'source_email' => null,
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 250000.00,
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'BUNGA-FEB2024',
                'bank_account' => 'BCA 1234567890',
                'payment_date' => Carbon::create($currentYear, 2, 28),
                'received_date' => Carbon::create($currentYear, 2, 28),
                'receipt_number' => 'RCP-202402-0008',
                'receipt_file' => null,
                'status' => 'verified',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 2, 29, 9, 0),
                'verification_notes' => 'Bunga bank otomatis verified',
                'attachments' => null,
                'notes' => 'Bunga rekening periode Februari 2024',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 2, 28, 16, 0),
                'updated_at' => Carbon::create($currentYear, 2, 29, 9, 0),
            ],
            [
                'event_id' => $events->first()->id,
                'budget_id' => null,
                'income_code' => 'IN-2024-015',
                'title' => 'Pembayaran Check - Pending',
                'description' => 'Pembayaran dengan cek yang belum dicairkan',
                'category' => 'other',
                'source_name' => 'PT Solusi Digital',
                'source_contact' => '021-55556666',
                'source_email' => 'finance@solusi.com',
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 8000000.00,
                'payment_method' => 'check',
                'payment_reference' => 'CHK-001-2024',
                'bank_account' => null,
                'payment_date' => Carbon::create($currentYear, 3, 1),
                'received_date' => Carbon::create($currentYear, 3, 1),
                'receipt_number' => null,
                'receipt_file' => null,
                'status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'verification_notes' => null,
                'attachments' => null,
                'notes' => 'Cek belum dicairkan, menunggu pencairan',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 3, 1, 10, 0),
                'updated_at' => Carbon::create($currentYear, 3, 1, 10, 0),
            ],
            [
                'event_id' => $events->first()->id,
                'budget_id' => null,
                'income_code' => 'IN-2024-016',
                'title' => 'Pembayaran Ditolak - Bukti Tidak Valid',
                'description' => 'Pembayaran dengan bukti transfer yang tidak valid',
                'category' => 'other',
                'source_name' => 'John Doe',
                'source_contact' => '081999888777',
                'source_email' => 'john@email.com',
                'registration_id' => null,
                'sponsorship_id' => null,
                'amount' => 1000000.00,
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'FAKE-TRF-123',
                'bank_account' => 'BCA 1234567890',
                'payment_date' => Carbon::create($currentYear, 3, 3),
                'received_date' => null,
                'receipt_number' => null,
                'receipt_file' => null,
                'status' => 'rejected',
                'verified_by' => $verifier->id,
                'verified_at' => Carbon::create($currentYear, 3, 4, 14, 0),
                'verification_notes' => 'Bukti transfer tidak dapat diverifikasi di sistem bank. Foto bukti tidak jelas dan nomor rekening tidak cocok.',
                'attachments' => null,
                'notes' => 'Sudah dikonfirmasi ke pengirim untuk mengirim ulang dengan bukti yang valid',
                'recorded_by' => $admin->id,
                'created_at' => Carbon::create($currentYear, 3, 3, 11, 0),
                'updated_at' => Carbon::create($currentYear, 3, 4, 14, 0),
            ],
        ];

        $incomes = array_merge($incomes, $otherIncomes);

        // Create all incomes
        foreach ($incomes as $income) {
            Income::create($income);
        }

        $this->command->info('✅ Income seeder completed! Created ' . count($incomes) . ' incomes.');

        // Summary by category
        $summary = [
            'registration_fee' => 0,
            'sponsorship' => 0,
            'donation' => 0,
            'infaq' => 0,
            'merchandise' => 0,
            'grant' => 0,
            'other' => 0,
        ];

        foreach ($incomes as $income) {
            $summary[$income['category']]++;
        }

        $this->command->info('');
        $this->command->info('📊 Summary by Category:');
        foreach ($summary as $category => $count) {
            if ($count > 0) {
                $this->command->info("   - {$category}: {$count} incomes");
            }
        }

        $verified = collect($incomes)->where('status', 'verified')->count();
        $pending = collect($incomes)->where('status', 'pending')->count();
        $rejected = collect($incomes)->where('status', 'rejected')->count();

        $this->command->info('');
        $this->command->info('📈 Summary by Status:');
        $this->command->info("   - Verified: {$verified} incomes");
        $this->command->info("   - Pending: {$pending} incomes");
        $this->command->info("   - Rejected: {$rejected} incomes");

        $totalAmount = collect($incomes)->where('status', 'verified')->sum('amount');
        $this->command->info('');
        $this->command->info('💰 Total Verified Amount: Rp ' . number_format($totalAmount, 0, ',', '.'));
    }
}
