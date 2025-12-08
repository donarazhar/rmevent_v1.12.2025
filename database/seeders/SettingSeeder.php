<?php
// database/seeders/SettingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ============================================
            // GENERAL SETTINGS
            // ============================================
            [
                'key' => 'site_name',
                'value' => 'Ramadhan Mubarak 1447 H',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama website/organisasi',
                'order' => 1
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Menyambut Bulan Penuh Berkah dengan Kebersamaan',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Tagline atau slogan website',
                'order' => 2
            ],
            [
                'key' => 'site_description',
                'value' => 'Platform digital terpadu untuk mengelola seluruh kegiatan Ramadhan Mubarak 1447 H. Melayani registrasi jamaah, informasi kegiatan, dan manajemen internal panitia dengan sistem yang transparan dan akuntabel.',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Deskripsi singkat website untuk SEO',
                'order' => 3
            ],
            [
                'key' => 'site_logo',
                'value' => '/images/logo-ramadhan-mubarak.png',
                'type' => 'image',
                'group' => 'general',
                'description' => 'Logo utama website',
                'order' => 4
            ],
            [
                'key' => 'site_logo_light',
                'value' => '/images/logo-ramadhan-mubarak-light.png',
                'type' => 'image',
                'group' => 'general',
                'description' => 'Logo untuk background gelap',
                'order' => 5
            ],
            [
                'key' => 'site_favicon',
                'value' => '/images/favicon.ico',
                'type' => 'image',
                'group' => 'general',
                'description' => 'Favicon website (16x16 atau 32x32 px)',
                'order' => 6
            ],
            [
                'key' => 'site_timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Timezone website',
                'order' => 7
            ],
            [
                'key' => 'site_language',
                'value' => 'id',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Bahasa default website (id/en)',
                'order' => 8
            ],

            // ============================================
            // CONTACT INFORMATION
            // ============================================
            [
                'key' => 'contact_email',
                'value' => 'info@ramadhanmubarak.org',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Email utama untuk dihubungi',
                'order' => 1
            ],
            [
                'key' => 'contact_email_support',
                'value' => 'support@ramadhanmubarak.org',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Email untuk support/bantuan',
                'order' => 2
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 812-3456-7890',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Nomor telepon/WhatsApp',
                'order' => 3
            ],
            [
                'key' => 'contact_phone_alt',
                'value' => '+62 813-9876-5432',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Nomor telepon alternatif',
                'order' => 4
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Masjid Agung No. 123, Menteng, Jakarta Pusat 10310',
                'type' => 'textarea',
                'group' => 'contact',
                'description' => 'Alamat lengkap sekretariat',
                'order' => 5
            ],
            [
                'key' => 'contact_maps_url',
                'value' => 'https://maps.google.com/?q=-6.1944491,106.8229198',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Google Maps URL',
                'order' => 6
            ],
            [
                'key' => 'contact_maps_embed',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'type' => 'textarea',
                'group' => 'contact',
                'description' => 'Google Maps Embed Code',
                'order' => 7
            ],

            // ============================================
            // SOCIAL MEDIA
            // ============================================
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/ramadhanmubarak1447',
                'type' => 'text',
                'group' => 'social',
                'description' => 'URL Facebook Page',
                'order' => 1
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/ramadhanmubarak1447',
                'type' => 'text',
                'group' => 'social',
                'description' => 'URL Instagram',
                'order' => 2
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/ramadhan1447',
                'type' => 'text',
                'group' => 'social',
                'description' => 'URL Twitter/X',
                'order' => 3
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@ramadhanmubarak1447',
                'type' => 'text',
                'group' => 'social',
                'description' => 'URL YouTube Channel',
                'order' => 4
            ],
            [
                'key' => 'social_tiktok',
                'value' => 'https://tiktok.com/@ramadhanmubarak',
                'type' => 'text',
                'group' => 'social',
                'description' => 'URL TikTok',
                'order' => 5
            ],
            [
                'key' => 'social_whatsapp',
                'value' => '+6281234567890',
                'type' => 'text',
                'group' => 'social',
                'description' => 'WhatsApp Business number',
                'order' => 6
            ],
            [
                'key' => 'social_telegram',
                'value' => 'https://t.me/ramadhanmubarak1447',
                'type' => 'text',
                'group' => 'social',
                'description' => 'URL Telegram Group/Channel',
                'order' => 7
            ],

            // ============================================
            // EVENT CONFIGURATION
            // ============================================
            [
                'key' => 'event_year',
                'value' => '1447',
                'type' => 'text',
                'group' => 'event',
                'description' => 'Tahun Hijriyah event',
                'order' => 1
            ],
            [
                'key' => 'event_ramadhan_start',
                'value' => '2025-03-01',
                'type' => 'date',
                'group' => 'event',
                'description' => 'Tanggal mulai Ramadhan (perkiraan)',
                'order' => 2
            ],
            [
                'key' => 'event_ramadhan_end',
                'value' => '2025-03-30',
                'type' => 'date',
                'group' => 'event',
                'description' => 'Tanggal akhir Ramadhan (perkiraan)',
                'order' => 3
            ],
            [
                'key' => 'event_main_location',
                'value' => 'Masjid Agung Al-Mubarak, Jakarta',
                'type' => 'text',
                'group' => 'event',
                'description' => 'Lokasi utama kegiatan',
                'order' => 4
            ],

            // ============================================
            // FEATURES & FUNCTIONALITY
            // ============================================
            [
                'key' => 'enable_registration',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Aktifkan fitur registrasi event',
                'order' => 1
            ],
            [
                'key' => 'enable_feedback',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Aktifkan fitur feedback jamaah',
                'order' => 2
            ],
            [
                'key' => 'enable_comments',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Aktifkan komentar di artikel',
                'order' => 3
            ],
            [
                'key' => 'enable_testimonials',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Tampilkan testimonial di homepage',
                'order' => 4
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'features',
                'description' => 'Mode maintenance website',
                'order' => 5
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Website sedang dalam perbaikan. Mohon maaf atas ketidaknyamanannya.',
                'type' => 'textarea',
                'group' => 'features',
                'description' => 'Pesan saat maintenance mode aktif',
                'order' => 6
            ],

            // ============================================
            // EMAIL SETTINGS
            // ============================================
            [
                'key' => 'email_from_name',
                'value' => 'Ramadhan Mubarak 1447 H',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Nama pengirim email',
                'order' => 1
            ],
            [
                'key' => 'email_from_address',
                'value' => 'noreply@ramadhanmubarak.org',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Email pengirim',
                'order' => 2
            ],
            [
                'key' => 'email_footer_text',
                'value' => 'Barakallahu fiikum. Semoga Allah meridhai setiap langkah kebaikan kita.',
                'type' => 'textarea',
                'group' => 'email',
                'description' => 'Teks footer email',
                'order' => 3
            ],

            // ============================================
            // APPEARANCE SETTINGS
            // ============================================
            [
                'key' => 'theme_primary_color',
                'value' => '#059669',
                'type' => 'color',
                'group' => 'appearance',
                'description' => 'Warna utama tema (hijau Islamic)',
                'order' => 1
            ],
            [
                'key' => 'theme_secondary_color',
                'value' => '#F59E0B',
                'type' => 'color',
                'group' => 'appearance',
                'description' => 'Warna sekunder tema (emas)',
                'order' => 2
            ],
            [
                'key' => 'homepage_hero_title',
                'value' => 'Ramadhan Mubarak 1447 H',
                'type' => 'text',
                'group' => 'appearance',
                'description' => 'Judul hero section homepage',
                'order' => 3
            ],
            [
                'key' => 'homepage_hero_subtitle',
                'value' => 'Menyambut Bulan Penuh Berkah dengan Kebersamaan',
                'type' => 'textarea',
                'group' => 'appearance',
                'description' => 'Subjudul hero section',
                'order' => 4
            ],
            [
                'key' => 'homepage_about_text',
                'value' => 'Ramadhan Mubarak 1447 H adalah program rutin tahunan yang diselenggarakan untuk memfasilitasi umat Muslim dalam menjalankan ibadah di bulan suci Ramadhan. Kami menghadirkan berbagai kegiatan spiritual, sosial, dan edukatif yang dirancang untuk meningkatkan ketakwaan dan mempererat ukhuwah Islamiyah.',
                'type' => 'textarea',
                'group' => 'appearance',
                'description' => 'Teks About Us di homepage',
                'order' => 5
            ],

            // ============================================
            // SEO SETTINGS
            // ============================================
            [
                'key' => 'seo_meta_title',
                'value' => 'Ramadhan Mubarak 1447 H - Portal Digital Kegiatan Ramadhan',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta title untuk SEO',
                'order' => 1
            ],
            [
                'key' => 'seo_meta_description',
                'value' => 'Platform digital terpadu untuk kegiatan Ramadhan Mubarak 1447 H. Daftar event, kajian, tarawih, dan berbagai kegiatan spiritual lainnya.',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Meta description untuk SEO',
                'order' => 2
            ],
            [
                'key' => 'seo_meta_keywords',
                'value' => 'ramadhan, ramadhan 1447, kegiatan ramadhan, kajian islam, tarawih, buka puasa bersama, tadarus quran',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta keywords (comma separated)',
                'order' => 3
            ],
            [
                'key' => 'seo_og_image',
                'value' => '/images/og-ramadhan-mubarak.jpg',
                'type' => 'image',
                'group' => 'seo',
                'description' => 'Open Graph image untuk social sharing',
                'order' => 4
            ],

            // ============================================
            // NOTIFICATION SETTINGS
            // ============================================
            [
                'key' => 'notification_email_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Aktifkan notifikasi email',
                'order' => 1
            ],
            [
                'key' => 'notification_sms_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Aktifkan notifikasi SMS',
                'order' => 2
            ],
            [
                'key' => 'notification_whatsapp_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Aktifkan notifikasi WhatsApp',
                'order' => 3
            ],

            // ============================================
            // ANALYTICS & TRACKING
            // ============================================
            [
                'key' => 'analytics_google_id',
                'value' => 'G-XXXXXXXXXX',
                'type' => 'text',
                'group' => 'analytics',
                'description' => 'Google Analytics ID (GA4)',
                'order' => 1
            ],
            [
                'key' => 'analytics_facebook_pixel',
                'value' => '',
                'type' => 'text',
                'group' => 'analytics',
                'description' => 'Facebook Pixel ID',
                'order' => 2
            ],

            // ============================================
            // FOOTER SETTINGS
            // ============================================
            [
                'key' => 'footer_about_text',
                'value' => 'Ramadhan Mubarak 1447 H adalah inisiatif untuk memfasilitasi umat Muslim dalam menjalankan ibadah di bulan suci Ramadhan dengan lebih baik dan terorganisir.',
                'type' => 'textarea',
                'group' => 'footer',
                'description' => 'Teks about di footer',
                'order' => 1
            ],
            [
                'key' => 'footer_copyright_text',
                'value' => '© 2025 Ramadhan Mubarak 1447 H. All rights reserved.',
                'type' => 'text',
                'group' => 'footer',
                'description' => 'Teks copyright',
                'order' => 2
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Settings seeded successfully! Total: ' . count($settings));
    }
}
