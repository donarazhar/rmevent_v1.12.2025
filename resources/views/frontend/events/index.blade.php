@extends('layouts.app')

@section('title', 'Daftar Event - Ramadhan 1447 H')

@section('content')

    {{-- Hero Section --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Event Ramadhan 1447 H</h1>
                <p class="text-lg text-blue-100 mb-8">
                    Temukan berbagai kegiatan spiritual, kajian, tilawah, dan program menarik selama bulan penuh berkah
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('events.index') }}" method="GET" class="relative max-w-2xl mx-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari event berdasarkan judul, lokasi, atau kategori..."
                        class="w-full px-6 py-4 pr-32 rounded-full text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    <button type="submit"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white px-8 py-3 rounded-full font-semibold hover:shadow-xl transition-all">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Filter & Stats Section --}}
    <section class="bg-white border-b py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                {{-- Stats --}}
                <div class="flex items-center gap-6 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-[#0053C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span><strong>{{ $events->total() }}</strong> Event Tersedia</span>
                    </div>
                    @if (request('category'))
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>Kategori: <strong>{{ request('category') }}</strong></span>
                        </div>
                    @endif
                </div>

                {{-- Filter Buttons --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('events.index') }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ !request('filter') ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua Event
                    </a>
                    <a href="{{ route('events.index', ['filter' => 'upcoming']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('filter') == 'upcoming' ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Akan Datang
                    </a>
                    <a href="{{ route('events.index', ['filter' => 'ongoing']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('filter') == 'ongoing' ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Sedang Berlangsung
                    </a>
                    <a href="{{ route('events.index', ['filter' => 'available']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('filter') == 'available' ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Masih Tersedia
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Section --}}
    @if ($categories->count() > 0)
        <section class="bg-gray-50 py-8">
            <div class="container mx-auto px-4">
                <div class="flex items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800 mr-4">Kategori:</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($categories as $category)
                            <a href="{{ route('events.index', ['category' => $category->slug]) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ request('category') == $category->slug ? 'bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                                {{ $category->name }}
                                <span class="ml-1 text-xs opacity-75">({{ $category->events_count ?? 0 }})</span>
                            </a>
                        @endforeach
                        @if (request('category'))
                            <a href="{{ route('events.index') }}"
                                class="px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-600 hover:bg-red-200 transition-all">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Events Grid Section --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if ($events->count() > 0)
                {{-- Events Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($events as $event)
                        <div
                            class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                            {{-- Event Image --}}
                            <div class="relative h-56 bg-gradient-to-br from-blue-100 to-blue-50 overflow-hidden">
                                @if ($event->image)
                                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-24 h-24 text-blue-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Featured Badge --}}
                                @if ($event->is_featured)
                                    <div
                                        class="absolute top-4 left-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Featured
                                    </div>
                                @endif

                                {{-- Status Badge --}}
                                <div class="absolute top-4 right-4">
                                    @if ($event->status == 'published')
                                        @if ($event->start_date > now())
                                            <span
                                                class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">Akan
                                                Datang</span>
                                        @elseif($event->end_date < now())
                                            <span
                                                class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                                        @else
                                            <span
                                                class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">Berlangsung</span>
                                        @endif
                                    @else
                                        <span
                                            class="bg-gray-400 text-white px-3 py-1 rounded-full text-xs font-bold">Draft</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Event Content --}}
                            <div class="p-6">
                                {{-- Category --}}
                                @if ($event->category)
                                    <a href="{{ route('events.category', $event->category->slug) }}"
                                        class="inline-block bg-blue-50 text-[#0053C5] px-3 py-1 rounded-full text-xs font-semibold mb-3 hover:bg-blue-100 transition-colors">
                                        {{ $event->category->name }}
                                    </a>
                                @endif

                                {{-- Title --}}
                                <h3
                                    class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-[#0053C5] transition-colors">
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        {{ $event->title }}
                                    </a>
                                </h3>

                                {{-- Description --}}
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $event->excerpt ?? Str::limit(strip_tags($event->description), 100) }}
                                </p>

                                {{-- Meta Info --}}
                                <div class="space-y-2 mb-4 text-sm text-gray-600">
                                    {{-- Date & Time --}}
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $event->start_datetime->format('d M Y') }} •
                                            {{ $event->start_datetime->format('H:i') }} WIB</span>
                                    </div>

                                    {{-- Location --}}
                                    @if ($event->location)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="line-clamp-1">{{ $event->location }}</span>
                                        </div>
                                    @endif

                                    {{-- Quota --}}
                                    @if ($event->max_participants)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span>
                                                <strong>{{ $event->current_participants ?? 0 }}</strong> /
                                                {{ $event->max_participants }} peserta
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Button --}}
                                <div class="flex items-center justify-between">
                                    @if ($event->max_participants && ($event->current_participants ?? 0) >= $event->max_participants)
                                        <span class="text-red-600 font-semibold text-sm">Kuota Penuh</span>
                                    @elseif($event->end_date < now())
                                        <span class="text-gray-500 font-semibold text-sm">Event Selesai</span>
                                    @else
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-full font-semibold hover:shadow-lg transform hover:scale-105 transition-all">
                                            Lihat Detail
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Price --}}
                                    @if ($event->price > 0)
                                        <span class="text-[#0053C5] font-bold text-lg">
                                            Rp {{ number_format($event->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-green-600 font-bold text-sm">GRATIS</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($events->hasPages())
                    <div class="mt-12">
                        {{ $events->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="mb-6">
                        <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak Ada Event Ditemukan</h3>
                    <p class="text-gray-600 mb-6">
                        @if (request('search'))
                            Pencarian "{{ request('search') }}" tidak menemukan hasil.
                        @elseif(request('category'))
                            Tidak ada event dalam kategori "{{ request('category') }}".
                        @else
                            Belum ada event yang tersedia saat ini.
                        @endif
                    </p>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-full font-semibold hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Lihat Semua Event
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Tidak Menemukan Event yang Tepat?</h2>
            <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                Hubungi kami untuk informasi lebih lanjut atau saran event yang ingin Anda ikuti
            </p>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center px-8 py-4 bg-white text-[#0053C5] rounded-full font-bold hover:shadow-2xl transform hover:scale-105 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Hubungi Kami
            </a>
        </div>
    </section>

@endsection

@push('styles')
    <style>
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
    </style>
@endpush
