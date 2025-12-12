@extends('admin.layouts.app')

@section('title', 'Detail Registrasi: ' . $registration->registration_code)

@section('content')
    <div>
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-gray-900">Detail Registrasi</h1>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'attended' => 'bg-blue-100 text-blue-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'no_show' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$registration->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">Kode: {{ $registration->registration_code }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.registrations.index') }}"
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

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-medium">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-medium">Info</p>
                    <p class="text-sm">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-medium">Error!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Participant Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Peserta
                    </h2>

                    <div class="space-y-4">
                        {{-- Avatar & Name --}}
                        <div class="flex items-center pb-4 border-b border-gray-200">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div
                                    class="h-16 w-16 rounded-full bg-primary text-white flex items-center justify-center text-2xl font-semibold">
                                    {{ strtoupper(substr($registration->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $registration->name }}</h3>
                                <p class="text-sm text-gray-500">Kode: {{ $registration->registration_code }}</p>
                            </div>
                        </div>

                        {{-- Contact Info --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $registration->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">No. Telepon</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $registration->phone }}</p>
                            </div>
                        </div>

                        {{-- Personal Info --}}
                        @if ($registration->gender || $registration->birth_date)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                @if ($registration->gender)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Jenis Kelamin</p>
                                        <p class="text-sm text-gray-900 mt-1">
                                            {{ $registration->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                        </p>
                                    </div>
                                @endif
                                @if ($registration->birth_date)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Tanggal Lahir</p>
                                        <p class="text-sm text-gray-900 mt-1">
                                            {{ $registration->birth_date->format('d F Y') }}
                                            <span class="text-gray-500">({{ $registration->birth_date->age }} tahun)</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Address --}}
                        @if ($registration->address || $registration->city || $registration->province)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-500 mb-2">Alamat</p>
                                @if ($registration->address)
                                    <p class="text-sm text-gray-900">{{ $registration->address }}</p>
                                @endif
                                @if ($registration->city || $registration->province)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $registration->city }}{{ $registration->city && $registration->province ? ', ' : '' }}{{ $registration->province }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- Notes --}}
                        @if ($registration->notes)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-500 mb-2">Catatan Peserta</p>
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $registration->notes }}</p>
                            </div>
                        @endif

                        {{-- Custom Data --}}
                        @if ($registration->custom_data && count($registration->custom_data) > 0)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-500 mb-3">Data Tambahan</p>
                                <div class="space-y-2">
                                    @foreach ($registration->custom_data as $key => $value)
                                        <div class="flex justify-between">
                                            <span
                                                class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                            <span class="text-sm text-gray-900 font-medium">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Event Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Informasi Event
                    </h2>

                    <div class="space-y-4">
                        {{-- Event Title --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $registration->event->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $registration->event->description }}</p>
                        </div>

                        {{-- Event Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal & Waktu</p>
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ $registration->event->start_datetime->format('d F Y, H:i') }} WIB
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    s/d {{ $registration->event->end_datetime->format('d F Y, H:i') }} WIB
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Lokasi</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $registration->event->location }}</p>
                                @if ($registration->event->location_maps_url)
                                    <a href="{{ $registration->event->location_maps_url }}" target="_blank"
                                        class="text-xs text-primary hover:text-primary-dark inline-flex items-center mt-1">
                                        Lihat di Maps
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Event Link --}}
                        <div class="pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.events.show', $registration->event) }}"
                                class="inline-flex items-center text-sm text-primary hover:text-primary-dark">
                                Lihat Detail Event
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Payment Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Informasi Pembayaran
                    </h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status Pembayaran</p>
                                @php
                                    $paymentColors = [
                                        'unpaid' => 'bg-red-100 text-red-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'refunded' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentColors[$registration->payment_status] ?? 'bg-gray-100 text-gray-800' }} mt-2">
                                    {{ ucfirst($registration->payment_status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Jumlah Pembayaran</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    @if ($registration->payment_amount > 0)
                                        Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}
                                    @else
                                        <span class="text-green-600">GRATIS</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($registration->payment_date)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-500">Tanggal Pembayaran</p>
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ $registration->payment_date->format('d F Y, H:i') }} WIB</p>
                            </div>
                        @endif

                        @if ($registration->payment_proof)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-500 mb-2">Bukti Pembayaran</p>
                                <img src="{{ asset('storage/' . $registration->payment_proof) }}" alt="Bukti Pembayaran"
                                    class="w-full max-w-md h-auto rounded-lg border border-gray-200">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Check-in Information --}}
                @if ($registration->checked_in_at)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Check-in
                        </h2>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Waktu Check-in</p>
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ $registration->checked_in_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                            @if ($registration->checked_in_by)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Dicek oleh</p>
                                    <p class="text-sm text-gray-900 mt-1">{{ $registration->checked_in_by }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Feedback --}}
                @if ($registration->feedback)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Feedback Peserta
                        </h2>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Rating Keseluruhan</p>
                                <div class="flex items-center mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $registration->feedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span
                                        class="ml-2 text-sm text-gray-600">{{ $registration->feedback->overall_rating }}/5</span>
                                </div>
                            </div>
                            @if ($registration->feedback->comments)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Komentar</p>
                                    <p class="text-sm text-gray-900 mt-1 whitespace-pre-line">
                                        {{ $registration->feedback->comments }}</p>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('admin.feedbacks.show', $registration->feedback) }}"
                                    class="inline-flex items-center text-sm text-primary hover:text-primary-dark">
                                    Lihat Detail Feedback
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-2">
                        @if ($registration->isPending())
                            <form action="{{ route('admin.registrations.confirm', $registration) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Konfirmasi Registrasi
                                </button>
                            </form>
                        @endif

                        @if ($registration->isConfirmed())
                            <form action="{{ route('admin.registrations.check-in', $registration) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Check-in Peserta
                                </button>
                            </form>
                        @endif

                        @if (!$registration->isCancelled())
                            <form action="{{ route('admin.registrations.cancel', $registration) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan registrasi ini?')">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Batalkan Registrasi
                                </button>
                            </form>
                        @endif

                        <a href="mailto:{{ $registration->email }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email Peserta
                        </a>

                        <a href="tel:{{ $registration->phone }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Telepon Peserta
                        </a>
                    </div>
                </div>

                {{-- Admin Notes --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan Admin</h3>

                    <form action="{{ route('admin.registrations.update-notes', $registration) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea name="admin_notes" rows="4" placeholder="Tambahkan catatan internal..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('admin_notes', $registration->admin_notes) }}</textarea>
                        <button type="submit"
                            class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Catatan
                        </button>
                    </form>
                </div>

                {{-- Registration Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Registrasi</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Kode Registrasi</span>
                            <span
                                class="font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $registration->registration_code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Tanggal Daftar</span>
                            <span class="text-gray-900">{{ $registration->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if ($registration->confirmation_sent_at)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Konfirmasi Dikirim</span>
                                <span
                                    class="text-gray-900">{{ $registration->confirmation_sent_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                        @if ($registration->user)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">User Account</span>
                                <a href="{{ route('admin.users.show', $registration->user) }}"
                                    class="text-primary hover:text-primary-dark">
                                    Lihat Profile
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Meta Information --}}
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Meta Information</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Created</span>
                            <span class="text-gray-900">{{ $registration->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Updated</span>
                            <span class="text-gray-900">{{ $registration->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">ID</span>
                            <span class="text-gray-900">#{{ $registration->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
