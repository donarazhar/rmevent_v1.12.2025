@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('content')
    <div x-data="mediaManager()">
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Media Library</h1>
                    <p class="mt-1 text-sm text-gray-600">Kelola semua file gambar, video, dan dokumen</p>
                </div>
                <button @click="openUploadModal()"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload Media
                </button>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-medium">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Media</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $media->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Gambar</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ App\Models\Media::where('file_type', 'image')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Video</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ App\Models\Media::where('file_type', 'video')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Dokumen</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ App\Models\Media::where('file_type', 'document')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Search Section --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form action="{{ route('admin.media.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Media</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama file atau title..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    {{-- Type Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe File</label>
                        <select name="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Semua Tipe</option>
                            <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Gambar</option>
                            <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Dokumen
                            </option>
                        </select>
                    </div>

                    {{-- Collection Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Koleksi</label>
                        <select name="collection"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Semua Koleksi</option>
                            <option value="general" {{ request('collection') == 'general' ? 'selected' : '' }}>General
                            </option>
                            <option value="events" {{ request('collection') == 'events' ? 'selected' : '' }}>Events
                            </option>
                            <option value="posts" {{ request('collection') == 'posts' ? 'selected' : '' }}>Posts</option>
                            <option value="gallery" {{ request('collection') == 'gallery' ? 'selected' : '' }}>Gallery
                            </option>
                            <option value="sliders" {{ request('collection') == 'sliders' ? 'selected' : '' }}>Sliders
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.media.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Media Grid --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if ($media->count() > 0)
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($media as $item)
                            <div class="group relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-primary transition-all cursor-pointer"
                                @click="viewMedia({{ $item->id }}, '{{ $item->url }}', '{{ $item->file_type }}', '{{ $item->title }}', '{{ $item->file_size_human }}', '{{ $item->mime_type }}', '{{ $item->created_at->format('d M Y') }}')">

                                {{-- Media Preview --}}
                                <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                                    @if ($item->isImage())
                                        <img src="{{ $item->url }}" alt="{{ $item->title ?? $item->file_name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @elseif($item->isVideo())
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info Overlay --}}
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="text-white text-center p-2">
                                        <p class="text-sm font-medium truncate">{{ $item->title ?? $item->file_name }}</p>
                                        <p class="text-xs mt-1">{{ $item->file_size_human }}</p>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div
                                    class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex gap-1">
                                    <button
                                        @click.stop="editMedia({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->description) }}', '{{ addslashes($item->alt_text) }}')"
                                        class="p-1.5 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="deleteMedia({{ $item->id }})"
                                        class="p-1.5 bg-white rounded-lg shadow-lg hover:bg-red-50 transition-colors"
                                        title="Hapus">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $media->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada media</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan mengupload file baru.</p>
                    <div class="mt-6">
                        <button @click="openUploadModal()"
                            class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Media
                        </button>
                    </div>
                </div>
            @endif
        </div>

        {{-- Upload Modal --}}
        <div x-show="showUploadModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            @click.self="closeUploadModal()">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Upload Media</h3>
                        <button @click="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="uploadFiles()" enctype="multipart/form-data">
                        {{-- Collection Select --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Koleksi</label>
                            <select x-model="uploadCollection"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="general">General</option>
                                <option value="events">Events</option>
                                <option value="posts">Posts</option>
                                <option value="gallery">Gallery</option>
                                <option value="sliders">Sliders</option>
                            </select>
                        </div>

                        {{-- File Upload Area --}}
                        <div class="mb-4">
                            <label
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Click to upload</span> atau drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF, MP4, PDF up to 10MB</p>
                                </div>
                                <input type="file" class="hidden" multiple @change="handleFileSelect($event)"
                                    accept="image/*,video/*,.pdf,.doc,.docx">
                            </label>
                        </div>

                        {{-- Selected Files --}}
                        <div x-show="selectedFiles.length > 0" class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">
                                File terpilih: <span x-text="selectedFiles.length"></span>
                            </p>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                <template x-for="(file, index) in selectedFiles" :key="index">
                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg">
                                        <span class="text-sm text-gray-700 truncate" x-text="file.name"></span>
                                        <button type="button" @click="removeFile(index)"
                                            class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Upload Progress --}}
                        <div x-show="uploading" class="mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full transition-all duration-300"
                                    :style="`width: ${uploadProgress}%`"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Uploading... <span x-text="uploadProgress + '%'"></span>
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="closeUploadModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="selectedFiles.length === 0 || uploading"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!uploading">Upload</span>
                                <span x-show="uploading">Uploading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- View Modal --}}
        <div x-show="showViewModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
            @click.self="closeViewModal()">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Media Detail</h3>
                        <button @click="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Preview --}}
                        <div>
                            <div class="bg-gray-100 rounded-lg overflow-hidden">
                                <template x-if="viewMediaType === 'image'">
                                    <img :src="viewMediaUrl" :alt="viewMediaTitle" class="w-full h-auto">
                                </template>
                                <template x-if="viewMediaType === 'video'">
                                    <video :src="viewMediaUrl" controls class="w-full h-auto"></video>
                                </template>
                                <template x-if="viewMediaType === 'document'">
                                    <div class="flex items-center justify-center h-64">
                                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Title</label>
                                    <p class="text-gray-900" x-text="viewMediaTitle || '-'"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">File Size</label>
                                    <p class="text-gray-900" x-text="viewMediaSize"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">MIME Type</label>
                                    <p class="text-gray-900" x-text="viewMediaMimeType"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Upload Date</label>
                                    <p class="text-gray-900" x-text="viewMediaDate"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">URL</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" :value="viewMediaUrl" readonly
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
                                        <button @click="copyUrl(viewMediaUrl)"
                                            class="px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="showEditModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            @click.self="closeEditModal()">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full" @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Edit Media</h3>
                        <button @click="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="updateMedia()">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                <input type="text" x-model="editMediaTitle"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea x-model="editMediaDescription" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text (untuk
                                    gambar)</label>
                                <input type="text" x-model="editMediaAltText"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-6">
                            <button type="button" @click="closeEditModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="updating"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-50">
                                <span x-show="!updating">Update</span>
                                <span x-show="updating">Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function mediaManager() {
                return {
                    // Upload Modal
                    showUploadModal: false,
                    selectedFiles: [],
                    uploadCollection: 'general',
                    uploading: false,
                    uploadProgress: 0,

                    // View Modal
                    showViewModal: false,
                    viewMediaId: null,
                    viewMediaUrl: '',
                    viewMediaType: '',
                    viewMediaTitle: '',
                    viewMediaSize: '',
                    viewMediaMimeType: '',
                    viewMediaDate: '',

                    // Edit Modal
                    showEditModal: false,
                    editMediaId: null,
                    editMediaTitle: '',
                    editMediaDescription: '',
                    editMediaAltText: '',
                    updating: false,

                    openUploadModal() {
                        this.showUploadModal = true;
                    },

                    closeUploadModal() {
                        this.showUploadModal = false;
                        this.selectedFiles = [];
                        this.uploading = false;
                        this.uploadProgress = 0;
                    },

                    handleFileSelect(event) {
                        this.selectedFiles = Array.from(event.target.files);
                    },

                    removeFile(index) {
                        this.selectedFiles.splice(index, 1);
                    },

                    async uploadFiles() {
                        if (this.selectedFiles.length === 0) return;

                        this.uploading = true;
                        this.uploadProgress = 0;

                        const formData = new FormData();
                        this.selectedFiles.forEach(file => {
                            formData.append('files[]', file);
                        });
                        formData.append('collection', this.uploadCollection);

                        try {
                            const response = await fetch('{{ route('admin.media.upload') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (data.success) {
                                this.uploadProgress = 100;

                                // ✅ AUTO RELOAD AFTER SUCCESS
                                setTimeout(() => {
                                    window.location.reload();
                                }, 500);
                            } else {
                                alert('Upload gagal: ' + data.message);
                                this.uploading = false;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat upload');
                            this.uploading = false;
                        }
                    },

                    viewMedia(id, url, type, title, size, mimeType, date) {
                        this.viewMediaId = id;
                        this.viewMediaUrl = url;
                        this.viewMediaType = type;
                        this.viewMediaTitle = title;
                        this.viewMediaSize = size;
                        this.viewMediaMimeType = mimeType;
                        this.viewMediaDate = date;
                        this.showViewModal = true;
                    },

                    closeViewModal() {
                        this.showViewModal = false;
                    },

                    editMedia(id, title, description, altText) {
                        this.editMediaId = id;
                        this.editMediaTitle = title || '';
                        this.editMediaDescription = description || '';
                        this.editMediaAltText = altText || '';
                        this.showEditModal = true;
                        this.updating = false;
                    },

                    closeEditModal() {
                        this.showEditModal = false;
                        this.updating = false;
                    },

                    async updateMedia() {
                        if (this.updating) return;

                        this.updating = true;

                        try {
                            const response = await fetch(`/admin/media/${this.editMediaId}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    title: this.editMediaTitle,
                                    description: this.editMediaDescription,
                                    alt_text: this.editMediaAltText
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                // ✅ AUTO RELOAD AFTER SUCCESS
                                window.location.reload();
                            } else {
                                alert('Update gagal: ' + (data.message || 'Unknown error'));
                                this.updating = false;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan: ' + error.message);
                            this.updating = false;
                        }
                    },

                    async deleteMedia(id) {
                        if (!confirm(
                                'Apakah Anda yakin ingin menghapus media ini? File akan dihapus secara permanen.'
                            )) {
                            return;
                        }

                        try {
                            const response = await fetch(`/admin/media/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json',
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                // ✅ AUTO RELOAD AFTER SUCCESS
                                window.location.reload();
                            } else {
                                alert('Hapus gagal: ' + (data.message || 'Unknown error'));
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan: ' + error.message);
                        }
                    },

                    copyUrl(url) {
                        navigator.clipboard.writeText(url).then(() => {
                            alert('URL berhasil dicopy!');
                        });
                    }
                }
            }
        </script>
    @endpush
@endsection
