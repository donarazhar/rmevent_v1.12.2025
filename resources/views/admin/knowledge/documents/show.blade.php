@extends('admin.layouts.app')

@section('title', $document->title)

@section('content')
    <div x-data="{
        showShareModal: false,
        showVersionModal: false,
        showDeleteConfirm: false
    }">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.documents.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $document->title }}</h1>
                        <span
                            class="px-3 py-1 text-sm font-medium rounded-full
                            @if ($document->status == 'final') bg-green-100 text-green-700
                            @elseif($document->status == 'draft') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($document->status) }}
                        </span>
                    </div>
                    <p class="text-gray-600">{{ $document->document_code }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($document->allow_download)
                        <a href="{{ route('admin.documents.download', $document) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download
                        </a>
                    @endif
                    @if ($document->uploaded_by == auth()->id() || auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.documents.edit', $document) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <button @click="showDeleteConfirm = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
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
                {{-- Document Preview --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Preview Dokumen</h2>
                    </div>
                    <div class="p-6">
                        @if ($document->isImage())
                            <img src="{{ $document->file_url }}" alt="{{ $document->title }}"
                                class="w-full rounded-lg shadow-sm">
                        @elseif($document->isPdf())
                            <div class="aspect-[16/9] bg-gray-100 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM8 18v-2h8v2H8zm0-4v-2h8v2H8z" />
                                    </svg>
                                    <p class="text-gray-700 font-medium mb-2">PDF Document</p>
                                    <a href="{{ $document->file_url }}" target="_blank"
                                        class="text-[#0053C5] hover:underline">Buka di tab baru</a>
                                </div>
                            </div>
                        @else
                            <div class="aspect-[16/9] bg-gray-100 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4z" />
                                    </svg>
                                    <p class="text-gray-700 font-medium mb-2">{{ strtoupper($document->file_type) }}
                                        File</p>
                                    <p class="text-gray-500 text-sm mb-4">{{ $document->file_size_human }}</p>
                                    @if ($document->allow_download)
                                        <a href="{{ route('admin.documents.download', $document) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download untuk melihat
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if ($document->description)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $document->description }}</p>
                    </div>
                @endif

                {{-- Version History --}}
                @if ($document->versions->count() > 0 || $document->parent_document_id)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Riwayat Versi</h2>
                            @if ($document->uploaded_by == auth()->id() || auth()->user()->hasRole('admin'))
                                <button @click="showVersionModal = true"
                                    class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#004AB0]">
                                    Upload Versi Baru
                                </button>
                            @endif
                        </div>
                        <div class="space-y-3">
                            {{-- Current Version --}}
                            <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-gray-900">Versi
                                                {{ $document->version }}</span>
                                            <span
                                                class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                                Terbaru
                                            </span>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $document->file_size_human }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        Oleh {{ $document->uploadedBy->name }} •
                                        {{ $document->created_at->diffForHumans() }}
                                    </p>
                                    @if ($document->version_notes)
                                        <p class="text-sm text-gray-700 italic">{{ $document->version_notes }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Previous Versions --}}
                            @foreach ($document->versions->sortByDesc('version') as $version)
                                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-medium text-gray-900">Versi {{ $version->version }}</span>
                                            <span class="text-sm text-gray-500">{{ $version->file_size_human }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Oleh {{ $version->uploadedBy->name }} •
                                            {{ $version->created_at->diffForHumans() }}
                                        </p>
                                        @if ($version->version_notes)
                                            <p class="text-sm text-gray-700 italic mb-2">{{ $version->version_notes }}</p>
                                        @endif
                                        <a href="{{ route('admin.documents.download', $version) }}"
                                            class="inline-flex items-center gap-1 text-sm text-[#0053C5] hover:underline">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download versi ini
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Parent Document --}}
                            @if ($document->parentDocument)
                                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-medium text-gray-900">Versi
                                                {{ $document->parentDocument->version }}</span>
                                            <span class="text-sm text-gray-500">Original</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ $document->parentDocument->created_at->diffForHumans() }}
                                        </p>
                                        <a href="{{ route('admin.documents.show', $document->parentDocument) }}"
                                            class="inline-flex items-center gap-1 text-sm text-[#0053C5] hover:underline">
                                            Lihat versi original
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Related Documents --}}
                @if ($relatedDocuments->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Terkait</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($relatedDocuments as $related)
                                <a href="{{ route('admin.documents.show', $related) }}"
                                    class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg hover:border-[#0053C5] hover:shadow-sm transition-all">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if ($related->isPdf())
                                            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">{{ $related->title }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $related->file_size_human }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Document Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Kategori</p>
                            <span
                                class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                            @if ($document->category == 'proposal') bg-purple-100 text-purple-700
                            @elseif($document->category == 'report') bg-blue-100 text-blue-700
                            @elseif($document->category == 'contract') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($document->category) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tipe File</p>
                            <p class="text-sm font-medium text-gray-900 uppercase">{{ $document->file_type }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Ukuran File</p>
                            <p class="text-sm font-medium text-gray-900">{{ $document->file_size_human }}</p>
                        </div>

                        @if ($document->folder)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Folder</p>
                                <p class="text-sm font-medium text-gray-900">{{ $document->folder->name }}</p>
                            </div>
                        @endif

                        @if ($document->event)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Event</p>
                                <p class="text-sm font-medium text-gray-900">{{ $document->event->title }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Diunggah oleh</p>
                            <p class="text-sm font-medium text-gray-900">{{ $document->uploadedBy->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tanggal Upload</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $document->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        @if ($document->document_date)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Dokumen</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $document->document_date->format('d M Y') }}
                                </p>
                            </div>
                        @endif

                        @if ($document->expiry_date)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Kedaluwarsa</p>
                                <p
                                    class="text-sm font-medium {{ $document->is_expired ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $document->expiry_date->format('d M Y') }}
                                    @if ($document->is_expired)
                                        <span class="text-xs">(Kedaluwarsa)</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-sm">Views</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $document->view_count }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span class="text-sm">Downloads</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $document->download_count }}</span>
                        </div>

                        @if ($document->last_viewed_at)
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500">Terakhir dilihat</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $document->last_viewed_at->diffForHumans() }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tags --}}
                @if ($document->tags && count($document->tags) > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tags</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($document->tags as $tag)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Permissions --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Izin Akses</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Visibilitas</span>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full
                            @if ($document->visibility == 'public') bg-green-100 text-green-700
                            @elseif($document->visibility == 'private') bg-gray-100 text-gray-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($document->visibility) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Download</span>
                            @if ($document->allow_download)
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Print</span>
                            @if ($document->allow_print)
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                @if ($document->notes)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $document->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Upload New Version Modal --}}
        <div x-show="showVersionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="showVersionModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Versi Baru</h3>
                    <form action="{{ route('admin.documents.upload', $document) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Baru</label>
                                <input type="file" name="file" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Versi</label>
                                <textarea name="version_notes" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]"
                                    placeholder="Apa yang berubah di versi ini?"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="showVersionModal = false"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0]">
                                Upload Versi Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="showDeleteConfirm" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="showDeleteConfirm = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                            <p class="text-sm text-gray-600 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        Apakah Anda yakin ingin menghapus dokumen <strong>{{ $document->title }}</strong>? Semua versi
                        dokumen ini juga akan dihapus.
                    </p>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="showDeleteConfirm = false"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <form action="{{ route('admin.documents.destroy', $document) }}" method="POST"
                            class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Ya, Hapus Dokumen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
