@extends('admin.layouts.app')

@section('title', 'Detail Surat Resmi')

@section('content')

    @php
        // Safe array conversion
        $attachmentList = is_array($officialLetter->attachment_list) ? $officialLetter->attachment_list : [];

        $ccRecipients = is_array($officialLetter->cc_recipients) ? $officialLetter->cc_recipients : [];

        $supportingFiles = is_array($officialLetter->supporting_files) ? $officialLetter->supporting_files : [];
    @endphp

    <div x-data="{ showDeleteModal: false }">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-3 text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.official-letters.index') }}" class="hover:text-[#0053C5]">
                    Manajemen Surat
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">Detail Surat</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $officialLetter->subject }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mt-2">
                        <span class="text-sm text-gray-600">{{ $officialLetter->letter_number }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm text-gray-600">{{ $officialLetter->letter_date->format('d M Y') }}</span>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $officialLetter->direction == 'incoming' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $officialLetter->direction == 'incoming' ? '📥 Masuk' : '📤 Keluar' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if ($officialLetter->status == 'draft')
                        <a href="{{ route('admin.official-letters.edit', $officialLetter) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        <form action="{{ route('admin.official-letters.submit', $officialLetter) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Ajukan Persetujuan
                            </button>
                        </form>
                    @endif

                    @if ($officialLetter->status == 'pending_approval')
                        <form action="{{ route('admin.official-letters.approve', $officialLetter) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Setujui
                            </button>
                        </form>

                        <form action="{{ route('admin.official-letters.reject', $officialLetter) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak
                            </button>
                        </form>
                    @endif

                    @if ($officialLetter->status == 'approved' && $officialLetter->direction == 'outgoing')
                        <form action="{{ route('admin.official-letters.send', $officialLetter) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Kirim
                            </button>
                        </form>
                    @endif

                    @if (in_array($officialLetter->status, ['sent', 'received']))
                        <form action="{{ route('admin.official-letters.archive', $officialLetter) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Arsipkan
                            </button>
                        </form>
                    @endif

                    @if ($officialLetter->letter_file)
                        <a href="{{ route('admin.official-letters.download', $officialLetter) }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download
                        </a>
                    @endif

                    <a href="{{ route('admin.official-letters.print', $officialLetter) }}" target="_blank"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </a>

                    @if ($officialLetter->status == 'draft')
                        <button @click="showDeleteModal = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Status & Priority --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                {{ $officialLetter->status == 'draft' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $officialLetter->status == 'pending_approval' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $officialLetter->status == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $officialLetter->status == 'sent' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $officialLetter->status == 'received' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                {{ $officialLetter->status == 'archived' ? 'bg-purple-100 text-purple-700' : '' }}">
                                {{ $officialLetter->status_label }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Prioritas</p>
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                {{ $officialLetter->priority == 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $officialLetter->priority == 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $officialLetter->priority == 'normal' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $officialLetter->priority == 'low' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ $officialLetter->priority_label }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Klasifikasi</p>
                            <span
                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 rounded-full text-sm font-medium">
                                {{ ucfirst($officialLetter->classification) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Jenis Surat</p>
                            <span
                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                {{ ucfirst(str_replace('_', ' ', $officialLetter->letter_type)) }}
                            </span>
                        </div>
                    </div>

                    @if ($officialLetter->due_date)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 {{ $officialLetter->is_overdue ? 'text-red-600' : 'text-gray-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span
                                    class="text-sm {{ $officialLetter->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    Tenggat: {{ $officialLetter->due_date->format('d M Y') }}
                                    @if ($officialLetter->is_overdue)
                                        (Terlambat!)
                                    @elseif($officialLetter->days_until_due !== null)
                                        ({{ $officialLetter->days_until_due }} hari lagi)
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Letter Content --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Isi Surat</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($officialLetter->content)) !!}
                    </div>
                </div>

                {{-- Sender Information --}}
                @if ($officialLetter->direction == 'incoming' || $officialLetter->sender_name)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengirim</h2>
                        <div class="space-y-3">
                            @if ($officialLetter->sender_name)
                                <div>
                                    <p class="text-sm text-gray-600">Nama Pengirim</p>
                                    <p class="text-gray-900 font-medium">{{ $officialLetter->sender_name }}</p>
                                </div>
                            @endif
                            @if ($officialLetter->sender_organization)
                                <div>
                                    <p class="text-sm text-gray-600">Organisasi</p>
                                    <p class="text-gray-900">{{ $officialLetter->sender_organization }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Recipient Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penerima</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Nama Penerima</p>
                            <p class="text-gray-900 font-medium">{{ $officialLetter->recipient_name }}</p>
                        </div>
                        @if ($officialLetter->recipient_organization)
                            <div>
                                <p class="text-sm text-gray-600">Organisasi</p>
                                <p class="text-gray-900">{{ $officialLetter->recipient_organization }}</p>
                            </div>
                        @endif
                        @if ($officialLetter->recipient_address)
                            <div>
                                <p class="text-sm text-gray-600">Alamat</p>
                                <p class="text-gray-900">{{ $officialLetter->recipient_address }}</p>
                            </div>
                        @endif
                        @if ($officialLetter->recipient_email)
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="text-gray-900">{{ $officialLetter->recipient_email }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CC Recipients --}}
                @if (count($ccRecipients) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tembusan (CC)</h2>
                        <div class="space-y-3">
                            @foreach ($ccRecipients as $cc)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $cc['name'] ?? '-' }}</p>
                                        <p class="text-sm text-gray-600">{{ $cc['email'] ?? '-' }}</p>
                                        @if (!empty($cc['organization']))
                                            <p class="text-sm text-gray-500">{{ $cc['organization'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Attachments --}}
                @if (count($attachmentList) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Lampiran</h2>
                        <ol class="list-decimal list-inside space-y-2">
                            @foreach ($attachmentList as $attachment)
                                <li class="text-gray-700">{{ $attachment }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Supporting Files --}}
                @if (count($supportingFiles) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">File Pendukung</h2>
                        <div class="space-y-2">
                            @foreach ($supportingFiles as $file)
                                <a href="{{ Storage::url($file) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ basename($file) }}</p>
                                        <p class="text-xs text-gray-500">Klik untuk download</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if ($officialLetter->notes || $officialLetter->internal_notes)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>

                        @if ($officialLetter->notes)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-600 mb-2">Catatan Umum</p>
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <p class="text-gray-700">{{ $officialLetter->notes }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($officialLetter->internal_notes)
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-2">Catatan Internal</p>
                                <div class="p-3 bg-yellow-50 rounded-lg">
                                    <p class="text-gray-700">{{ $officialLetter->internal_notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Dates --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tanggal</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Surat</p>
                            <p class="text-gray-900 font-medium">{{ $officialLetter->letter_date->format('d M Y') }}</p>
                        </div>

                        @if ($officialLetter->sent_date)
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Kirim</p>
                                <p class="text-gray-900">{{ $officialLetter->sent_date->format('d M Y') }}</p>
                            </div>
                        @endif

                        @if ($officialLetter->received_date)
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Terima</p>
                                <p class="text-gray-900">{{ $officialLetter->received_date->format('d M Y') }}</p>
                            </div>
                        @endif

                        @if ($officialLetter->due_date)
                            <div>
                                <p class="text-sm text-gray-600">Tenggat Waktu</p>
                                <p
                                    class="text-gray-900 {{ $officialLetter->is_overdue ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $officialLetter->event->start_date ? $officialLetter->event->start_date->format('d M Y') : 'Tanggal belum ditentukan' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Reference --}}
                @if ($officialLetter->reference_number || $officialLetter->repliedToLetter)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Referensi</h2>

                        @if ($officialLetter->reference_number)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Nomor Rujukan</p>
                                <p class="text-gray-900 font-medium">{{ $officialLetter->reference_number }}</p>
                            </div>
                        @endif

                        @if ($officialLetter->repliedToLetter)
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Balasan dari</p>
                                <a href="{{ route('admin.official-letters.show', $officialLetter->repliedToLetter) }}"
                                    class="block p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    <p class="text-sm font-medium text-blue-900">
                                        {{ $officialLetter->repliedToLetter->letter_number }}
                                    </p>
                                    <p class="text-xs text-blue-700 mt-1">
                                        {{ Str::limit($officialLetter->repliedToLetter->subject, 50) }}
                                    </p>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Signatory --}}
                @if ($officialLetter->direction == 'outgoing' && $officialLetter->signatory_name)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Penandatangan</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="text-gray-900 font-medium">{{ $officialLetter->signatory_name }}</p>
                            </div>
                            @if ($officialLetter->signatory_position)
                                <div>
                                    <p class="text-sm text-gray-600">Jabatan</p>
                                    <p class="text-gray-900">{{ $officialLetter->signatory_position }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Approval Info --}}
                @if ($officialLetter->approved_by)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Persetujuan</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Disetujui oleh</p>
                                <p class="text-gray-900 font-medium">{{ $officialLetter->approvedBy->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Persetujuan</p>
                                <p class="text-gray-900">{{ $officialLetter->approved_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Event --}}
                @if ($officialLetter->event)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Terkait Event</h2>
                        <a href="{{ route('admin.events.show', $officialLetter->event) }}"
                            class="block p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-purple-900 truncate">
                                        {{ $officialLetter->event->title }}
                                    </p>
                                    @if ($officialLetter->event->start_date)
                                        <p class="text-xs text-purple-700 mt-1">
                                            📅 {{ $officialLetter->event->start_date->format('d M Y') }}
                                            @if ($officialLetter->event->end_date && $officialLetter->event->end_date != $officialLetter->event->start_date)
                                                - {{ $officialLetter->event->end_date->format('d M Y') }}
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-xs text-purple-600 mt-1">Tanggal belum ditentukan</p>
                                    @endif
                                    @if (!empty($officialLetter->event->location))
                                        <p class="text-xs text-purple-600 mt-1">
                                            📍 {{ Str::limit($officialLetter->event->location, 30) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Metadata --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Dibuat oleh</p>
                            <p class="text-gray-900">{{ $officialLetter->createdBy->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Tanggal Dibuat</p>
                            <p class="text-gray-900">{{ $officialLetter->created_at->format('d M Y H:i') }}</p>
                        </div>
                        @if ($officialLetter->updated_at != $officialLetter->created_at)
                            <div>
                                <p class="text-gray-600">Terakhir Diupdate</p>
                                <p class="text-gray-900">{{ $officialLetter->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> {{-- Delete Modal --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm"
                    @click="showDeleteModal = false"></div>
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6 z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Hapus Surat?</h3>
                            <p class="text-sm text-gray-600 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        Anda yakin ingin menghapus surat <strong>{{ $officialLetter->letter_number }}</strong>?
                    </p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <form action="{{ route('admin.official-letters.destroy', $officialLetter) }}" method="POST"
                            class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
