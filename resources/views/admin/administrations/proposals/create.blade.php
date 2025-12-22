@extends('admin.layouts.app')

@section('title', 'Buat Proposal Baru')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.proposals.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buat Proposal Baru</h1>
                <p class="mt-1 text-sm text-gray-600">Isi formulir berikut untuk membuat proposal baru</p>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-800 mb-1">Informasi Penting</p>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Proposal akan disimpan dengan status <strong>Draft</strong></li>
                        <li>• Anda dapat mengedit proposal selama masih berstatus Draft</li>
                        <li>• Gunakan tombol "Ajukan" di halaman detail untuk mengirim proposal</li>
                        <li>• Field yang bertanda <span class="text-red-500">*</span> wajib diisi</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.proposals.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.administrations.proposals._form')
        </form>
    </div>
@endsection
