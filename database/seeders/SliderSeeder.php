<?php
// database/seeders/SliderSeeder.php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            // Main Hero Slider
            [
                'title' => 'Ramadhan Mubarak 1447 H',
                'subtitle' => 'Menyambut Bulan Penuh Berkah',
                'description' => 'Mari bersama-sama memaksimalkan ibadah di bulan suci Ramadhan melalui berbagai kegiatan spiritual yang telah kami persiapkan.',
                'image' => '/images/sliders/hero-ramadhan-main.jpg',
                'image_mobile' => '/images/sliders/hero-ramadhan-main-mobile.jpg',
                'button_text' => 'Lihat Jadwal Kegiatan',
                'button_url' => '/events',
                'button_style' => 'primary',
                'text_position' => 'center',
                'overlay_color' => '#000000',
                'overlay_opacity' => 40,
                'animation_effect' => 'fade',
                'order' => 1,
                'is_active' => true,
                'active_from' => now(),
                'active_until' => now()->addMonths(2),
                'placement' => 'homepage',
            ],

            // Slider 2 - Kajian Rutin
            [
                'title' => 'Kajian Rutin Bersama Ustadz Terbaik',
                'subtitle' => 'Setiap Hari Setelah Maghrib',
                'description' => 'Tingkatkan pemahaman agama Anda melalui kajian rutin dengan ustadz yang berpengalaman dan kompeten.',
                'image' => '/images/sliders/kajian-rutin.jpg',
                'image_mobile' => '/images/sliders/kajian-rutin-mobile.jpg',
                'button_text' => 'Daftar Sekarang',
                'button_url' => '/events?category=kajian',
                'button_style' => 'primary',
                'text_position' => 'left',
                'overlay_color' => '#059669',
                'overlay_opacity' => 50,
                'animation_effect' => 'slide',
                'order' => 2,
                'is_active' => true,
                'active_from' => now(),
                'active_until' => now()->addMonths(2),
                'placement' => 'homepage',
            ],

            // Slider 3 - Tarawih
            [
                'title' => 'Shalat Tarawih Berjamaah',
                'subtitle' => 'Setiap Malam di Masjid Agung',
                'description' => 'Ramaikan shalat tarawih berjamaah dan rasakan kekhusyukan ibadah di bulan Ramadhan.',
                'image' => '/images/sliders/tarawih.jpg',
                'image_mobile' => '/images/sliders/tarawih-mobile.jpg',
                'button_text' => 'Info Lebih Lanjut',
                'button_url' => '/events?category=tarawih',
                'button_style' => 'secondary',
                'text_position' => 'right',
                'overlay_color' => '#1E3A8A',
                'overlay_opacity' => 45,
                'animation_effect' => 'zoom',
                'order' => 3,
                'is_active' => true,
                'active_from' => now(),
                'active_until' => now()->addMonths(2),
                'placement' => 'homepage',
            ],

            // Slider 4 - Buka Puasa Bersama
            [
                'title' => 'Buka Puasa Bersama',
                'subtitle' => 'Berbagi Kebahagiaan di Bulan Ramadhan',
                'description' => 'Bergabunglah dalam acara buka puasa bersama yang penuh dengan kehangatan dan kebersamaan.',
                'image' => '/images/sliders/buka-puasa-bersama.jpg',
                'image_mobile' => '/images/sliders/buka-puasa-bersama-mobile.jpg',
                'button_text' => 'Lihat Jadwal',
                'button_url' => '/events?category=buka-puasa',
                'button_style' => 'primary',
                'text_position' => 'center',
                'overlay_color' => '#DC2626',
                'overlay_opacity' => 35,
                'animation_effect' => 'fade',
                'order' => 4,
                'is_active' => true,
                'active_from' => now(),
                'active_until' => now()->addMonths(2),
                'placement' => 'homepage',
            ],

            // Slider 5 - Donasi & Zakat
            [
                'title' => 'Salurkan Zakat & Sedekah Anda',
                'subtitle' => 'Bersama Kita Berbagi Kebahagiaan',
                'description' => 'Tunaikan zakat fitrah dan sedekah Anda melalui program yang amanah dan transparan.',
                'image' => '/images/sliders/zakat-sedekah.jpg',
                'image_mobile' => '/images/sliders/zakat-sedekah-mobile.jpg',
                'button_text' => 'Donasi Sekarang',
                'button_url' => '/donasi',
                'button_style' => 'outline',
                'text_position' => 'left',
                'overlay_color' => '#F59E0B',
                'overlay_opacity' => 40,
                'animation_effect' => 'slide',
                'order' => 5,
                'is_active' => true,
                'active_from' => now(),
                'active_until' => now()->addMonths(2),
                'placement' => 'homepage',
            ],

            // Event Page Banner
            [
                'title' => 'Jelajahi Semua Kegiatan Ramadhan',
                'subtitle' => 'Temukan Kegiatan yang Sesuai Dengan Minat Anda',
                'description' => 'Lebih dari 50 kegiatan spiritual, sosial, dan edukatif telah kami siapkan untuk Anda.',
                'image' => '/images/sliders/events-banner.jpg',
                'image_mobile' => '/images/sliders/events-banner-mobile.jpg',
                'button_text' => 'Mulai Menjelajah',
                'button_url' => '#events-list',
                'button_style' => 'primary',
                'text_position' => 'center',
                'overlay_color' => '#7C3AED',
                'overlay_opacity' => 50,
                'animation_effect' => 'fade',
                'order' => 1,
                'is_active' => true,
                'active_from' => now(),
                'active_until' => now()->addMonths(2),
                'placement' => 'events',
            ],

            // About Page Banner
            [
                'title' => 'Tentang Ramadhan Mubarak 1447 H',
                'subtitle' => 'Dedikasi untuk Umat',
                'description' => 'Mengenal lebih dekat visi, misi, dan perjalanan kami dalam melayani umat Muslim.',
                'image' => '/images/sliders/about-banner.jpg',
                'image_mobile' => '/images/sliders/about-banner-mobile.jpg',
                'button_text' => null,
                'button_url' => null,
                'button_style' => 'primary',
                'text_position' => 'center',
                'overlay_color' => '#059669',
                'overlay_opacity' => 45,
                'animation_effect' => 'fade',
                'order' => 1,
                'is_active' => true,
                'active_from' => now(),
                'active_until' => now()->addMonths(2),
                'placement' => 'about',
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }

        $this->command->info('✅ Sliders seeded successfully! Total: ' . Slider::count());
        $this->command->info('💡 Note: Placeholder images need to be replaced with actual images');
    }
}
