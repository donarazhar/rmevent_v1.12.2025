@extends('layouts.app')

@section('title', $page->meta_title ?? 'Galeri')

@section('content')
    {{-- ============================================================================ --}}
    {{-- GALLERY HEADER --}}
    {{-- ============================================================================ --}}
    <section class="bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $page->title }}</h1>
                <p class="text-xl text-blue-100">
                    Dokumentasi kegiatan spiritual dan event Ramadhan Mubarak 1447 H
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================================================ --}}
    {{-- GALLERY GRID --}}
    {{-- ============================================================================ --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                @if ($eventMedia->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($eventMedia as $media)
                            <div
                                class="group relative rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all cursor-pointer">
                                <img src="{{ $media->getUrl('thumb') ?? $media->getUrl() }}"
                                    alt="{{ $media->mediable->title ?? 'Gallery Image' }}"
                                    class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">

                                {{-- Overlay --}}
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                    <div class="text-white">
                                        <p class="font-semibold text-sm">{{ $media->mediable->title ?? 'Event' }}</p>
                                        <p class="text-xs text-gray-300">
                                            {{ $media->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $eventMedia->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Galeri Belum Tersedia</h3>
                        <p class="text-gray-600">Foto-foto kegiatan akan segera ditambahkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
