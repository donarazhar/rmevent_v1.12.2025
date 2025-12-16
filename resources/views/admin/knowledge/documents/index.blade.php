@extends('admin.layouts.app')

@section('title', 'Repositori Dokumen')

@section('content')
    <div x-data="{
        showCreateFolder: false,
        showUpload: false,
        viewMode: localStorage.getItem('doc_view_mode') || 'grid',
        toggleView(mode) {
            this.viewMode = mode;
            localStorage.setItem('doc_view_mode', mode);
        }
    }">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Repositori Dokumen</h1>
                    <p class="text-gray-600 mt-1">Kelola dan organisir semua dokumen kegiatan</p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="showCreateFolder = true"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <span>Folder Baru</span>
                    </button>
                    <a href="{{ route('admin.documents.create') }}"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span>Upload Dokumen</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Dokumen</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Ukuran</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ number_format($stats['total_size'] / 1024 / 1024, 1) }} MB
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Upload Minggu Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['recent_uploads'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Kategori</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ count($stats['by_category']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('admin.documents.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Search --}}
                    <div class="lg:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari dokumen..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Category Filter --}}
                    <div>
                        <select name="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            <option value="">Semua Kategori</option>
                            <option value="proposal" {{ request('category') == 'proposal' ? 'selected' : '' }}>Proposal
                            </option>
                            <option value="report" {{ request('category') == 'report' ? 'selected' : '' }}>Laporan
                            </option>
                            <option value="meeting_notes" {{ request('category') == 'meeting_notes' ? 'selected' : '' }}>
                                Notulen</option>
                            <option value="contract" {{ request('category') == 'contract' ? 'selected' : '' }}>Kontrak
                            </option>
                            <option value="letter" {{ request('category') == 'letter' ? 'selected' : '' }}>Surat</option>
                            <option value="certificate" {{ request('category') == 'certificate' ? 'selected' : '' }}>
                                Sertifikat</option>
                            <option value="presentation" {{ request('category') == 'presentation' ? 'selected' : '' }}>
                                Presentasi</option>
                            <option value="photo" {{ request('category') == 'photo' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ request('category') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    </div>

                    {{-- Folder Filter --}}
                    <div>
                        <select name="folder_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            <option value="">Semua Folder</option>
                            @foreach ($folders as $folder)
                                <option value="{{ $folder->id }}"
                                    {{ request('folder_id') == $folder->id ? 'selected' : '' }}>
                                    {{ $folder->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Final</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0]">
                            Filter
                        </button>
                        <a href="{{ route('admin.documents.index') }}"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Reset
                        </a>
                    </div>

                    {{-- View Toggle --}}
                    <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                        <button type="button" @click="toggleView('grid')"
                            :class="viewMode === 'grid' ? 'bg-white shadow-sm' : ''"
                            class="p-2 rounded-md transition-all">
                            <svg class="w-5 h-5" :class="viewMode === 'grid' ? 'text-[#0053C5]' : 'text-gray-500'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                        <button type="button" @click="toggleView('list')"
                            :class="viewMode === 'list' ? 'bg-white shadow-sm' : ''"
                            class="p-2 rounded-md transition-all">
                            <svg class="w-5 h-5" :class="viewMode === 'list' ? 'text-[#0053C5]' : 'text-gray-500'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Documents Grid/List --}}
        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @forelse($documents as $document)
                <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow overflow-hidden">
                    {{-- Document Preview --}}
                    <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        @if ($document->isImage())
                            <img src="{{ $document->file_url }}" alt="{{ $document->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 mx-auto bg-white rounded-lg flex items-center justify-center mb-2 shadow">
                                    @if ($document->isPdf())
                                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4z" />
                                        </svg>
                                    @elseif($document->isDocument())
                                        <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4z" />
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4z" />
                                        </svg>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 uppercase">{{ $document->file_type }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Document Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-semibold text-gray-900 line-clamp-2">{{ $document->title }}</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap
                            @if ($document->category == 'proposal') bg-purple-100 text-purple-700
                            @elseif($document->category == 'report') bg-blue-100 text-blue-700
                            @elseif($document->category == 'contract') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($document->category) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">{{ $document->file_size_human }}</p>

                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ $document->view_count }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>{{ $document->download_count }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center gap-2">
                            <a href="{{ route('admin.documents.show', $document) }}"
                                class="flex-1 px-3 py-2 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#004AB0] text-center">
                                Lihat
                            </a>
                            @if ($document->allow_download)
                                <a href="{{ route('admin.documents.download', $document) }}"
                                    class="px-3 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada dokumen</h3>
                    <p class="text-gray-600 mb-4">Belum ada dokumen yang tersedia.</p>
                    <a href="{{ route('admin.documents.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Upload Dokumen Pertama
                    </a>
                </div>
            @endforelse
        </div>

        {{-- List View --}}
        <div x-show="viewMode === 'list'" x-cloak class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if ($document->isPdf())
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $document->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $document->document_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                    {{ ucfirst($document->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $document->file_size_human }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $document->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                @if ($document->status == 'final') bg-green-100 text-green-700
                                @elseif($document->status == 'draft') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($document->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.documents.show', $document) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if ($document->allow_download)
                                        <a href="{{ route('admin.documents.download', $document) }}"
                                            class="text-green-600 hover:text-green-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada dokumen</h3>
                                <p class="text-gray-600">Belum ada dokumen yang tersedia.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($documents->hasPages())
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
        @endif

        {{-- Create Folder Modal --}}
        <div x-show="showCreateFolder" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="showCreateFolder = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Buat Folder Baru</h3>
                    <form action="{{ route('admin.documents.create-folder') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Folder</label>
                                <input type="text" name="name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Visibilitas</label>
                                <select name="visibility"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="restricted">Restricted</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="showCreateFolder = false"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0]">
                                Buat Folder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
