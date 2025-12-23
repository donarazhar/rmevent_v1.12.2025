@extends('admin.layouts.app')

@section('title', 'Buat Executive Summary')

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
                    <h1 class="text-2xl font-bold text-gray-900">Buat Executive Summary</h1>
                </div>
                <p class="text-sm text-gray-600">Buat laporan ringkasan eksekutif baru</p>
            </div>
            <div class="bg-blue-50 px-3 py-2 rounded-lg border border-blue-200">
                <span class="text-xs text-gray-600">Kode Summary:</span>
                <span class="text-sm font-mono font-bold text-blue-600 ml-2">{{ $summaryCode }}</span>
            </div>
        </div>

        <form action="{{ route('admin.reports.executive-summaries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                {{-- Main Form --}}
                <div class="lg:col-span-2 space-y-3">
                    {{-- Basic Information --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Informasi Dasar</h2>

                        <div class="space-y-3">
                            {{-- Title --}}
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Judul <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                                    placeholder="Contoh: Executive Summary Ramadhan 1447 H">
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Summary Type --}}
                            <div>
                                <label for="summary_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tipe Summary <span class="text-red-500">*</span>
                                </label>
                                <select name="summary_type" id="summary_type" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('summary_type') border-red-500 @enderror">
                                    <option value="">Pilih Tipe Summary</option>
                                    <option value="monthly" {{ old('summary_type') == 'monthly' ? 'selected' : '' }}>Monthly
                                        - Bulanan</option>
                                    <option value="quarterly" {{ old('summary_type') == 'quarterly' ? 'selected' : '' }}>
                                        Quarterly - Kuartalan</option>
                                    <option value="event" {{ old('summary_type') == 'event' ? 'selected' : '' }}>Event -
                                        Berdasarkan Event</option>
                                    <option value="annual" {{ old('summary_type') == 'annual' ? 'selected' : '' }}>Annual -
                                        Tahunan</option>
                                </select>
                                @error('summary_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Event (Optional) --}}
                            <div id="event-field" style="display: none;">
                                <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event (Opsional)
                                </label>
                                <select name="event_id" id="event_id"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Event</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }} ({{ $event->start_datetime->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Period Start & End --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="period_start" class="block text-sm font-medium text-gray-700 mb-1">
                                        Periode Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="period_start" id="period_start"
                                        value="{{ old('period_start') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('period_start') border-red-500 @enderror">
                                    @error('period_start')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="period_end" class="block text-sm font-medium text-gray-700 mb-1">
                                        Periode Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="period_end" id="period_end" value="{{ old('period_end') }}"
                                        required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('period_end') border-red-500 @enderror">
                                    @error('period_end')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Report Date --}}
                            <div>
                                <label for="report_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Laporan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="report_date" id="report_date"
                                    value="{{ old('report_date', date('Y-m-d')) }}" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('report_date') border-red-500 @enderror">
                                @error('report_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Content Section --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Konten Summary</h2>

                        <div class="space-y-3">
                            {{-- Executive Overview --}}
                            <div>
                                <label for="executive_overview" class="block text-sm font-medium text-gray-700 mb-1">
                                    Executive Overview
                                </label>
                                <textarea name="executive_overview" id="executive_overview" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Ringkasan eksekutif secara keseluruhan...">{{ old('executive_overview') }}</textarea>
                            </div>

                            {{-- Key Highlights --}}
                            <div>
                                <label for="key_highlights" class="block text-sm font-medium text-gray-700 mb-1">
                                    Key Highlights
                                </label>
                                <textarea name="key_highlights" id="key_highlights" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Sorotan utama dari periode ini...">{{ old('key_highlights') }}</textarea>
                            </div>

                            {{-- Achievements --}}
                            <div>
                                <label for="achievements" class="block text-sm font-medium text-gray-700 mb-1">
                                    Achievements
                                </label>
                                <textarea name="achievements" id="achievements" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Pencapaian yang telah diraih...">{{ old('achievements') }}</textarea>
                            </div>

                            {{-- Challenges --}}
                            <div>
                                <label for="challenges" class="block text-sm font-medium text-gray-700 mb-1">
                                    Challenges
                                </label>
                                <textarea name="challenges" id="challenges" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Tantangan yang dihadapi...">{{ old('challenges') }}</textarea>
                            </div>

                            {{-- Recommendations --}}
                            <div>
                                <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-1">
                                    Recommendations
                                </label>
                                <textarea name="recommendations" id="recommendations" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Rekomendasi untuk periode selanjutnya...">{{ old('recommendations') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Metrics --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Metrik Keuangan</h2>

                        <div class="grid grid-cols-2 gap-3">
                            {{-- Total Income --}}
                            <div>
                                <label for="total_income" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Pendapatan
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                    <input type="number" name="total_income" id="total_income"
                                        value="{{ old('total_income') }}" step="0.01"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="0">
                                </div>
                            </div>

                            {{-- Total Expenses --}}
                            <div>
                                <label for="total_expenses" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Pengeluaran
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                    <input type="number" name="total_expenses" id="total_expenses"
                                        value="{{ old('total_expenses') }}" step="0.01"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Net Result (Auto-calculated):</span>
                                <span id="net-result" class="text-lg font-bold text-gray-900">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Event Metrics --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Metrik Event</h2>

                        <div class="grid grid-cols-3 gap-3">
                            {{-- Events Conducted --}}
                            <div>
                                <label for="events_conducted" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Event
                                </label>
                                <input type="number" name="events_conducted" id="events_conducted"
                                    value="{{ old('events_conducted') }}" min="0"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0">
                            </div>

                            {{-- Total Participants --}}
                            <div>
                                <label for="total_participants" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Partisipan
                                </label>
                                <input type="number" name="total_participants" id="total_participants"
                                    value="{{ old('total_participants') }}" min="0"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0">
                            </div>

                            {{-- Satisfaction Score --}}
                            <div>
                                <label for="satisfaction_score" class="block text-sm font-medium text-gray-700 mb-1">
                                    Satisfaction Score (1-5)
                                </label>
                                <input type="number" name="satisfaction_score" id="satisfaction_score"
                                    value="{{ old('satisfaction_score') }}" min="0" max="5"
                                    step="0.1"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0.0">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 space-y-4">
                    {{-- Submit Actions --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Aksi</h3>

                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 text-sm rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan sebagai Draft
                            </button>

                            <a href="{{ route('admin.reports.executive-summaries.index') }}"
                                class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 text-sm rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                        </div>
                    </div>

                    {{-- Supporting Documents --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Dokumen Pendukung</h3>

                        <div>
                            <label for="supporting_documents" class="block text-sm font-medium text-gray-700 mb-1">
                                Upload Dokumen
                            </label>
                            <input type="file" name="supporting_documents[]" id="supporting_documents" multiple
                                accept=".pdf,.doc,.docx,.xls,.xlsx"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Format: PDF, DOC, DOCX, XLS, XLSX. Max: 10MB per file</p>
                        </div>
                    </div>

                    {{-- Help --}}
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-bold text-blue-900 mb-1">Tips</h4>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>• Isi semua field yang wajib (*)</li>
                                    <li>• Data keuangan akan dihitung otomatis</li>
                                    <li>• Summary akan disimpan sebagai Draft</li>
                                    <li>• Anda bisa mengedit nanti</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Show/hide event field based on summary type
            document.getElementById('summary_type').addEventListener('change', function() {
                const eventField = document.getElementById('event-field');
                if (this.value === 'event') {
                    eventField.style.display = 'block';
                } else {
                    eventField.style.display = 'none';
                }
            });

            // Auto-calculate net result
            function calculateNetResult() {
                const income = parseFloat(document.getElementById('total_income').value) || 0;
                const expenses = parseFloat(document.getElementById('total_expenses').value) || 0;
                const netResult = income - expenses;

                const netResultElement = document.getElementById('net-result');
                netResultElement.textContent = 'Rp ' + netResult.toLocaleString('id-ID');

                if (netResult > 0) {
                    netResultElement.classList.remove('text-red-600');
                    netResultElement.classList.add('text-green-600');
                } else if (netResult < 0) {
                    netResultElement.classList.remove('text-green-600');
                    netResultElement.classList.add('text-red-600');
                } else {
                    netResultElement.classList.remove('text-green-600', 'text-red-600');
                    netResultElement.classList.add('text-gray-900');
                }
            }

            document.getElementById('total_income').addEventListener('input', calculateNetResult);
            document.getElementById('total_expenses').addEventListener('input', calculateNetResult);
        </script>
    @endpush
@endsection
