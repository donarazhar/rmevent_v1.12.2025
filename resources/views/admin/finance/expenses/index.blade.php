@extends('admin.layouts.app')

@section('title', 'Expense Management')

@section('content')
    <div x-data="{
        showFilters: false,
        selectedExpenses: [],
        selectAll: false,
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedExpenses = Array.from(document.querySelectorAll('input[name=expense_ids]')).map(el => el.value);
            } else {
                this.selectedExpenses = [];
            }
        }
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Expense Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Kelola pengajuan dan pembayaran pengeluaran</p>
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
                    <a href="{{ route('admin.expenses.create') }}"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm font-medium">Ajukan Expense</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total Requested --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Requested</p>
                        <p class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($stats['total_requested'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['total_count'] }} expenses</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Approved --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Approved</p>
                        <p class="text-2xl font-bold text-green-600">Rp
                            {{ number_format($stats['total_approved'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['approved_count'] }} approved</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Paid --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Paid</p>
                        <p class="text-2xl font-bold text-purple-600">Rp
                            {{ number_format($stats['total_paid'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['paid_count'] }} paid</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending & Overdue --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_count'] }}</p>
                        <p class="text-xs text-red-500 mt-1">
                            <span class="font-semibold">{{ $stats['overdue_count'] }}</span> overdue
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
        </div>

        {{-- Filters Section --}}
        <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form action="{{ route('admin.expenses.index') }}" method="GET" class="space-y-4">
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
                            <option value="operational" {{ request('category') == 'operational' ? 'selected' : '' }}>
                                Operational</option>
                            <option value="event_execution"
                                {{ request('category') == 'event_execution' ? 'selected' : '' }}>Event Execution</option>
                            <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>Equipment
                            </option>
                            <option value="logistics" {{ request('category') == 'logistics' ? 'selected' : '' }}>Logistics
                            </option>
                            <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>Marketing
                            </option>
                            <option value="transportation"
                                {{ request('category') == 'transportation' ? 'selected' : '' }}>Transportation</option>
                            <option value="accommodation" {{ request('category') == 'accommodation' ? 'selected' : '' }}>
                                Accommodation</option>
                            <option value="meals" {{ request('category') == 'meals' ? 'selected' : '' }}>Meals</option>
                            <option value="honorarium" {{ request('category') == 'honorarium' ? 'selected' : '' }}>
                                Honorarium</option>
                            <option value="utilities" {{ request('category') == 'utilities' ? 'selected' : '' }}>Utilities
                            </option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted
                            </option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under
                                Review</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    {{-- Requester Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Requester</label>
                        <select name="requested_by"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Requester</option>
                            @foreach ($requesters as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('requested_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
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

                    {{-- Overdue Filter --}}
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="overdue" value="1"
                                {{ request('overdue') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-[#0053C5] rounded focus:ring-[#0053C5]">
                            <span class="text-sm font-medium text-gray-700">Hanya Overdue</span>
                        </label>
                    </div>
                </div>

                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode, judul, vendor..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                </div>

                {{-- Filter Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.expenses.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Bulk Actions --}}
        <div x-show="selectedExpenses.length > 0" x-cloak
            class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-blue-900">
                    <span x-text="selectedExpenses.length"></span> expense dipilih
                </span>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.expenses.bulk-approve') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="expense_ids" :value="JSON.stringify(selectedExpenses)">
                    <button type="submit" onclick="return confirm('Approve semua expense yang dipilih?')"
                        class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                        Approve Terpilih
                    </button>
                </form>
                <button @click="selectedExpenses = []; selectAll = false"
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
                                Expense</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Request Date</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50 transition-colors {{ $expense->is_overdue ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4">
                                    @if (in_array($expense->status, ['submitted', 'under_review']))
                                        <input type="checkbox" name="expense_ids" value="{{ $expense->id }}"
                                            x-model="selectedExpenses"
                                            class="w-4 h-4 text-[#0053C5] rounded focus:ring-[#0053C5]">
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $expense->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $expense->expense_code }}</p>
                                        @if ($expense->is_overdue)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700 mt-1">
                                                Overdue
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $expense->vendor_name }}</p>
                                    @if ($expense->vendor_contact)
                                        <p class="text-xs text-gray-500">{{ $expense->vendor_contact }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $categoryColors = [
                                            'operational' => 'bg-blue-100 text-blue-700',
                                            'event_execution' => 'bg-purple-100 text-purple-700',
                                            'equipment' => 'bg-indigo-100 text-indigo-700',
                                            'logistics' => 'bg-pink-100 text-pink-700',
                                            'marketing' => 'bg-green-100 text-green-700',
                                            'transportation' => 'bg-yellow-100 text-yellow-700',
                                            'accommodation' => 'bg-orange-100 text-orange-700',
                                            'meals' => 'bg-red-100 text-red-700',
                                            'honorarium' => 'bg-emerald-100 text-emerald-700',
                                            'utilities' => 'bg-cyan-100 text-cyan-700',
                                            'other' => 'bg-gray-100 text-gray-700',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $categoryColors[$expense->category] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $expense->category)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">Rp
                                        {{ number_format($expense->requested_amount, 0, ',', '.') }}</p>
                                    @if ($expense->approved_amount && $expense->approved_amount != $expense->requested_amount)
                                        <p class="text-xs text-green-600">Approved: Rp
                                            {{ number_format($expense->approved_amount, 0, ',', '.') }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-700',
                                            'submitted' => 'bg-blue-100 text-blue-700',
                                            'under_review' => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'paid' => 'bg-purple-100 text-purple-700',
                                            'cancelled' => 'bg-gray-100 text-gray-700',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$expense->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $expense->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $expense->request_date->format('d M Y') }}</p>
                                    @if ($expense->needed_by_date)
                                        <p class="text-xs text-gray-500">Need:
                                            {{ $expense->needed_by_date->format('d M Y') }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.expenses.show', $expense) }}"
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
                                        @if (!in_array($expense->status, ['paid', 'cancelled']))
                                            <a href="{{ route('admin.expenses.edit', $expense) }}"
                                                class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif
                                        @if (in_array($expense->status, ['draft', 'rejected']))
                                            <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus expense ini?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-500">Belum ada data expense</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($expenses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
