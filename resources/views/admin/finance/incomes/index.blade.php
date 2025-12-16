@extends('admin.layouts.app')

@section('title', 'Income Management')

@section('content')
    <div x-data="{
        showFilters: false,
        selectedIncomes: [],
        selectAll: false,
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedIncomes = Array.from(document.querySelectorAll('input[name=income_ids]')).map(el => el.value);
            } else {
                this.selectedIncomes = [];
            }
        }
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Income Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Kelola data pemasukan dan verifikasi pembayaran</p>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="showFilters = !showFilters"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="text-sm font-medium">Filter</span>
                    </button>
                    <a href="{{ route('admin.incomes.create') }}"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm font-medium">Tambah Income</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total Income --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Income</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_count'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="text-green-600">{{ $stats['verified_count'] }}</span> verified
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Amount Verified --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Terverifikasi</p>
                        <p class="text-2xl font-bold text-green-600">Rp
                            {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total yang sudah verified</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending Amount --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pending Verifikasi</p>
                        <p class="text-2xl font-bold text-yellow-600">Rp
                            {{ number_format($stats['pending_amount'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['pending_count'] }} transaksi
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Rejected --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['rejected_count'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Transaksi rejected</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form action="{{ route('admin.incomes.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Event Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                        <select name="event_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="registration_fee"
                                {{ request('category') == 'registration_fee' ? 'selected' : '' }}>Registration Fee</option>
                            <option value="sponsorship" {{ request('category') == 'sponsorship' ? 'selected' : '' }}>
                                Sponsorship</option>
                            <option value="donation" {{ request('category') == 'donation' ? 'selected' : '' }}>Donation
                            </option>
                            <option value="infaq" {{ request('category') == 'infaq' ? 'selected' : '' }}>Infaq</option>
                            <option value="merchandise" {{ request('category') == 'merchandise' ? 'selected' : '' }}>
                                Merchandise</option>
                            <option value="grant" {{ request('category') == 'grant' ? 'selected' : '' }}>Grant</option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    {{-- Payment Method Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <select name="payment_method"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="bank_transfer"
                                {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="e_wallet" {{ request('payment_method') == 'e_wallet' ? 'selected' : '' }}>
                                E-Wallet</option>
                            <option value="credit_card"
                                {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Check
                            </option>
                            <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>
                </div>

                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode, judul, sumber, referensi..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                </div>

                {{-- Filter Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.incomes.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Bulk Actions --}}
        <div x-show="selectedIncomes.length > 0" x-cloak
            class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-blue-900">
                    <span x-text="selectedIncomes.length"></span> income dipilih
                </span>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.incomes.bulk-verify') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="income_ids" :value="JSON.stringify(selectedIncomes)">
                    <button type="submit" onclick="return confirm('Verifikasi semua income yang dipilih?')"
                        class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                        Verifikasi Terpilih
                    </button>
                </form>
                <button @click="selectedIncomes = []; selectAll = false"
                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()"
                                    class="w-4 h-4 text-[#0053C5] rounded focus:ring-[#0053C5]">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Income</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sumber</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($incomes as $income)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="income_ids" value="{{ $income->id }}"
                                        x-model="selectedIncomes"
                                        class="w-4 h-4 text-[#0053C5] rounded focus:ring-[#0053C5]">
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $income->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $income->income_code }}</p>
                                        @if ($income->receipt_number)
                                            <p class="text-xs text-gray-500">{{ $income->receipt_number }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $categoryColors = [
                                            'registration_fee' => 'bg-blue-100 text-blue-700',
                                            'sponsorship' => 'bg-purple-100 text-purple-700',
                                            'donation' => 'bg-green-100 text-green-700',
                                            'infaq' => 'bg-emerald-100 text-emerald-700',
                                            'merchandise' => 'bg-pink-100 text-pink-700',
                                            'grant' => 'bg-indigo-100 text-indigo-700',
                                            'other' => 'bg-gray-100 text-gray-700',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $categoryColors[$income->category] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $income->category)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $income->source_name }}</p>
                                    @if ($income->source_email)
                                        <p class="text-xs text-gray-500">{{ $income->source_email }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">Rp
                                        {{ number_format($income->amount, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $income->payment_method)) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $income->received_date?->format('d M Y') ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'verified' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$income->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($income->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.incomes.show', $income) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.incomes.edit', $income) }}"
                                            class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.incomes.destroy', $income) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus income ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-500">Belum ada data income</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($incomes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $incomes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
