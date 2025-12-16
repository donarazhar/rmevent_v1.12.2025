@extends('admin.layouts.app')

@section('title', 'Proyeksi Cash Flow')

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
                    <h1 class="text-2xl font-bold text-gray-900">Proyeksi Cash Flow</h1>
                </div>
                <p class="text-sm text-gray-600">Estimasi arus kas masa depan berdasarkan data historis</p>
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

        {{-- Projection Settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('admin.cash-flow.projection') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="months" class="block text-sm font-medium text-gray-700 mb-2">
                        Proyeksi untuk (bulan ke depan)
                    </label>
                    <select name="months" id="months"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="3" {{ $months == 3 ? 'selected' : '' }}>3 Bulan</option>
                        <option value="6" {{ $months == 6 ? 'selected' : '' }}>6 Bulan</option>
                        <option value="12" {{ $months == 12 ? 'selected' : '' }}>12 Bulan</option>
                    </select>
                </div>

                <button type="submit"
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    Hitung Proyeksi
                </button>
            </form>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Rata-rata Pemasukan/Bulan</p>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($avgIncome, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Rata-rata Pengeluaran/Bulan</p>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($avgExpense, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Projection Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Proyeksi</h3>
            <div class="relative" style="height: 400px;">
                <canvas id="projectionChart"></canvas>
            </div>
        </div>

        {{-- Projection Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tabel Proyeksi</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bulan
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Proyeksi Pemasukan
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Proyeksi Pengeluaran
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Net Flow
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Saldo Proyeksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($projections as $projection)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $projection['month'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600">
                                    Rp {{ number_format($projection['projected_income'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600">
                                    Rp {{ number_format($projection['projected_expense'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">
                                    <span
                                        class="{{ $projection['projected_net'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                        Rp {{ number_format($projection['projected_net'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold">
                                    <span
                                        class="{{ $projection['projected_balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                        Rp {{ number_format($projection['projected_balance'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Pending Transactions --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Pending Incomes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Pemasukan Pending</h3>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                            {{ $pendingIncomes->count() }} transaksi
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">Pemasukan yang belum terverifikasi</p>
                </div>

                <div class="p-6">
                    @forelse($pendingIncomes->take(5) as $income)
                        <div class="mb-3 p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $income->title }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $income->source_name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Expected: {{ $income->received_date->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($income->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8 text-sm">Tidak ada pemasukan pending</p>
                    @endforelse

                    @if ($pendingIncomes->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.incomes.index', ['status' => 'pending']) }}"
                                class="text-sm text-primary hover:text-primary-dark font-medium">
                                Lihat semua ({{ $pendingIncomes->count() }}) →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pending Expenses --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Pengeluaran Pending</h3>
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
                            {{ $pendingExpenses->count() }} transaksi
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">Pengeluaran yang belum dibayar</p>
                </div>

                <div class="p-6">
                    @forelse($pendingExpenses->take(5) as $expense)
                        <div class="mb-3 p-3 bg-orange-50 rounded-lg border border-orange-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $expense->title }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $expense->vendor_name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Needed by:
                                        {{ $expense->needed_by_date ? $expense->needed_by_date->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-red-600 block">
                                        Rp
                                        {{ number_format($expense->approved_amount ?? $expense->requested_amount, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ ucfirst($expense->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8 text-sm">Tidak ada pengeluaran pending</p>
                    @endforelse

                    @if ($pendingExpenses->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.expenses.index', ['status' => 'approved']) }}"
                                class="text-sm text-primary hover:text-primary-dark font-medium">
                                Lihat semua ({{ $pendingExpenses->count() }}) →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Historical Data --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Data Historis (6 Bulan Terakhir)</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bulan
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
                        @foreach ($historicalData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $data['month'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600">
                                    Rp {{ number_format($data['income'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600">
                                    Rp {{ number_format($data['expense'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">
                                    <span class="{{ $data['net'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                        Rp {{ number_format($data['net'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Disclaimer --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-yellow-900 mb-1">Catatan Penting</h4>
                    <p class="text-sm text-yellow-800">
                        Proyeksi ini dibuat berdasarkan rata-rata data historis 6 bulan terakhir. Hasil proyeksi bersifat
                        estimasi dan dapat berbeda dengan kondisi aktual. Gunakan sebagai referensi perencanaan, bukan
                        sebagai jaminan kondisi kas masa depan.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectionCtx = document.getElementById('projectionChart');
            if (projectionCtx) {
                // Combine historical and projection data
                const historicalLabels = {!! json_encode(collect($historicalData)->pluck('month')) !!};
                const projectionLabels = {!! json_encode(collect($projections)->pluck('month')) !!};
                const allLabels = historicalLabels.concat(projectionLabels);
                const historicalIncome = {!! json_encode(collect($historicalData)->pluck('income')) !!};
                const projectionIncome = {!! json_encode(collect($projections)->pluck('projected_income')) !!};
                const allIncome = historicalIncome.concat(projectionIncome);

                const historicalExpense = {!! json_encode(collect($historicalData)->pluck('expense')) !!};
                const projectionExpense = {!! json_encode(collect($projections)->pluck('projected_expense')) !!};
                const allExpense = historicalExpense.concat(projectionExpense);

                const historicalNet = {!! json_encode(collect($historicalData)->pluck('net')) !!};
                const projectionNet = {!! json_encode(collect($projections)->pluck('projected_net')) !!};
                const allNet = historicalNet.concat(projectionNet);

                new Chart(projectionCtx, {
                    type: 'line',
                    data: {
                        labels: allLabels,
                        datasets: [{
                                label: 'Pemasukan',
                                data: allIncome,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2,
                                segment: {
                                    borderDash: ctx => {
                                        // Dashed line for projection part
                                        return ctx.p0DataIndex >= historicalIncome.length - 1 ? [5,
                                            5
                                        ] : [];
                                    }
                                }
                            },
                            {
                                label: 'Pengeluaran',
                                data: allExpense,
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2,
                                segment: {
                                    borderDash: ctx => {
                                        return ctx.p0DataIndex >= historicalExpense.length - 1 ? [5,
                                            5
                                        ] : [];
                                    }
                                }
                            },
                            {
                                label: 'Net Flow',
                                data: allNet,
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2,
                                segment: {
                                    borderDash: ctx => {
                                        return ctx.p0DataIndex >= historicalNet.length - 1 ? [5,
                                            5] : [];
                                    }
                                }
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
                                        const isProjection = context.dataIndex >= historicalIncome
                                            .length;
                                        const prefix = isProjection ? 'Proyeksi ' : '';
                                        return prefix + context.dataset.label + ': Rp ' + context.parsed
                                            .y
                                            .toLocaleString('id-ID');
                                    }
                                }
                            },
                            annotation: {
                                annotations: {
                                    line1: {
                                        type: 'line',
                                        xMin: historicalIncome.length - 0.5,
                                        xMax: historicalIncome.length - 0.5,
                                        borderColor: 'rgb(156, 163, 175)',
                                        borderWidth: 2,
                                        borderDash: [10, 5],
                                        label: {
                                            content: 'Proyeksi →',
                                            enabled: true,
                                            position: 'start'
                                        }
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
        });
    </script>
@endpush
