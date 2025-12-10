<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories for posts
        $categories = Category::where('type', Category::TYPE_POST)->get();

        // Get admin user as author
        $author = User::where('role', 'admin')->first();

        if (!$author || $categories->isEmpty()) {
            $this->command->warn('Please run UserSeeder and CategorySeeder first!');
            return;
        }

        $this->command->info('📝 Available post categories:');
        foreach ($categories as $cat) {
            $this->command->info("  - {$cat->name}");
        }

        // Create or get tags
        $tags = $this->createTags();

        // Posts data - UPDATED with correct category names
        $posts = [
            // Berita
            [
                'title' => 'Keutamaan 10 Hari Terakhir Ramadhan',
                'category' => 'Berita', // Changed from 'Kajian Ramadhan'
                'excerpt' => 'Mengapa 10 hari terakhir Ramadhan begitu istimewa? Pelajari keutamaan dan amalan yang dianjurkan.',
                'content' => $this->getContent1(),
                'tags' => ['Ramadhan', 'Kajian', 'Ibadah', 'Lailatul Qadr'],
                'is_featured' => true,
                'reading_time' => 8,
                'views_count' => 1250,
            ],
            [
                'title' => 'Adab dan Tata Cara Berbuka Puasa yang Benar',
                'category' => 'Berita',
                'excerpt' => 'Panduan lengkap tentang adab berbuka puasa sesuai sunnah Rasulullah SAW.',
                'content' => $this->getContent2(),
                'tags' => ['Ramadhan', 'Puasa', 'Sunnah', 'Adab'],
                'is_featured' => false,
                'reading_time' => 6,
                'views_count' => 890,
            ],
            [
                'title' => 'Hikmah dan Makna Puasa Ramadhan',
                'category' => 'Berita',
                'excerpt' => 'Memahami lebih dalam tentang hikmah dan makna di balik ibadah puasa Ramadhan.',
                'content' => $this->getContent3(),
                'tags' => ['Ramadhan', 'Puasa', 'Hikmah'],
                'is_featured' => false,
                'reading_time' => 7,
                'views_count' => 720,
            ],

            // Artikel Islami
            [
                'title' => '7 Tips Khatam Al-Quran di Bulan Ramadhan',
                'category' => 'Artikel Islami',
                'excerpt' => 'Strategi praktis untuk menyelesaikan bacaan Al-Quran 30 juz selama Ramadhan.',
                'content' => $this->getContent4(),
                'tags' => ['Al-Quran', 'Tilawah', 'Ramadhan', 'Khatam'],
                'is_featured' => true,
                'reading_time' => 5,
                'views_count' => 1450,
            ],
            [
                'title' => 'Adab Membaca Al-Quran yang Perlu Diketahui',
                'category' => 'Artikel Islami',
                'excerpt' => 'Pelajari adab dan etika yang benar dalam membaca Al-Quran.',
                'content' => $this->getContent5(),
                'tags' => ['Al-Quran', 'Tilawah', 'Adab'],
                'is_featured' => false,
                'reading_time' => 6,
                'views_count' => 680,
            ],
            [
                'title' => 'Doa-Doa Pilihan untuk Bulan Ramadhan',
                'category' => 'Artikel Islami',
                'excerpt' => 'Kumpulan doa-doa mustajab yang dianjurkan dibaca selama Ramadhan.',
                'content' => $this->getContent6(),
                'tags' => ['Doa', 'Ramadhan', 'Dzikir'],
                'is_featured' => true,
                'reading_time' => 10,
                'views_count' => 2100,
            ],

            // Tips
            [
                'title' => 'Amalan Dzikir Pagi dan Petang',
                'category' => 'Tips',
                'excerpt' => 'Panduan lengkap dzikir pagi dan petang beserta keutamaannya.',
                'content' => $this->getContent7(),
                'tags' => ['Dzikir', 'Pagi', 'Petang', 'Amalan'],
                'is_featured' => false,
                'reading_time' => 8,
                'views_count' => 950,
            ],
            [
                'title' => 'Tips Menjaga Kesehatan Saat Berpuasa',
                'category' => 'Tips',
                'excerpt' => 'Panduan menjaga kesehatan tubuh dan nutrisi yang tepat selama berpuasa.',
                'content' => $this->getContent10(),
                'tags' => ['Kesehatan', 'Puasa', 'Nutrisi', 'Tips'],
                'is_featured' => false,
                'reading_time' => 7,
                'views_count' => 1120,
            ],
            [
                'title' => 'Menu Sahur Sehat dan Bergizi',
                'category' => 'Tips',
                'excerpt' => 'Rekomendasi menu sahur yang menyehatkan dan tahan lapar seharian.',
                'content' => $this->getContent11(),
                'tags' => ['Sahur', 'Makanan', 'Kesehatan', 'Nutrisi'],
                'is_featured' => false,
                'reading_time' => 5,
                'views_count' => 890,
            ],

            // Kisah Inspiratif
            [
                'title' => 'Kisah Sahabat Nabi dalam Menyambut Ramadhan',
                'category' => 'Kisah Inspiratif',
                'excerpt' => 'Belajar dari semangat para sahabat dalam beribadah di bulan Ramadhan.',
                'content' => $this->getContent12(),
                'tags' => ['Kisah', 'Sahabat', 'Ramadhan', 'Inspirasi'],
                'is_featured' => true,
                'reading_time' => 12,
                'views_count' => 1650,
            ],

            // Pengumuman
            [
                'title' => 'Panduan Lengkap Zakat Fitrah 2025',
                'category' => 'Pengumuman',
                'excerpt' => 'Ketentuan, waktu, dan besaran zakat fitrah yang wajib diketahui.',
                'content' => $this->getContent8(),
                'tags' => ['Zakat', 'Zakat Fitrah', 'Ramadhan'],
                'is_featured' => true,
                'reading_time' => 9,
                'views_count' => 1800,
            ],
            [
                'title' => 'Keutamaan Sedekah di Bulan Ramadhan',
                'category' => 'Pengumuman',
                'excerpt' => 'Mengapa sedekah di bulan Ramadhan memiliki pahala yang berlipat ganda?',
                'content' => $this->getContent9(),
                'tags' => ['Sedekah', 'Ramadhan', 'Pahala'],
                'is_featured' => false,
                'reading_time' => 6,
                'views_count' => 740,
            ],

            // Additional Posts for variety
            [
                'title' => 'Jadwal Imsakiyah Ramadhan 1447 H',
                'category' => 'Berita',
                'excerpt' => 'Jadwal lengkap waktu imsak dan berbuka puasa selama bulan Ramadhan.',
                'content' => '<h2>Jadwal Imsakiyah Ramadhan 1447 H</h2><p>Berikut adalah jadwal lengkap waktu imsak dan berbuka puasa untuk wilayah Jakarta dan sekitarnya selama bulan Ramadhan 1447 H. Jadwal ini dapat dijadikan panduan untuk mempersiapkan sahur dan berbuka puasa tepat waktu.</p><h3>Pentingnya Mengetahui Jadwal Imsakiyah</h3><p>Mengetahui jadwal imsakiyah sangat penting agar kita dapat mengatur waktu sahur dengan baik dan tidak melewatkan waktu imsak. Begitu juga dengan waktu berbuka, agar kita dapat segera berbuka di waktu yang tepat sesuai sunnah Rasulullah SAW.</p>',
                'tags' => ['Ramadhan', 'Jadwal', 'Imsakiyah'],
                'is_featured' => false,
                'reading_time' => 4,
                'views_count' => 2500,
            ],
            [
                'title' => 'Makna Takbiran dan Adab Merayakan Idul Fitri',
                'category' => 'Artikel Islami',
                'excerpt' => 'Memahami makna takbiran dan tata cara merayakan Idul Fitri sesuai tuntunan Islam.',
                'content' => '<h2>Makna Takbiran dan Adab Merayakan Idul Fitri</h2><p>Takbir adalah salah satu syiar Islam yang sangat dianjurkan pada malam Idul Fitri. Takbir dilakukan untuk mengagungkan nama Allah SWT setelah sebulan penuh menjalankan ibadah puasa.</p><h3>Waktu Takbiran</h3><p>Takbir dimulai sejak terbenam matahari di akhir bulan Ramadhan hingga imam naik mimbar untuk melaksanakan shalat Idul Fitri.</p><h3>Adab Merayakan Idul Fitri</h3><ul><li>Mandi dan memakai pakaian terbaik</li><li>Makan sesuatu yang manis sebelum berangkat shalat</li><li>Berangkat ke lapangan dengan berjalan kaki jika memungkinkan</li><li>Pulang melalui jalan yang berbeda</li><li>Memperbanyak sedekah</li></ul>',
                'tags' => ['Idul Fitri', 'Takbir', 'Sunnah'],
                'is_featured' => false,
                'reading_time' => 7,
                'views_count' => 980,
            ],
            [
                'title' => '10 Amalan Sunah di Bulan Ramadhan',
                'category' => 'Tips',
                'excerpt' => 'Amalan-amalan sunnah yang dapat menambah pahala di bulan Ramadhan.',
                'content' => '<h2>10 Amalan Sunnah di Bulan Ramadhan</h2><p>Selain puasa wajib, ada banyak amalan sunnah yang dapat kita lakukan di bulan Ramadhan untuk menambah pahala dan mendekatkan diri kepada Allah SWT.</p><h3>Daftar Amalan Sunnah</h3><ol><li>Sahur di waktu akhir</li><li>Menyegerakan berbuka puasa</li><li>Berbuka dengan kurma</li><li>Membaca Al-Quran</li><li>Shalat Tarawih</li><li>Iktikaf di 10 hari terakhir</li><li>Mencari Lailatul Qadr</li><li>Memperbanyak sedekah</li><li>Berbuat baik kepada sesama</li><li>Menjaga lisan dan pandangan</li></ol>',
                'tags' => ['Amalan', 'Sunnah', 'Ramadhan', 'Ibadah'],
                'is_featured' => false,
                'reading_time' => 6,
                'views_count' => 1350,
            ],
        ];

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($posts as $postData) {
            // Get category
            $category = $categories->firstWhere('name', $postData['category']);

            if (!$category) {
                $this->command->warn("⚠ Category '{$postData['category']}' not found for: {$postData['title']}");
                $skippedCount++;
                continue;
            }

            // Create post
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'author_id' => $author->id,
                'category_id' => $category->id,
                'status' => Post::STATUS_PUBLISHED,
                'published_at' => now()->subDays(rand(1, 30)),
                'reading_time' => $postData['reading_time'],
                'views_count' => $postData['views_count'],
                'is_featured' => $postData['is_featured'],
                'allow_comments' => true,
                'is_sticky' => false,
            ]);

            // Attach tags
            $postTags = [];
            foreach ($postData['tags'] as $tagName) {
                $tag = $tags->firstWhere('name', $tagName);
                if ($tag) {
                    $postTags[] = $tag->id;
                }
            }
            $post->tags()->attach($postTags);

            $this->command->info("✓ Created post: {$post->title} [Category: {$category->name}]");
            $createdCount++;
        }

        $this->command->info("✅ Post seeding completed!");
        $this->command->info("📊 Summary: {$createdCount} posts created, {$skippedCount} skipped");
    }

    /**
     * Create tags
     */
    private function createTags()
    {
        $tagNames = [
            'Ramadhan',
            'Kajian',
            'Ibadah',
            'Lailatul Qadr',
            'Puasa',
            'Sunnah',
            'Adab',
            'Al-Quran',
            'Tilawah',
            'Khatam',
            'Doa',
            'Dzikir',
            'Pagi',
            'Petang',
            'Amalan',
            'Zakat',
            'Zakat Fitrah',
            'Sedekah',
            'Pahala',
            'Kesehatan',
            'Nutrisi',
            'Tips',
            'Sahur',
            'Makanan',
            'Kisah',
            'Sahabat',
            'Inspirasi',
            'Hikmah',
            'Jadwal',
            'Imsakiyah',
            'Idul Fitri',
            'Takbir',
        ];

        $tags = collect();
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                [
                    'slug' => Str::slug($tagName),
                    'color' => $this->randomColor(),
                ]
            );
            $tags->push($tag);
        }

        return $tags;
    }

    /**
     * Generate random color for tags
     */
    private function randomColor(): string
    {
        $colors = [
            '#EF4444',
            '#F59E0B',
            '#10B981',
            '#3B82F6',
            '#6366F1',
            '#8B5CF6',
            '#EC4899',
            '#14B8A6',
            '#F97316',
            '#84CC16',
            '#06B6D4',
            '#A855F7',
        ];
        return $colors[array_rand($colors)];
    }

    // Content methods remain the same...
    private function getContent1()
    {
        return '<h2>Keistimewaan 10 Hari Terakhir Ramadhan</h2>
<p>Sepuluh hari terakhir Ramadhan memiliki keistimewaan yang sangat luar biasa. Rasulullah SAW bersabda dalam hadits riwayat Muslim: "Carilah Lailatul Qadr pada sepuluh hari terakhir Ramadhan."</p>

<h3>Amalan yang Dianjurkan</h3>
<p>Berikut beberapa amalan yang sangat dianjurkan pada 10 hari terakhir Ramadhan:</p>
<ul>
<li><strong>Iktikaf di Masjid</strong> - Menyendiri untuk beribadah kepada Allah SWT</li>
<li><strong>Memperbanyak Doa</strong> - Terutama doa Lailatul Qadr</li>
<li><strong>Membaca Al-Quran</strong> - Memperbanyak tilawah dan tadabbur</li>
<li><strong>Shalat Tahajjud</strong> - Bangun di sepertiga malam terakhir</li>
<li><strong>Sedekah</strong> - Memperbanyak infaq dan sedekah</li>
</ul>

<h3>Doa Lailatul Qadr</h3>
<blockquote>"Allahumma innaka afuwwun tuhibbul afwa fa\'fu anni" - Ya Allah, sesungguhnya Engkau Maha Pemaaf, Engkau menyukai pemaafan, maka maafkanlah aku.</blockquote>

<p>Manfaatkan 10 hari terakhir ini dengan sebaik-baiknya, karena di dalamnya terdapat malam yang lebih baik dari seribu bulan.</p>';
    }

    private function getContent2()
    {
        return '<h2>Adab Berbuka Puasa Sesuai Sunnah</h2>
<p>Berbuka puasa bukan sekadar melepas dahaga dan lapar, tetapi juga memiliki adab dan tata cara yang diajarkan Rasulullah SAW.</p>

<h3>Waktu Berbuka</h3>
<p>Rasulullah SAW menganjurkan untuk segera berbuka puasa ketika waktu maghrib tiba. Beliau bersabda: "Manusia senantiasa dalam kebaikan selama mereka menyegerakan berbuka puasa."</p>

<h3>Makanan untuk Berbuka</h3>
<ul>
<li><strong>Kurma</strong> - Rasulullah berbuka dengan kurma basah (ruthab)</li>
<li><strong>Air Putih</strong> - Jika tidak ada kurma, berbuka dengan air putih</li>
<li><strong>Makanan Manis</strong> - Sesuatu yang manis dan mudah dicerna</li>
</ul>

<h3>Doa Berbuka Puasa</h3>
<blockquote>"Allahumma laka shumtu wa \'ala rizqika afthartu" - Ya Allah, karena-Mu aku berpuasa dan dengan rezeki-Mu aku berbuka.</blockquote>

<h3>Tips Sehat Berbuka Puasa</h3>
<ol>
<li>Jangan langsung makan dalam porsi besar</li>
<li>Mulai dengan yang manis dan ringan</li>
<li>Minum air putih yang cukup</li>
<li>Tunggu beberapa saat sebelum makan berat</li>
</ol>';
    }

    private function getContent3()
    {
        return '<h2>Hikmah dan Makna Puasa Ramadhan</h2>
<p>Puasa bukan sekadar menahan lapar dan dahaga, tetapi memiliki hikmah dan makna yang sangat dalam dalam kehidupan seorang muslim.</p>

<h3>Hikmah Spiritual</h3>
<p>Puasa melatih jiwa untuk mendekatkan diri kepada Allah SWT. Dengan menahan hawa nafsu, kita belajar untuk lebih taat dan tunduk kepada perintah-Nya.</p>

<h3>Hikmah Sosial</h3>
<p>Puasa mengajarkan empati terhadap sesama yang kekurangan. Dengan merasakan lapar dan dahaga, kita menjadi lebih peka terhadap penderitaan orang lain.</p>

<h3>Hikmah Kesehatan</h3>
<p>Secara medis, puasa terbukti memiliki banyak manfaat bagi kesehatan tubuh, seperti detoksifikasi, regenerasi sel, dan meningkatkan metabolisme.</p>

<h3>Makna Taqwa</h3>
<blockquote>"Hai orang-orang yang beriman, diwajibkan atas kamu berpuasa sebagaimana diwajibkan atas orang-orang sebelum kamu agar kamu bertakwa." (QS. Al-Baqarah: 183)</blockquote>

<p>Tujuan utama puasa adalah mencapai tingkat ketakwaan yang lebih tinggi kepada Allah SWT.</p>';
    }

    // Keep getContent1(), getContent2(), getContent3() as they are above...

    private function getContent4()
    {
        return '<h2>7 Tips Khatam Al-Quran di Bulan Ramadhan</h2>
<p>Membaca 30 juz Al-Quran dalam sebulan Ramadhan adalah target yang ingin dicapai banyak muslim. Berikut tips praktisnya:</p>

<h3>1. Buat Target Harian</h3>
<p>Bagi 30 juz menjadi target harian. Jika ingin khatam 1 kali, baca 1 juz per hari. Untuk khatam 2 kali, baca 2 juz per hari.</p>

<h3>2. Manfaatkan Waktu Luang</h3>
<ul>
<li>Setelah shalat fardhu</li>
<li>Saat menunggu berbuka</li>
<li>Setelah sahur sebelum tidur</li>
<li>Waktu istirahat siang</li>
</ul>

<h3>3. Gunakan Teknologi</h3>
<p>Manfaatkan aplikasi Al-Quran digital yang memudahkan Anda membaca kapan saja dan di mana saja.</p>

<h3>4. Baca dengan Tartil</h3>
<p>Jangan terburu-buru. Baca dengan tajwid yang benar dan renungkan maknanya.</p>

<h3>5. Konsisten Setiap Hari</h3>
<p>Lebih baik membaca sedikit tapi konsisten daripada banyak namun tidak teratur.</p>

<h3>6. Bergabung dengan Kelompok Tilawah</h3>
<p>Motivasi dari teman sejawat sangat membantu menjaga semangat.</p>

<h3>7. Berdoa Meminta Kemudahan</h3>
<p>Selalu minta kepada Allah agar dimudahkan dalam membaca dan memahami Al-Quran.</p>

<blockquote>"Sesungguhnya orang-orang yang membaca Kitab Allah dan mendirikan shalat dan menafkahkan sebagian dari rezeki yang Kami anugerahkan kepada mereka dengan diam-diam dan terang-terangan, mereka itu mengharapkan perniagaan yang tidak akan rugi." (QS. Fathir: 29)</blockquote>';
    }

    private function getContent5()
    {
        return '<h2>Adab Membaca Al-Quran</h2>
<p>Membaca Al-Quran memiliki adab dan etika tersendiri yang perlu kita perhatikan agar bacaan kita lebih khusyuk dan berkah.</p>

<h3>Sebelum Membaca</h3>
<ul>
<li><strong>Berwudhu</strong> - Pastikan dalam keadaan suci</li>
<li><strong>Memilih Tempat Bersih</strong> - Pilih tempat yang tenang dan bersih</li>
<li><strong>Menghadap Kiblat</strong> - Dianjurkan menghadap kiblat</li>
<li><strong>Membaca Taawudz</strong> - "Audzubillahi minasy syaithanir rajim"</li>
<li><strong>Membaca Basmalah</strong> - "Bismillahir rahmanir rahim"</li>
</ul>

<h3>Saat Membaca</h3>
<ol>
<li>Membaca dengan tartil (perlahan dan jelas)</li>
<li>Memperhatikan tajwid</li>
<li>Merenungkan makna ayat</li>
<li>Tidak berbicara saat membaca</li>
<li>Duduk dengan sopan</li>
</ol>

<h3>Setelah Membaca</h3>
<p>Tutup dengan doa khatam Al-Quran dan memohon agar ilmu yang didapat bermanfaat.</p>

<blockquote>"Bacalah Al-Quran dengan tartil, karena sesungguhnya Al-Quran itu diturunkan secara tartil." (Hadits)</blockquote>';
    }

    private function getContent6()
    {
        return '<h2>Doa-Doa Pilihan untuk Ramadhan</h2>
<p>Berikut kumpulan doa-doa yang sangat dianjurkan untuk diamalkan selama bulan Ramadhan:</p>

<h3>1. Doa Ketika Melihat Hilal</h3>
<p><em>"Allahumma ahillahu alaina bil amni wal imaani was salamati wal islami wat taufiqi lima tuhibbu wa tardha, rabbuna wa rabbukallah"</em></p>
<p>Artinya: "Ya Allah, tampakkanlah bulan ini kepada kami dengan membawa keamanan, iman, keselamatan, Islam, dan taufik untuk mengerjakan apa yang Engkau cintai dan ridhai. Tuhanku dan Tuhanmu adalah Allah."</p>

<h3>2. Doa Berbuka Puasa</h3>
<p><em>"Allahumma laka shumtu wa \'ala rizqika afthartu"</em></p>
<p>Artinya: "Ya Allah, karena-Mu aku berpuasa dan dengan rezeki-Mu aku berbuka."</p>

<h3>3. Doa Lailatul Qadr</h3>
<p><em>"Allahumma innaka afuwwun tuhibbul afwa fa\'fu anni"</em></p>
<p>Artinya: "Ya Allah, sesungguhnya Engkau Maha Pemaaf, Engkau menyukai pemaafan, maka maafkanlah aku."</p>

<h3>4. Doa Setelah Sahur</h3>
<p><em>"Allahumma inni as-aluka min fadhlikal wasi\'"</em></p>
<p>Artinya: "Ya Allah, aku memohon kepada-Mu dari karunia-Mu yang luas."</p>

<h3>5. Doa Ketika Hujan di Ramadhan</h3>
<p>Waktu turun hujan adalah waktu mustajab. Perbanyaklah doa saat itu.</p>

<blockquote>"Doa adalah senjata orang beriman" (Hadits)</blockquote>';
    }

    private function getContent7()
    {
        return '<h2>Amalan Dzikir Pagi dan Petang</h2>
<p>Dzikir pagi dan petang adalah amalan sunnah yang sangat dianjurkan untuk diamalkan setiap hari, terutama di bulan Ramadhan.</p>

<h3>Waktu Dzikir Pagi</h3>
<p>Dzikir pagi dilakukan setelah shalat Subuh hingga terbit matahari (sekitar pukul 06:00).</p>

<h3>Waktu Dzikir Petang</h3>
<p>Dzikir petang dilakukan setelah shalat Ashar hingga terbenam matahari (maghrib).</p>

<h3>Dzikir-Dzikir Penting</h3>
<ol>
<li><strong>Ayat Kursi</strong> - 1x</li>
<li><strong>Surat Al-Ikhlas, Al-Falaq, An-Nas</strong> - 3x setiap surat</li>
<li><strong>Tasbih, Tahmid, Takbir</strong> - 33x atau 100x</li>
<li><strong>Istighfar</strong> - 100x</li>
<li><strong>Shalawat kepada Nabi</strong> - 10x atau lebih</li>
</ol>

<h3>Keutamaan Dzikir</h3>
<ul>
<li>Mendapat perlindungan Allah</li>
<li>Terhindar dari gangguan jin dan sihir</li>
<li>Ketenangan hati</li>
<li>Pahala yang berlimpah</li>
<li>Ampunan dosa</li>
</ul>

<blockquote>"Ingatlah kalian kepada-Ku, niscaya Aku ingat kepada kalian." (QS. Al-Baqarah: 152)</blockquote>';
    }

    private function getContent8()
    {
        return '<h2>Panduan Lengkap Zakat Fitrah 2025</h2>
<p>Zakat fitrah adalah zakat yang wajib dikeluarkan oleh setiap muslim menjelang Idul Fitri. Berikut panduannya:</p>

<h3>Ketentuan Zakat Fitrah</h3>
<ul>
<li><strong>Wajib bagi:</strong> Setiap muslim yang memiliki kelebihan makanan pada malam dan hari raya</li>
<li><strong>Besaran:</strong> 1 sha\' (2,5 kg atau 3,5 liter) beras atau makanan pokok</li>
<li><strong>Dapat diganti uang:</strong> Sesuai harga beras di daerah masing-masing</li>
</ul>

<h3>Waktu Pembayaran</h3>
<ol>
<li><strong>Paling utama:</strong> Setelah shalat Subuh sampai sebelum shalat Idul Fitri</li>
<li><strong>Boleh:</strong> Sejak awal Ramadhan</li>
<li><strong>Makruh:</strong> Setelah shalat Idul Fitri</li>
</ol>

<h3>Golongan yang Berhak Menerima</h3>
<p>Zakat fitrah diberikan kepada 8 golongan mustahiq, terutama fakir miskin yang ada di sekitar kita.</p>

<h3>Besaran Zakat Fitrah 2025</h3>
<p>Sesuai dengan harga beras di daerah masing-masing, biasanya berkisar Rp 40.000 - Rp 50.000 per jiwa.</p>

<blockquote>"Rasulullah SAW mewajibkan zakat fitrah sebesar satu sha\' kurma atau satu sha\' gandum atas setiap muslim, merdeka maupun budak, laki-laki maupun perempuan, kecil maupun besar." (HR. Bukhari dan Muslim)</blockquote>';
    }

    private function getContent9()
    {
        return '<h2>Keutamaan Sedekah di Bulan Ramadhan</h2>
<p>Sedekah di bulan Ramadhan memiliki keutamaan yang sangat luar biasa. Pahala yang didapat berlipat ganda dibanding bulan-bulan lainnya.</p>

<h3>Kenapa Sedekah Ramadhan Istimewa?</h3>
<ul>
<li><strong>Pahala Berlipat Ganda</strong> - Setiap kebaikan di Ramadhan dilipatgandakan pahalanya</li>
<li><strong>Bulan Penuh Berkah</strong> - Ramadhan adalah bulan yang penuh dengan keberkahan</li>
<li><strong>Mencontoh Rasulullah</strong> - Beliau adalah orang yang paling dermawan, terutama di Ramadhan</li>
</ul>

<h3>Bentuk-Bentuk Sedekah</h3>
<ol>
<li><strong>Sedekah Harta</strong> - Memberikan uang atau barang</li>
<li><strong>Sedekah Ilmu</strong> - Berbagi pengetahuan kepada orang lain</li>
<li><strong>Sedekah Tenaga</strong> - Membantu pekerjaan orang lain</li>
<li><strong>Sedekah Senyum</strong> - Memberikan senyuman kepada sesama</li>
<li><strong>Sedekah Waktu</strong> - Meluangkan waktu untuk kebaikan</li>
</ol>

<h3>Tips Bersedekah</h3>
<p>Niatkan hanya karena Allah, jangan riya atau mengharap pujian. Sedekah terbaik adalah yang dilakukan dengan tangan kanan, tidak diketahui tangan kiri.</p>

<blockquote>"Perumpamaan orang-orang yang menginfakkan hartanya di jalan Allah adalah seperti sebutir biji yang menumbuhkan tujuh bulir, pada setiap bulir ada seratus biji." (QS. Al-Baqarah: 261)</blockquote>';
    }

    private function getContent10()
    {
        return '<h2>Tips Menjaga Kesehatan Saat Berpuasa</h2>
<p>Berpuasa bukan berarti mengabaikan kesehatan. Berikut tips agar tetap sehat dan bugar selama Ramadhan:</p>

<h3>1. Sahur yang Tepat</h3>
<ul>
<li>Makan makanan yang mengandung karbohidrat kompleks</li>
<li>Konsumsi protein yang cukup</li>
<li>Jangan lupa sayur dan buah</li>
<li>Minum air putih minimal 2 gelas</li>
</ul>

<h3>2. Berbuka dengan Bijak</h3>
<ul>
<li>Mulai dengan yang manis (kurma)</li>
<li>Jangan langsung makan berat</li>
<li>Hindari gorengan berlebihan</li>
<li>Kunyah makanan dengan perlahan</li>
</ul>

<h3>3. Hidrasi yang Cukup</h3>
<p>Terapkan pola 2-4-2: 2 gelas saat berbuka, 4 gelas antara berbuka dan sahur, 2 gelas saat sahur.</p>

<h3>4. Tetap Aktif Bergerak</h3>
<p>Lakukan olahraga ringan seperti jalan kaki atau stretching 30 menit sebelum berbuka.</p>

<h3>5. Istirahat yang Cukup</h3>
<p>Tidur minimal 6-7 jam sehari. Manfaatkan waktu tidur siang jika memungkinkan.</p>

<h3>6. Kelola Stress</h3>
<p>Puasa adalah waktu untuk menenangkan diri. Hindari stress berlebihan dan perbanyak ibadah.</p>

<blockquote>"Berpuasalah, niscaya kamu sehat." (Hadits)</blockquote>';
    }

    private function getContent11()
    {
        return '<h2>Menu Sahur Sehat dan Bergizi</h2>
<p>Sahur adalah waktu makan yang sangat penting saat berpuasa. Berikut rekomendasi menu sahur yang sehat dan tahan lapar:</p>

<h3>Prinsip Menu Sahur Sehat</h3>
<ul>
<li><strong>Karbohidrat Kompleks</strong> - Nasi merah, oatmeal, roti gandum</li>
<li><strong>Protein Tinggi</strong> - Telur, ikan, ayam, tempe, tahu</li>
<li><strong>Serat</strong> - Sayuran dan buah-buahan</li>
<li><strong>Lemak Sehat</strong> - Alpukat, kacang-kacangan</li>
<li><strong>Cairan</strong> - Air putih, jus buah, susu</li>
</ul>

<h3>Contoh Menu Sahur</h3>

<h4>Menu 1: Nasi Merah dengan Protein</h4>
<ul>
<li>Nasi merah 1 porsi</li>
<li>Telur rebus/dadar 1-2 butir</li>
<li>Tempe/tahu bacem</li>
<li>Sayur bening</li>
<li>Buah-buahan segar</li>
</ul>

<h4>Menu 2: Oatmeal Bergizi</h4>
<ul>
<li>Oatmeal dengan susu</li>
<li>Pisang dan kacang almond</li>
<li>Madu sebagai pemanis</li>
<li>Telur rebus 1 butir</li>
</ul>

<h4>Menu 3: Roti Gandum Isi</h4>
<ul>
<li>Roti gandum 2 lembar</li>
<li>Isi: telur, sayuran, keju</li>
<li>Jus buah segar</li>
<li>Yogurt plain</li>
</ul>

<h3>Yang Harus Dihindari</h3>
<ul>
<li>Makanan terlalu pedas</li>
<li>Makanan tinggi gula</li>
<li>Gorengan berlebihan</li>
<li>Minuman berkafein tinggi</li>
<li>Makanan terlalu asin</li>
</ul>

<blockquote>"Sahur adalah makanan yang penuh berkah, maka janganlah kalian meninggalkannya." (HR. Ahmad)</blockquote>';
    }

    private function getContent12()
    {
        return '<h2>Kisah Sahabat Nabi dalam Menyambut Ramadhan</h2>
<p>Para sahabat Rasulullah SAW memiliki semangat luar biasa dalam menyambut dan menjalani ibadah di bulan Ramadhan. Mari kita belajar dari mereka.</p>

<h3>1. Abu Hurairah - Rajin Beribadah Malam</h3>
<p>Abu Hurairah membagi malamnya menjadi tiga bagian: sepertiga untuk shalat, sepertiga untuk tidur, dan sepertiga untuk mengajarkan ilmu. Di bulan Ramadhan, beliau lebih banyak menghabiskan waktu untuk beribadah.</p>

<h3>2. Utsman bin Affan - Dermawan Ramadhan</h3>
<p>Utsman bin Affan dikenal sangat dermawan, terutama di bulan Ramadhan. Beliau pernah membeli sumur Raumah untuk kaum muslimin dan membebaskan budak dalam jumlah banyak.</p>

<h3>3. Ali bin Abi Thalib - Khusyuk dalam Tilawah</h3>
<p>Ali bin Abi Thalib sangat mencintai Al-Quran. Beliau mengkhatamkan Al-Quran berkali-kali dalam sebulan Ramadhan sambil merenungkan maknanya.</p>

<h3>4. Aisyah - Rajin Iktikaf</h3>
<p>Aisyah ra. menceritakan bahwa Rasulullah SAW rutin iktikaf di 10 hari terakhir Ramadhan. Setelah beliau wafat, istri-istri beliau meneruskan tradisi ini.</p>

<h3>Pelajaran yang Bisa Diambil</h3>
<ul>
<li><strong>Semangat beribadah</strong> - Tidak menunda-nunda kebaikan</li>
<li><strong>Dermawan</strong> - Berbagi kepada sesama</li>
<li><strong>Mencintai Al-Quran</strong> - Menjadikan Al-Quran sebagai pedoman</li>
<li><strong>Iktikaf</strong> - Menyendiri untuk mendekatkan diri kepada Allah</li>
<li><strong>Ikhlas</strong> - Semua dilakukan hanya karena Allah</li>
</ul>

<blockquote>"Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia lainnya." (HR. Ahmad)</blockquote>

<p>Mari kita teladani semangat para sahabat dalam beribadah di bulan Ramadhan ini. Jadikan Ramadhan sebagai momentum untuk meningkatkan kualitas ibadah kita.</p>';
    }
}
