@extends('layouts.app')

@section('title', 'Artikel & Blog - Ramadhan 1447 H')

@section('content')
    {{-- ============================================================================ --}}
    {{-- HERO SECTION --}}
    {{-- ============================================================================ --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-[#0053C5] to-slate-900 py-20 overflow-hidden">
        {{-- Animated Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>

        {{-- Decorative Elements --}}
        <div class="absolute top-20 left-10 w-20 h-20 border border-white/10 rounded-2xl rotate-12"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 border border-white/10 rounded-full"></div>
        <div class="absolute top-40 right-20 w-8 h-8 bg-indigo-500/20 rounded-lg rotate-45"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                {{-- Decorative Badge --}}
                <div
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-6">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm font-medium text-indigo-200">Blog & Artikel</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight">
                    Bacaan <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-pink-400">Inspiratif</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Tips ibadah, wawasan islami, dan artikel berkualitas untuk menemani perjalanan spiritual Anda
                </p>

                {{-- Search Bar - Glassmorphism Style --}}
                <form action="{{ route('posts.index') }}" method="GET" class="max-w-2xl mx-auto">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-pink-500 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-500">
                        </div>
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-2">
                            <div class="flex items-center">
                                <div class="pl-4 pr-2">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari artikel, topik, atau penulis..."
                                    class="flex-1 bg-transparent text-white placeholder-slate-400 px-4 py-4 focus:outline-none text-lg">
                                <button type="submit"
                                    class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/25">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Quick Stats --}}
                <div class="flex flex-wrap justify-center gap-8 mt-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $posts->total() }}</div>
                        <div class="text-sm text-slate-400">Total Artikel</div>
                    </div>
                    <div class="w-px h-12 bg-white/20 hidden md:block"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-400">{{ $categories->count() }}</div>
                        <div class="text-sm text-slate-400">Kategori</div>
                    </div>
                    <div class="w-px h-12 bg-white/20 hidden md:block"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-pink-400">{{ $featuredPosts->count() }}</div>
                        <div class="text-sm text-slate-400">Pilihan Editor</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave Divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
                    fill="#f8fafc" />
            </svg>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- FILTERS & SORTING --}}
    {{-- ============================================================================ --}}
    <section class="bg-slate-50 py-4 sticky top-0 z-40 border-b border-slate-200/50 backdrop-blur-lg bg-slate-50/90">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- Categories Filter --}}
                <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-2 lg:pb-0">
                    <span class="text-sm text-slate-500 font-medium whitespace-nowrap mr-2">Kategori:</span>
                    <a href="{{ route('posts.index') }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 {{ !request('category') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Semua
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('posts.index', ['category' => $cat->slug]) }}"
                            class="group flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                            {{ $cat->name }}
                            <span
                                class="px-1.5 py-0.5 rounded-md text-xs {{ request('category') == $cat->slug ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-indigo-100 group-hover:text-indigo-600' }} transition-colors">
                                {{ $cat->posts_count }}
                            </span>
                        </a>
                    @endforeach
                </div>

                {{-- Sort Options --}}
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500 font-medium">Urutkan:</span>
                    <div class="relative">
                        <select name="sort" onchange="window.location.href=this.value"
                            class="appearance-none bg-white border border-slate-200 text-slate-700 pl-4 pr-10 py-2.5 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer hover:border-slate-300 transition-colors">
                            <option
                                value="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                                {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                Terbaru
                            </option>
                            <option
                                value="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}"
                                {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                Terpopuler
                            </option>
                            <option
                                value="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}"
                                {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                Terlama
                            </option>
                            <option
                                value="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'title'])) }}"
                                {{ request('sort') == 'title' ? 'selected' : '' }}>
                                A-Z
                            </option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- MAIN CONTENT --}}
    {{-- ============================================================================ --}}
    <section class="py-12 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    @if ($posts->count() > 0)
                        {{-- Featured Post (First Post) --}}
                        @if ($posts->currentPage() == 1 && $posts->first())
                            @php $firstPost = $posts->first(); @endphp
                            <article class="mb-10 group">
                                <a href="{{ route('posts.show', $firstPost->slug) }}"
                                    class="block bg-white rounded-2xl overflow-hidden border border-slate-200/50 hover:border-slate-300 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                                    <div class="relative aspect-[21/10] overflow-hidden">
                                        <img src="{{ $firstPost->featured_image_url }}" alt="{{ $firstPost->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">

                                        {{-- Overlay Gradient --}}
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent">
                                        </div>

                                        {{-- Featured Badge --}}
                                        <div class="absolute top-5 left-5">
                                            <div
                                                class="flex items-center gap-1.5 bg-amber-500 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-lg shadow-amber-500/25">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Featured
                                            </div>
                                        </div>

                                        {{-- Content on Image --}}
                                        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                                            <div class="flex items-center gap-3 mb-4">
                                                <span
                                                    class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                                                    {{ $firstPost->category->name }}
                                                </span>
                                                <span
                                                    class="text-white/80 text-sm">{{ $firstPost->published_at->format('d M Y') }}</span>
                                                <span class="text-white/60">•</span>
                                                <span class="text-white/80 text-sm flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {{ number_format($firstPost->views_count) }}
                                                </span>
                                            </div>
                                            <h2
                                                class="text-2xl md:text-3xl font-bold text-white mb-3 group-hover:text-indigo-200 transition-colors">
                                                {{ $firstPost->title }}
                                            </h2>
                                            <p class="text-white/80 mb-4 line-clamp-2 max-w-2xl">
                                                {{ $firstPost->excerpt_or_content }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Author Bar --}}
                                    <div class="p-5 flex items-center justify-between border-t border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/25">
                                                {{ substr($firstPost->author->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">
                                                    {{ $firstPost->author->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $firstPost->reading_time }} menit
                                                    baca</p>
                                            </div>
                                        </div>
                                        <span
                                            class="inline-flex items-center gap-2 text-indigo-600 font-semibold text-sm group-hover:gap-3 transition-all">
                                            Baca Selengkapnya
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            </article>
                        @endif

                        {{-- Section Header --}}
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-slate-900">
                                @if (request('category'))
                                    Artikel {{ request('category') }}
                                @elseif(request('search'))
                                    Hasil Pencarian
                                @else
                                    Artikel Lainnya
                                @endif
                            </h2>
                            <span class="text-sm text-slate-500">{{ $posts->total() - 1 }} artikel</span>
                        </div>

                        {{-- Posts Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($posts->skip(1) as $post)
                                <article
                                    class="group bg-white rounded-2xl overflow-hidden border border-slate-200/50 hover:border-slate-300 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                        <div class="relative aspect-[16/10] overflow-hidden">
                                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">

                                            {{-- Overlay --}}
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            </div>

                                            @if ($post->is_featured)
                                                <div class="absolute top-3 right-3">
                                                    <div
                                                        class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center shadow-lg">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Category Badge --}}
                                            <div class="absolute bottom-3 left-3">
                                                <span
                                                    class="inline-flex items-center bg-white/90 backdrop-blur-sm text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold">
                                                    {{ $post->category->name }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-5">
                                            {{-- Meta Info --}}
                                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                                                <span>{{ $post->published_at->format('d M Y') }}</span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {{ number_format($post->views_count) }}
                                                </span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span>{{ $post->reading_time }} min</span>
                                            </div>

                                            {{-- Title --}}
                                            <h3
                                                class="text-lg font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                                {{ $post->title }}
                                            </h3>

                                            {{-- Excerpt --}}
                                            <p class="text-slate-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                                {{ $post->excerpt_or_content }}
                                            </p>

                                            {{-- Author --}}
                                            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                                <div
                                                    class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($post->author->name, 0, 1) }}
                                                </div>
                                                <div class="flex-1">
                                                    <span
                                                        class="text-sm font-medium text-slate-900">{{ $post->author->name }}</span>
                                                </div>
                                                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if ($posts->hasPages())
                            <div class="mt-12 flex justify-center">
                                <nav
                                    class="inline-flex items-center gap-1 bg-white rounded-xl border border-slate-200 p-1.5 shadow-sm">
                                    {{ $posts->links() }}
                                </nav>
                            </div>
                        @endif
                    @else
                        {{-- Empty State --}}
                        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                            <div
                                class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Tidak Ada Artikel</h3>
                            <p class="text-slate-600 mb-8 leading-relaxed">
                                @if (request('search'))
                                    Pencarian "{{ request('search') }}" tidak menemukan hasil. Coba kata kunci lain.
                                @elseif(request('category'))
                                    Belum ada artikel dalam kategori ini.
                                @else
                                    Belum ada artikel yang tersedia saat ini.
                                @endif
                            </p>
                            <a href="{{ route('posts.index') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Lihat Semua Artikel
                            </a>
                        </div>
                    @endif
                </div>

                {{-- ============================================================================ --}}
                {{-- SIDEBAR --}}
                {{-- ============================================================================ --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-20 space-y-6">
                        {{-- Featured Posts --}}
                        @if ($featuredPosts->count() > 0)
                            <div class="bg-white rounded-2xl border border-slate-200/50 p-6">
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900">Pilihan Editor</h3>
                                </div>
                                <div class="space-y-4">
                                    @foreach ($featuredPosts as $index => $featured)
                                        <a href="{{ route('posts.show', $featured->slug) }}"
                                            class="flex gap-4 group {{ !$loop->last ? 'pb-4 border-b border-slate-100' : '' }}">
                                            <div
                                                class="relative w-20 h-20 flex-shrink-0 rounded-xl overflow-hidden bg-slate-100">
                                                <img src="{{ $featured->featured_image_url }}"
                                                    alt="{{ $featured->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                <div
                                                    class="absolute top-1 left-1 w-5 h-5 bg-slate-900/80 rounded-md flex items-center justify-center text-white text-xs font-bold">
                                                    {{ $index + 1 }}
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 text-sm mb-1.5 leading-snug">
                                                    {{ $featured->title }}
                                                </h4>
                                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                                    <span>{{ $featured->published_at->format('d M') }}</span>
                                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                    <span>{{ number_format($featured->views_count) }} views</span>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        {{-- Popular Tags --}}
                        @if ($popularTags->count() > 0)
                            <div class="bg-white rounded-2xl border border-slate-200/50 p-6">
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900">Tag Populer</h3>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($popularTags as $tag)
                                        <a href="{{ route('posts.tag', $tag->slug) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-700 rounded-lg text-sm font-medium transition-all duration-200">
                                            <span class="text-slate-400 mr-1">#</span>{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Recent Posts --}}
                        @if ($recentPosts->count() > 0)
                            <div class="bg-white rounded-2xl border border-slate-200/50 p-6">
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900">Terbaru</h3>
                                </div>
                                <div class="space-y-4">
                                    @foreach ($recentPosts as $recent)
                                        <a href="{{ route('posts.show', $recent->slug) }}"
                                            class="block group {{ !$loop->last ? 'pb-4 border-b border-slate-100' : '' }}">
                                            <h4
                                                class="font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 text-sm mb-2 leading-snug">
                                                {{ $recent->title }}
                                            </h4>
                                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                                <span>{{ $recent->published_at->diffForHumans() }}</span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span>{{ $recent->reading_time }} min baca</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Newsletter CTA --}}
                        <div
                            class="relative bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white overflow-hidden">
                            {{-- Background Pattern --}}
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2">
                            </div>

                            <div class="relative z-10">
                                <div
                                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Berlangganan Newsletter</h3>
                                <p class="text-indigo-100 text-sm mb-5 leading-relaxed">
                                    Dapatkan artikel inspiratif terbaru langsung di inbox Anda setiap minggu.
                                </p>
                                <form class="space-y-3">
                                    <input type="email" placeholder="Masukkan email Anda"
                                        class="w-full px-4 py-3 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 placeholder-slate-400">
                                    <button type="submit"
                                        class="w-full px-6 py-3 bg-white text-indigo-600 rounded-xl font-semibold text-sm hover:bg-indigo-50 transition-all duration-200 shadow-lg shadow-black/10">
                                        Berlangganan Gratis
                                    </button>
                                </form>
                                <p class="text-xs text-indigo-200 mt-3 text-center">
                                    Tanpa spam. Berhenti kapan saja.
                                </p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
@push('styles')
    <style>
        /* Line Clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Hide Scrollbar */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Blob Animation */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Smooth Transitions */
        * {
            scroll-behavior: smooth;
        }

        /* Card Hover Effects */
        article {
            transform: translateY(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        article:hover {
            transform: translateY(-4px);
        }
    </style>
@endpush
