@extends('layouts.app')

@section('title', 'Berikan Feedback')

@section('content')
    {{-- ============================================================================ --}}
    {{-- FEEDBACK HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Breadcrumb --}}
                <nav class="flex items-center space-x-2 text-sm text-blue-100 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('testimonials') }}" class="hover:text-white">Testimoni</a>
                    <span>/</span>
                    <span class="text-white font-semibold">Berikan Feedback</span>
                </nav>

                <h1 class="text-3xl md:text-4xl font-bold mb-2">Berikan Feedback Anda</h1>
                <p class="text-lg text-blue-100">
                    Masukan Anda sangat berharga untuk meningkatkan kualitas kegiatan kami
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- FEEDBACK FORM --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('feedback.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
                    @csrf

                    {{-- Alert Info --}}
                    <div class="bg-blue-50 border-l-4 border-[#0053C5] p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-[#0053C5] mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-gray-700">
                                <p class="font-semibold mb-1">Catatan:</p>
                                <p>Feedback Anda akan membantu kami meningkatkan kualitas event dan pelayanan. Testimoni
                                    positif dapat kami tampilkan di halaman publik setelah persetujuan Anda.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Event Selection (if not pre-selected) --}}
                    @if (!$event)
                        <input type="hidden" name="type" value="general">
                    @else
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="hidden" name="type" value="event">
                        @if ($registration)
                            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                        @endif

                        {{-- Event Info Card --}}
                        <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <p class="text-sm text-gray-600 mb-1">Feedback untuk event:</p>
                            <p class="font-bold text-gray-900">{{ $event->title }}</p>
                            <p class="text-sm text-gray-600">{{ $event->start_datetime->format('d F Y') }}</p>
                        </div>
                    @endif

                    {{-- Personal Information --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                            Informasi Pribadi
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
                                    placeholder="Nama lengkap Anda">
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
                                    placeholder="email@example.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-gray-500 text-xs">(Opsional)</span>
                                </label>
                                <input type="tel" id="phone" name="phone"
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('phone') border-red-500 @enderror"
                                    placeholder="081234567890">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Feedback Content --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                            Feedback Anda
                        </h2>

                        {{-- Subject (if general feedback) --}}
                        @if (!$event)
                            <div class="mb-4">
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Subjek <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('subject') border-red-500 @enderror"
                                    placeholder="Subjek feedback Anda">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Overall Rating --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Rating Keseluruhan <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2" x-data="{ rating: {{ old('overall_rating', 0) }} }">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="rating = {{ $i }}"
                                        class="focus:outline-none">
                                        <svg class="w-10 h-10 transition-colors"
                                            :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                    <input type="radio" name="overall_rating" :value="{{ $i }}"
                                        x-model="rating" class="hidden" required>
                                @endfor
                                <span class="ml-4 text-sm text-gray-600" x-text="rating > 0 ? rating + '/5' : ''"></span>
                            </div>
                            @error('overall_rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Message --}}
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pesan/Testimoni <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('message') border-red-500 @enderror"
                                placeholder="Tuliskan pengalaman, kesan, dan saran Anda di sini...">{{ old('message') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Minimal 50 karakter</p>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Detailed Ratings (for event feedback) --}}
                    @if ($event)
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                                Rating Detail <span class="text-gray-500 text-sm font-normal">(Opsional)</span>
                            </h2>

                            <div class="space-y-4">
                                {{-- Content Rating --}}
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-semibold text-gray-700">Materi/Konten</label>
                                    <div class="flex space-x-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="content_rating" value="{{ $i }}"
                                                    class="hidden peer">
                                                <svg class="w-6 h-6 text-gray-300 peer-checked:text-yellow-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                {{-- Speaker Rating --}}
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-semibold text-gray-700">Pembicara/Ustadz</label>
                                    <div class="flex space-x-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="speaker_rating" value="{{ $i }}"
                                                    class="hidden peer">
                                                <svg class="w-6 h-6 text-gray-300 peer-checked:text-yellow-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                {{-- Venue Rating --}}
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-semibold text-gray-700">Tempat/Venue</label>
                                    <div class="flex space-x-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="venue_rating" value="{{ $i }}"
                                                    class="hidden peer">
                                                <svg class="w-6 h-6 text-gray-300 peer-checked:text-yellow-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                {{-- Organization Rating --}}
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-semibold text-gray-700">Organisasi/Panitia</label>
                                    <div class="flex space-x-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="organization_rating"
                                                    value="{{ $i }}" class="hidden peer">
                                                <svg class="w-6 h-6 text-gray-300 peer-checked:text-yellow-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Suggestions --}}
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                                Saran & Kritik
                            </h2>

                            <div>
                                <label for="suggestions" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Saran untuk Perbaikan <span class="text-gray-500 text-xs">(Opsional)</span>
                                </label>
                                <textarea id="suggestions" name="suggestions" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent transition-all @error('suggestions') border-red-500 @enderror"
                                    placeholder="Saran atau kritik Anda untuk perbaikan event selanjutnya">{{ old('suggestions') }}</textarea>
                                @error('suggestions')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Recommendation Score --}}
                            <div class="mt-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Apakah Anda akan merekomendasikan event ini kepada teman?
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @for ($i = 1; $i <= 10; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="recommendation_score"
                                                value="{{ $i }}" class="hidden peer">
                                            <div
                                                class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-lg peer-checked:border-[#0053C5] peer-checked:bg-[#0053C5] peer-checked:text-white transition-all">
                                                {{ $i }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                                <p class="mt-2 text-xs text-gray-500">1 = Tidak Mungkin, 10 = Sangat Mungkin</p>
                            </div>
                        </div>
                    @endif

                    {{-- Publish Permission --}}
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="allow_publish" value="1"
                                class="w-5 h-5 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5] mt-1">
                            <span class="ml-3 text-sm text-gray-700">
                                Saya mengizinkan testimoni saya untuk dipublikasikan di website (testimoni positif akan
                                ditampilkan setelah review)
                            </span>
                        </label>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit"
                            class="flex-1 px-8 py-4 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Feedback
                        </button>
                        <a href="{{ $event ? route('events.show', $event->slug) : route('home') }}"
                            class="flex-1 text-center px-8 py-4 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
