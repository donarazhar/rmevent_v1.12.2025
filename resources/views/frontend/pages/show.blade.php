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
                <article class="prose prose-lg prose-blue max-w-none">
                    {!! $page->content !!}
                </article>

                {{-- Page Sections (if any) --}}
                @if ($page->sections && count($page->sections) > 0)
                    <div class="mt-12 space-y-8">
                        @foreach ($page->sections as $section)
                            <div class="bg-white rounded-2xl shadow-lg p-8">
                                @if (isset($section['title']))
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $section['title'] }}</h2>
                                @endif
                                @if (isset($section['content']))
                                    <div class="prose prose-blue max-w-none">
                                        {!! $section['content'] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Page Media (if any) --}}
                @if ($page->hasMedia())
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
        }

        .prose blockquote {
            border-left: 4px solid #0053C5;
            padding-left: 1rem;
            font-style: italic;
            color: #6B7280;
        }
    </style>
@endpush
