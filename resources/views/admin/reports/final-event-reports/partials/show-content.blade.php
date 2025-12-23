{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- Completion Progress --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-600">Kelengkapan</span>
            <span
                class="text-2xl font-bold text-[#0053C5]">{{ number_format($finalEventReport->completion_percentage, 0) }}%</span>
        </div>
        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-[#0053C5] rounded-full" style="width: {{ $finalEventReport->completion_percentage }}%">
            </div>
        </div>
    </div>

    {{-- Participants --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Peserta</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ number_format($finalEventReport->total_participants ?? 0) }}</p>
                @if ($finalEventReport->attendance_rate)
                    <p class="text-xs text-gray-500 mt-1">Kehadiran:
                        {{ number_format($finalEventReport->attendance_rate, 1) }}%</p>
                @endif
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Financial Status --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Status Keuangan</p>
                <p
                    class="text-2xl font-bold mt-1 {{ $finalEventReport->is_surplus ? 'text-green-600' : ($finalEventReport->surplus_deficit < 0 ? 'text-red-600' : 'text-gray-900') }}">
                    Rp {{ number_format(abs($finalEventReport->surplus_deficit ?? 0), 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $finalEventReport->is_surplus ? 'Surplus' : ($finalEventReport->surplus_deficit < 0 ? 'Defisit' : 'Balanced') }}
                </p>
            </div>
            <div
                class="w-12 h-12 {{ $finalEventReport->is_surplus ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 {{ $finalEventReport->is_surplus ? 'text-green-600' : 'text-red-600' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Overall Satisfaction --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Kepuasan</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ number_format($finalEventReport->overall_satisfaction ?? 0, 1) }}/5</p>
                <p class="text-xs text-gray-500 mt-1">Rating Keseluruhan</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Event Information --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Acara</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600">Nama Acara</p>
            <p class="text-base font-medium text-gray-900 mt-1">{{ $finalEventReport->event->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Tanggal Acara</p>
            <p class="text-base font-medium text-gray-900 mt-1">
                {{ $finalEventReport->event->start_datetime->format('d M Y') }} -
                {{ $finalEventReport->event->end_datetime->format('d M Y') }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Lokasi</p>
            <p class="text-base font-medium text-gray-900 mt-1">{{ $finalEventReport->event->location ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Kategori</p>
            <p class="text-base font-medium text-gray-900 mt-1">{{ $finalEventReport->event->category->name ?? '-' }}
            </p>
        </div>
    </div>
</div>

{{-- Report Content Sections --}}
<div class="space-y-6">
    @if ($finalEventReport->executive_summary)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Ringkasan Eksekutif</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->executive_summary)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->event_overview)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Gambaran Umum Acara</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->event_overview)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->objectives_achievement)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Pencapaian Tujuan</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->objectives_achievement)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->implementation_process)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Proses Pelaksanaan</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->implementation_process)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->participant_analysis)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Analisis Peserta</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->participant_analysis)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->financial_report)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Laporan Keuangan</h2>
            <div class="prose max-w-none text-gray-700 mb-4">
                {!! nl2br(e($finalEventReport->financial_report)) !!}
            </div>
            @if ($finalEventReport->total_budget || $finalEventReport->total_income || $finalEventReport->total_expenses)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Anggaran</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">
                            Rp {{ number_format($finalEventReport->total_budget ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Pemasukan</p>
                        <p class="text-lg font-bold text-green-600 mt-1">
                            Rp {{ number_format($finalEventReport->total_income ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Pengeluaran</p>
                        <p class="text-lg font-bold text-red-600 mt-1">
                            Rp {{ number_format($finalEventReport->total_expenses ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Surplus/Defisit</p>
                        <p
                            class="text-lg font-bold mt-1 {{ $finalEventReport->is_surplus ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format(abs($finalEventReport->surplus_deficit ?? 0), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if ($finalEventReport->challenges_solutions)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Tantangan & Solusi</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->challenges_solutions)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->lessons_learned)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Pelajaran yang Dipetik</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->lessons_learned)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->recommendations)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Rekomendasi</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->recommendations)) !!}
            </div>
        </div>
    @endif

    @if ($finalEventReport->conclusion)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Kesimpulan</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($finalEventReport->conclusion)) !!}
            </div>
        </div>
    @endif
</div>

{{-- Workflow History --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Workflow</h2>
    <div class="space-y-3">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Dibuat</p>
                <p class="text-sm text-gray-600">{{ $finalEventReport->createdBy->name }} -
                    {{ $finalEventReport->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        @if ($finalEventReport->reviewed_at)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Ditinjau</p>
                    <p class="text-sm text-gray-600">{{ $finalEventReport->reviewedBy->name }} -
                        {{ $finalEventReport->reviewed_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        @endif

        @if ($finalEventReport->approved_at)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Disetujui</p>
                    <p class="text-sm text-gray-600">{{ $finalEventReport->approvedBy->name }} -
                        {{ $finalEventReport->approved_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        @endif

        @if ($finalEventReport->published_at)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Dipublikasikan</p>
                    <p class="text-sm text-gray-600">{{ $finalEventReport->published_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        @endif
    </div>

    @if ($finalEventReport->notes)
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm font-medium text-gray-900 mb-1">Catatan:</p>
            <p class="text-sm text-gray-700">{{ $finalEventReport->notes }}</p>
        </div>
    @endif
</div>
