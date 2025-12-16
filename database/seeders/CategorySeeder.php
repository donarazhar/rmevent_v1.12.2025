<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Categories untuk Posts/Blog
            [
                'name' => 'Berita',
                'slug' => 'berita',
                'description' => 'Berita dan informasi seputar kegiatan masjid',
                'icon' => 'fas fa-newspaper',
                'color' => '#3B82F6',
                'type' => 'post',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Artikel Islami',
                'slug' => 'artikel-islami',
                'description' => 'Artikel dan kajian tentang Islam',
                'icon' => 'fas fa-book-open',
                'color' => '#10B981',
                'type' => 'post',
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Tips',
                'slug' => 'tips',
                'description' => 'Tips dan panduan ibadah',
                'icon' => 'fas fa-lightbulb',
                'color' => '#F59E0B',
                'type' => 'post',
                'parent_id' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Kisah Inspiratif',
                'slug' => 'kisah-inspiratif',
                'description' => 'Kisah-kisah inspiratif dari tokoh Islam',
                'icon' => 'fas fa-heart',
                'color' => '#EF4444',
                'type' => 'post',
                'parent_id' => null,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman resmi dari masjid',
                'icon' => 'fas fa-bullhorn',
                'color' => '#8B5CF6',
                'type' => 'post',
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
            ],

            // Categories untuk Events - disesuaikan dengan PDF
            [
                'name' => 'Kajian Ramadhan',
                'slug' => 'kajian-ramadhan',
                'description' => 'Kajian dan ceramah khusus Ramadhan',
                'icon' => 'fas fa-mosque',
                'color' => '#059669',
                'type' => 'event',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Tilawah Al-Quran',
                'slug' => 'tilawah-alquran',
                'description' => 'Kegiatan tilawah, tadarus, dan tahsin Al-Quran',
                'icon' => 'fas fa-quran',
                'color' => '#0EA5E9',
                'type' => 'event',
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Tarawih Berjamaah',
                'slug' => 'tarawih-berjamaah',
                'description' => 'Shalat tarawih berjamaah',
                'icon' => 'fas fa-praying-hands',
                'color' => '#7C3AED',
                'type' => 'event',
                'parent_id' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Dzikir & Doa',
                'slug' => 'dzikir-doa',
                'description' => 'Majelis dzikir dan doa bersama',
                'icon' => 'fas fa-hands',
                'color' => '#6366F1',
                'type' => 'event',
                'parent_id' => null,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Buka Puasa Bersama',
                'slug' => 'buka-puasa-bersama',
                'description' => 'Acara buka puasa bersama',
                'icon' => 'fas fa-utensils',
                'color' => '#DC2626',
                'type' => 'event',
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Sahur On The Road',
                'slug' => 'sahur-on-the-road',
                'description' => 'Kegiatan sahur keliling',
                'icon' => 'fas fa-motorcycle',
                'color' => '#F97316',
                'type' => 'event',
                'parent_id' => null,
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Kegiatan Sosial',
                'slug' => 'kegiatan-sosial',
                'description' => 'Kegiatan sosial dan santunan',
                'icon' => 'fas fa-hands-helping',
                'color' => '#F97316',
                'type' => 'event',
                'parent_id' => null,
                'order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded successfully! Total: ' . Category::count());
    }
}
