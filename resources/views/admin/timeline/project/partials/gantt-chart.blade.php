{{-- resources/views/admin/timeline/gantt-chart.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Gantt Chart - Project Timeline')

@push('styles')
    <style>
        .gantt-container {
            overflow-x: auto;
            overflow-y: auto;
            max-height: calc(100vh - 250px);
        }

        .gantt-header {
            background: #f9fafb;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .gantt-row {
            min-height: 50px;
            border-bottom: 1px solid #e5e7eb;
        }

        .gantt-bar {
            height: 30px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            padding: 0 8px;
            font-size: 12px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .gantt-bar:hover {
            opacity: 0.8;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .gantt-today-line {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #ef4444;
            z-index: 5;
            pointer-events: none;
        }

        .gantt-weekend {
            background: #f3f4f6;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush

@section('content')
    @php
        // Calculate date range for gantt chart
        $minDate = null;
        $maxDate = null;

        if ($timelines->isNotEmpty()) {
            $minDate = $timelines->min('start_date')->copy()->subDays(7);
            $maxDate = $timelines->max('end_date')->copy()->addDays(7);
        }
    @endphp

    <div x-data="ganttChart()">
        {{-- Header --}}
        <div class="mb-6 no-print">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gantt Chart</h1>
                    <p class="text-gray-600 mt-1">Visualisasi timeline proyek dalam bentuk Gantt Chart</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.timeline.index') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke List
                    </a>
                    <button @click="window.print()"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6 no-print">
            <form method="GET" action="{{ route('admin.timeline.gantt-chart') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                    <select name="event_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Semua Event</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ $eventId == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                    Filter
                </button>
            </form>
        </div>

        {{-- Legend --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex items-center gap-6 flex-wrap">
                <span class="text-sm font-medium text-gray-700">Status:</span>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gray-400"></div>
                    <span class="text-sm text-gray-600">Belum Dimulai</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-blue-500"></div>
                    <span class="text-sm text-gray-600">Sedang Berjalan</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-green-500"></div>
                    <span class="text-sm text-gray-600">Selesai</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-500"></div>
                    <span class="text-sm text-gray-600">Terlambat</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-8 bg-red-500"></div>
                    <span class="text-sm text-gray-600">Hari Ini</span>
                </div>
            </div>
        </div>

        {{-- Gantt Chart --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if ($timelines->isNotEmpty() && $minDate && $maxDate)
                <div class="gantt-container">
                    <div class="flex">
                        {{-- Task Names Column (Fixed) --}}
                        <div class="w-80 flex-shrink-0 bg-gray-50 border-r border-gray-200">
                            <div class="gantt-header border-b border-gray-200 p-4">
                                <h3 class="font-semibold text-gray-900">Timeline Task</h3>
                            </div>
                            @foreach ($timelines as $timeline)
                                <div class="gantt-row p-4 flex items-center">
                                    @if ($timeline->level > 0)
                                        <span class="text-gray-400 mr-2"
                                            style="margin-left: {{ $timeline->level * 15 }}px">└─</span>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $timeline->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $timeline->code }}</div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $timeline->start_date->format('d M') }} -
                                            {{ $timeline->end_date->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Timeline Chart (Scrollable) --}}
                        @php
                            $daysDiff = $minDate->diffInDays($maxDate);
                            $chartWidth = $daysDiff * 40;
                        @endphp
                        <div class="flex-1 relative" style="min-width: {{ $chartWidth }}px;">
                            {{-- Header with dates --}}
                            <div class="gantt-header border-b border-gray-200 flex">
                                @for ($i = 0; $i <= $daysDiff; $i++)
                                    @php
                                        $currentDate = $minDate->copy()->addDays($i);
                                        $isWeekend = in_array($currentDate->dayOfWeek, [0, 6]);
                                    @endphp
                                    <div class="flex-shrink-0 p-2 text-center border-r border-gray-200 {{ $isWeekend ? 'gantt-weekend' : '' }}"
                                        style="width: 40px;">
                                        <div class="text-xs font-medium text-gray-900">{{ $currentDate->format('d') }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $currentDate->format('D') }}</div>
                                    </div>
                                @endfor
                            </div>

                            {{-- Today indicator --}}
                            @php
                                $today = \Carbon\Carbon::today();
                                $todayDiff = $minDate->diffInDays($today);
                                $todayPosition = $todayDiff * 40;
                            @endphp
                            @if ($today->between($minDate, $maxDate))
                                <div class="gantt-today-line" style="left: {{ $todayPosition }}px;"></div>
                            @endif

                            {{-- Timeline bars --}}
                            @foreach ($timelines as $timeline)
                                <div class="gantt-row relative flex">
                                    @for ($i = 0; $i <= $daysDiff; $i++)
                                        @php
                                            $currentDate = $minDate->copy()->addDays($i);
                                            $isWeekend = in_array($currentDate->dayOfWeek, [0, 6]);
                                        @endphp
                                        <div class="flex-shrink-0 border-r border-gray-100 {{ $isWeekend ? 'gantt-weekend' : '' }}"
                                            style="width: 40px; height: 50px;">
                                        </div>
                                    @endfor

                                    {{-- Task bar --}}
                                    @php
                                        $statusColors = [
                                            'not_started' => '#9ca3af',
                                            'in_progress' => '#3b82f6',
                                            'completed' => '#10b981',
                                            'delayed' => '#ef4444',
                                            'cancelled' => '#6b7280',
                                        ];
                                        $color = $statusColors[$timeline->status] ?? '#9ca3af';

                                        $startDiff = $minDate->diffInDays($timeline->start_date);
                                        $barLeft = $startDiff * 40;

                                        $duration = $timeline->start_date->diffInDays($timeline->end_date) + 1;
                                        $barWidth = $duration * 40;
                                    @endphp
                                    <div class="absolute top-2.5 gantt-bar"
                                        style="background: {{ $color }}; 
                                            left: {{ $barLeft }}px; 
                                            width: {{ $barWidth }}px;"
                                        title="{{ $timeline->name }} - {{ $timeline->start_date->format('d M Y') }} to {{ $timeline->end_date->format('d M Y') }} ({{ $timeline->progress_percentage }}%)">
                                        <span class="truncate">{{ Str::limit($timeline->name, 20) }}</span>
                                        <span class="ml-auto text-xs">{{ $timeline->progress_percentage }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="p-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data timeline</h3>
                    <p class="mt-1 text-sm text-gray-500">Pilih event untuk menampilkan gantt chart.</p>
                </div>
            @endif
        </div>

        {{-- Statistics --}}
        @if ($timelines->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-600">Total Timeline</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $timelines->count() }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-600">Sedang Berjalan</div>
                    <div class="text-2xl font-bold text-blue-600 mt-2">
                        {{ $timelines->where('status', 'in_progress')->count() }}
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-600">Selesai</div>
                    <div class="text-2xl font-bold text-green-600 mt-2">
                        {{ $timelines->where('status', 'completed')->count() }}
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-600">Terlambat</div>
                    <div class="text-2xl font-bold text-red-600 mt-2">
                        {{ $timelines->where('status', 'delayed')->count() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function ganttChart() {
            return {
                // Add any Alpine.js methods if needed
            }
        }
    </script>
@endpush
