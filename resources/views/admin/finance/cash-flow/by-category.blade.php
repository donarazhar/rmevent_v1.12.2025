@extends('admin.layouts.app')

@section('title', 'Laporan Kas per Kategori')

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
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Kas per Kategori</h1>
                </div>
                <p class="text-sm text-gray-600">Analisis arus kas berdasarkan kategori</p>
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
            <form method="GET" action="{{ route('admin.cash-flow.by-category') }}"
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
                    <a href="{{ route('admin.cash-flow.by-category') }}"
                        class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                        {{ $incomeByCategory->count() }} kategori
                    </span>
                </div>
                <h3 class="text-sm font-medium mb-1 text-green-100">Total Pemasukan</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
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
                        {{ $expenseByCategory->count() }} kategori
                    </span>
                </div>
                <h3 class="text-sm font-medium mb-1 text-red-100">Total Pengeluaran</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
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
                </div>
                <h3 class="text-sm font-medium mb-1 text-blue-100">Net Flow</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Category Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Income by Category --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pemasukan per Kategori</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaksi
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    %
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($incomeByCategory as $item)
                                <tr class="hover:bg-green-50 cursor-pointer"
                                    onclick="window.location='{{ route('admin.cash-flow.by-category', ['category' => $item->category, 'type' => 'income', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                        {{ $item->count }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-green-600">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            {{ number_format(($item->total / $totalIncome) * 100, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data pemasukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($incomeByCategory->count() > 0)
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">
                                        TOTAL
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-green-600">
                                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-900">
                                        100%
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Expense by Category --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pengeluaran per Kategori</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaksi
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    %
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($expenseByCategory as $item)
                                <tr class="hover:bg-red-50 cursor-pointer"
                                    onclick="window.location='{{ route('admin.cash-flow.by-category', ['category' => $item->category, 'type' => 'expense', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                        {{ $item->count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-red-600">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            {{ number_format(($item->total / $totalExpense) * 100, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data pengeluaran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($expenseByCategory->count() > 0)
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">
                                        TOTAL
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-red-600">
                                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-900">
                                        100%
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Category Detail Transactions --}}
        @if ($selectedCategory)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Detail Transaksi: {{ ucfirst(str_replace('_', ' ', $selectedCategory)) }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Tipe: <span
                                    class="font-medium">{{ $selectedType === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span>
                            </p>
                        </div>
                        <a href="{{ route('admin.cash-flow.by-category', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                            Tutup Detail
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    @if ($categoryTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach ($categoryTransactions as $transaction)
                                @if ($selectedType === 'income')
                                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">{{ $transaction->title }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $transaction->source_name }}</p>
                                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                    <span>{{ $transaction->received_date->format('d M Y H:i') }}</span>
                                                    <span>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-green-600">
                                                    +Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">{{ $transaction->title }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $transaction->vendor_name }}</p>
                                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                    <span>{{ $transaction->payment_date->format('d M Y H:i') }}</span>
                                                    <span>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-red-600">
                                                    -Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">Tidak ada transaksi</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
