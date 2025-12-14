@extends('admin.layouts.app')

@section('title', 'Buat Evaluasi Kinerja')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Evaluasi Kinerja</h1>
                <p class="text-gray-600 mt-1">Isi form evaluasi kinerja anggota panitia</p>
            </div>
            <a href="{{ route('admin.evaluations.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.evaluations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kode Evaluasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="evaluation_code"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 @error('evaluation_code') border-red-500 @enderror"
                                        value="{{ old('evaluation_code', $evaluationCode) }}" readonly>
                                    @error('evaluation_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        User yang Dievaluasi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="user_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('user_id') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} - {{ $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Struktur/Posisi</label>
                                    <select name="structure_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('structure_id') border-red-500 @enderror">
                                        <option value="">Pilih Posisi (Opsional)</option>
                                        @foreach ($structures as $structure)
                                            <option value="{{ $structure->id }}"
                                                {{ old('structure_id') == $structure->id ? 'selected' : '' }}>
                                                {{ $structure->position_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('structure_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                                    <select name="event_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('event_id') border-red-500 @enderror">
                                        <option value="">Pilih Event (Opsional)</option>
                                        @foreach ($events as $event)
                                            <option value="{{ $event->id }}"
                                                {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipe Periode <span class="text-red-500">*</span>
                                    </label>
                                    <select name="period_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('period_type') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="weekly" {{ old('period_type') == 'weekly' ? 'selected' : '' }}>
                                            Mingguan</option>
                                        <option value="monthly" {{ old('period_type') == 'monthly' ? 'selected' : '' }}>
                                            Bulanan</option>
                                        <option value="quarterly"
                                            {{ old('period_type') == 'quarterly' ? 'selected' : '' }}>Kuartalan</option>
                                        <option value="semester" {{ old('period_type') == 'semester' ? 'selected' : '' }}>
                                            Semester</option>
                                        <option value="annual" {{ old('period_type') == 'annual' ? 'selected' : '' }}>
                                            Tahunan</option>
                                        <option value="event_based"
                                            {{ old('period_type') == 'event_based' ? 'selected' : '' }}>Event Based
                                        </option>
                                    </select>
                                    @error('period_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="period_start"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('period_start') border-red-500 @enderror"
                                        value="{{ old('period_start') }}" required>
                                    @error('period_start')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="period_end"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('period_end') border-red-500 @enderror"
                                        value="{{ old('period_end') }}" required>
                                    @error('period_end')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scores -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Penilaian Kinerja</h3>
                            <p class="text-sm text-gray-600 mt-1">Berikan nilai 0-5 untuk setiap aspek</p>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Penyelesaian Tugas</label>
                                    <input type="number" name="task_completion_score"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('task_completion_score') border-red-500 @enderror"
                                        value="{{ old('task_completion_score') }}" min="0" max="5"
                                        step="0.01" placeholder="0.00">
                                    @error('task_completion_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kualitas Pekerjaan</label>
                                    <input type="number" name="quality_score"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('quality_score') border-red-500 @enderror"
                                        value="{{ old('quality_score') }}" min="0" max="5" step="0.01"
                                        placeholder="0.00">
                                    @error('quality_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kerjasama Tim</label>
                                    <input type="number" name="teamwork_score"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('teamwork_score') border-red-500 @enderror"
                                        value="{{ old('teamwork_score') }}" min="0" max="5"
                                        step="0.01" placeholder="0.00">
                                    @error('teamwork_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Inisiatif</label>
                                    <input type="number" name="initiative_score"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('initiative_score') border-red-500 @enderror"
                                        value="{{ old('initiative_score') }}" min="0" max="5"
                                        step="0.01" placeholder="0.00">
                                    @error('initiative_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kepemimpinan</label>
                                    <input type="number" name="leadership_score"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('leadership_score') border-red-500 @enderror"
                                        value="{{ old('leadership_score') }}" min="0" max="5"
                                        step="0.01" placeholder="0.00">
                                    @error('leadership_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Disiplin</label>
                                    <input type="number" name="discipline_score"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('discipline_score') border-red-500 @enderror"
                                        value="{{ old('discipline_score') }}" min="0" max="5"
                                        step="0.01" placeholder="0.00">
                                    @error('discipline_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Details -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Detail Kinerja</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kekuatan (Strengths)</label>
                                <textarea name="strengths" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('strengths') border-red-500 @enderror"
                                    placeholder="Tuliskan kekuatan yang dimiliki...">{{ old('strengths') }}</textarea>
                                @error('strengths')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kelemahan (Weaknesses)</label>
                                <textarea name="weaknesses" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('weaknesses') border-red-500 @enderror"
                                    placeholder="Tuliskan kelemahan yang perlu diperbaiki...">{{ old('weaknesses') }}</textarea>
                                @error('weaknesses')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pencapaian
                                    (Achievements)</label>
                                <textarea name="achievements" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('achievements') border-red-500 @enderror"
                                    placeholder="Tuliskan pencapaian yang telah diraih...">{{ old('achievements') }}</textarea>
                                @error('achievements')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Area yang Perlu
                                    Ditingkatkan</label>
                                <textarea name="improvement_areas" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('improvement_areas') border-red-500 @enderror"
                                    placeholder="Tuliskan area yang perlu ditingkatkan...">{{ old('improvement_areas') }}</textarea>
                                @error('improvement_areas')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
                                <textarea name="recommendations" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('recommendations') border-red-500 @enderror"
                                    placeholder="Tuliskan rekomendasi untuk pengembangan...">{{ old('recommendations') }}</textarea>
                                @error('recommendations')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Komentar Evaluator</label>
                                <textarea name="evaluator_comments" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('evaluator_comments') border-red-500 @enderror"
                                    placeholder="Tambahkan komentar evaluator...">{{ old('evaluator_comments') }}</textarea>
                                @error('evaluator_comments')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Statistics -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Statistik</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tugas Diselesaikan</label>
                                    <input type="number" name="tasks_completed"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('tasks_completed') border-red-500 @enderror"
                                        value="{{ old('tasks_completed', 0) }}" min="0">
                                    @error('tasks_completed')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Tugas</label>
                                    <input type="number" name="tasks_assigned"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('tasks_assigned') border-red-500 @enderror"
                                        value="{{ old('tasks_assigned', 0) }}" min="0">
                                    @error('tasks_assigned')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hari Hadir</label>
                                    <input type="number" name="attendance_days"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('attendance_days') border-red-500 @enderror"
                                        value="{{ old('attendance_days', 0) }}" min="0">
                                    @error('attendance_days')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Hari</label>
                                    <input type="number" name="total_days"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('total_days') border-red-500 @enderror"
                                        value="{{ old('total_days', 0) }}" min="0">
                                    @error('total_days')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-3">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Evaluasi
                        </button>
                        <a href="{{ route('admin.evaluations.index') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
