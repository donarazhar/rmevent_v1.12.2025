<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficialLetter;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class OfficialLetterSeeder extends Seeder
{

    // ADD THESE PROPERTIES
    private $incomingCounter = 1;
    private $outgoingCounter = 1;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $creator = $users->first();
        $approver = $users->count() > 1 ? $users->skip(1)->first() : $creator;
        $signatory = $users->count() > 2 ? $users->skip(2)->first() : $creator;

        // Letter types dengan sample data
        $letterTypes = [
            'invitation' => [
                'subjects' => [
                    'Undangan Rapat Koordinasi Panitia Ramadhan 1447H',
                    'Undangan Menghadiri Acara Buka Puasa Bersama',
                    'Undangan Seminar Ramadhan dan Pembinaan Rohani',
                    'Undangan Kegiatan Sosial Berbagi Takjil',
                ],
                'recipients' => [
                    ['name' => 'Ketua RT 05', 'org' => 'RT 05 RW 03'],
                    ['name' => 'Kepala Dinas Sosial', 'org' => 'Dinas Sosial Jakarta Pusat'],
                    ['name' => 'Pengurus Masjid Al-Ikhlas', 'org' => 'DKM Masjid Al-Ikhlas'],
                    ['name' => 'Ketua Karang Taruna', 'org' => 'Karang Taruna Mekar Jaya'],
                ],
            ],
            'announcement' => [
                'subjects' => [
                    'Pengumuman Jadwal Kegiatan Ramadhan 1447H',
                    'Pengumuman Pembagian Tugas Panitia',
                    'Pengumuman Perubahan Lokasi Kegiatan',
                    'Pengumuman Penutupan Pendaftaran Peserta',
                ],
                'recipients' => [
                    ['name' => 'Seluruh Anggota Panitia', 'org' => 'Panitia Ramadhan 1447H'],
                    ['name' => 'Jemaah Masjid', 'org' => 'Masjid Agung Jakarta'],
                    ['name' => 'Masyarakat Sekitar', 'org' => 'Kelurahan Tanah Abang'],
                    ['name' => 'Peserta Kegiatan', 'org' => 'Umum'],
                ],
            ],
            'request' => [
                'subjects' => [
                    'Permohonan Izin Penggunaan Tempat untuk Kegiatan Ramadhan',
                    'Permohonan Bantuan Dana Kegiatan Ramadhan 1447H',
                    'Permohonan Dukungan Kegiatan Sosial Ramadhan',
                    'Permohonan Kerjasama Program Berbagi Takjil',
                ],
                'recipients' => [
                    ['name' => 'Lurah Tanah Abang', 'org' => 'Kelurahan Tanah Abang'],
                    ['name' => 'Manager CSR', 'org' => 'PT Bank Mandiri'],
                    ['name' => 'Direktur', 'org' => 'Rumah Zakat Indonesia'],
                    ['name' => 'Kepala Bidang', 'org' => 'Dinas Pariwisata Jakarta'],
                ],
            ],
            'thank_you' => [
                'subjects' => [
                    'Ucapan Terima Kasih atas Dukungan Kegiatan Ramadhan',
                    'Apresiasi Partisipasi dalam Kegiatan Buka Puasa Bersama',
                    'Terima Kasih atas Bantuan Dana Kegiatan',
                    'Penghargaan untuk Sponsor Kegiatan Ramadhan',
                ],
                'recipients' => [
                    ['name' => 'Direktur Utama', 'org' => 'PT Unilever Indonesia'],
                    ['name' => 'Bapak H. Ahmad Syahid', 'org' => 'Donatur'],
                    ['name' => 'Tim Relawan', 'org' => 'Aksi Cepat Tanggap'],
                    ['name' => 'Pengurus Masjid', 'org' => 'Masjid Istiqlal'],
                ],
            ],
            'cooperation' => [
                'subjects' => [
                    'Penawaran Kerjasama Program Ramadhan 1447H',
                    'Kerjasama Pelaksanaan Kegiatan Sosial Ramadhan',
                    'Proposal Kerjasama Media Partner',
                    'Kerjasama Distribusi Bantuan untuk Dhuafa',
                ],
                'recipients' => [
                    ['name' => 'Direktur', 'org' => 'Lazismu Jakarta'],
                    ['name' => 'Pemimpin Redaksi', 'org' => 'Media Indonesia'],
                    ['name' => 'Manager Marketing', 'org' => 'PT Telkom Indonesia'],
                    ['name' => 'Ketua Yayasan', 'org' => 'Yayasan Peduli Ummat'],
                ],
            ],
            'notification' => [
                'subjects' => [
                    'Pemberitahuan Pelaksanaan Kegiatan Ramadhan',
                    'Notifikasi Perubahan Jadwal Acara',
                    'Pemberitahuan Penutupan Sementara Kantor Panitia',
                    'Informasi Update Program Ramadhan',
                ],
                'recipients' => [
                    ['name' => 'Seluruh Peserta', 'org' => 'Peserta Terdaftar'],
                    ['name' => 'Mitra Kerja', 'org' => 'Partner Kegiatan'],
                    ['name' => 'Vendor', 'org' => 'CV Berkah Catering'],
                    ['name' => 'Sponsor', 'org' => 'PT Astra International'],
                ],
            ],
        ];

        $statusDistribution = [
            'draft' => 10,
            'pending_approval' => 8,
            'approved' => 8,
            'sent' => 25,
            'received' => 30,
            'archived' => 19,
        ];

        $letterCount = 0;
        $yearMonth = now()->format('Y-m');

        foreach ($statusDistribution as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $letterCount++;

                // Random type and direction
                $direction = ($status === 'received') ? 'incoming' : (rand(0, 100) > 70 ? 'incoming' : 'outgoing');
                $type = array_rand($letterTypes);
                $typeData = $letterTypes[$type];

                $subjectIndex = array_rand($typeData['subjects']);
                $subject = $typeData['subjects'][$subjectIndex];

                $recipientIndex = array_rand($typeData['recipients']);
                $recipient = $typeData['recipients'][$recipientIndex];

                // Generate dates based on status
                [$letterDate, $sentDate, $receivedDate] = $this->getDatesForStatus($status, $direction);

                // Generate letter content
                $content = $this->generateContent($type, $subject, $recipient['name']);

                $letterData = [
                    'letter_number' => $this->generateLetterNumber($direction, $letterDate), // TAMBAHKAN INI
                    'event_id' => $events->isNotEmpty() && rand(0, 100) > 40 ? $events->random()->id : null,
                    'letter_type' => $type,
                    'direction' => $direction,
                    'subject' => $subject,
                    'content' => $content,

                    // Sender info
                    'sender_id' => $direction === 'outgoing' ? $creator->id : null,
                    'sender_name' => $direction === 'incoming' ? $recipient['name'] : null,
                    'sender_organization' => $direction === 'incoming' ? $recipient['org'] : null,

                    // Recipient info
                    'recipient_name' => $direction === 'outgoing' ? $recipient['name'] : 'Panitia Ramadhan 1447H',
                    'recipient_organization' => $direction === 'outgoing' ? $recipient['org'] : 'Panitia Ramadhan 1447H',
                    'recipient_address' => $this->generateAddress(),
                    'recipient_email' => $this->generateEmail($recipient['name']),

                    // CC Recipients (30% chance)
                    'cc_recipients' => rand(0, 100) > 70 ? json_encode($this->generateCcRecipients()) : null,

                    // Attachments
                    'attachment_count' => rand(0, 3),
                    'attachment_list' => rand(0, 100) > 50 ? json_encode($this->generateAttachments(rand(1, 3))) : null,

                    // Reference (40% chance)
                    'reference_number' => rand(0, 100) > 60 ? $this->generateReferenceNumber() : null,

                    // Dates
                    'letter_date' => $letterDate->format('Y-m-d'),
                    'received_date' => $receivedDate?->format('Y-m-d'),
                    'sent_date' => $sentDate?->format('Y-m-d'),
                    'due_date' => $this->needsDueDate($type) ? $letterDate->copy()->addDays(rand(7, 30))->format('Y-m-d') : null,

                    // Priority & Classification
                    'priority' => $this->determinePriority($type),
                    'classification' => $this->determineClassification($type),

                    // Status
                    'status' => $status,

                    // Signatory
                    'signatory' => $direction === 'outgoing' && in_array($status, ['approved', 'sent', 'archived']) ? $signatory->id : null,
                    'signatory_name' => $direction === 'outgoing' ? 'Dr. H. Abdullah Rahman, M.A.' : null,
                    'signatory_position' => $direction === 'outgoing' ? 'Ketua Panitia Ramadhan 1447H' : null,

                    // Management
                    'created_by' => $creator->id,
                    'notes' => $this->generateNotes($type, $status),
                    'internal_notes' => rand(0, 100) > 70 ? $this->generateInternalNotes() : null,
                ];

                // Add approval info for approved/sent/archived letters
                if ($direction === 'outgoing' && in_array($status, ['approved', 'sent', 'archived'])) {
                    $letterData['approved_by'] = $approver->id;
                    $letterData['approved_at'] = $letterDate->copy()->subDays(rand(1, 3))->format('Y-m-d H:i:s');
                }

                OfficialLetter::create($letterData);
            }
        }

        $this->command->info("Created {$letterCount} official letters successfully!");
        $this->command->info("Status breakdown:");
        foreach ($statusDistribution as $status => $count) {
            $this->command->info("  - {$status}: {$count} letters");
        }
    }

    /**
     * Generate dates based on status and direction
     */
    private function getDatesForStatus(string $status, string $direction): array
    {
        $now = now();

        switch ($status) {
            case 'draft':
            case 'pending_approval':
                $letterDate = $now->copy()->subDays(rand(1, 7));
                $sentDate = null;
                $receivedDate = null;
                break;

            case 'approved':
                $letterDate = $now->copy()->subDays(rand(3, 10));
                $sentDate = null;
                $receivedDate = null;
                break;

            case 'sent':
                $letterDate = $now->copy()->subDays(rand(5, 30));
                $sentDate = $letterDate->copy()->addDays(rand(1, 3));
                $receivedDate = null;
                break;

            case 'received':
                $letterDate = $now->copy()->subDays(rand(7, 60));
                $sentDate = null;
                $receivedDate = $letterDate->copy()->addDays(rand(1, 5));
                break;

            case 'archived':
                $letterDate = $now->copy()->subDays(rand(60, 180));
                if ($direction === 'outgoing') {
                    $sentDate = $letterDate->copy()->addDays(rand(1, 3));
                    $receivedDate = null;
                } else {
                    $sentDate = null;
                    $receivedDate = $letterDate->copy()->addDays(rand(1, 5));
                }
                break;

            default:
                $letterDate = $now;
                $sentDate = null;
                $receivedDate = null;
        }

        return [$letterDate, $sentDate, $receivedDate];
    }

    /**
     * Generate letter content
     */
    private function generateContent(string $type, string $subject, string $recipientName): string
    {
        $opening = "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n";
        $opening .= "Kepada Yth.\n{$recipientName}\n\n";
        $opening .= "Dengan hormat,\n\n";

        $contents = [
            'invitation' => "Dalam rangka menyukseskan program Ramadhan 1447H, kami mengundang Bapak/Ibu untuk hadir dalam acara yang akan kami selenggarakan.\n\nHari/Tanggal: Jumat, 15 Ramadhan 1447H\nWaktu: 14.00 WIB - Selesai\nTempat: Aula Masjid Agung Jakarta\n\nDemikian undangan ini kami sampaikan. Atas perhatian dan kehadirannya, kami ucapkan terima kasih.",

            'announcement' => "Dengan ini kami sampaikan pengumuman terkait {$subject}.\n\nBerkenaan dengan hal tersebut, kami informasikan bahwa kegiatan akan dilaksanakan sesuai dengan jadwal yang telah ditentukan. Mohon untuk seluruh pihak terkait dapat memperhatikan dan mengikuti ketentuan yang berlaku.\n\nDemikian pengumuman ini kami sampaikan untuk dapat diketahui dan dilaksanakan sebagaimana mestinya.",

            'request' => "Bersama ini kami mengajukan permohonan terkait {$subject}.\n\nAdapun latar belakang permohonan ini adalah dalam rangka melaksanakan program kegiatan Ramadhan 1447H yang telah kami rencanakan. Kami berharap permohonan ini dapat dipertimbangkan dengan baik.\n\nAtas perhatian dan pertimbangan Bapak/Ibu, kami ucapkan terima kasih.",

            'thank_you' => "Melalui surat ini, kami menyampaikan apresiasi dan ucapan terima kasih yang sebesar-besarnya atas dukungan dan partisipasi Bapak/Ibu dalam kegiatan Ramadhan 1447H.\n\nKontribusi yang telah diberikan sangat membantu kesuksesan program kami dan memberikan manfaat bagi masyarakat luas. Semoga amal baik ini menjadi ladang pahala di bulan yang penuh berkah ini.\n\nSekali lagi, kami ucapkan terima kasih yang sebesar-besarnya.",

            'cooperation' => "Bersama ini kami sampaikan proposal kerjasama untuk program {$subject}.\n\nKami meyakini bahwa kerjasama ini akan memberikan manfaat yang signifikan bagi kedua belah pihak dan terutama untuk masyarakat umum. Kami sangat mengharapkan dapat menjalin kerjasama yang baik dengan pihak Bapak/Ibu.\n\nUntuk pembahasan lebih lanjut, kami dapat mengatur pertemuan sesuai dengan waktu yang Bapak/Ibu tentukan.",

            'notification' => "Melalui surat ini, kami memberitahukan bahwa {$subject}.\n\nInformasi lebih detail terkait hal ini dapat dilihat pada dokumen terlampir. Apabila ada hal yang perlu ditanyakan, dapat menghubungi sekretariat panitia.\n\nDemikian pemberitahuan ini kami sampaikan. Terima kasih atas perhatiannya.",
        ];

        $closing = "\n\nWassalamu'alaikum Warahmatullahi Wabarakatuh\n\n";
        $closing .= "Hormat kami,\n\nPanitia Ramadhan 1447H\n\n\n";
        $closing .= "Dr. H. Abdullah Rahman, M.A.\nKetua Panitia";

        return $opening . ($contents[$type] ?? $contents['notification']) . $closing;
    }

    /**
     * Generate CC recipients
     */
    private function generateCcRecipients(): array
    {
        $ccList = [
            ['name' => 'Sekretaris Panitia', 'email' => 'sekretaris@ramadhan1447.org', 'organization' => 'Panitia Ramadhan 1447H'],
            ['name' => 'Bendahara Panitia', 'email' => 'bendahara@ramadhan1447.org', 'organization' => 'Panitia Ramadhan 1447H'],
            ['name' => 'Koordinator Acara', 'email' => 'acara@ramadhan1447.org', 'organization' => 'Panitia Ramadhan 1447H'],
            ['name' => 'Humas', 'email' => 'humas@ramadhan1447.org', 'organization' => 'Panitia Ramadhan 1447H'],
        ];

        // Shuffle dan ambil random
        shuffle($ccList);
        $count = rand(1, 2);

        return array_slice($ccList, 0, $count);
    }

    /**
     * Generate attachments
     */
    private function generateAttachments(int $count): array
    {
        $attachments = [
            'Proposal Kegiatan.pdf',
            'Rundown Acara.pdf',
            'Daftar Panitia.pdf',
            'RAB Kegiatan.xlsx',
            'Denah Lokasi.pdf',
            'Surat Pengantar.pdf',
            'Formulir Pendaftaran.pdf',
            'Panduan Teknis.pdf',
        ];

        return array_slice($attachments, 0, min($count, count($attachments)));
    }

    /**
     * Generate reference number
     */
    private function generateReferenceNumber(): string
    {
        $number = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $month = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][rand(0, 11)];
        $year = now()->year;

        return "{$number}/EXT/{$month}/{$year}";
    }

    /**
     * Check if letter type needs due date
     */
    private function needsDueDate(string $type): bool
    {
        return in_array($type, ['invitation', 'request']);
    }

    /**
     * Determine priority based on type
     */
    private function determinePriority(string $type): string
    {
        $priorities = [
            'invitation' => ['normal', 'normal', 'high'],
            'announcement' => ['normal', 'normal', 'normal'],
            'request' => ['normal', 'high', 'urgent'],
            'thank_you' => ['low', 'normal', 'normal'],
            'cooperation' => ['normal', 'high', 'high'],
            'notification' => ['normal', 'normal', 'high'],
        ];

        $options = $priorities[$type] ?? ['normal', 'normal', 'normal'];
        return $options[array_rand($options)];
    }

    /**
     * Determine classification based on type
     */
    private function determineClassification(string $type): string
    {
        $classifications = [
            'invitation' => 'internal',
            'announcement' => 'public',
            'request' => 'internal',
            'thank_you' => 'internal',
            'cooperation' => 'confidential',
            'notification' => 'internal',
        ];

        return $classifications[$type] ?? 'internal';
    }

    /**
     * Generate notes
     */
    private function generateNotes(string $type, string $status): string
    {
        $notes = [
            "Surat telah diperiksa dan disetujui untuk dikirim",
            "Mohon segera ditindaklanjuti sesuai prosedur",
            "Surat penting, harap diproses dengan prioritas tinggi",
            "Sudah dikonfirmasi dengan pihak terkait",
            "Perlu koordinasi lanjutan setelah pengiriman",
        ];

        return $notes[array_rand($notes)];
    }

    /**
     * Generate internal notes
     */
    private function generateInternalNotes(): string
    {
        $notes = [
            "Catatan internal: Follow up response dalam 3 hari",
            "Internal: Koordinasi dengan divisi terkait sudah dilakukan",
            "Note: Perlu diarsipkan ke folder khusus",
            "Reminder: Cek status balasan minggu depan",
            "Internal: Draft sudah direvisi 2x sebelum dikirim",
        ];

        return $notes[array_rand($notes)];
    }

    /**
     * Generate address
     */
    private function generateAddress(): string
    {
        $streets = ['Jl. Sudirman', 'Jl. Thamrin', 'Jl. Gatot Subroto', 'Jl. Rasuna Said', 'Jl. Kuningan', 'Jl. HR Rasuna Said'];
        $cities = ['Jakarta Selatan', 'Jakarta Pusat', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara'];

        return $streets[array_rand($streets)] . ' No. ' . rand(10, 500) . ', ' .
            $cities[array_rand($cities)] . ' ' . rand(10000, 14000);
    }

    /**
     * Generate email
     */
    private function generateEmail(string $name): string
    {
        $domain = strtolower(str_replace([' ', '.', ',', 'H.', 'Bapak', 'Ibu'], '', $name));
        $domain = preg_replace('/[^a-z0-9]/', '', $domain);
        $domain = substr($domain, 0, 15);

        $domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'organization.id'];

        return $domain . '@' . $domains[array_rand($domains)];
    }

    /**
     * Generate unique letter number
     */
    private function generateLetterNumber(string $direction, Carbon $letterDate): string
    {
        $year = $letterDate->year;
        $month = $letterDate->format('m');
        $romanMonth = $this->getRomanMonth($month);

        if ($direction === 'incoming') {
            $number = $this->incomingCounter++;
            $prefix = 'IN';
        } else {
            $number = $this->outgoingCounter++;
            $prefix = 'OUT';
        }

        // Format: 001/OUT/RM-1447H/XII/2025
        return str_pad($number, 3, '0', STR_PAD_LEFT) .
            '/' . $prefix . '/RM-1447H/' .
            $romanMonth . '/' .
            $year;
    }

    /**
     * Convert month to Roman numeral
     */
    private function getRomanMonth(string $month): string
    {
        $romans = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII'
        ];

        return $romans[$month] ?? 'I';
    }
}
