@extends('layouts.app')

@section('title', 'Pendaftaran Event: ' . $event->title)

@section('content')
    {{-- ============================================================================ --}}
    {{-- REGISTRATION HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Breadcrumb --}}
                <nav class="flex items-center space-x-2 text-sm text-blue-100 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('events.index') }}" class="hover:text-white">Event</a>
                    <span>/</span>
                    <a href="{{ route('events.show', $event->slug) }}"
                        class="hover:text-white">{{ Str::limit($event->title, 30) }}</a>
                    <span>/</span>
                    <span class="text-white font-semibold">Pendaftaran</span>
                </nav>

                <h1 class="text-3xl md:text-4xl font-bold mb-2">Formulir Pendaftaran</h1>
                <p class="text-lg text-blue-100">{{ $event->title }}</p>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- REGISTRATION FORM --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Main Form --}}
                    <div class="lg:col-span-2">
                        <form action="{{ route('registrations.store', $event->slug) }}" method="POST"
                            enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg p-8">
                            @csrf

                            {{-- Alert Info --}}
                            <div class="bg-blue-50 border-l-4 border-[#0053C5] p-4 mb-6">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-[#0053C5] mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="text-sm text-gray-700">
                                        <p class="font-semibold mb-1">Perhatian!</p>
                                        <p>Pastikan data yang Anda masukkan sudah benar. Anda akan menerima konfirmasi
                                            pendaftaran melalui email.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Personal Information --}}
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                                    Data Pribadi
                                </h2>

                                <div class="space-y-4">
                                    {{-- Name --}}
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', auth()->user()->name ?? '') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                                            placeholder="Masukkan nama lengkap Anda">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', auth()->user()->email ?? '') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                                            placeholder="nama@email.com">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Phone --}}
                                    <div>
                                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nomor Telepon/WhatsApp <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" id="phone" name="phone"
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('phone') border-red-500 @enderror"
                                            placeholder="081234567890">
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Gender --}}
                                    <div>
                                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Jenis Kelamin <span class="text-red-500">*</span>
                                        </label>
                                        <select id="gender" name="gender" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('gender') border-red-500 @enderror">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                        @error('gender')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Birth Date --}}
                                    <div>
                                        <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Lahir <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" id="birth_date" name="birth_date"
                                            value="{{ old('birth_date') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('birth_date') border-red-500 @enderror">
                                        @error('birth_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Address --}}
                                    <div>
                                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Alamat Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="address" name="address" rows="3" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('address') border-red-500 @enderror"
                                            placeholder="Masukkan alamat lengkap Anda">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- City & Province --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Kota <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="city" name="city"
                                                value="{{ old('city') }}" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('city') border-red-500 @enderror"
                                                placeholder="Nama kota">
                                            @error('city')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Provinsi <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="province" name="province"
                                                value="{{ old('province') }}" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('province') border-red-500 @enderror"
                                                placeholder="Nama provinsi">
                                            @error('province')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Information (if not free) --}}
                            @if (!$event->is_free)
                                <div class="mb-8">
                                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                                        Informasi Pembayaran
                                    </h2>

                                    {{-- Payment Instructions --}}
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div class="text-sm text-gray-700">
                                                <p class="font-semibold mb-2">Informasi Pembayaran:</p>
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Biaya pendaftaran: <strong>Rp
                                                            {{ number_format($event->price, 0, ',', '.') }}</strong></li>
                                                    <li>Transfer ke: <strong>Bank BCA 1234567890 a.n. Panitia
                                                            Ramadhan</strong></li>
                                                    <li>Upload bukti transfer di bawah ini</li>
                                                    <li>Pendaftaran akan dikonfirmasi setelah pembayaran diverifikasi</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Payment Proof Upload --}}
                                    <div>
                                        <label for="payment_proof" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Bukti Transfer <span class="text-gray-500 text-xs">(Opsional, bisa diupload
                                                nanti)</span>
                                        </label>
                                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('payment_proof') border-red-500 @enderror">
                                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB</p>
                                        @error('payment_proof')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Additional Notes --}}
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                                    Catatan Tambahan
                                </h2>

                                <div>
                                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Catatan <span class="text-gray-500 text-xs">(Opsional)</span>
                                    </label>
                                    <textarea id="notes" name="notes" rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('notes') border-red-500 @enderror"
                                        placeholder="Tuliskan catatan atau pertanyaan jika ada">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Terms & Conditions --}}
                            <div class="mb-6">
                                <label class="flex items-start">
                                    <input type="checkbox" name="agree_terms" required
                                        class="w-5 h-5 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5] mt-1">
                                    <span class="ml-3 text-sm text-gray-700">
                                        Saya setuju dengan <a href="{{ route('terms') }}"
                                            class="text-[#0053C5] hover:text-[#003d8f] font-semibold"
                                            target="_blank">syarat dan ketentuan</a> yang berlaku dan menyatakan bahwa
                                        data yang saya berikan adalah benar.
                                    </span>
                                </label>
                                @error('agree_terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit"
                                    class="flex-1 px-8 py-4 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Daftar Sekarang
                                </button>
                                <a href="{{ route('events.show', $event->slug) }}"
                                    class="flex-1 text-center px-8 py-4 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-all">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Sidebar - Event Info --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Event</h3>

                            {{-- Event Image --}}
                            @if ($event->featured_image)
                                <div class="mb-4 rounded-xl overflow-hidden">
                                    <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}"
                                        class="w-full h-40 object-cover">
                                </div>
                            @endif

                            {{-- Event Title --}}
                            <h4 class="font-bold text-gray-900 mb-4">{{ $event->title }}</h4>

                            {{-- Event Details --}}
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-[#0053C5] flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600">Tanggal & Waktu</div>
                                        <div class="font-semibold text-gray-900">
                                            {{ $event->start_datetime->format('d M Y, H:i') }} WIB
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-[#0053C5] flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600">Lokasi</div>
                                        <div class="font-semibold text-gray-900">{{ $event->location }}</div>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-[#0053C5] flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600">Biaya</div>
                                        <div class="font-semibold text-gray-900">
                                            @if ($event->is_free)
                                                <span class="text-green-600">GRATIS</span>
                                            @else
                                                Rp {{ number_format($event->price, 0, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($event->max_participants)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-2 text-[#0053C5] flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <div>
                                            <div class="text-gray-600">Kuota</div>
                                            <div class="font-semibold text-gray-900">
                                                {{ $event->current_participants }}/{{ $event->max_participants }}
                                                peserta
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Requirements --}}
                            @if ($event->requirements)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-2 text-sm">Persyaratan:</h4>
                                    <p class="text-sm text-gray-600">{{ $event->requirements }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
