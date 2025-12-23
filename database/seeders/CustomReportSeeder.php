<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomReport;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class CustomReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for created_by
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        // Get events for event_id
        $events = Event::all();

        $this->command->info('Seeding Custom Reports...');

        // 1. Financial Report - Laporan Keuangan Event
        CustomReport::create([
            'report_code' => $this->generateReportCode(1),
            'event_id' => $events->isNotEmpty() ? $events->random()->id : null,
            'title' => 'Laporan Keuangan Event Q4 2024',
            'description' => 'Analisis pendapatan dan pengeluaran event selama Q4 2024, mencakup semua transaksi dan biaya operasional.',
            'report_type' => 'financial',
            'data_sources' => ['payments', 'expenses', 'budgets', 'registrations'],
            'filters' => [
                ['field' => 'created_at', 'operator' => '>=', 'value' => '2024-10-01'],
                ['field' => 'created_at', 'operator' => '<=', 'value' => '2024-12-31'],
                ['field' => 'status', 'operator' => '=', 'value' => 'completed'],
            ],
            'metrics' => [
                ['name' => 'Total Pendapatan', 'aggregation' => 'sum', 'field' => 'amount'],
                ['name' => 'Total Pengeluaran', 'aggregation' => 'sum', 'field' => 'expense_amount'],
                ['name' => 'Jumlah Transaksi', 'aggregation' => 'count', 'field' => 'id'],
                ['name' => 'Rata-rata Transaksi', 'aggregation' => 'avg', 'field' => 'amount'],
            ],
            'dimensions' => [
                ['field' => 'payment_method', 'label' => 'Metode Pembayaran'],
                ['field' => 'event_category', 'label' => 'Kategori Event'],
            ],
            'chart_config' => [
                'type' => 'bar',
                'color' => '#10B981',
            ],
            'period_start' => Carbon::parse('2024-10-01'),
            'period_end' => Carbon::parse('2024-12-31'),
            'report_data' => [
                'total_revenue' => 75500000,
                'total_expenses' => 45200000,
                'net_profit' => 30300000,
                'transaction_count' => 342,
                'average_transaction' => 220760,
            ],
            'last_generated_at' => Carbon::now()->subHours(2),
            'is_scheduled' => true,
            'schedule_frequency' => 'monthly',
            'schedule_config' => ['day' => 1, 'time' => '08:00'],
            'visibility' => 'team',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(50, 200),
            'export_count' => rand(10, 50),
        ]);

        // 2. Performance Report - Kinerja Tim
        CustomReport::create([
            'report_code' => $this->generateReportCode(2),
            'title' => 'Kinerja Tim Panitia Ramadhan 2024',
            'description' => 'Evaluasi kinerja tim berdasarkan penyelesaian tugas, kehadiran, dan kontribusi dalam berbagai event.',
            'report_type' => 'performance',
            'data_sources' => ['tasks', 'users', 'events'],
            'filters' => [
                ['field' => 'task_status', 'operator' => '=', 'value' => 'completed'],
                ['field' => 'year', 'operator' => '=', 'value' => '2024'],
            ],
            'metrics' => [
                ['name' => 'Total Tugas Selesai', 'aggregation' => 'count', 'field' => 'id'],
                ['name' => 'Rata-rata Waktu Penyelesaian', 'aggregation' => 'avg', 'field' => 'completion_time'],
                ['name' => 'Tingkat Keberhasilan', 'aggregation' => 'avg', 'field' => 'success_rate'],
            ],
            'dimensions' => [
                ['field' => 'user_name', 'label' => 'Nama Anggota'],
                ['field' => 'department', 'label' => 'Departemen'],
            ],
            'chart_config' => [
                'type' => 'line',
                'color' => '#3B82F6',
            ],
            'period_start' => Carbon::parse('2024-01-01'),
            'period_end' => Carbon::parse('2024-12-31'),
            'report_data' => [
                'total_tasks' => 856,
                'completed_tasks' => 789,
                'completion_rate' => 92.2,
                'average_completion_days' => 3.4,
            ],
            'last_generated_at' => Carbon::now()->subDay(),
            'is_scheduled' => true,
            'schedule_frequency' => 'weekly',
            'schedule_config' => ['day' => 'Monday', 'time' => '09:00'],
            'visibility' => 'team',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(80, 150),
            'export_count' => rand(15, 40),
        ]);

        // 3. Event Report - Analisis Event
        CustomReport::create([
            'report_code' => $this->generateReportCode(3),
            'event_id' => $events->isNotEmpty() ? $events->random()->id : null,
            'title' => 'Analisis Kehadiran Event Ramadhan',
            'description' => 'Laporan komprehensif tentang tingkat kehadiran, kepuasan peserta, dan feedback untuk improvement event mendatang.',
            'report_type' => 'event',
            'data_sources' => ['events', 'registrations', 'feedback'],
            'filters' => [
                ['field' => 'event_status', 'operator' => '=', 'value' => 'completed'],
                ['field' => 'registration_status', 'operator' => '=', 'value' => 'confirmed'],
            ],
            'metrics' => [
                ['name' => 'Total Peserta Terdaftar', 'aggregation' => 'count', 'field' => 'registration_id'],
                ['name' => 'Total Kehadiran', 'aggregation' => 'count', 'field' => 'attendance_id'],
                ['name' => 'Tingkat Kehadiran', 'aggregation' => 'avg', 'field' => 'attendance_rate'],
                ['name' => 'Rating Kepuasan', 'aggregation' => 'avg', 'field' => 'satisfaction_rating'],
            ],
            'dimensions' => [
                ['field' => 'event_title', 'label' => 'Nama Event'],
                ['field' => 'event_category', 'label' => 'Kategori'],
            ],
            'chart_config' => [
                'type' => 'pie',
                'color' => '#8B5CF6',
            ],
            'period_start' => Carbon::parse('2024-03-01'),
            'period_end' => Carbon::parse('2024-04-30'),
            'report_data' => [
                'total_events' => 12,
                'total_registrations' => 2456,
                'total_attendees' => 2103,
                'attendance_rate' => 85.6,
                'average_satisfaction' => 4.3,
            ],
            'last_generated_at' => Carbon::now()->subHours(5),
            'is_scheduled' => false,
            'visibility' => 'public',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(100, 300),
            'export_count' => rand(20, 60),
        ]);

        // 4. Registration Report - Pendaftaran Event
        CustomReport::create([
            'report_code' => $this->generateReportCode(4),
            'title' => 'Tren Pendaftaran Event Bulanan',
            'description' => 'Analisis tren pendaftaran peserta event per bulan untuk melihat pola dan peak periods.',
            'report_type' => 'registration',
            'data_sources' => ['registrations', 'events'],
            'filters' => [
                ['field' => 'registration_status', 'operator' => '!=', 'value' => 'cancelled'],
            ],
            'metrics' => [
                ['name' => 'Total Pendaftaran', 'aggregation' => 'count', 'field' => 'id'],
                ['name' => 'Pendaftaran Confirmed', 'aggregation' => 'count', 'field' => 'confirmed_id'],
                ['name' => 'Tingkat Konversi', 'aggregation' => 'avg', 'field' => 'conversion_rate'],
            ],
            'dimensions' => [
                ['field' => 'month', 'label' => 'Bulan'],
                ['field' => 'event_type', 'label' => 'Tipe Event'],
            ],
            'chart_config' => [
                'type' => 'line',
                'color' => '#F59E0B',
            ],
            'period_start' => Carbon::parse('2024-01-01'),
            'period_end' => Carbon::parse('2024-12-31'),
            'report_data' => [
                'total_registrations' => 5678,
                'confirmed' => 4890,
                'cancelled' => 345,
                'pending' => 443,
                'conversion_rate' => 86.1,
            ],
            'last_generated_at' => Carbon::now()->subHours(12),
            'is_scheduled' => true,
            'schedule_frequency' => 'monthly',
            'schedule_config' => ['day' => 1, 'time' => '06:00'],
            'visibility' => 'team',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(60, 120),
            'export_count' => rand(10, 30),
        ]);

        // 5. Custom Report - Dashboard Overview
        CustomReport::create([
            'report_code' => $this->generateReportCode(5),
            'title' => 'Dashboard Overview Masjid 2024',
            'description' => 'Ringkasan keseluruhan aktivitas masjid termasuk event, keuangan, dan jamaah.',
            'report_type' => 'custom',
            'data_sources' => ['events', 'payments', 'users', 'registrations'],
            'filters' => [
                ['field' => 'year', 'operator' => '=', 'value' => '2024'],
            ],
            'metrics' => [
                ['name' => 'Total Event', 'aggregation' => 'count', 'field' => 'event_id'],
                ['name' => 'Total Pendapatan', 'aggregation' => 'sum', 'field' => 'amount'],
                ['name' => 'Total Jamaah Aktif', 'aggregation' => 'count', 'field' => 'user_id'],
                ['name' => 'Rating Rata-rata', 'aggregation' => 'avg', 'field' => 'rating'],
            ],
            'dimensions' => [
                ['field' => 'month', 'label' => 'Bulan'],
                ['field' => 'category', 'label' => 'Kategori'],
            ],
            'chart_config' => [
                'type' => 'area',
                'color' => '#EC4899',
            ],
            'period_start' => Carbon::parse('2024-01-01'),
            'period_end' => Carbon::parse('2024-12-31'),
            'report_data' => [
                'total_events' => 45,
                'total_revenue' => 125000000,
                'active_users' => 1234,
                'average_rating' => 4.5,
            ],
            'last_generated_at' => Carbon::now()->subHours(1),
            'is_scheduled' => true,
            'schedule_frequency' => 'daily',
            'schedule_config' => ['time' => '05:00'],
            'visibility' => 'public',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(200, 500),
            'export_count' => rand(50, 100),
        ]);

        // 6. Draft Report - Work in Progress
        CustomReport::create([
            'report_code' => $this->generateReportCode(6),
            'title' => 'Analisis Donasi Ramadhan (Draft)',
            'description' => 'Laporan donasi selama bulan Ramadhan - masih dalam pengembangan.',
            'report_type' => 'financial',
            'data_sources' => ['donations', 'payments'],
            'filters' => [
                ['field' => 'type', 'operator' => '=', 'value' => 'donation'],
            ],
            'metrics' => [
                ['name' => 'Total Donasi', 'aggregation' => 'sum', 'field' => 'amount'],
            ],
            'dimensions' => [
                ['field' => 'donation_type', 'label' => 'Jenis Donasi'],
            ],
            'chart_config' => [
                'type' => 'bar',
                'color' => '#22C55E',
            ],
            'period_start' => Carbon::parse('2024-03-01'),
            'period_end' => Carbon::parse('2024-04-30'),
            'is_scheduled' => false,
            'visibility' => 'private',
            'status' => 'draft',
            'created_by' => $users->random()->id,
            'view_count' => rand(5, 20),
            'export_count' => 0,
        ]);

        // 7. Feedback Analysis Report
        CustomReport::create([
            'report_code' => $this->generateReportCode(7),
            'event_id' => $events->isNotEmpty() ? $events->random()->id : null,
            'title' => 'Analisis Feedback Peserta Event',
            'description' => 'Ringkasan dan analisis sentiment dari feedback peserta event untuk peningkatan kualitas.',
            'report_type' => 'custom',
            'data_sources' => ['feedback', 'events', 'registrations'],
            'filters' => [
                ['field' => 'has_comment', 'operator' => '=', 'value' => true],
            ],
            'metrics' => [
                ['name' => 'Total Feedback', 'aggregation' => 'count', 'field' => 'id'],
                ['name' => 'Rating Rata-rata', 'aggregation' => 'avg', 'field' => 'rating'],
                ['name' => 'Rating Tertinggi', 'aggregation' => 'max', 'field' => 'rating'],
                ['name' => 'Rating Terendah', 'aggregation' => 'min', 'field' => 'rating'],
            ],
            'dimensions' => [
                ['field' => 'event_title', 'label' => 'Event'],
                ['field' => 'rating', 'label' => 'Rating'],
            ],
            'chart_config' => [
                'type' => 'bar',
                'color' => '#14B8A6',
            ],
            'period_start' => Carbon::parse('2024-03-01'),
            'period_end' => Carbon::parse('2024-04-30'),
            'report_data' => [
                'total_feedback' => 567,
                'average_rating' => 4.2,
                'five_star' => 234,
                'four_star' => 198,
                'three_star' => 89,
                'two_star' => 32,
                'one_star' => 14,
            ],
            'last_generated_at' => Carbon::now()->subHours(8),
            'is_scheduled' => true,
            'schedule_frequency' => 'weekly',
            'schedule_config' => ['day' => 'Friday', 'time' => '16:00'],
            'visibility' => 'public',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(70, 140),
            'export_count' => rand(12, 35),
        ]);

        // 8. Task Completion Report
        CustomReport::create([
            'report_code' => $this->generateReportCode(8),
            'title' => 'Laporan Penyelesaian Tugas per Departemen',
            'description' => 'Monitoring progres dan penyelesaian tugas berdasarkan departemen untuk track productivity.',
            'report_type' => 'performance',
            'data_sources' => ['tasks', 'users'],
            'filters' => [
                ['field' => 'task_status', 'operator' => 'in', 'value' => 'completed,in_progress'],
            ],
            'metrics' => [
                ['name' => 'Total Tugas', 'aggregation' => 'count', 'field' => 'id'],
                ['name' => 'Tugas Selesai', 'aggregation' => 'count', 'field' => 'completed_id'],
                ['name' => 'Tingkat Penyelesaian', 'aggregation' => 'avg', 'field' => 'completion_rate'],
            ],
            'dimensions' => [
                ['field' => 'department', 'label' => 'Departemen'],
                ['field' => 'priority', 'label' => 'Prioritas'],
            ],
            'chart_config' => [
                'type' => 'bar',
                'color' => '#6366F1',
            ],
            'period_start' => Carbon::now()->subMonth(),
            'period_end' => Carbon::now(),
            'report_data' => [
                'total_tasks' => 234,
                'completed' => 189,
                'in_progress' => 35,
                'not_started' => 10,
                'completion_rate' => 80.8,
            ],
            'last_generated_at' => Carbon::now()->subHours(3),
            'is_scheduled' => true,
            'schedule_frequency' => 'daily',
            'schedule_config' => ['time' => '18:00'],
            'visibility' => 'team',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(40, 90),
            'export_count' => rand(8, 25),
        ]);

        // 9. Monthly Financial Summary
        CustomReport::create([
            'report_code' => $this->generateReportCode(9),
            'title' => 'Ringkasan Keuangan Bulanan Desember 2024',
            'description' => 'Summary lengkap transaksi keuangan bulan Desember termasuk income, expense, dan balance.',
            'report_type' => 'financial',
            'data_sources' => ['payments', 'expenses', 'budgets'],
            'filters' => [
                ['field' => 'month', 'operator' => '=', 'value' => '12'],
                ['field' => 'year', 'operator' => '=', 'value' => '2024'],
            ],
            'metrics' => [
                ['name' => 'Total Pemasukan', 'aggregation' => 'sum', 'field' => 'income'],
                ['name' => 'Total Pengeluaran', 'aggregation' => 'sum', 'field' => 'expense'],
                ['name' => 'Saldo Akhir', 'aggregation' => 'sum', 'field' => 'balance'],
            ],
            'dimensions' => [
                ['field' => 'category', 'label' => 'Kategori'],
                ['field' => 'week', 'label' => 'Minggu'],
            ],
            'chart_config' => [
                'type' => 'line',
                'color' => '#10B981',
            ],
            'period_start' => Carbon::parse('2024-12-01'),
            'period_end' => Carbon::parse('2024-12-31'),
            'report_data' => [
                'total_income' => 28500000,
                'total_expense' => 19200000,
                'net_balance' => 9300000,
                'transaction_count' => 156,
            ],
            'last_generated_at' => Carbon::now()->subMinutes(30),
            'is_scheduled' => true,
            'schedule_frequency' => 'monthly',
            'schedule_config' => ['day' => 1, 'time' => '07:00'],
            'visibility' => 'team',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(90, 180),
            'export_count' => rand(18, 45),
        ]);

        // 10. Quarterly Event Summary
        CustomReport::create([
            'report_code' => $this->generateReportCode(10),
            'title' => 'Ringkasan Event Triwulan IV 2024',
            'description' => 'Overview komprehensif semua event yang dilaksanakan pada Q4 2024.',
            'report_type' => 'event',
            'data_sources' => ['events', 'registrations', 'feedback', 'payments'],
            'filters' => [
                ['field' => 'quarter', 'operator' => '=', 'value' => '4'],
                ['field' => 'year', 'operator' => '=', 'value' => '2024'],
            ],
            'metrics' => [
                ['name' => 'Total Event', 'aggregation' => 'count', 'field' => 'event_id'],
                ['name' => 'Total Peserta', 'aggregation' => 'sum', 'field' => 'participant_count'],
                ['name' => 'Total Pendapatan', 'aggregation' => 'sum', 'field' => 'revenue'],
                ['name' => 'Rating Rata-rata', 'aggregation' => 'avg', 'field' => 'rating'],
            ],
            'dimensions' => [
                ['field' => 'event_category', 'label' => 'Kategori Event'],
                ['field' => 'month', 'label' => 'Bulan'],
            ],
            'chart_config' => [
                'type' => 'bar',
                'color' => '#8B5CF6',
            ],
            'period_start' => Carbon::parse('2024-10-01'),
            'period_end' => Carbon::parse('2024-12-31'),
            'report_data' => [
                'total_events' => 18,
                'total_participants' => 3456,
                'total_revenue' => 45600000,
                'average_rating' => 4.4,
                'successful_events' => 16,
            ],
            'last_generated_at' => Carbon::now()->subHours(6),
            'is_scheduled' => true,
            'schedule_frequency' => 'quarterly',
            'schedule_config' => ['month' => 1, 'day' => 5, 'time' => '09:00'],
            'visibility' => 'public',
            'status' => 'published',
            'created_by' => $users->random()->id,
            'view_count' => rand(120, 250),
            'export_count' => rand(25, 55),
        ]);

        $this->command->info('✅ Successfully seeded 10 Custom Reports');
        $this->command->newLine();
        $this->command->table(
            ['Status', 'Count'],
            [
                ['Published', CustomReport::where('status', 'published')->count()],
                ['Saved', CustomReport::where('status', 'saved')->count()],
                ['Draft', CustomReport::where('status', 'draft')->count()],
                ['Scheduled', CustomReport::where('is_scheduled', true)->count()],
            ]
        );
    }

    /**
     * Generate unique report code for seeder
     */
    private function generateReportCode(int $number): string
    {
        $year = now()->year;
        return 'CR-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
