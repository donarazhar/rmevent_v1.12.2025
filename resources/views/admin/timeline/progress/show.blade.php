@extends('admin.layouts.app')

@section('title', 'Detail Progress Report')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Progress Report</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $progressReport->report_code }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.progress-reports.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                @if (in_array($progressReport->status, ['draft', 'rejected']))
                    <a href="{{ route('admin.progress-reports.edit', $progressReport) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif

                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
            </div>
        </div>

        {{-- Status Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-xl font-bold text-gray-900">{{ $progressReport->title }}</h2>
                            @php
                                $statusConfig = [
                                    'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Draft'],
                                    'submitted' => [
                                        'bg' => 'bg-blue-100',
                                        'text' => 'text-blue-800',
                                        'label' => 'Submitted',
                                    ],
                                    'reviewed' => [
                                        'bg' => 'bg-yellow-100',
                                        'text' => 'text-yellow-800',
                                        'label' => 'Reviewed',
                                    ],
                                    'approved' => [
                                        'bg' => 'bg-green-100',
                                        'text' => 'text-green-800',
                                        'label' => 'Approved',
                                    ],
                                    'rejected' => [
                                        'bg' => 'bg-red-100',
                                        'text' => 'text-red-800',
                                        'label' => 'Rejected',
                                    ],
                                ];
                                $config = $statusConfig[$progressReport->status];
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $config['label'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                            <div>
                                <p class="text-gray-500 mb-1">Event</p>
                                <p class="font-medium text-gray-900">{{ $progressReport->event->name }}</p>
                            </div>
                            @if ($progressReport->structure)
                                <div>
                                    <p class="text-gray-500 mb-1">Struktur</p>
                                    <p class="font-medium text-gray-900">{{ $progressReport->structure->name }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-gray-500 mb-1">Periode</p>
                                <p class="font-medium text-gray-900">
                                    {{ $progressReport->period_start->format('d M Y') }} -
                                    {{ $progressReport->period_end->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Tanggal Report</p>
                                <p class="font-medium text-gray-900">{{ $progressReport->report_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Dibuat Oleh</p>
                                <p class="font-medium text-gray-900">{{ $progressReport->creator->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Tanggal Dibuat</p>
                                <p class="font-medium text-gray-900">{{ $progressReport->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Workflow Actions --}}
        @if ($progressReport->status === 'draft')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Submit Report untuk Review</h3>
                <form action="{{ route('admin.progress-reports.submit', $progressReport) }}" method="POST"
                    class="flex items-end gap-4">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-blue-900 mb-2">
                            Submit Kepada
                        </label>
                        <select name="submitted_to" required
                            class="w-full rounded-lg border-blue-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Reviewer</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                        Submit Report
                    </button>
                </form>
            </div>
        @endif

        @if ($progressReport->status === 'submitted')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-yellow-900 mb-4">Review Report</h3>
                <form action="{{ route('admin.progress-reports.approve', $progressReport) }}" method="POST" x-data="{ action: '' }">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-yellow-900 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="notes" rows="3"
                                class="w-full rounded-lg border-yellow-300 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>

                        <div x-show="action === 'reject'">
                            <label class="block text-sm font-medium text-red-900 mb-2">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="feedback" rows="3"
                                class="w-full rounded-lg border-red-300 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                placeholder="Jelaskan alasan penolakan..." :required="action === 'reject'"></textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" name="action" value="approve" @click="action = 'approve'"
                                class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Approve
                            </button>
                            <button type="submit" name="action" value="reject" @click="action = 'reject'"
                                class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        @if ($progressReport->status === 'approved' && $progressReport->approval_notes)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-green-900">Catatan Approval</h4>
                        <p class="mt-1 text-sm text-green-700">{{ $progressReport->approval_notes }}</p>
                        <p class="mt-2 text-xs text-green-600">
                            Diapprove oleh {{ $progressReport->approver->name }} pada
                            {{ $progressReport->approved_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if ($progressReport->status === 'rejected' && $progressReport->reviewer_feedback)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-red-900">Alasan Penolakan</h4>
                        <p class="mt-1 text-sm text-red-700">{{ $progressReport->reviewer_feedback }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Progress Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Overall Progress --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Progress Keseluruhan</h3>
                    <span class="text-2xl font-bold text-primary">{{ $progressReport->overall_progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-primary h-3 rounded-full transition-all"
                        style="width: {{ $progressReport->overall_progress }}%"></div>
                </div>
            </div>

            {{-- Task Completion --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Task Completion</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Direncanakan</span>
                        <span class="font-semibold text-gray-900">{{ $progressReport->tasks_planned ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Selesai</span>
                        <span class="font-semibold text-green-600">{{ $progressReport->tasks_completed ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tertunda</span>
                        <span class="font-semibold text-red-600">{{ $progressReport->tasks_delayed ?? 0 }}</span>
                    </div>
                    @if ($progressReport->tasks_planned > 0)
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Completion Rate</span>
                                <span
                                    class="font-semibold text-primary">{{ $progressReport->task_completion_rate }}%</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Budget --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Budget</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Dialokasikan</span>
                        <span class="font-semibold text-gray-900">
                            {{ $progressReport->budget_allocated ? 'Rp ' . number_format($progressReport->budget_allocated, 0, ',', '.') : '-' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Terpakai</span>
                        <span class="font-semibold text-blue-600">
                            {{ $progressReport->budget_used ? 'Rp ' . number_format($progressReport->budget_used, 0, ',', '.') : '-' }}
                        </span>
                    </div>
                    @if ($progressReport->budget_allocated)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Variance</span>
                            <span
                                class="font-semibold {{ $progressReport->budget_variance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $progressReport->budget_variance >= 0 ? '+' : '' }}Rp
                                {{ number_format($progressReport->budget_variance, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Utilization</span>
                                <span
                                    class="font-semibold text-primary">{{ $progressReport->budget_utilization }}%</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Resource Stats --}}
        @if ($progressReport->team_members_involved || $progressReport->hours_spent)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($progressReport->team_members_involved)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Anggota Tim Terlibat</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $progressReport->team_members_involved }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($progressReport->hours_spent)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Jam Kerja</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($progressReport->hours_spent) }}
                                    <span class="text-sm font-normal text-gray-600">jam</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Report Content --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Konten Report</h3>
            </div>
            <div class="p-6 space-y-6">
                @if ($progressReport->executive_summary)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Ringkasan Eksekutif</h4>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($progressReport->executive_summary)) !!}
                        </div>
                    </div>
                @endif

                @if ($progressReport->activities_completed)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Aktivitas yang Telah Diselesaikan</h4>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($progressReport->activities_completed)) !!}
                        </div>
                    </div>
                @endif

                @if ($progressReport->ongoing_activities)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Aktivitas yang Sedang Berjalan</h4>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($progressReport->ongoing_activities)) !!}
                        </div>
                    </div>
                @endif

                @if ($progressReport->planned_activities)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Aktivitas yang Direncanakan</h4>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($progressReport->planned_activities)) !!}
                        </div>
                    </div>
                @endif

                @if ($progressReport->issues_challenges)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Kendala & Tantangan</h4>
                        <div class="prose prose-sm max-w-none text-gray-700 bg-red-50 border border-red-200 rounded-lg p-4">
                            {!! nl2br(e($progressReport->issues_challenges)) !!}
                        </div>
                    </div>
                @endif

                @if ($progressReport->solutions_recommendations)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Solusi & Rekomendasi</h4>
                        <div
                            class="prose prose-sm max-w-none text-gray-700 bg-green-50 border border-green-200 rounded-lg p-4">
                            {!! nl2br(e($progressReport->solutions_recommendations)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Attachments --}}
        @if ($progressReport->attachments && count($progressReport->attachments) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Lampiran Dokumen</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($progressReport->attachments as $attachment)
                            <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                                class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors group">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary">
                                        {{ $attachment['name'] ?? 'File' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format(($attachment['size'] ?? 0) / 1024, 2) }} KB
                                        @if (isset($attachment['type']))
                                            • {{ strtoupper(explode('/', $attachment['type'])[1] ?? '') }}
                                        @endif
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
@endpush