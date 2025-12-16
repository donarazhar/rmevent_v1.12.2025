@extends('admin.layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
    <div x-data="{
        tags: @js($document->tags ?? []),
        tagInput: '',
        addTag() {
            if (this.tagInput.trim() !== '') {
                this.tags.push(this.tagInput.trim());
                this.tagInput = '';
                this.updateTagsInput();
            }
        },
        removeTag(index) {
            this.tags.splice(index, 1);
            this.updateTagsInput();
        },
        updateTagsInput() {
            document.getElementById('tags-input').value = this.tags.join(',');
        }
    }" x-init="updateTagsInput()">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.documents.show', $document) }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Dokumen</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi dokumen</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.documents.update', $document) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Current File Info --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">File Dokumen</h2>

                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            <div
                                class="w-16 h-16 bg-white rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                @if ($document->isPdf())
                                    <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4z" />
                                    </svg>
                                @elseif($document->isImage())
                                    <img src="{{ $document->file_url }}" alt="{{ $document->title }}"
                                        class="w-full h-full object-cover rounded-lg">
                                @else
                                    <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $document->file_name }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ strtoupper($document->file_type) }} • {{ $document->file_size_human }}
                                </p>
                            </div>
                            <a href="{{ route('admin.documents.show', $document) }}"
                                class="px-3 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                                Lihat File
                            </a>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">Ingin mengganti file?</p>
                                    <p>Gunakan fitur "Upload Versi Baru" di halaman detail dokumen untuk menjaga riwayat
                                        versi.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Document Information --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dokumen</h2>

                        <div class="space-y-4">
                            {{-- Document Code (Read Only) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Dokumen</label>
                                <input type="text" value="{{ $document->document_code }}" disabled
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>

                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Dokumen <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title', $document->title) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Masukkan judul dokumen">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Deskripsi singkat tentang dokumen ini">{{ old('description', $document->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Category & Status --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <select name="category" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('category', $document->category) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                        <option value="draft"
                                            {{ old('status', $document->status) == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="final"
                                            {{ old('status', $document->status) == 'final' ? 'selected' : '' }}>Final
                                        </option>
                                        <option value="archived"
                                            {{ old('status', $document->status) == 'archived' ? 'selected' : '' }}>
                                            Archived
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Dates --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dokumen</label>
                                    <input type="date" name="document_date"
                                        value="{{ old('document_date', $document->document_date?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    @error('document_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kedaluwarsa</label>
                                    <input type="date" name="expiry_date"
                                        value="{{ old('expiry_date', $document->expiry_date?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    @error('expiry_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tags --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="removeTag(index)" class="hover:text-blue-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" x-model="tagInput" @keydown.enter.prevent="addTag()"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]"
                                        placeholder="Ketik tag dan tekan Enter">
                                    <button type="button" @click="addTag()"
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                        Tambah
                                    </button>
                                </div>
                                <input type="hidden" id="tags-input" name="tags">
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]"
                                    placeholder="Catatan tambahan tentang dokumen ini">{{ old('notes', $document->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Organization --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Organisasi</h2>

                        <div class="space-y-4">
                            {{-- Folder --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Folder</label>
                                <select name="folder_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    <option value="">Root Folder</option>
                                    @foreach ($folders as $folder)
                                        <option value="{{ $folder->id }}"
                                            {{ old('folder_id', $document->folder_id) == $folder->id ? 'selected' : '' }}>
                                            {{ $folder->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Event --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                                <select name="event_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    <option value="">Tidak ada event</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id', $document->event_id) == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Permissions --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Izin & Akses</h2>

                        <div class="space-y-4">
                            {{-- Visibility --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Visibilitas <span class="text-red-500">*</span>
                                </label>
                                <select name="visibility" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    <option value="private"
                                        {{ old('visibility', $document->visibility) == 'private' ? 'selected' : '' }}>
                                        Private - Hanya saya
                                    </option>
                                    <option value="restricted"
                                        {{ old('visibility', $document->visibility) == 'restricted' ? 'selected' : '' }}>
                                        Restricted - Pengguna tertentu
                                    </option>
                                    <option value="public"
                                        {{ old('visibility', $document->visibility) == 'public' ? 'selected' : '' }}>
                                        Public - Semua orang
                                    </option>
                                </select>
                            </div>

                            {{-- Permissions Checkboxes --}}
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="allow_download" value="1"
                                        {{ old('allow_download', $document->allow_download) ? 'checked' : '' }}
                                        class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                    <span class="text-sm text-gray-700">Izinkan Download</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="allow_print" value="1"
                                        {{ old('allow_print', $document->allow_print) ? 'checked' : '' }}
                                        class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                    <span class="text-sm text-gray-700">Izinkan Print</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Version Info --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Versi</h2>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Versi Saat Ini</span>
                                <span class="font-semibold text-gray-900">{{ $document->version }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Total Versi</span>
                                <span class="font-semibold text-gray-900">{{ $document->versions->count() + 1 }}</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Terakhir diupdate</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $document->updated_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Statistics --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h2>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="text-sm">Total Views</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $document->view_count }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span class="text-sm">Total Downloads</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $document->download_count }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full px-4 py-3 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.documents.show', $document) }}"
                                class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center block">
                                Batal
                            </a>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500 text-center">
                                Dokumen terakhir diedit {{ $document->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
