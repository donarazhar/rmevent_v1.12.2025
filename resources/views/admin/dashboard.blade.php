{{-- File: resources/views/admin/dashboard.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-semibold">Dashboard</span>
@endsection

@section('content')
    {{-- Alerts Section --}}
    @if (count($alerts) > 0)
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($alerts as $alert)
                    <a href="{{ $alert['link'] }}"
                        class="block p-4 rounded-xl border-l-4 transition-all hover:shadow-md
                            {{ $alert['type'] === 'danger' ? 'bg-red-50 border-red-500' : '' }}
                            {{ $alert['type'] === 'warning' ? 'bg-yellow-50 border-yellow-500' : '' }}
                            {{ $alert['type'] === 'info' ? 'bg-blue-50 border-blue-500' : '' }}
                            {{ $alert['type'] === 'success' ? 'bg-green-50 border-green-500' : '' }}">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $alert['type'] === 'danger' ? 'bg-red-100 text-red-600' : '' }}
                                {{ $alert['type'] === 'warning' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                {{ $alert['type'] === 'info' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $alert['type'] === 'success' ? 'bg-green-100 text-green-600' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if ($alert['icon'] === 'exclamation-triangle')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    @elseif($alert['icon'] === 'clock')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @elseif($alert['icon'] === 'exclamation-circle')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @elseif($alert['icon'] === 'users')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-semibold 
                                    {{ $alert['type'] === 'danger' ? 'text-red-800' : '' }}
                                    {{ $alert['type'] === 'warning' ? 'text-yellow-800' : '' }}
                                    {{ $alert['type'] === 'info' ? 'text-blue-800' : '' }}
                                    {{ $alert['type'] === 'success' ? 'text-green-800' : '' }}">
                                    {{ $alert['title'] }}
                                </p>
                                <p
                                    class="text-xs mt-0.5
                                    {{ $alert['type'] === 'danger' ? 'text-red-600' : '' }}
                                    {{ $alert['type'] === 'warning' ? 'text-yellow-600' : '' }}
                                    {{ $alert['type'] === 'info' ? 'text-blue-600' : '' }}
                                    {{ $alert['type'] === 'success' ? 'text-green-600' : '' }}">
                                    {{ $alert['message'] }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Main Stats Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
        {{-- Events --}}
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Events</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ $stats['upcoming_events'] }} upcoming</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Registrations --}}
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Registrations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_registrations'] }}</p>
                    <p class="text-xs text-green-600 mt-1">+{{ $stats['this_week_registrations'] }} this week</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </div>
        {{-- Users --}}
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-purple-600 mt-1">{{ $stats['jamaah_count'] }} jamaah</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Committee --}}
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Panitia</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $committeeStats['active_members'] }}</p>
                    <p class="text-xs text-indigo-600 mt-1">{{ $committeeStats['active_structures'] }} divisi</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Milestones --}}
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Milestones</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $timelineStats['milestone_completion_rate'] }}%</p>
                    <p class="text-xs text-yellow-600 mt-1">
                        {{ $timelineStats['completed_milestones'] }}/{{ $timelineStats['total_milestones'] }} done</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Rating --}}
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Avg Rating</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['avg_rating'] }}/5</p>
                    <p class="text-xs text-orange-600 mt-1">{{ $stats['total_feedbacks'] }} reviews</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Financial Summary Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Budget --}}
        <div class="bg-gradient-to-br from-[#0053C5] to-[#003280] rounded-xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-blue-100">Total Anggaran Disetujui</p>
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold">Rp {{ number_format($financialSummary['total_budget_approved'], 0, ',', '.') }}
            </p>
            <div class="mt-3 flex items-center gap-2">
                <div class="flex-1 bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2"
                        style="width: {{ min($financialSummary['budget_utilization'], 100) }}%"></div>
                </div>
                <span class="text-sm font-medium">{{ $financialSummary['budget_utilization'] }}%</span>
            </div>
        </div>

        {{-- Total Spent --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500">Total Pengeluaran</p>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp
                {{ number_format($financialSummary['total_spent'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-2">
                <span class="text-red-600 font-medium">Rp
                    {{ number_format($financialSummary['this_month_expenses'], 0, ',', '.') }}</span> bulan ini
            </p>
        </div>

        {{-- Total Income --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500">Total Pemasukan</p>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp
                {{ number_format($financialSummary['total_income'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-2">
                <span class="text-green-600 font-medium">Rp
                    {{ number_format($financialSummary['this_month_income'], 0, ',', '.') }}</span> bulan ini
            </p>
        </div>

        {{-- Cash Flow --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500">Sisa Anggaran</p>
                <div
                    class="w-10 h-10 {{ $financialSummary['budget_remaining'] >= 0 ? 'bg-emerald-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 {{ $financialSummary['budget_remaining'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p
                class="text-2xl font-bold {{ $financialSummary['budget_remaining'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                Rp {{ number_format(abs($financialSummary['budget_remaining']), 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-2">
                {{ $financialSummary['pending_expenses_count'] }} pengajuan pending
            </p>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Registration Trend --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Trend Registrasi (30 Hari Terakhir)</h3>
                <a href="{{ route('admin.registrations.index') }}" class="text-sm text-[#0053C5] hover:underline">Lihat
                    Semua →</a>
            </div>
            <div class="h-64">
                <canvas id="registrationTrendChart"></canvas>
            </div>
        </div>

        {{-- Monthly Financial --}}
        {{-- <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Pemasukan vs Pengeluaran (6 Bulan)</h3>
                <a href="{{ route('admin.financial-reports.summary') }}"
                    class="text-sm text-[#0053C5] hover:underline">Lihat Laporan →</a>
            </div>
            <div class="h-64">
                <canvas id="monthlyFinancialChart"></canvas>
            </div>
        </div> --}}
    </div>

    {{-- Charts Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        {{-- Registration by Status --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Registrasi by Status</h3>
            <div class="h-48">
                <canvas id="registrationStatusChart"></canvas>
            </div>
        </div>

        {{-- Events by Category --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Event by Kategori</h3>
            <div class="h-48">
                <canvas id="eventCategoryChart"></canvas>
            </div>
        </div>

        {{-- Timeline Progress --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Timeline Progress</h3>
            <div class="h-48">
                <canvas id="timelineProgressChart"></canvas>
            </div>
        </div>

        {{-- Expense by Category --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Pengeluaran by Kategori</h3>
            <div class="h-48">
                <canvas id="expenseCategoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Performance & Sponsorship Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Performance Overview --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Kinerja Panitia</h3>
                <a href="{{ route('admin.evaluations.index') }}" class="text-sm text-[#0053C5] hover:underline">Detail
                    →</a>
            </div>
            <div class="space-y-4">
                {{-- Overall Score --}}
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                    <p class="text-4xl font-bold text-[#0053C5]">{{ $performanceStats['avg_overall_score'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Rata-rata Skor</p>
                    <div class="flex justify-center mt-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($performanceStats['avg_overall_score']) ? 'text-yellow-400' : 'text-gray-300' }}"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                        @endfor
                    </div>
                </div>

                {{-- Score Breakdown --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Task Completion</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full"
                                    style="width: {{ ($performanceStats['avg_task_completion'] / 5) * 100 }}%"></div>
                            </div>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $performanceStats['avg_task_completion'] }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Quality</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full"
                                    style="width: {{ ($performanceStats['avg_quality'] / 5) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $performanceStats['avg_quality'] }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Teamwork</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full"
                                    style="width: {{ ($performanceStats['avg_teamwork'] / 5) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $performanceStats['avg_teamwork'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-3 pt-3 border-t">
                    <div class="text-center p-2 bg-green-50 rounded-lg">
                        <p class="text-lg font-bold text-green-600">{{ $performanceStats['approved_evaluations'] }}</p>
                        <p class="text-xs text-gray-600">Evaluasi Selesai</p>
                    </div>
                    <div class="text-center p-2 bg-yellow-50 rounded-lg">
                        <p class="text-lg font-bold text-yellow-600">{{ $performanceStats['pending_evaluations'] }}</p>
                        <p class="text-xs text-gray-600">Menunggu Review</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sponsorship Summary --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Sponsorship</h3>
                <a href="{{ route('admin.sponsorships.index') }}" class="text-sm text-[#0053C5] hover:underline">Detail
                    →</a>
            </div>
            <div class="space-y-4">
                {{-- Collection Rate --}}
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                    <p class="text-4xl font-bold text-emerald-600">{{ $sponsorshipStats['collection_rate'] }}%</p>
                    <p class="text-sm text-gray-600 mt-1">Collection Rate</p>
                </div>

                {{-- Amount Summary --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Komitmen</span>
                        <span class="text-sm font-bold text-gray-900">Rp
                            {{ number_format($sponsorshipStats['total_committed'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <span class="text-sm text-gray-600">Diterima</span>
                        <span class="text-sm font-bold text-green-600">Rp
                            {{ number_format($sponsorshipStats['total_received'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <span class="text-sm text-gray-600">Outstanding</span>
                        <span class="text-sm font-bold text-yellow-600">Rp
                            {{ number_format($sponsorshipStats['total_outstanding'], 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Sponsor Count --}}
                <div class="grid grid-cols-2 gap-3 pt-3 border-t">
                    <div class="text-center p-2 bg-blue-50 rounded-lg">
                        <p class="text-lg font-bold text-blue-600">{{ $sponsorshipStats['confirmed_sponsors'] }}</p>
                        <p class="text-xs text-gray-600">Sponsor Confirmed</p>
                    </div>
                    <div class="text-center p-2 bg-gray-100 rounded-lg">
                        <p class="text-lg font-bold text-gray-600">{{ $sponsorshipStats['pending_sponsors'] }}</p>
                        <p class="text-xs text-gray-600">Dalam Negosiasi</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Proposal Summary --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Proposal</h3>
                <a href="{{ route('admin.proposals.index') }}" class="text-sm text-[#0053C5] hover:underline">Detail
                    →</a>
            </div>
            <div class="space-y-4">
                {{-- Approval Rate --}}
                <div class="text-center p-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl">
                    <p class="text-4xl font-bold text-indigo-600">{{ $proposalStats['approval_rate'] }}%</p>
                    <p class="text-sm text-gray-600 mt-1">Approval Rate</p>
                </div>

                {{-- Status Breakdown --}}
                <div class="grid grid-cols-2 gap-2">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <p class="text-xl font-bold text-gray-600">{{ $proposalStats['draft'] }}</p>
                        <p class="text-xs text-gray-500">Draft</p>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-xl font-bold text-blue-600">{{ $proposalStats['under_review'] }}</p>
                        <p class="text-xs text-gray-500">Under Review</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <p class="text-xl font-bold text-green-600">{{ $proposalStats['approved'] }}</p>
                        <p class="text-xs text-gray-500">Approved</p>
                    </div>
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <p class="text-xl font-bold text-red-600">{{ $proposalStats['rejected'] }}</p>
                        <p class="text-xs text-gray-500">Rejected</p>
                    </div>
                </div>

                {{-- Amount Summary --}}
                <div class="pt-3 border-t space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Diajukan</span>
                        <span class="font-bold">Rp
                            {{ number_format($proposalStats['total_requested_amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Disetujui</span>
                        <span class="font-bold text-green-600">Rp
                            {{ number_format($proposalStats['total_approved_amount'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Recent Registrations --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Registrasi Terbaru</h3>
                <a href="{{ route('admin.registrations.index') }}" class="text-sm text-[#0053C5] hover:underline">View
                    All →</a>
            </div>
            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse($recentRegistrations as $registration)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-[#0053C5] to-[#003280] rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($registration->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $registration->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $registration->event->title ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $registration->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $registration->status === 'attended' ? 'bg-blue-100 text-blue-800' : '' }}
{{ $registration->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $registration->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p>Belum ada registrasi</p>
                    </div>
                @endforelse
            </div>
        </div>
        {{-- Upcoming Events --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Event Mendatang</h3>
                <a href="{{ route('admin.events.index') }}" class="text-sm text-[#0053C5] hover:underline">View All →</a>
            </div>
            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse($upcomingEvents as $event)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-[#0053C5] to-[#003280] rounded-xl flex flex-col items-center justify-center text-white shadow-lg">
                                <span
                                    class="text-[10px] font-medium uppercase">{{ $event->start_datetime->format('M') }}</span>
                                <span
                                    class="text-xl font-bold leading-none">{{ $event->start_datetime->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $event->title }}</p>
                                <p class="text-sm text-gray-600">{{ $event->start_datetime->format('H:i') }} WIB</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }}
                                    </div>
                                    @if ($event->is_full)
                                        <span class="px-2 py-0.5 text-xs bg-red-100 text-red-600 rounded-full">Full</span>
                                    @elseif($event->canRegister())
                                        <span
                                            class="px-2 py-0.5 text-xs bg-green-100 text-green-600 rounded-full">Open</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Tidak ada event mendatang</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Milestones & Progress --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Upcoming Milestones --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Milestone Mendatang</h3>
                <a href="{{ route('admin.milestone.index') }}" class="text-sm text-[#0053C5] hover:underline">View All
                    →</a>
            </div>
            <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                @forelse($upcomingMilestones as $milestone)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">{{ $milestone->name }}</p>
                                <p class="text-xs text-gray-500">{{ $milestone->structure->name ?? 'General' }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-[#0053C5] h-1.5 rounded-full"
                                            style="width: {{ $milestone->progress_percentage }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs font-medium text-gray-600">{{ $milestone->progress_percentage }}%</span>
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <p
                                    class="text-sm font-medium {{ $milestone->days_until_due < 0 ? 'text-red-600' : ($milestone->days_until_due <= 7 ? 'text-yellow-600' : 'text-gray-600') }}">
                                    {{ $milestone->target_date->format('d M') }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    @if ($milestone->days_until_due < 0)
                                        {{ abs($milestone->days_until_due) }} hari lalu
                                    @elseif($milestone->days_until_due == 0)
                                        Hari ini
                                    @else
                                        {{ $milestone->days_until_due }} hari lagi
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <p>Tidak ada milestone mendatang</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Overdue Milestones --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">
                    <span class="text-red-600">⚠</span> Milestone Terlambat
                </h3>
                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
                    {{ count($overdueMilestones) }} items
                </span>
            </div>
            <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                @forelse($overdueMilestones as $milestone)
                    <div class="px-6 py-4 hover:bg-red-50 transition-colors bg-red-50/50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">{{ $milestone->name }}</p>
                                <p class="text-xs text-gray-500">{{ $milestone->structure->name ?? 'General' }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs text-gray-600">PIC:</span>
                                    <span
                                        class="text-xs font-medium text-gray-900">{{ $milestone->responsiblePerson->name ?? 'Belum ditentukan' }}</span>
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <p class="text-sm font-medium text-red-600">
                                    {{ $milestone->target_date->format('d M Y') }}</p>
                                <p class="text-xs text-red-500">
                                    {{ abs($milestone->days_until_due) }} hari terlambat
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-green-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-600">Semua milestone on track! 🎉</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top Events & Quick Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Top Events by Registration --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Top Events by Registration</h3>
            <div class="space-y-4">
                @forelse($topEvents as $index => $event)
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : '' }}
                        {{ $index === 1 ? 'bg-gray-100 text-gray-600' : '' }}
                        {{ $index === 2 ? 'bg-orange-100 text-orange-600' : '' }}
                        {{ $index > 2 ? 'bg-gray-50 text-gray-500' : '' }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $event->title }}</p>
                            <p class="text-xs text-gray-500">{{ $event->registrations_count }} registrations</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada data event</p>
                @endforelse
            </div>
        </div>

        {{-- Committee Quick Stats --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Kepanitiaan</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Total Divisi/Seksi</span>
                    <span class="text-lg font-bold text-gray-900">{{ $committeeStats['total_structures'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm text-gray-600">Total Anggota Aktif</span>
                    <span class="text-lg font-bold text-blue-600">{{ $committeeStats['active_members'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <span class="text-sm text-gray-600">Job Description</span>
                    <span class="text-lg font-bold text-green-600">{{ $committeeStats['active_jobdescs'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <span class="text-sm text-gray-600">Posisi Belum Terisi</span>
                    <span class="text-lg font-bold text-yellow-600">{{ $committeeStats['unfilled_positions'] }}</span>
                </div>
            </div>
        </div>

        {{-- Timeline Summary --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Progress Timeline</h3>
            <div class="text-center mb-4">
                <div class="relative inline-flex items-center justify-center">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="56" stroke="#e5e7eb" stroke-width="12"
                            fill="none" />
                        <circle cx="64" cy="64" r="56" stroke="#0053C5" stroke-width="12" fill="none"
                            stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - $timelineStats['overall_progress'] / 100) }}"
                            stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $timelineStats['overall_progress'] }}%</span>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Overall Progress</p>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="text-center p-2 bg-green-50 rounded-lg">
                    <p class="text-lg font-bold text-green-600">{{ $timelineStats['completed'] }}</p>
                    <p class="text-xs text-gray-500">Completed</p>
                </div>
                <div class="text-center p-2 bg-blue-50 rounded-lg">
                    <p class="text-lg font-bold text-blue-600">{{ $timelineStats['in_progress'] }}</p>
                    <p class="text-xs text-gray-500">In Progress</p>
                </div>
                <div class="text-center p-2 bg-yellow-50 rounded-lg">
                    <p class="text-lg font-bold text-yellow-600">{{ $timelineStats['delayed'] }}</p>
                    <p class="text-xs text-gray-500">Delayed</p>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <p class="text-lg font-bold text-gray-600">{{ $timelineStats['not_started'] }}</p>
                    <p class="text-xs text-gray-500">Not Started</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Color palette
        const colors = {
            primary: '#0053C5',
            primaryDark: '#003280',
            success: '#10B981',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#3B82F6',
            purple: '#8B5CF6',
            pink: '#EC4899',
            gray: '#6B7280'
        };

        const chartColors = [colors.primary, colors.success, colors.warning, colors.danger, colors.info, colors.purple,
            colors.pink, colors.gray
        ];

        // 1. Registration Trend Chart
        const registrationTrendCtx = document.getElementById('registrationTrendChart');
        if (registrationTrendCtx) {
            new Chart(registrationTrendCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($chartData['registrations']['labels']),
                    datasets: [{
                        label: 'Registrasi',
                        data: @json($chartData['registrations']['data']),
                        borderColor: colors.primary,
                        backgroundColor: 'rgba(0, 83, 197, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: colors.primary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // 2. Monthly Financial Chart
        const monthlyFinancialCtx = document.getElementById('monthlyFinancialChart');
        if (monthlyFinancialCtx) {
            new Chart(monthlyFinancialCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($chartData['monthly_financial']['labels']),
                    datasets: [{
                            label: 'Pemasukan',
                            data: @json($chartData['monthly_financial']['income']),
                            backgroundColor: colors.success,
                            borderRadius: 4
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($chartData['monthly_financial']['expense']),
                            backgroundColor: colors.danger,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // 3. Registration Status Chart
        const registrationStatusCtx = document.getElementById('registrationStatusChart');
        if (registrationStatusCtx) {
            new Chart(registrationStatusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['registration_by_status']['labels']),
                    datasets: [{
                        data: @json($chartData['registration_by_status']['data']),
                        backgroundColor: [colors.success, colors.warning, colors.info, colors.danger, colors
                            .gray
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // 4. Event Category Chart
        const eventCategoryCtx = document.getElementById('eventCategoryChart');
        if (eventCategoryCtx) {
            new Chart(eventCategoryCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['events_by_category']['labels']),
                    datasets: [{
                        data: @json($chartData['events_by_category']['data']),
                        backgroundColor: chartColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // 5. Timeline Progress Chart
        const timelineProgressCtx = document.getElementById('timelineProgressChart');
        if (timelineProgressCtx) {
            new Chart(timelineProgressCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['timeline_progress']['labels']),
                    datasets: [{
                        data: @json($chartData['timeline_progress']['data']),
                        backgroundColor: [colors.success, colors.info, colors.warning, colors.danger, colors
                            .gray
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // 6. Expense Category Chart
        const expenseCategoryCtx = document.getElementById('expenseCategoryChart');
        if (expenseCategoryCtx) {
            new Chart(expenseCategoryCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['expense_by_category']['labels']),
                    datasets: [{
                        data: @json($chartData['expense_by_category']['data']),
                        backgroundColor: chartColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }
    </script>
@endpush
