@extends('layouts.app')

@section('title', 'Daftar Event - Ramadhan 1447 H')

@section('content')

    {{-- Hero Section - Redesigned with animated background --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-[#0053C5] to-slate-900 py-20 overflow-hidden">
        {{-- Animated Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>

        {{-- Islamic Pattern Overlay --}}
        <div class="absolute inset-0 opacity-5"
            style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 80 80\"><path fill=\"%23fff\" d=\"M40 0L0 40l40 40 40-40z\" opacity=\"0.1\"/></svg>'); background-size: 40px 40px;">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                {{-- Decorative Badge --}}
                <div
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-6">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-blue-100">Ramadhan 1447 H</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight">
                    Temukan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">Event
                        Spesial</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Berbagai kegiatan spiritual, kajian ilmu, tilawah, dan program menarik selama bulan penuh berkah
                </p>

                {{-- Search Bar - Glassmorphism Style --}}
                <form action="{{ route('events.index') }}" method="GET" class="relative max-w-2xl mx-auto">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-emerald-500 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-500">
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
                                    placeholder="Cari event, lokasi, atau kategori..."
                                    class="flex-1 bg-transparent text-white placeholder-slate-400 px-4 py-4 focus:outline-none text-lg">
                                <button type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/25">
                                    Cari Event
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Quick Stats --}}
                <div class="flex flex-wrap justify-center gap-8 mt-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $events->total() }}</div>
                        <div class="text-sm text-slate-400">Total Event</div>
                    </div>
                    <div class="w-px h-12 bg-white/20 hidden md:block"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-400">{{ $categories->count() }}</div>
                        <div class="text-sm text-slate-400">Kategori</div>
                    </div>
                    <div class="w-px h-12 bg-white/20 hidden md:block"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-400">30</div>
                        <div class="text-sm text-slate-400">Hari Berkah</div>
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

    {{-- Filter Section - Modernized --}}
    <section class="bg-slate-50 py-6 sticky top-0 z-40 border-b border-slate-200/50 backdrop-blur-lg bg-slate-50/90">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                {{-- Active Filters Display --}}
                <div class="flex items-center gap-3 text-sm">
                    @if (request('search') || request('category') || request('filter'))
                        <span class="text-slate-500">Filter aktif:</span>
                        @if (request('search'))
                            <span
                                class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('search') }}"
                            </span>
                        @endif
                        @if (request('category'))
                            <span
                                class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ request('category') }}
                            </span>
                        @endif
                        <a href="{{ route('events.index') }}"
                            class="text-slate-500 hover:text-red-500 transition-colors p-1.5 hover:bg-red-50 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @else
                        <span class="text-slate-600 font-medium">{{ $events->total() }} event tersedia</span>
                    @endif
                </div>

                {{-- Filter Pills --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm text-slate-500 mr-2 hidden md:inline">Status:</span>
                    <a href="{{ route('events.index', request()->except('filter')) }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ !request('filter') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('events.index', array_merge(request()->except('filter'), ['filter' => 'upcoming'])) }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('filter') == 'upcoming' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        <span class="flex items-center gap-1.5">
                            <span
                                class="w-1.5 h-1.5 bg-blue-400 rounded-full {{ request('filter') == 'upcoming' ? '' : 'bg-slate-400' }}"></span>
                            Akan Datang
                        </span>
                    </a>
                    <a href="{{ route('events.index', array_merge(request()->except('filter'), ['filter' => 'ongoing'])) }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('filter') == 'ongoing' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        <span class="flex items-center gap-1.5">
                            <span
                                class="w-1.5 h-1.5 rounded-full animate-pulse {{ request('filter') == 'ongoing' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                            Berlangsung
                        </span>
                    </a>
                    <a href="{{ route('events.index', array_merge(request()->except('filter'), ['filter' => 'available'])) }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('filter') == 'available' ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Slot Tersedia
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Section - Card Style --}}
    @if ($categories->count() > 0)
        <section class="bg-slate-50 pb-8">
            <div class="container mx-auto px-4">
                <div class="flex items-center gap-4 overflow-x-auto pb-2 scrollbar-hide">
                    <span class="text-sm text-slate-500 font-medium whitespace-nowrap">Kategori:</span>
                    @foreach ($categories as $category)
                        <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                            class="group flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 {{ request('category') == $category->slug ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-600/25' : 'bg-white text-slate-700 hover:bg-white hover:shadow-md border border-slate-200' }}">
                            <span>{{ $category->name }}</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-xs {{ request('category') == $category->slug ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-600' }} transition-colors">
                                {{ $category->events_count ?? 0 }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Events Grid Section --}}
    <section class="py-12 bg-slate-50">
        <div class="container mx-auto px-4">
            @if ($events->count() > 0)
                {{-- View Toggle (Optional Enhancement) --}}
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800">
                        @if (request('filter') == 'upcoming')
                            Event Akan Datang
                        @elseif(request('filter') == 'ongoing')
                            Event Sedang Berlangsung
                        @elseif(request('category'))
                            Event {{ request('category') }}
                        @else
                            Semua Event
                        @endif
                    </h2>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500">Urutkan:</span>
                        <select
                            class="bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option>Terbaru</option>
                            <option>Terdekat</option>
                            <option>Populer</option>
                        </select>
                    </div>
                </div>

                {{-- Events Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($events as $event)
                        <article
                            class="group bg-white rounded-2xl overflow-hidden border border-slate-200/50 hover:border-slate-300 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                            {{-- Event Image --}}
                            <div
                                class="relative aspect-[16/10] bg-gradient-to-br from-slate-100 to-slate-50 overflow-hidden">
                                @if ($event->featured_image)
                                    <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                                        <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Overlay Gradient --}}
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                {{-- Top Badges --}}
                                <div class="absolute top-4 left-4 right-4 flex justify-between items-start">
                                    {{-- Featured Badge --}}
                                    @if ($event->is_featured)
                                        <div
                                            class="flex items-center gap-1.5 bg-amber-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-lg">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            Featured
                                        </div>
                                    @else
                                        <div></div>
                                    @endif

                                    {{-- Status Badge --}}
                                    @if ($event->status == 'published')
                                        @if ($event->start_date > now())
                                            <span
                                                class="inline-flex items-center gap-1.5 bg-blue-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                                Akan Datang
                                            </span>
                                        @elseif($event->end_date < now())
                                            <span
                                                class="bg-slate-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                                                Selesai
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 bg-emerald-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                                Berlangsung
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                {{-- Quick Action on Hover --}}
                                <div
                                    class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                    <a href="{{ route('events.show', $event->slug) }}"
                                        class="flex items-center justify-center gap-2 w-full bg-white/95 backdrop-blur-sm text-slate-900 py-3 rounded-xl font-semibold hover:bg-white transition-colors">
                                        Lihat Detail
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Event Content --}}
                            <div class="p-5">
                                {{-- Category & Price Row --}}
                                <div class="flex items-center justify-between mb-3">
                                    @if ($event->category)
                                        <a href="{{ route('events.category', $event->category->slug) }}"
                                            class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full mr-2"></span>
                                            {{ $event->category->name }}
                                        </a>
                                    @else
                                        <span></span>
                                    @endif

                                    @if ($event->price > 0)
                                        <span class="text-sm font-bold text-slate-900">
                                            Rp {{ number_format($event->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg text-xs font-bold">
                                            GRATIS
                                        </span>
                                    @endif
                                </div>

                                {{-- Title --}}
                                <h3
                                    class="text-lg font-bold text-slate-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        {{ $event->title }}
                                    </a>
                                </h3>

                                {{-- Description --}}
                                <p class="text-slate-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $event->excerpt ?? Str::limit(strip_tags($event->description), 100) }}
                                </p>

                                {{-- Divider --}}
                                <div class="border-t border-slate-100 pt-4">
                                    {{-- Meta Info --}}
                                    <div class="flex flex-col gap-2.5 text-sm">
                                        {{-- Date & Time --}}
                                        <div class="flex items-center text-slate-600">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-blue-50 rounded-lg mr-3">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span
                                                    class="font-medium text-slate-900">{{ $event->start_datetime->format('d M Y') }}</span>
                                                <span class="text-slate-400 mx-1.5">•</span>
                                                <span>{{ $event->start_datetime->format('H:i') }} WIB</span>
                                            </div>
                                        </div>

                                        {{-- Location --}}
                                        @if ($event->location)
                                            <div class="flex items-center text-slate-600">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-rose-50 rounded-lg mr-3">
                                                    <svg class="w-4 h-4 text-rose-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <span class="line-clamp-1">{{ $event->location }}</span>
                                            </div>
                                        @endif

                                        {{-- Quota Progress --}}
                                        @if ($event->max_participants)
                                            <div class="mt-2">
                                                <div class="flex items-center justify-between text-xs mb-1.5">
                                                    <span class="text-slate-500">Kuota Peserta</span>
                                                    <span class="font-semibold text-slate-700">
                                                        {{ $event->current_participants ?? 0 }} /
                                                        {{ $event->max_participants }}
                                                    </span>
                                                </div>
                                                @php
                                                    $percentage =
                                                        (($event->current_participants ?? 0) /
                                                            $event->max_participants) *
                                                        100;
                                                    $barColor =
                                                        $percentage >= 90
                                                            ? 'bg-red-500'
                                                            : ($percentage >= 70
                                                                ? 'bg-amber-500'
                                                                : 'bg-emerald-500');
                                                @endphp
                                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-500"
                                                        style="width: {{ min($percentage, 100) }}%"></div>
                                                </div>
                                                @if ($percentage >= 90)
                                                    <p class="text-xs text-red-600 mt-1 font-medium">Hampir penuh!</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action Footer --}}
                                @if ($event->max_participants && ($event->current_participants ?? 0) >= $event->max_participants)
                                    <div class="mt-4 py-3 px-4 bg-red-50 rounded-xl text-center">
                                        <span class="text-red-600 font-semibold text-sm">Kuota Penuh</span>
                                    </div>
                                @elseif($event->end_date < now())
                                    <div class="mt-4 py-3 px-4 bg-slate-100 rounded-xl text-center">
                                        <span class="text-slate-500 font-semibold text-sm">Event Telah Berakhir</span>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($events->hasPages())
                    <div class="mt-12 flex justify-center">
                        <nav class="inline-flex items-center gap-1 bg-white rounded-xl border border-slate-200 p-1.5">
                            {{ $events->links() }}
                        </nav>
                    </div>
                @endif
            @else
                {{-- Empty State - Modern Design --}}
                <div class="max-w-md mx-auto text-center py-20">
                    <div
                        class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Tidak Ada Event</h3>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        @if (request('search'))
                            Pencarian "{{ request('search') }}" tidak menemukan hasil. Coba kata kunci lain.
                        @elseif(request('category'))
                            Belum ada event dalam kategori "{{ request('category') }}".
                        @else
                            Belum ada event yang tersedia saat ini. Nantikan update terbaru!
                        @endif
                    </p>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition-colors">
                        <svgclass="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </a>
                </div>
            @endif
        </div>
    </section>
    {{-- CTA Section - Redesigned --}}
    <section class="relative bg-slate-900 text-white py-20 overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium text-slate-300">Ada pertanyaan?</span>
                </div>

                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Tidak Menemukan Event yang Tepat?
                </h2>
                <p class="text-lg text-slate-400 mb-8 leading-relaxed">
                    Tim kami siap membantu Anda menemukan event yang sesuai atau memberikan informasi lebih lanjut
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-slate-900 rounded-xl font-bold hover:bg-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Hubungi Kami
                    </a>
                    <a href="#"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Lihat FAQ
                    </a>
                </div>
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

        /* Custom Focus States */
        input:focus {
            outline: none;
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
