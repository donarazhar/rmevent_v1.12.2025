@extends('admin.layouts.app')

@section('title', 'Laporan Kas Harian')

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
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Kas Harian</h1>
                </div>
                <p class="text-sm text-gray-600">Detail transaksi kas per hari</p>
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

        {{-- Date Filter --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('admin.cash-flow.daily') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Tanggal
                    </label>
                    <input type="date" name="date" id="date" value="{{ $date->format('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Tampilkan
                    </button>
                    <a href="{{ route('admin.cash-flow.daily', ['date' => now()->format('Y-m-d')]) }}"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Hari Ini
                    </a>
                </div>
            </form>

            {{-- Navigation --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.cash-flow.daily', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Hari Sebelumnya
                </a>

                <div class="text-center">
                    <p class="text-lg font-bold text-gray-900">{{ $date->translatedFormat('d F Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $date->translatedFormat('l') }}</p>
                </div>

                <a href="{{ route('admin.cash-flow.daily', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Hari Berikutnya
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
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">
                        {{ $stats['income_count'] }} transaksi
                    </span>
                </div>
                <h3 class="text-sm font-medium mb-1 text-green-100">Pemasukan Hari Ini</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</p>
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
                        {{ $stats['expense_count'] }} transaksi
                    </span>
                </div>
                <h3 class="text-sm font-medium mb-1 text-red-100">Pengeluaran Hari Ini</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($stats['total_expense'], 0, ',', '.') }}</p>
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
                <p class="text-2xl font-bold">Rp {{ number_format($stats['net_cash_flow'], 0, ',', '.') }}</p>
                @if ($stats['net_cash_flow'] > 0)
                    <p class="text-xs text-blue-100 mt-2">✓ Surplus</p>
                @elseif ($stats['net_cash_flow'] < 0)
                    <p class="text-xs text-blue-100 mt-2">⚠ Defisit</p>
                @else
                    <p class="text-xs text-blue-100 mt-2">= Seimbang</p>
                @endif
            </div>

            {{-- Comparison --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium mb-1 text-purple-100">vs Hari Sebelumnya</h3>
                @php
                    $diff = $stats['net_cash_flow'] - $stats['previous_day']['net_cash_flow'];
                    $percentage =
                        $stats['previous_day']['net_cash_flow'] != 0
                            ? abs(($diff / $stats['previous_day']['net_cash_flow']) * 100)
                            : 0;
                @endphp
                <p class="text-2xl font-bold">
                    @if ($diff > 0)
                        +{{ number_format($percentage, 1) }}%
                    @elseif ($diff < 0)
                        -{{ number_format($percentage, 1) }}%
                    @else
                        0%
                    @endif
                </p>
                <p class="text-xs text-purple-100 mt-2">
                    Rp {{ number_format($stats['previous_day']['net_cash_flow'], 0, ',', '.') }} kemarin
                </p>
            </div>
        </div>

        {{-- Transactions Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Incomes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Pemasukan</h3>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                            {{ $incomes->count() }} transaksi
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    @forelse($incomes as $income)
                        <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $income->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $income->source_name }}</p>
                                </div>
                                <span class="text-lg font-bold text-green-600">
                                    +Rp {{ number_format($income->amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div
                                class="flex items-center justify-between text-xs text-gray-500 mt-3 pt-3 border-t border-green-200">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $income->category)) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $income->payment_method)) }}
                                    </span>
                                </div>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $income->received_date->format('H:i') }}
                                </span>
                            </div>

                            @if ($income->description)
                                <p class="text-xs text-gray-600 mt-2 pt-2 border-t border-green-200">
                                    {{ Str::limit($income->description, 100) }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada pemasukan hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Expenses --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Pengeluaran</h3>
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                            {{ $expenses->count() }} transaksi
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    @forelse($expenses as $expense)
                        <div class="mb-4 p-4 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $expense->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $expense->vendor_name }}</p>
                                </div>
                                <span class="text-lg font-bold text-red-600">
                                    -Rp {{ number_format($expense->paid_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div
                                class="flex items-center justify-between text-xs text-gray-500 mt-3 pt-3 border-t border-red-200">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $expense->category)) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}
                                    </span>
                                </div>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $expense->payment_date->format('H:i') }}
                                </span>
                            </div>

                            @if ($expense->description)
                                <p class="text-xs text-gray-600 mt-2 pt-2 border-t border-red-200">
                                    {{ Str::limit($expense->description, 100) }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada pengeluaran hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
