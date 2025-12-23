{{-- Executive Summary Tab --}}
<div x-show="activeTab === 'executive'" x-transition class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Ringkasan Eksekutif</label>
        <textarea name="executive_summary" rows="8"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('executive_summary') border-red-500 @enderror"
            placeholder="Tuliskan ringkasan eksekutif yang mencakup poin-poin penting dari laporan...">{{ old('executive_summary', $finalEventReport->executive_summary ?? '') }}</textarea>
        @error('executive_summary')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Ringkasan singkat yang mencakup tujuan, hasil utama, dan kesimpulan acara.
        </p>
    </div>
</div>

{{-- Event Overview Tab --}}
<div x-show="activeTab === 'overview'" x-transition class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gambaran Umum Acara</label>
        <textarea name="event_overview" rows="8"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('event_overview') border-red-500 @enderror"
            placeholder="Deskripsi lengkap mengenai acara, latar belakang, dan konteks pelaksanaan...">{{ old('event_overview', $finalEventReport->event_overview ?? '') }}</textarea>
        @error('event_overview')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Analisis Peserta</label>
        <textarea name="participant_analysis" rows="6"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('participant_analysis') border-red-500 @enderror"
            placeholder="Analisis demografis peserta, tingkat partisipasi, dan karakteristik peserta...">{{ old('participant_analysis', $finalEventReport->participant_analysis ?? '') }}</textarea>
        @error('participant_analysis')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Objectives Achievement Tab --}}
<div x-show="activeTab === 'objectives'" x-transition class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pencapaian Tujuan</label>
        <textarea name="objectives_achievement" rows="8"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('objectives_achievement') border-red-500 @enderror"
            placeholder="Evaluasi pencapaian setiap tujuan yang telah ditetapkan sebelumnya...">{{ old('objectives_achievement', $finalEventReport->objectives_achievement ?? '') }}</textarea>
        @error('objectives_achievement')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Overall Satisfaction --}}
        <div>
            <label for="overall_satisfaction" class="block text-sm font-medium text-gray-700 mb-1.5">
                Kepuasan Keseluruhan (0-5)
            </label>
            <input type="number" name="overall_satisfaction" id="overall_satisfaction" step="0.01" min="0"
                max="5" value="{{ old('overall_satisfaction', $finalEventReport->overall_satisfaction ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="4.5">
        </div>

        {{-- Content Rating --}}
        <div>
            <label for="content_rating" class="block text-sm font-medium text-gray-700 mb-1.5">
                Rating Konten (0-5)
            </label>
            <input type="number" name="content_rating" id="content_rating" step="0.01" min="0" max="5"
                value="{{ old('content_rating', $finalEventReport->content_rating ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="4.3">
        </div>

        {{-- Organization Rating --}}
        <div>
            <label for="organization_rating" class="block text-sm font-medium text-gray-700 mb-1.5">
                Rating Organisasi (0-5)
            </label>
            <input type="number" name="organization_rating" id="organization_rating" step="0.01" min="0"
                max="5" value="{{ old('organization_rating', $finalEventReport->organization_rating ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="4.7">
        </div>

        {{-- Venue Rating --}}
        <div>
            <label for="venue_rating" class="block text-sm font-medium text-gray-700 mb-1.5">
                Rating Venue (0-5)
            </label>
            <input type="number" name="venue_rating" id="venue_rating" step="0.01" min="0" max="5"
                value="{{ old('venue_rating', $finalEventReport->venue_rating ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="4.2">
        </div>
    </div>
</div>

{{-- Implementation Process Tab --}}
<div x-show="activeTab === 'implementation'" x-transition class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Proses Pelaksanaan</label>
        <textarea name="implementation_process" rows="8"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('implementation_process') border-red-500 @enderror"
            placeholder="Deskripsi detail mengenai tahapan pelaksanaan acara dari persiapan hingga penutupan...">{{ old('implementation_process', $finalEventReport->implementation_process ?? '') }}</textarea>
        @error('implementation_process')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Committee Members --}}
        <div>
            <label for="committee_members" class="block text-sm font-medium text-gray-700 mb-1.5">
                Jumlah Panitia
            </label>
            <input type="number" name="committee_members" id="committee_members" min="0"
                value="{{ old('committee_members', $finalEventReport->committee_members ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="25">
        </div>

        {{-- Team Performance Score --}}
        <div>
            <label for="team_performance_score" class="block text-sm font-medium text-gray-700 mb-1.5">
                Skor Kinerja Tim (0-5)
            </label>
            <input type="number" name="team_performance_score" id="team_performance_score" step="0.01"
                min="0" max="5"
                value="{{ old('team_performance_score', $finalEventReport->team_performance_score ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="4.5">
        </div>
    </div>
</div>

