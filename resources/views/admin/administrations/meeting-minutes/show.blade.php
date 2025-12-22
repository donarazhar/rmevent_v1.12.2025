@extends('admin.layouts.app')

@section('title', 'Detail Notulensi Rapat')

@section('content')
    <div class="space-y-6" x-data="{ showDistributeModal: false, selectedUsers: [] }">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.meeting-minutes.index') }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $meetingMinute->meeting_title }}</h1>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $meetingMinute->status == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $meetingMinute->status == 'finalized' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $meetingMinute->status == 'distributed' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $meetingMinute->status == 'archived' ? 'bg-blue-100 text-blue-800' : '' }}">
                            {{ $meetingMinute->status_label }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">{{ $meetingMinute->minute_code }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Print --}}
                <a href="{{ route('admin.meeting-minutes.print', $meetingMinute) }}" target="_blank"
                    class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </span>
                </a>

                {{-- Edit (Only Draft) --}}
                @if ($meetingMinute->isDraft())
                    <a href="{{ route('admin.meeting-minutes.edit', $meetingMinute) }}"
                        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </span>
                    </a>

                    {{-- Finalize --}}
                    <form action="{{ route('admin.meeting-minutes.finalize', $meetingMinute) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin memfinalisasi notulensi ini? Notulensi yang sudah difinalisasi tidak dapat diedit lagi.')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Finalisasi
                            </span>
                        </button>
                    </form>
                @endif

                {{-- Distribute (Only Finalized) --}}
                @if ($meetingMinute->isFinalized())
                    <button @click="showDistributeModal = true"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Distribusikan
                        </span>
                    </button>
                @endif

                {{-- Download Document --}}
                @if ($meetingMinute->document_file)
                    <a href="{{ route('admin.meeting-minutes.download', $meetingMinute) }}"
                        class="px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download
                        </span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Meeting Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Tanggal Rapat</p>
                        <p class="text-sm font-bold text-gray-900">
                            {{ $meetingMinute->meeting_date ? $meetingMinute->meeting_date->format('d M Y') : '-' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $meetingMinute->meeting_date ? $meetingMinute->meeting_date->format('H:i') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Durasi</p>
                        <p class="text-sm font-bold text-gray-900">{{ $meetingMinute->duration_formatted }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Peserta</p>
                        <p class="text-sm font-bold text-gray-900">{{ $meetingMinute->participant_count }} Hadir</p>
                        <p class="text-xs text-gray-500">{{ $meetingMinute->getAttendanceRate() }}% Kehadiran</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Action Items</p>
                        <p class="text-sm font-bold text-gray-900">{{ $meetingMinute->pending_action_items_count }}
                            Pending</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Rapat</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Tipe Rapat</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ ucfirst($meetingMinute->meeting_type) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $meetingMinute->location ?? '-' }}
                                </p>
                            </div>
                        </div>

                        @if ($meetingMinute->meeting_link)
                            <div>
                                <p class="text-sm text-gray-600">Link Meeting Online</p>
                                <a href="{{ $meetingMinute->meeting_link }}" target="_blank"
                                    class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-flex items-center gap-1">
                                    {{ $meetingMinute->meeting_link }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        @endif

                        @if ($meetingMinute->event)
                            <div>
                                <p class="text-sm text-gray-600">Terkait Event</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $meetingMinute->event->title }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Agenda --}}
                @if ($meetingMinute->agenda)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Agenda</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($meetingMinute->agenda)) !!}
                        </div>
                    </div>
                @endif

                {{-- Discussion Summary --}}
                @if ($meetingMinute->discussion_summary)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Diskusi</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($meetingMinute->discussion_summary)) !!}
                        </div>
                    </div>
                @endif

                {{-- Decisions --}}
                @if ($meetingMinute->decisions)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Keputusan</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($meetingMinute->decisions)) !!}
                        </div>
                    </div>
                @endif

                {{-- Action Items --}}
                @if ($meetingMinute->action_items_list && count($meetingMinute->action_items_list) > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Tindak Lanjut</h3>
                        <div class="space-y-3">
                            @foreach ($meetingMinute->action_items_list as $index => $item)
                                <div
                                    class="p-4 border rounded-lg {{ $item['status'] == 'completed' ? 'bg-green-50 border-green-200' : ($item['status'] == 'in_progress' ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200') }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $item['task'] }}</p>
                                            @if (isset($item['assignee']) && $item['assignee'])
                                                @php
                                                    $assignee = \App\Models\User::find($item['assignee']);
                                                @endphp
                                                @if ($assignee)
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        <span class="font-medium">PIC:</span> {{ $assignee->name }}
                                                    </p>
                                                @endif
                                            @endif
                                            @if (isset($item['deadline']))
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <span class="font-medium">Deadline:</span>
                                                    {{ \Carbon\Carbon::parse($item['deadline'])->format('d M Y') }}
                                                </p>
                                            @endif
                                            @if (isset($item['notes']) && $item['notes'])
                                                <p class="text-sm text-gray-500 mt-1">{{ $item['notes'] }}</p>
                                            @endif
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $item['status'] == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $item['status'] == 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $item['status'] == 'pending' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $item['status'])) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Next Meeting --}}
                @if ($meetingMinute->next_meeting_date)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rapat Berikutnya</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $meetingMinute->next_meeting_date->format('d M Y, H:i') }}
                                </p>
                            </div>
                            @if ($meetingMinute->next_meeting_location)
                                <div>
                                    <p class="text-sm text-gray-600">Lokasi</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">
                                        {{ $meetingMinute->next_meeting_location }}
                                    </p>
                                </div>
                            @endif
                            @if ($meetingMinute->next_meeting_agenda)
                                <div>
                                    <p class="text-sm text-gray-600">Agenda</p>
                                    <div class="text-sm text-gray-900 mt-1 prose prose-sm max-w-none">
                                        {!! nl2br(e($meetingMinute->next_meeting_agenda)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Sidebar --}}
            <div class="space-y-6">
                {{-- Meeting Roles --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Peran Rapat</h3>
                    <div class="space-y-3">
                        @if ($meetingMinute->chairmanUser)
                            <div>
                                <p class="text-xs text-gray-600">Ketua Rapat</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $meetingMinute->chairmanUser->name }}</p>
                            </div>
                        @endif
                        @if ($meetingMinute->secretaryUser)
                            <div>
                                <p class="text-xs text-gray-600">Sekretaris/Notulis</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $meetingMinute->secretaryUser->name }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-600">Dibuat Oleh</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ $meetingMinute->createdBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $meetingMinute->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        @if ($meetingMinute->finalizedBy)
                            <div>
                                <p class="text-xs text-gray-600">Difinalisasi Oleh</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $meetingMinute->finalizedBy->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $meetingMinute->finalized_at ? $meetingMinute->finalized_at->format('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Participants --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Peserta Hadir ({{ count($participants) }})</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($participants as $participant)
                            <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-900">{{ $participant->name }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada peserta</p>
                        @endforelse
                    </div>
                </div>

                {{-- Absent Members --}}
                @if (count($absentMembers) > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tidak Hadir ({{ count($absentMembers) }})
                        </h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach ($absentMembers as $absent)
                                <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $absent->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- External Participants --}}
                @if ($meetingMinute->external_participants && count($meetingMinute->external_participants) > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Peserta Eksternal</h3>
                        <div class="space-y-3">
                            @foreach ($meetingMinute->external_participants as $ext)
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <p class="font-medium text-sm text-gray-900">{{ $ext['name'] }}</p>
                                    @if (isset($ext['organization']))
                                        <p class="text-xs text-gray-600 mt-1">{{ $ext['organization'] }}</p>
                                    @endif
                                    @if (isset($ext['email']))
                                        <p class="text-xs text-gray-600 mt-1">{{ $ext['email'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Attachments --}}
                @if ($meetingMinute->attachments && count($meetingMinute->attachments) > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lampiran</h3>
                        <div class="space-y-2">
                            @foreach ($meetingMinute->attachments as $attachment)
                                <a href="{{ Storage::url($attachment) }}" target="_blank"
                                    class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded group">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="text-sm text-gray-700 group-hover:text-blue-600 flex-1">
                                        {{ basename($attachment) }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Distributed To --}}
                @if ($meetingMinute->isDistributed() && count($distributedToUsers) > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Didistribusikan Kepada</h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach ($distributedToUsers as $user)
                                <div class="flex items-center gap-2 p-2 bg-purple-50 rounded">
                                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-900">{{ $user->name }}</span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Didistribusikan:
                            {{ $meetingMinute->distributed_at ? $meetingMinute->distributed_at->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Distribute Modal --}}
        <div x-show="showDistributeModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            @click.self="showDistributeModal = false">
            <div class="bg-white rounded-xl max-w-2xl w-full p-6" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Distribusikan Notulensi</h3>
                    <button @click="showDistributeModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.meeting-minutes.send', $meetingMinute) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-3">Pilih penerima distribusi notulensi:</p>
                        <div class="border border-gray-300 rounded-lg p-3 max-h-96 overflow-y-auto">
                            @foreach (\App\Models\User::all() as $user)
                                <label class="flex items-center gap-2 py-2 px-2 hover:bg-gray-50 rounded cursor-pointer">
                                    <input type="checkbox" name="distribute_to[]" value="{{ $user->id }}"
                                        x-model="selectedUsers"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-600">
                                    <span class="text-sm text-gray-700">{{ $user->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="showDistributeModal = false"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit" :disabled="selectedUsers.length === 0"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors">
                            Kirim ke <span x-text="selectedUsers.length"></span> Penerima
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
