@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - ' . $registration->event->title)

@section('content')
    {{-- ============================================================================ --}}
    {{-- SUCCESS HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-green-500 to-green-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                {{-- Success Icon --}}
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">Pendaftaran Berhasil!</h1>
                <p class="text-xl text-green-100 mb-2">Kode Pendaftaran:</p>
                <p class="text-3xl font-mono font-bold bg-white/20 inline-block px-6 py-3 rounded-lg">
                    {{ $registration->registration_code }}
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- REGISTRATION DETAILS --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Status Alert --}}
                @if ($registration->status === 'pending')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8 rounded-r-xl">
                        <div class="flex">
                            <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="text-lg font-bold text-yellow-800 mb-2">Menunggu Konfirmasi</h3>
                                <p class="text-yellow-700">
                                    Pendaftaran Anda sedang dalam proses verifikasi. Anda akan menerima email konfirmasi
                                    setelah pendaftaran disetujui oleh panitia.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($registration->status === 'confirmed')
                    <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-8 rounded-r-xl">
                        <div class="flex">
                            <svg class="w-6 h-6 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="text-lg font-bold text-green-800 mb-2">Pendaftaran Dikonfirmasi</h3>
                                <p class="text-green-700">
                                    Selamat! Pendaftaran Anda telah dikonfirmasi. Silakan download tiket di bawah ini dan
                                    tunjukkan saat check-in event.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Event Information --}}
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-[#0053C5]">
                                Informasi Event
                            </h2>

                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">Nama Event</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $registration->event->title }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ $registration->event->start_datetime->format('d F Y') }}
                                        </p>
                                        <p class="text-gray-600">
                                            {{ $registration->event->start_datetime->format('H:i') }} -
                                            {{ $registration->event->end_datetime->format('H:i') }} WIB
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-[#0053C5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">Lokasi</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $registration->event->location }}</p>
                                        @if ($registration->event->location_maps)
                                            <a href="{{ $registration->event->location_maps }}" target="_blank"
                                                class="text-[#0053C5] hover:text-[#003d8f] text-sm inline-flex items-center mt-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Lihat di Maps
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if ($registration->event->contact_person)
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-[#0053C5]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm text-gray-600">Kontak Panitia</p>
                                            <p class="text-lg font-bold text-gray-900">
                                                {{ $registration->event->contact_person }}
                                            </p>
                                            <p class="text-gray-600">{{ $registration->event->contact_phone }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Participant Information --}}
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-[#0053C5]">
                                Data Peserta
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                                    <p class="font-semibold text-gray-900">{{ $registration->name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Email</p>
                                    <p class="font-semibold text-gray-900">{{ $registration->email }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nomor Telepon</p>
                                    <p class="font-semibold text-gray-900">{{ $registration->phone }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Jenis Kelamin</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $registration->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Tanggal Lahir</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($registration->birth_date)->format('d F Y') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Kota</p>
                                    <p class="font-semibold text-gray-900">{{ $registration->city }},
                                        {{ $registration->province }}</p>
                                </div>

                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600 mb-1">Alamat</p>
                                    <p class="font-semibold text-gray-900">{{ $registration->address }}</p>
                                </div>

                                @if ($registration->notes)
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-600 mb-1">Catatan</p>
                                        <p class="font-semibold text-gray-900">{{ $registration->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Payment Information (if not free) --}}
                        @if (!$registration->event->is_free)
                            <div class="bg-white rounded-2xl shadow-lg p-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-[#0053C5]">
                                    Informasi Pembayaran
                                </h2>

                                <div class="space-y-4">
                                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                        <span class="text-gray-600">Biaya Pendaftaran</span>
                                        <span class="text-2xl font-bold text-gray-900">
                                            Rp {{ number_format($registration->event->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Status Pembayaran</span>
                                        @if ($registration->payment_status === 'paid')
                                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">
                                                ✓ Lunas
                                            </span>
                                        @elseif($registration->payment_status === 'unpaid')
                                            <span
                                                class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-semibold">
                                                ⏳ Menunggu Pembayaran
                                            </span>
                                        @endif
                                    </div>

                                    @if ($registration->payment_status === 'unpaid')
                                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <p class="font-semibold text-yellow-800 mb-2">Informasi Pembayaran:</p>
                                            <p class="text-sm text-yellow-700 mb-2">Silakan transfer ke:</p>
                                            <div class="bg-white p-3 rounded border border-yellow-200">
                                                <p class="font-mono font-bold text-gray-900">Bank BCA</p>
                                                <p class="font-mono font-bold text-gray-900">1234567890</p>
                                                <p class="text-sm text-gray-600">a.n. Panitia Ramadhan Mubarak</p>
                                            </div>
                                            <p class="text-sm text-yellow-700 mt-3">
                                                Upload bukti transfer melalui email konfirmasi yang kami kirimkan.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24 space-y-6">
                            {{-- QR Code --}}
                            <div class="text-center">
                                <h3 class="font-bold text-gray-900 mb-4">QR Code Tiket</h3>
                                <div class="bg-gray-100 p-4 rounded-xl inline-block">
                                    <div class="w-48 h-48 bg-white p-2 rounded-lg">
                                        {!! QrCode::size(180)->generate($registration->registration_code) !!}
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 mt-3">Tunjukkan QR Code ini saat check-in</p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="space-y-3">
                                @if ($registration->status === 'confirmed')
                                    <a href="{{ route('registrations.download-ticket', $registration->registration_code) }}"
                                        class="block w-full px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white text-center font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Download Tiket (PDF)
                                    </a>
                                @endif

                                <a href="{{ route('events.show', $registration->event->slug) }}"
                                    class="block w-full px-6 py-3 bg-gray-100 text-gray-700 text-center font-bold rounded-xl hover:bg-gray-200 transition-all">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail Event
                                </a>

                                <a href="{{ route('events.index') }}"
                                    class="block w-full px-6 py-3 bg-gray-100 text-gray-700 text-center font-bold rounded-xl hover:bg-gray-200 transition-all">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Lihat Event Lainnya
                                </a>
                            </div>

                            {{-- Important Notes --}}
                            <div class="bg-blue-50 p-4 rounded-xl">
                                <h4 class="font-bold text-gray-900 mb-2 text-sm">📌 Catatan Penting:</h4>
                                <ul class="text-xs text-gray-700 space-y-1">
                                    <li>• Simpan kode pendaftaran Anda dengan baik</li>
                                    <li>• Datang 15 menit sebelum acara dimulai</li>
                                    <li>• Tunjukkan QR Code saat check-in</li>
                                    <li>• Hubungi panitia jika ada pertanyaan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- RELATED EVENTS --}}
    {{-- ============================================================================ --}}
    @if ($relatedEvents->count() > 0)
        <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Event Lainnya yang Mungkin Anda Suka
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($relatedEvents as $event)
                            <a href="{{ route('events.show', $event->slug) }}"
                                class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all overflow-hidden group">
                                @if ($event->featured_image)
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        @if ($event->is_free)
                                            <span
                                                class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                                                GRATIS
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h3
                                        class="text-lg font-bold text-gray-900 mb-2 group-hover:text-[#0053C5] transition-colors">
                                        {{ $event->title }}
                                    </h3>

                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $event->start_datetime->format('d M Y') }}
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        {{ Str::limit($event->location, 30) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
