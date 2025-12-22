@extends('admin.layouts.app')

@section('title', 'Edit Proposal')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.proposals.show', $proposal) }}"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Proposal</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $proposal->proposal_code }} - {{ $proposal->title }}</p>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-yellow-800 mb-1">Perhatian</p>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Hanya proposal dengan status <strong>Draft</strong> yang dapat diedit</li>
                        <li>• Setelah proposal diajukan, Anda tidak dapat mengubahnya lagi</li>
                        <li>• Pastikan semua informasi sudah benar sebelum mengajukan</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.proposals.update', $proposal) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.administrations.proposals._form')
        </form>
    </div>
@endsection
