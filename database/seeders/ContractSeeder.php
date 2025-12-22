<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\Event;
use App\Models\Sponsorship;
use App\Models\User;
use Carbon\Carbon;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get references
        $events = Event::all();
        $sponsorships = Sponsorship::all();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $creator = $users->first();
        $picInternal = $users->count() > 1 ? $users->skip(1)->first() : $creator;

        // Contract types and their sample data
        $contractTypes = [
            'sponsorship' => [
                'party_b_companies' => [
                    'PT Bank Mandiri (Persero) Tbk',
                    'PT Telkom Indonesia',
                    'PT Unilever Indonesia',
                    'PT Indofood CBP Sukses Makmur',
                    'PT Astra International',
                ],
                'values' => [50000000, 75000000, 100000000, 150000000, 200000000],
            ],
            'vendor' => [
                'party_b_companies' => [
                    'CV Berkah Catering',
                    'CV Mandiri Multimedia',
                    'PT Surya Sound System',
                    'CV Maju Jaya Printing',
                    'PT Prima Dekorasi',
                ],
                'values' => [15000000, 20000000, 25000000, 30000000, 40000000],
            ],
            'venue' => [
                'party_b_companies' => [
                    'PT Pengelola Masjid Istiqlal',
                    'Yayasan Al-Azhar',
                    'PT Graha Pemuda',
                    'Gedung Serbaguna Mulia',
                ],
                'values' => [10000000, 15000000, 20000000, 25000000],
            ],
            'partnership' => [
                'party_b_companies' => [
                    'Lazismu Jakarta',
                    'Rumah Zakat Indonesia',
                    'Dompet Dhuafa',
                    'Aksi Cepat Tanggap (ACT)',
                ],
                'values' => [0, 0, 0, 0],
            ],
            'service' => [
                'party_b_companies' => [
                    'CV Digital Creative Agency',
                    'PT Konsultan Manajemen Sukses',
                    'CV Media Sosial Expert',
                    'PT Security Profesional',
                ],
                'values' => [8000000, 12000000, 15000000, 18000000],
            ],
        ];

        $statusList = [
            'draft' => 15,
            'pending_signature' => 20,
            'signed' => 20,
            'active' => 30,
            'completed' => 10,
            'terminated' => 3,
            'expired' => 2,
        ];

        $contractCount = 0;
        $contractCodeCounter = 1; // Counter untuk generate contract_code

        foreach ($statusList as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $contractCount++;

                // Random type
                $type = array_rand($contractTypes);
                $typeData = $contractTypes[$type];
                $companyIndex = array_rand($typeData['party_b_companies']);
                $partyBName = $typeData['party_b_companies'][$companyIndex];
                $contractValue = $typeData['values'][$companyIndex] ?? 0;

                // Dates based on status
                [$startDate, $endDate] = $this->getDatesForStatus($status);

                // Representative names
                $representatives = [
                    'Budi Santoso, S.E.',
                    'Ahmad Rizki, M.M.',
                    'Siti Nurhaliza, S.H.',
                    'Eko Prasetyo, S.Kom.',
                    'Dewi Lestari, M.B.A.',
                    'Hendra Gunawan, S.T.',
                    'Rina Wijaya, S.Sos.',
                    'Agus Setiawan, S.Pd.',
                ];

                $contractData = [
                    'contract_code' => 'CTR-' . date('Y') . '-' . str_pad($contractCodeCounter++, 3, '0', STR_PAD_LEFT), // PERBAIKAN: Generate contract_code
                    'event_id' => $events->isNotEmpty() ? $events->random()->id : null,
                    'sponsorship_id' => ($type === 'sponsorship' && $sponsorships->isNotEmpty())
                        ? $sponsorships->random()->id
                        : null,
                    'title' => $this->generateTitle($type, $partyBName),
                    'description' => $this->generateDescription($type, $partyBName),
                    'type' => $type,

                    // Party A (Our Organization)
                    'party_a_name' => 'Panitia Ramadhan 1447H',
                    'party_a_address' => 'Jl. Masjid Agung No. 123, Jakarta Pusat 10110',
                    'party_a_representative' => 'Dr. H. Abdullah Rahman, M.A.',

                    // Party B (External)
                    'party_b_name' => $partyBName,
                    'party_b_address' => $this->generateAddress(),
                    'party_b_representative' => $representatives[array_rand($representatives)],
                    'party_b_contact' => $this->generatePhone(),
                    'party_b_email' => $this->generateEmail($partyBName),

                    // Contract Value
                    'contract_value' => $contractValue,
                    'currency' => 'IDR',

                    // Terms
                    'terms_and_conditions' => $this->generateTerms($type),
                    'scope_of_work' => $this->generateScope($type),
                    'deliverables' => json_encode($this->generateDeliverables($type)), // PERBAIKAN: Encode ke JSON
                    'payment_terms' => json_encode($this->generatePaymentTerms($contractValue)), // PERBAIKAN: Encode ke JSON

                    // Period
                    'start_date' => $startDate->format('Y-m-d'), // PERBAIKAN: Format ke string date
                    'end_date' => $endDate->format('Y-m-d'), // PERBAIKAN: Format ke string date
                    'duration_days' => $startDate->diffInDays($endDate),
                    'auto_renewal' => rand(0, 100) > 70,

                    // Status
                    'status' => $status,

                    // Management
                    'pic_internal' => $picInternal->id,
                    'created_by' => $creator->id,
                    'notes' => $this->generateNotes($type, $status),
                ];

                // Add signatures for signed/active/completed contracts
                if (in_array($status, ['signed', 'active', 'completed'])) {
                    $contractData['signed_by_party_a'] = $creator->id;
                    $contractData['signed_at_party_a'] = $startDate->copy()->subDays(rand(7, 14))->format('Y-m-d H:i:s'); // PERBAIKAN: Format timestamp
                    $contractData['signed_at_party_b'] = $startDate->copy()->subDays(rand(5, 10))->format('Y-m-d'); // Sudah sesuai (date)
                }

                // Add termination data for terminated contracts
                if ($status === 'terminated') {
                    $contractData['termination_date'] = now()->subDays(rand(1, 30))->format('Y-m-d'); // PERBAIKAN: Format ke date
                    $contractData['termination_reason'] = $this->generateTerminationReason();
                }

                Contract::create($contractData);
            }
        }

        $this->command->info("Created {$contractCount} contracts successfully!");
        $this->command->info("Status breakdown:");
        foreach ($statusList as $status => $count) {
            $this->command->info("  - {$status}: {$count} contracts");
        }
    }

    /**
     * Generate dates based on contract status
     */
    private function getDatesForStatus(string $status): array
    {
        $now = now();

        switch ($status) {
            case 'draft':
            case 'pending_signature':
                $startDate = $now->copy()->addDays(rand(10, 60));
                $endDate = $startDate->copy()->addMonths(rand(3, 12));
                break;

            case 'signed':
                $startDate = $now->copy()->addDays(rand(1, 15));
                $endDate = $startDate->copy()->addMonths(rand(6, 12));
                break;

            case 'active':
                $startDate = $now->copy()->subDays(rand(30, 180));
                $endDate = $startDate->copy()->addMonths(rand(6, 18));
                break;

            case 'completed':
                $endDate = $now->copy()->subDays(rand(1, 90));
                $startDate = $endDate->copy()->subMonths(rand(6, 12));
                break;

            case 'terminated':
                $startDate = $now->copy()->subMonths(rand(2, 6));
                $endDate = $startDate->copy()->addMonths(rand(6, 12));
                break;

            case 'expired':
                $endDate = $now->copy()->subDays(rand(1, 60));
                $startDate = $endDate->copy()->subMonths(rand(6, 12));
                break;

            default:
                $startDate = $now;
                $endDate = $now->copy()->addMonths(6);
        }

        return [$startDate, $endDate];
    }

    // ... (sisanya sama, tidak ada perubahan pada method lainnya)

    private function generateTitle(string $type, string $company): string
    {
        $titles = [
            'sponsorship' => "Kontrak Kerjasama Sponsorship dengan {$company}",
            'vendor' => "Perjanjian Pengadaan Jasa {$company}",
            'venue' => "Kontrak Sewa Tempat dengan {$company}",
            'partnership' => "Perjanjian Kerjasama dengan {$company}",
            'service' => "Kontrak Jasa Profesional {$company}",
            'employment' => "Kontrak Kerja dengan {$company}",
            'other' => "Perjanjian dengan {$company}",
        ];

        return $titles[$type] ?? "Kontrak dengan {$company}";
    }

    private function generateDescription(string $type, string $company): string
    {
        $descriptions = [
            'sponsorship' => "Kontrak sponsorship untuk mendukung kegiatan Ramadhan 1447H dengan {$company} sebagai sponsor utama.",
            'vendor' => "Perjanjian pengadaan barang/jasa dengan {$company} untuk mendukung pelaksanaan kegiatan Ramadhan.",
            'venue' => "Kontrak sewa venue/tempat kegiatan dengan {$company} untuk pelaksanaan acara Ramadhan 1447H.",
            'partnership' => "Perjanjian kerjasama strategis dengan {$company} dalam pelaksanaan program Ramadhan.",
            'service' => "Kontrak jasa profesional dengan {$company} untuk mendukung operasional kegiatan.",
            'employment' => "Kontrak kerja karyawan dengan {$company}.",
            'other' => "Perjanjian kerjasama dengan {$company}.",
        ];

        return $descriptions[$type] ?? "Kontrak kerjasama dengan {$company}.";
    }

    private function generateTerms(string $type): string
    {
        return "1. Kedua belah pihak sepakat untuk melaksanakan kontrak ini dengan itikad baik.\n" .
            "2. Setiap perubahan atau amandemen harus dibuat secara tertulis dan disetujui kedua belah pihak.\n" .
            "3. Kontrak ini berlaku sejak ditandatangani hingga masa berakhir yang tercantum.\n" .
            "4. Pembayaran dilakukan sesuai dengan jadwal yang telah disepakati.\n" .
            "5. Pihak kedua wajib memenuhi seluruh deliverables yang telah disepakati.\n" .
            "6. Force majeure akan dipertimbangkan sesuai ketentuan hukum yang berlaku.\n" .
            "7. Penyelesaian sengketa dilakukan secara musyawarah atau melalui jalur hukum yang berlaku.";
    }

    private function generateScope(string $type): string
    {
        $scopes = [
            'sponsorship' => "1. Penyediaan dana sponsorship sesuai kesepakatan\n2. Pemasangan logo di semua media promosi\n3. Penyebutan nama sponsor di setiap acara\n4. Booth khusus di lokasi acara\n5. Publikasi di media sosial dan website",
            'vendor' => "1. Pengadaan barang/jasa sesuai spesifikasi\n2. Pengiriman tepat waktu sesuai jadwal\n3. Garansi kualitas produk/jasa\n4. After sales service\n5. Pelaporan berkala",
            'venue' => "1. Penyediaan tempat acara sesuai kapasitas\n2. Fasilitas pendukung (sound system, AC, dll)\n3. Cleaning service\n4. Security\n5. Parkir untuk peserta",
            'partnership' => "1. Kolaborasi program kegiatan\n2. Sharing resources dan expertise\n3. Joint promotion\n4. Koordinasi pelaksanaan program\n5. Evaluasi bersama",
            'service' => "1. Penyediaan jasa sesuai keahlian\n2. Konsultasi dan pendampingan\n3. Pelaporan progress\n4. Quality assurance\n5. Support berkelanjutan",
        ];

        return $scopes[$type] ?? "Lingkup pekerjaan sesuai kesepakatan kedua belah pihak.";
    }

    private function generateDeliverables(string $type): array
    {
        return [
            [
                'item' => 'Laporan Progress Awal',
                'deadline' => now()->addDays(30)->format('Y-m-d'),
                'status' => 'pending',
                'created_at' => now()->toDateTimeString(),
            ],
            [
                'item' => 'Laporan Progress Tengah',
                'deadline' => now()->addDays(60)->format('Y-m-d'),
                'status' => 'pending',
                'created_at' => now()->toDateTimeString(),
            ],
            [
                'item' => 'Laporan Akhir & Dokumentasi',
                'deadline' => now()->addDays(90)->format('Y-m-d'),
                'status' => 'pending',
                'created_at' => now()->toDateTimeString(),
            ],
        ];
    }

    private function generatePaymentTerms(float $totalAmount): array
    {
        if ($totalAmount == 0) {
            return [];
        }

        return [
            [
                'description' => 'Down Payment (30%)',
                'amount' => $totalAmount * 0.3,
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'status' => 'pending',
                'created_at' => now()->toDateTimeString(),
            ],
            [
                'description' => 'Progress Payment (40%)',
                'amount' => $totalAmount * 0.4,
                'due_date' => now()->addDays(45)->format('Y-m-d'),
                'status' => 'pending',
                'created_at' => now()->toDateTimeString(),
            ],
            [
                'description' => 'Final Payment (30%)',
                'amount' => $totalAmount * 0.3,
                'due_date' => now()->addDays(90)->format('Y-m-d'),
                'status' => 'pending',
                'created_at' => now()->toDateTimeString(),
            ],
        ];
    }

    private function generateNotes(string $type, string $status): string
    {
        $notes = [
            "Kontrak ini merupakan hasil negosiasi yang baik antara kedua belah pihak.",
            "Perlu perhatian khusus pada deadline deliverables.",
            "Koordinasi intensif diperlukan untuk kelancaran pelaksanaan.",
            "Sudah dilakukan review legal oleh tim hukum.",
            "Kontrak ini merupakan prioritas tinggi untuk segera diselesaikan.",
        ];

        return $notes[array_rand($notes)];
    }

    private function generateTerminationReason(): string
    {
        $reasons = [
            "Perubahan kebijakan internal organisasi yang mengharuskan penghentian kontrak.",
            "Kesepakatan bersama kedua belah pihak untuk mengakhiri kontrak lebih awal.",
            "Pihak kedua tidak memenuhi kewajiban sesuai kontrak yang telah disepakati.",
            "Force majeure yang mengakibatkan ketidakmampuan melanjutkan kontrak.",
            "Perubahan scope project yang signifikan sehingga perlu kontrak baru.",
        ];

        return $reasons[array_rand($reasons)];
    }

    private function generateAddress(): string
    {
        $streets = ['Jl. Sudirman', 'Jl. Thamrin', 'Jl. Gatot Subroto', 'Jl. Rasuna Said', 'Jl. Kuningan'];
        $cities = ['Jakarta Selatan', 'Jakarta Pusat', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara'];

        return $streets[array_rand($streets)] . ' No. ' . rand(10, 500) . ', ' .
            $cities[array_rand($cities)] . ' ' . rand(10000, 14000);
    }

    private function generatePhone(): string
    {
        $prefixes = ['021', '022', '024', '031'];
        return $prefixes[array_rand($prefixes)] . '-' . rand(1000000, 9999999);
    }

    private function generateEmail(string $company): string
    {
        $domain = strtolower(str_replace([' ', '.', ',', '(', ')'], '', $company));
        $domain = substr($domain, 0, 20);
        return 'contact@' . $domain . '.com';
    }
}
