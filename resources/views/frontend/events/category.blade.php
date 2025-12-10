@extends('layouts.app')

@section('title', 'Event ' . $category->name)

@section('content')
    {{-- ============================================================================ --}}
    {{-- CATEGORY HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                {{-- Breadcrumb --}}
                <nav class="flex items-center space-x-2 text-sm text-blue-100 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('events.index') }}" class="hover:text-white">Event</a>
                    <span>/</span>
                    <span class="text-white font-semibold">{{ $category->name }}</span>
                </nav>

                <div class="flex items-center mb-4">
                    @if ($category->icon)
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                            <span class="text-3xl">{{ $category->icon }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold">{{ $category->name }}</h1>
                        <p class="text-lg text-blue-100 mt-2">{{ $events->total() }} event tersedia</p>
                    </div>
                </div>

                @if ($category->description)
                    <p class="text-lg text-blue-100">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- EVENTS GRID --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                @if ($events->count() > 0)
                    {{-- Events Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($events as $event)
                            <a href="{{ route('events.show', $event->slug) }}"
                                class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all overflow-hidden group">
                                {{-- Event Image --}}
                                @if ($event->featured_image)
                                    <div class="relative h-56 overflow-hidden">
                                        <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">

                                        {{-- Badges --}}
                                        <div class="absolute top-4 right-4 flex flex-col gap-2">
                                            @if ($event->is_featured)
                                                <span
                                                    class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                    ⭐ FEATURED
                                                </span>
                                            @endif
                                            @if ($event->is_free)
                                                <span
                                                    class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                    GRATIS
                                                </span>
                                            @endif
                                            @if ($event->isUpcoming())
                                                <span
                                                    class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                    UPCOMING
                                                </span>
                                            @elseif($event->isOngoing())
                                                <span
                                                    class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                                                    🔴 LIVE
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Category Badge --}}
                                        <div class="absolute top-4 left-4">
                                            <span
                                                class="px-3 py-1 bg-black/60 text-white text-xs font-semibold rounded-full backdrop-blur-sm">
                                                {{ $event->category->name }}
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Event Content --}}
                                <div class="p-6">
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#0053C5] transition-colors">
                                        {{ $event->title }}
                                    </h3>

                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $event->excerpt }}</p>

                                    {{-- Event Meta --}}
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $event->start_datetime->format('d M Y, H:i') }} WIB</span>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span class="line-clamp-1">{{ $event->location }}</span>
                                        </div>

                                        @if ($event->max_participants)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span>{{ $event->current_participants }}/{{ $event->max_participants }}
                                                    peserta</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Quota Bar --}}
                                    @if ($event->max_participants)
                                        <div class="mb-4">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] h-2 rounded-full transition-all"
                                                    style="width: {{ ($event->current_participants / $event->max_participants) * 100 }}%">
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ $event->available_slots }} slot tersisa
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Price & CTA --}}
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                        @if ($event->is_free)
                                            <span class="text-2xl font-bold text-green-600">GRATIS</span>
                                        @else
                                            <div>
                                                <span class="text-xs text-gray-500">Mulai dari</span>
                                                <p class="text-2xl font-bold text-[#0053C5]">
                                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        @endif

                                        <span
                                            class="px-4 py-2 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-bold rounded-lg group-hover:shadow-lg transition-all">
                                            Lihat Detail →
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $events->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Event</h3>
                        <p class="text-gray-600 mb-6">
                            Saat ini belum ada event di kategori {{ $category->name }}. <br>
                            Pantau terus untuk update event terbaru!
                        </p>
                        <a href="{{ route('events.index') }}"
                            class="inline-block px-8 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-bold rounded-xl hover:shadow-xl transition-all">
                            Lihat Semua Event
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection