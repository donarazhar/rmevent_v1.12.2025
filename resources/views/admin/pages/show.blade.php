@extends('admin.layouts.app')

@section('title', 'Page Details - ' . $page->title)

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-600">Frontend Management</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900">Pages</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-semibold">{{ $page->title }}</span>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $page->title }}</h2>
                    @if ($page->is_homepage)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                            Homepage
                        </span>
                    @endif
                    @if ($page->status === 'published')
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                            Published
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">
                            Draft
                        </span>
                    @endif
                </div>
                <p class="text-gray-600">View page details and content</p>
            </div>
            <div class="flex items-center space-x-3">
                {{-- View on Frontend --}}
                <a href="{{ route('pages.show', $page->slug) }}" target="_blank"
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    View Live
                </a>

                {{-- Edit Button --}}
                <a href="{{ route('admin.pages.edit', $page) }}"
                    class="px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Page
                </a>

                {{-- Back Button --}}
                <a href="{{ route('admin.pages.index') }}"
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Page Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Page Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Title</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $page->title }}</p>
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Slug (URL)</label>
                            <div class="flex items-center space-x-2">
                                <code class="text-sm text-gray-900 bg-gray-100 px-3 py-2 rounded-lg">
                                    {{ url('/pages/' . $page->slug) }}
                                </code>
                                <button onclick="copyToClipboard('{{ url('/pages/' . $page->slug) }}')"
                                    class="p-2 text-gray-600 hover:text-[#0053C5] hover:bg-gray-100 rounded-lg transition-colors"
                                    title="Copy URL">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Template --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Template</label>
                            <span
                                class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                                {{ ucfirst($page->template) }}
                            </span>
                        </div>

                        {{-- Parent Page --}}
                        @if ($page->parent)
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">Parent Page</label>
                                <a href="{{ route('admin.pages.show', $page->parent) }}"
                                    class="text-[#0053C5] hover:text-[#003d8f] font-semibold">
                                    {{ $page->parent->title }}
                                </a>
                            </div>
                        @endif

                        {{-- Children Pages --}}
                        @if ($page->children->count() > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Child Pages</label>
                                <div class="space-y-2">
                                    @foreach ($page->children as $child)
                                        <a href="{{ route('admin.pages.show', $child) }}"
                                            class="block px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                            <span class="text-gray-900 font-medium">{{ $child->title }}</span>
                                            <span
                                                class="ml-2 text-xs {{ $child->status === 'published' ? 'text-green-600' : 'text-yellow-600' }}">
                                                ({{ ucfirst($child->status) }})
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Page Content --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Page Content</h3>
                    </div>
                    <div class="p-6">
                        @if ($page->content)
                            <div class="prose prose-blue max-w-none">
                                {!! $page->content !!}
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>No content available</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SEO Information --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">SEO Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Meta Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Meta Title</label>
                            <p class="text-gray-900">
                                {{ $page->meta_title ?: $page->title }}
                            </p>
                            @if (!$page->meta_title)
                                <p class="text-xs text-gray-500 mt-1">(Using page title)</p>
                            @endif
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Meta Description</label>
                            <p class="text-gray-900">
                                {{ $page->meta_description ?: 'No meta description set' }}
                            </p>
                        </div>

                        {{-- Meta Keywords --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Meta Keywords</label>
                            @if ($page->meta_keywords)
                                <div class="flex flex-wrap gap-2">
                                    @foreach (explode(',', $page->meta_keywords) as $keyword)
                                        <span
                                            class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ trim($keyword) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No keywords set</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Sidebar --}}
            <div class="space-y-6">
                {{-- Status Card --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status & Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Status --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Status</span>
                            @if ($page->status === 'published')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                    Published
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">
                                    Draft
                                </span>
                            @endif
                        </div>

                        {{-- Display Order --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Display Order</span>
                            <span class="text-gray-900 font-bold">{{ $page->order }}</span>
                        </div>

                        {{-- Show in Menu --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Show in Menu</span>
                            @if ($page->show_in_menu)
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Is Homepage --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Homepage</span>
                            @if ($page->is_homepage)
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Timestamps Card --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Timestamps</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Created</label>
                            <p class="text-sm text-gray-900">{{ $page->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $page->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $page->updated_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $page->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        {{-- Edit --}}
                        <a href="{{ route('admin.pages.edit', $page) }}"
                            class="flex items-center px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Page
                        </a>

                        {{-- View Live --}}
                        <a href="{{ route('pages.show', $page->slug) }}" target="_blank"
                            class="flex items-center px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View on Frontend
                        </a>

                        {{-- Delete --}}
                        @unless ($page->is_homepage)
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Page
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success feedback
                alert('URL copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Prose Styling for Content Preview */
        .prose {
            color: #374151;
        }

        .prose h2 {
            color: #0053C5;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .prose h3 {
            color: #003d8f;
            font-weight: 600;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose a {
            color: #0053C5;
            text-decoration: underline;
        }

        .prose ul,
        .prose ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }
    </style>
@endpush
