{{-- resources/views/admin/timeline/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Project Timeline')

@section('content')
    <div x-data="timelineManager()">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Project Timeline</h1>
                    <p class="text-gray-600 mt-1">Kelola timeline dan jadwal proyek event</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.timeline.gantt-chart') }}"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="hidden sm:inline">Gantt Chart</span>
                        <span class="sm:hidden">Gantt</span>
                    </a>
                    <button @click="openModal('create')"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Tambah Timeline</span>
                        <span class="sm:hidden">Tambah</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('admin.timeline.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                        <select name="event_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">Semua Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">Semua Status</option>
                            <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Belum Dimulai</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Berjalan</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>Terlambat</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                        <select name="priority"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">Semua Prioritas</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari timeline..."
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm whitespace-nowrap">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Timeline List - Desktop View --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hidden lg:block">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1200px]">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">Nama Timeline</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Event</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Progress</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Prioritas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">PIC</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($timelines as $timeline)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-900">
                                    {{ $timeline->code }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-start">
                                        @if ($timeline->level > 0)
                                            <span class="text-gray-400 mr-2 flex-shrink-0"
                                                style="margin-left: {{ $timeline->level * 15 }}px">└─</span>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $timeline->name }}</div>
                                            @if ($timeline->description)
                                                <div class="text-xs text-gray-500 truncate">
                                                    {{ Str::limit($timeline->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                                    <span class="truncate block">{{ $timeline->event->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                    <div>{{ $timeline->start_date->format('d M Y') }}</div>
                                    <div>{{ $timeline->end_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $timeline->duration_days }} hari</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 min-w-[60px]">
                                            <div class="bg-primary h-2 rounded-full"
                                                style="width: {{ $timeline->progress_percentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600 flex-shrink-0">{{ $timeline->progress_percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'not_started' => 'gray',
                                            'in_progress' => 'blue',
                                            'completed' => 'green',
                                            'delayed' => 'red',
                                            'cancelled' => 'gray',
                                        ];
                                        $statusLabels = [
                                            'not_started' => 'Belum Dimulai',
                                            'in_progress' => 'Sedang Berjalan',
                                            'completed' => 'Selesai',
                                            'delayed' => 'Terlambat',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                        $color = $statusColors[$timeline->status] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 inline-block">
                                        {{ $statusLabels[$timeline->status] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $priorityColors = [
                                            'low' => 'gray',
                                            'medium' => 'yellow',
                                            'high' => 'orange',
                                            'urgent' => 'red',
                                        ];
                                        $priorityLabels = [
                                            'low' => 'Rendah',
                                            'medium' => 'Sedang',
                                            'high' => 'Tinggi',
                                            'urgent' => 'Mendesak',
                                        ];
                                        $pColor = $priorityColors[$timeline->priority] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $pColor }}-100 text-{{ $pColor }}-800 inline-block">
                                        {{ $priorityLabels[$timeline->priority] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                    <span class="truncate block">{{ $timeline->assignedUser->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-xs font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openModal('edit', {{ $timeline->id }})"
                                            class="text-primary hover:text-primary-dark p-1" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.timeline.duplicate', $timeline) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 p-1" title="Duplikat">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.timeline.destroy', $timeline) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus timeline ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan timeline baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($timelines->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $timelines->links() }}
                </div>
            @endif
        </div>

        {{-- Timeline List - Mobile/Tablet View (Card Layout) --}}
        <div class="lg:hidden space-y-4">
            @forelse($timelines as $timeline)
                <div class="bg-white rounded-lg shadow-sm p-4">
                    {{-- Header --}}
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-gray-500">{{ $timeline->code }}</span>
                                @php
                                    $priorityColors = [
                                        'low' => 'gray',
                                        'medium' => 'yellow',
                                        'high' => 'orange',
                                        'urgent' => 'red',
                                    ];
                                    $priorityLabels = [
                                        'low' => 'Rendah',
                                        'medium' => 'Sedang',
                                        'high' => 'Tinggi',
                                        'urgent' => 'Mendesak',
                                    ];
                                    $pColor = $priorityColors[$timeline->priority] ?? 'gray';
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-{{ $pColor }}-100 text-{{ $pColor }}-800">
                                    {{ $priorityLabels[$timeline->priority] }}
                                </span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 truncate" 
                                style="margin-left: {{ $timeline->level * 15 }}px">
                                @if ($timeline->level > 0)
                                    <span class="text-gray-400 mr-1">└─</span>
                                @endif
                                {{ $timeline->name }}
                            </h3>
                            @if ($timeline->description)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($timeline->description, 60) }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
                        <div>
                            <span class="text-gray-500">Event:</span>
                            <p class="font-medium text-gray-900 truncate">{{ $timeline->event->name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">PIC:</span>
                            <p class="font-medium text-gray-900 truncate">{{ $timeline->assignedUser->name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Mulai:</span>
                            <p class="font-medium text-gray-900">{{ $timeline->start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Selesai:</span>
                            <p class="font-medium text-gray-900">{{ $timeline->end_date->format('d M Y') }}</p>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mb-3">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-500">Progress</span>
                            <span class="font-medium text-gray-900">{{ $timeline->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $timeline->progress_percentage }}%"></div>
                        </div>
                    </div>

                    {{-- Status & Actions --}}
                    <div class="flex justify-between items-center">
                        @php
                            $statusColors = [
                                'not_started' => 'gray',
                                'in_progress' => 'blue',
                                'completed' => 'green',
                                'delayed' => 'red',
                                'cancelled' => 'gray',
                            ];
                            $statusLabels = [
                                'not_started' => 'Belum Dimulai',
                                'in_progress' => 'Sedang Berjalan',
                                'completed' => 'Selesai',
                                'delayed' => 'Terlambat',
                                'cancelled' => 'Dibatalkan',
                            ];
                            $color = $statusColors[$timeline->status] ?? 'gray';
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ $statusLabels[$timeline->status] }}
                        </span>

                        <div class="flex items-center gap-2">
                            <button @click="openModal('edit', {{ $timeline->id }})"
                                class="text-primary hover:text-primary-dark p-1.5" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form action="{{ route('admin.timeline.duplicate', $timeline) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 p-1.5" title="Duplikat">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.timeline.destroy', $timeline) }}" method="POST" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus timeline ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1.5" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan timeline baru.</p>
                </div>
            @endforelse

            {{-- Pagination Mobile --}}
            @if ($timelines->hasPages())
                <div class="mt-4">
                    {{ $timelines->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Create/Edit --}}
        @include('admin.timeline.project.partials.modal-form')
    </div>
@endsection

@push('scripts')
    <script>
        function timelineManager() {
            return {
                showModal: false,
                modalMode: 'create',
                timelineId: null,

                openModal(mode, id = null) {
                    this.modalMode = mode;
                    this.timelineId = id;
                    this.showModal = true;

                    if (mode === 'edit' && id) {
                        // Load timeline data via AJAX if needed
                        // Or use inline data
                    }
                },

                closeModal() {
                    this.showModal = false;
                    this.timelineId = null;
                }
            }
        }
    </script>
@endpush