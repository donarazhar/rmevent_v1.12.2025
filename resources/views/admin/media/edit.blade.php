@extends('admin.layouts.app')

@section('title', 'Edit Media')

@section('content')
    <div class="max-w-5xl mx-auto">
        {{-- Header & Breadcrumb --}}
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('admin.media.index') }}" class="hover:text-primary">Media Library</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900">Edit Media</span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Media</h1>
                    <p class="mt-1 text-sm text-gray-600">Perbarui informasi dan metadata media</p>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-medium">Terdapat kesalahan pada form:</p>
                        <ul class="mt-2 text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.media.update', $media) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Form - Left Column --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Basic Information Card --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                            <div class="space-y-4">
                                {{-- Title --}}
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Title <span class="text-gray-400 font-normal">(opsional)</span>
                                    </label>
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $media->title) }}" placeholder="Masukkan judul media..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-300 @enderror">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Judul deskriptif untuk media ini</p>
                                </div>

                                {{-- Description --}}
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Description <span class="text-gray-400 font-normal">(opsional)</span>
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                        placeholder="Tuliskan deskripsi singkat tentang media ini..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('description') border-red-300 @enderror">{{ old('description', $media->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Deskripsi detail tentang konten media</p>
                                </div>

                                {{-- Alt Text --}}
                                @if ($media->isImage())
                                    <div>
                                        <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">
                                            Alt Text <span class="text-gray-400 font-normal">(opsional, tapi
                                                disarankan)</span>
                                        </label>
                                        <input type="text" name="alt_text" id="alt_text"
                                            value="{{ old('alt_text', $media->alt_text) }}"
                                            placeholder="Contoh: Kegiatan buka puasa bersama di Masjid Agung"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('alt_text') border-red-300 @enderror">
                                        @error('alt_text')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-sm text-gray-500">Teks alternatif untuk accessibility dan SEO
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SEO Tips Card --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Tips Optimasi SEO & Accessibility</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Gunakan <strong>title</strong> yang deskriptif dan mengandung keyword</li>
                                        <li>Isi <strong>alt text</strong> dengan kalimat yang menjelaskan gambar</li>
                                        <li>Alt text membantu screen reader untuk accessibility</li>
                                        <li>Description bagus untuk SEO dan konteks konten</li>
                                        <li>Hindari keyword stuffing, gunakan bahasa natural</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar - Right Column --}}
                <div class="space-y-6">
                    {{-- Preview Card --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Preview Media</h3>
                            <div class="bg-gray-100 rounded-lg overflow-hidden">
                                @if ($media->isImage())
                                    <img src="{{ $media->url }}" alt="{{ $media->alt_text ?? $media->title }}"
                                        class="w-full h-auto">
                                @elseif($media->isVideo())
                                    <div class="aspect-video flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="aspect-square flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- File Info Card --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">File Information</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">File Name:</span>
                                    <span
                                        class="font-medium text-gray-900 text-right truncate ml-2">{{ $media->file_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">File Size:</span>
                                    <span class="font-medium text-gray-900">{{ $media->file_size_human }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type:</span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    {{ $media->file_type === 'image' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $media->file_type === 'video' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $media->file_type === 'document' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ strtoupper($media->file_type) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Collection:</span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($media->collection) }}
                                    </span>
                                </div>
                                @if ($media->width && $media->height)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Dimensions:</span>
                                        <span class="font-medium text-gray-900">{{ $media->width }} x
                                            {{ $media->height }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Uploaded:</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $media->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.media.show', $media) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </a>

                                <a href="{{ $media->url }}" target="_blank" download
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.media.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>

                    <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Media
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <button type="reset"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>

                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Media
                    </button>
                </div>
            </div>
        </form>

        {{-- Delete Form (Hidden) --}}
        <form id="deleteForm" action="{{ route('admin.media.destroy', $media) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    @push('scripts')
        <script>
            function confirmDelete() {
                if (confirm(
                        'Apakah Anda yakin ingin menghapus media ini? File akan dihapus secara permanen dan tidak dapat dikembalikan.'
                    )) {
                    document.getElementById('deleteForm').submit();
                }
            }
        </script>
    @endpush
@endsection
