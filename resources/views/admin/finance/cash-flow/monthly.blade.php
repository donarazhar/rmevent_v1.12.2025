@extends('admin.layouts.app')

@section('title', 'Laporan Kas Bulanan')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.cash-flow.index') }}"
                        class="text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Kas Bulanan</h1>
                </div>
                <p class="text-sm text-gray-600">Analisis arus kas per bulan</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.cash-flow.export') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </a>
            </div>
        </div>

        {{-- Month/Year Filter --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('admin.cash-flow.monthly') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">
                        Bulan
                    </label>
                    <select name="month" id="month"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex-1">
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun
                    </label>
                    <select name="year" id="year"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Tampilkan
                    </button>
                    <a href="{{ route('admin.cash-flow.monthly') }}"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                </div>
            </form>

            {{-- Navigation --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                @php
                    $prevMonth = $startDate->copy()->subMonth();
                    $nextMonth = $startDate->copy()->addMonth();
                @endphp

                <a href="{{ route('admin.cash-flow.monthly', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ $prevMonth->translatedFormat('F Y') }}
                </a>

                <div class="text-center">
                    <p class="text-lg font-bold text-gray-900">{{ $startDate->translatedFormat('F Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $startDate->format('d') }} - {{ $endDate->format('d') }} hari
                    </p>
                </div>

                <a href="{{ route('admin.cash-flow.monthly', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    {{ $nextMonth->translatedFormat('F Y') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Income --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium mb-1 text-green-100">Total Pemasukan</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</p>
                @php
                    $incomeDiff = $summary['total_income'] - $previousMonthData['total_income'];
                    $incomePercentage =
                        $previousMonthData['total_income'] != 0
                            ? abs(($incomeDiff / $previousMonthData['total_income']) * 100)
                            : 0;
                @endphp
                <p class="text-xs text-green-100 mt-2">
                    @if ($incomeDiff > 0)
                        ↑ +{{ number_format($incomePercentage, 1) }}% dari bulan lalu
                    @elseif ($incomeDiff < 0)
                        ↓ -{{ number_format($incomePercentage, 1) }}% dari bulan lalu
                    @else
                        = Sama dengan bulan lalu
                    @endif
                </p>
            </div>

            {{-- Total Expense --}}
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium mb-1 text-red-100">Total Pengeluaran</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}</p>
                @php
                    $expenseDiff = $summary['total_expense'] - $previousMonthData['total_expense'];
                    $expensePercentage =
                        $previousMonthData['total_expense'] != 0
                            ? abs(($expenseDiff / $previousMonthData['total_expense']) * 100)
                            : 0;
                @endphp
                <p class="text-xs text-red-100 mt-2">
                    @if ($expenseDiff > 0)
                        ↑ +{{ number_format($expensePercentage, 1) }}% dari bulan lalu
                    @elseif ($expenseDiff < 0)
                        ↓ -{{ number_format($expensePercentage, 1) }}% dari bulan lalu
                    @else
                        = Sama dengan bulan lalu
                    @endif
                </p>
            </div>

            {{-- Net Cash Flow --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium mb-1 text-blue-100">Kas Bersih</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['net_cash_flow'], 0, ',', '.') }}</p>
                @if ($summary['net_cash_flow'] > 0)
                    <p class="text-xs text-blue-100 mt-2">✓ Surplus Bulan Ini</p>
                @elseif ($summary['net_cash_flow'] < 0)
                    <p class="text-xs text-blue-100 mt-2">⚠ Defisit Bulan Ini</p>
                @else
                    <p class="text-xs text-blue-100 mt-2">= Seimbang Bulan Ini</p>
                @endif
            </div>

            {{-- Year to Date --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium mb-1 text-purple-100">Year to Date</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($ytdData['net_cash_flow'], 0, ',', '.') }}</p>
                <p class="text-xs text-purple-100 mt-2">Akumulasi s/d {{ $startDate->translatedFormat('F') }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Daily Trend Chart --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Harian</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="dailyTrendChart"></canvas>
                </div>
            </div>

            {{-- Category Breakdown Chart --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Breakdown per Kategori</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="categoryBreakdownChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Weekly Breakdown Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Breakdown per Minggu</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Minggu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Periode
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pemasukan
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengeluaran
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Net Flow
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($weeklyBreakdown as $week)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $week['week'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $week['start_date'] }} - {{ $week['end_date'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600">
                                    Rp {{ number_format($week['income'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600">
                                    Rp {{ number_format($week['expense'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold">
                                    <span class="{{ $week['net'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                        Rp {{ number_format($week['net'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">
                                TOTAL BULAN INI
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-green-600">
                                Rp {{ number_format($summary['total_income'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-red-600">
                                Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-blue-600">
                                Rp {{ number_format($summary['net_cash_flow'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Daily Breakdown Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Breakdown per Hari</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pemasukan
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengeluaran
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Net Flow
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($dailyBreakdown as $day)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($day['date'])->translatedFormat('l, d F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600">
                                    Rp {{ number_format($day['income'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600">
                                    Rp {{ number_format($day['expense'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">
                                    <span class="{{ $day['net'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                        Rp {{ number_format($day['net'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daily Trend Chart
            const dailyTrendCtx = document.getElementById('dailyTrendChart');
            if (dailyTrendCtx) {
                new Chart(dailyTrendCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(collect($dailyBreakdown)->pluck('display_date')) !!},
                        datasets: [{
                                label: 'Pemasukan',
                                data: {!! json_encode(collect($dailyBreakdown)->pluck('income')) !!},
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: 'rgb(34, 197, 94)',
                                borderWidth: 1
                            },
                            {
                                label: 'Pengeluaran',
                                data: {!! json_encode(collect($dailyBreakdown)->pluck('expense')) !!},
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: 'rgb(239, 68, 68)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + context.parsed.y
                                            .toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                        }
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Category Breakdown Chart
            const categoryCtx = document.getElementById('categoryBreakdownChart');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pemasukan', 'Pengeluaran'],
                        datasets: [{
                            data: [
                                {{ $summary['total_income'] }},
                                {{ $summary['total_expense'] }}
                            ],
                            backgroundColor: [
                                'rgb(34, 197, 94)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' +
                                            percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
