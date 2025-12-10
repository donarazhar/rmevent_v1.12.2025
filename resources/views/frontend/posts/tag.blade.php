@extends('layouts.app')

@section('title', '#' . $tag->name . ' - Artikel Ramadhan 1447 H')

@section('content')
    {{-- ============================================================================ --}}
    {{-- TAG HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center text-white">
                {{-- Breadcrumb --}}
                <nav class="flex items-center justify-center space-x-2 text-sm text-blue-100 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('posts.index') }}" class="hover:text-white">Artikel</a>
                    <span>/</span>
                    <span class="text-white font-semibold">#{{ $tag->name }}</span>
                </nav>

                {{-- Tag Icon --}}
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"
                    style="background-color: {{ $tag->color }}20; border: 3px solid {{ $tag->color }};">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>

                {{-- Tag Name --}}
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    #{{ $tag->name }}
                </h1>

                {{-- Tag Description --}}
                @if ($tag->description)
                    <p class="text-xl text-blue-100 mb-6 leading-relaxed">
                        {{ $tag->description }}
                    </p>
                @endif

                {{-- Posts Count --}}
                <div class="inline-block px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full text-white font-semibold">
                    {{ $posts->total() }} Artikel dengan Tag Ini
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- POSTS GRID --}}
    {{-- ============================================================================ --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($posts as $post)
                        <article
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all group">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                <div class="relative h-56 overflow-hidden">
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                                    {{-- Category Badge --}}
                                    @if ($post->category)
                                        <div class="absolute top-3 left-3">
                                            <span
                                                class="px-3 py-1 bg-[#0053C5] text-white rounded-full text-xs font-semibold">
                                                {{ $post->category->name }}
                                            </span>
                                        </div>
                                    @endif

                                    @if ($post->is_featured)
                                        <div class="absolute top-3 right-3">
                                            <span class="text-2xl">⭐</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    {{-- Meta --}}
                                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-3">
                                        <span>{{ $post->published_at->format('d M Y') }}</span>
                                        <span>•</span>
                                        <span>{{ $post->reading_time }} min read</span>
                                        <span>•</span>
                                        <span>{{ number_format($post->views_count) }} views</span>
                                    </div>

                                    {{-- Title --}}
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#0053C5] transition-colors line-clamp-2">
                                        {{ $post->title }}
                                    </h3>

                                    {{-- Excerpt --}}
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ $post->excerpt_or_content }}
                                    </p>

                                    {{-- Author --}}
                                    <div class="flex items-center gap-2 text-sm text-gray-600 pt-4 border-t border-gray-100">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-r from-[#0053C5] to-[#003d8f] flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium">{{ $post->author->name }}</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Artikel</h3>
                    <p class="text-gray-600 mb-6">Belum ada artikel dengan tag ini.</p>
                    <a href="{{ route('posts.index') }}"
                        class="inline-block px-6 py-3 bg-[#0053C5] text-white rounded-full font-semibold hover:bg-[#003d8f] transition-all">
                        Lihat Semua Artikel
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
