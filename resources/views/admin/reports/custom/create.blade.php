@extends('admin.layouts.app')

@section('title', 'Buat Custom Report Baru')

@push('styles')
    <style>
        .config-item {
            @apply p-4 bg-gray-50 border border-gray-200 rounded-lg;
        }

        .draggable {
            cursor: move;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6" x-data="reportBuilder()">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Custom Report Baru</h1>
                <p class="mt-1 text-sm text-gray-600">Konfigurasi dan buat custom report sesuai kebutuhan</p>
            </div>
            <a href="{{ route('admin.reports.custom.index') }}"
                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.reports.custom.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Basic Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Report <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                            placeholder="Masukkan judul report...">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                            placeholder="Deskripsi singkat tentang report ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Report Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Report <span class="text-red-500">*</span>
                        </label>
                        <select name="report_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Tipe</option>
                            @foreach ($reportTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('report_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        @error('report_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Event --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event (Opsional)</label>
                        <select name="event_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Period Start --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode Mulai</label>
                        <input type="date" name="period_start" value="{{ old('period_start') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        @error('period_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Period End --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode Selesai</label>
                        <input type="date" name="period_end" value="{{ old('period_end') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        @error('period_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Configuration --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfigurasi Report</h3>

                {{-- Data Sources --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Sumber Data</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach ($dataSources as $key => $label)
                            <label
                                class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="data_sources[]" value="{{ $key }}"
                                    {{ in_array($key, old('data_sources', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Filters --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-700">Filter</label>
                        <button type="button" @click="addFilter"
                            class="text-sm text-[#0053C5] hover:text-[#004AB0] font-medium">
                            + Tambah Filter
                        </button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(filter, index) in filters" :key="index">
                            <div class="config-item flex items-center gap-3">
                                <input type="text" :name="'filters[' + index + '][field]'" x-model="filter.field"
                                    placeholder="Field"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                <select :name="'filters[' + index + '][operator]'" x-model="filter.operator"
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                    <option value="=">=</option>
                                    <option value="!=">!=</option>
                                    <option value=">">></option>
                                    <option value="<">
                                        << /option>
                                    <option value=">=">>=</option>
                                    <option value="<=">
                                        <=< /option>
                                    <option value="like">LIKE</option>
                                </select>
                                <input type="text" :name="'filters[' + index + '][value]'" x-model="filter.value"
                                    placeholder="Value"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                <button type="button" @click="removeFilter(index)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="filters.length === 0" class="text-sm text-gray-500 italic text-center py-4">
                            Belum ada filter ditambahkan
                        </div>
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-700">Metrik</label>
                        <button type="button" @click="addMetric"
                            class="text-sm text-[#0053C5] hover:text-[#004AB0] font-medium">
                            + Tambah Metrik
                        </button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(metric, index) in metrics" :key="index">
                            <div class="config-item flex items-center gap-3">
                                <input type="text" :name="'metrics[' + index + '][name]'" x-model="metric.name"
                                    placeholder="Nama Metrik"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                <select :name="'metrics[' + index + '][aggregation]'" x-model="metric.aggregation"
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                    <option value="count">Count</option>
                                    <option value="sum">Sum</option>
                                    <option value="avg">Average</option>
                                    <option value="max">Max</option>
                                    <option value="min">Min</option>
                                </select>
                                <input type="text" :name="'metrics[' + index + '][field]'" x-model="metric.field"
                                    placeholder="Field"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                <button type="button" @click="removeMetric(index)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="metrics.length === 0" class="text-sm text-gray-500 italic text-center py-4">
                            Belum ada metrik ditambahkan
                        </div>
                    </div>
                </div>

                {{-- Dimensions --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-700">Dimensi (Group By)</label>
                        <button type="button" @click="addDimension"
                            class="text-sm text-[#0053C5] hover:text-[#004AB0] font-medium">
                            + Tambah Dimensi
                        </button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(dimension, index) in dimensions" :key="index">
                            <div class="config-item flex items-center gap-3">
                                <input type="text" :name="'dimensions[' + index + '][field]'" x-model="dimension.field"
                                    placeholder="Field"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                <input type="text" :name="'dimensions[' + index + '][label]'"
                                    x-model="dimension.label" placeholder="Label"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                <button type="button" @click="removeDimension(index)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="dimensions.length === 0" class="text-sm text-gray-500 italic text-center py-4">
                            Belum ada dimensi ditambahkan
                        </div>
                    </div>
                </div>

                {{-- Chart Configuration --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Konfigurasi Chart</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 mb-2">Tipe Chart</label>
                            <select name="chart_config[type]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                <option value="">Tidak Ada Chart</option>
                                @foreach ($chartTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-2">Warna</label>
                            <input type="color" name="chart_config[color]" value="#0053C5"
                                class="w-full h-10 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Settings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Visibility --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Visibility <span class="text-red-500">*</span>
                        </label>
                        <select name="visibility" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            @foreach ($visibilities as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('visibility', 'private') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('visibility')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="saved" {{ old('status') == 'saved' ? 'selected' : '' }}>Tersimpan</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Dipublikasikan
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Scheduling --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 mb-4">
                            <input type="checkbox" name="is_scheduled" value="1"
                                {{ old('is_scheduled') ? 'checked' : '' }}
                                class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]"
                                x-model="isScheduled">
                            <span class="text-sm font-medium text-gray-700">Aktifkan Penjadwalan Otomatis</span>
                        </label>

                        <div x-show="isScheduled" x-collapse class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-6">
                            <div>
                                <label class="block text-xs text-gray-600 mb-2">Frekuensi</label>
                                <select name="schedule_frequency"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent text-sm">
                                    @foreach ($frequencies as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Generate Now --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="generate_now" value="1"
                                class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                            <span class="text-sm font-medium text-gray-700">Generate report setelah disimpan</span>
                        </label>
                        <p class="text-xs text-gray-500 ml-6 mt-1">Report akan di-generate otomatis setelah dibuat</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.reports.custom.index') }}"
                    class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Report
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function reportBuilder() {
            return {
                isScheduled: false,
                filters: [],
                metrics: [],
                dimensions: [],

                addFilter() {
                    this.filters.push({
                        field: '',
                        operator: '=',
                        value: ''
                    });
                },

                removeFilter(index) {
                    this.filters.splice(index, 1);
                },

                addMetric() {
                    this.metrics.push({
                        name: '',
                        aggregation: 'count',
                        field: ''
                    });
                },

                removeMetric(index) {
                    this.metrics.splice(index, 1);
                },

                addDimension() {
                    this.dimensions.push({
                        field: '',
                        label: ''
                    });
                },

                removeDimension(index) {
                    this.dimensions.splice(index, 1);
                }
            }
        }
    </script>
@endpush
