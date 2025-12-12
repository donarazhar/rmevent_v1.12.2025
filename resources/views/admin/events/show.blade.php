@extends('admin.layouts.app')

@section('title', 'Detail Event: ' . $event->title)

@section('content')
    <div>
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                        @if ($event->is_featured)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Featured
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-600">Detail lengkap event dan statistik</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.events.edit', $event) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Event
                    </a>
                    <a href="{{ route('admin.events.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Status Badge --}}
        <div class="mb-6">
            @php
                $statusColors = [
                    'draft' => 'bg-gray-100 text-gray-800',
                    'published' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'completed' => 'bg-blue-100 text-blue-800',
                ];
            @endphp
            <span
                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ $statusColors[$event->status] ?? 'bg-gray-100 text-gray-800' }}">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Status: {{ ucfirst($event->status) }}
            </span>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            {{-- Registrations --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Registrasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $registrationStats['total'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Confirmed --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Terkonfirmasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $registrationStats['confirmed'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Attended --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hadir</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $registrationStats['attended'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Feedbacks --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rating</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $feedbackStats['average_rating'] ? number_format($feedbackStats['average_rating'], 1) : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Featured Image --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <img src="{{ $event->featured_image_url }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                </div>

                {{-- Event Details --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Event</h2>

                    <div class="space-y-4">
                        {{-- Category --}}
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Kategori</p>
                                <p class="text-sm text-gray-900">{{ $event->category->name ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Deskripsi Singkat</p>
                                <p class="text-sm text-gray-900">{{ $event->description }}</p>
                            </div>
                        </div>

                        {{-- Full Description --}}
                        @if ($event->full_description)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500">Deskripsi Lengkap</p>
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $event->full_description }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Location --}}
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Lokasi</p>
                                <p class="text-sm text-gray-900">{{ $event->location }}</p>
                                @if ($event->location_maps_url)
                                    <a href="{{ $event->location_maps_url }}" target="_blank"
                                        class="text-sm text-primary hover:text-primary-dark inline-flex items-center mt-1">
                                        Lihat di Google Maps
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Date Time --}}
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal & Waktu</p>
                                <p class="text-sm text-gray-900">
                                    {{ $event->start_datetime->format('d F Y, H:i') }} WIB -
                                    {{ $event->end_datetime->format('H:i') }} WIB
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if ($event->isUpcoming())
                                        <span class="text-blue-600">● Upcoming</span>
                                    @elseif ($event->isOngoing())
                                        <span class="text-green-600">● Sedang Berlangsung</span>
                                    @else
                                        <span class="text-gray-600">● Sudah Selesai</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Requirements --}}
                        @if ($event->requirements)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500">Persyaratan</p>
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $event->requirements }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Tags --}}
                        @if ($event->tags->count() > 0)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-2">Tags</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($event->tags as $tag)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Recent Registrations --}}
                @if ($event->registrations->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Registrasi Terbaru</h2>
                            <a href="{{ route('admin.registrations.index', ['event_id' => $event->id]) }}"
                                class="text-sm text-primary hover:text-primary-dark">
                                Lihat Semua →
                            </a>
                        </div>

                        <div class="space-y-3">
                            @foreach ($event->registrations->take(5) as $registration)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $registration->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $registration->email }}</p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'attended' => 'bg-blue-100 text-blue-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $statusColors[$registration->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $registration->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Recent Feedbacks --}}
                @if ($event->feedbacks->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Feedback Terbaru</h2>
                            <a href="{{ route('admin.feedbacks.index', ['event_id' => $event->id]) }}"
                                class="text-sm text-primary hover:text-primary-dark">
                                Lihat Semua →
                            </a>
                        </div>

                        <div class="space-y-4">
                            @foreach ($event->feedbacks->take(3) as $feedback)
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $feedback->name }}</p>
                                            <div class="flex items-center mt-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $feedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span
                                                    class="ml-2 text-sm text-gray-600">{{ $feedback->overall_rating }}/5</span>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if ($feedback->comments)
                                        <p class="mt-2 text-sm text-gray-700">{{ Str::limit($feedback->comments, 150) }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Registration Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pendaftaran</h3>

                    <div class="space-y-3">
                        {{-- Registration Status --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status Pendaftaran</span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $event->is_registration_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $event->is_registration_open ? 'Dibuka' : 'Ditutup' }}
                            </span>
                        </div>

                        {{-- Participants --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Peserta</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $event->current_participants }}
                                @if ($event->max_participants)
                                    / {{ $event->max_participants }}
                                @else
                                    / ∞
                                @endif
                            </span>
                        </div>

                        {{-- Available Slots --}}
                        @if ($event->max_participants)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Sisa Kuota</span>
                                <span
                                    class="text-sm font-medium {{ $event->available_slots <= 10 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $event->available_slots }} slot
                                </span>
                            </div>
                        @endif

                        {{-- Registration Period --}}
                        @if ($event->registration_start || $event->registration_end)
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Periode Pendaftaran</p>
                                @if ($event->registration_start)
                                    <p class="text-xs text-gray-500">Dibuka:
                                        {{ $event->registration_start->format('d M Y, H:i') }}</p>
                                @endif
                                @if ($event->registration_end)
                                    <p class="text-xs text-gray-500">Ditutup:
                                        {{ $event->registration_end->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        @endif

                        {{-- Price --}}
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-600">Harga</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if ($event->is_free)
                                    <span class="text-green-600">GRATIS</span>
                                @else
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                @if ($event->contact_person || $event->contact_phone || $event->contact_email)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>

                        <div class="space-y-3">
                            @if ($event->contact_person)
                                <div>
                                    <p class="text-xs text-gray-500">Narahubung</p>
                                    <p class="text-sm text-gray-900">{{ $event->contact_person }}</p>
                                </div>
                            @endif
                            @if ($event->contact_phone)
                                <div>
                                    <p class="text-xs text-gray-500">No. Telepon</p>
                                    <p class="text-sm text-gray-900">{{ $event->contact_phone }}</p>
                                </div>
                            @endif
                            @if ($event->contact_email)
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="text-sm text-gray-900">{{ $event->contact_email }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-2">
                        <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Lihat di Frontend
                        </a>

                        <form action="{{ route('admin.events.duplicate', $event) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Duplicate Event
                            </button>
                        </form>

                        @if ($event->registrations()->count() > 0)
                            <a href="{{ route('admin.events.export-registrations', $event) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export Registrasi
                            </a>
                        @endif

                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini? Semua data terkait akan terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 hover:bg-red-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Event
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Meta Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Meta Info</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Dibuat</span>
                            <span class="text-gray-900">{{ $event->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Diupdate</span>
                            <span class="text-gray-900">{{ $event->updated_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Views</span>
                            <span class="text-gray-900">{{ number_format($event->views_count ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Slug</span>
                            <span class="text-gray-900 text-xs">{{ $event->slug }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
