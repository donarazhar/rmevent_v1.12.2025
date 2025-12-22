@extends('admin.layouts.app')

@section('title', 'Edit Notulensi Rapat')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.meeting-minutes.show', $meetingMinute) }}"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Notulensi Rapat</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $meetingMinute->minute_code }} -
                    {{ $meetingMinute->meeting_title }}</p>
            </div>
        </div>

        {{-- Alert: Only Draft Can Be Edited --}}
        @if (!$meetingMinute->isDraft())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">
                            Notulensi ini tidak dapat diedit karena statusnya bukan Draft.
                        </p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Status saat ini: <span class="font-semibold">{{ $meetingMinute->status_label }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.meeting-minutes.update', $meetingMinute) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.administrations.meeting-minutes._form')
        </form>
    </div>
@endsection
