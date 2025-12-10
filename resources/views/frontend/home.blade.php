@extends('layouts.app')

@section('title', 'Beranda - Ecosystem Digital Ramadhan 1447 H')

@section('content')

    {{-- ============================================================================ --}}
    {{-- HERO SLIDER SECTION --}}
    {{-- ============================================================================ --}}
    <section class="relative overflow-hidden">
        @if ($sliders->isNotEmpty())
            <div class="hero-slider relative">
                @foreach ($sliders as $index => $slider)
                    <div class="slider-item {{ $index === 0 ? 'active' : '' }} relative h-[500px] md:h-[600px] lg:h-[700px]">
                        {{-- Background Image with Overlay --}}
                        <div class="absolute inset-0">
                            <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}"
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#0053C5] via-[#003d8f]/95 to-[#002a5c]/80">
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="relative container mx-auto px-4 h-full flex items-center">
                            <div class="max-w-3xl">
                                <div class="animate-fade-in-up">
                                    @if ($slider->subtitle)
                                        <p
                                            class="text-blue-200 text-sm md:text-base font-medium mb-3 tracking-wide uppercase">
                                            {{ $slider->subtitle }}
                                        </p>
                                    @endif

                                    <h1
                                        class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 md:mb-6 leading-tight">
                                        {{ $slider->title }}
                                    </h1>

                                    @if ($slider->description)
                                        <p class="text-lg md:text-xl text-blue-100 mb-6 md:mb-8 leading-relaxed">
                                            {{ $slider->description }}
                                        </p>
                                    @endif

                                    @if ($slider->button_text && $slider->button_url)
                                        <a href="{{ $slider->button_url }}"
                                            class="inline-flex items-center px-8 py-4 bg-white text-[#0053C5] font-semibold rounded-full hover:bg-blue-50 transform hover:scale-105 transition-all duration-300 shadow-xl hover:shadow-2xl">
                                            {{ $slider->button_text }}
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Slider Controls --}}
                        @if ($sliders->count() > 1)
                            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                @foreach ($sliders as $dotIndex => $dot)
                                    <button
                                        class="slider-dot w-3 h-3 rounded-full {{ $dotIndex === 0 ? 'bg-white' : 'bg-white/50' }} transition-all duration-300 hover:bg-white"
                                        data-slide="{{ $dotIndex }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            {{-- Default Hero --}}
            <div
                class="relative h-[500px] md:h-[600px] lg:h-[700px] bg-gradient-to-br from-[#0053C5] via-[#003d8f] to-[#002a5c]">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full blur-xl"></div>
                    <div class="absolute bottom-20 right-20 w-32 h-32 bg-white rounded-full blur-2xl"></div>
                </div>
                <div class="relative container mx-auto px-4 h-full flex items-center justify-center text-center">
                    <div class="max-w-4xl animate-fade-in-up">
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                            Selamat Datang di<br>
                            <span class="text-yellow-300">Ecosystem Digital</span><br>
                            Ramadhan 1447 H
                        </h1>
                        <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                            Raih keberkahan Ramadhan bersama kajian, tilawah, dan kegiatan spiritual terbaik
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('events.index') }}"
                                class="px-8 py-4 bg-white text-[#0053C5] font-semibold rounded-full hover:bg-blue-50 transform hover:scale-105 transition-all duration-300 shadow-xl">
                                Lihat Semua Event
                            </a>
                            <a href="{{ route('posts.index') }}"
                                class="px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-full hover:bg-white hover:text-[#0053C5] transform hover:scale-105 transition-all duration-300">
                                Baca Artikel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- ============================================================================ --}}
    {{-- STATISTICS SECTION --}}
    {{-- ============================================================================ --}}
    <section class="py-12 md:py-16 bg-gradient-to-r from-[#0053C5] to-[#003d8f] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_500px_at_50%_200px,#ffffff,transparent)]"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                {{-- Stat Card 1 --}}
                <div
                    class="stat-card bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300 border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                        {{ number_format($stats['total_events']) }}+
                    </div>
                    <div class="text-blue-100 text-sm md:text-base font-medium">Total Event</div>
                </div>

                {{-- Stat Card 2 --}}
                <div
                    class="stat-card bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300 border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                        {{ number_format($stats['total_participants']) }}+
                    </div>
                    <div class="text-blue-100 text-sm md:text-base font-medium">Peserta</div>
                </div>

                {{-- Stat Card 3 --}}
                <div
                    class="stat-card bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300 border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                        {{ number_format($stats['active_events']) }}
                    </div>
                    <div class="text-blue-100 text-sm md:text-base font-medium">Event Aktif</div>
                </div>

                {{-- Stat Card 4 --}}
                <div
                    class="stat-card bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300 border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                        {{ number_format($stats['upcoming_events']) }}
                    </div>
                    <div class="text-blue-100 text-sm md:text-base font-medium">Event Mendatang</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- FEATURED EVENTS SECTION --}}
    {{-- ============================================================================ --}}
    @if ($featuredEvents->isNotEmpty())
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="container mx-auto px-4">
                {{-- Section Header --}}
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Event <span class="text-[#0053C5]">Unggulan</span>
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                        Jangan lewatkan event-event pilihan yang telah kami kurasi untuk Anda
                    </p>
                </div>

                {{-- Featured Events Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($featuredEvents as $event)
                        <div
                            class="event-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
                            {{-- Event Image --}}
                            <div class="relative h-48 md:h-56 overflow-hidden">
                                <img src="{{ $event->featured_image ? Storage::url($event->featured_image) : 'https://via.placeholder.com/600x400/0053C5/FFFFFF?text=Event+Image' }}"
                                    alt="{{ $event->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                                {{-- Badge Featured --}}
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-4 py-1 rounded-full text-xs font-semibold shadow-lg">
                                        ⭐ Featured
                                    </span>
                                </div>

                                {{-- Category Badge --}}
                                @if ($event->category)
                                    <div class="absolute top-4 right-4">
                                        <span
                                            class="bg-[#0053C5] text-white px-4 py-1 rounded-full text-xs font-semibold shadow-lg">
                                            {{ $event->category->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Event Content --}}
                            <div class="p-6">
                                {{-- Event Meta --}}
                                <div class="flex items-center text-sm text-gray-500 mb-3 space-x-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $event->start_datetime->format('d M Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $event->start_datetime->format('H:i') }}
                                    </div>
                                </div>

                                {{-- Event Title --}}
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#0053C5] transition-colors">
                                    {{ $event->title }}
                                </h3>

                                {{-- Event Description --}}
                                <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
                                    {{ Str::limit(strip_tags($event->description), 100) }}
                                </p>

                                {{-- Event Footer --}}
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span
                                            class="text-gray-600">{{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }}</span>
                                    </div>

                                    <a href="{{ route('events.show', $event->slug) }}"
                                        class="text-[#0053C5] font-semibold hover:text-[#003d8f] flex items-center text-sm">
                                        Lihat Detail
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- View All Button --}}
                <div class="text-center mt-12">
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-full hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Lihat Semua Event
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- UPCOMING EVENTS SECTION --}}
    {{-- ============================================================================ --}}
    @if ($upcomingEvents->isNotEmpty())
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4">
                {{-- Section Header --}}
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Event <span class="text-[#0053C5]">Mendatang</span>
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                        Segera daftar sebelum kuota penuh!
                    </p>
                </div>

                {{-- Upcoming Events List --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($upcomingEvents as $event)
                        <div
                            class="flex flex-col sm:flex-row bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group border border-gray-100">
                            {{-- Event Image --}}
                            <div class="sm:w-48 h-48 sm:h-auto flex-shrink-0 relative overflow-hidden">
                                <img src="{{ $event->featured_image ? Storage::url($event->featured_image) : 'https://via.placeholder.com/300x300/0053C5/FFFFFF?text=Event' }}"
                                    alt="{{ $event->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>

                            {{-- Event Details --}}
                            <div class="flex-1 p-6">
                                {{-- Category --}}
                                @if ($event->category)
                                    <span
                                        class="inline-block bg-blue-50 text-[#0053C5] px-3 py-1 rounded-full text-xs font-semibold mb-3">
                                        {{ $event->category->name }}
                                    </span>
                                @endif

                                {{-- Title --}}
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-[#0053C5] transition-colors">
                                    {{ $event->title }}
                                </h3>

                                {{-- Meta Info --}}
                                <div class="space-y-2 mb-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $event->start_datetime->format('d M Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $event->start_datetime->format('H:i') }} WIB
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <div class="flex items-center justify-between">
                                    <div class="text-sm">
                                        <span class="text-gray-500">Kuota:</span>
                                        <span class="font-semibold text-[#0053C5]">
                                            {{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('events.show', $event->slug) }}"
                                        class="px-6 py-2 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-300 text-sm font-semibold">
                                        Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- EVENT CATEGORIES SECTION --}}
    {{-- ============================================================================ --}}
    @if ($eventCategories->isNotEmpty())
        <section
            class="py-16 md:py-24 bg-gradient-to-br from-[#0053C5] via-[#003d8f] to-[#002a5c] relative overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxIiBmaWxsPSJ3aGl0ZSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')]">
                </div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                {{-- Section Header --}}
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">
                        Kategori <span class="text-yellow-300">Event</span>
                    </h2>
                    <p class="text-lg md:text-xl text-blue-100 max-w-2xl mx-auto">
                        Temukan event sesuai minat dan kebutuhan spiritual Anda
                    </p>
                </div>

                {{-- Categories Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach ($eventCategories as $category)
                        <a href="{{ route('events.category', $category->slug) }}"
                            class="category-card bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/20 transform hover:scale-105 transition-all duration-300 border border-white/20 group">
                            {{-- Icon --}}
                            <div
                                class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition-all">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>

                            {{-- Category Name --}}
                            <h3 class="text-white font-bold mb-2 text-lg">
                                {{ $category->name }}
                            </h3>

                            {{-- Event Count --}}
                            <p class="text-blue-200 text-sm">
                                {{ $category->events_count }} Event
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- RECENT POSTS/ARTICLES SECTION --}}
    {{-- ============================================================================ --}}
    @if ($recentPosts->isNotEmpty())
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="container mx-auto px-4">
                {{-- Section Header --}}
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Artikel & <span class="text-[#0053C5]">Berita Terbaru</span>
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                        Baca artikel inspiratif dan berita terkini seputar Ramadhan
                    </p>
                </div>

                {{-- Posts Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($recentPosts as $post)
                        <article
                            class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
                            {{-- Post Image --}}
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $post->featured_image ? Storage::url($post->featured_image) : 'https://via.placeholder.com/600x400/0053C5/FFFFFF?text=Article' }}"
                                    alt="{{ $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                                {{-- Category Badge --}}
                                @if ($post->category)
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-[#0053C5] text-white px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $post->category->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Post Content --}}
                            <div class="p-6">
                                {{-- Post Meta --}}
                                <div class="flex items-center text-xs text-gray-500 mb-3 space-x-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $post->published_at->format('d M Y') }}
                                    </div>
                                    @if ($post->author)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $post->author->name }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Post Title --}}
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#0053C5] transition-colors">
                                    {{ $post->title }}
                                </h3>

                                {{-- Post Excerpt --}}
                                <p class="text-gray-600 mb-4 line-clamp-3 text-sm">
                                    {{ Str::limit(strip_tags($post->content), 120) }}
                                </p>

                                {{-- Read More Link --}}
                                <a href="{{ route('posts.show', $post->slug) }}"
                                    class="inline-flex items-center text-[#0053C5] font-semibold hover:text-[#003d8f] text-sm">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- View All Button --}}
                <div class="text-center mt-12">
                    <a href="{{ route('posts.index') }}"
                        class="inline-flex items-center px-8 py-4 bg-white text-[#0053C5] border-2 border-[#0053C5] font-semibold rounded-full hover:bg-[#0053C5] hover:text-white transform hover:scale-105 transition-all duration-300">
                        Lihat Semua Artikel
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- TESTIMONIALS SECTION --}}
    {{-- ============================================================================ --}}
    @if ($testimonials->isNotEmpty())
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4">
                {{-- Section Header --}}
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        Apa Kata <span class="text-[#0053C5]">Mereka</span>
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                        Testimoni dari peserta yang telah merasakan manfaat program kami
                    </p>
                </div>

                {{-- Testimonials Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($testimonials as $testimonial)
                        <div
                            class="testimonial-card bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6 md:p-8 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-blue-100">
                            {{-- Rating Stars --}}
                            <div class="flex items-center mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $testimonial->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>

                            {{-- Testimonial Text --}}
                            <p class="text-gray-700 mb-6 italic leading-relaxed">
                                "{{ Str::limit($testimonial->message, 200) }}"
                            </p>

                            {{-- Author Info --}}
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($testimonial->name ?? ($testimonial->user?->name ?? 'A'), 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="font-bold text-gray-900">
                                        {{ $testimonial->name ?? ($testimonial->user?->name ?? 'Anonymous') }}
                                    </div>
                                    @if ($testimonial->event)
                                        <div class="text-sm text-gray-500">
                                            Peserta {{ Str::limit($testimonial->event->title, 30) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- View All Testimonials --}}
                <div class="text-center mt-12">
                    <a href="{{ route('testimonials') }}"
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-full hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Lihat Semua Testimoni
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- CTA (Call To Action) SECTION --}}
    {{-- ============================================================================ --}}
    <section class="py-16 md:py-24 bg-gradient-to-br from-[#0053C5] via-[#003d8f] to-[#002a5c] relative overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Siap Raih Berkah Ramadhan?
                </h2>
                <p class="text-xl md:text-2xl text-blue-100 mb-10 leading-relaxed">
                    Bergabunglah dengan ribuan peserta lainnya dalam menjalani Ramadhan penuh makna
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('events.index') }}"
                        class="px-8 py-4 bg-white text-[#0053C5] font-bold rounded-full hover:bg-blue-50 transform hover:scale-105 transition-all duration-300 shadow-2xl">
                        Daftar Event Sekarang
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-[#0053C5] transform hover:scale-105 transition-all duration-300">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fadeInUp 1s ease-out;
            }

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

            .slider-item {
                display: none;
            }

            .slider-item.active {
                display: block;
            }

            html {
                scroll-behavior: smooth;
            }

            ::-webkit-scrollbar {
                width: 10px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #0053C5;
                border-radius: 5px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #003d8f;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Slider Functionality
                const sliderItems = document.querySelectorAll('.slider-item');
                const sliderDots = document.querySelectorAll('.slider-dot');
                let currentSlide = 0;

                if (sliderItems.length > 1) {
                    setInterval(() => {
                        nextSlide();
                    }, 5000);

                    sliderDots.forEach((dot, index) => {
                        dot.addEventListener('click', () => {
                            goToSlide(index);
                        });
                    });

                    function nextSlide() {
                        currentSlide = (currentSlide + 1) % sliderItems.length;
                        goToSlide(currentSlide);
                    }

                    function goToSlide(index) {
                        sliderItems.forEach(item => item.classList.remove('active'));
                        sliderDots.forEach(dot => {
                            dot.classList.remove('bg-white');
                            dot.classList.add('bg-white/50');
                        });

                        sliderItems[index].classList.add('active');
                        sliderDots[index].classList.remove('bg-white/50');
                        sliderDots[index].classList.add('bg-white');
                        currentSlide = index;
                    }
                }

                // Scroll animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -100px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.event-card, .testimonial-card, .category-card').forEach(el => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';
                    el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    observer.observe(el);
                });
            });
        </script>
    @endpush

@endsection
