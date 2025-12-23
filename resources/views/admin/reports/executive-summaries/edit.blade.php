@extends('admin.layouts.app')

@section('title', 'Edit Executive Summary')

@section('content')
    <div class="px-6 py-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.reports.executive-summaries.show', $executiveSummary) }}"
                        class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Executive Summary</h1>
                </div>
                <p class="text-gray-600">Edit laporan ringkasan eksekutif</p>
            </div>
            <div class="bg-blue-50 px-3 py-2 text-sm rounded-lg border border-blue-200">
                <span class="text-sm text-gray-600">Kode Summary:</span>
                <span class="text-sm font-mono font-bold text-blue-600 ml-2">{{ $executiveSummary->summary_code }}</span>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.reports.executive-summaries.update', $executiveSummary) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                {{-- Main Form --}}
                <div class="lg:col-span-2 space-y-4">
                    {{-- Basic Information --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Informasi Dasar</h2>

                        <div class="space-y-3">
                            {{-- Title --}}
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Judul <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title"
                                    value="{{ old('title', $executiveSummary->title) }}" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
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
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="monthly"
                                        {{ old('summary_type', $executiveSummary->summary_type) == 'monthly' ? 'selected' : '' }}>
                                        Monthly - Bulanan</option>
                                    <option value="quarterly"
                                        {{ old('summary_type', $executiveSummary->summary_type) == 'quarterly' ? 'selected' : '' }}>
                                        Quarterly - Kuartalan</option>
                                    <option value="event"
                                        {{ old('summary_type', $executiveSummary->summary_type) == 'event' ? 'selected' : '' }}>
                                        Event - Berdasarkan Event</option>
                                    <option value="annual"
                                        {{ old('summary_type', $executiveSummary->summary_type) == 'annual' ? 'selected' : '' }}>
                                        Annual - Tahunan</option>
                                </select>
                            </div>

                            {{-- Event --}}
                            <div id="event-field"
                                style="{{ old('summary_type', $executiveSummary->summary_type) == 'event' ? '' : 'display: none;' }}">
                                <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event (Opsional)
                                </label>
                                <select name="event_id" id="event_id"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Event</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id', $executiveSummary->event_id) == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }} ({{ $event->start_datetime->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Period --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="period_start" class="block text-sm font-medium text-gray-700 mb-1">
                                        Periode Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="period_start" id="period_start"
                                        value="{{ old('period_start', $executiveSummary->period_start->format('Y-m-d')) }}"
                                        required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="period_end" class="block text-sm font-medium text-gray-700 mb-1">
                                        Periode Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="period_end" id="period_end"
                                        value="{{ old('period_end', $executiveSummary->period_end->format('Y-m-d')) }}"
                                        required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            {{-- Report Date --}}
                            <div>
                                <label for="report_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Laporan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="report_date" id="report_date"
                                    value="{{ old('report_date', $executiveSummary->report_date->format('Y-m-d')) }}"
                                    required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    {{-- Content Section --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Konten Summary</h2>

                        <div class="space-y-3">
                            <div>
                                <label for="executive_overview" class="block text-sm font-medium text-gray-700 mb-1">
                                    Executive Overview
                                </label>
                                <textarea name="executive_overview" id="executive_overview" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('executive_overview', $executiveSummary->executive_overview) }}</textarea>
                            </div>

                            <div>
                                <label for="key_highlights" class="block text-sm font-medium text-gray-700 mb-1">
                                    Key Highlights
                                </label>
                                <textarea name="key_highlights" id="key_highlights" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('key_highlights', $executiveSummary->key_highlights) }}</textarea>
                            </div>

                            <div>
                                <label for="achievements" class="block text-sm font-medium text-gray-700 mb-1">
                                    Achievements
                                </label>
                                <textarea name="achievements" id="achievements" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('achievements', $executiveSummary->achievements) }}</textarea>
                            </div>

                            <div>
                                <label for="challenges" class="block text-sm font-medium text-gray-700 mb-1">
                                    Challenges
                                </label>
                                <textarea name="challenges" id="challenges" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('challenges', $executiveSummary->challenges) }}</textarea>
                            </div>

                            <div>
                                <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-1">
                                    Recommendations
                                </label>
                                <textarea name="recommendations" id="recommendations" rows="4"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('recommendations', $executiveSummary->recommendations) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Metrics --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Metrik Keuangan</h2>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="total_income" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Pendapatan
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                    <input type="number" name="total_income" id="total_income"
                                        value="{{ old('total_income', $executiveSummary->total_income) }}" step="0.01"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <div>
                                <label for="total_expenses" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Pengeluaran
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                    <input type="number" name="total_expenses" id="total_expenses"
                                        value="{{ old('total_expenses', $executiveSummary->total_expenses) }}"
                                        step="0.01"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Net Result:</span>
                                <span id="net-result"
                                    class="text-lg font-bold {{ $executiveSummary->is_profitable ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($executiveSummary->net_result ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Event Metrics --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Metrik Event</h2>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="events_conducted" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Event
                                </label>
                                <input type="number" name="events_conducted" id="events_conducted"
                                    value="{{ old('events_conducted', $executiveSummary->events_conducted) }}"
                                    min="0"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="total_participants" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Partisipan
                                </label>
                                <input type="number" name="total_participants" id="total_participants"
                                    value="{{ old('total_participants', $executiveSummary->total_participants) }}"
                                    min="0"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="satisfaction_score" class="block text-sm font-medium text-gray-700 mb-1">
                                    Satisfaction Score (1-5)
                                </label>
                                <input type="number" name="satisfaction_score" id="satisfaction_score"
                                    value="{{ old('satisfaction_score', $executiveSummary->satisfaction_score) }}"
                                    min="0" max="5" step="0.1"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                                Update Summary
                            </button>

                            <a href="{{ route('admin.reports.executive-summaries.show', $executiveSummary) }}"
                                class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 text-sm rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                        </div>
                    </div>

                    {{-- Status Info --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Status</h3>

                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">Current Status:</span>
                                <span
                                    class="block mt-1 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                {{ $executiveSummary->status == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $executiveSummary->status == 'under_review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $executiveSummary->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $executiveSummary->status == 'published' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ $executiveSummary->status_label }}
                                </span>
                            </div>

                            @if ($executiveSummary->reviewedBy)
                                <div class="text-xs text-gray-600">
                                    Ditinjau oleh: {{ $executiveSummary->reviewedBy->name }}<br>
                                    {{ $executiveSummary->reviewed_at->diffForHumans() }}
                                </div>
                            @endif

                            @if ($executiveSummary->approvedBy)
                                <div class="text-xs text-gray-600">
                                    Disetujui oleh: {{ $executiveSummary->approvedBy->name }}<br>
                                    {{ $executiveSummary->approved_at->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Supporting Documents --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Dokumen Pendukung</h3>

                        @if ($executiveSummary->supporting_documents)
                            <div class="space-y-2 mb-3">
                                @foreach ($executiveSummary->supporting_documents as $doc)
                                    <div
                                        class="flex items-center justify-between p-2 bg-gray-50 rounded border border-gray-200">
                                        <span class="text-xs text-gray-600 truncate flex-1">{{ basename($doc) }}</span>
                                        <form
                                            action="{{ route('admin.reports.executive-summaries.delete-document', $executiveSummary) }}"
                                            method="POST" onsubmit="return confirm('Hapus dokumen ini?')"
                                            class="ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="document_path" value="{{ $doc }}">
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div>
                            <label for="supporting_documents" class="block text-sm font-medium text-gray-700 mb-1">
                                Upload Dokumen Baru
                            </label>
                            <input type="file" name="supporting_documents[]" id="supporting_documents" multiple
                                accept=".pdf,.doc,.docx,.xls,.xlsx"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Max: 10MB per file</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Show/hide event field
            document.getElementById('summary_type').addEventListener('change', function() {
                const eventField = document.getElementById('event-field');
                eventField.style.display = this.value === 'event' ? 'block' : 'none';
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
                }
            }

            document.getElementById('total_income').addEventListener('input', calculateNetResult);
            document.getElementById('total_expenses').addEventListener('input', calculateNetResult);
        </script>
    @endpush
@endsection
