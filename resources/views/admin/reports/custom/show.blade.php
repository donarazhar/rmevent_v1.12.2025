@extends('admin.layouts.app')

@section('title', $customReport->title)

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.custom.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $customReport->title }}</h1>
                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                            {{ $customReport->report_code }}
                        </span>
                        <span>•</span>
                        <span>Dibuat oleh {{ $customReport->createdBy->name }}</span>
                        @if ($customReport->event)
                            <span>•</span>
                            <span>{{ $customReport->event->title }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Actions --}}
                @if ($customReport->canBeEditedBy(Auth::user()))
                    <a href="{{ route('admin.reports.custom.edit', $customReport) }}"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>

                    <form action="{{ route('admin.reports.custom.generate', $customReport) }}" method="POST"
                        class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Regenerate
                        </button>
                    </form>
                @endif

                {{-- Export Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-4 py-2 text-white bg-[#0053C5] rounded-lg hover:bg-[#004AB0]">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                        <a href="{{ route('admin.reports.custom.export', $customReport) }}?format=pdf"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Export PDF
                        </a>
                        <a href="{{ route('admin.reports.custom.export', $customReport) }}?format=excel"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Export Excel
                        </a>
                        <a href="{{ route('admin.reports.custom.export', $customReport) }}?format=csv"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status & Info --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p
                            class="mt-2 text-lg font-semibold
                            @if ($customReport->status == 'published') text-green-600
                            @elseif($customReport->status == 'saved') text-blue-600
                            @else text-gray-600 @endif">
                            {{ $customReport->status_label }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        @if ($customReport->status == 'published')
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Views</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($customReport->view_count) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Exports</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($customReport->export_count) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Last Generated</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900">
                            @if ($customReport->last_generated_at)
                                {{ $customReport->last_generated_at->diffForHumans() }}
                            @else
                                <span class="text-gray-400">Never</span>
                            @endif
                        </p>
                        @if ($customReport->is_stale)
                            <p class="text-xs text-orange-600 mt-1">⚠ Needs Update</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Report Description --}}
        @if ($customReport->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi</h3>
                <p class="text-gray-600">{{ $customReport->description }}</p>
            </div>
        @endif

        {{-- Configuration Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Data Sources --}}
            @if ($customReport->data_sources)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Sumber Data</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($customReport->data_sources as $source)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($source) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Period --}}
            @if ($customReport->period_start && $customReport->period_end)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Periode</h3>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Mulai:</span>
                            {{ $customReport->period_start->format('d M Y') }}
                        </div>
                        <span>→</span>
                        <div>
                            <span class="font-medium">Selesai:</span>
                            {{ $customReport->period_end->format('d M Y') }}
                        </div>
                    </div>
                    @if ($customReport->period_duration_days)
                        <p class="text-xs text-gray-500 mt-2">
                            ({{ $customReport->period_duration_days }} hari)
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Filters, Metrics, Dimensions --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Filters --}}
            @if ($customReport->filters)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Filter yang Diterapkan</h3>
                    <div class="space-y-2">
                        @foreach ($customReport->filters as $filter)
                            <div class="text-xs bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <code class="text-gray-700">
                                    {{ $filter['field'] ?? '' }} {{ $filter['operator'] ?? '' }}
                                    {{ $filter['value'] ?? '' }}
                                </code>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Metrics --}}
            @if ($customReport->metrics)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Metrik</h3>
                    <div class="space-y-2">
                        @foreach ($customReport->metrics as $metric)
                            <div class="text-xs bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <div class="font-semibold text-gray-900">{{ $metric['name'] ?? '' }}</div>
                                <div class="text-gray-600 mt-1">
                                    {{ strtoupper($metric['aggregation'] ?? '') }}({{ $metric['field'] ?? '' }})
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Dimensions --}}
            @if ($customReport->dimensions)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Dimensi (Group By)</h3>
                    <div class="space-y-2">
                        @foreach ($customReport->dimensions as $dimension)
                            <div class="text-xs bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <div class="font-semibold text-gray-900">{{ $dimension['label'] ?? $dimension['field'] }}
                                </div>
                                <div class="text-gray-600 mt-1">{{ $dimension['field'] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Report Data Visualization --}}
        @if ($customReport->report_data)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Data</h3>

                {{-- Chart if configured --}}
                @if ($customReport->chart_config && isset($customReport->chart_config['type']))
                    <div class="mb-6">
                        <canvas id="reportChart" class="w-full" style="max-height: 400px;"></canvas>
                    </div>
                @endif

                {{-- Data Table --}}
                <div class="overflow-x-auto">
                    <pre class="bg-gray-50 rounded-lg p-4 text-xs overflow-auto">{{ json_encode($customReport->report_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-yellow-800">Report belum di-generate</h3>
                <p class="mt-2 text-sm text-yellow-600">Klik tombol "Regenerate" untuk generate report ini.</p>
            </div>
        @endif

        {{-- Scheduling Info --}}
        @if ($customReport->is_scheduled)
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-purple-900">Scheduled Report</h3>
                        <p class="text-sm text-purple-700 mt-1">
                            Report ini dijadwalkan untuk di-generate secara
                            <strong>{{ $customReport->schedule_frequency }}</strong>
                            @if ($customReport->next_scheduled_run)
                                . Run berikutnya: {{ $customReport->next_scheduled_run->format('d M Y H:i') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@if ($customReport->chart_config && isset($customReport->chart_config['type']) && $customReport->report_data)
    @push('scripts')
        <script>
            // Sample chart data - you would populate this from report_data
            const ctx = document.getElementById('reportChart');
            new Chart(ctx, {
                type: '{{ $customReport->chart_config['type'] }}',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: '{{ $customReport->title }}',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: '{{ $customReport->chart_config['color'] ?? '#0053C5' }}',
                        borderColor: '{{ $customReport->chart_config['color'] ?? '#0053C5' }}',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush
@endif
