@extends('admin.layouts.app')

@section('title', 'Manajemen Surat Resmi')

@section('content')
    <div x-data="{
        showFilters: false,
        selectedLetters: [],
        selectAll: false,
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedLetters = Array.from(document.querySelectorAll('input[name=\'letter_ids[]\']')).map(cb => cb.value);
            } else {
                this.selectedLetters = [];
            }
        }
    }">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manajemen Surat Resmi</h1>
                    <p class="text-gray-600 mt-1">Kelola surat masuk dan surat keluar</p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Quick Filters --}}
                    <div class="flex gap-2">
                        <a href="{{ route('admin.official-letters.index', ['filter' => 'urgent']) }}"
                            class="px-4 py-2 text-sm {{ request('filter') == 'urgent' ? 'bg-red-100 text-red-700 border-red-300' : 'bg-white text-gray-700 border-gray-300' }} border rounded-lg hover:bg-red-50 transition-colors">
                            🚨 Mendesak
                        </a>
                        <a href="{{ route('admin.official-letters.index', ['filter' => 'due_soon']) }}"
                            class="px-4 py-2 text-sm {{ request('filter') == 'due_soon' ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : 'bg-white text-gray-700 border-gray-300' }} border rounded-lg hover:bg-yellow-50 transition-colors">
                            ⏰ Segera
                        </a>
                        <a href="{{ route('admin.official-letters.index', ['filter' => 'overdue']) }}"
                            class="px-4 py-2 text-sm {{ request('filter') == 'overdue' ? 'bg-orange-100 text-orange-700 border-orange-300' : 'bg-white text-gray-700 border-gray-300' }} border rounded-lg hover:bg-orange-50 transition-colors">
                            ⚠️ Terlambat
                        </a>
                    </div>

                    <button @click="showFilters = !showFilters"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Surat Baru
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-10">
                            <a href="{{ route('admin.official-letters.create', ['direction' => 'outgoing']) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Surat Keluar
                            </a>
                            <a href="{{ route('admin.official-letters.create', ['direction' => 'incoming']) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                </svg>
                                Surat Masuk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Panel --}}
        <div x-show="showFilters" x-collapse x-cloak class="mb-6">
            <form method="GET" action="{{ route('admin.official-letters.index') }}"
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Direction --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Arah Surat</label>
                        <select name="direction"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Arah</option>
                            <option value="incoming" {{ request('direction') == 'incoming' ? 'selected' : '' }}>Surat
                                Masuk</option>
                            <option value="outgoing" {{ request('direction') == 'outgoing' ? 'selected' : '' }}>Surat
                                Keluar</option>
                        </select>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                        <select name="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Jenis</option>
                            <option value="invitation" {{ request('type') == 'invitation' ? 'selected' : '' }}>Undangan
                            </option>
                            <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>
                                Pengumuman</option>
                            <option value="notification" {{ request('type') == 'notification' ? 'selected' : '' }}>
                                Pemberitahuan</option>
                            <option value="request" {{ request('type') == 'request' ? 'selected' : '' }}>Permohonan
                            </option>
                            <option value="response" {{ request('type') == 'response' ? 'selected' : '' }}>Balasan
                            </option>
                            <option value="thank_you" {{ request('type') == 'thank_you' ? 'selected' : '' }}>Ucapan
                                Terima Kasih</option>
                            <option value="cooperation" {{ request('type') == 'cooperation' ? 'selected' : '' }}>
                                Kerjasama</option>
                            <option value="recommendation" {{ request('type') == 'recommendation' ? 'selected' : '' }}>
                                Rekomendasi</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending_approval"
                                {{ request('status') == 'pending_approval' ? 'selected' : '' }}>
                                Menunggu Persetujuan</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
                            <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diterima
                            </option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Diarsipkan
                            </option>
                        </select>
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prioritas</label>
                        <select name="priority"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Prioritas</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal
                            </option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Mendesak
                            </option>
                        </select>
                    </div>

                    {{-- Event --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                        <select name="event_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Semua Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Search --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor/subjek..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.official-letters.index') }}"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Surat</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $letters->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Surat Masuk</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ \App\Models\OfficialLetter::incoming()->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Surat Keluar</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">
                            {{ \App\Models\OfficialLetter::outgoing()->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Mendesak</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">
                            {{ \App\Models\OfficialLetter::urgent()->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Perlu Tindakan</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">
                            {{ \App\Models\OfficialLetter::pendingApproval()->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Letters Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if ($letters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()"
                                        class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nomor & Tanggal</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Subjek</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Jenis & Arah</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Penerima/Pengirim</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Prioritas</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($letters as $letter)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="letter_ids[]" value="{{ $letter->id }}"
                                            x-model="selectedLetters"
                                            class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $letter->letter_number }}</span>
                                            <span
                                                class="text-xs text-gray-500">{{ $letter->letter_date->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <a href="{{ route('admin.official-letters.show', $letter) }}"
                                                class="text-sm font-medium text-gray-900 hover:text-[#0053C5]">
                                                {{ Str::limit($letter->subject, 50) }}
                                            </a>
                                            @if ($letter->event)
                                                <span class="text-xs text-gray-500 mt-1">
                                                    📅 {{ $letter->event->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                {{ $letter->letter_type == 'invitation' ? 'bg-purple-100 text-purple-700' : '' }}
                                                {{ $letter->letter_type == 'announcement' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $letter->letter_type == 'notification' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                {{ $letter->letter_type == 'request' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $letter->letter_type == 'response' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ $letter->letter_type == 'thank_you' ? 'bg-pink-100 text-pink-700' : '' }}
                                                {{ $letter->letter_type == 'cooperation' ? 'bg-orange-100 text-orange-700' : '' }}
                                                {{ $letter->letter_type == 'recommendation' ? 'bg-teal-100 text-teal-700' : '' }}
                                                {{ $letter->letter_type == 'other' ? 'bg-gray-100 text-gray-700' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $letter->letter_type)) }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                {{ $letter->direction == 'incoming' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $letter->direction == 'incoming' ? '📥 Masuk' : '📤 Keluar' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900">
                                                {{ $letter->direction == 'outgoing' ? $letter->recipient_name : $letter->sender_name }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $letter->direction == 'outgoing' ? $letter->recipient_organization : $letter->sender_organization }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            {{ $letter->priority == 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $letter->priority == 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                            {{ $letter->priority == 'normal' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $letter->priority == 'low' ? 'bg-gray-100 text-gray-700' : '' }}">
                                            {{ $letter->priority_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            {{ $letter->status == 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ $letter->status == 'pending_approval' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $letter->status == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $letter->status == 'sent' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $letter->status == 'received' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                            {{ $letter->status == 'archived' ? 'bg-purple-100 text-purple-700' : '' }}">
                                            {{ $letter->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.official-letters.show', $letter) }}"
                                                class="text-gray-600 hover:text-[#0053C5]" title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @if ($letter->status == 'draft')
                                                <a href="{{ route('admin.official-letters.edit', $letter) }}"
                                                    class="text-gray-600 hover:text-blue-600" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endif

                                            @if ($letter->letter_file)
                                                <a href="{{ route('admin.official-letters.download', $letter) }}"
                                                    class="text-gray-600 hover:text-green-600" title="Download">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $letters->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada surat</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat surat baru.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
