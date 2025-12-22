@extends('admin.layouts.app')

@section('title', 'Buat Notulensi Rapat Baru')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.meeting-minutes.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buat Notulensi Rapat Baru</h1>
                <p class="mt-1 text-sm text-gray-600">Isi formulir di bawah untuk membuat notulensi rapat</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.meeting-minutes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.administrations.meeting-minutes._form')
        </form>
    </div>
@endsection
