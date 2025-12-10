@extends('layouts.app')

@section('title', $post->title . ' - Ramadhan 1447 H')

@section('content')
    {{-- ============================================================================ --}}
    {{-- ARTICLE HEADER --}}
    {{-- ============================================================================ --}}
    <article class="bg-white">
        {{-- Featured Image --}}
        <div class="relative h-[400px] md:h-[500px] lg:h-[600px] overflow-hidden">
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

            {{-- Breadcrumb --}}
            <div class="absolute top-8 left-0 right-0">
                <div class="container mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-sm text-white/90">
                        <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                        <span>/</span>
                        <a href="{{ route('posts.index') }}" class="hover:text-white">Artikel</a>
                        <span>/</span>
                        <a href="{{ route('posts.category', $post->category->slug) }}"
                            class="hover:text-white">{{ $post->category->name }}</a>
                        <span>/</span>
                        <span class="text-white font-semibold">{{ Str::limit($post->title, 50) }}</span>
                    </nav>
                </div>
            </div>

            {{-- Title & Meta --}}
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                <div class="container mx-auto">
                    <div class="max-w-4xl">
                        {{-- Category Badge --}}
                        <a href="{{ route('posts.category', $post->category->slug) }}"
                            class="inline-block px-4 py-2 bg-[#0053C5] text-white rounded-full text-sm font-semibold mb-4 hover:bg-[#003d8f] transition-all">
                            {{ $post->category->name }}
                        </a>

                        {{-- Title --}}
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                            {{ $post->title }}
                        </h1>

                        {{-- Excerpt --}}
                        @if ($post->excerpt)
                            <p class="text-lg md:text-xl text-white/90 mb-6 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        @endif

                        {{-- Meta Info --}}
                        <div class="flex flex-wrap items-center gap-4 md:gap-6 text-white/80 text-sm">
                            {{-- Author --}}
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($post->author->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $post->author->name }}</div>
                                    <div class="text-xs text-white/70">Penulis</div>
                                </div>
                            </div>

                            <div class="h-8 w-px bg-white/30 hidden md:block"></div>

                            {{-- Published Date --}}
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $post->published_at->format('d M Y') }}</span>
                            </div>

                            {{-- Reading Time --}}
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $post->reading_time }} min read</span>
                            </div>

                            {{-- Views Count --}}
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ number_format($post->views_count) }} views</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================================ --}}
        {{-- ARTICLE CONTENT --}}
        {{-- ============================================================================ --}}
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                {{-- Social Share Buttons --}}
                <div class="flex items-center justify-between mb-8 pb-8 border-b border-gray-200">
                    <div class="text-sm text-gray-600">
                        Bagikan artikel ini:
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:opacity-80 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>

                        {{-- Twitter --}}
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-[#1DA1F2] text-white flex items-center justify-center hover:opacity-80 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . url()->current()) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center hover:opacity-80 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </a>

                        {{-- Copy Link --}}
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

                {{-- Article Content --}}
                <div class="prose prose-lg prose-blue max-w-none mb-12">
                    {!! $post->content !!}
                </div>

                {{-- Tags --}}
                @if ($post->tags->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-2 mb-8 pb-8 border-b border-gray-200">
                        <span class="text-gray-600 font-semibold mr-2">Tags:</span>
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('posts.tag', $tag->slug) }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-[#0053C5] hover:text-white text-gray-700 rounded-full text-sm font-medium transition-all">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Author Bio --}}
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 md:p-8 mb-12">
                    <div class="flex items-start gap-4 md:gap-6">
                        <div
                            class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-gradient-to-r from-[#0053C5] to-[#003d8f] flex items-center justify-center text-white font-bold text-3xl flex-shrink-0">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">{{ $post->author->name }}</h3>
                            <p class="text-gray-600 mb-4">
                                Penulis konten inspiratif seputar Ramadhan dan kehidupan Islami. Berbagi ilmu dan
                                pengalaman untuk meningkatkan kualitas ibadah.
                            </p>
                            <div class="flex gap-3">
                                <a href="#"
                                    class="text-[#0053C5] hover:text-[#003d8f] transition-colors">Facebook</a>
                                <span class="text-gray-300">•</span>
                                <a href="#"
                                    class="text-[#0053C5] hover:text-[#003d8f] transition-colors">Twitter</a>
                                <span class="text-gray-300">•</span>
                                <a href="#"
                                    class="text-[#0053C5] hover:text-[#003d8f] transition-colors">Instagram</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation (Previous/Next Post) --}}
                @if ($previousPost || $nextPost)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        {{-- Previous Post --}}
                        @if ($previousPost)
                            <a href="{{ route('posts.show', $previousPost->slug) }}"
                                class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-[#0053C5] transition-all">
                                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span>Artikel Sebelumnya</span>
                                </div>
                                <h4 class="font-bold text-gray-900 group-hover:text-[#0053C5] transition-colors">
                                    {{ Str::limit($previousPost->title, 60) }}
                                </h4>
                            </a>
                        @endif

                        {{-- Next Post --}}
                        @if ($nextPost)
                            <a href="{{ route('posts.show', $nextPost->slug) }}"
                                class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-[#0053C5] transition-all {{ !$previousPost ? 'md:col-start-2' : '' }}">
                                <div class="flex items-center justify-end gap-2 text-sm text-gray-500 mb-2">
                                    <span>Artikel Selanjutnya</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                                <h4
                                    class="font-bold text-gray-900 group-hover:text-[#0053C5] transition-colors text-right">
                                    {{ Str::limit($nextPost->title, 60) }}
                                </h4>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================================================================ --}}
        {{-- RELATED POSTS --}}
        {{-- ============================================================================ --}}
        @if ($relatedPosts->isNotEmpty())
            <div class="bg-gray-50 py-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">
                            Artikel <span class="text-[#0053C5]">Terkait</span>
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($relatedPosts as $related)
                                <article
                                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all group">
                                    <a href="{{ route('posts.show', $related->slug) }}">
                                        <div class="h-48 overflow-hidden">
                                            <img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div class="p-5">
                                            <span
                                                class="inline-block px-3 py-1 bg-blue-100 text-[#0053C5] rounded-full text-xs font-semibold mb-3">
                                                {{ $related->category->name }}
                                            </span>
                                            <h3
                                                class="text-lg font-bold text-gray-900 mb-2 group-hover:text-[#0053C5] transition-colors line-clamp-2">
                                                {{ $related->title }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                                {{ $related->excerpt_or_content }}
                                            </p>
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span>{{ $related->published_at->format('d M Y') }}</span>
                                                <span>{{ $related->reading_time }} min read</span>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </article>

    @push('styles')
        <style>
            /* Prose Styling for Article Content */
            .prose {
                color: #374151;
                max-width: 65ch;
            }

            .prose h2 {
                font-size: 1.875rem;
                font-weight: 700;
                margin-top: 2rem;
                margin-bottom: 1rem;
                color: #111827;
            }

            .prose h3 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-top: 1.5rem;
                margin-bottom: 0.75rem;
                color: #1F2937;
            }

            .prose p {
                margin-bottom: 1.25rem;
                line-height: 1.75;
            }

            .prose ul,
            .prose ol {
                margin-bottom: 1.25rem;
                padding-left: 1.5rem;
            }

            .prose li {
                margin-bottom: 0.5rem;
            }

            .prose strong {
                font-weight: 600;
                color: #0053C5;
            }

            .prose blockquote {
                border-left: 4px solid #0053C5;
                padding-left: 1.5rem;
                font-style: italic;
                color: #4B5563;
                margin: 1.5rem 0;
                background: #F3F4F6;
                padding: 1rem 1.5rem;
                border-radius: 0.5rem;
            }

            .prose a {
                color: #0053C5;
                text-decoration: underline;
            }

            .prose a:hover {
                color: #003d8f;
            }

            .prose img {
                border-radius: 0.5rem;
                margin: 1.5rem 0;
            }

            .prose code {
                background: #F3F4F6;
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.875rem;
                color: #EF4444;
            }

            .prose pre {
                background: #1F2937;
                color: #F9FAFB;
                padding: 1rem;
                border-radius: 0.5rem;
                overflow-x: auto;
                margin: 1.5rem 0;
            }

            .prose pre code {
                background: transparent;
                color: inherit;
                padding: 0;
            }
        </style>
    @endpush

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
