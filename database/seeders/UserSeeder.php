<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@ramadhanmubarak.org',
            'password' => Hash::make('password123'),
            'phone' => '+62 812-1000-0001',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Admin Team
        $admins = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'fauzi@ramadhanmubarak.org',
                'phone' => '+62 812-1000-0002',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@ramadhanmubarak.org',
                'phone' => '+62 812-1000-0003',
            ],
        ];

        foreach ($admins as $admin) {
            User::create(array_merge($admin, [
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        // ============================================
        // STRUKTUR PANITIA SESUAI SK
        // ============================================

        // PENASEHAT
        $penasehat = [
            [
                'name' => 'Pengurus Takmir Masjid Agung Al Azhar',
                'email' => 'takmir@alazhar.or.id',
                'phone' => '+62 21-7283683',
            ],
            [
                'name' => 'Kepala Direktorat Dakwah dan Sosial YPI Al Azhar',
                'email' => 'dakwah@alazhar.or.id',
                'phone' => '+62 21-7397267',
            ],
        ];

        foreach ($penasehat as $member) {
            User::create(array_merge($member, [
                'password' => Hash::make('password123'),
                'role' => 'penasehat',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        // PANITIA PENGARAH (SC)
        User::create([
            'name' => 'Kepala Kantor Masjid Agung Al Azhar',
            'email' => 'kepala.kantor@alazhar.or.id',
            'password' => Hash::make('password123'),
            'phone' => '+62 812-1100-0001',
            'role' => 'pengarah',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // PANITIA PELAKSANA (OC)
        $pelaksana = [
            [
                'name' => 'Tubagus Sumtana',
                'email' => 'tubagus.sumtana@alazhar.or.id',
                'phone' => '+62 812-1100-0002',
            ],
            [
                'name' => 'Pian Sopian',
                'email' => 'pian.sopian@alazhar.or.id',
                'phone' => '+62 812-1100-0003',
            ],
            [
                'name' => 'Acum Maulana',
                'email' => 'acum.maulana@alazhar.or.id',
                'phone' => '+62 812-1100-0004',
            ],
        ];

        foreach ($pelaksana as $member) {
            User::create(array_merge($member, [
                'password' => Hash::make('password123'),
                'role' => 'pelaksana',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        // ANGGOTA (UNIT KOORDINASI)
        $koordinator = [
            // Pendidikan
            ['name' => 'Kepala TK Islam Al Azhar 1', 'email' => 'kepala.tk1@alazhar.or.id'],
            ['name' => 'Kepala SD Islam Al Azhar 1', 'email' => 'kepala.sd1@alazhar.or.id'],
            ['name' => 'Kepala SMP Islam Al Azhar 1', 'email' => 'kepala.smp1@alazhar.or.id'],
            ['name' => 'Kepala SMA Islam Al Azhar 1', 'email' => 'kepala.sma1@alazhar.or.id'],
            ['name' => 'Kepala SMA Islam Al Azhar 3', 'email' => 'kepala.sma3@alazhar.or.id'],
            ['name' => 'Ketua Jam\'iyyah Kampus Kebayoran Baru', 'email' => 'jamiyyah.kampus@alazhar.or.id'],

            // Lembaga
            ['name' => 'Rektor UAI', 'email' => 'rektor.uai@alazhar.or.id'],
            ['name' => 'Ketua Umum YISC Al Azhar', 'email' => 'ketua.yisc@alazhar.or.id'],
            ['name' => 'Ketua ASBD', 'email' => 'ketua.asbd@alazhar.or.id'],
            ['name' => 'Ketua AYLI', 'email' => 'ketua.ayli@alazhar.or.id'],
            ['name' => 'Ketua LMA', 'email' => 'ketua.lma@alazhar.or.id'],
            ['name' => 'Ketua LTA', 'email' => 'ketua.lta@alazhar.or.id'],
            ['name' => 'Ketua PMA', 'email' => 'ketua.pma@alazhar.or.id'],
            ['name' => 'Kepala Kursus Al Azhar', 'email' => 'kepala.kursus@alazhar.or.id'],
            ['name' => 'Kepala LAZWAF BMT Al Azhar', 'email' => 'kepala.lazwaf@alazhar.or.id'],
            ['name' => 'Ketua PIA', 'email' => 'ketua.pia@alazhar.or.id'],
            ['name' => 'Ketua DPYDA', 'email' => 'ketua.dpyda@alazhar.or.id'],
            ['name' => 'Ketua BIMROHIS Al Azhar', 'email' => 'ketua.bimrohis@alazhar.or.id'],
            ['name' => 'Ketua Study Islam Al Azhar', 'email' => 'ketua.studyislam@alazhar.or.id'],
            ['name' => 'Kepala KBIH Al Azhar', 'email' => 'kepala.kbih@alazhar.or.id'],
            ['name' => 'Kepala Klinik Pratama Al Azhar', 'email' => 'kepala.klinik@alazhar.or.id'],
            ['name' => 'Ketua PHUA', 'email' => 'ketua.phua@alazhar.or.id'],
        ];

        $phoneCounter = 1;
        foreach ($koordinator as $member) {
            User::create(array_merge($member, [
                'password' => Hash::make('password123'),
                'phone' => '+62 812-3000-' . str_pad($phoneCounter++, 4, '0', STR_PAD_LEFT),
                'role' => 'koordinator',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        // SEKSI - SEKSI KEGIATAN RUTIN
        $seksiRutin = [
            // a. Perlengkapan dan Kebersihan
            ['name' => 'Lutfi Azis', 'email' => 'lutfi.azis@alazhar.or.id'],
            ['name' => 'Daud Jamil', 'email' => 'daud.jamil@alazhar.or.id'],
            ['name' => 'H. Baharudin', 'email' => 'baharudin@alazhar.or.id'],
            ['name' => 'M. Arif Affandi', 'email' => 'arif.affandi@alazhar.or.id'],

            // b. Layanan Teknisi & Sound System
            ['name' => 'A. Ali Imron', 'email' => 'ali.imron@alazhar.or.id'],
            ['name' => 'Zakaria', 'email' => 'zakaria@alazhar.or.id'],

            // c. Layanan Keamanan, Parkir, Shaf dan Tromol Idul Fitri
            ['name' => 'Nasroni', 'email' => 'nasroni@alazhar.or.id'],
            ['name' => 'Subhan Dzaelani', 'email' => 'subhan.dzaelani@alazhar.or.id'],
            ['name' => 'Fitri Indriyani', 'email' => 'fitri.indriyani@alazhar.or.id'],

            // d. Layanan Dokumentasi PJ Zakat fitrah
            ['name' => 'Khairul Basar', 'email' => 'khairul.basar@alazhar.or.id'],
            ['name' => 'Ahmad Iyonk', 'email' => 'ahmad.iyonk@alazhar.or.id'],
            ['name' => 'Novaldi Rahmat', 'email' => 'novaldi.rahmat@alazhar.or.id'],

            // e. Layanan Kesehatan
            ['name' => 'H. Mukti Usman', 'email' => 'mukti.usman@alazhar.or.id'],
        ];

        $phoneCounter = 1;
        foreach ($seksiRutin as $member) {
            User::create(array_merge($member, [
                'password' => Hash::make('password123'),
                'phone' => '+62 812-4000-' . str_pad($phoneCounter++, 4, '0', STR_PAD_LEFT),
                'role' => 'panitia',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        // SEKSI - SEKSI KEGIATAN NON RUTIN
        $seksiNonRutin = [
            // a. Buka Puasa Harian (Musafir) & Kultum Ifthor
            ['name' => 'Januar Yazid', 'email' => 'januar.yazid@alazhar.or.id'],
            ['name' => 'Yayan Nasrudin', 'email' => 'yayan.nasrudin@alazhar.or.id'],

            // b. Konsumsi Ramadhan
            ['name' => 'Rohaeni', 'email' => 'rohaeni@alazhar.or.id'],
            ['name' => 'Solikin', 'email' => 'solikin@alazhar.or.id'],
            ['name' => 'Rudi', 'email' => 'rudi@alazhar.or.id'],

            // c. Sholat Tarawih & Tadarus Qur'an 5 waktu Malam Nuzulul Qur'an
            ['name' => 'Ustadz Agus Nur Qowim', 'email' => 'agus.nurqowim@alazhar.or.id'],
            ['name' => 'Ustadz Ahmad Rizki Nurfadillah', 'email' => 'ahmad.rizki@alazhar.or.id'],

            // d. Kuliah Subuh Sabtu - Ahad
            ['name' => 'Ustadz Ruslin Abdurrahman', 'email' => 'ruslin.abdurrahman@alazhar.or.id'],
            ['name' => 'Ustadz Muamar Raihan', 'email' => 'muamar.raihan@alazhar.or.id'],

            // e. Bazar Ramadhan
            ['name' => 'Agus Soni Setiawan', 'email' => 'agus.soni@alazhar.or.id'],
            ['name' => 'Jubaidah', 'email' => 'jubaidah@alazhar.or.id'],

            // f. Iktikaf 10 Malam Akhir
            ['name' => 'Aris Suyitno', 'email' => 'aris.suyitno@alazhar.or.id'],

            // g. Distribusi Beras Zakat Fitrah/Pendampingan LAZWAF
            ['name' => 'Pian Sopyan', 'email' => 'pian.sopyan@alazhar.or.id'],
            ['name' => 'Rafli Hidayat', 'email' => 'rafli.hidayat@alazhar.or.id'],
            ['name' => 'Khairul Basar', 'email' => 'khairul.basar2@alazhar.or.id'],

            // h. Peduli Yatim dan Dhuafa
            ['name' => 'Ustadz Mukhtar Ibnu', 'email' => 'mukhtar.ibnu@alazhar.or.id'],

            // i. Takbiran dan Sholat Idul Fitri
            // (tidak ada nama di SK)

            // j. Wisuda LTA, Khotmul Qur'an, dan Lomba MTQ
            ['name' => 'Ustadz Achmad Khotib', 'email' => 'achmad.khotib@alazhar.or.id'],
        ];

        $phoneCounter = 1;
        foreach ($seksiNonRutin as $member) {
            User::create(array_merge($member, [
                'password' => Hash::make('password123'),
                'phone' => '+62 812-5000-' . str_pad($phoneCounter++, 4, '0', STR_PAD_LEFT),
                'role' => 'panitia',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        // ============================================
        // JAMAAH (PUBLIC USERS) - LENGKAP
        // ============================================
        $jamaah = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '+62 812-6000-0001',
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@email.com',
                'phone' => '+62 812-6000-0002',
            ],
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@email.com',
                'phone' => '+62 812-6000-0003',
            ],
            [
                'name' => 'Rani Puspita',
                'email' => 'rani.puspita@email.com',
                'phone' => '+62 812-6000-0004',
            ],
            [
                'name' => 'Doni Prasetyo',
                'email' => 'doni.prasetyo@email.com',
                'phone' => '+62 812-6000-0005',
            ],
            [
                'name' => 'Linda Maharani',
                'email' => 'linda.maharani@email.com',
                'phone' => '+62 812-6000-0006',
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@email.com',
                'phone' => '+62 812-6000-0007',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'phone' => '+62 812-6000-0008',
            ],
            [
                'name' => 'Rizki Firmansyah',
                'email' => 'rizki.firmansyah@email.com',
                'phone' => '+62 812-6000-0009',
            ],
            [
                'name' => 'Putri Ayu',
                'email' => 'putri.ayu@email.com',
                'phone' => '+62 812-6000-0010',
            ],
        ];

        foreach ($jamaah as $member) {
            User::create(array_merge($member, [
                'password' => Hash::make('password123'),
                'role' => 'jamaah',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        $this->command->info('✅ Users seeded successfully! Total: ' . User::count());
        $this->command->info('📧 Default credentials:');
        $this->command->info('   Admin: admin@ramadhanmubarak.org / password123');
        $this->command->info('   Ketua Pelaksana: tubagus.sumtana@alazhar.or.id / password123');
        $this->command->info('   Sekretaris: pian.sopian@alazhar.or.id / password123');
        $this->command->info('   Panitia: lutfi.azis@alazhar.or.id / password123');
        $this->command->info('   Jamaah: budi.santoso@email.com / password123');
    }
}
