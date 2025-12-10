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
                    <div
                        class="slider-item {{ $index === 0 ? 'active' : '' }} relative h-screen min-h-[600px] max-h-[900px]">
                        {{-- Background Image --}}
                        <div class="absolute inset-0 overflow-hidden">
                            <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}"
                                class="w-full h-full object-cover scale-105">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-[#0053C5]/90 via-[#003d8f]/85 to-[#002a5c]/80">
                            </div>
                            {{-- Animated Particles/Shapes --}}
                            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                                <div class="floating-shape shape-1"></div>
                                <div class="floating-shape shape-2"></div>
                                <div class="floating-shape shape-3"></div>
                                <div class="floating-shape shape-4"></div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="relative container mx-auto px-4 h-full flex items-center">
                            <div class="max-w-4xl w-full">
                                {{-- Subtitle --}}
                                @if ($slider->subtitle)
                                    <div class="slide-content mb-4">
                                        <p
                                            class="text-blue-200 text-sm md:text-base font-medium tracking-[0.2em] uppercase flex items-center gap-3">
                                            <span class="w-12 h-[2px] bg-yellow-400"></span>
                                            {{ $slider->subtitle }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Title --}}
                                <div class="slide-content mb-6">
                                    <h1
                                        class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-[1.1] tracking-tight">
                                        {{ $slider->title }}
                                    </h1>
                                </div>

                                {{-- Description --}}
                                @if ($slider->description)
                                    <div class="slide-content mb-10">
                                        <p class="text-lg md:text-xl text-blue-100/90 leading-relaxed max-w-2xl">
                                            {{ $slider->description }}
                                        </p>
                                    </div>
                                @endif

                                {{-- CTA Buttons --}}
                                <div class="slide-content flex flex-wrap gap-4">
                                    @if ($slider->button_text && $slider->button_url)
                                        <a href="{{ $slider->button_url }}"
                                            class="group relative inline-flex items-center px-8 py-4 bg-white text-[#0053C5] font-bold rounded-full overflow-hidden transition-all duration-500 hover:shadow-[0_20px_50px_rgba(255,255,255,0.3)]">
                                            <span class="relative z-10 flex items-center gap-2">
                                                {{ $slider->button_text }}
                                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </span>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-yellow-300 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                                            </div>
                                        </a>
                                    @endif
                                    <a href="{{ route('events.index') }}"
                                        class="group inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-full backdrop-blur-sm hover:bg-white/10 hover:border-white/50 transition-all duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Jelajahi Event
                                    </a>
                                </div>
                            </div>

                            {{-- Decorative Element --}}
                            <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-[500px] h-[500px]">
                                <div class="relative w-full h-full">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-full blur-3xl animate-pulse-slow">
                                    </div>
                                    <div class="absolute inset-10 border-2 border-white/20 rounded-full animate-spin-slow">
                                    </div>
                                    <div class="absolute inset-20 border border-white/10 rounded-full animate-spin-reverse">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- ============================== --}}
                {{-- SLIDER NAVIGATION CONTROLS --}}
                {{-- ============================== --}}
                @if ($sliders->count() > 1)
                    {{-- Previous Button --}}
                    <button id="slider-prev"
                        class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-20 group w-14 h-14 md:w-16 md:h-16 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 hover:bg-white hover:border-white transition-all duration-300 hover:scale-110">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white group-hover:text-[#0053C5] transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    {{-- Next Button --}}
                    <button id="slider-next"
                        class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-20 group w-14 h-14 md:w-16 md:h-16 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 hover:bg-white hover:border-white transition-all duration-300 hover:scale-110">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white group-hover:text-[#0053C5] transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    {{-- Bottom Navigation --}}
                    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-6">
                        {{-- Slide Counter --}}
                        <div class="flex items-center gap-4 text-white">
                            <span id="current-slide" class="text-3xl md:text-4xl font-bold">01</span>
                            <span class="w-12 md:w-16 h-[2px] bg-white/30 relative overflow-hidden">
                                <span id="slide-progress" class="absolute inset-y-0 left-0 bg-yellow-400 w-0"></span>
                            </span>
                            <span class="text-lg text-white/60">0{{ $sliders->count() }}</span>
                        </div>

                        {{-- Dot Indicators --}}
                        <div class="flex items-center gap-3">
                            @foreach ($sliders as $dotIndex => $dot)
                                <button
                                    class="slider-dot w-3 h-3 rounded-full transition-all duration-300 {{ $dotIndex === 0 ? 'bg-yellow-400 scale-125' : 'bg-white/40 hover:bg-white/60' }}"
                                    data-slide="{{ $dotIndex }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Scroll Indicator --}}
                <div
                    class="absolute bottom-8 right-8 md:bottom-10 md:right-auto md:left-8 flex flex-col items-center gap-2 z-10">
                    <span class="text-white/60 text-xs tracking-widest uppercase hidden md:block">Scroll</span>
                    <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center pt-2">
                        <div class="w-1 h-3 bg-white/60 rounded-full animate-bounce"></div>
                    </div>
                </div>
            </div>
        @else
            {{-- Default Hero (ketika tidak ada slider) --}}
            <div
                class="relative h-screen min-h-[600px] max-h-[900px] bg-gradient-to-br from-[#0053C5] via-[#003d8f] to-[#002a5c] overflow-hidden">
                {{-- Animated Background --}}
                <div class="absolute inset-0">
                    <div class="absolute inset-0 opacity-30">
                        <div
                            class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxIiBmaWxsPSJ3aGl0ZSIgb3BhY2l0eT0iMC4zIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')]">
                        </div>
                    </div>
                    <div class="floating-shape shape-1"></div>
                    <div class="floating-shape shape-2"></div>
                    <div class="floating-shape shape-3"></div>
                    <div class="floating-shape shape-4"></div>
                </div>

                <div class="relative container mx-auto px-4 h-full flex items-center">
                    <div class="max-w-5xl mx-auto text-center">
                        {{-- Subtitle --}}
                        <p class="text-blue-200 text-sm md:text-base font-medium tracking-[0.3em] uppercase mb-6">
                            ✨ Selamat Datang di Platform Spiritual Digital ✨
                        </p>

                        {{-- Title --}}
                        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[1.05] mb-6">
                            Ecosystem Digital
                            <span
                                class="block mt-2 bg-gradient-to-r from-yellow-300 via-yellow-400 to-yellow-300 bg-clip-text text-transparent">
                                Ramadhan 1447 H
                            </span>
                        </h1>

                        {{-- Description --}}
                        <p class="text-xl md:text-2xl text-blue-100/80 leading-relaxed max-w-3xl mx-auto mb-12">
                            Raih keberkahan Ramadhan bersama kajian, tilawah, dan kegiatan spiritual terbaik dalam genggaman
                            Anda
                        </p>

                        {{-- CTA Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('events.index') }}"
                                class="group relative inline-flex items-center justify-center px-10 py-5 bg-white text-[#0053C5] font-bold text-lg rounded-full overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.3)] hover:shadow-[0_30px_60px_rgba(0,0,0,0.4)] transition-all duration-500">
                                <span class="relative z-10 flex items-center gap-3">
                                    Mulai Perjalanan
                                    <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-yellow-300 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                                </div>
                            </a>
                            <a href="{{ route('posts.index') }}"
                                class="group inline-flex items-center justify-center px-10 py-5 border-2 border-white/40 text-white font-semibold text-lg rounded-full backdrop-blur-sm hover:bg-white/10 hover:border-white transition-all duration-300">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Baca Artikel
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Scroll Indicator --}}
                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
                    <span class="text-white/60 text-xs tracking-widest uppercase">Scroll</span>
                    <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center pt-2">
                        <div class="w-1 h-3 bg-white/60 rounded-full animate-bounce"></div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- ============================================================================ --}}
    {{-- FEATURED EVENTS SECTION --}}
    {{-- ============================================================================ --}}
    @if ($featuredEvents->isNotEmpty())
        <section class="py-24 md:py-32 bg-gray-50 overflow-hidden">
            <div class="container mx-auto px-4">
                {{-- Section Header --}}
                <div class="max-w-3xl mx-auto text-center mb-16 md:mb-20">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#0053C5]/10 rounded-full mb-6">
                        <span class="w-2 h-2 bg-[#0053C5] rounded-full animate-pulse"></span>
                        <span class="text-[#0053C5] font-semibold text-sm tracking-wide uppercase">Event Pilihan</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-6 leading-tight">
                        Event <span class="text-[#0053C5]">Unggulan</span>
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                        Jangan lewatkan event-event pilihan yang telah kami kurasi khusus untuk menemani perjalanan
                        spiritual Anda
                    </p>
                </div>

                {{-- Featured Events Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($featuredEvents as $index => $event)
                        <div class="event-card group" style="animation-delay: {{ $index * 0.1 }}s">
                            <div
                                class="relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                                {{-- Event Image --}}
                                <div class="relative h-64 overflow-hidden">
                                    <img src="{{ $event->featured_image ? Storage::url($event->featured_image) : 'https://via.placeholder.com/600x400/0053C5/FFFFFF?text=Event+Image' }}"
                                        alt="{{ $event->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                                    {{-- Overlay --}}
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    {{-- Featured Badge --}}
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-gradient-to-r from-yellow-400 to-amber-500 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            Featured
                                        </span>
                                    </div>

                                    {{-- Category Badge --}}
                                    @if ($event->category)
                                        <div class="absolute top-4 right-4">
                                            <span
                                                class="bg-white/95 backdrop-blur-sm text-[#0053C5] px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                                                {{ $event->category->name }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Quick View Button --}}
                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-white text-[#0053C5] font-bold rounded-full transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 hover:bg-[#0053C5] hover:text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>

                                {{-- Event Content --}}
                                <div class="p-6 md:p-8">
                                    {{-- Event Date & Time --}}
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <div
                                                class="w-8 h-8 bg-[#0053C5]/10 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#0053C5]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ $event->start_datetime->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <div
                                                class="w-8 h-8 bg-[#0053C5]/10 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#0053C5]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <span class="font-medium">{{ $event->start_datetime->format('H:i') }}
                                                WIB</span>
                                        </div>
                                    </div>

                                    {{-- Event Title --}}
                                    <h3
                                        class="text-xl md:text-2xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#0053C5] transition-colors">
                                        {{ $event->title }}
                                    </h3>

                                    {{-- Event Description --}}
                                    <p class="text-gray-600 mb-6 line-clamp-2">
                                        {{ Str::limit(strip_tags($event->description), 100) }}
                                    </p>

                                    {{-- Event Footer --}}
                                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                                        {{-- Participants --}}
                                        <div class="flex items-center gap-3">
                                            <div class="flex -space-x-2">
                                                @for ($i = 0; $i < min(3, $event->current_participants); $i++)
                                                    <div
                                                        class="w-8 h-8 bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-full border-2 border-white flex items-center justify-center text-white text-xs font-bold">
                                                        {{ chr(65 + $i) }}
                                                    </div>
                                                @endfor
                                                @if ($event->current_participants > 3)
                                                    <div
                                                        class="w-8 h-8 bg-gray-200 rounded-full border-2 border-white flex items-center justify-center text-gray-600 text-xs font-bold">
                                                        +{{ $event->current_participants - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                {{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }}
                                            </span>
                                        </div>

                                        {{-- Action Button --}}
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="inline-flex items-center gap-2 text-[#0053C5] font-bold hover:gap-3 transition-all">
                                            Detail
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- View All Button --}}
                <div class="text-center mt-16">
                    <a href="{{ route('events.index') }}"
                        class="group relative inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-bold text-lg rounded-full overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300">
                        <span class="relative z-10">Lihat Semua Event</span>
                        <svg class="relative z-10 w-6 h-6 transform group-hover:translate-x-2 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-[#003d8f] to-[#002a5c] transform translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                        </div>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- UPCOMING EVENTS SECTION --}}
    {{-- ============================================================================ --}}
    @if ($upcomingEvents->isNotEmpty())
        <section class="py-24 md:py-32 bg-white relative overflow-hidden">
            {{-- Background Pattern --}}
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0 bg-[radial-gradient(#0053C5_1px,transparent_1px)] bg-[size:40px_40px]"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                {{-- Section Header --}}
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-16">
                    <div class="max-w-2xl mb-8 lg:mb-0">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 rounded-full mb-6">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-emerald-700 font-semibold text-sm tracking-wide uppercase">Segera
                                Hadir</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-6 leading-tight">
                            Event <span class="text-[#0053C5]">Mendatang</span>
                        </h2>
                        <p class="text-lg md:text-xl text-gray-600">
                            Segera daftar sebelum kuota penuh! Jangan sampai ketinggalan momen berharga ini.
                        </p>
                    </div>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 border-2 border-[#0053C5] text-[#0053C5] font-bold rounded-full hover:bg-[#0053C5] hover:text-white transition-all duration-300">
                        Lihat Semua
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                {{-- Upcoming Events List --}}
                <div class="space-y-6">
                    @foreach ($upcomingEvents as $index => $event)
                        <div class="upcoming-event-card group" style="animation-delay: {{ $index * 0.1 }}s">
                            <div
                                class="relative bg-white rounded-3xl border border-gray-100 hover:border-[#0053C5]/20 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                                <div class="flex flex-col lg:flex-row">
                                    {{-- Date Badge --}}
                                    <div
                                        class="lg:w-48 flex-shrink-0 p-6 lg:p-8 bg-gradient-to-br from-[#0053C5] to-[#003d8f] text-white flex flex-row lg:flex-col items-center lg:items-start justify-between lg:justify-center gap-4">
                                        <div class="text-center lg:text-left">
                                            <div class="text-5xl lg:text-6xl font-black leading-none">
                                                {{ $event->start_datetime->format('d') }}
                                            </div>
                                            <div class="text-lg font-bold text-blue-200 uppercase tracking-wide">
                                                {{ $event->start_datetime->format('M Y') }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-blue-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-semibold">{{ $event->start_datetime->format('H:i') }}
                                                WIB</span>
                                        </div>
                                    </div>

                                    {{-- Event Content --}}
                                    <div class="flex-1 p-6 lg:p-8">
                                        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                                            {{-- Event Info --}}
                                            <div class="flex-1">
                                                @if ($event->category)
                                                    <span
                                                        class="inline-block bg-[#0053C5]/10 text-[#0053C5] px-3 py-1 rounded-full text-xs font-bold mb-3">
                                                        {{ $event->category->name }}
                                                    </span>
                                                @endif
                                                <h3
                                                    class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-[#0053C5] transition-colors">
                                                    {{ $event->title }}
                                                </h3>
                                                <p class="text-gray-600 line-clamp-2 mb-4">
                                                    {{ Str::limit(strip_tags($event->description), 150) }}
                                                </p>

                                                {{-- Quota Progress --}}
                                                <div class="flex items-center gap-4">
                                                    <div class="flex-1 max-w-xs">
                                                        <div class="flex items-center justify-between text-sm mb-2">
                                                            <span class="text-gray-500">Kuota Terisi</span>
                                                            <span class="font-bold text-[#0053C5]">
                                                                {{ $event->current_participants }}/{{ $event->max_participants ?? '∞' }}
                                                            </span>
                                                        </div>
                                                        @if ($event->max_participants)
                                                            <div
                                                                class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                                                <div class="h-full bg-gradient-to-r from-[#0053C5] to-[#003d8f] rounded-full transition-all duration-500"
                                                                    style="width: {{ min(100, ($event->current_participants / $event->max_participants) * 100) }}%">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Action Button --}}
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('events.show', $event->slug) }}"
                                                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-bold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                                    <span>Daftar Sekarang</span>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hover Effect Line --}}
                                <div
                                    class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-[#0053C5] to-[#003d8f] transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
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
            class="py-24 md:py-32 bg-gradient-to-br from-[#0053C5] via-[#003d8f] to-[#002a5c] relative overflow-hidden">
            {{-- Animated Background --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-10">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_800px_at_100%_200px,rgba(255,255,255,0.15),transparent)]">
                    </div>
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_600px_at_0%_80%,rgba(255,255,255,0.1),transparent)]">
                    </div>
                </div>
                <div class="floating-shape-dark shape-1"></div>
                <div class="floating-shape-dark shape-2"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                {{-- Section Header --}}
                <div class="max-w-3xl mx-auto text-center mb-16 md:mb-20">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                        <span class="text-white font-semibold text-sm tracking-wide uppercase">Kategori</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight">
                        Temukan Event <span class="text-yellow-400">Sesuai Minat</span>
                    </h2>
                    <p class="text-lg md:text-xl text-blue-100/80">
                        Pilih kategori event sesuai kebutuhan spiritual dan minat Anda
                    </p>
                </div>

                {{-- Categories Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($eventCategories as $index => $category)
                        <a href="{{ route('events.category', $category->slug) }}" class="category-card group"
                            style="animation-delay: {{ $index * 0.05 }}s">
                            <div
                                class="relative bg-white/10 backdrop-blur-md rounded-3xl p-8 text-center border border-white/20 hover:bg-white/20 hover:border-white/40 transform hover:-translate-y-2 hover:scale-105 transition-all duration-500">
                                {{-- Icon Container --}}
                                <div class="relative w-20 h-20 mx-auto mb-6">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-2xl transform rotate-6 group-hover:rotate-12 transition-transform duration-300">
                                    </div>
                                    <div
                                        class="relative bg-gradient-to-br from-yellow-400 to-amber-500 rounded-2xl w-full h-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Category Name --}}
                                <h3
                                    class="text-white font-bold text-lg mb-2 group-hover:text-yellow-300 transition-colors">
                                    {{ $category->name }}
                                </h3>

                                {{-- Event Count --}}
                                <p class="text-blue-200 text-sm font-medium">
                                    {{ $category->events_count }} Event
                                </p>

                                {{-- Arrow Indicator --}}
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="inline-flex items-center gap-2 text-yellow-400 text-sm font-semibold">
                                        Jelajahi
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
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
        <section class="py-24 md:py-32 bg-gray-50 relative overflow-hidden">
            <div class="container mx-auto px-4">
                {{-- Section Header --}}
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-16">
                    <div class="max-w-2xl mb-8 lg:mb-0">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#0053C5]/10 rounded-full mb-6">
                            <span class="w-2 h-2 bg-[#0053C5] rounded-full animate-pulse"></span>
                            <span class="text-[#0053C5] font-semibold text-sm tracking-wide uppercase">Blog &
                                Artikel</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-6 leading-tight">
                            Artikel & <span class="text-[#0053C5]">Berita Terbaru</span>
                        </h2>
                        <p class="text-lg md:text-xl text-gray-600">
                            Baca artikel inspiratif dan berita terkini seputar Ramadhan dan kegiatan spiritual
                        </p>
                    </div>
                    <a href="{{ route('posts.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 border-2 border-[#0053C5] text-[#0053C5] font-bold rounded-full hover:bg-[#0053C5] hover:text-white transition-all duration-300">
                        Semua Artikel
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                {{-- Posts Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($recentPosts as $index => $post)
                        <article class="post-card group" style="animation-delay: {{ $index * 0.1 }}s">
                            <div
                                class="relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                                {{-- Post Image --}}
                                <div class="relative h-56 overflow-hidden">
                                    <img src="{{ $post->featured_image ? Storage::url($post->featured_image) : 'https://via.placeholder.com/600x400/0053C5/FFFFFF?text=Article' }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                                    {{-- Overlay --}}
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                    </div>

                                    {{-- Category Badge --}}
                                    @if ($post->category)
                                        <div class="absolute top-4 left-4">
                                            <span
                                                class="bg-white/95 backdrop-blur-sm text-[#0053C5] px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                                                {{ $post->category->name }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Read Time --}}
                                    <div
                                        class="absolute bottom-4 left-4 text-white text-sm font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        5 min read
                                    </div>
                                </div>

                                {{-- Post Content --}}
                                <div class="p-6 md:p-8">
                                    {{-- Post Meta --}}
                                    <div class="flex items-center gap-4 mb-4 text-sm text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $post->published_at->format('d M Y') }}
                                        </div>
                                        @if ($post->author)
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-6 h-6 bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($post->author->name, 0, 1) }}
                                                </div>
                                                {{ $post->author->name }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Post Title --}}
                                    <h3
                                        class="text-xl md:text-2xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#0053C5] transition-colors">
                                        {{ $post->title }}
                                    </h3>

                                    {{-- Post Excerpt --}}
                                    <p class="text-gray-600 mb-6 line-clamp-3">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>

                                    {{-- Read More Link --}}
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                        class="inline-flex items-center gap-2 text-[#0053C5] font-bold hover:gap-3 transition-all">
                                        Baca Selengkapnya
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- TESTIMONIALS SECTION --}}
    {{-- ============================================================================ --}}
    @if ($testimonials->isNotEmpty())
        <section class="py-24 md:py-32 bg-white relative overflow-hidden">
            {{-- Background Decoration --}}
            <div class="absolute top-0 left-0 w-96 h-96 bg-[#0053C5]/5 rounded-full -translate-x-1/2 -translate-y-1/2">
            </div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#0053C5]/5 rounded-full translate-x-1/2 translate-y-1/2">
            </div>

            <div class="container mx-auto px-4 relative z-10">
                {{-- Section Header --}}
                <div class="max-w-3xl mx-auto text-center mb-16 md:mb-20">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#0053C5]/10 rounded-full mb-6">
                        <span class="w-2 h-2 bg-[#0053C5] rounded-full animate-pulse"></span>
                        <span class="text-[#0053C5] font-semibold text-sm tracking-wide uppercase">Testimoni</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-6 leading-tight">
                        Apa Kata <span class="text-[#0053C5]">Mereka</span>
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600">
                        Testimoni dari peserta yang telah merasakan manfaat mengikuti program-program kami
                    </p>
                </div>

                {{-- Testimonials Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($testimonials as $index => $testimonial)
                        <div class="testimonial-card group" style="animation-delay: {{ $index * 0.1 }}s">
                            <div
                                class="relative bg-gradient-to-br from-white to-blue-50/50 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                                {{-- Quote Icon --}}
                                <div class="absolute -top-4 left-8">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-2xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Rating Stars --}}
                                <div class="flex items-center gap-1 mb-6 mt-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $testimonial->overall_rating ? 'text-yellow-400' : 'text-gray-200' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>

                                {{-- Testimonial Text --}}
                                <p class="text-gray-700 text-lg leading-relaxed mb-8 italic">
                                    "{{ Str::limit($testimonial->message, 200) }}"
                                </p>

                                {{-- Author Info --}}
                                <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                        {{ substr($testimonial->name ?? ($testimonial->user?->name ?? 'A'), 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-lg">
                                            {{ $testimonial->name ?? ($testimonial->user?->name ?? 'Anonymous') }}
                                        </div>
                                        @if ($testimonial->event)
                                            <div class="text-sm text-[#0053C5] font-medium">
                                                {{ Str::limit($testimonial->event->title, 30) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- View All Button --}}
                <div class="text-center mt-16">
                    <a href="{{ route('testimonials') }}"
                        class="group relative inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-bold text-lg rounded-full overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300">
                        <span class="relative z-10">Lihat Semua Testimoni</span>
                        <svg class="relative z-10 w-6 h-6 transform group-hover:translate-x-2 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-[#003d8f] to-[#002a5c] transform translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                        </div>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- CTA SECTION --}}
    {{-- ============================================================================ --}}
    <section class="py-24 md:py-32 bg-gradient-to-br from-[#0053C5] via-[#003d8f] to-[#002a5c] relative overflow-hidden">
        {{-- Animated Background --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 opacity-20">
                <div
                    class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxIiBmaWxsPSJ3aGl0ZSIgb3BhY2l0eT0iMC4zIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')]">
                </div>
            </div>
            <div class="absolute top-20 left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse-slow"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse-slow"
                style="animation-delay: 1s"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto">
                <div class="text-center">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-8">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                        <span class="text-white font-semibold text-sm tracking-wide uppercase">Mulai Sekarang</span>
                    </div>

                    {{-- Heading --}}
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight">
                        Siap Raih Berkah
                        <span
                            class="block mt-2 bg-gradient-to-r from-yellow-300 via-yellow-400 to-yellow-300 bg-clip-text text-transparent">
                            Ramadhan?
                        </span>
                    </h2>

                    {{-- Description --}}
                    <p class="text-xl md:text-2xl text-blue-100/80 mb-12 max-w-3xl mx-auto leading-relaxed">
                        Bergabunglah dengan ribuan peserta lainnya dalam menjalani Ramadhan penuh makna dan keberkahan
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('events.index') }}"
                            class="group relative inline-flex items-center justify-center gap-3 px-10 py-5 bg-white text-[#0053C5] font-bold text-lg rounded-full overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.3)] hover:shadow-[0_30px_60px_rgba(0,0,0,0.4)] transition-all duration-500">
                            <span class="relative z-10 flex items-center gap-3">
                                Daftar Event Sekarang
                                <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-yellow-300 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                            </div>
                        </a>
                        <a href="{{ route('contact') }}"
                            class="inline-flex items-center justify-center gap-3 px-10 py-5 border-2 border-white/40 text-white font-bold text-lg rounded-full backdrop-blur-sm hover:bg-white/10 hover:border-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Hubungi Kami
                        </a>
                    </div>

                    {{-- Trust Indicators --}}
                    <div class="mt-16 pt-12 border-t border-white/20">
                        <p class="text-blue-200 text-sm mb-6 uppercase tracking-wider font-medium">Dipercaya oleh</p>
                        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
                            <div class="text-white/60 hover:text-white/100 transition-colors">
                                <span class="text-2xl font-bold">{{ number_format($stats['total_participants']) }}+</span>
                                <span class="block text-sm">Peserta</span>
                            </div>
                            <div class="w-px h-12 bg-white/20"></div>
                            <div class="text-white/60 hover:text-white/100 transition-colors">
                                <span class="text-2xl font-bold">{{ number_format($stats['total_events']) }}+</span>
                                <span class="block text-sm">Event</span>
                            </div>
                            <div class="w-px h-12 bg-white/20"></div>
                            <div class="text-white/60 hover:text-white/100 transition-colors">
                                <span class="text-2xl font-bold">4.9</span>
                                <span class="block text-sm">Rating</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            /* ============================================ */
            /* HERO SLIDER STYLES */
            /* ============================================ */
            .hero-slider {
                position: relative;
                width: 100%;
                min-height: 600px;
            }

            .slider-item {
                display: none;
                opacity: 0;
                transition: opacity 0.8s ease-in-out;
            }

            .slider-item.active {
                display: block;
                opacity: 1;
            }

            /* Slide Content Animation */
            .slider-item .slide-content {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }

            .slider-item.active .slide-content {
                opacity: 1;
                transform: translateY(0);
            }

            .slider-item.active .slide-content:nth-child(1) {
                transition-delay: 0.1s;
            }

            .slider-item.active .slide-content:nth-child(2) {
                transition-delay: 0.2s;
            }

            .slider-item.active .slide-content:nth-child(3) {
                transition-delay: 0.3s;
            }

            .slider-item.active .slide-content:nth-child(4) {
                transition-delay: 0.4s;
            }

            /* Progress Bar Animation */
            @keyframes progressBar {
                from {
                    width: 0%;
                }

                to {
                    width: 100%;
                }
            }

            #slide-progress.animating {
                animation: progressBar 6s linear forwards;
            }

            /* Slider Dot Active */
            .slider-dot.active {
                background-color: #facc15 !important;
                transform: scale(1.25);
            }

            /* ============================================ */
            /* FLOATING SHAPES */
            /* ============================================ */
            .floating-shape {
                position: absolute;
                border-radius: 50%;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
                pointer-events: none;
            }

            .floating-shape.shape-1 {
                width: 300px;
                height: 300px;
                top: 10%;
                right: 10%;
                animation: float 8s ease-in-out infinite;
            }

            .floating-shape.shape-2 {
                width: 200px;
                height: 200px;
                bottom: 20%;
                left: 5%;
                animation: float 10s ease-in-out infinite;
                animation-delay: 1s;
            }

            .floating-shape.shape-3 {
                width: 150px;
                height: 150px;
                top: 40%;
                left: 20%;
                animation: float 7s ease-in-out infinite;
                animation-delay: 2s;
            }

            .floating-shape.shape-4 {
                width: 100px;
                height: 100px;
                bottom: 10%;
                right: 20%;
                animation: float 9s ease-in-out infinite;
                animation-delay: 0.5s;
            }

            .floating-shape-dark {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.05);
                pointer-events: none;
            }

            .floating-shape-dark.shape-1 {
                width: 400px;
                height: 400px;
                top: -100px;
                right: -100px;
                animation: float 12s ease-in-out infinite;
            }

            .floating-shape-dark.shape-2 {
                width: 300px;
                height: 300px;
                bottom: -50px;
                left: -50px;
                animation: float 10s ease-in-out infinite;
                animation-delay: 2s;
            }

            /* ============================================ */
            /* ANIMATION KEYFRAMES */
            /* ============================================ */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(40px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0) rotate(0deg);
                }

                50% {
                    transform: translateY(-20px) rotate(5deg);
                }
            }

            @keyframes pulse-slow {

                0%,
                100% {
                    opacity: 0.5;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.8;
                    transform: scale(1.05);
                }
            }

            @keyframes spin-slow {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes spin-reverse {
                from {
                    transform: rotate(360deg);
                }

                to {
                    transform: rotate(0deg);
                }
            }

            /* ============================================ */
            /* ANIMATION CLASSES */
            /* ============================================ */
            .animate-fade-in-up {
                animation: fadeInUp 1s ease-out forwards;
            }

            .animate-pulse-slow {
                animation: pulse-slow 4s ease-in-out infinite;
            }

            .animate-spin-slow {
                animation: spin-slow 20s linear infinite;
            }

            .animate-spin-reverse {
                animation: spin-reverse 15s linear infinite;
            }

            /* ============================================ */
            /* CARD ANIMATIONS */
            /* ============================================ */
            .event-card,
            .post-card,
            .testimonial-card,
            .category-card,
            .upcoming-event-card {
                opacity: 0;
                transform: translateY(30px);
            }

            .event-card.animate,
            .post-card.animate,
            .testimonial-card.animate,
            .category-card.animate,
            .upcoming-event-card.animate {
                animation: fadeInUp 0.8s ease-out forwards;
            }

            /* ============================================ */
            /* LINE CLAMP */
            /* ============================================ */
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

            /* ============================================ */
            /* CUSTOM SCROLLBAR */
            /* ============================================ */
            html {
                scroll-behavior: smooth;
            }

            ::-webkit-scrollbar {
                width: 10px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #0053C5, #003d8f);
                border-radius: 10px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #003d8f, #002a5c);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ============================================
                // HERO SLIDER
                // ============================================
                const sliderItems = document.querySelectorAll('.slider-item');
                const sliderDots = document.querySelectorAll('.slider-dot');
                const prevBtn = document.getElementById('slider-prev');
                const nextBtn = document.getElementById('slider-next');
                const currentSlideEl = document.getElementById('current-slide');
                const slideProgress = document.getElementById('slide-progress');

                let currentSlide = 0;
                let slideInterval;
                let totalSlides = sliderItems.length;
                let isAnimating = false;
                const autoPlayDelay = 6000;

                // Exit if no slides or only one slide
                if (totalSlides <= 1) return;

                // Initialize
                init();

                function init() {
                    updateSlide(0);
                    startAutoPlay();

                    // Event Listeners
                    if (prevBtn) {
                        prevBtn.addEventListener('click', function() {
                            if (!isAnimating) {
                                goToPrev();
                                resetAutoPlay();
                            }
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', function() {
                            if (!isAnimating) {
                                goToNext();
                                resetAutoPlay();
                            }
                        });
                    }

                    // Dot Navigation
                    sliderDots.forEach(function(dot, index) {
                        dot.addEventListener('click', function() {
                            if (!isAnimating && index !== currentSlide) {
                                goToSlide(index);
                                resetAutoPlay();
                            }
                        });
                    });

                    // Keyboard Navigation
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'ArrowLeft' && !isAnimating) {
                            goToPrev();
                            resetAutoPlay();
                        } else if (e.key === 'ArrowRight' && !isAnimating) {
                            goToNext();
                            resetAutoPlay();
                        }
                    });

                    // Touch/Swipe Support
                    let touchStartX = 0;
                    let touchEndX = 0;
                    const heroSlider = document.querySelector('.hero-slider');

                    if (heroSlider) {
                        heroSlider.addEventListener('touchstart', function(e) {
                            touchStartX = e.changedTouches[0].screenX;
                        }, {
                            passive: true
                        });

                        heroSlider.addEventListener('touchend', function(e) {
                            touchEndX = e.changedTouches[0].screenX;
                            handleSwipe();
                        }, {
                            passive: true
                        });
                    }

                    function handleSwipe() {
                        const swipeThreshold = 50;
                        const diff = touchStartX - touchEndX;

                        if (Math.abs(diff) > swipeThreshold && !isAnimating) {
                            if (diff > 0) {
                                goToNext();
                            } else {
                                goToPrev();
                            }
                            resetAutoPlay();
                        }
                    }

                    // Pause on hover
                    const heroSliderContainer = document.querySelector('.hero-slider');
                    if (heroSliderContainer) {
                        heroSliderContainer.addEventListener('mouseenter', function() {
                            clearInterval(slideInterval);
                            if (slideProgress) {
                                slideProgress.style.animationPlayState = 'paused';
                            }
                        });

                        heroSliderContainer.addEventListener('mouseleave', function() {
                            startAutoPlay();
                            if (slideProgress) {
                                slideProgress.style.animationPlayState = 'running';
                            }
                        });
                    }
                }

                function goToNext() {
                    const next = (currentSlide + 1) % totalSlides;
                    goToSlide(next);
                }

                function goToPrev() {
                    const prev = (currentSlide - 1 + totalSlides) % totalSlides;
                    goToSlide(prev);
                }

                function goToSlide(index) {
                    if (index === currentSlide || isAnimating) return;

                    isAnimating = true;

                    // Remove active from all slides and dots
                    sliderItems.forEach(function(item) {
                        item.classList.remove('active');
                    });

                    sliderDots.forEach(function(dot) {
                        dot.classList.remove('active', 'bg-yellow-400', 'scale-125');
                        dot.classList.add('bg-white/40');
                    });

                    // Update current slide
                    currentSlide = index;

                    // Add active to current slide and dot
                    sliderItems[currentSlide].classList.add('active');

                    if (sliderDots[currentSlide]) {
                        sliderDots[currentSlide].classList.add('active', 'bg-yellow-400', 'scale-125');
                        sliderDots[currentSlide].classList.remove('bg-white/40');
                    }

                    // Update counter
                    updateCounter();

                    // Reset progress bar
                    resetProgressBar();

                    // Allow next animation after transition
                    setTimeout(function() {
                        isAnimating = false;
                    }, 800);
                }

                function updateSlide(index) {
                    sliderItems.forEach(function(item, i) {
                        if (i === index) {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });

                    sliderDots.forEach(function(dot, i) {
                        if (i === index) {
                            dot.classList.add('active', 'bg-yellow-400', 'scale-125');
                            dot.classList.remove('bg-white/40');
                        } else {
                            dot.classList.remove('active', 'bg-yellow-400', 'scale-125');
                            dot.classList.add('bg-white/40');
                        }
                    });

                    currentSlide = index;
                    updateCounter();
                    resetProgressBar();
                }

                function updateCounter() {
                    if (currentSlideEl) {
                        currentSlideEl.textContent = String(currentSlide + 1).padStart(2, '0');
                    }
                }

                function resetProgressBar() {
                    if (slideProgress) {
                        slideProgress.classList.remove('animating');
                        void slideProgress.offsetWidth; // Trigger reflow
                        slideProgress.classList.add('animating');
                    }
                }

                function startAutoPlay() {
                    clearInterval(slideInterval);
                    slideInterval = setInterval(function() {
                        if (!isAnimating) {
                            goToNext();
                        }
                    }, autoPlayDelay);
                    resetProgressBar();
                }

                function resetAutoPlay() {
                    clearInterval(slideInterval);
                    startAutoPlay();
                }

                // ============================================
                // SCROLL ANIMATIONS
                // ============================================
                const animateOnScroll = new IntersectionObserver((entries) => {
                    entries.forEach((entry, index) => {
                        if (entry.isIntersecting) {
                            setTimeout(() => {
                                entry.target.classList.add('animate');
                            }, index * 100);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                document.querySelectorAll(
                        '.event-card, .post-card, .testimonial-card, .category-card, .upcoming-event-card')
                    .forEach(el => {
                        animateOnScroll.observe(el);
                    });

                // ============================================
                // SMOOTH REVEAL FOR SECTIONS
                // ============================================
                const revealSections = document.querySelectorAll('section');
                const sectionObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, {
                    threshold: 0.1
                });

                revealSections.forEach(section => {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(20px)';
                    section.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
                    sectionObserver.observe(section);
                });

                // First section should be visible immediately
                if (revealSections[0]) {
                    revealSections[0].style.opacity = '1';
                    revealSections[0].style.transform = 'translateY(0)';
                }
            });
        </script>
    @endpush
@endsection
