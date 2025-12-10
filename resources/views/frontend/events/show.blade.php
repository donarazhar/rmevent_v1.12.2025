@extends('layouts.app')

@section('title', $event->title . ' - Ramadhan 1447 H')

@section('content')
    {{-- ============================================================================ --}}
    {{-- EVENT HEADER --}}
    {{-- ============================================================================ --}}
    <section class="relative bg-gradient-to-br from-[#0053C5] via-[#003d8f] to-[#002a5c] text-white py-16">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,...')]"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="flex items-center space-x-2 text-sm text-blue-100 mb-8">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span>/</span>
                <a href="{{ route('events.index') }}" class="hover:text-white">Event</a>
                <span>/</span>
                @if ($event->category)
                    <a href="{{ route('events.category', $event->category->slug) }}"
                        class="hover:text-white">{{ $event->category->name }}</a>
                    <span>/</span>
                @endif
                <span class="text-white font-semibold">{{ Str::limit($event->title, 50) }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Info --}}
                <div class="lg:col-span-2">
                    {{-- Category Badge --}}
                    @if ($event->category)
                        <a href="{{ route('events.category', $event->category->slug) }}"
                            class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold mb-4 hover:bg-white/30 transition-all">
                            {{ $event->category->name }}
                        </a>
                    @endif

                    {{-- Title --}}
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                        {{ $event->title }}
                    </h1>

                    {{-- Short Description --}}
                    <p class="text-lg md:text-xl text-blue-100 mb-6 leading-relaxed">
                        {{ $event->description }}
                    </p>

                    {{-- Meta Info --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Date & Time --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="flex items-center text-blue-100 text-sm mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tanggal & Waktu
                            </div>
                            <div class="text-white font-bold">
                                {{ $event->start_datetime->format('d M Y') }}
                            </div>
                            <div class="text-blue-200 text-sm">
                                {{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}
                                WIB
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="flex items-center text-blue-100 text-sm mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lokasi
                            </div>
                            <div class="text-white font-bold text-sm">
                                {{ $event->location }}
                            </div>
                            @if ($event->location_maps_url)
                                <a href="{{ $event->location_maps_url }}" target="_blank"
                                    class="text-blue-200 text-xs hover:text-white inline-flex items-center mt-1">
                                    Lihat di Maps
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Status Badges --}}
                    <div class="flex flex-wrap gap-2 mt-4">
                        @if ($event->is_featured)
                            <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-bold">
                                ⭐ Featured Event
                            </span>
                        @endif

                        @if ($event->is_free)
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                💚 GRATIS
                            </span>
                        @endif

                        @if ($event->isUpcoming())
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                📅 Akan Datang
                            </span>
                        @elseif ($event->isOngoing())
                            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                ⏰ Sedang Berlangsung
                            </span>
                        @elseif ($event->isPast())
                            <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                ✓ Selesai
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Registration Card (Sticky) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 lg:sticky lg:top-24">
                        {{-- Price --}}
                        <div class="text-center mb-6">
                            @if ($event->is_free)
                                <div class="text-4xl font-bold text-green-600 mb-2">
                                    GRATIS
                                </div>
                            @else
                                <div class="text-sm text-gray-600 mb-1">Biaya Pendaftaran</div>
                                <div class="text-4xl font-bold text-[#0053C5] mb-2">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>

                        {{-- Quota Info --}}
                        @if ($event->max_participants)
                            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">Peserta Terdaftar</span>
                                    <span class="text-sm font-bold text-[#0053C5]">
                                        {{ $event->current_participants }}/{{ $event->max_participants }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] h-2 rounded-full transition-all"
                                        style="width: {{ ($event->current_participants / $event->max_participants) * 100 }}%">
                                    </div>
                                </div>
                                @if ($event->available_slots)
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $event->available_slots }} slot tersisa
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- Registration Button --}}
                        @if ($userRegistration)
                            {{-- Already Registered --}}
                            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 mb-4">
                                <div class="flex items-center text-green-700 mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-semibold">Anda Sudah Terdaftar</span>
                                </div>
                                <p class="text-sm text-green-600">
                                    Kode Registrasi: <strong>{{ $userRegistration->registration_code }}</strong>
                                </p>
                            </div>
                            <a href="{{ route('registrations.show', $userRegistration->registration_code) }}"
                                class="block w-full text-center px-6 py-4 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-xl font-bold hover:shadow-xl transform hover:scale-105 transition-all">
                                Lihat Detail Pendaftaran
                            </a>
                        @elseif (!$event->canRegister())
                            {{-- Cannot Register --}}
                            <button disabled
                                class="w-full px-6 py-4 bg-gray-300 text-gray-600 rounded-xl font-bold cursor-not-allowed">
                                @if ($event->is_full)
                                    Kuota Penuh
                                @elseif ($event->registration_end && $event->registration_end->isPast())
                                    Pendaftaran Ditutup
                                @else
                                    Tidak Dapat Mendaftar
                                @endif
                            </button>
                        @else
                            {{-- Can Register --}}
                            <a href="{{ route('registrations.create', $event->slug) }}"
                                class="block w-full text-center px-6 py-4 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-xl font-bold hover:shadow-xl transform hover:scale-105 transition-all mb-3">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Daftar Sekarang
                            </a>

                            @if ($event->registration_end)
                                <p class="text-xs text-center text-gray-500">
                                    Pendaftaran ditutup: {{ $event->registration_end->format('d M Y, H:i') }} WIB
                                </p>
                            @endif
                        @endif

                        {{-- Share Buttons --}}
                        <div class="border-t border-gray-200 mt-6 pt-6">
                            <p class="text-sm text-gray-600 mb-3 text-center">Bagikan event ini:</p>
                            <div class="flex justify-center gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:opacity-80 transition-all"
                                    title="Share to Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($event->title) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-[#1DA1F2] text-white flex items-center justify-center hover:opacity-80 transition-all"
                                    title="Share to Twitter">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center hover:opacity-80 transition-all"
                                    title="Share to WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                </a>
                                <button onclick="copyToClipboard('{{ url()->current() }}')"
                                    class="w-10 h-10 rounded-full bg-gray-600 text-white flex items-center justify-center hover:bg-gray-700 transition-all"
                                    title="Copy Link">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- EVENT DETAILS CONTENT --}}
    {{-- ============================================================================ --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    {{-- Full Description --}}
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Deskripsi Event
                        </h2>
                        <div class="prose prose-lg max-w-none">
                            {!! $event->full_description !!}
                        </div>
                    </div>

                    {{-- Requirements --}}
                    @if ($event->requirements)
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-6 mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Persyaratan Peserta
                            </h3>
                            <p class="text-gray-700">{{ $event->requirements }}</p>
                        </div>
                    @endif

                    {{-- Tags --}}
                    @if ($event->tags->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($event->tags as $tag)
                                    <a href="{{ route('events.tag', $tag->slug) }}"
                                        class="px-4 py-2 bg-gray-100 hover:bg-[#0053C5] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Testimonials --}}
                    @if ($testimonials->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                Testimoni Peserta
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($testimonials as $testimonial)
                                    <div class="bg-gray-50 rounded-xl p-6">
                                        {{-- Rating --}}
                                        <div class="flex items-center mb-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $testimonial->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>

                                        {{-- Message --}}
                                        <p class="text-gray-700 italic mb-4">
                                            "{{ Str::limit($testimonial->message, 150) }}"
                                        </p>

                                        {{-- Author --}}
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-r from-[#0053C5] to-[#003d8f] flex items-center justify-center text-white font-bold mr-3">
                                                {{ substr($testimonial->name ?? ($testimonial->user?->name ?? 'A'), 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">
                                                    {{ $testimonial->name ?? ($testimonial->user?->name ?? 'Anonymous') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $testimonial->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    {{-- Contact Info --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#0053C5]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Kontak Informasi
                        </h3>
                        <div class="space-y-3 text-sm">
                            @if ($event->contact_person)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600">Contact Person</div>
                                        <div class="font-semibold text-gray-900">{{ $event->contact_person }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($event->contact_phone)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600">WhatsApp</div>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $event->contact_phone) }}"
                                            target="_blank" class="font-semibold text-[#0053C5] hover:text-[#003d8f]">
                                            {{ $event->contact_phone }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if ($event->contact_email)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600">Email</div>
                                        <a href="mailto:{{ $event->contact_email }}"
                                            class="font-semibold text-[#0053C5] hover:text-[#003d8f] break-all">
                                            {{ $event->contact_email }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Events --}}
                    @if ($relatedEvents->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Event Terkait</h3>
                            <div class="space-y-4">
                                @foreach ($relatedEvents as $related)
                                    <a href="{{ route('events.show', $related->slug) }}"
                                        class="block group hover:bg-gray-50 rounded-xl p-3 transition-all">
                                        <div class="flex gap-3">
                                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                                @if ($related->featured_image)
                                                    <img src="{{ Storage::url($related->featured_image) }}"
                                                        alt="{{ $related->title }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-300" fill="none"
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
                                                    class="font-semibold text-gray-900 group-hover:text-[#0053C5] transition-colors line-clamp-2 mb-1">
                                                    {{ $related->title }}
                                                </h4>
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $related->start_datetime->format('d M Y') }}
                                                </div>
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
    </section>

    @push('scripts')
        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    alert('Link berhasil disalin!');
                }, function(err) {
                    console.error('Could not copy text: ', err);
                });
            }
        </script>
    @endpush
@endsection
