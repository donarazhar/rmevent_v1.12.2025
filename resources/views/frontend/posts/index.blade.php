@extends('layouts.app')

@section('title', 'Artikel & Blog - Ramadhan 1447 H')

@section('content')
    {{-- ============================================================================ --}}
    {{-- HERO SECTION --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    📝 Artikel & Blog
                </h1>
                <p class="text-xl text-blue-100 mb-8">
                    Bacaan inspiratif, tips ibadah, dan wawasan islami untuk menemani Ramadhan Anda
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('posts.index') }}" method="GET" class="max-w-2xl mx-auto">
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..."
                            class="flex-1 px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-4 focus:ring-white/50">
                        <button type="submit"
                            class="px-8 py-4 bg-white text-[#0053C5] rounded-full font-semibold hover:bg-blue-50 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- FILTERS & SORTING --}}
    {{-- ============================================================================ --}}
    <section class="bg-white border-b sticky top-20 z-40 shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                {{-- Categories Filter --}}
                <div class="flex items-center gap-2 overflow-x-auto">
                    <a href="{{ route('posts.index') }}"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition-all {{ !request('category') ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('posts.index', ['category' => $cat->slug]) }}"
                            class="px-4 py-2 rounded-full whitespace-nowrap transition-all {{ request('category') == $cat->slug ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $cat->name }} ({{ $cat->posts_count }})
                        </a>
                    @endforeach
                </div>

                {{-- Sort Options --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Urutkan:</span>
                    <select name="sort" onchange="window.location.href=this.value"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0053C5]">
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
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- MAIN CONTENT --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    @if ($posts->count() > 0)
                        {{-- Featured Post (First Post) --}}
                        @if ($posts->currentPage() == 1 && $posts->first())
                            <div class="mb-8">
                                <a href="{{ route('posts.show', $posts->first()->slug) }}"
                                    class="group block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all">
                                    <div class="relative h-80 overflow-hidden">
                                        <img src="{{ $posts->first()->featured_image_url }}"
                                            alt="{{ $posts->first()->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <div
                                            class="absolute top-4 left-4 px-4 py-2 bg-[#0053C5] text-white rounded-full text-sm font-semibold">
                                            ⭐ Featured
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                {{ $posts->first()->category->name }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $posts->first()->published_at->format('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ number_format($posts->first()->views_count) }} views
                                            </span>
                                        </div>
                                        <h2
                                            class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-[#0053C5] transition-colors">
                                            {{ $posts->first()->title }}
                                        </h2>
                                        <p class="text-gray-600 mb-4">
                                            {{ $posts->first()->excerpt_or_content }}
                                        </p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-[#0053C5] to-[#003d8f] flex items-center justify-center text-white font-bold">
                                                    {{ substr($posts->first()->author->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ $posts->first()->author->name }}</p>
                                                    <p class="text-xs text-gray-600">{{ $posts->first()->reading_time }}
                                                        min
                                                        read</p>
                                                </div>
                                            </div>
                                            <span
                                                class="text-[#0053C5] font-semibold group-hover:underline flex items-center gap-2">
                                                Baca Selengkapnya
                                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif

                        {{-- Posts Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($posts->skip(1) as $post)
                                <article
                                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all group">
                                    <a href="{{ route('posts.show', $post->slug) }}">
                                        <div class="relative h-48 overflow-hidden">
                                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @if ($post->is_featured)
                                                <div
                                                    class="absolute top-3 right-3 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                                    ⭐
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-5">
                                            <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                                                <span
                                                    class="px-3 py-1 bg-blue-100 text-[#0053C5] rounded-full font-semibold">
                                                    {{ $post->category->name }}
                                                </span>
                                                <span>{{ $post->published_at->format('d M Y') }}</span>
                                            </div>
                                            <h3
                                                class="text-lg font-bold text-gray-900 mb-2 group-hover:text-[#0053C5] transition-colors line-clamp-2">
                                                {{ $post->title }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                                {{ $post->excerpt_or_content }}
                                            </p>
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gradient-to-r from-[#0053C5] to-[#003d8f] flex items-center justify-center text-white text-xs font-bold">
                                                        {{ substr($post->author->name, 0, 1) }}
                                                    </div>
                                                    <span class="font-medium">{{ $post->author->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        {{ number_format($post->views_count) }}
                                                    </span>
                                                    <span>{{ $post->reading_time }} min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-12">
                            {{ $posts->links() }}
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Tidak Ada Artikel</h3>
                            <p class="text-gray-600 mb-6">Belum ada artikel yang ditemukan.</p>
                            <a href="{{ route('posts.index') }}"
                                class="inline-block px-6 py-3 bg-[#0053C5] text-white rounded-full font-semibold hover:bg-[#003d8f] transition-all">
                                Lihat Semua Artikel
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-32 space-y-6">
                        {{-- Featured Posts --}}
                        @if ($featuredPosts->count() > 0)
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <span>⭐</span> Artikel Pilihan
                                </h3>
                                <div class="space-y-4">
                                    @foreach ($featuredPosts as $featured)
                                        <a href="{{ route('posts.show', $featured->slug) }}" class="flex gap-3 group">
                                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                                <img src="{{ $featured->featured_image_url }}"
                                                    alt="{{ $featured->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="font-semibold text-gray-900 group-hover:text-[#0053C5] transition-colors line-clamp-2 mb-1">
                                                    {{ $featured->title }}
                                                </h4>
                                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                                    <span>{{ $featured->published_at->format('d M Y') }}</span>
                                                    <span>•</span>
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
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <span>🏷️</span> Tag Populer
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($popularTags as $tag)
                                        <a href="{{ route('posts.tag', $tag->slug) }}"
                                            class="px-4 py-2 bg-gray-100 hover:bg-[#0053C5] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Recent Posts --}}
                        @if ($recentPosts->count() > 0)
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <span>🕒</span> Artikel Terbaru
                                </h3>
                                <div class="space-y-4">
                                    @foreach ($recentPosts as $recent)
                                        <a href="{{ route('posts.show', $recent->slug) }}" class="block group">
                                            <h4
                                                class="font-semibold text-gray-900 group-hover:text-[#0053C5] transition-colors line-clamp-2 mb-2">
                                                {{ $recent->title }}
                                            </h4>
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <span>{{ $recent->published_at->diffForHumans() }}</span>
                                                <span>•</span>
                                                <span>{{ $recent->reading_time }} min read</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Newsletter CTA --}}
                        <div
                            class="bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-xl shadow-lg p-6 text-white text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">📧 Newsletter</h3>
                            <p class="text-blue-100 text-sm mb-4">
                                Dapatkan artikel terbaru langsung di email Anda!
                            </p>
                            <form class="space-y-3">
                                <input type="email" placeholder="Email Anda"
                                    class="w-full px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                                <button type="submit"
                                    class="w-full px-6 py-3 bg-white text-[#0053C5] rounded-lg font-semibold hover:bg-blue-50 transition-all">
                                    Berlangganan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
