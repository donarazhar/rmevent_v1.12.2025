@extends('layouts.app')

@section('title', $page->meta_title ?? 'FAQ - ' . $page->title)

@section('content')
    {{-- ============================================================================ --}}
    {{-- FAQ HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                {{-- Breadcrumb --}}
                <nav class="flex items-center justify-center space-x-2 text-sm text-blue-100 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <span class="text-white font-semibold">{{ $page->title }}</span>
                </nav>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $page->title }}</h1>
                @if ($page->content)
                    <p class="text-xl text-blue-100">
                        {!! Str::limit(strip_tags($page->content), 150) !!}
                    </p>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- FAQ CONTENT --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                @if ($faqs->count() > 0)
                    {{-- FAQ Accordion --}}
                    <div class="space-y-4" x-data="{ activeAccordion: null }">
                        @php $index = 0; @endphp
                        @foreach ($faqs as $categoryName => $categoryFaqs)
                            @if ($categoryName)
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-[#0053C5]">
                                        {{ $categoryName }}
                                    </h2>
                                </div>
                            @endif

                            @foreach ($categoryFaqs as $faq)
                                @php $index++; @endphp
                                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                    {{-- Question --}}
                                    <button
                                        @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
                                        class="w-full px-6 py-4 flex items-start justify-between text-left hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start space-x-4 flex-1">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                {{ $index }}
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900"
                                                    :class="activeAccordion === {{ $index }} ? 'text-[#0053C5]' : ''">
                                                    {{ $faq->question }}
                                                </h3>
                                            </div>
                                        </div>
                                        <svg class="w-6 h-6 text-gray-500 flex-shrink-0 ml-4 transition-transform"
                                            :class="activeAccordion === {{ $index }} ? 'rotate-180' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    {{-- Answer --}}
                                    <div x-show="activeAccordion === {{ $index }}" x-cloak x-transition
                                        class="px-6 pb-6">
                                        <div class="pl-12 text-gray-700 prose prose-blue max-w-none">
                                            {!! $faq->answer !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada FAQ</h3>
                        <p class="text-gray-600">FAQ akan segera ditambahkan.</p>
                    </div>
                @endif

                {{-- Still Have Questions? --}}
                <div class="mt-12 p-8 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-2xl text-center">
                    <h3 class="text-2xl font-bold mb-3">Masih Ada Pertanyaan?</h3>
                    <p class="text-blue-100 mb-6">
                        Jika pertanyaan Anda belum terjawab, jangan ragu untuk menghubungi kami!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('pages.show', 'contact') }}"
                            class="px-8 py-3 bg-white text-[#0053C5] font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Hubungi Kami
                        </a>
                        <a href="{{ route('feedback.create') }}"
                            class="px-8 py-3 bg-white/20 text-white font-bold rounded-xl hover:bg-white/30 transition-all">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Kirim Feedback
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
