@extends('admin.layouts.app')

@section('title', 'Upload Dokumen Baru')

@section('content')
    <div x-data="{
        fileSelected: false,
        fileName: '',
        fileSize: '',
        fileType: '',
        previewUrl: null,
        tags: [],
        tagInput: '',
        selectedUsers: [],
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
        },
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileSelected = true;
                this.fileName = file.name;
                this.fileSize = this.formatBytes(file.size);
                this.fileType = file.type;
    
                // Auto-fill title if empty
                const titleInput = document.getElementById('title');
                if (!titleInput.value) {
                    titleInput.value = file.name.replace(/\.[^/.]+$/, '');
                }
    
                // Preview for images
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        },
        formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.documents.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Upload Dokumen Baru</h1>
                    <p class="text-gray-600 mt-1">Unggah dan kelola dokumen kegiatan Anda</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- File Upload Section --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">File Dokumen</h2>

                        <div class="mb-4">
                            <label
                                class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-[#0053C5] transition-colors"
                                for="file-upload">
                                <div x-show="!fileSelected">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-gray-700 font-medium mb-1">Klik untuk upload atau drag & drop</p>
                                    <p class="text-sm text-gray-500">PDF, DOC, XLS, PPT, Gambar, Video (Max. 50MB)</p>
                                </div>

                                <div x-show="fileSelected" x-cloak class="flex items-center gap-4">
                                    <template x-if="previewUrl">
                                        <img :src="previewUrl" class="w-20 h-20 object-cover rounded-lg">
                                    </template>
                                    <template x-if="!previewUrl">
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4z" />
                                            </svg>
                                        </div>
                                    </template>
                                    <div class="flex-1 text-left">
                                        <p class="font-medium text-gray-900" x-text="fileName"></p>
                                        <p class="text-sm text-gray-500" x-text="fileSize"></p>
                                    </div>
                                    <button type="button" @click="fileSelected = false; previewUrl = null"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <input type="file" id="file-upload" name="file" class="hidden" required
                                    @change="handleFileSelect"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.avi">
                            </label>
                            @error('file')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Document Information --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dokumen</h2>

                        <div class="space-y-4">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Dokumen <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
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
                                    placeholder="Deskripsi singkat tentang dokumen ini">{{ old('description') }}</textarea>
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
                                                {{ old('category') == $key ? 'selected' : '' }}>
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
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="final" {{ old('status', 'final') == 'final' ? 'selected' : '' }}>
                                            Final</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                                            Archived</option>
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
                                    <input type="date" name="document_date" value="{{ old('document_date') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    @error('document_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kedaluwarsa</label>
                                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
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
                                <input type="hidden" id="tags-input" name="tags" :value="tags.join(',')">
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]"
                                    placeholder="Catatan tambahan tentang dokumen ini">{{ old('notes') }}</textarea>
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
                                            {{ old('folder_id', $selectedFolder) == $folder->id ? 'selected' : '' }}>
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
                                            {{ old('event_id', $selectedEvent) == $event->id ? 'selected' : '' }}>
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
                                        {{ old('visibility', 'private') == 'private' ? 'selected' : '' }}>
                                        Private - Hanya saya</option>
                                    <option value="restricted" {{ old('visibility') == 'restricted' ? 'selected' : '' }}>
                                        Restricted - Pengguna tertentu</option>
                                    <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public -
                                        Semua orang</option>
                                </select>
                            </div>

                            {{-- Permissions Checkboxes --}}
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="allow_download" value="1"
                                        {{ old('allow_download', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                    <span class="text-sm text-gray-700">Izinkan Download</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="allow_print" value="1"
                                        {{ old('allow_print', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                    <span class="text-sm text-gray-700">Izinkan Print</span>
                                </label>
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
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload Dokumen
                            </button>
                            <a href="{{ route('admin.documents.index') }}"
                                class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center block">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
