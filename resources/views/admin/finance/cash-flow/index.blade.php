@extends('admin.layouts.app')

@section('title', 'Cash Flow Overview')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Cash Flow Overview</h1>
                <p class="text-sm text-gray-600 mt-1">Monitor arus kas masuk dan keluar</p>
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

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('admin.cash-flow.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Akhir
                    </label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Filter
                    </button>
                </div>

                <div class="flex items-end">
                    <a href="{{ route('admin.cash-flow.index') }}"
                        class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center">
                        Reset
                    </a>
                </div>
            </form>
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
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">
                        {{ $summary['income_count'] }} transaksi
                    </span>
                </div>
                <h3 class="text-sm font-medium mb-1 text-green-100">Total Pemasukan</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</p>
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
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">
                        {{ $summary['expense_count'] }} transaksi
                    </span>
                </div>
                <h3 class="text-sm font-medium mb-1 text-red-100">Total Pengeluaran</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}</p>
            </div>

            {{-- Net Cash Flow --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">
                        Net Flow
                    </span>
                </div>
                <h3 class="text-sm font-medium mb-1 text-blue-100">Arus Kas Bersih</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['net_cash_flow'], 0, ',', '.') }}</p>
            </div>

            {{-- Quick Links --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-sm font-medium mb-4">Laporan Lainnya</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.cash-flow.daily') }}"
                        class="block px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">
                        📅 Laporan Harian
                    </a>
                    <a href="{{ route('admin.cash-flow.monthly') }}"
                        class="block px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">
                        📊 Laporan Bulanan
                    </a>
                    <a href="{{ route('admin.cash-flow.by-category') }}"
                        class="block px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">
                        🏷️ Per Kategori
                    </a>
                    <a href="{{ route('admin.cash-flow.projection') }}"
                        class="block px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">
                        🔮 Proyeksi
                    </a>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Daily Cash Flow Chart --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Arus Kas Harian</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="dailyCashFlowChart"></canvas>
                </div>
            </div>

            {{-- Category Breakdown Chart --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Breakdown Kategori</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Incomes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pemasukan Terbaru</h3>
                    <a href="{{ route('admin.incomes.index') }}" class="text-sm text-primary hover:text-primary-dark">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentIncomes as $income)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $income->title }}</p>
                                <p class="text-sm text-gray-600">{{ $income->source_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $income->received_date->format('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">
                                    +Rp {{ number_format($income->amount, 0, ',', '.') }}
                                </p>
                                <span
                                    class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $income->category)) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Belum ada pemasukan</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Expenses --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengeluaran Terbaru</h3>
                    <a href="{{ route('admin.expenses.index') }}" class="text-sm text-primary hover:text-primary-dark">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentExpenses as $expense)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $expense->title }}</p>
                                <p class="text-sm text-gray-600">{{ $expense->vendor_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $expense->payment_date->format('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">
                                    -Rp {{ number_format($expense->paid_amount, 0, ',', '.') }}
                                </p>
                                <span
                                    class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $expense->category)) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Belum ada pengeluaran</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daily Cash Flow Chart
            const dailyCashFlowCtx = document.getElementById('dailyCashFlowChart');
            if (dailyCashFlowCtx) {
                new Chart(dailyCashFlowCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(collect($dailyCashFlow)->pluck('display_date')) !!},
                        datasets: [{
                                label: 'Pemasukan',
                                data: {!! json_encode(collect($dailyCashFlow)->pluck('income')) !!},
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            },
                            {
                                label: 'Pengeluaran',
                                data: {!! json_encode(collect($dailyCashFlow)->pluck('expense')) !!},
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            },
                            {
                                label: 'Net Flow',
                                data: {!! json_encode(collect($dailyCashFlow)->pluck('net')) !!},
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
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
                                    boxWidth: 12,
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    },
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
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart');
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
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
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
