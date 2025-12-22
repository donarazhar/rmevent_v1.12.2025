@extends('admin.layouts.app')

@section('title', 'Notulensi Rapat')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Notulensi Rapat</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola semua notulensi dan dokumentasi rapat</p>
            </div>
            <a href="{{ route('admin.meeting-minutes.create') }}"
                class="inline-flex items-center px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Notulensi Baru
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Notulensi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Draft</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['draft'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Finalisasi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['finalized'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Didistribusikan</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['distributed'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['this_month'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Akan Datang</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['upcoming'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl border border-gray-200" x-data="{ showFilters: false }">
            <div class="p-4 border-b border-gray-200">
                <button @click="showFilters = !showFilters"
                    class="flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filter & Pencarian</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showFilters }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div x-show="showFilters" x-collapse>
                <form method="GET" action="{{ route('admin.meeting-minutes.index') }}" class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Search --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari judul, kode, atau lokasi..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="finalized" {{ request('status') == 'finalized' ? 'selected' : '' }}>
                                    Finalisasi</option>
                                <option value="distributed" {{ request('status') == 'distributed' ? 'selected' : '' }}>
                                    Didistribusikan</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>
                                    Diarsipkan</option>
                            </select>
                        </div>

                        {{-- Type Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Rapat</label>
                            <select name="meeting_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Tipe</option>
                                <option value="coordination"
                                    {{ request('meeting_type') == 'coordination' ? 'selected' : '' }}>Koordinasi</option>
                                <option value="planning" {{ request('meeting_type') == 'planning' ? 'selected' : '' }}>
                                    Perencanaan</option>
                                <option value="evaluation"
                                    {{ request('meeting_type') == 'evaluation' ? 'selected' : '' }}>Evaluasi</option>
                                <option value="emergency" {{ request('meeting_type') == 'emergency' ? 'selected' : '' }}>
                                    Darurat</option>
                                <option value="general" {{ request('meeting_type') == 'general' ? 'selected' : '' }}>
                                    Umum</option>
                                <option value="other" {{ request('meeting_type') == 'other' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>

                        {{-- Period Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                            <select name="period"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Semua Waktu</option>
                                <option value="upcoming" {{ request('period') == 'upcoming' ? 'selected' : '' }}>Akan
                                    Datang</option>
                                <option value="past" {{ request('period') == 'past' ? 'selected' : '' }}>Sudah
                                    Berlalu</option>
                                <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>
                                    Bulan Ini</option>
                                <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>Tahun
                                    Ini</option>
                            </select>
                        </div>

                        {{-- Event Filter --}}
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
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Terapkan Filter
                            </span>
                        </button>
                        <a href="{{ route('admin.meeting-minutes.index') }}"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Meeting Minutes Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @if ($minutes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Kode & Judul</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Tanggal & Waktu</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Tipe</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Peserta</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($minutes as $minute)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $minute->minute_code }}</p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ Str::limit($minute->meeting_title, 50) }}
                                            </p>
                                            @if ($minute->event)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Event: {{ $minute->event->title }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $minute->meeting_date ? $minute->meeting_date->format('d M Y') : '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $minute->meeting_date ? $minute->meeting_date->format('H:i') : '-' }}
                                        </p>
                                        @if ($minute->duration_minutes)
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $minute->duration_formatted }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $minute->meeting_type == 'coordination' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $minute->meeting_type == 'planning' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $minute->meeting_type == 'evaluation' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $minute->meeting_type == 'emergency' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $minute->meeting_type == 'general' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst($minute->meeting_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ $minute->participant_count }}</span>
                                        </div>
                                        @if ($minute->pending_action_items_count > 0)
                                            <p class="text-xs text-orange-600 mt-1">
                                                {{ $minute->pending_action_items_count }} tindak lanjut
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $minute->status == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $minute->status == 'finalized' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $minute->status == 'distributed' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $minute->status == 'archived' ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ $minute->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.meeting-minutes.show', $minute) }}"
                                                class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @if ($minute->isDraft())
                                                <a href="{{ route('admin.meeting-minutes.edit', $minute) }}"
                                                    class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <form action="{{ route('admin.meeting-minutes.destroy', $minute) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus notulensi ini?')"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800"
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $minutes->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada notulensi rapat</h3>
                    <p class="text-gray-600 mb-4">Mulai dengan membuat notulensi rapat pertama Anda</p>
                    <a href="{{ route('admin.meeting-minutes.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Notulensi
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
