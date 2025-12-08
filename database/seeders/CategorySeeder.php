<?php
// database/seeders/CategorySeeder.php

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
                'description' => 'Berita terkini seputar event Ramadhan Mubarak',
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
                'description' => 'Artikel dan kajian tentang nilai-nilai Islam',
                'icon' => 'fas fa-book-open',
                'color' => '#10B981',
                'type' => 'post',
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Tips & Panduan',
                'slug' => 'tips-panduan',
                'description' => 'Tips dan panduan ibadah di bulan Ramadhan',
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
                'description' => 'Kisah-kisah inspiratif dari para jamaah',
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
                'description' => 'Pengumuman resmi dari panitia',
                'icon' => 'fas fa-bullhorn',
                'color' => '#8B5CF6',
                'type' => 'post',
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
            ],

            // Categories untuk Events
            [
                'name' => 'Kajian & Ceramah',
                'slug' => 'kajian-ceramah',
                'description' => 'Kajian rutin dan ceramah ustadz',
                'icon' => 'fas fa-mosque',
                'color' => '#059669',
                'type' => 'event',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Tadarus Al-Quran',
                'slug' => 'tadarus-alquran',
                'description' => 'Kegiatan tadarus dan tahsin Al-Quran',
                'icon' => 'fas fa-quran',
                'color' => '#0EA5E9',
                'type' => 'event',
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Shalat Tarawih',
                'slug' => 'shalat-tarawih',
                'description' => 'Shalat tarawih berjamaah',
                'icon' => 'fas fa-praying-hands',
                'color' => '#7C3AED',
                'type' => 'event',
                'parent_id' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Buka Puasa Bersama',
                'slug' => 'buka-puasa-bersama',
                'description' => 'Acara buka puasa bersama jamaah',
                'icon' => 'fas fa-utensils',
                'color' => '#DC2626',
                'type' => 'event',
                'parent_id' => null,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Kegiatan Sosial',
                'slug' => 'kegiatan-sosial',
                'description' => 'Kegiatan berbagi dan santunan',
                'icon' => 'fas fa-hands-helping',
                'color' => '#F97316',
                'type' => 'event',
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Qiyamul Lail',
                'slug' => 'qiyamul-lail',
                'description' => 'Shalat malam dan tahajud berjamaah',
                'icon' => 'fas fa-moon',
                'color' => '#6366F1',
                'type' => 'event',
                'parent_id' => null,
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Pelatihan & Workshop',
                'slug' => 'pelatihan-workshop',
                'description' => 'Workshop dan pelatihan keislaman',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => '#14B8A6',
                'type' => 'event',
                'parent_id' => null,
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Kegiatan Anak',
                'slug' => 'kegiatan-anak',
                'description' => 'Program khusus untuk anak-anak',
                'icon' => 'fas fa-child',
                'color' => '#EC4899',
                'type' => 'event',
                'parent_id' => null,
                'order' => 8,
                'is_active' => true,
            ],

            // General Categories
            [
                'name' => 'Umum',
                'slug' => 'umum',
                'description' => 'Kategori umum',
                'icon' => 'fas fa-folder',
                'color' => '#6B7280',
                'type' => 'general',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Sub-categories untuk "Artikel Islami"
        $artikelIslami = Category::where('slug', 'artikel-islami')->first();
        if ($artikelIslami) {
            $subCategories = [
                [
                    'name' => 'Fiqih',
                    'slug' => 'fiqih',
                    'description' => 'Pembahasan hukum-hukum Islam',
                    'icon' => 'fas fa-balance-scale',
                    'color' => '#10B981',
                    'type' => 'post',
                    'parent_id' => $artikelIslami->id,
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'name' => 'Akhlak',
                    'slug' => 'akhlak',
                    'description' => 'Pembahasan tentang akhlak mulia',
                    'icon' => 'fas fa-hand-holding-heart',
                    'color' => '#10B981',
                    'type' => 'post',
                    'parent_id' => $artikelIslami->id,
                    'order' => 2,
                    'is_active' => true,
                ],
                [
                    'name' => 'Tafsir',
                    'slug' => 'tafsir',
                    'description' => 'Tafsir ayat-ayat Al-Quran',
                    'icon' => 'fas fa-book-reader',
                    'color' => '#10B981',
                    'type' => 'post',
                    'parent_id' => $artikelIslami->id,
                    'order' => 3,
                    'is_active' => true,
                ],
            ];

            foreach ($subCategories as $subCategory) {
                Category::create($subCategory);
            }
        }

        $this->command->info('✅ Categories seeded successfully! Total: ' . Category::count());
    }
}