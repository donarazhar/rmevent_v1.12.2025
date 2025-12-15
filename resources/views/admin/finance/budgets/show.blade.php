@extends('admin.layouts.app')

@section('title', 'Detail RAB')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail RAB</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $budget->budget_code }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.budgets.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                @if (in_array($budget->status, ['draft', 'revised']))
                    <a href="{{ route('admin.budgets.edit', $budget) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif

                <a href="{{ route('admin.budgets.print', $budget) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </a>

                @if ($budget->status === 'approved')
                    <form action="{{ route('admin.budgets.duplicate', $budget) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Duplicate
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Status Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-xl font-bold text-gray-900">{{ $budget->title }}</h2>
                            @php
                                $statusConfig = [
                                    'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Draft'],
                                    'submitted' => [
                                        'bg' => 'bg-blue-100',
                                        'text' => 'text-blue-800',
                                        'label' => 'Submitted',
                                    ],
                                    'reviewed' => [
                                        'bg' => 'bg-yellow-100',
                                        'text' => 'text-yellow-800',
                                        'label' => 'Reviewed',
                                    ],
                                    'approved' => [
                                        'bg' => 'bg-green-100',
                                        'text' => 'text-green-800',
                                        'label' => 'Approved',
                                    ],
                                    'rejected' => [
                                        'bg' => 'bg-red-100',
                                        'text' => 'text-red-800',
                                        'label' => 'Rejected',
                                    ],
                                    'revised' => [
                                        'bg' => 'bg-purple-100',
                                        'text' => 'text-purple-800',
                                        'label' => 'Revised',
                                    ],
                                ];
                                $config = $statusConfig[$budget->status] ?? $statusConfig['draft'];
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $config['label'] }}
                            </span>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                v{{ $budget->version }}
                            </span>
                        </div>

                        @if ($budget->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $budget->description }}</p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
                            <div>
                                <p class="text-gray-500 mb-1">Event</p>
                                <p class="font-medium text-gray-900">{{ $budget->event->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Tahun Anggaran</p>
                                <p class="font-medium text-gray-900">{{ $budget->fiscal_year }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Periode Berlaku</p>
                                <p class="font-medium text-gray-900">
                                    {{ $budget->valid_from->format('d M Y') }} -
                                    {{ $budget->valid_until->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Dibuat Oleh</p>
                                <p class="font-medium text-gray-900">{{ $budget->creator->name }}</p>
                                <p class="text-xs text-gray-500">{{ $budget->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Workflow Actions --}}
        @if ($budget->status === 'draft')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6" x-data="{ submittedTo: '' }">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Submit RAB untuk Approval</h3>
                <form action="{{ route('admin.budgets.approve', $budget) }}" method="POST" class="space-y-4">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                        Submit untuk Approval
                    </button>
                </form>
            </div>
        @endif

        @if (in_array($budget->status, ['submitted', 'reviewed']))
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6" x-data="{ action: '', reason: '' }">
                <h3 class="text-lg font-semibold text-yellow-900 mb-4">Review RAB</h3>
                <div class="space-y-4">
                    <div x-show="action === 'reject' || action === 'revise'">
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            <span x-show="action === 'reject'">Alasan Penolakan</span>
                            <span x-show="action === 'revise'">Alasan Revisi</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea x-model="reason" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                            placeholder="Jelaskan alasan..."></textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <form action="{{ route('admin.budgets.approve', $budget) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" @click="action = 'approve'"
                                class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Approve
                            </button>
                        </form>

                        <form action="{{ route('admin.budgets.revise', $budget) }}" method="POST" class="inline"
                            @submit.prevent="if(reason) $el.submit()" x-ref="reviseForm">
                            @csrf
                            <input type="hidden" name="revision_reason" x-model="reason">
                            <button type="submit" @click="action = 'revise'"
                                class="px-6 py-2.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Request Revision
                            </button>
                        </form>

                        <form action="{{ route('admin.budgets.reject', $budget) }}" method="POST" class="inline"
                            @submit.prevent="if(reason) $el.submit()" x-ref="rejectForm">
                            @csrf
                            <input type="hidden" name="rejection_reason" x-model="reason">
                            <button type="submit" @click="action = 'reject'"
                                class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if ($budget->status === 'approved' && $budget->approval_notes)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-green-900">Catatan Approval</h4>
                        <p class="mt-1 text-sm text-green-700">{{ $budget->approval_notes }}</p>
                        <p class="mt-2 text-xs text-green-600">
                            Diapprove oleh {{ $budget->approver->name }} pada
                            {{ $budget->approved_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if ($budget->status === 'rejected' && $budget->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-red-900">Alasan Penolakan</h4>
                        <p class="mt-1 text-sm text-red-700">{{ $budget->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($budget->status === 'revised' && $budget->revision_reason)
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-purple-900">Alasan Revisi</h4>
                        <p class="mt-1 text-sm text-purple-700">{{ $budget->revision_reason }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Budget Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Planned</p>
                        <p class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($budget->total_planned, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Approved</p>
                        <p class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($budget->total_approved ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Allocated</p>
                        <p class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($budget->total_allocated ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Spent</p>
                        <p class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($budget->total_spent ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Budget Items --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Item Anggaran</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Item
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Qty
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Satuan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($budget->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $item->code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                    @if ($item->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ $item->description }}</div>
                                    @endif
                                    @if ($item->is_mandatory)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                            Mandatory
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 capitalize">{{ $item->category }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ number_format($item->quantity, 2) }}
                                        {{ $item->unit }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">Rp
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $priorityConfig = [
                                            'low' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                                            'medium' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                            'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                                            'critical' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                        ];
                                        $config = $priorityConfig[$item->priority] ?? $priorityConfig['medium'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} capitalize">
                                        {{ $item->priority }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm text-gray-500">Tidak ada item anggaran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                                Total:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-bold text-primary">
                                    Rp {{ number_format($budget->total_planned, 0, ',', '.') }}
                                </span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Attachments --}}
        @if ($budget->attachments && count($budget->attachments) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Lampiran Dokumen</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($budget->attachments as $attachment)
                            <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                                class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors group">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary">
                                        {{ $attachment['name'] ?? 'File' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format(($attachment['size'] ?? 0) / 1024, 2) }} KB
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Notes --}}
        @if ($budget->notes)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Catatan</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $budget->notes }}</p>
                </div>
            </div>
        @endif
    </div>
@endsection
