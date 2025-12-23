@extends('admin.layouts.app')

@section('title', 'Edit Laporan - ' . $finalEventReport->report_code)

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Laporan Akhir Acara</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $finalEventReport->report_code }} - {{ $finalEventReport->title }}
                </p>
            </div>
            <a href="{{ route('admin.reports.final-event-reports.show', $finalEventReport) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.reports.final-event-reports.update', $finalEventReport) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Event Selection --}}
                    <div class="md:col-span-2">
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Acara <span class="text-red-500">*</span>
                        </label>
                        <select name="event_id" id="event_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('event_id') border-red-500 @enderror">
                            <option value="">Pilih Acara</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ old('event_id', $finalEventReport->event_id) == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }} - {{ $event->start_datetime->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Judul Laporan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                            value="{{ old('title', $finalEventReport->title) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Report Date --}}
                    <div>
                        <label for="report_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanggal Laporan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="report_date" id="report_date"
                            value="{{ old('report_date', $finalEventReport->report_date->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('report_date') border-red-500 @enderror">
                        @error('report_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Report Sections - Using Tabs --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200" x-data="{ activeTab: 'executive' }">
                <div class="border-b border-gray-200">
                    <nav class="flex overflow-x-auto" aria-label="Tabs">
                        <button type="button" @click="activeTab = 'executive'"
                            :class="activeTab === 'executive' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Ringkasan Eksekutif
                        </button>
                        <button type="button" @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Gambaran Umum
                        </button>
                        <button type="button" @click="activeTab = 'objectives'"
                            :class="activeTab === 'objectives' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Pencapaian Tujuan
                        </button>
                        <button type="button" @click="activeTab = 'implementation'"
                            :class="activeTab === 'implementation' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Proses Pelaksanaan
                        </button>
                        <button type="button" @click="activeTab = 'statistics'"
                            :class="activeTab === 'statistics' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Statistik
                        </button>
                        <button type="button" @click="activeTab = 'financial'"
                            :class="activeTab === 'financial' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Keuangan
                        </button>
                        <button type="button" @click="activeTab = 'challenges'"
                            :class="activeTab === 'challenges' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Tantangan & Solusi
                        </button>
                        <button type="button" @click="activeTab = 'conclusion'"
                            :class="activeTab === 'conclusion' ? 'border-[#0053C5] text-[#0053C5]' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Kesimpulan
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    @include('admin.reports.final-event-reports.partials.form-sections')
                </div>
            </div>

            {{-- Notes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                <textarea name="notes" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes', $finalEventReport->notes) }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.reports.final-event-reports.show', $finalEventReport) }}"
                    class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-xl transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
