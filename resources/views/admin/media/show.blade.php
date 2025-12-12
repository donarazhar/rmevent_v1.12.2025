@extends('admin.layouts.app')

@section('title', 'Detail Media - ' . ($media->title ?? $media->file_name))

@section('content')
    <div class="max-w-6xl mx-auto">
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
                <span class="text-gray-900">Detail Media</span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $media->title ?? $media->file_name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Detail lengkap informasi media</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.media.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('admin.media.edit', $media) }}"
                        class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Media
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content - Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Media Preview Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview Media</h3>
                        <div class="bg-gray-100 rounded-lg overflow-hidden">
                            @if ($media->isImage())
                                <img src="{{ $media->url }}" alt="{{ $media->alt_text ?? $media->title }}"
                                    class="w-full h-auto max-h-[500px] object-contain mx-auto">
                            @elseif($media->isVideo())
                                <video controls class="w-full h-auto max-h-[500px] mx-auto">
                                    <source src="{{ $media->url }}" type="{{ $media->mime_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                                    <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm font-medium">{{ strtoupper($media->file_type) }} File</p>
                                    <a href="{{ $media->url }}" target="_blank"
                                        class="mt-2 text-primary hover:underline text-sm">
                                        Download File
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- File Information Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi File</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama File</label>
                                <p class="text-gray-900">{{ $media->file_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran File</label>
                                <p class="text-gray-900">{{ $media->file_size_human }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe File</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $media->file_type === 'image' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $media->file_type === 'video' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $media->file_type === 'document' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ strtoupper($media->file_type) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">MIME Type</label>
                                <p class="text-gray-900 text-sm">{{ $media->mime_type }}</p>
                            </div>
                            @if ($media->width && $media->height)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dimensi</label>
                                    <p class="text-gray-900">{{ $media->width }} x {{ $media->height }} px</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Koleksi</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($media->collection) }}
                                </span>
                            </div>
                        </div>

                        @if ($media->title || $media->description || $media->alt_text)
                            <hr class="my-4">
                            <div class="space-y-3">
                                @if ($media->title)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                        <p class="text-gray-900">{{ $media->title }}</p>
                                    </div>
                                @endif
                                @if ($media->description)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <p class="text-gray-900">{{ $media->description }}</p>
                                    </div>
                                @endif
                                @if ($media->alt_text)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                                        <p class="text-gray-900">{{ $media->alt_text }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- URL & Embed Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">URL & Embed Code</h3>
                        <div class="space-y-4">
                            {{-- Direct URL --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Direct URL</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" value="{{ $media->url }}" readonly
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono">
                                    <button onclick="copyToClipboard('{{ $media->url }}')"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            @if ($media->isImage())
                                {{-- HTML Image Tag --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">HTML Image Tag</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text"
                                            value='<img src="{{ $media->url }}" alt="{{ $media->alt_text ?? $media->title }}">'
                                            readonly
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono">
                                        <button
                                            onclick="copyToClipboard('<img src=&quot;{{ $media->url }}&quot; alt=&quot;{{ $media->alt_text ?? $media->title }}&quot;>')"
                                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Markdown --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Markdown</label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" value='![{{ $media->title }}]({{ $media->url }})'
                                            readonly
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono">
                                        <button onclick="copyToClipboard('![{{ $media->title }}]({{ $media->url }})')"
                                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar - Right Column --}}
            <div class="space-y-6">
                {{-- Meta Information Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Meta Information</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Diupload Oleh</p>
                                    <p class="text-gray-900">{{ $media->uploader->name ?? 'Unknown' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tanggal Upload</p>
                                    <p class="text-gray-900">{{ $media->created_at->format('d M Y, H:i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $media->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Terakhir Diupdate</p>
                                    <p class="text-gray-900">{{ $media->updated_at->format('d M Y, H:i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $media->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Storage Disk</p>
                                    <p class="text-gray-900">{{ $media->disk }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ $media->url }}" target="_blank" download
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download File
                            </a>

                            <a href="{{ route('admin.media.edit', $media) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Media
                            </a>

                            <button onclick="confirmDelete()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Media
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Usage Information --}}
                @if ($media->mediable)
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
                                <h3 class="text-sm font-medium text-blue-800">Media Usage</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Media ini digunakan pada:</p>
                                    <p class="font-medium mt-1">{{ class_basename($media->mediable_type) }}
                                        #{{ $media->mediable_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Delete Form (Hidden) --}}
        <form id="deleteForm" action="{{ route('admin.media.destroy', $media) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    @push('scripts')
        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    // Show success notification
                    const notification = document.createElement('div');
                    notification.className =
                        'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    notification.textContent = 'Berhasil dicopy ke clipboard!';
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.remove();
                    }, 2000);
                });
            }

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
