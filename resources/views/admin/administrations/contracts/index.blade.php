@extends('admin.layouts.app')

@section('title', 'Manajemen Kontrak')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Kontrak</h1>
                <p class="text-gray-600 mt-1">Kelola semua kontrak & perjanjian</p>
            </div>
            <a href="{{ route('admin.contracts.create') }}"
                class="inline-flex items-center px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Kontrak Baru
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Total Contracts --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Kontrak</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Active Contracts --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kontrak Aktif</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['active']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending Signature --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Menunggu TTD</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($stats['pending_signature']) }}
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

            {{-- Expiring Soon --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Segera Berakhir</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['expiring_soon']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Value --}}
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Nilai</p>
                        <p class="text-2xl font-bold text-[#0053C5] mt-1">
                            {{ number_format($stats['total_value'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.contracts.index') }}" class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Search --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Kode, judul, atau pihak..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Type Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                        <select name="type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Tipe</option>
                            <option value="sponsorship" {{ request('type') == 'sponsorship' ? 'selected' : '' }}>Sponsorship
                            </option>
                            <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            <option value="venue" {{ request('type') == 'venue' ? 'selected' : '' }}>Venue</option>
                            <option value="partnership" {{ request('type') == 'partnership' ? 'selected' : '' }}>
                                Partnership
                            </option>
                            <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service</option>
                            <option value="employment" {{ request('type') == 'employment' ? 'selected' : '' }}>Employment
                            </option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending_signature"
                                {{ request('status') == 'pending_signature' ? 'selected' : '' }}>
                                Menunggu TTD</option>
                            <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }}>Ditandatangani
                            </option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Diakhiri
                            </option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa
                            </option>
                        </select>
                    </div>

                    {{-- Event Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                        <select name="event_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white rounded-lg transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.contracts.index') }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Contracts Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kode & Judul
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tipe
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Pihak Kedua
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Periode
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nilai Kontrak
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($contracts as $contract)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $contract->contract_code }}</div>
                                        <div class="text-sm text-gray-600">{{ $contract->title }}</div>
                                        @if ($contract->event)
                                            <span
                                                class="inline-block mt-1 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">
                                                {{ $contract->event->name }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $contract->type === 'sponsorship' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $contract->type === 'vendor' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $contract->type === 'venue' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $contract->type === 'partnership' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $contract->type === 'service' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $contract->type === 'employment' ? 'bg-pink-100 text-pink-800' : '' }}
                                    {{ $contract->type === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($contract->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $contract->party_b_name }}</div>
                                    @if ($contract->party_b_representative)
                                        <div class="text-xs text-gray-500">{{ $contract->party_b_representative }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div>{{ $contract->start_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">s/d {{ $contract->end_date->format('d M Y') }}
                                    </div>
                                    @if ($contract->is_expiring_soon)
                                        <div class="mt-1 text-xs text-red-600 font-medium">
                                            ⚠️ {{ $contract->days_remaining }} hari lagi
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $contract->contract_value_formatted }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'draft' => ['color' => 'gray', 'label' => 'Draft'],
                                            'pending_signature' => ['color' => 'yellow', 'label' => 'Menunggu TTD'],
                                            'signed' => ['color' => 'blue', 'label' => 'Ditandatangani'],
                                            'active' => ['color' => 'green', 'label' => 'Aktif'],
                                            'completed' => ['color' => 'indigo', 'label' => 'Selesai'],
                                            'terminated' => ['color' => 'red', 'label' => 'Diakhiri'],
                                            'expired' => ['color' => 'red', 'label' => 'Kadaluarsa'],
                                        ];
                                        $status = $statusConfig[$contract->status] ?? [
                                            'color' => 'gray',
                                            'label' => $contract->status,
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.contracts.show', $contract) }}"
                                            class="p-2 text-[#0053C5] hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.contracts.edit', $contract) }}"
                                            class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if ($contract->isDraft())
                                            <form action="{{ route('admin.contracts.destroy', $contract) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Yakin ingin menghapus kontrak ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
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
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">Belum ada kontrak</p>
                                    <p class="text-sm mt-1">Mulai dengan membuat kontrak baru</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($contracts->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $contracts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
