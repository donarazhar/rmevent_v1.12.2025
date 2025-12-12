@extends('layouts.app')

@section('title', 'Testimoni Peserta - Ramadhan 1447 H')

@section('content')
    {{-- ============================================================================ --}}
    {{-- HERO SECTION --}}
    {{-- ============================================================================ --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-[#0053C5] to-slate-900 py-20 overflow-hidden">
        {{-- Animated Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-rose-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>

        {{-- Decorative Quote Icons --}}
        <div class="absolute top-20 left-10 text-white/5">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
        </div>
        <div class="absolute bottom-20 right-10 text-white/5 rotate-180">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                {{-- Breadcrumb --}}
                <nav class="flex items-center justify-center space-x-2 text-sm mb-8">
                    <a href="{{ route('home') }}"
                        class="text-slate-400 hover:text-white transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Beranda
                    </a>
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-white font-medium">Testimoni</span>
                </nav>

                {{-- Decorative Badge --}}
                <div
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-6">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="text-sm font-medium text-rose-200">Cerita dari Jamaah</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight">
                    Testimoni <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-orange-400">Peserta</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Pengalaman dan kesan nyata dari para jamaah yang telah mengikuti kegiatan Ramadhan Mubarak 1447 H
                </p>

                {{-- Stats Cards --}}
                <div class="flex flex-wrap justify-center gap-6 mt-12">
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-4 min-w-[140px]">
                        <div class="text-3xl font-bold text-white">{{ $testimonials->total() }}</div>
                        <div class="text-sm text-slate-400">Total Testimoni</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-4 min-w-[140px]">
                        <div class="text-3xl font-bold text-amber-400 flex items-center justify-center gap-1">
                            4.8
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <div class="text-sm text-slate-400">Rating Rata-rata</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-4 min-w-[140px]">
                        <div class="text-3xl font-bold text-emerald-400">{{ $featuredTestimonials->count() }}</div>
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
    {{-- FEATURED TESTIMONIALS --}}
    {{-- ============================================================================ --}}
    @if ($featuredTestimonials->count() > 0)
        <section class="py-16 bg-slate-50">
            <div class="container mx-auto px-4">
                <div class="max-w-7xl mx-auto">
                    {{-- Section Header --}}
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 rounded-full px-4 py-2 mb-4">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-sm font-semibold">Pilihan Editor</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-3">Testimoni Terbaik</h2>
                        <p class="text-slate-600 max-w-2xl mx-auto">Kisah inspiratif dari para jamaah yang memberikan kesan
                            mendalam</p>
                    </div>

                    {{-- Featured Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach ($featuredTestimonials as $index => $testimonial)
                            <div class="group relative">
                                {{-- Card Glow Effect --}}
                                <div
                                    class="absolute -inset-1 bg-gradient-to-r from-rose-500 to-orange-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500">
                                </div>

                                <div
                                    class="relative bg-white rounded-2xl p-8 border border-slate-200/50 hover:border-slate-300 transition-all duration-300">
                                    {{-- Quote Icon --}}
                                    <div class="absolute -top-4 left-8">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-rose-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-rose-500/25">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Rating Stars --}}
                                    <div class="flex items-center gap-1 mb-6 pt-4">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $testimonial->overall_rating ? 'text-amber-400' : 'text-slate-200' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        <span
                                            class="ml-2 text-sm font-medium text-slate-500">{{ $testimonial->overall_rating }}.0</span>
                                    </div>

                                    {{-- Testimonial Content --}}
                                    <blockquote class="text-slate-700 text-lg leading-relaxed mb-6 line-clamp-4">
                                        "{{ $testimonial->message }}"
                                    </blockquote>

                                    {{-- Author Info --}}
                                    <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
                                        <div
                                            class="w-12 h-12 rounded-full bg-gradient-to-br from-rose-500 to-orange-500 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-rose-500/20">
                                            {{ substr($testimonial->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-900">{{ $testimonial->name }}</h4>
                                            @if ($testimonial->event)
                                                <p class="text-sm text-rose-600 font-medium">
                                                    {{ $testimonial->event->title }}</p>
                                            @endif
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                {{ $testimonial->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- FILTER SECTION --}}
    {{-- ============================================================================ --}}
    <section class="bg-white py-4 sticky top-0 z-40 border-b border-slate-200/50 backdrop-blur-lg bg-white/90">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    {{-- Filter by Rating --}}
                    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-2 md:pb-0">
                        <span class="text-sm text-slate-500 font-medium whitespace-nowrap mr-2">Rating:</span>
                        <a href="{{ route('testimonials') }}"
                            class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 {{ !request('rating') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Semua
                        </a>
                        @for ($rating = 5; $rating >= 3; $rating--)
                            <a href="{{ route('testimonials', ['rating' => $rating]) }}"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 {{ request('rating') == $rating ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <svg class="w-4 h-4 {{ request('rating') == $rating ? 'text-white' : 'text-amber-400' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                {{ $rating }}+
                            </a>
                        @endfor
                    </div>

                    {{-- Sort Options --}}
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500 font-medium">Urutkan:</span>
                        <div class="relative">
                            <select
                                class="appearance-none bg-slate-100 text-slate-700 pl-4 pr-10 py-2.5 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:bg-white cursor-pointer hover:bg-slate-200 transition-colors">
                                <option>Terbaru</option>
                                <option>Rating Tertinggi</option>
                                <option>Rating Terendah</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- ALL TESTIMONIALS --}}
    {{-- ============================================================================ --}}
    <section class="py-12 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                {{-- Section Header --}}
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Semua Testimoni</h2>
                        <p class="text-slate-500 text-sm mt-1">{{ $testimonials->total() }} testimoni dari jamaah</p>
                    </div>
                    <a href="{{ route('feedback.create') }}"
                        class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-rose-500 to-orange-500 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tulis Testimoni
                    </a>
                </div>

                @if ($testimonials->count() > 0)
                    {{-- Testimonials Masonry Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($testimonials as $testimonial)
                            <article
                                class="group bg-white rounded-2xl border border-slate-200/50 hover:border-slate-300 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 overflow-hidden">
                                <div class="p-6">
                                    {{-- Header: Rating & Badge --}}
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $testimonial->overall_rating ? 'text-amber-400' : 'text-slate-200' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                            <span
                                                class="ml-1.5 text-xs font-semibold text-slate-500">({{ $testimonial->overall_rating }}/5)</span>
                                        </div>

                                        @if ($testimonial->is_featured)
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Pilihan
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Event Link --}}
                                    @if ($testimonial->event)
                                        <a href="{{ route('events.show', $testimonial->event->slug) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 mb-3 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $testimonial->event->title }}
                                        </a>
                                    @endif

                                    {{-- Message --}}
                                    <blockquote class="text-slate-700 leading-relaxed mb-4 line-clamp-4">
                                        "{{ $testimonial->message }}"
                                    </blockquote>

                                    {{-- Read More (if long message) --}}
                                    @if (strlen($testimonial->message) > 200)
                                        <button
                                            class="text-rose-600 hover:text-rose-700 text-sm font-medium mb-4 transition-colors">
                                            Baca selengkapnya...
                                        </button>
                                    @endif
                                </div>

                                {{-- Author Footer --}}
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-rose-500 to-orange-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                            {{ substr($testimonial->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-slate-900 text-sm truncate">
                                                {{ $testimonial->name }}</p>
                                            <p class="text-xs text-slate-500">
                                                {{ $testimonial->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div class="text-slate-300">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($testimonials->hasPages())
                        <div class="mt-12 flex justify-center">
                            <nav
                                class="inline-flex items-center gap-1 bg-white rounded-xl border border-slate-200 p-1.5 shadow-sm">
                                {{ $testimonials->links() }}
                            </nav>
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="max-w-md mx-auto text-center py-16">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-rose-100 to-orange-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Belum Ada Testimoni</h3>
                        <p class="text-slate-600 mb-8 leading-relaxed">
                            Jadilah yang pertama berbagi pengalaman dan kesan Anda mengikuti kegiatan kami!
                        </p>
                        <a href="{{ route('feedback.create') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-rose-500 to-orange-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-rose-500/25 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Tulis Testimoni Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
    {{-- ============================================================================ --}}
    {{-- CTA SECTION --}}
    {{-- ============================================================================ --}}
    <section class="relative bg-slate-900 py-20 overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-rose-600/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-600/20 rounded-full blur-3xl"></div>
        </div>

        {{-- Decorative Quote Icons --}}
        <div class="absolute top-10 left-10 text-white/5">
            <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                {{-- Icon --}}
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-500 to-orange-500 rounded-2xl mb-6 shadow-lg shadow-rose-500/25">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>

                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Bagikan Pengalaman Anda!
                </h2>
                <p class="text-lg text-slate-400 mb-10 leading-relaxed max-w-xl mx-auto">
                    Sudah mengikuti event kami? Ceritakan pengalaman Anda dan bantu jamaah lain untuk mengenal kegiatan kami
                    lebih dekat.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('feedback.create') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-rose-500 to-orange-500 text-white rounded-xl font-bold hover:shadow-xl hover:shadow-rose-500/25 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Tulis Testimoni
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Lihat Event
                    </a>
                </div>

                {{-- Trust Indicators --}}
                <div class="flex flex-wrap justify-center gap-8 mt-12 pt-8 border-t border-white/10">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm">Terverifikasi</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="text-sm">Privasi Terjaga</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-sm">Proses Cepat</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- MOBILE FLOATING CTA --}}
    {{-- ============================================================================ --}}
    <div class="fixed bottom-6 right-6 md:hidden z-50">
        <a href="{{ route('feedback.create') }}"
            class="flex items-center justify-center w-14 h-14 bg-gradient-to-r from-rose-500 to-orange-500 text-white rounded-full shadow-lg shadow-rose-500/30 hover:shadow-xl hover:shadow-rose-500/40 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </a>
    </div>
@endsection
@push('styles')
    <style>
        /* Line Clamp */
        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
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
