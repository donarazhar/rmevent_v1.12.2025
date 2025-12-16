@extends('admin.layouts.app')

@section('title', 'Detail Template')

@section('content')
    <div>
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.templates.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $template->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $template->template_code }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.templates.download', $template) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download</span>
                    </a>

                    <form action="{{ route('admin.templates.duplicate', $template) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Duplicate</span>
                        </button>
                    </form>

                    <a href="{{ route('admin.templates.edit', $template) }}"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </a>

                    <form action="{{ route('admin.templates.destroy', $template) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Status & Category Badges --}}
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $template->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($template->status) }}
                </span>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700">
                    {{ ucfirst($template->category) }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                    {{ strtoupper($template->file_type) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- File Preview Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Preview Template</h3>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                    {{ strtoupper($template->file_type) }}
                                </span>
                                <a href="{{ route('admin.templates.download', $template) }}"
                                    class="px-3 py-1.5 bg-[#0053C5] text-white text-xs font-medium rounded-lg hover:bg-[#003d8f] flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @php
                            $fileUrl = Storage::url($template->file_path);
                            $fullUrl = url($fileUrl);
                        @endphp

                        @if ($template->file_type === 'pdf')
                            {{-- PDF Preview --}}
                            <div class="space-y-3">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-900">PDF Preview</p>
                                        <p class="text-xs text-blue-700 mt-1">Preview ditampilkan langsung di browser.</p>
                                    </div>
                                </div>

                                <div class="border-2 border-gray-200 rounded-lg overflow-hidden bg-gray-50"
                                    style="height: 800px;">
                                    <iframe src="{{ $fullUrl }}" type="application/pdf" width="100%" height="100%"
                                        style="border: none;">
                                    </iframe>
                                </div>
                            </div>
                        @elseif(in_array($template->file_type, ['docx', 'xlsx', 'pptx']))
                            {{-- Office Documents Preview --}}
                            <div x-data="{
                                viewerType: 'google',
                                loading: true,
                                googleUrl: 'https://docs.google.com/viewer?url={{ urlencode($fullUrl) }}&embedded=true',
                                officeUrl: 'https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($fullUrl) }}'
                            }" class="space-y-3">

                                {{-- Viewer Selection --}}
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">Preview Options</p>
                                    <p class="text-xs text-gray-600 mb-3">Pilih viewer untuk melihat preview template.</p>

                                    <div class="flex flex-wrap gap-2">
                                        <button @click="viewerType = 'google'; loading = true"
                                            :class="viewerType === 'google' ? 'bg-[#0053C5] text-white' :
                                                'bg-white text-gray-700'"
                                            class="px-4 py-2 rounded-lg text-xs font-medium border transition-colors">
                                            Google Docs Viewer
                                        </button>

                                        <button @click="viewerType = 'microsoft'; loading = true"
                                            :class="viewerType === 'microsoft' ? 'bg-[#0053C5] text-white' :
                                                'bg-white text-gray-700'"
                                            class="px-4 py-2 rounded-lg text-xs font-medium border transition-colors">
                                            Microsoft Office Online
                                        </button>

                                        <a href="{{ route('admin.templates.download', $template) }}"
                                            class="px-4 py-2 rounded-lg text-xs font-medium border bg-green-50 text-green-700 hover:bg-green-100">
                                            Download File
                                        </a>
                                    </div>
                                </div>

                                {{-- Loading State --}}
                                <div x-show="loading"
                                    class="border-2 border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center"
                                    style="height: 800px;">
                                    <div class="text-center">
                                        <div
                                            class="inline-block w-12 h-12 border-4 border-[#0053C5] border-t-transparent rounded-full animate-spin mb-4">
                                        </div>
                                        <p class="text-sm text-gray-600">Loading preview...</p>
                                    </div>
                                </div>

                                {{-- Google Docs Viewer --}}
                                <div x-show="viewerType === 'google'"
                                    class="border-2 border-gray-200 rounded-lg overflow-hidden" style="height: 800px;">
                                    <iframe :src="googleUrl" width="100%" height="100%" style="border: none;"
                                        @load="loading = false">
                                    </iframe>
                                </div>

                                {{-- Microsoft Office Online Viewer --}}
                                <div x-show="viewerType === 'microsoft'"
                                    class="border-2 border-gray-200 rounded-lg overflow-hidden" style="height: 800px;">
                                    <iframe :src="officeUrl" width="100%" height="100%" style="border: none;"
                                        @load="loading = false">
                                    </iframe>
                                </div>
                            </div>
                        @else
                            {{-- Fallback for other file types --}}
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center bg-gray-50">
                                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <p class="text-lg font-semibold text-gray-700 mb-2">Preview Tidak Tersedia</p>
                                <p class="text-sm text-gray-600 mb-4">File type {{ strtoupper($template->file_type) }}
                                    tidak support preview.</p>
                                <a href="{{ route('admin.templates.download', $template) }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] font-medium">
                                    Download untuk Melihat
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Preview Image --}}
                @if ($template->preview_image)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ Storage::url($template->preview_image) }}" alt="{{ $template->name }}"
                            class="w-full h-64 object-cover">
                    </div>
                @endif

                {{-- Description --}}
                @if ($template->description)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $template->description }}</p>
                    </div>
                @endif

                {{-- Usage Instructions --}}
                @if ($template->usage_instructions)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Petunjuk Penggunaan</h3>
                        <div class="prose max-w-none text-sm text-gray-700">
                            {!! nl2br(e($template->usage_instructions)) !!}
                        </div>
                    </div>
                @endif

                {{-- Variables --}}
                @php
                    $variables = is_array($template->variables)
                        ? $template->variables
                        : json_decode($template->variables, true);
                @endphp
                @if ($variables && count($variables) > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Variables</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($variables as $variable)
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-mono">
                                    {{ $variable }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tags --}}
                @php
                    $tags = is_array($template->tags) ? $template->tags : json_decode($template->tags, true);
                @endphp
                @if ($tags && count($tags) > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Statistics --}}
                <div class="bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-xl shadow-sm p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Statistics</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-white/20">
                            <span class="text-sm text-blue-100">Downloads</span>
                            <span class="text-2xl font-bold">{{ number_format($template->download_count) }}</span>
                        </div>

                        <div class="flex items-center justify-between pb-3 border-b border-white/20">
                            <span class="text-sm text-blue-100">Usage</span>
                            <span class="text-2xl font-bold">{{ number_format($template->usage_count) }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-100">File Size</span>
                            <span class="text-xl font-bold">{{ $template->file_size_formatted }}</span>
                        </div>
                    </div>
                </div>

                {{-- File Info --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">File Information</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Code</span>
                            <span class="text-gray-900 font-mono">{{ $template->template_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Type</span>
                            <span class="text-gray-900 font-semibold">{{ strtoupper($template->file_type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Category</span>
                            <span class="text-gray-900">{{ ucfirst($template->category) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Creator Info --}}
                @if ($template->creator)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dibuat Oleh</h3>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-[#0053C5] text-white rounded-full flex items-center justify-center font-semibold">
                                {{ substr($template->creator->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $template->creator->name }}</p>
                                <p class="text-xs text-gray-500">{{ $template->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-2">
                        <a href="{{ route('admin.templates.download', $template) }}"
                            class="w-full px-4 py-2.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors flex items-center justify-center gap-2 text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Template
                        </a>

                        <form action="{{ route('admin.templates.duplicate', $template) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors flex items-center justify-center gap-2 text-sm font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Duplicate Template
                            </button>
                        </form>

                        <a href="{{ route('admin.templates.edit', $template) }}"
                            class="w-full px-4 py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
