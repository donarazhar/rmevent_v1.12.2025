@extends('admin.layouts.app')

@section('title', 'Detail Expense')

@section('content')
    <div x-data="{
        showApproveModal: false,
        showRejectModal: false,
        showPaymentModal: false,
        showReceiptModal: false,
        approvedAmount: {{ $expense->requested_amount }},
        paidAmount: {{ $expense->approved_amount ?? $expense->requested_amount }},
        paymentDate: '{{ date('Y-m-d') }}',
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between gap-4 mb-2">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.expenses.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Expense</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $expense->expense_code }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    @if (!in_array($expense->status, ['paid', 'cancelled']))
                        <a href="{{ route('admin.expenses.edit', $expense) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit</span>
                        </a>
                    @endif

                    @if (in_array($expense->status, ['submitted', 'under_review']))
                        <button @click="showApproveModal = true"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Approve</span>
                        </button>

                        <button @click="showRejectModal = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Reject</span>
                        </button>
                    @endif

                    @if ($expense->status === 'approved')
                        <button @click="showPaymentModal = true"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Mark as Paid</span>
                        </button>
                    @endif

                    @if ($expense->status === 'paid' && !$expense->receipt_file)
                        <button @click="showReceiptModal = true"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span>Upload Receipt</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status Badge & Overdue Warning --}}
        <div class="mb-6 flex items-center gap-3">
            @php
                $statusColors = [
                    'draft' => 'bg-gray-100 text-gray-700 border-gray-200',
                    'submitted' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'under_review' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'approved' => 'bg-green-100 text-green-700 border-green-200',
                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                    'paid' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'cancelled' => 'bg-gray-100 text-gray-700 border-gray-200',
                ];
            @endphp
            <span
                class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $statusColors[$expense->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                {{ ucfirst(str_replace('_', ' ', $expense->status)) }}
            </span>

            @if ($expense->is_overdue)
                <span
                    class="px-4 py-2 text-sm font-semibold rounded-lg border bg-red-100 text-red-700 border-red-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Overdue
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kode Expense</p>
                            <p class="text-base font-semibold text-gray-900">{{ $expense->expense_code }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kategori</p>
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
                                class="px-3 py-1 text-sm font-medium rounded-full {{ $categoryColors[$expense->category] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $expense->category)) }}
                            </span>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 mb-1">Judul</p>
                            <p class="text-base font-medium text-gray-900">{{ $expense->title }}</p>
                        </div>

                        @if ($expense->description)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Deskripsi</p>
                                <p class="text-sm text-gray-700">{{ $expense->description }}</p>
                            </div>
                        @endif

                        @if ($expense->event)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Event</p>
                                <p class="text-base font-medium text-gray-900">{{ $expense->event->title }}</p>
                            </div>
                        @endif

                        @if ($expense->budget)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Budget</p>
                                <p class="text-base font-medium text-gray-900">{{ $expense->budget->budget_code }} -
                                    {{ $expense->budget->title }}</p>
                            </div>
                        @endif

                        @if ($expense->budgetItem)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Budget Item</p>
                                <p class="text-base font-medium text-gray-900">{{ $expense->budgetItem->code }} -
                                    {{ $expense->budgetItem->name }}</p>
                            </div>
                        @endif

                        @if ($expense->structure)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Committee Structure</p>
                                <p class="text-base font-medium text-gray-900">{{ $expense->structure->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Vendor Information --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Informasi Vendor/Penerima
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nama Vendor</p>
                            <p class="text-base font-semibold text-gray-900">{{ $expense->vendor_name }}</p>
                        </div>

                        @if ($expense->vendor_contact)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Kontak</p>
                                <p class="text-base text-gray-900">{{ $expense->vendor_contact }}</p>
                            </div>
                        @endif

                        @if ($expense->vendor_address)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Alamat</p>
                                <p class="text-base text-gray-900">{{ $expense->vendor_address }}</p>
                            </div>
                        @endif

                        @if ($expense->vendor_tax_id)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">NPWP</p>
                                <p class="text-base font-mono text-gray-900">{{ $expense->vendor_tax_id }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Amount & Tax Information --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Nilai & Pajak
                    </h3>

                    <div class="space-y-4">
                        {{-- Requested Amount --}}
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div>
                                <p class="text-sm text-blue-700 mb-1">Nilai yang Diajukan</p>
                                <p class="text-2xl font-bold text-blue-900">Rp
                                    {{ number_format($expense->requested_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Approved Amount --}}
                        @if ($expense->approved_amount)
                            <div
                                class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                                <div>
                                    <p class="text-sm text-green-700 mb-1">Nilai yang Disetujui</p>
                                    <p class="text-2xl font-bold text-green-900">Rp
                                        {{ number_format($expense->approved_amount, 0, ',', '.') }}</p>
                                    @if ($expense->approved_amount != $expense->requested_amount)
                                        <p class="text-xs text-green-600 mt-1">
                                            @if ($expense->approved_amount < $expense->requested_amount)
                                                ↓ Rp
                                                {{ number_format($expense->requested_amount - $expense->approved_amount, 0, ',', '.') }}
                                                lebih kecil
                                            @else
                                                ↑ Rp
                                                {{ number_format($expense->approved_amount - $expense->requested_amount, 0, ',', '.') }}
                                                lebih besar
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        @endif

                        {{-- Paid Amount --}}
                        @if ($expense->paid_amount)
                            <div
                                class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div>
                                    <p class="text-sm text-purple-700 mb-1">Nilai yang Dibayar</p>
                                    <p class="text-2xl font-bold text-purple-900">Rp
                                        {{ number_format($expense->paid_amount, 0, ',', '.') }}</p>
                                    @if ($expense->approved_amount && $expense->paid_amount != $expense->approved_amount)
                                        <p class="text-xs text-purple-600 mt-1">
                                            Variance: Rp
                                            {{ number_format(abs($expense->variance), 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        @endif

                        {{-- Tax Information --}}
                        @if ($expense->tax_amount || $expense->tax_type)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                                @if ($expense->tax_amount)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Nilai Pajak</p>
                                        <p class="text-lg font-semibold text-orange-600">Rp
                                            {{ number_format($expense->tax_amount, 0, ',', '.') }}</p>
                                    </div>
                                @endif

                                @if ($expense->tax_type)
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Jenis Pajak</p>
                                        <p class="text-base font-medium text-gray-900">{{ $expense->tax_type }}</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Faktur Pajak</p>
                                    <span
                                        class="px-3 py-1 text-sm font-medium rounded-full {{ $expense->has_tax_invoice ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $expense->has_tax_invoice ? 'Ada' : 'Tidak Ada' }}
                                    </span>
                                </div>

                                @if ($expense->tax_amount)
                                    <div class="md:col-span-3">
                                        <p class="text-sm text-gray-600 mb-1">Total dengan Pajak</p>
                                        <p class="text-xl font-bold text-gray-900">Rp
                                            {{ number_format($expense->total_amount_with_tax, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Information --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Informasi Pembayaran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Metode Pembayaran</p>
                            <p class="text-base font-medium text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</p>
                        </div>

                        @if ($expense->bank_account)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Rekening Bank</p>
                                <p class="text-base font-mono text-gray-900">{{ $expense->bank_account }}</p>
                            </div>
                        @endif

                        @if ($expense->payment_reference)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Referensi Pembayaran</p>
                                <p class="text-base font-mono text-gray-900">{{ $expense->payment_reference }}</p>
                            </div>
                        @endif

                        @if ($expense->payment_date)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tanggal Pembayaran</p>
                                <p class="text-base font-medium text-gray-900">
                                    {{ $expense->payment_date->format('d F Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Documents --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Dokumen
                    </h3>

                    <div class="space-y-3">
                        @if ($expense->invoice_file)
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Invoice/Quotation</p>
                                        <p class="text-xs text-gray-500">Uploaded by {{ $expense->requester->name }}</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($expense->invoice_file) }}" target="_blank"
                                    class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                    Lihat
                                </a>
                            </div>
                        @endif

                        @if ($expense->receipt_file)
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Bukti Pembayaran</p>
                                        <p class="text-xs text-gray-500">Receipt/Proof of Payment</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($expense->receipt_file) }}" target="_blank"
                                    class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                    Lihat
                                </a>
                            </div>
                        @else
                            <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200 text-center">
                                <p class="text-sm text-yellow-700">Belum ada bukti pembayaran</p>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- Notes & History --}}
                @if ($expense->notes || $expense->review_notes || $expense->approval_notes || $expense->rejection_reason)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Catatan & Riwayat
                        </h3>

                        <div class="space-y-4">
                            @if ($expense->notes)
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-sm font-medium text-blue-900 mb-1">Catatan Pengaju</p>
                                    <p class="text-sm text-blue-700">{{ $expense->notes }}</p>
                                </div>
                            @endif

                            @if ($expense->review_notes)
                                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <p class="text-sm font-medium text-yellow-900 mb-1">Catatan Review</p>
                                    <p class="text-sm text-yellow-700">{{ $expense->review_notes }}</p>
                                    @if ($expense->reviewer)
                                        <p class="text-xs text-yellow-600 mt-2">oleh {{ $expense->reviewer->name }} -
                                            {{ $expense->reviewed_at->format('d M Y H:i') }}</p>
                                    @endif
                                </div>
                            @endif

                            @if ($expense->approval_notes)
                                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-sm font-medium text-green-900 mb-1">Catatan Approval</p>
                                    <p class="text-sm text-green-700">{{ $expense->approval_notes }}</p>
                                    @if ($expense->approver)
                                        <p class="text-xs text-green-600 mt-2">oleh {{ $expense->approver->name }} -
                                            {{ $expense->approved_at->format('d M Y H:i') }}</p>
                                    @endif
                                </div>
                            @endif

                            @if ($expense->rejection_reason)
                                <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                    <p class="text-sm font-medium text-red-900 mb-1">Alasan Penolakan</p>
                                    <p class="text-sm text-red-700">{{ $expense->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Timeline --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Timeline
                    </h3>

                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                        <div class="space-y-6">
                            {{-- Request --}}
                            <div class="relative pl-10">
                                <div
                                    class="absolute left-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center border-2 border-white">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Request Dibuat</p>
                                    <p class="text-xs text-gray-600">{{ $expense->request_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">oleh {{ $expense->requester->name }}</p>
                                </div>
                            </div>

                            {{-- Reviewed --}}
                            @if ($expense->reviewed_at)
                                <div class="relative pl-10">
                                    <div
                                        class="absolute left-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center border-2 border-white">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Di-review</p>
                                        <p class="text-xs text-gray-600">{{ $expense->reviewed_at->format('d M Y H:i') }}
                                        </p>
                                        @if ($expense->reviewer)
                                            <p class="text-xs text-gray-500 mt-1">oleh {{ $expense->reviewer->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Approved --}}
                            @if ($expense->approved_at)
                                <div class="relative pl-10">
                                    <div
                                        class="absolute left-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center border-2 border-white">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Disetujui</p>
                                        <p class="text-xs text-gray-600">{{ $expense->approved_at->format('d M Y H:i') }}
                                        </p>
                                        @if ($expense->approver)
                                            <p class="text-xs text-gray-500 mt-1">oleh {{ $expense->approver->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Paid --}}
                            @if ($expense->paid_at)
                                <div class="relative pl-10">
                                    <div
                                        class="absolute left-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center border-2 border-white">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Dibayar</p>
                                        <p class="text-xs text-gray-600">{{ $expense->paid_at->format('d M Y H:i') }}</p>
                                        @if ($expense->payer)
                                            <p class="text-xs text-gray-500 mt-1">oleh {{ $expense->payer->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Needed By Date --}}
                            @if ($expense->needed_by_date)
                                <div class="relative pl-10">
                                    <div
                                        class="absolute left-0 w-8 h-8 {{ $expense->is_overdue ? 'bg-red-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center border-2 border-white">
                                        <svg class="w-4 h-4 {{ $expense->is_overdue ? 'text-red-600' : 'text-gray-600' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-semibold {{ $expense->is_overdue ? 'text-red-600' : 'text-gray-900' }}">
                                            Deadline Kebutuhan</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $expense->needed_by_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Info</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Request Date</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $expense->request_date->format('d M Y') }}
                            </span>
                        </div>

                        @if ($expense->needed_by_date)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Needed By</span>
                                <span
                                    class="text-sm font-medium {{ $expense->is_overdue ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $expense->needed_by_date->format('d M Y') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Requester</span>
                            <span class="text-sm font-medium text-gray-900">{{ $expense->requester->name }}</span>
                        </div>

                        @if ($expense->approver)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Approver</span>
                                <span class="text-sm font-medium text-gray-900">{{ $expense->approver->name }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $expense->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Last Update</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $expense->updated_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Approve Modal --}}
        <div x-show="showApproveModal" x-cloak @keydown.escape.window="showApproveModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div @click.away="showApproveModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Approve Expense</h3>
                        <p class="text-sm text-gray-600">{{ $expense->expense_code }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.expenses.approve', $expense) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Approved Amount <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="approved_amount" x-model="approvedAmount" min="0"
                            step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-sm font-medium text-green-600">
                            Rp <span x-text="parseFloat(approvedAmount || 0).toLocaleString('id-ID')">0</span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Approval Notes</label>
                        <textarea name="approval_notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Catatan approval (opsional)"></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Approve Expense
                        </button>
                        <button type="button" @click="showApproveModal = false"
                            class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Reject Modal --}}
        <div x-show="showRejectModal" x-cloak @keydown.escape.window="showRejectModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div @click.away="showRejectModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Reject Expense</h3>
                        <p class="text-sm text-gray-600">{{ $expense->expense_code }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.expenses.reject', $expense) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="rejection_reason" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Jelaskan alasan penolakan expense ini..."></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            Reject Expense
                        </button>
                        <button type="button" @click="showRejectModal = false"
                            class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Payment Modal --}}
        <div x-show="showPaymentModal" x-cloak @keydown.escape.window="showPaymentModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div @click.away="showPaymentModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Mark as Paid</h3>
                        <p class="text-sm text-gray-600">{{ $expense->expense_code }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.expenses.pay', $expense) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Paid Amount <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="paid_amount" x-model="paidAmount" min="0" step="0.01"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <p class="mt-1 text-sm font-medium text-purple-600">
                            Rp <span x-text="parseFloat(paidAmount || 0).toLocaleString('id-ID')">0</span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="payment_date" x-model="paymentDate" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference</label>
                        <input type="text" name="payment_reference"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="e.g., TRX-20240101-001">
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                            Mark as Paid
                        </button>
                        <button type="button" @click="showPaymentModal = false"
                            class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Receipt Upload Modal --}}
        <div x-show="showReceiptModal" x-cloak @keydown.escape.window="showReceiptModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div @click.away="showReceiptModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Upload Receipt</h3>
                        <p class="text-sm text-gray-600">Bukti Pembayaran</p>
                    </div>
                </div>

                <form action="{{ route('admin.expenses.receipt', $expense) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Receipt File <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="receipt_file" accept=".pdf,.jpg,.jpeg,.png" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, PNG. Max: 5MB</p>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Upload Receipt
                        </button>
                        <button type="button" @click="showReceiptModal = false"
                            class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
