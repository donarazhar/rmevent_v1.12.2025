@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')
    {{-- ============================================================================ --}}
    {{-- PAGE HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                {{-- Breadcrumb --}}
                <nav class="flex items-center justify-center space-x-2 text-sm text-blue-100 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <span class="text-white font-semibold">{{ $page->title }}</span>
                </nav>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $page->title }}</h1>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- PAGE CONTENT --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Main Content --}}
                @if ($page->content)
                    <article class="prose prose-lg prose-blue max-w-none">
                        {!! $page->content !!}
                    </article>
                @endif

                {{-- Page Sections (if any) --}}
                @if (!empty($page->sections) && is_array($page->sections))
                    <div class="mt-12 space-y-8">
                        @foreach ($page->sections as $section)
                            <div class="bg-white rounded-2xl shadow-lg p-8">
                                @if (isset($section['title']) && $section['title'])
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $section['title'] }}</h2>
                                @endif
                                @if (isset($section['content']) && $section['content'])
                                    <div class="prose prose-blue max-w-none">
                                        {!! $section['content'] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Page Media (if any) --}}
                @if (method_exists($page, 'hasMedia') && $page->hasMedia())
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Galeri</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($page->getMedia() as $media)
                                <div class="rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all">
                                    <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}"
                                        class="w-full h-48 object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Empty State if no content --}}
                @if (!$page->content && (empty($page->sections) || !is_array($page->sections)))
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Content Coming Soon</h3>
                        <p class="text-gray-600">This page is being prepared. Please check back later.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Prose Styling */
        .prose {
            color: #374151;
        }

        .prose h2 {
            color: #0053C5;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .prose h3 {
            color: #003d8f;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .prose p {
            margin-bottom: 1rem;
            line-height: 1.75;
        }

        .prose a {
            color: #0053C5;
            text-decoration: underline;
        }

        .prose a:hover {
            color: #003d8f;
        }

        .prose img {
            border-radius: 0.75rem;
            margin: 1.5rem auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .prose blockquote {
            border-left: 4px solid #0053C5;
            padding-left: 1rem;
            font-style: italic;
            color: #6B7280;
            margin: 1.5rem 0;
        }

        .prose ul,
        .prose ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose table {
            width: 100%;
            margin: 1.5rem 0;
            border-collapse: collapse;
        }

        .prose th,
        .prose td {
            padding: 0.75rem;
            border: 1px solid #E5E7EB;
        }

        .prose th {
            background-color: #F9FAFB;
            font-weight: 600;
        }
    </style>
@endpush
