@extends('layouts.app')

@section('title', 'Testimoni Peserta')

@section('content')
    {{-- ============================================================================ --}}
    {{-- TESTIMONIALS HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                {{-- Breadcrumb --}}
                <nav class="flex items-center justify-center space-x-2 text-sm text-blue-100 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <span class="text-white font-semibold">Testimoni</span>
                </nav>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">Testimoni Peserta</h1>
                <p class="text-xl text-blue-100">
                    Pengalaman dan kesan dari para jamaah yang telah mengikuti kegiatan Ramadhan Mubarak 1447 H
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- FEATURED TESTIMONIALS --}}
    {{-- ============================================================================ --}}
    @if ($featuredTestimonials->count() > 0)
        <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">⭐ Testimoni Pilihan</h2>
                        <p class="text-gray-600">Kisah inspiratif dari para jamaah terbaik</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach ($featuredTestimonials as $testimonial)
                            <div
                                class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all transform hover:-translate-y-2">
                                {{-- Rating Stars --}}
                                <div class="flex items-center justify-center mb-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-6 h-6 {{ $i <= $testimonial->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>

                                {{-- Testimonial Content --}}
                                <blockquote class="text-gray-700 text-center italic mb-6 line-clamp-4">
                                    "{{ $testimonial->message }}"
                                </blockquote>

                                {{-- Author Info --}}
                                <div class="text-center pt-6 border-t border-gray-200">
                                    <h4 class="font-bold text-gray-900">{{ $testimonial->name }}</h4>
                                    @if ($testimonial->event)
                                        <p class="text-sm text-gray-600">{{ $testimonial->event->title }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $testimonial->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================================ --}}
    {{-- ALL TESTIMONIALS --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Semua Testimoni</h2>
                    <p class="text-gray-600">{{ $testimonials->total() }} testimoni dari para jamaah</p>
                </div>

                @if ($testimonials->count() > 0)
                    {{-- Testimonials Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($testimonials as $testimonial)
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all p-6">
                                {{-- Rating --}}
                                <div class="flex items-center mb-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $testimonial->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">({{ $testimonial->overall_rating }}/5)</span>
                                </div>

                                {{-- Event Title --}}
                                @if ($testimonial->event)
                                    <a href="{{ route('events.show', $testimonial->event->slug) }}"
                                        class="text-sm text-[#0053C5] hover:text-[#003d8f] font-semibold mb-2 block">
                                        {{ $testimonial->event->title }}
                                    </a>
                                @endif

                                {{-- Message --}}
                                <blockquote class="text-gray-700 mb-4 line-clamp-3">
                                    "{{ $testimonial->message }}"
                                </blockquote>

                                {{-- Author & Date --}}
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $testimonial->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $testimonial->created_at->format('d M Y') }}
                                        </p>
                                    </div>

                                    @if ($testimonial->is_featured)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                            ⭐ Featured
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $testimonials->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Testimoni</h3>
                        <p class="text-gray-600 mb-6">Jadilah yang pertama memberikan testimoni!</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- CTA SECTION --}}
    {{-- ============================================================================ --}}
    <section class="py-16 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Bagikan Pengalaman Anda!</h2>
                <p class="text-xl text-blue-100 mb-8">
                    Sudah mengikuti event kami? Ceritakan pengalaman Anda dan bantu jamaah lain untuk bergabung!
                </p>
                <a href="{{ route('feedback.create') }}"
                    class="inline-block px-8 py-4 bg-white text-[#0053C5] font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Tulis Testimoni
                </a>
            </div>
        </div>
    </section>
@endsection
