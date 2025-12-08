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

        // Panitia (Committee Members)
        $panitia = [
            [
                'name' => 'Muhammad Ridwan',
                'email' => 'ridwan@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0001',
            ],
            [
                'name' => 'Fatimah Azzahra',
                'email' => 'fatimah@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0002',
            ],
            [
                'name' => 'Abdullah Rahman',
                'email' => 'abdullah@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0003',
            ],
            [
                'name' => 'Khadijah Nur',
                'email' => 'khadijah@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0004',
            ],
            [
                'name' => 'Umar Faruq',
                'email' => 'umar@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0005',
            ],
            [
                'name' => 'Aisyah Rahmawati',
                'email' => 'aisyah@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0006',
            ],
            [
                'name' => 'Bilal Ibrahim',
                'email' => 'bilal@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0007',
            ],
            [
                'name' => 'Zainab Husna',
                'email' => 'zainab@ramadhanmubarak.org',
                'phone' => '+62 812-2000-0008',
            ],
        ];

        foreach ($panitia as $member) {
            User::create(array_merge($member, [
                'password' => Hash::make('password123'),
                'role' => 'panitia',
                'status' => 'active',
                'email_verified_at' => now(),
            ]));
        }

        // Jamaah (Public Users) - Sample
        $jamaah = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '+62 812-3000-0001',
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@email.com',
                'phone' => '+62 812-3000-0002',
            ],
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@email.com',
                'phone' => '+62 812-3000-0003',
            ],
            [
                'name' => 'Rani Puspita',
                'email' => 'rani.puspita@email.com',
                'phone' => '+62 812-3000-0004',
            ],
            [
                'name' => 'Doni Prasetyo',
                'email' => 'doni.prasetyo@email.com',
                'phone' => '+62 812-3000-0005',
            ],
            [
                'name' => 'Linda Maharani',
                'email' => 'linda.maharani@email.com',
                'phone' => '+62 812-3000-0006',
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@email.com',
                'phone' => '+62 812-3000-0007',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'phone' => '+62 812-3000-0008',
            ],
            [
                'name' => 'Rizki Firmansyah',
                'email' => 'rizki.firmansyah@email.com',
                'phone' => '+62 812-3000-0009',
            ],
            [
                'name' => 'Putri Ayu',
                'email' => 'putri.ayu@email.com',
                'phone' => '+62 812-3000-0010',
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
        $this->command->info('   Panitia: ridwan@ramadhanmubarak.org / password123');
        $this->command->info('   Jamaah: budi.santoso@email.com / password123');
    }
}