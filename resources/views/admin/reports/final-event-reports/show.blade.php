@extends('admin.layouts.app')

@section('title', 'Detail Laporan - ' . $finalEventReport->report_code)

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start gap-4">
                <a href="{{ route('admin.reports.final-event-reports.index') }}"
                    class="mt-1 p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $finalEventReport->title }}</h1>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            {{ $finalEventReport->report_code }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $finalEventReport->report_date->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @php
                    $statusColors = [
                        'draft' => 'bg-gray-100 text-gray-800',
                        'under_review' => 'bg-yellow-100 text-yellow-800',
                        'approved' => 'bg-blue-100 text-blue-800',
                        'published' => 'bg-green-100 text-green-800',
                    ];
                @endphp
                <span
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColors[$finalEventReport->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $finalEventReport->status_label }}
                </span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex flex-wrap items-center gap-2">
                @if ($finalEventReport->canBeEditedBy(Auth::user()))
                    <a href="{{ route('admin.reports.final-event-reports.edit', $finalEventReport) }}"
                        class="inline-flex items-center px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Laporan
                    </a>
                @endif

                @if ($finalEventReport->isDraft())
                    <form action="{{ route('admin.reports.final-event-reports.submit-for-review', $finalEventReport) }}"
                        method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Ajukan untuk Ditinjau
                        </button>
                    </form>
                @endif

                @if ($finalEventReport->canBeApprovedBy(Auth::user()))
                    <form action="{{ route('admin.reports.final-event-reports.approve', $finalEventReport) }}" method="POST"
                        class="inline-block">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Setujui Laporan
                        </button>
                    </form>
                @endif

                @if ($finalEventReport->isApproved() && !$finalEventReport->isPublished())
                    <form action="{{ route('admin.reports.final-event-reports.publish', $finalEventReport) }}" method="POST"
                        class="inline-block">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                            Publikasikan
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.reports.final-event-reports.print', $finalEventReport) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </a>

                <form action="{{ route('admin.reports.final-event-reports.duplicate', $finalEventReport) }}" method="POST"
                    class="inline-block">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Duplikat
                    </button>
                </form>
            </div>
        </div>

        {{-- Continue with remaining sections... --}}
        @include('admin.reports.final-event-reports.partials.show-content')
    </div>
@endsection
