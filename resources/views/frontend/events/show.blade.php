@extends('layouts.app')

@section('title', $event->title . ' - Ramadhan 1447 H')

@section('content')
    {{-- ============================================================================ --}}
    {{-- EVENT HEADER --}}
    {{-- ============================================================================ --}}
    <section
        class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white pt-8 pb-32 overflow-hidden">
        {{-- Animated Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>

        {{-- Decorative Elements --}}
        <div class="absolute top-20 right-10 w-64 h-64 border border-white/5 rounded-full"></div>
        <div class="absolute bottom-40 left-10 w-32 h-32 border border-white/5 rounded-2xl rotate-12"></div>

        <div class="container mx-auto px-4 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="flex items-center flex-wrap gap-2 text-sm mb-8">
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
                <a href="{{ route('events.index') }}" class="text-slate-400 hover:text-white transition-colors">Event</a>
                @if ($event->category)
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('events.category', $event->category->slug) }}"
                        class="text-slate-400 hover:text-white transition-colors">
                        {{ $event->category->name }}
                    </a>
                @endif
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-white font-medium">{{ Str::limit($event->title, 40) }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                {{-- Main Info (3 columns) --}}
                <div class="lg:col-span-3">
                    {{-- Status Badges --}}
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        @if ($event->category)
                            <a href="{{ route('events.category', $event->category->slug) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 backdrop-blur-sm border border-white/20 text-white rounded-lg text-sm font-medium hover:bg-white/20 transition-all">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                                {{ $event->category->name }}
                            </a>
                        @endif

                        @if ($event->is_featured)
                            <span
                                class="inline-flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-lg text-sm font-semibold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Featured
                            </span>
                        @endif

                        @if ($event->is_free)
                            <span
                                class="inline-flex items-center gap-1.5 bg-emerald-500 text-white px-3 py-1.5 rounded-lg text-sm font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                GRATIS
                            </span>
                        @endif

                        @if ($event->isUpcoming())
                            <span
                                class="inline-flex items-center gap-1.5 bg-blue-500/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-sm font-semibold">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                Akan Datang
                            </span>
                        @elseif ($event->isOngoing())
                            <span
                                class="inline-flex items-center gap-1.5 bg-emerald-500/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-sm font-semibold">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                Sedang Berlangsung
                            </span>
                        @elseif ($event->isPast())
                            <span
                                class="inline-flex items-center gap-1.5 bg-slate-500/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-sm font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Selesai
                            </span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 leading-tight tracking-tight">
                        {{ $event->title }}
                    </h1>

                    {{-- Short Description --}}
                    <p class="text-lg text-slate-300 mb-8 leading-relaxed max-w-2xl">
                        {{ $event->description }}
                    </p>

                    {{-- Meta Info Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Date & Time --}}
                        <div
                            class="group bg-white/5 backdrop-blur-sm rounded-2xl p-5 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-slate-400 text-sm mb-1">Tanggal & Waktu</p>
                                    <p class="text-white font-semibold text-lg">
                                        {{ $event->start_datetime->format('d M Y') }}</p>
                                    <p class="text-slate-300 text-sm">{{ $event->start_datetime->format('H:i') }} -
                                        {{ $event->end_datetime->format('H:i') }} WIB</p>
                                </div>
                            </div>
                        </div>

                        {{-- Location --}}
                        <div
                            class="group bg-white/5 backdrop-blur-sm rounded-2xl p-5 border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-rose-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-slate-400 text-sm mb-1">Lokasi</p>
                                    <p class="text-white font-semibold truncate">{{ $event->location }}</p>
                                    @if ($event->location_maps_url)
                                        <a href="{{ $event->location_maps_url }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-rose-400 hover:text-rose-300 text-sm mt-1 transition-colors">
                                            <span>Lihat di Maps</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Event Image (2 columns) --}}
                <div class="lg:col-span-2">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-black/30">
                        @if ($event->featured_image)
                            <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}"
                                class="w-full aspect-[4/3] object-cover">
                        @else
                            <div
                                class="w-full aspect-[4/3] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Image Overlay Gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- REGISTRATION CARD (Floating) --}}
    {{-- ============================================================================ --}}
    <section class="relative z-20 -mt-20">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/50 overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-0 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
                        {{-- Price --}}
                        <div class="p-6 lg:p-8 text-center lg:text-left">
                            <p class="text-sm text-slate-500 mb-1">Biaya Pendaftaran</p>
                            @if ($event->is_free)
                                <p class="text-3xl font-bold text-emerald-600">GRATIS</p>
                            @else
                                <p class="text-3xl font-bold text-slate-900">Rp
                                    {{ number_format($event->price, 0, ',', '.') }}</p>
                            @endif
                        </div>

                        {{-- Quota --}}
                        <div class="p-6 lg:p-8">
                            <p class="text-sm text-slate-500 mb-2">Kuota Peserta</p>
                            @if ($event->max_participants)
                                <div class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between text-sm mb-1.5">
                                            <span
                                                class="font-semibold text-slate-900">{{ $event->current_participants }}/{{ $event->max_participants }}</span>
                                            <span class="text-slate-500">{{ $event->available_slots }} tersisa</span>
                                        </div>
                                        @php
                                            $percentage =
                                                ($event->current_participants / $event->max_participants) * 100;
                                            $barColor =
                                                $percentage >= 90
                                                    ? 'from-red-500 to-red-600'
                                                    : ($percentage >= 70
                                                        ? 'from-amber-500 to-amber-600'
                                                        : 'from-emerald-500 to-emerald-600');
                                        @endphp
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r {{ $barColor }} h-full rounded-full transition-all duration-500"
                                                style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="font-semibold text-slate-900">Tidak Terbatas</p>
                            @endif
                        </div>

                        {{-- Deadline --}}
                        <div class="p-6 lg:p-8">
                            <p class="text-sm text-slate-500 mb-1">Batas Pendaftaran</p>
                            @if ($event->registration_end)
                                <p class="font-semibold text-slate-900">{{ $event->registration_end->format('d M Y') }}
                                </p>
                                <p class="text-sm text-slate-500">{{ $event->registration_end->format('H:i') }} WIB</p>
                            @else
                                <p class="font-semibold text-slate-900">Sampai kuota penuh</p>
                            @endif
                        </div>

                        {{-- CTA Button --}}
                        <div class="p-6 lg:p-8 flex items-center justify-center">
                            @if ($userRegistration)
                                {{-- Already Registered --}}
                                <div class="w-full">
                                    <div
                                        class="flex items-center gap-2 text-emerald-600 mb-3 justify-center lg:justify-start">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-semibold text-sm">Sudah Terdaftar</span>
                                    </div>
                                    <a href="{{ route('registrations.show', $userRegistration->registration_code) }}"
                                        class="block w-full text-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/25 transition-all duration-300">
                                        Lihat Detail
                                    </a>
                                </div>
                            @elseif (!$event->canRegister())
                                {{-- Cannot Register --}}
                                <button disabled
                                    class="w-full px-6 py-3.5 bg-slate-200 text-slate-500 rounded-xl font-semibold cursor-not-allowed">
                                    @if ($event->is_full)
                                        Kuota Penuh
                                    @elseif ($event->registration_end && $event->registration_end->isPast())
                                        Pendaftaran Ditutup
                                    @else
                                        Tidak Tersedia
                                    @endif
                                </button>
                            @else
                                {{-- Can Register --}}
                                <a href="{{ route('registrations.create', $event->slug) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/25 transform hover:scale-[1.02] transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Daftar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- EVENT DETAILS CONTENT --}}
    {{-- ============================================================================ --}}
    <section class="py-12 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-8">
                        {{-- Full Description --}}
                        <div class="bg-white rounded-2xl border border-slate-200/50 p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-slate-900">Tentang Event</h2>
                            </div>
                            <div
                                class="prose prose-slate prose-lg max-w-none prose-headings:text-slate-900 prose-p:text-slate-600 prose-a:text-blue-600 prose-strong:text-slate-900">
                                {!! $event->full_description !!}
                            </div>
                        </div>

                        {{-- Requirements --}}
                        @if ($event->requirements)
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-8">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900">Persyaratan Peserta</h3>
                                </div>
                                <p class="text-slate-700 leading-relaxed">{{ $event->requirements }}</p>
                            </div>
                        @endif

                        {{-- Tags --}}
                        @if ($event->tags->isNotEmpty())
                            <div class="bg-white rounded-2xl border border-slate-200/50 p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900">Tags</h3>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($event->tags as $tag)
                                        <a href="{{ route('events.tag', $tag->slug) }}"
                                            class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-700 rounded-xl text-sm font-medium transition-all duration-200">
                                            <span class="text-slate-400 mr-1">#</span>{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Testimonials --}}
                        @if ($testimonials->isNotEmpty())
                            <div class="bg-white rounded-2xl border border-slate-200/50 p-8">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </div>
                                        <h2 class="text-xl font-bold text-slate-900">Testimoni Peserta</h2>
                                    </div>
                                    <a href="{{ route('testimonials') }}"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                        Lihat Semua
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($testimonials as $testimonial)
                                        <div
                                            class="bg-slate-50 rounded-xl p-5 border border-slate-100 hover:border-slate-200 transition-colors">
                                            {{-- Rating --}}
                                            <div class="flex items-center gap-1 mb-3">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $testimonial->overall_rating ? 'text-amber-400' : 'text-slate-200' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            {{-- Message --}}
                                            <blockquote class="text-slate-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                                "{{ $testimonial->message }}"
                                            </blockquote>

                                            {{-- Author --}}
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ substr($testimonial->name ?? ($testimonial->user?->name ?? 'A'), 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900 text-sm">
                                                        {{ $testimonial->name ?? ($testimonial->user?->name ?? 'Anonymous') }}
                                                    </p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $testimonial->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Contact Info --}}
                        <div class="bg-white rounded-2xl border border-slate-200/50 p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Kontak</h3>
                            </div>

                            <div class="space-y-4">
                                @if ($event->contact_person)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 mb-0.5">Contact Person</p>
                                            <p class="font-semibold text-slate-900 text-sm">{{ $event->contact_person }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if ($event->contact_phone)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 mb-0.5">WhatsApp</p>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $event->contact_phone) }}"
                                                target="_blank"
                                                class="font-semibold text-emerald-600 hover:text-emerald-700 text-sm transition-colors">
                                                {{ $event->contact_phone }}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if ($event->contact_email)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 mb-0.5">Email</p>
                                            <a href="mailto:{{ $event->contact_email }}"
                                                class="font-semibold text-blue-600 hover:text-blue-700 text-sm transition-colors break-all">
                                                {{ $event->contact_email }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Share Section --}}
                        <div class="bg-white rounded-2xl border border-slate-200/50 p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Bagikan Event</h3>
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank"
                                    class="group flex items-center justify-center h-12 bg-slate-100 hover:bg-[#1877F2] rounded-xl transition-all duration-300"
                                    title="Share to Facebook">
                                    <svg class="w-5 h-5 text-slate-500 group-hover:text-white transition-colors"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($event->title) }}"
                                    target="_blank"
                                    class="group flex items-center justify-center h-12 bg-slate-100 hover:bg-[#1DA1F2] rounded-xl transition-all duration-300"
                                    title="Share to Twitter">
                                    <svg class="w-5 h-5 text-slate-500 group-hover:text-white transition-colors"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="group flex items-center justify-center h-12 bg-slate-100 hover:bg-[#25D366] rounded-xl transition-all duration-300"
                                    title="Share to WhatsApp">
                                    <svg class="w-5 h-5 text-slate-500 group-hover:text-white transition-colors"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                </a>
                                <button onclick="copyToClipboard('{{ url()->current() }}')"
                                    class="group flex items-center justify-center h-12 bg-slate-100 hover:bg-slate-700 rounded-xl transition-all duration-300"
                                    title="Copy Link">
                                    <svg class="w-5 h-5 text-slate-500 group-hover:text-white transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Related Events --}}
                        @if ($relatedEvents->isNotEmpty())
                            <div class="bg-white rounded-2xl border border-slate-200/50 p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900">Event Terkait</h3>
                                </div>

                                <div class="space-y-3">
                                    @foreach ($relatedEvents as $related)
                                        <a href="{{ route('events.show', $related->slug) }}"
                                            class="group flex gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                                            <div class="w-16 h-16 flex-shrink-0 rounded-xl overflow-hidden bg-slate-100">
                                                @if ($related->featured_image)
                                                    <img src="{{ Storage::url($related->featured_image) }}"
                                                        alt="{{ $related->title }}"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100">
                                                        <svg class="w-6 h-6 text-blue-300" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors text-sm line-clamp-2 mb-1">
                                                    {{ $related->title }}
                                                </h4>
                                                <div class="flex items-center text-xs text-slate-500">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $related->start_datetime->format('d M Y') }}
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- MOBILE STICKY CTA --}}
    {{-- ============================================================================ --}}
    @if (!$userRegistration && $event->canRegister())
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 lg:hidden z-50 shadow-lg">
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    @if ($event->is_free)
                        <p class="text-lg font-bold text-emerald-600">GRATIS</p>
                    @else
                        <p class="text-lg font-bold text-slate-900">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                    @endif
                    @if ($event->max_participants)
                        <p class="text-xs text-slate-500">{{ $event->available_slots }} slot tersisa</p>
                    @endif
                </div>
                <a href="{{ route('registrations.create', $event->slug) }}"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    @endif
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

        /* Prose customization */
        .prose img {
            border-radius: 1rem;
        }

        .prose a {
            text-decoration: none;
        }

        .prose a:hover {
            text-decoration: underline;
        }

        /* Add padding for mobile sticky CTA */
        @media (max-width: 1023px) {
            section:last-of-type {
                padding-bottom: 100px;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className =
                    'fixed bottom-24 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300';
                toast.innerHTML = < div class = "flex items-center gap-2" > < svg class = "w-5 h-5 text-emerald-400"
                fill = "none"
                stroke = "currentColor"
                viewBox = "0 0 24 24" > < path stroke - linecap = "round"
                stroke - linejoin = "round"
                stroke - width = "2"
                d = "M5 13l4 4L19 7" / > < /svg>                         <span>Link berhasil disalin!</span > <
                    /div>                ;
                document.body.appendChild(toast);
                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endpush
