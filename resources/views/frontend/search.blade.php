@extends('layouts.app')

@section('title', 'Search Results - ' . $results['query'])

@section('content')
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-6xl mx-auto">
            {{-- Search Header --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Search Results for "<span class="text-[#0053C5]">{{ $results['query'] }}</span>"
                </h1>

                {{-- Filter Tabs --}}
                <div class="flex space-x-4 border-b border-gray-200">
                    <a href="{{ route('search', ['q' => $results['query'], 'type' => 'all']) }}"
                        class="px-4 py-2 {{ $results['type'] === 'all' ? 'border-b-2 border-[#0053C5] text-[#0053C5] font-semibold' : 'text-gray-600 hover:text-[#0053C5]' }}">
                        All Results
                    </a>
                    <a href="{{ route('search', ['q' => $results['query'], 'type' => 'events']) }}"
                        class="px-4 py-2 {{ $results['type'] === 'events' ? 'border-b-2 border-[#0053C5] text-[#0053C5] font-semibold' : 'text-gray-600 hover:text-[#0053C5]' }}">
                        Events
                    </a>
                    <a href="{{ route('search', ['q' => $results['query'], 'type' => 'posts']) }}"
                        class="px-4 py-2 {{ $results['type'] === 'posts' ? 'border-b-2 border-[#0053C5] text-[#0053C5] font-semibold' : 'text-gray-600 hover:text-[#0053C5]' }}">
                        Articles
                    </a>
                </div>
            </div>

            {{-- Events Results --}}
            @if (isset($results['events']) && $results['events']->isNotEmpty())
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Events</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($results['events'] as $event)
                            {{-- Event Card (sama seperti di homepage) --}}
                            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $event->featured_image ? Storage::url($event->featured_image) : 'https://via.placeholder.com/600x400' }}"
                                        alt="{{ $event->title }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $event->title }}
                                    </h3>
                                    <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
                                        {{ Str::limit(strip_tags($event->description), 100) }}
                                    </p>
                                    <a href="{{ route('events.show', $event->slug) }}"
                                        class="text-[#0053C5] font-semibold hover:text-[#003d8f] flex items-center text-sm">
                                        View Details
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $results['events']->links() }}
                    </div>
                </div>
            @endif

            {{-- Posts Results --}}
            @if (isset($results['posts']) && $results['posts']->isNotEmpty())
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($results['posts'] as $post)
                            {{-- Post Card (sama seperti di homepage) --}}
                            <article class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $post->featured_image ? Storage::url($post->featured_image) : 'https://via.placeholder.com/600x400' }}"
                                        alt="{{ $post->title }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="text-gray-600 mb-4 line-clamp-3 text-sm">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                        class="text-[#0053C5] font-semibold hover:text-[#003d8f] flex items-center text-sm">
                                        Read More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $results['posts']->links() }}
                    </div>
                </div>
            @endif

            {{-- No Results --}}
            @if (isset($results['events']) &&
                    $results['events']->isEmpty() &&
                    (isset($results['posts']) && $results['posts']->isEmpty()))
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-600 mb-2">No results found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search terms or browse our categories</p>
                    <a href="{{ route('events.index') }}"
                        class="px-6 py-3 bg-[#0053C5] text-white rounded-full hover:bg-[#003d8f] transition-all">
                        Browse All Events
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
