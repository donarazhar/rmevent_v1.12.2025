<div class="space-y-6">
    {{-- Basic Information --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Title --}}
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Proposal <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $proposal->title ?? '') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type --}}
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipe Proposal <span class="text-red-500">*</span>
                </label>
                <select name="type" id="type" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('type') border-red-500 @enderror">
                    <option value="">Pilih Tipe</option>
                    <option value="event" {{ old('type', $proposal->type ?? '') == 'event' ? 'selected' : '' }}>Event
                    </option>
                    <option value="sponsorship"
                        {{ old('type', $proposal->type ?? '') == 'sponsorship' ? 'selected' : '' }}>Sponsorship</option>
                    <option value="partnership"
                        {{ old('type', $proposal->type ?? '') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                    <option value="funding" {{ old('type', $proposal->type ?? '') == 'funding' ? 'selected' : '' }}>
                        Funding</option>
                    <option value="project" {{ old('type', $proposal->type ?? '') == 'project' ? 'selected' : '' }}>
                        Project</option>
                    <option value="other" {{ old('type', $proposal->type ?? '') == 'other' ? 'selected' : '' }}>Other
                    </option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Event --}}
            <div>
                <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Event (Opsional)
                </label>
                <select name="event_id" id="event_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('event_id') border-red-500 @enderror">
                    <option value="">Tidak Terkait Event</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}"
                            {{ old('event_id', $proposal->event_id ?? '') == $event->id ? 'selected' : '' }}>
                            {{ $event->title }}
                        </option>
                    @endforeach
                </select>
                @error('event_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Structure --}}
            <div>
                <label for="structure_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Struktur Kepanitiaan (Opsional)
                </label>
                <select name="structure_id" id="structure_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('structure_id') border-red-500 @enderror">
                    <option value="">Tidak Terkait Struktur</option>
                    @foreach ($structures as $structure)
                        <option value="{{ $structure->id }}"
                            {{ old('structure_id', $proposal->structure_id ?? '') == $structure->id ? 'selected' : '' }}>
                            {{ $structure->name }}
                        </option>
                    @endforeach
                </select>
                @error('structure_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Singkat
                </label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $proposal->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Recipient Information --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penerima</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Submitted To --}}
            <div>
                <label for="submitted_to" class="block text-sm font-medium text-gray-700 mb-2">
                    Diajukan Kepada
                </label>
                <input type="text" name="submitted_to" id="submitted_to"
                    value="{{ old('submitted_to', $proposal->submitted_to ?? '') }}"
                    placeholder="Nama Organisasi/Perusahaan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('submitted_to') border-red-500 @enderror">
                @error('submitted_to')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Contact Person --}}
            <div>
                <label for="recipient_contact" class="block text-sm font-medium text-gray-700 mb-2">
                    Kontak Person
                </label>
                <input type="text" name="recipient_contact" id="recipient_contact"
                    value="{{ old('recipient_contact', $proposal->recipient_contact ?? '') }}"
                    placeholder="Nama/No. Telepon"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('recipient_contact') border-red-500 @enderror">
                @error('recipient_contact')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="recipient_email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Penerima
                </label>
                <input type="email" name="recipient_email" id="recipient_email"
                    value="{{ old('recipient_email', $proposal->recipient_email ?? '') }}"
                    placeholder="email@example.com"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('recipient_email') border-red-500 @enderror">
                @error('recipient_email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Financial Information --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Keuangan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Requested Amount --}}
            <div>
                <label for="requested_amount" class="block text-sm font-medium text-gray-700 mb-2">
                    Dana yang Diminta (Rp)
                </label>
                <input type="number" name="requested_amount" id="requested_amount" min="0" step="0.01"
                    value="{{ old('requested_amount', $proposal->requested_amount ?? '') }}" placeholder="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('requested_amount') border-red-500 @enderror">
                @error('requested_amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Response Deadline --}}
            <div>
                <label for="response_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                    Batas Waktu Respon
                </label>
                <input type="date" name="response_deadline" id="response_deadline"
                    value="{{ old('response_deadline', isset($proposal) && $proposal->response_deadline ? $proposal->response_deadline->format('Y-m-d') : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('response_deadline') border-red-500 @enderror">
                @error('response_deadline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Budget Overview --}}
            <div class="md:col-span-2">
                <label for="budget_overview" class="block text-sm font-medium text-gray-700 mb-2">
                    Overview Budget
                </label>
                <textarea name="budget_overview" id="budget_overview" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('budget_overview') border-red-500 @enderror"
                    placeholder="Rincian budget dan alokasi dana">{{ old('budget_overview', $proposal->budget_overview ?? '') }}</textarea>
                @error('budget_overview')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Proposal Content --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konten Proposal</h3>
        <div class="space-y-6">
            {{-- Executive Summary --}}
            <div>
                <label for="executive_summary" class="block text-sm font-medium text-gray-700 mb-2">
                    Ringkasan Eksekutif
                </label>
                <textarea name="executive_summary" id="executive_summary" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('executive_summary') border-red-500 @enderror"
                    placeholder="Ringkasan singkat dan padat dari keseluruhan proposal">{{ old('executive_summary', $proposal->executive_summary ?? '') }}</textarea>
                @error('executive_summary')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Background --}}
            <div>
                <label for="background" class="block text-sm font-medium text-gray-700 mb-2">
                    Latar Belakang
                </label>
                <textarea name="background" id="background" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('background') border-red-500 @enderror"
                    placeholder="Uraian latar belakang proposal">{{ old('background', $proposal->background ?? '') }}</textarea>
                @error('background')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Objectives --}}
            <div>
                <label for="objectives" class="block text-sm font-medium text-gray-700 mb-2">
                    Tujuan
                </label>
                <textarea name="objectives" id="objectives" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('objectives') border-red-500 @enderror"
                    placeholder="Tujuan yang ingin dicapai">{{ old('objectives', $proposal->objectives ?? '') }}</textarea>
                @error('objectives')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Methodology --}}
            <div>
                <label for="methodology" class="block text-sm font-medium text-gray-700 mb-2">
                    Metodologi/Rencana Pelaksanaan
                </label>
                <textarea name="methodology" id="methodology" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('methodology') border-red-500 @enderror"
                    placeholder="Cara pelaksanaan dan metode yang akan digunakan">{{ old('methodology', $proposal->methodology ?? '') }}</textarea>
                @error('methodology')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Timeline --}}
            <div>
                <label for="timeline" class="block text-sm font-medium text-gray-700 mb-2">
                    Timeline/Jadwal
                </label>
                <textarea name="timeline" id="timeline" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('timeline') border-red-500 @enderror"
                    placeholder="Jadwal dan timeline pelaksanaan">{{ old('timeline', $proposal->timeline ?? '') }}</textarea>
                @error('timeline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Expected Outcomes --}}
            <div>
                <label for="expected_outcomes" class="block text-sm font-medium text-gray-700 mb-2">
                    Hasil yang Diharapkan
                </label>
                <textarea name="expected_outcomes" id="expected_outcomes" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('expected_outcomes') border-red-500 @enderror"
                    placeholder="Output dan outcome yang diharapkan">{{ old('expected_outcomes', $proposal->expected_outcomes ?? '') }}</textarea>
                @error('expected_outcomes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Documents --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>
        <div class="space-y-6">
            {{-- Main Document --}}
            <div>
                <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Dokumen Proposal (PDF/DOC/DOCX)
                </label>
                @if (isset($proposal) && $proposal->document_file)
                    <div class="mb-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            File saat ini: <a href="{{ $proposal->document_url }}" target="_blank"
                                class="font-medium underline">Lihat Dokumen</a>
                        </p>
                    </div>
                @endif
                <input type="file" name="document_file" id="document_file" accept=".pdf,.doc,.docx"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('document_file') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX. Maksimal 10MB.</p>
                @error('document_file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Supporting Documents --}}
            <div>
                <label for="supporting_documents" class="block text-sm font-medium text-gray-700 mb-2">
                    Dokumen Pendukung (Opsional)
                </label>
                @if (isset($proposal) && $proposal->supporting_documents && count($proposal->supporting_documents) > 0)
                    <div class="mb-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-medium text-blue-800 mb-2">Dokumen Pendukung Saat Ini:</p>
                        <ul class="space-y-1">
                            @foreach ($proposal->supporting_documents as $doc)
                                <li class="text-sm text-blue-700">
                                    <a href="{{ Storage::url($doc) }}" target="_blank" class="underline">
                                        {{ basename($doc) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input type="file" name="supporting_documents[]" id="supporting_documents" multiple
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('supporting_documents.*') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, JPG, PNG. Maksimal 5MB per file. Bisa
                    pilih beberapa file.</p>
                @error('supporting_documents.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan Tambahan</h3>
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                Catatan Internal (Tidak ditampilkan ke penerima)
            </label>
            <textarea name="notes" id="notes" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('notes') border-red-500 @enderror"
                placeholder="Catatan internal untuk tim">{{ old('notes', $proposal->notes ?? '') }}</textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Submit Buttons --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.proposals.index') }}"
            class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
            Batal
        </a>
        <button type="submit"
            class="px-6 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg transition-colors">
            {{ isset($proposal) ? 'Update Proposal' : 'Simpan Proposal' }}
        </button>
    </div>
</div>
