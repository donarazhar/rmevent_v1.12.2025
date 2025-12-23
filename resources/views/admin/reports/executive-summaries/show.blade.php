@extends('admin.layouts.app')

@section('title', 'Detail Executive Summary')

@section('content')
    <div class="px-6 py-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.reports.executive-summaries.index') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">{{ $executiveSummary->title }}</h1>
                        <p class="text-gray-600 text-xs mt-1">{{ $executiveSummary->summary_code }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if ($executiveSummary->canBeEditedBy(Auth::user()))
                    <a href="{{ route('admin.reports.executive-summaries.edit', $executiveSummary) }}"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 text-sm rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif

                <form action="{{ route('admin.reports.executive-summaries.duplicate', $executiveSummary) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 text-sm rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Duplikat
                    </button>
                </form>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-4">
                {{-- Summary Information --}}
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">Informasi Summary</h2>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-sm text-gray-600">Tipe Summary:</span>
                            <p class="font-medium text-gray-900 mt-1">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                {{ $executiveSummary->summary_type == 'monthly' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $executiveSummary->summary_type == 'quarterly' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $executiveSummary->summary_type == 'event' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $executiveSummary->summary_type == 'annual' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ ucfirst($executiveSummary->summary_type) }}
                                </span>
                            </p>
                        </div>

                        @if ($executiveSummary->event)
                            <div>
                                <span class="text-sm text-gray-600">Event:</span>
                                <p class="font-medium text-gray-900 mt-1">{{ $executiveSummary->event->title }}</p>
                            </div>
                        @endif

                        <div>
                            <span class="text-sm text-gray-600">Periode:</span>
                            <p class="font-medium text-gray-900 mt-1">
                                {{ $executiveSummary->period_start->format('d M Y') }} -
                                {{ $executiveSummary->period_end->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $executiveSummary->period_duration_days }} hari</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">Tanggal Laporan:</span>
                            <p class="font-medium text-gray-900 mt-1">{{ $executiveSummary->report_date->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">Dibuat oleh:</span>
                            <p class="font-medium text-gray-900 mt-1">{{ $executiveSummary->createdBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $executiveSummary->created_at->diffForHumans() }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-gray-600">Status:</span>
                            <p class="font-medium mt-1">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm
                                {{ $executiveSummary->status == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $executiveSummary->status == 'under_review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $executiveSummary->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $executiveSummary->status == 'published' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ $executiveSummary->status_label }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Financial Summary --}}
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">Ringkasan Keuangan</h2>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="text-sm text-green-600 mb-1">Total Pendapatan</div>
                            <div class="text-lg font-bold text-green-900">
                                Rp {{ number_format($executiveSummary->total_income ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="text-sm text-red-600 mb-1">Total Pengeluaran</div>
                            <div class="text-lg font-bold text-red-900">
                                Rp {{ number_format($executiveSummary->total_expenses ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <div
                            class="bg-{{ $executiveSummary->is_profitable ? 'blue' : 'gray' }}-50 rounded-lg p-4 border border-{{ $executiveSummary->is_profitable ? 'blue' : 'gray' }}-200">
                            <div class="text-sm text-{{ $executiveSummary->is_profitable ? 'blue' : 'gray' }}-600 mb-1">Net
                                Result</div>
                            <div
                                class="text-lg font-bold text-{{ $executiveSummary->is_profitable ? 'blue' : 'gray' }}-900">
                                Rp {{ number_format($executiveSummary->net_result ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    @if ($executiveSummary->budget_utilization_percentage)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">Budget Utilization</span>
                                <span
                                    class="text-sm font-bold text-gray-900">{{ number_format($executiveSummary->budget_utilization_percentage, 2) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-{{ $executiveSummary->getBudgetStatus() == 'over_budget' ? 'red' : ($executiveSummary->getBudgetStatus() == 'on_budget' ? 'yellow' : 'green') }}-600 h-2 rounded-full"
                                    style="width: {{ min($executiveSummary->budget_utilization_percentage, 100) }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if ($executiveSummary->profit_margin_percentage)
                        <div class="mt-3 text-sm text-gray-600">
                            <span class="font-medium">Profit Margin:</span>
                            {{ number_format($executiveSummary->profit_margin_percentage, 2) }}%
                        </div>
                    @endif
                </div>

                {{-- Event Statistics --}}
                @if (
                    $executiveSummary->events_conducted ||
                        $executiveSummary->total_participants ||
                        $executiveSummary->satisfaction_score)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Statistik Event</h2>

                        <div class="grid grid-cols-3 gap-3">
                            @if ($executiveSummary->events_conducted)
                                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="text-lg font-bold text-blue-900">{{ $executiveSummary->events_conducted }}
                                    </div>
                                    <div class="text-sm text-blue-600 mt-1">Events Conducted</div>
                                </div>
                            @endif

                            @if ($executiveSummary->total_participants)
                                <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                                    <div class="text-lg font-bold text-purple-900">
                                        {{ number_format($executiveSummary->total_participants) }}</div>
                                    <div class="text-sm text-purple-600 mt-1">Total Participants</div>
                                    @if ($executiveSummary->getAverageParticipantsPerEvent())
                                        <div class="text-xs text-purple-500 mt-1">Avg:
                                            {{ $executiveSummary->getAverageParticipantsPerEvent() }}/event</div>
                                    @endif
                                </div>
                            @endif

                            @if ($executiveSummary->satisfaction_score)
                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                    <div class="text-lg font-bold text-green-900">
                                        {{ number_format($executiveSummary->satisfaction_score, 1) }}/5</div>
                                    <div class="text-sm text-green-600 mt-1">Satisfaction Score</div>
                                    @if ($executiveSummary->getPerformanceRating())
                                        <div class="text-xs text-green-500 mt-1">
                                            {{ ucfirst(str_replace('_', ' ', $executiveSummary->getPerformanceRating())) }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Content Sections --}}
                @if ($executiveSummary->executive_overview)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Executive Overview</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($executiveSummary->executive_overview)) !!}
                        </div>
                    </div>
                @endif

                @if ($executiveSummary->key_highlights)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Key Highlights</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($executiveSummary->key_highlights)) !!}
                        </div>
                    </div>
                @endif

                @if ($executiveSummary->achievements)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Achievements</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($executiveSummary->achievements)) !!}
                        </div>
                    </div>
                @endif

                @if ($executiveSummary->challenges)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Challenges</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($executiveSummary->challenges)) !!}
                        </div>
                    </div>
                @endif

                @if ($executiveSummary->recommendations)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Recommendations</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($executiveSummary->recommendations)) !!}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-4">
                {{-- Workflow Actions --}}
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Workflow Actions</h3>

                    <div class="space-y-3">
                        @if ($executiveSummary->isDraft())
                            <form action="{{ route('admin.reports.executive-summaries.submit-for-review', $executiveSummary) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 text-sm rounded-lg text-sm font-medium transition-colors">
                                    Submit for Review
                                </button>
                            </form>
                        @endif

                        @if ($executiveSummary->isUnderReview() && $executiveSummary->canBeReviewedBy(Auth::user()))
                            <form action="{{ route('admin.reports.executive-summaries.review', $executiveSummary) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 text-sm rounded-lg text-sm font-medium transition-colors">
                                    Mark as Reviewed
                                </button>
                            </form>
                        @endif

                        @if ($executiveSummary->canBeApprovedBy(Auth::user()))
                            <form action="{{ route('admin.reports.executive-summaries.approve', $executiveSummary) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 text-sm rounded-lg text-sm font-medium transition-colors">
                                    Approve Summary
                                </button>
                            </form>
                        @endif

                        @if ($executiveSummary->isApproved() && $executiveSummary->canBeApprovedBy(Auth::user()))
                            <form action="{{ route('admin.reports.executive-summaries.publish', $executiveSummary) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 text-sm rounded-lg text-sm font-medium transition-colors">
                                    Publish Summary
                                </button>
                            </form>
                        @endif

                        @if ($executiveSummary->isUnderReview() && $executiveSummary->canBeReviewedBy(Auth::user()))
                            <form action="{{ route('admin.reports.executive-summaries.reject', $executiveSummary) }}"
                                method="POST" onsubmit="return confirm('Kembalikan ke draft?')">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 text-sm rounded-lg text-sm font-medium transition-colors">
                                    Reject to Draft
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.reports.executive-summaries.generate-pdf', $executiveSummary) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 text-sm rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Generate PDF
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Approval History --}}
                @if ($executiveSummary->reviewedBy || $executiveSummary->approvedBy)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Approval History</h3>

                        <div class="space-y-3">
                            @if ($executiveSummary->reviewedBy)
                                <div class="flex items-start gap-3">
                                    <div class="bg-yellow-100 p-2 rounded-full">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Reviewed by</p>
                                        <p class="text-xs text-gray-600">{{ $executiveSummary->reviewedBy->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $executiveSummary->reviewed_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($executiveSummary->approvedBy)
                                <div class="flex items-start gap-3">
                                    <div class="bg-green-100 p-2 rounded-full">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Approved by</p>
                                        <p class="text-xs text-gray-600">{{ $executiveSummary->approvedBy->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $executiveSummary->approved_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Supporting Documents --}}
                @if ($executiveSummary->supporting_documents && count($executiveSummary->supporting_documents) > 0)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Supporting Documents</h3>

                        <div class="space-y-2">
                            @foreach ($executiveSummary->supporting_documents as $doc)
                                <a href="{{ Storage::url($doc) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700 flex-1 truncate">{{ basename($doc) }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Delete Summary --}}
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <h3 class="text-lg font-bold text-red-900 mb-1">Danger Zone</h3>
                    <p class="text-sm text-red-700 mb-3">Tindakan ini tidak dapat dibatalkan</p>

                    <form action="{{ route('admin.reports.executive-summaries.destroy', $executiveSummary) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus summary ini? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 text-sm rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Summary
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
