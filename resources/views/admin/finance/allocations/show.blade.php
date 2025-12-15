@extends('admin.layouts.app')

@section('title', 'Detail Alokasi Budget')

@section('content')
    <div x-data="{
        showTransferModal: false,
        showAdjustModal: false
    }" class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900">Detail Alokasi Budget</h1>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                        {{ $budgetAllocation->allocation_code }}
                    </span>
                    @if ($budgetAllocation->status === 'active')
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">Active</span>
                    @elseif($budgetAllocation->status === 'depleted')
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Depleted</span>
                    @else
                        <span
                            class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">{{ ucfirst($budgetAllocation->status) }}</span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ $budgetAllocation->title }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if (in_array($budgetAllocation->status, ['active', 'pending']))
                    <a href="{{ route('admin.budget-allocations.edit', $budgetAllocation) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('admin.budget-allocations.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Informasi Alokasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Budget</p>
                            <p class="text-sm font-medium text-gray-900">{{ $budgetAllocation->budget->budget_code }} -
                                {{ $budgetAllocation->budget->title }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Event</p>
                            <p class="text-sm font-medium text-gray-900">{{ $budgetAllocation->event->title ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Struktur/Divisi</p>
                            <p class="text-sm font-medium text-gray-900">{{ $budgetAllocation->structure->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Tipe Alokasi</p>
                            <span class="inline-flex px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                {{ ucfirst($budgetAllocation->allocation_type) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Berlaku Dari</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $budgetAllocation->valid_from->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Berlaku Sampai</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $budgetAllocation->valid_until->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Dialokasikan Kepada</p>
                            <p class="text-sm font-medium text-gray-900">{{ $budgetAllocation->allocatedTo->name ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Dibuat Oleh</p>
                            <p class="text-sm font-medium text-gray-900">{{ $budgetAllocation->creator->name ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($budgetAllocation->description)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Deskripsi</p>
                            <p class="text-sm text-gray-900">{{ $budgetAllocation->description }}</p>
                        </div>
                    @endif

                    @if ($budgetAllocation->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Catatan</p>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-700">
                                {!! nl2br(e($budgetAllocation->notes)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Financial Summary --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Ringkasan Keuangan</h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Dialokasikan</p>
                            <p class="text-lg font-bold text-[#0053C5]">Rp
                                {{ number_format($budgetAllocation->allocated_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Terpakai</p>
                            <p class="text-lg font-bold text-red-600">Rp
                                {{ number_format($budgetAllocation->spent_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Sisa</p>
                            <p class="text-lg font-bold text-green-600">Rp
                                {{ number_format($budgetAllocation->remaining_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Utilisasi</p>
                            <p class="text-lg font-bold text-yellow-600">
                                {{ number_format($budgetAllocation->utilization_rate, 1) }}%</p>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Penggunaan Budget</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ number_format($budgetAllocation->utilization_rate, 1) }}%</span>
                        </div>
                        <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="{{ $budgetAllocation->utilization_rate > 80 ? 'bg-yellow-500' : 'bg-green-500' }} h-full transition-all duration-500"
                                style="width: {{ min($budgetAllocation->utilization_rate, 100) }}%"></div>
                        </div>
                    </div>

                    @if ($budgetAllocation->committed_amount > 0)
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Budget Terkomit</p>
                                    <p class="text-xs text-gray-600">Rp
                                        {{ number_format($budgetAllocation->committed_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">Tersedia</p>
                                    <p class="text-xs text-gray-600">Rp
                                        {{ number_format($budgetAllocation->available_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Expenses List --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-900">Riwayat Pengeluaran</h3>
                    </div>

                    @if ($budgetAllocation->expenses->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Kode
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">
                                            Deskripsi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Tanggal
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase">Jumlah
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase">Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($budgetAllocation->expenses as $expense)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <a href="{{ route('admin.expenses.show', $expense) }}"
                                                    class="text-[#0053C5] hover:underline text-sm">
                                                    {{ $expense->expense_code }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ Str::limit($expense->description, 50) }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $expense->expense_date->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-right text-sm text-gray-900">Rp
                                                {{ number_format($expense->paid_amount ?? $expense->requested_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($expense->status === 'paid')
                                                    <span
                                                        class="inline-flex px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Paid</span>
                                                @elseif($expense->status === 'approved')
                                                    <span
                                                        class="inline-flex px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Approved</span>
                                                @elseif($expense->status === 'pending')
                                                    <span
                                                        class="inline-flex px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Pending</span>
                                                @else
                                                    <span
                                                        class="inline-flex px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">{{ ucfirst($expense->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr class="font-medium">
                                        <td colspan="3" class="px-4 py-3 text-right text-gray-700">Total:</td>
                                        <td class="px-4 py-3 text-right text-gray-900">Rp
                                            {{ number_format($budgetAllocation->expenses->sum('paid_amount'), 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-gray-600">Belum ada transaksi pengeluaran</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Quick Actions --}}
                @if ($budgetAllocation->status === 'active')
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <button @click="showTransferModal = true"
                                class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Transfer Budget
                            </button>
                            <button @click="showAdjustModal = true"
                                class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                Adjustment Budget
                            </button>

                            <div class="border-t border-gray-200 my-4"></div>

                            <form action="{{ route('admin.budget-allocations.destroy', $budgetAllocation) }}"
                                method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus alokasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" {{ $budgetAllocation->expenses->count() > 0 ? 'disabled' : '' }}
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Alokasi
                                </button>
                            </form>
                            @if ($budgetAllocation->expenses->count() > 0)
                                <p class="text-xs text-gray-500 text-center">Tidak dapat menghapus alokasi dengan transaksi
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Monitoring --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Monitoring</h3>

                    @if ($budgetAllocation->is_depleted)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-red-800">
                                    <p class="font-semibold">Budget Habis!</p>
                                    <p class="text-xs mt-1">Alokasi budget sudah habis terpakai.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($budgetAllocation->utilization_rate > 80)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-semibold">Peringatan!</p>
                                    <p class="text-xs mt-1">Budget sudah terpakai
                                        {{ number_format($budgetAllocation->utilization_rate, 2) }}%</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-green-800">
                                    <p class="font-semibold">Budget Aman</p>
                                    <p class="text-xs mt-1">Masih tersedia
                                        {{ number_format(100 - $budgetAllocation->utilization_rate, 2) }}%</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Periode Berlaku:</span>
                            <span
                                class="font-medium">{{ $budgetAllocation->valid_from->diffInDays($budgetAllocation->valid_until) }}
                                hari</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sisa Waktu:</span>
                            <span class="font-medium">
                                @if (now()->isAfter($budgetAllocation->valid_until))
                                    <span class="text-red-600">Expired</span>
                                @else
                                    {{ now()->diffInDays($budgetAllocation->valid_until) }} hari
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah Transaksi:</span>
                            <span class="font-medium">{{ $budgetAllocation->expenses->count() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Budget Induk --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Budget Induk</h3>
                    <p class="text-sm font-medium text-gray-900">{{ $budgetAllocation->budget->title }}</p>
                    <p class="text-xs text-gray-600 mb-4">{{ $budgetAllocation->budget->budget_code }}</p>

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Budget:</span>
                            <span class="font-medium">Rp
                                {{ number_format($budgetAllocation->budget->total_approved, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Alokasi:</span>
                            <span class="font-medium">Rp
                                {{ number_format($budgetAllocation->budget->total_allocated, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sisa Budget:</span>
                            <span class="font-medium text-green-600">Rp
                                {{ number_format($budgetAllocation->budget->total_approved - $budgetAllocation->budget->total_allocated, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.budgets.show', $budgetAllocation->budget) }}"
                        class="block w-full text-center px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors text-sm">
                        Lihat Budget Induk
                    </a>
                </div>
            </div>
        </div>

        {{-- Transfer Modal --}}
        <div x-show="showTransferModal" x-cloak
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showTransferModal = false">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" @click.away="showTransferModal = false">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Transfer Budget</h3>
                        <button @click="showTransferModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.budget-allocations.transfer', $budgetAllocation) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-900">Budget Tersedia:</p>
                            <p class="text-lg font-bold text-[#0053C5]">Rp
                                {{ number_format($budgetAllocation->available_amount, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alokasi Tujuan <span
                                    class="text-red-500">*</span></label>
                            <select name="target_allocation_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                required>
                                <option value="">-- Pilih Alokasi Tujuan --</option>
                                @foreach (\App\Models\BudgetAllocation::where('id', '!=', $budgetAllocation->id)->where('status', 'active')->get() as $allocation)
                                    <option value="{{ $allocation->id }}">{{ $allocation->allocation_code }} -
                                        {{ $allocation->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Transfer <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="amount" step="0.01" min="0"
                                    max="{{ $budgetAllocation->available_amount }}"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                required></textarea>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 flex items-center justify-end gap-2">
                        <button type="button" @click="showTransferModal = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Adjust Modal --}}
        <div x-show="showAdjustModal" x-cloak
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showAdjustModal = false">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" @click.away="showAdjustModal = false">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Adjustment Budget</h3>
                        <button @click="showAdjustModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.budget-allocations.adjust', $budgetAllocation) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-900">Budget Saat Ini:</p>
                            <p class="text-lg font-bold text-yellow-600">Rp
                                {{ number_format($budgetAllocation->allocated_amount, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Adjustment <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="amount" step="0.01"
                                    placeholder="Positif untuk menambah, negatif untuk mengurangi"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Gunakan angka positif untuk menambah budget, negatif
                                untuk mengurangi</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="reason" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                required></textarea>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 flex items-center justify-end gap-2">
                        <button type="button" @click="showAdjustModal = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Adjust
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
