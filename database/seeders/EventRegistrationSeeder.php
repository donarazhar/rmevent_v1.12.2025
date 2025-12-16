<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class EventRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $events = Event::published()->get();

        if ($events->isEmpty()) {
            $this->command->warn('Please run EventSeeder first!');
            return;
        }

        $users = User::all();
        $totalRegistrations = 0;

        foreach ($events as $event) {
            // Skip if event has no max participants or already full
            if ($event->max_participants && $event->current_participants >= $event->max_participants) {
                continue;
            }

            // Generate random number of registrations
            $maxReg = $event->max_participants
                ? min($event->max_participants, rand(5, (int)($event->max_participants * 0.8)))
                : rand(10, 50);

            $createdCount = 0;

            for ($i = 0; $i < $maxReg; $i++) {
                $user = $users->random();
                $isGuest = rand(0, 100) < 30; // 30% guest registration

                $registration = EventRegistration::create([
                    'registration_code' => $this->generateUniqueCode(),
                    'event_id' => $event->id,
                    'user_id' => $isGuest ? null : $user->id,
                    'name' => $isGuest ? $faker->name : $user->name,
                    'email' => $isGuest ? $faker->safeEmail : $user->email,
                    'phone' => $faker->numerify('08##########'),
                    'gender' => $faker->randomElement([EventRegistration::GENDER_MALE, EventRegistration::GENDER_FEMALE]),
                    'birth_date' => $faker->dateTimeBetween('-50 years', '-17 years'),
                    'address' => $faker->address,
                    'city' => $faker->city,
                    'province' => $faker->randomElement(['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Banten']),
                    'custom_data' => $this->generateCustomData($event, $faker),
                    'status' => $this->randomStatus(),
                    'payment_status' => $event->is_free
                        ? EventRegistration::PAYMENT_PAID
                        : $this->randomPaymentStatus(),
                    'payment_amount' => $event->is_free ? 0 : $event->price,
                    'payment_date' => !$event->is_free && rand(0, 100) < 70 ? now()->subDays(rand(1, 10)) : null,
                    'notes' => rand(0, 100) < 30 ? $faker->sentence : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                $createdCount++;
                $totalRegistrations++;
            }

            $event->update(['current_participants' => $createdCount]);
            $this->command->info("✓ Created {$createdCount} registrations for: {$event->title}");
        }

        $this->command->info("✅ Total registrations created: {$totalRegistrations}");
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = 'REG-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (EventRegistration::where('registration_code', $code)->exists());

        return $code;
    }

    private function randomStatus()
    {
        $statuses = [
            EventRegistration::STATUS_PENDING => 10,
            EventRegistration::STATUS_CONFIRMED => 70,
            EventRegistration::STATUS_ATTENDED => 15,
            EventRegistration::STATUS_CANCELLED => 3,
            EventRegistration::STATUS_NO_SHOW => 2,
        ];

        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $status => $percentage) {
            $cumulative += $percentage;
            if ($random <= $cumulative) {
                return $status;
            }
        }

        return EventRegistration::STATUS_CONFIRMED;
    }

    private function randomPaymentStatus()
    {
        $statuses = [
            EventRegistration::PAYMENT_PAID => 70,
            EventRegistration::PAYMENT_UNPAID => 25,
            EventRegistration::PAYMENT_REFUNDED => 5,
        ];

        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $status => $percentage) {
            $cumulative += $percentage;
            if ($random <= $cumulative) {
                return $status;
            }
        }

        return EventRegistration::PAYMENT_PAID;
    }

    private function generateCustomData($event, $faker)
    {
        $customData = [];

        // Untuk kajian dan pelatihan
        if (
            str_contains(strtolower($event->title), 'kajian') ||
            str_contains(strtolower($event->title), 'dialog') ||
            str_contains(strtolower($event->title), 'kuliah')
        ) {
            $customData['pendidikan_terakhir'] = $faker->randomElement(['SMA', 'D3', 'S1', 'S2', 'S3']);
            $customData['pekerjaan'] = $faker->jobTitle;
        }

        // Untuk anak yatim
        if (
            str_contains(strtolower($event->title), 'yatim') ||
            str_contains(strtolower($event->title), 'dhuafa')
        ) {
            $customData['status_yatim'] = $faker->randomElement(['Yatim', 'Piatu', 'Yatim Piatu', 'Dhuafa']);
            $customData['nama_wali'] = $faker->name;
        }

        // Untuk lomba MTQ/MHQ
        if (
            str_contains(strtolower($event->title), 'mtq') ||
            str_contains(strtolower($event->title), 'mhq') ||
            str_contains(strtolower($event->title), 'lomba')
        ) {
            $customData['kategori_lomba'] = $faker->randomElement(['Anak-anak', 'Remaja', 'Dewasa']);
            $customData['pengalaman_lomba'] = $faker->randomElement(['Pemula', 'Berpengalaman', 'Profesional']);
        }

        // Untuk sahur on the road (konvoi)
        if (
            str_contains(strtolower($event->title), 'konvoi') ||
            str_contains(strtolower($event->title), 'sahur on the road')
        ) {
            $customData['jenis_kendaraan'] = $faker->randomElement(['Honda Beat', 'Honda Vario', 'Yamaha Mio', 'Suzuki Nex']);
            $customData['no_polisi'] = $faker->regexify('[A-Z]{1}[0-9]{4}[A-Z]{2,3}');
            $customData['no_sim'] = $faker->numerify('############');
        }

        // Untuk i'tikaf
        if (
            str_contains(strtolower($event->title), 'itikaf') ||
            str_contains(strtolower($event->title), 'i\'tikaf')
        ) {
            $customData['durasi_itikaf'] = $faker->randomElement(['3 hari', '5 hari', '10 hari']);
            $customData['kebutuhan_khusus'] = $faker->optional(0.3)->sentence;
        }

        return empty($customData) ? null : $customData;
    }
}
