@extends('admin.layouts.app')

@section('title', 'Edit Progress Report')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Progress Report</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $progressReport->report_code }} - {{ $progressReport->title }}</p>
            </div>
            <a href="{{ route('admin.progress-reports.show', $progressReport) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($progressReport->status === 'rejected' && $progressReport->reviewer_feedback)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-red-900">Feedback dari Reviewer</h4>
                        <p class="mt-1 text-sm text-red-700">{{ $progressReport->reviewer_feedback }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.progress-reports.update', $progressReport) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Event --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Event <span class="text-red-500">*</span>
                            </label>
                            <select name="event_id" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('event_id') border-red-500 @enderror">
                                <option value="">Pilih Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}"
                                        {{ old('event_id', $progressReport->event_id) == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Structure --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Struktur Kepanitiaan
                            </label>
                            <select name="structure_id"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('structure_id') border-red-500 @enderror">
                                <option value="">Pilih Struktur (Opsional)</option>
                                @foreach ($structures as $structure)
                                    <option value="{{ $structure->id }}"
                                        {{ old('structure_id', $progressReport->structure_id) == $structure->id ? 'selected' : '' }}>
                                        {{ $structure->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('structure_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Timeline --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Project Timeline
                            </label>
                            <select name="timeline_id"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('timeline_id') border-red-500 @enderror">
                                <option value="">Pilih Timeline (Opsional)</option>
                                @foreach ($timelines as $timeline)
                                    <option value="{{ $timeline->id }}"
                                        {{ old('timeline_id', $progressReport->timeline_id) == $timeline->id ? 'selected' : '' }}>
                                        {{ $timeline->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timeline_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Report Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Report <span class="text-red-500">*</span>
                            </label>
                            <select name="report_type" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('report_type') border-red-500 @enderror">
                                <option value="">Pilih Tipe</option>
                                <option value="daily"
                                    {{ old('report_type', $progressReport->report_type) == 'daily' ? 'selected' : '' }}>
                                    Daily
                                    Report</option>
                                <option value="weekly"
                                    {{ old('report_type', $progressReport->report_type) == 'weekly' ? 'selected' : '' }}>
                                    Weekly Report</option>
                                <option value="monthly"
                                    {{ old('report_type', $progressReport->report_type) == 'monthly' ? 'selected' : '' }}>
                                    Monthly Report</option>
                                <option value="milestone"
                                    {{ old('report_type', $progressReport->report_type) == 'milestone' ? 'selected' : '' }}>
                                    Milestone Report</option>
                                <option value="ad_hoc"
                                    {{ old('report_type', $progressReport->report_type) == 'ad_hoc' ? 'selected' : '' }}>Ad
                                    Hoc Report</option>
                            </select>
                            @error('report_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Report <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $progressReport->title) }}" required
                            placeholder="Contoh: Progress Report Persiapan Acara Minggu Ke-2"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Period Start --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Periode Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="period_start"
                                value="{{ old('period_start', $progressReport->period_start->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('period_start') border-red-500 @enderror">
                            @error('period_start')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Period End --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Periode Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="period_end"
                                value="{{ old('period_end', $progressReport->period_end->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('period_end') border-red-500 @enderror">
                            @error('period_end')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Report Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Report <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="report_date"
                                value="{{ old('report_date', $progressReport->report_date->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('report_date') border-red-500 @enderror">
                            @error('report_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Report Content --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Konten Report</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Executive Summary --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ringkasan Eksekutif
                        </label>
                        <textarea name="executive_summary" rows="3"
                            placeholder="Ringkasan singkat mengenai progress secara keseluruhan..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('executive_summary') border-red-500 @enderror">{{ old('executive_summary', $progressReport->executive_summary) }}</textarea>
                        @error('executive_summary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Activities Completed --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Aktivitas yang Telah Diselesaikan
                        </label>
                        <textarea name="activities_completed" rows="4" placeholder="Daftar aktivitas yang sudah selesai dikerjakan..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('activities_completed') border-red-500 @enderror">{{ old('activities_completed', $progressReport->activities_completed) }}</textarea>
                        @error('activities_completed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ongoing Activities --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Aktivitas yang Sedang Berjalan
                        </label>
                        <textarea name="ongoing_activities" rows="4"
                            placeholder="Daftar aktivitas yang masih dalam proses pengerjaan..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('ongoing_activities') border-red-500 @enderror">{{ old('ongoing_activities', $progressReport->ongoing_activities) }}</textarea>
                        @error('ongoing_activities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Planned Activities --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Aktivitas yang Direncanakan
                        </label>
                        <textarea name="planned_activities" rows="4" placeholder="Daftar aktivitas yang akan dikerjakan selanjutnya..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('planned_activities') border-red-500 @enderror">{{ old('planned_activities', $progressReport->planned_activities) }}</textarea>
                        @error('planned_activities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Issues & Challenges --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kendala & Tantangan
                        </label>
                        <textarea name="issues_challenges" rows="4"
                            placeholder="Masalah atau tantangan yang dihadapi selama periode ini..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('issues_challenges') border-red-500 @enderror">{{ old('issues_challenges', $progressReport->issues_challenges) }}</textarea>
                        @error('issues_challenges')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Solutions & Recommendations --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Solusi & Rekomendasi
                        </label>
                        <textarea name="solutions_recommendations" rows="4"
                            placeholder="Solusi yang diterapkan dan rekomendasi untuk kedepannya..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('solutions_recommendations') border-red-500 @enderror">{{ old('solutions_recommendations', $progressReport->solutions_recommendations) }}</textarea>
                        @error('solutions_recommendations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Metrics --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Metrik & Statistik</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Overall Progress --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Progress Keseluruhan (%) <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="range" name="overall_progress" min="0" max="100"
                                value="{{ old('overall_progress', $progressReport->overall_progress) }}" required
                                x-data="{ value: {{ old('overall_progress', $progressReport->overall_progress) }} }" x-model="value"
                                class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span x-text="value + '%'" class="text-lg font-semibold text-primary w-12 text-right"></span>
                        </div>
                        @error('overall_progress')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Tasks Planned --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Task Direncanakan
                            </label>
                            <input type="number" name="tasks_planned"
                                value="{{ old('tasks_planned', $progressReport->tasks_planned) }}" min="0"
                                placeholder="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('tasks_planned') border-red-500 @enderror">
                            @error('tasks_planned')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tasks Completed --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Task Selesai
                            </label>
                            <input type="number" name="tasks_completed"
                                value="{{ old('tasks_completed', $progressReport->tasks_completed) }}" min="0"
                                placeholder="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('tasks_completed') border-red-500 @enderror">
                            @error('tasks_completed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tasks Delayed --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Task Tertunda
                            </label>
                            <input type="number" name="tasks_delayed"
                                value="{{ old('tasks_delayed', $progressReport->tasks_delayed) }}" min="0"
                                placeholder="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('tasks_delayed') border-red-500 @enderror">
                            @error('tasks_delayed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Budget Allocated --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Budget Dialokasikan (Rp)
                            </label>
                            <input type="number" name="budget_allocated"
                                value="{{ old('budget_allocated', $progressReport->budget_allocated) }}" min="0"
                                step="0.01" placeholder="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('budget_allocated') border-red-500 @enderror">
                            @error('budget_allocated')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Budget Used --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Budget Terpakai (Rp)
                            </label>
                            <input type="number" name="budget_used"
                                value="{{ old('budget_used', $progressReport->budget_used) }}" min="0"
                                step="0.01" placeholder="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('budget_used') border-red-500 @enderror">
                            @error('budget_used')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Team Members --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Anggota Tim Terlibat
                            </label>
                            <input type="number" name="team_members_involved"
                                value="{{ old('team_members_involved', $progressReport->team_members_involved) }}"
                                min="0" placeholder="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('team_members_involved') border-red-500 @enderror">
                            @error('team_members_involved')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Hours Spent --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Total Jam Kerja
                            </label>
                            <input type="number" name="hours_spent"
                                value="{{ old('hours_spent', $progressReport->hours_spent) }}" min="0"
                                placeholder="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('hours_spent') border-red-500 @enderror">
                            @error('hours_spent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attachments --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Lampiran</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Existing Attachments --}}
                    @if ($progressReport->attachments && count($progressReport->attachments) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                File yang Sudah Di-upload
                            </label>
                            <div class="space-y-2">
                                @foreach ($progressReport->attachments as $attachment)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $attachment['name'] ?? 'File' }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ number_format(($attachment['size'] ?? 0) / 1024, 2) }} KB</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                                            class="text-primary hover:text-primary-dark text-sm font-medium">
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- New Attachments --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tambah Dokumen Baru
                        </label>
                        <input type="file" name="attachments[]" multiple
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer @error('attachments.*') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-gray-500">
                            Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Maksimal 10MB per file.
                        </p>
                        @error('attachments.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <a href="{{ route('admin.progress-reports.show', $progressReport) }}"
                    class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium shadow-sm">
                    Update Report
                </button>
            </div>
        </form>
    </div>
@endsection