{{-- Statistics Tab --}}
<div x-show="activeTab === 'statistics'" x-transition class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Total Participants --}}
        <div>
            <label for="total_participants" class="block text-sm font-medium text-gray-700 mb-1.5">
                Total Peserta
            </label>
            <input type="number" name="total_participants" id="total_participants" min="0"
                value="{{ old('total_participants', $finalEventReport->total_participants ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="500">
        </div>

        {{-- Registered Participants --}}
        <div>
            <label for="registered_participants" class="block text-sm font-medium text-gray-700 mb-1.5">
                Peserta Terdaftar
            </label>
            <input type="number" name="registered_participants" id="registered_participants" min="0"
                value="{{ old('registered_participants', $finalEventReport->registered_participants ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="600">
        </div>

        {{-- Attended Participants --}}
        <div>
            <label for="attended_participants" class="block text-sm font-medium text-gray-700 mb-1.5">
                Peserta Hadir
            </label>
            <input type="number" name="attended_participants" id="attended_participants" min="0"
                value="{{ old('attended_participants', $finalEventReport->attended_participants ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="450">
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">Informasi</p>
                <p class="text-sm text-blue-700 mt-1">Tingkat kehadiran akan dihitung otomatis berdasarkan peserta
                    terdaftar dan peserta hadir.</p>
            </div>
        </div>
    </div>
</div>

{{-- Financial Tab --}}
<div x-show="activeTab === 'financial'" x-transition class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Laporan Keuangan</label>
        <textarea name="financial_report" rows="6"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('financial_report') border-red-500 @enderror"
            placeholder="Deskripsi lengkap mengenai pengelolaan keuangan acara...">{{ old('financial_report', $finalEventReport->financial_report ?? '') }}</textarea>
        @error('financial_report')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Budget --}}
        <div>
            <label for="total_budget" class="block text-sm font-medium text-gray-700 mb-1.5">
                Total Anggaran (Rp)
            </label>
            <input type="number" name="total_budget" id="total_budget" step="0.01" min="0"
                value="{{ old('total_budget', $finalEventReport->total_budget ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="50000000">
        </div>

        {{-- Total Income --}}
        <div>
            <label for="total_income" class="block text-sm font-medium text-gray-700 mb-1.5">
                Total Pemasukan (Rp)
            </label>
            <input type="number" name="total_income" id="total_income" step="0.01" min="0"
                value="{{ old('total_income', $finalEventReport->total_income ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="45000000">
        </div>

        {{-- Total Expenses --}}
        <div>
            <label for="total_expenses" class="block text-sm font-medium text-gray-700 mb-1.5">
                Total Pengeluaran (Rp)
            </label>
            <input type="number" name="total_expenses" id="total_expenses" step="0.01" min="0"
                value="{{ old('total_expenses', $finalEventReport->total_expenses ?? '') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                placeholder="43000000">
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">Informasi</p>
                <p class="text-sm text-blue-700 mt-1">Surplus/Defisit akan dihitung otomatis berdasarkan total
                    pemasukan dan pengeluaran.</p>
            </div>
        </div>
    </div>
</div>

{{-- Challenges & Solutions Tab --}}
<div x-show="activeTab === 'challenges'" x-transition class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tantangan & Solusi</label>
        <textarea name="challenges_solutions" rows="8"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('challenges_solutions') border-red-500 @enderror"
            placeholder="Uraikan tantangan yang dihadapi dan solusi yang diterapkan...">{{ old('challenges_solutions', $finalEventReport->challenges_solutions ?? '') }}</textarea>
        @error('challenges_solutions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pelajaran yang Dipetik</label>
        <textarea name="lessons_learned" rows="6"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('lessons_learned') border-red-500 @enderror"
            placeholder="Pelajaran berharga yang dapat diambil dari pelaksanaan acara ini...">{{ old('lessons_learned', $finalEventReport->lessons_learned ?? '') }}</textarea>
        @error('lessons_learned')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Rekomendasi</label>
        <textarea name="recommendations" rows="6"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('recommendations') border-red-500 @enderror"
            placeholder="Rekomendasi untuk acara serupa di masa mendatang...">{{ old('recommendations', $finalEventReport->recommendations ?? '') }}</textarea>
        @error('recommendations')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror>
    </div>
</div>

{{-- Conclusion Tab --}}
<div x-show="activeTab === 'conclusion'" x-transition class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kesimpulan</label>
        <textarea name="conclusion" rows="8"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('conclusion') border-red-500 @enderror"
            placeholder="Kesimpulan akhir dari pelaksanaan acara...">{{ old('conclusion', $finalEventReport->conclusion ?? '') }}</textarea>
        @error('conclusion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-green-900">Tips</p>
                <p class="text-sm text-green-700 mt-1">Pastikan semua bagian laporan telah terisi dengan lengkap
                    sebelum
                    mengajukan untuk ditinjau.</p>
            </div>
        </div>
    </div>
</div>
