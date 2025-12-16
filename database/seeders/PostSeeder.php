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
    public function run(): void
    {
        $categories = Category::where('type', Category::TYPE_POST)->get();
        $author = User::where('role', 'admin')->first();

        if (!$author || $categories->isEmpty()) {
            $this->command->warn('Please run UserSeeder and CategorySeeder first!');
            return;
        }

        $tags = $this->createTags();

        $posts = [
            // Berita
            [
                'title' => 'Marhaban Ya Ramadhan 1436 H',
                'category' => 'Berita',
                'excerpt' => 'Menyambut bulan suci Ramadhan 1436 H dengan berbagai kegiatan ibadah dan sosial.',
                'content' => $this->getMarhabanContent(),
                'tags' => ['Ramadhan', 'Ibadah', 'Masjid'],
                'is_featured' => true,
                'reading_time' => 5,
                'views_count' => 2500,
            ],
            [
                'title' => 'Jadwal Lengkap Kegiatan Ramadhan 1436 H',
                'category' => 'Pengumuman',
                'excerpt' => 'Informasi lengkap jadwal kegiatan Ramadhan di Masjid Agung Al Azhar.',
                'content' => $this->getJadwalContent(),
                'tags' => ['Ramadhan', 'Jadwal', 'Pengumuman'],
                'is_featured' => true,
                'reading_time' => 10,
                'views_count' => 3200,
            ],

            // Artikel Islami - Materi dari PDF
            [
                'title' => 'Pengertian dan Hukum Puasa Ramadhan',
                'category' => 'Artikel Islami',
                'excerpt' => 'Memahami makna, syarat, dan kewajiban puasa Ramadhan menurut Al-Quran dan Hadits.',
                'content' => $this->getPuasaContent(),
                'tags' => ['Puasa', 'Ramadhan', 'Fiqih'],
                'is_featured' => true,
                'reading_time' => 12,
                'views_count' => 1850,
            ],
            [
                'title' => 'Tata Cara Shalat Tarawih yang Benar',
                'category' => 'Artikel Islami',
                'excerpt' => 'Panduan lengkap melaksanakan shalat tarawih sesuai sunnah Rasulullah SAW.',
                'content' => $this->getTarawihContent(),
                'tags' => ['Tarawih', 'Shalat', 'Ramadhan'],
                'is_featured' => false,
                'reading_time' => 8,
                'views_count' => 1420,
            ],
            [
                'title' => 'I\'tikaf: Pengertian, Syarat, dan Keutamaannya',
                'category' => 'Artikel Islami',
                'excerpt' => 'Mengenal lebih dalam tentang ibadah i\'tikaf di 10 hari terakhir Ramadhan.',
                'content' => $this->getItikafContent(),
                'tags' => ['Itikaf', 'Ramadhan', 'Ibadah'],
                'is_featured' => false,
                'reading_time' => 10,
                'views_count' => 980,
            ],
            [
                'title' => 'Panduan Lengkap Zakat Fitrah dan Zakat Maal',
                'category' => 'Artikel Islami',
                'excerpt' => 'Ketentuan, perhitungan, dan waktu pembayaran zakat fitrah serta zakat maal.',
                'content' => $this->getZakatContent(),
                'tags' => ['Zakat', 'Zakat Fitrah', 'Ramadhan'],
                'is_featured' => true,
                'reading_time' => 15,
                'views_count' => 2100,
            ],

            // Tips
            [
                'title' => '10 Amalan Sunnah di Bulan Ramadhan',
                'category' => 'Tips',
                'excerpt' => 'Amalan-amalan sunnah yang dianjurkan untuk menambah pahala di bulan Ramadhan.',
                'content' => $this->getAmalanSunnahContent(),
                'tags' => ['Amalan', 'Sunnah', 'Ramadhan'],
                'is_featured' => false,
                'reading_time' => 7,
                'views_count' => 1650,
            ],
            [
                'title' => 'Tips Menjaga Kesehatan Saat Berpuasa',
                'category' => 'Tips',
                'excerpt' => 'Panduan praktis menjaga kesehatan dan stamina selama menjalankan ibadah puasa.',
                'content' => $this->getKesehatanContent(),
                'tags' => ['Kesehatan', 'Puasa', 'Tips'],
                'is_featured' => false,
                'reading_time' => 6,
                'views_count' => 1120,
            ],
            [
                'title' => 'Hikmah dan Manfaat Puasa Ramadhan',
                'category' => 'Tips',
                'excerpt' => 'Memahami hikmah spiritual, sosial, dan kesehatan dari ibadah puasa.',
                'content' => $this->getHikmahContent(),
                'tags' => ['Hikmah', 'Puasa', 'Ramadhan'],
                'is_featured' => false,
                'reading_time' => 8,
                'views_count' => 890,
            ],

            // Kisah Inspiratif
            [
                'title' => 'Semangat Para Sahabat dalam Menyambut Ramadhan',
                'category' => 'Kisah Inspiratif',
                'excerpt' => 'Belajar dari keteladanan para sahabat Nabi dalam beribadah di bulan Ramadhan.',
                'content' => $this->getSahabatContent(),
                'tags' => ['Sahabat', 'Kisah', 'Ramadhan'],
                'is_featured' => true,
                'reading_time' => 10,
                'views_count' => 1450,
            ],
        ];

        $createdCount = 0;
        foreach ($posts as $postData) {
            $category = $categories->firstWhere('name', $postData['category']);

            if (!$category) {
                $this->command->warn("Category '{$postData['category']}' not found");
                continue;
            }

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
            ]);

            $postTags = [];
            foreach ($postData['tags'] as $tagName) {
                $tag = $tags->firstWhere('name', $tagName);
                if ($tag) $postTags[] = $tag->id;
            }
            $post->tags()->attach($postTags);

            $createdCount++;
        }

        $this->command->info("✅ Created {$createdCount} posts!");
    }

    private function createTags()
    {
        $tagNames = [
            'Ramadhan',
            'Puasa',
            'Shalat',
            'Tarawih',
            'Zakat',
            'Zakat Fitrah',
            'Ibadah',
            'Al-Quran',
            'Tilawah',
            'Dzikir',
            'Doa',
            'Itikaf',
            'Fiqih',
            'Sunnah',
            'Hadits',
            'Sahabat',
            'Kisah',
            'Hikmah',
            'Kesehatan',
            'Tips',
            'Amalan',
            'Kajian',
            'Masjid',
            'Pengumuman',
            'Jadwal',
            'Lailatul Qadr',
            'Idul Fitri',
            'Takbir'
        ];

        $tags = collect();
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName), 'color' => $this->randomColor()]
            );
            $tags->push($tag);
        }
        return $tags;
    }

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
            '#14B8A6'
        ];
        return $colors[array_rand($colors)];
    }

    // Content methods berdasarkan PDF
    private function getMarhabanContent()
    {
        return '<h2>Marhaban Ya Ramadhan 1436 H</h2>
<p>Alhamdulillah, bulan suci Ramadhan 1436 H telah tiba. Bulan yang penuh berkah, bulan yang di dalamnya kita dapat menempa diri menjadi insan muttaqin.</p>

<h3>Keistimewaan Ramadhan</h3>
<p>Ramadhan adalah bulan dimana Al-Quran diturunkan sebagai petunjuk bagi manusia. Bulan dimana pintu-pintu surga dibukakan, pintu-pintu neraka ditutup, dan setan-setan dibelenggu.</p>

<h3>Kegiatan Ramadhan di Masjid Agung Al Azhar</h3>
<ul>
<li>Kuliah Subuh setiap hari setelah shalat Subuh</li>
<li>Tadarus Al-Quran sebelum Dzuhur dan Ashar</li>
<li>Taushiyah sebelum berbuka puasa</li>
<li>Shalat Tarawih berjamaah</li>
<li>Buka puasa bersama harian</li>
<li>I\'tikaf 10 hari terakhir</li>
<li>Kajian khusus dan dialog interaktif</li>
</ul>

<blockquote>"Barangsiapa yang berpuasa Ramadhan karena iman dan mengharap pahala dari Allah, maka diampuni dosa-dosanya yang telah lalu." (HR. Bukhari Muslim)</blockquote>';
    }

    private function getJadwalContent()
    {
        return '<h2>Jadwal Kegiatan Ramadhan 1436 H</h2>
<p>Masjid Agung Al Azhar menyelenggarakan berbagai kegiatan selama bulan Ramadhan untuk meningkatkan ketakwaan dan kebersamaan umat.</p>

<h3>Kegiatan Harian</h3>
<ul>
<li><strong>Kuliah Subuh:</strong> Setiap hari ba\'da Subuh</li>
<li><strong>Tadarus Dzuhur:</strong> Pukul 11.00-12.00 WIB</li>
<li><strong>Tadarus Ashar:</strong> Pukul 14.30-15.30 WIB</li>
<li><strong>Taushiyah Sebelum Buka:</strong> Pukul 17.15-18.00 WIB</li>
<li><strong>Shalat Tarawih:</strong> Ba\'da Isya (8 rakaat + 3 witir)</li>
<li><strong>Tadarus Ba\'da Tarawih:</strong> Pukul 21.00-21.30 WIB</li>
</ul>

<h3>Kegiatan Khusus</h3>
<ul>
<li>Buka Puasa Bersama 2000 Anak Yatim (11 Juli 2015)</li>
<li>Nuzulul Quran (3 Juli 2015)</li>
<li>MTQ dan MHQ (27-28 Juni 2015)</li>
<li>I\'tikaf 10 Hari Terakhir (7-15 Juli 2015)</li>
<li>Kajian Khusus setiap Jumat ba\'da Jumat</li>
</ul>

<h3>Pelayanan</h3>
<ul>
<li>Pengobatan gratis untuk jamaah (15.00-22.00 WIB)</li>
<li>Bazaar Ramadhan</li>
<li>Penerimaan Zakat, Infaq, dan Sedekah</li>
</ul>';
    }

    private function getPuasaContent()
    {
        return '<h2>Pengertian Puasa</h2>
<p>Puasa menurut bahasa berarti "menahan diri". Secara syariat, puasa berarti menahan diri dari segala yang membatalkan puasa, mulai terbit fajar hingga terbenam matahari.</p>

<blockquote>يَا أَيُّهَا الَّذِينَ آمَنُوا كُتِبَ عَلَيْكُمُ الصِّيَامُ كَمَا كُتِبَ عَلَى الَّذِينَ مِن قَبْلِكُمْ لَعَلَّكُمْ تَتَّقُونَ

"Wahai orang-orang yang beriman, diwajibkan atas kamu berpuasa sebagaimana diwajibkan atas orang sebelum kamu agar kamu bertakwa." (QS. Al-Baqarah: 183)</blockquote>

<h3>Syarat Wajib Puasa</h3>
<ol>
<li><strong>Berakal</strong> - Tidak wajib bagi orang gila</li>
<li><strong>Islam</strong> - Tidak wajib bagi non-Muslim</li>
<li><strong>Mampu</strong> - Tidak wajib bagi yang sakit berat</li>
<li><strong>Baligh</strong> - Tidak wajib bagi anak kecil</li>
</ol>

<h3>Rukun Puasa</h3>
<ol>
<li><strong>Niat</strong> pada malam hari sebelum fajar</li>
<li><strong>Menahan diri</strong> dari hal yang membatalkan puasa</li>
</ol>

<p>"Sesungguhnya setiap amal itu tergantung niatnya." (HR. Bukhari)</p>

<h3>Hal yang Membatalkan Puasa</h3>
<ol>
<li>Makan dan minum dengan sengaja</li>
<li>Berhubungan suami istri</li>
<li>Mengeluarkan mani dengan sengaja</li>
<li>Keluar darah haid atau nifas</li>
<li>Muntah dengan sengaja</li>
<li>Murtad (keluar dari Islam)</li>
</ol>';
    }

    private function getTarawihContent()
    {
        return '<h2>Shalat Tarawih</h2>
<p>Shalat tarawih adalah shalat sunnah muakkadah yang dikerjakan pada malam bulan Ramadhan, setelah shalat Isya hingga terbit fajar.</p>

<blockquote>"Barangsiapa mendirikan shalat pada malam Ramadhan karena iman dan mengharap pahala, maka diampuni dosa-dosanya yang telah lalu." (HR. Bukhari Muslim)</blockquote>

<h3>Jumlah Rakaat</h3>
<p>Dari Aisyah RA, Rasulullah SAW melaksanakan shalat malam (termasuk tarawih) sebanyak 11 rakaat, baik di bulan Ramadhan maupun di luar Ramadhan.</p>

<p>Masjid Agung Al Azhar melaksanakan tarawih 8 rakaat + 3 rakaat witir, dengan setiap 2 rakaat satu salam.</p>

<h3>Tata Cara</h3>
<ol>
<li>Dikerjakan berjamaah atau sendiri</li>
<li>Setiap 2 atau 4 rakaat satu salam</li>
<li>Dilanjutkan dengan shalat witir 3 rakaat</li>
<li>Diutamakan membaca Al-Quran dengan tartil</li>
</ol>

<h3>Dzikir Pembuka Tarawih</h3>
<p>لَا إِلٰهَ إِلَّا اللهُ وَحْدَهُ لَا شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ يُحْيِيْ وَيُمِيْتُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرٌ، وَلَا حَوْلَ وَلَا قُوَّةَ إِلَّا بِاللهِ الْعَلِيِّ الْعَظِيْمِ</p>

<h3>Doa Setelah Rakaat Ke-4 dan Ke-8</h3>
<p>اللَّهُمَّ إِنَّكَ عَفُوٌّ تُحِبُّ الْعَفْوَ فَاعْفُ عَنَّا

"Ya Allah, sesungguhnya Engkau Maha Pemaaf, Engkau suka memberi maaf, maka maafkanlah kami."</p>';
    }

    private function getItikafContent()
    {
        return '<h2>I\'tikaf: Berdiam di Masjid</h2>
<p>I\'tikaf secara bahasa berarti "berdiam di suatu tempat". Menurut istilah syariat, i\'tikaf adalah berdiam di masjid untuk mendekatkan diri kepada Allah SWT.</p>

<h3>Macam-Macam I\'tikaf</h3>
<ol>
<li><strong>I\'tikaf Sunnah:</strong> Dilakukan secara sukarela tanpa nadzar</li>
<li><strong>I\'tikaf Wajib:</strong> Karena nadzar yang telah diucapkan</li>
</ol>

<h3>Syarat I\'tikaf</h3>
<ul>
<li>Muslim/muslimah</li>
<li>Dewasa (mumayyiz)</li>
<li>Bersih dari hadast</li>
<li>Dilakukan di masjid</li>
<li>Berniat karena Allah</li>
</ul>

<h3>Amalan yang Dianjurkan</h3>
<ul>
<li>Memperbanyak dzikir (tasbih, tahmid, takbir)</li>
<li>Tadabbur Al-Quran</li>
<li>Mempelajari hadits dan tafsir</li>
<li>Shalat sunnah</li>
<li>Doa dan istighfar</li>
</ul>

<h3>Hal yang Membatalkan</h3>
<ul>
<li>Keluar masjid tanpa keperluan</li>
<li>Haid atau nifas</li>
<li>Hilang kesadaran</li>
<li>Bermesraan dengan pasangan</li>
<li>Murtad</li>
</ul>

<p>Masjid Agung Al Azhar menyelenggarakan I\'tikaf khusus pada 10 hari terakhir Ramadhan dengan kajian-kajian dari para ulama.</p>';
    }

    private function getZakatContent()
    {
        return '<h2>Zakat Fitrah dan Zakat Maal</h2>

<h3>Zakat Fitrah</h3>
<p>Zakat fitrah adalah zakat yang wajib dikeluarkan setiap muslim menjelang Idul Fitri.</p>

<h4>Syarat Wajib</h4>
<ul>
<li>Beragama Islam</li>
<li>Hidup saat terbenam matahari akhir Ramadhan</li>
<li>Memiliki kelebihan makanan untuk sehari semalam</li>
</ul>

<h4>Besaran</h4>
<p>Satu sha\' (± 2,5 kg atau 3,5 liter) makanan pokok per jiwa. Atau setara uang Rp 40.000 - Rp 50.000 (tahun 2015).</p>

<h4>Waktu Pembayaran</h4>
<ol>
<li><strong>Paling utama:</strong> Setelah Subuh sampai sebelum shalat Id</li>
<li><strong>Boleh:</strong> Sejak awal Ramadhan</li>
<li><strong>Makruh:</strong> Setelah shalat Idul Fitri</li>
</ol>

<h3>Zakat Maal (Harta)</h3>

<h4>Jenis Harta yang Dizakati</h4>
<ol>
<li><strong>Emas dan Perak:</strong> Nishab 85 gram emas, zakat 2,5%</li>
<li><strong>Harta Perdagangan:</strong> Nishab setara 85 gram emas, zakat 2,5%</li>
<li><strong>Tanaman:</strong> Nishab 720 kg beras, zakat 5% atau 10%</li>
<li><strong>Hewan Ternak:</strong> Sesuai ketentuan masing-masing</li>
</ol>

<h4>Penerima Zakat (8 Asnaf)</h4>
<ol>
<li>Fakir - tidak punya harta dan pekerjaan</li>
<li>Miskin - punya pekerjaan tapi tidak mencukupi</li>
<li>Amil - pengurus zakat</li>
<li>Muallaf - yang baru masuk Islam</li>
<li>Riqab - memerdekakan budak</li>
<li>Gharim - yang terlilit hutang</li>
<li>Sabilillah - pejuang di jalan Allah</li>
<li>Ibnu Sabil - musafir yang kehabisan bekal</li>
</ol>

<h3>Hikmah Zakat</h3>
<ul>
<li>Membersihkan harta dan jiwa</li>
<li>Meningkatkan kepedulian sosial</li>
<li>Mengurangi kesenjangan ekonomi</li>
<li>Bentuk syukur kepada Allah</li>
</ul>';
    }

    private function getAmalanSunnahContent()
    {
        return '<h2>10 Amalan Sunnah di Bulan Ramadhan</h2>

<h3>1. Menyegerakan Berbuka</h3>
<p>RasTo run code, enable code execution and file creation in Settings > Capabilities.Continue08.10ulullah SAW bersabda: "Manusia senantiasa dalam kebaikan selama menyegerakan berbuka puasa."</p><h3>2. Berbuka dengan Kurma</h3>
<p>Nabi SAW berbuka dengan ruthab (kurma basah), jika tidak ada dengan kurma, jika tidak ada dengan air putih.</p><h3>3. Memperlambat Sahur</h3>
<p>"Hendaklah kalian makan sahur, karena di dalam sahur terdapat keberkahan." (HR. Bukhari Muslim)</p><h3>4. Memberi Makan Orang yang Berpuasa</h3>
<p>"Barangsiapa memberi makan orang yang berpuasa, maka baginya pahala seperti orang yang berpuasa tanpa mengurangi pahalanya sedikitpun." (HR. Tirmidzi)</p><h3>5. Memperbanyak Sedekah</h3>
<p>Rasulullah adalah orang paling dermawan, dan beliau paling dermawan di bulan Ramadhan.</p><h3>6. Membaca Al-Quran</h3>
<p>Jibril AS memeriksa hafalan Al-Quran Nabi SAW setiap malam di bulan Ramadhan.</p><h3>7. I\'tikaf di 10 Hari Terakhir</h3>
<p>Nabi SAW rutin i\'tikaf di 10 hari terakhir Ramadhan untuk mencari Lailatul Qadr.</p><h3>8. Shalat Tarawih</h3>
<p>"Barangsiapa qiyamul lail di Ramadhan karena iman dan ihtisab, diampuni dosanya yang lalu." (HR. Bukhari Muslim)</p><h3>9. Menjauhi Perbuatan Tercela</h3>
<p>"Jika salah seorang berpuasa, jangan berkata kotor dan berbuat jahil. Jika ada yang mencela, katakan: Saya sedang puasa." (HR. Bukhari Muslim)</p><h3>10. Memperbanyak Doa</h3>
<p>Khususnya doa Lailatul Qadr: "Allahumma innaka \'afuwwun tuhibbul \'afwa fa\'fu \'anni"</p>';
    }
    private function getKesehatanContent()
    {
        return '<h2>Tips Menjaga Kesehatan Saat Berpuasa</h2><h3>1. Sahur yang Tepat</h3>
<ul>
<li>Konsumsi karbohidrat kompleks (nasi merah, oatmeal)</li>
<li>Protein cukup (telur, ikan, tempe, tahu)</li>
<li>Sayur dan buah</li>
<li>Minum air putih minimal 2 gelas</li>
</ul><h3>2. Berbuka dengan Bijak</h3>
<ul>
<li>Mulai dengan yang manis (kurma, kolak)</li>
<li>Jangan langsung makan berat</li>
<li>Hindari gorengan berlebihan</li>
<li>Kunyah makanan perlahan</li>
</ul><h3>3. Hidrasi Cukup</h3>
<p>Terapkan pola 2-4-2:</p>
<ul>
<li>2 gelas saat berbuka</li>
<li>4 gelas antara berbuka dan sahur</li>
<li>2 gelas saat sahur</li>
</ul><h3>4. Tetap Aktif Bergerak</h3>
<p>Lakukan olahraga ringan seperti jalan kaki atau stretching 30 menit sebelum berbuka.</p><h3>5. Istirahat Cukup</h3>
<p>Tidur minimal 6-7 jam sehari. Manfaatkan tidur siang jika memungkinkan.</p><h3>6. Kelola Stress</h3>
<p>Perbanyak dzikir, doa, dan ibadah untuk ketenangan jiwa.</p><h3>Menu Sahur Sehat</h3>
<h4>Prinsip Menu</h4>
<ul>
<li>Karbohidrat kompleks</li>
<li>Protein tinggi</li>
<li>Serat dari sayur dan buah</li>
<li>Lemak sehat</li>
<li>Cairan cukup</li>
</ul><h4>Contoh Menu</h4>
<ul>
<li>Nasi merah + telur + tempe + sayur bening + buah</li>
<li>Oatmeal + susu + pisang + kacang almond + madu</li>
<li>Roti gandum isi telur dan sayur + jus buah + yogurt</li>
</ul><h3>Yang Harus Dihindari</h3>
<ul>
<li>Makanan terlalu pedas</li>
<li>Makanan tinggi gula</li>
<li>Gorengan berlebihan</li>
<li>Minuman berkafein tinggi</li>
<li>Makanan terlalu asin</li>
</ul>';
    }
    private function getHikmahContent()
    {
        return '<h2>Hikmah dan Manfaat Puasa Ramadhan</h2><h3>1. Hikmah Spiritual</h3>
<p>Puasa melatih jiwa untuk mendekatkan diri kepada Allah SWT. Dengan menahan hawa nafsu, kita belajar ketaatan dan ketundukan kepada perintah-Nya.</p><blockquote>"Diwajibkan atas kamu berpuasa agar kamu bertakwa." (QS. Al-Baqarah: 183)</blockquote><h3>2. Hikmah Sosial</h3>
<p>Puasa mengajarkan empati terhadap yang kekurangan. Dengan merasakan lapar dan dahaga, kita menjadi lebih peka terhadap penderitaan orang lain.</p><p>Ini mendorong untuk:</p>
<ul>
<li>Berbagi dengan sesama</li>
<li>Membantu fakir miskin</li>
<li>Meningkatkan kepedulian sosial</li>
<li>Mempererat persaudaraan</li>
</ul><h3>3. Hikmah Moral dan Akhlak</h3>
<p>Rasulullah SAW bersabda: "Barangsiapa tidak meninggalkan perkataan dusta dan perbuatan dusta, maka Allah tidak memerlukan dia meninggalkan makan dan minumnya." (HR. Bukhari)</p><p>Puasa mengajarkan:</p>
<ul>
<li>Mengendalikan emosi</li>
<li>Menjaga lisan</li>
<li>Meninggalkan perbuatan sia-sia</li>
<li>Meningkatkan kesabaran</li>
<li>Menjauh kan dari sifat buruk</li>
</ul><h3>4. Hikmah Kesehatan</h3>
<p>Dari segi medis, puasa terbukti memiliki banyak manfaat:</p>
<ul>
<li>Detoksifikasi tubuh</li>
<li>Regenerasi sel</li>
<li>Meningkatkan metabolisme</li>
<li>Menyehatkan pencernaan</li>
<li>Menurunkan kolesterol</li>
<li>Meningkatkan kekebalan tubuh</li>
</ul><h3>5. Hikmah Ekonomi</h3>
<ul>
<li>Melatih hidup sederhana</li>
<li>Mengatur keuangan</li>
<li>Mengurangi pemborosan</li>
<li>Meningkatkan kepedulian ekonomi umat</li>
</ul><h3>Kesimpulan</h3>
<p>Puasa bukan sekadar menahan lapar dan dahaga, tetapi merupakan latihan komprehensif untuk membentuk pribadi muslim yang bertakwa, berakhlak mulia, peduli sosial, dan sehat jasmani rohani.</p>';
    }
    private function getSahabatContent()
    {
        return '<h2>Semangat Para Sahabat dalam Menyambut Ramadhan</h2><h3>1. Abu Hurairah - Rajin Beribadah Malam</h3>
<p>Abu Hurairah membagi malamnya menjadi tiga bagian: sepertiga untuk shalat, sepertiga untuk tidur, dan sepertiga untuk mengajar ilmu. Di bulan Ramadhan, beliau lebih banyak menghabiskan waktu untuk beribadah dan menghafal hadits dari Rasulullah SAW.</p><h3>2. Utsman bin Affan - Dermawan Ramadhan</h3>
<p>Utsman bin Affan dikenal sangat dermawan, terutama di bulan Ramadhan. Beliau pernah:</p>
<ul>
<li>Membeli sumur Raumah untuk kaum muslimin</li>
<li>Membiayai pasukan Tabuk</li>
<li>Membebaskan budak dalam jumlah banyak</li>
<li>Menyantuni fakir miskin</li>
</ul><h3>3. Ali bin Abi Thalib - Khusyuk dalam Tilawah</h3>
<p>Ali bin Abi Thalib sangat mencintai Al-Quran. Beliau mengkhatamkan Al-Quran berkali-kali dalam sebulan Ramadhan sambil merenungkan maknanya dan mengamalkan isinya.</p><h3>4. Aisyah - Rajin I\'tikaf</h3>
<p>Aisyah RA menceritakan bahwa Rasulullah SAW rutin i\'tikaf di 10 hari terakhir Ramadhan. Setelah beliau wafat, istri-istri beliau meneruskan tradisi i\'tikaf ini.</p><h3>5. Abdullah bin Umar - Konsisten Beramal</h3>
<p>Ibnu Umar dikenal sangat konsisten dalam beramal. Di bulan Ramadhan, beliau:</p>
<ul>
<li>Tidak pernah meninggalkan shalat berjamaah</li>
<li>Selalu membaca Al-Quran setiap hari</li>
<li>Rajin bersedekah kepada fakir miskin</li>
<li>Memperbanyak doa dan istighfar</li>
</ul><h3>6. Salman Al-Farisi - Persiapan Menyambut Ramadhan</h3>
<p>Salman Al-Farisi meriwayatkan khutbah Nabi SAW yang menyambut Ramadhan dengan penuh antusias. Beliau menyiapkan diri jauh-jauh hari sebelum Ramadhan tiba.</p><h3>Pelajaran yang Dapat Diambil</h3>
<ul>
<li><strong>Semangat beribadah:</strong> Tidak menunda-nunda kebaikan</li>
<li><strong>Dermawan:</strong> Berbagi kepada sesama</li>
<li><strong>Mencintai Al-Quran:</strong> Menjadikan Al-Quran sebagai pedoman</li>
<li><strong>I\'tikaf:</strong> Menyendiri untuk mendekatkan diri kepada Allah</li>
<li><strong>Konsistensi:</strong> Istiqomah dalam beramal</li>
<li><strong>Ikhlas:</strong> Semua dilakukan hanya karena Allah</li>
</ul><blockquote>"Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia lainnya." (HR. Ahmad)</blockquote><p>Mari kita teladani semangat para sahabat dalam beribadah di bulan Ramadhan ini. Jadikan Ramadhan sebagai momentum untuk meningkatkan kualitas ibadah kita.</p>';
    }
}
